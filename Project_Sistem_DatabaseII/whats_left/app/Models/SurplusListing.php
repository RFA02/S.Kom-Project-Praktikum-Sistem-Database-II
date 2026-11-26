<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SurplusListing extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'food_id',
        'listing_date',
        'available_quantity',
        'initial_quantity',
        'discount_percentage',
        'discounted_price',
        'pickup_start_time',
        'pickup_end_time',
        'listing_status',
    ];

    protected function casts(): array
    {
        return [
            'listing_date' => 'date',
            'pickup_start_time' => 'datetime',
            'pickup_end_time' => 'datetime',
            'discount_percentage' => 'decimal:2',
            'discounted_price' => 'decimal:2',
        ];
    }

    public function food(): BelongsTo
    {
        return $this->belongsTo(Food::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Status reservasi yang masih "memegang" stok.
     * Batal mengembalikan stok. Tidak hadir mengembalikan stok jika jam pickup belum berakhir,
     * dan jika sudah berakhir listing sudah kedaluwarsa sehingga stoknya tidak lagi relevan.
     */
    public const CONSUMING_STATUSES = ['pending', 'confirmed', 'completed'];

    public function isPickupOver(): bool
    {
        return $this->pickup_end_time !== null && $this->pickup_end_time->lt(now());
    }

    public function consumedQuantity(): int
    {
        return (int) $this->reservations()
            ->whereIn('reservation_status', self::CONSUMING_STATUSES)
            ->sum('portion_quantity');
    }

    /**
     * Listing yang masih bisa dipesan: aktif, ada stok, dan jam pickup belum berakhir.
     */
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('listing_status', 'active')
            ->where('available_quantity', '>', 0)
            ->where('pickup_end_time', '>=', now());
    }

    /**
     * Listing yang boleh tampil ke publik (consumer): tersedia dan bisnisnya boleh menerima pesanan.
     */
    public function scopeVisibleToPublic(Builder $query): Builder
    {
        return $query->available()
            ->whereHas('food.business', fn ($business) => $business->publiclyOrderable());
    }

    /**
     * Kembalikan stok (pembatalan). Listing sold_out dibuka kembali jika masih dalam jam pickup.
     */
    public function restoreStock(int $quantity): void
    {
        $this->available_quantity += $quantity;

        if ($this->listing_status === 'sold_out') {
            $this->listing_status = $this->isPickupOver() ? 'expired' : 'active';
        }

        $this->save();
    }
}
