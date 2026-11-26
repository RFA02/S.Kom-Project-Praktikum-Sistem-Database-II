<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\SurplusListing;
use App\Notifications\ReservationUpdated;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Merapikan data yang jam pickup-nya sudah lewat:
 *  - listing active/sold_out        -> expired
 *  - reservasi pending              -> cancelled (stok dikembalikan ke hitungan listing)
 *  - reservasi confirmed (tak diambil) -> no_show
 */
class StaleReservationSweeper
{
    /**
     * @return array{listings:int, cancelled:int, no_show:int}
     */
    public function sweep(): array
    {
        $notifications = [];
        $cancelled = 0;
        $noShow = 0;

        $expiredListings = DB::transaction(function () use (&$notifications, &$cancelled, &$noShow) {
            // 1) Kunci listing yang punya reservasi menggantung (urutan id => konsisten, tanpa deadlock).
            $listingIds = SurplusListing::where('pickup_end_time', '<', now())
                ->whereHas('reservations', fn ($q) => $q->whereIn('reservation_status', ['pending', 'confirmed']))
                ->orderBy('id')
                ->pluck('id');

            if ($listingIds->isNotEmpty()) {
                SurplusListing::whereIn('id', $listingIds)->orderBy('id')->lockForUpdate()->get();
            }

            // 2) Baru kunci reservasinya.
            $stale = Reservation::with('surplusListing.food')
                ->whereIn('surplus_listing_id', $listingIds)
                ->whereIn('reservation_status', ['pending', 'confirmed'])
                ->lockForUpdate()
                ->get();

            foreach ($stale as $reservation) {
                $foodName = $reservation->surplusListing?->food?->food_name ?? 'makanan';

                if ($reservation->reservation_status === 'pending') {
                    $reservation->reservation_status = 'cancelled';
                    $cancelled++;

                    SurplusListing::where('id', $reservation->surplus_listing_id)
                        ->increment('available_quantity', $reservation->portion_quantity);

                    $title = 'Reservasi dibatalkan otomatis';
                    $body = 'Reservasi ' . $foodName . ' dibatalkan otomatis karena jam pickup sudah berakhir sebelum dikonfirmasi.';
                } else {
                    $reservation->reservation_status = 'no_show';
                    $noShow++;

                    $title = 'Pesanan tidak diambil';
                    $body = 'Reservasi ' . $foodName . ' ditandai tidak hadir karena jam pickup sudah berakhir.';
                }

                $reservation->save();
                $notifications[] = [$reservation->user_id, $title, $body, $reservation->id];
            }

            return SurplusListing::whereIn('listing_status', ['active', 'sold_out'])
                ->where('pickup_end_time', '<', now())
                ->update(['listing_status' => 'expired']);
        });

        foreach ($notifications as [$userId, $title, $body, $reservationId]) {
            try {
                \App\Models\User::find($userId)?->notify(new ReservationUpdated($title, $body, $reservationId));
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return ['listings' => (int) $expiredListings, 'cancelled' => $cancelled, 'no_show' => $noShow];
    }

    /**
     * Versi ringan untuk dipanggil dari halaman: maksimal 1x per menit.
     * Menjadi pengaman jika scheduler (cron / schedule:work) tidak berjalan, misalnya di Laragon Windows.
     */
    public function sweepThrottled(): void
    {
        if (! Cache::add('whatsleft:stale-sweep', 1, 60)) {
            return;
        }

        try {
            $this->sweep();
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
