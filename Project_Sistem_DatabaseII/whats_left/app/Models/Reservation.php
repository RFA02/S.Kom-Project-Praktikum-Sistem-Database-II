<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'surplus_listing_id',
        'reserved_at',
        'portion_quantity',
        'price_at_reservation',
        'reservation_status',
    ];

    protected function casts(): array
    {
        return [
            'reserved_at' => 'datetime',
            'price_at_reservation' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function surplusListing(): BelongsTo
    {
        return $this->belongsTo(SurplusListing::class);
    }
}
