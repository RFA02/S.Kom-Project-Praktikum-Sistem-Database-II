<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Food extends Model
{
    protected $table = 'foods';

    public $timestamps = false;

    protected $fillable = [
        'business_id',
        'food_name',
        'description',
        'normal_price',
        'category',
        'image_path',
    ];

    protected $appends = ['image_url'];

    protected function imageUrl(): Attribute
    {
        return Attribute::get(
            fn () => $this->image_path ? asset('storage/' . $this->image_path) : null
        );
    }

    protected function casts(): array
    {
        return [
            'normal_price' => 'decimal:2',
            'archived_at' => 'datetime',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function productionRecords(): HasMany
    {
        return $this->hasMany(ProductionRecord::class);
    }

    public function surplusListings(): HasMany
    {
        return $this->hasMany(SurplusListing::class);
    }

    // Makanan aktif = belum diarsipkan. Makanan yang diarsipkan tetap ada agar riwayat tidak rusak.
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('archived_at');
    }

    public function scopeArchived(Builder $query): Builder
    {
        return $query->whereNotNull('archived_at');
    }

    public function hasHistory(): bool
    {
        return $this->productionRecords()->exists() || $this->surplusListings()->exists();
    }
}
