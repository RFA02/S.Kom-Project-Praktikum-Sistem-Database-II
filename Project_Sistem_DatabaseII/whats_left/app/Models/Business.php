<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'business_name',
        'business_type',
        'street',
        'city',
        'province',
        'postal_code',
        'phone',
        'verification_status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function foods(): HasMany
    {
        return $this->hasMany(Food::class);
    }

    /**
     * Bisnis yang boleh menerima pesanan publik.
     * Default: semua kecuali "rejected". Jika REQUIRE_VERIFIED_BUSINESS=true: hanya "verified".
     */
    public function scopePubliclyOrderable(Builder $query): Builder
    {
        if (config('whatsleft.require_verified_business')) {
            return $query->where('verification_status', 'verified');
        }

        return $query->where(function ($builder) {
            $builder->whereNull('verification_status')
                ->orWhere('verification_status', '!=', 'rejected');
        });
    }

    public function acceptsPublicOrders(): bool
    {
        if (config('whatsleft.require_verified_business')) {
            return $this->verification_status === 'verified';
        }

        return $this->verification_status !== 'rejected';
    }
}
