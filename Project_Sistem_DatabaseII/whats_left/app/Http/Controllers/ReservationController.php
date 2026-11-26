<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Reservation;
use App\Models\SurplusListing;
use App\Notifications\ReservationUpdated;
use App\Services\StaleReservationSweeper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    private function notifySafely($recipient, string $title, string $body, int $reservationId): void
    {
        if (!$recipient) {
            return;
        }

        try {
            $recipient->notify(new ReservationUpdated($title, $body, $reservationId));
        } catch (\Throwable $e) {
            report($e);
        }
    }

    private function getAccessibleReservation(Request $request, $id)
    {
        $user = $request->user();

        if ($user->role === 'customer') {
            return Reservation::where('id', $id)
                ->where('user_id', $user->id)
                ->first();
        }

        if ($user->role === 'business') {
            $business = $user->business;

            if (!$business) {
                return null;
            }

            return Reservation::where('id', $id)
                ->whereHas('surplusListing.food', function ($query) use ($business) {
                    $query->where('business_id', $business->id);
                })
                ->first();
        }

        return null;
    }

    public function businessPage(Request $request)
    {
        $user = $request->user();

        abort_unless($user->role === 'business', 403, 'Hanya akun Business yang dapat melihat reservasi.');
        $business = $user->business;

        abort_if(!$business, 404, 'Data Business tidak ditemukan.');

        app(StaleReservationSweeper::class)->sweepThrottled();

        $reservations = Reservation::with(['user', 'surplusListing.food'])
            ->whereHas('surplusListing.food', function ($query) use ($business) {
                $query->where('business_id', $business->id);
            })
            ->orderByDesc('reserved_at')
            ->get()
            ->map(function ($reservation) {
                $listing = $reservation->surplusListing;
                $food = $listing?->food;
                $pickupStart = $listing?->pickup_start_time
                    ? Carbon::parse($listing->pickup_start_time)->format('H:i')
                    : '-';
                $pickupEnd = $listing?->pickup_end_time
                    ? Carbon::parse($listing->pickup_end_time)->format('H:i')
                    : '-';

                $statusLabels = [
                    'pending' => 'Menunggu',
                    'confirmed' => 'Dikonfirmasi',
                    'completed' => 'Selesai',
                    'cancelled' => 'Dibatalkan',
                    'no_show' => 'Tidak Hadir',
                ];

                $pickupDate = $listing?->listing_date;

                return [
                    'id' => 'RES-' . str_pad($reservation->id, 6, '0', STR_PAD_LEFT),
                    'databaseId' => $reservation->id,
                    'createdAt' => $reservation->reserved_at
                        ? $reservation->reserved_at->translatedFormat('d M Y, H.i')
                        : '-',
                    'sortTs' => $reservation->reserved_at?->timestamp ?? 0,
                    'createdAtTimestamp' => $reservation->reserved_at?->timestamp ?? 0,
                    'customerName' => trim(($reservation->user?->first_name ?? '') . ' ' . ($reservation->user?->last_name ?? '')) ?: 'Pelanggan',
                    'customerPhone' => $reservation->user?->phone ?: '—',
                    'avatar' => null,
                    'pickupDate' => $pickupDate ? $pickupDate->translatedFormat('d M Y') : '-',
                    'pickupDay' => $pickupDate ? (int) $pickupDate->format('j') : null,
                    'pickupMonth' => $pickupDate ? (int) $pickupDate->format('n') - 1 : null,
                    'pickupYear' => $pickupDate ? (int) $pickupDate->format('Y') : null,
                    'pickupSlot' => $pickupStart . ' - ' . $pickupEnd,
                    'status' => $statusLabels[$reservation->reservation_status] ?? $reservation->reservation_status,
                    'statusCode' => $reservation->reservation_status,
                    'total' => (int) round((int) $reservation->portion_quantity * (float) $reservation->price_at_reservation),
                    'itemCount' => (int) $reservation->portion_quantity,
                    'foodName' => $food?->food_name ?? 'Makanan',
                    'paymentMethod' => '—',
                    'paymentStatus' => 'Tidak tersedia',
                    'items' => [[
                        'name' => $food?->food_name ?? 'Makanan',
                        'qty' => (int) $reservation->portion_quantity,
                        'price' => (int) $reservation->price_at_reservation,
                    ]],
                    'notes' => 'Catatan pelanggan tidak tersedia pada schema database.',
                ];
            })
            ->values();

        return view('business.reservations-management', compact('business', 'reservations'));
    }

    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'customer') {
            $reservations = Reservation::where('user_id', $user->id)->get();
        } elseif ($user->role === 'business') {
            $business = $user->business;

            if (!$business) {
                return response()->json([
                    'message' => 'Data Business tidak ditemukan.',
                ], 404);
            }

            $reservations = Reservation::whereHas('surplusListing.food', function ($query) use ($business) {
                $query->where('business_id', $business->id);
            })->get();
        } else {
            return response()->json([
                'message' => 'Anda tidak memiliki akses.',
            ], 403);
        }

        return response()->json([
            'message' => 'Data reservasi berhasil diambil.',
            'data' => $reservations,
        ], 200);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'customer') {
            return response()->json([
                'message' => 'Hanya Consumer yang dapat membuat reservasi.',
            ], 403);
        }

        $validated = $request->validate([
            'surplus_listing_id' => 'required|integer',
            'portion_quantity' => 'required|integer|min:1|max:10',
        ]);

        $reservation = DB::transaction(function () use ($validated, $user) {
            $listing = SurplusListing::with('food.business')
                ->where('id', $validated['surplus_listing_id'])
                ->lockForUpdate()
                ->first();

            if (!$listing || $listing->listing_status !== 'active') {
                return null;
            }

            if (!$listing->food?->business?->acceptsPublicOrders()) {
                return null;
            }

            if ($listing->isPickupOver()) {
                return null;
            }

            if ($validated['portion_quantity'] > $listing->available_quantity) {
                return false;
            }

            $reservation = Reservation::create([
                'user_id' => $user->id,
                'surplus_listing_id' => $listing->id,
                'reserved_at' => now(),
                'portion_quantity' => $validated['portion_quantity'],
                'price_at_reservation' => $listing->discounted_price,
                'reservation_status' => 'pending',
            ]);

            $listing->available_quantity -= $validated['portion_quantity'];

            if ($listing->available_quantity == 0) {
                $listing->listing_status = 'sold_out';
            }

            $listing->save();

            return $reservation;
        });

        if ($reservation === null) {
            return response()->json([
                'message' => 'Listing tidak ditemukan atau tidak aktif.',
            ], 404);
        }

        if ($reservation === false) {
            return response()->json([
                'message' => 'Jumlah makanan yang tersedia tidak mencukupi.',
            ], 422);
        }

        $reservation->load('surplusListing.food.business.user');
        $foodName = $reservation->surplusListing?->food?->food_name ?? 'makanan';
        $this->notifySafely(
            $reservation->surplusListing?->food?->business?->user,
            'Reservasi baru',
            'Reservasi baru untuk ' . $foodName . ' (' . $reservation->portion_quantity . ' porsi) menunggu konfirmasi.',
            $reservation->id
        );

        return response()->json([
            'message' => 'Reservasi berhasil dibuat.',
            'data' => $reservation,
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $reservation = $this->getAccessibleReservation($request, $id);

        if (!$reservation) {
            return response()->json([
                'message' => 'Data reservasi tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'message' => 'Detail reservasi berhasil diambil.',
            'data' => $reservation,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();

        if (!in_array($user->role, ['customer', 'business'], true)) {
            return response()->json([
                'message' => 'Anda tidak memiliki akses.',
            ], 403);
        }

        if ($user->role === 'customer') {
            $validated = $request->validate([
                'reservation_status' => 'required|in:cancelled',
            ]);
        } else {
            $validated = $request->validate([
                'reservation_status' => 'required|in:confirmed,completed,cancelled,no_show',
            ]);
        }

        $result = DB::transaction(function () use ($user, $id, $validated) {
            $query = Reservation::where('id', $id);

            if ($user->role === 'customer') {
                $query->where('user_id', $user->id);
            } else {
                $business = $user->business;

                if (!$business) {
                    return ['error' => 'Data Business tidak ditemukan.', 'status' => 404];
                }

                $query->whereHas('surplusListing.food', function ($builder) use ($business) {
                    $builder->where('business_id', $business->id);
                });
            }

            // Urutan lock SELALU listing -> reservasi (sama dengan store & sweeper) agar tidak deadlock.
            $listingId = (clone $query)->value('surplus_listing_id');

            if ($listingId) {
                SurplusListing::where('id', $listingId)->lockForUpdate()->first();
            }

            $reservation = $query->lockForUpdate()->first();

            if (!$reservation) {
                return ['error' => 'Data reservasi tidak ditemukan.', 'status' => 404];
            }

            $oldStatus = $reservation->reservation_status;
            $newStatus = $validated['reservation_status'];

            if ($user->role === 'customer') {
                $allowedTransitions = [
                    'pending' => ['cancelled'],
                    'confirmed' => [],
                    'completed' => [],
                    'cancelled' => [],
                    'no_show' => [],
                ];
            } else {
                $allowedTransitions = [
                    'pending' => ['confirmed', 'cancelled'],
                    'confirmed' => ['completed', 'cancelled', 'no_show'],
                    'completed' => [],
                    'cancelled' => [],
                    'no_show' => [],
                ];
            }

            if (!in_array($newStatus, $allowedTransitions[$oldStatus] ?? [], true)) {
                return [
                    'error' => 'Perubahan status tidak diizinkan dari ' . $oldStatus . ' ke ' . $newStatus . '.',
                    'status' => 422,
                ];
            }

            // Pembatalan selalu mengembalikan stok. Tidak hadir hanya mengembalikan stok
            // jika jam pickup belum berakhir (makanan masih bisa dijual ke orang lain).
            if (
                in_array($newStatus, ['cancelled', 'no_show'], true) &&
                in_array($oldStatus, ['pending', 'confirmed'], true)
            ) {
                $listing = SurplusListing::where('id', $reservation->surplus_listing_id)
                    ->lockForUpdate()
                    ->first();

                if ($listing && ($newStatus === 'cancelled' || !$listing->isPickupOver())) {
                    $listing->restoreStock((int) $reservation->portion_quantity);
                }
            }

            $reservation->reservation_status = $newStatus;
            $reservation->save();

            return ['reservation' => $reservation->fresh()];
        });

        if (isset($result['error'])) {
            return response()->json([
                'message' => $result['error'],
            ], $result['status']);
        }

        $updated = $result['reservation']->load('user', 'surplusListing.food.business.user');
        $foodName = $updated->surplusListing?->food?->food_name ?? 'makanan';
        $statusText = [
            'confirmed' => 'dikonfirmasi',
            'completed' => 'selesai',
            'cancelled' => 'dibatalkan',
            'no_show' => 'ditandai tidak hadir',
        ][$updated->reservation_status] ?? $updated->reservation_status;

        if ($user->role === 'business') {
            $this->notifySafely(
                $updated->user,
                'Status reservasi diperbarui',
                'Reservasi ' . $foodName . ' kamu ' . $statusText . '.',
                $updated->id
            );
        } else {
            $this->notifySafely(
                $updated->surplusListing?->food?->business?->user,
                'Reservasi dibatalkan',
                'Pelanggan membatalkan reservasi ' . $foodName . '.',
                $updated->id
            );
        }

        return response()->json([
            'message' => 'Status reservasi berhasil diperbarui.',
            'data' => $result['reservation'],
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        return response()->json([
            'message' => 'Reservasi tidak dapat dihapus secara permanen. Gunakan perubahan status agar histori transaksi tetap tersimpan.',
        ], 405);
    }
}
