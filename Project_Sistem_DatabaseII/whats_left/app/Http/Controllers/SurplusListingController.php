<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Food;
use App\Models\SurplusListing;
use App\Http\Controllers\Concerns\ResolvesBusiness;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SurplusListingController extends Controller
{
    use ResolvesBusiness;

    public function page(Request $request)
    {
        $business = $this->getBusiness($request);

        $foods = $business->foods()->active()->orderBy('food_name')->get();
        $foodOptions = $foods->map(function ($food) {
            return [
                'id' => $food->id,
                'name' => $food->food_name,
                'category' => $food->category,
                'normalPrice' => (int) $food->normal_price,
            ];
        })->values();

        $listings = SurplusListing::with(['food', 'reservations'])
            ->whereHas('food', function ($query) use ($business) {
                $query->where('business_id', $business->id);
            })
            ->orderByDesc('listing_date')
            ->get()
            ->map(function ($listing) {
                $food = $listing->food;
                $validReservations = $listing->reservations->whereIn('reservation_status', [
                    'pending',
                    'confirmed',
                    'completed',
                ]);
                $completedReservations = $listing->reservations->where('reservation_status', 'completed');

                $pickupStart = $listing->pickup_start_time
                    ? Carbon::parse($listing->pickup_start_time)
                    : null;
                $pickupEnd = $listing->pickup_end_time
                    ? Carbon::parse($listing->pickup_end_time)
                    : null;

                $statusLabels = [
                    'active' => 'Aktif',
                    'sold_out' => 'Stok Habis',
                    'closed' => 'Ditutup',
                    'expired' => 'Kedaluwarsa',
                ];

                $categoryLabel = match ($food?->category) {
                    'Main Course' => 'Makanan Berat',
                    'Bread & Pastry' => 'Roti & Kue',
                    'Beverage' => 'Minuman',
                    'Snack' => 'Snack',
                    'Dessert' => 'Dessert',
                    default => 'Lainnya',
                };

                $categoryIcon = match ($food?->category) {
                    'Main Course' => '🍱',
                    'Bread & Pastry' => '🥐',
                    'Beverage' => '🥤',
                    'Snack' => '🍪',
                    'Dessert' => '🍰',
                    default => '🍽️',
                };

                return [
                    'id' => $listing->id,
                    'food_id' => $listing->food_id,
                    'title' => $food?->food_name ?? 'Makanan',
                    'category' => $categoryLabel,
                    'categoryIcon' => $categoryIcon,
                    'status' => $statusLabels[$listing->listing_status] ?? $listing->listing_status,
                    'statusCode' => $listing->listing_status,
                    'date' => $listing->listing_date->translatedFormat('d M Y'),
                    'listingDateInput' => $listing->listing_date->toDateString(),
                    'pickupTime' => ($pickupStart?->format('H:i') ?? '-') . ' - ' . ($pickupEnd?->format('H:i') ?? '-'),
                    'pickupStartInput' => $pickupStart?->format('Y-m-d\TH:i') ?? '',
                    'pickupEndInput' => $pickupEnd?->format('Y-m-d\TH:i') ?? '',
                    'stock' => (int) $listing->available_quantity,
                    'initialQuantity' => (int) ($listing->initial_quantity ?? $listing->available_quantity),
                    'unit' => 'porsi',
                    'price' => (int) $listing->discounted_price,
                    'originalPrice' => (int) ($food?->normal_price ?? 0),
                    'discountPercentage' => (float) $listing->discount_percentage,
                    'discountBadge' => 'Diskon ' . number_format((float) $listing->discount_percentage, 0) . '%',
                    'reservationsCount' => $validReservations->count(),
                    'completedPortions' => (int) $completedReservations->sum('portion_quantity'),
                    'completedRevenue' => (int) round($completedReservations->sum(function ($reservation) {
                        return (int) $reservation->portion_quantity * (float) $reservation->price_at_reservation;
                    })),
                    'image' => $food?->image_url,
                    'freshness' => null,
                    'pickupAddress' => null,
                ];
            })
            ->values();

        $listingStats = [
            'total' => $listings->count(),
            'active' => $listings->where('statusCode', 'active')->count(),
            'availableQuantity' => (int) $listings->where('statusCode', 'active')->sum('stock'),
            'reservations' => (int) $listings->sum('reservationsCount'),
            'savedPortions' => (int) $listings->sum('completedPortions'),
            'revenue' => (int) $listings->sum('completedRevenue'),
        ];

        return view('business.surplus-listings', compact('business', 'foodOptions', 'listings', 'listingStats'));
    }

    public function index(Request $request)
    {
        $business = $this->getBusiness($request);

        $listings = SurplusListing::whereHas('food', function ($query) use ($business) {
            $query->where('business_id', $business->id);
        })->get();

        return response()->json([
            'message' => 'Data surplus berhasil diambil.',
            'data' => $listings,
        ], 200);
    }

    public function store(Request $request)
    {
        $business = $this->getBusiness($request);

        if (!$business->acceptsPublicOrders()) {
            return response()->json([
                'message' => 'Akun bisnis Anda tidak dapat membuat listing karena status verifikasinya tidak mengizinkan (ditolak, atau menunggu verifikasi admin).',
            ], 403);
        }

        $validated = $request->validate([
            'food_id' => 'required|integer',
            'listing_date' => 'required|date|after_or_equal:today',
            'available_quantity' => 'required|integer|min:1',
            'discount_percentage' => 'required|numeric|min:1|max:90',
            'pickup_start_time' => 'required|date',
            'pickup_end_time' => 'required|date|after:pickup_start_time',
        ]);

        if (Carbon::parse($validated['pickup_end_time'])->lte(now())) {
            return response()->json([
                'message' => 'Waktu akhir pickup harus berada di masa depan.',
            ], 422);
        }

        $listingDate = Carbon::parse($validated['listing_date'])->toDateString();

        if (
            Carbon::parse($validated['pickup_start_time'])->toDateString() !== $listingDate ||
            Carbon::parse($validated['pickup_end_time'])->toDateString() !== $listingDate
        ) {
            return response()->json([
                'message' => 'Jam pickup harus berada pada tanggal listing yang sama.',
            ], 422);
        }

        $food = Food::where('id', $validated['food_id'])
            ->where('business_id', $business->id)
            ->whereNull('archived_at')
            ->first();

        if (!$food) {
            return response()->json([
                'message' => 'Makanan tidak ditemukan atau bukan milik Business Anda.',
            ], 404);
        }

        $validated['initial_quantity'] = (int) $validated['available_quantity'];
        $validated['discounted_price'] = (int) round(
            (float) $food->normal_price * (1 - ((float) $validated['discount_percentage'] / 100))
        );
        $validated['listing_status'] = 'active';

        // Cek duplikat + insert dalam satu transaksi dengan lock pada baris makanan.
        // Hanya listing AKTIF / habis yang dianggap duplikat; listing ditutup/kedaluwarsa tidak memblokir.
        $listing = DB::transaction(function () use ($food, $listingDate, $validated) {
            Food::where('id', $food->id)->lockForUpdate()->first();

            $exists = SurplusListing::where('food_id', $food->id)
                ->whereDate('listing_date', $listingDate)
                ->whereIn('listing_status', ['active', 'sold_out'])
                ->exists();

            return $exists ? null : $food->surplusListings()->create($validated);
        });

        if (!$listing) {
            return response()->json([
                'message' => 'Sudah ada listing aktif untuk makanan ini pada tanggal tersebut. Edit listing yang ada atau tutup terlebih dahulu.',
            ], 422);
        }

        return response()->json([
            'message' => 'Listing surplus berhasil ditambahkan.',
            'data' => $listing,
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $business = $this->getBusiness($request);

        $listing = SurplusListing::where('id', $id)
            ->whereHas('food', function ($query) use ($business) {
                $query->where('business_id', $business->id);
            })
            ->first();

        if (!$listing) {
            return response()->json([
                'message' => 'Listing surplus tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'message' => 'Detail listing surplus berhasil diambil.',
            'data' => $listing,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $business = $this->getBusiness($request);

        $validated = $request->validate([
            'food_id' => 'sometimes|required|integer',
            'listing_date' => 'sometimes|required|date',
            'available_quantity' => 'sometimes|required|integer|min:0',
            'discount_percentage' => 'sometimes|required|numeric|min:1|max:90',
            'pickup_start_time' => 'sometimes|required|date',
            'pickup_end_time' => 'sometimes|required|date',
            'listing_status' => 'sometimes|required|in:active,closed',
        ]);

        $result = DB::transaction(function () use ($business, $id, $validated) {
            $listing = SurplusListing::with('food')
                ->where('id', $id)
                ->whereHas('food', function ($query) use ($business) {
                    $query->where('business_id', $business->id);
                })
                ->lockForUpdate()
                ->first();

            if (!$listing) {
                return ['error' => 'Listing surplus tidak ditemukan.', 'status' => 404];
            }

            if ($listing->listing_status === 'expired') {
                return ['error' => 'Listing yang sudah kedaluwarsa tidak dapat diubah. Buat listing baru.', 'status' => 422];
            }

            $food = $listing->food;

            if (isset($validated['food_id'])) {
                $newFood = $business->foods()->active()->find($validated['food_id']);

                if (!$newFood) {
                    return ['error' => 'Makanan tidak ditemukan atau bukan milik Business Anda.', 'status' => 404];
                }

                if ($newFood->id !== $listing->food_id && $listing->reservations()->exists()) {
                    return [
                        'error' => 'Makanan pada listing tidak dapat diganti karena sudah memiliki histori reservasi.',
                        'status' => 409,
                    ];
                }

                $food = $newFood;
            }

            if (
                isset($validated['listing_date']) &&
                $validated['listing_date'] !== $listing->listing_date?->toDateString() &&
                Carbon::parse($validated['listing_date'])->startOfDay()->lt(today())
            ) {
                return [
                    'error' => 'Tanggal listing tidak boleh di masa lalu.',
                    'status' => 422,
                ];
            }

            $effectiveStart = $validated['pickup_start_time'] ?? $listing->pickup_start_time;
            $effectiveEnd = $validated['pickup_end_time'] ?? $listing->pickup_end_time;

            if (
                $effectiveStart &&
                $effectiveEnd &&
                Carbon::parse($effectiveEnd)->lte(Carbon::parse($effectiveStart))
            ) {
                return [
                    'error' => 'Waktu akhir pickup harus setelah waktu mulai pickup.',
                    'status' => 422,
                ];
            }

            $effectiveDate = Carbon::parse($validated['listing_date'] ?? $listing->listing_date)->toDateString();

            if (
                (isset($validated['listing_date']) || isset($validated['pickup_start_time']) || isset($validated['pickup_end_time'])) &&
                $effectiveStart && $effectiveEnd &&
                (
                    Carbon::parse($effectiveStart)->toDateString() !== $effectiveDate ||
                    Carbon::parse($effectiveEnd)->toDateString() !== $effectiveDate
                )
            ) {
                return [
                    'error' => 'Jam pickup harus berada pada tanggal listing yang sama.',
                    'status' => 422,
                ];
            }

            if (
                (isset($validated['listing_date']) || isset($validated['food_id'])) &&
                SurplusListing::where('food_id', $food->id)
                    ->whereDate('listing_date', $effectiveDate)
                    ->where('id', '!=', $listing->id)
                    ->whereIn('listing_status', ['active', 'sold_out'])
                    ->exists()
            ) {
                return [
                    'error' => 'Listing untuk makanan ini pada tanggal tersebut sudah ada.',
                    'status' => 422,
                ];
            }

            $oldStatus = $listing->listing_status;
            $newStatus = $validated['listing_status'] ?? $oldStatus;

            if ($newStatus !== $oldStatus) {
                $allowedTransitions = [
                    'active' => ['closed'],
                    'closed' => ['active'],
                    'sold_out' => [],
                    'expired' => [],
                ];

                if (!in_array($newStatus, $allowedTransitions[$oldStatus] ?? [], true)) {
                    return [
                        'error' => 'Perubahan status listing tidak diizinkan.',
                        'status' => 422,
                    ];
                }

                if ($newStatus === 'active' && $effectiveEnd && Carbon::parse($effectiveEnd)->lte(now())) {
                    return [
                        'error' => 'Listing tidak dapat diaktifkan karena waktu pickup sudah berakhir.',
                        'status' => 422,
                    ];
                }
            }

            $discountPercentage = (float) ($validated['discount_percentage'] ?? $listing->discount_percentage);
            $validated['discounted_price'] = (int) round(
                (float) $food->normal_price * (1 - ($discountPercentage / 100))
            );

            $newQuantity = (int) ($validated['available_quantity'] ?? $listing->available_quantity);

            if ($oldStatus === 'closed' && $newStatus === 'active' && $newQuantity <= 0) {
                return [
                    'error' => 'Tambahkan stok minimal 1 porsi sebelum mengaktifkan listing.',
                    'status' => 422,
                ];
            }

            if ($newStatus !== 'closed' && $newQuantity <= 0 && $newStatus === 'active') {
                $validated['listing_status'] = 'sold_out';
            } elseif ($oldStatus === 'sold_out' && $newQuantity > 0 && !isset($validated['listing_status'])) {
                $validated['listing_status'] = ($effectiveEnd && Carbon::parse($effectiveEnd)->lte(now()))
                    ? 'expired'
                    : 'active';
            }

            $finalStatus = $validated['listing_status'] ?? $oldStatus;

            if ($finalStatus === 'active' && $oldStatus !== 'active' && !$business->acceptsPublicOrders()) {
                return [
                    'error' => 'Akun bisnis Anda belum diverifikasi atau ditolak admin, sehingga listing tidak dapat diaktifkan.',
                    'status' => 403,
                ];
            }

            // Jaga invarian: stok awal = sisa stok + porsi yang sudah terpakai.
            if (isset($validated['available_quantity'])) {
                $validated['initial_quantity'] = $newQuantity + $listing->consumedQuantity();
            }

            $listing->update($validated);

            return ['listing' => $listing->fresh('food')];
        });

        if (isset($result['error'])) {
            return response()->json([
                'message' => $result['error'],
            ], $result['status']);
        }

        return response()->json([
            'message' => 'Listing surplus berhasil diperbarui.',
            'data' => $result['listing'],
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        $business = $this->getBusiness($request);

        $result = DB::transaction(function () use ($business, $id) {
            $listing = SurplusListing::where('id', $id)
                ->whereHas('food', fn ($query) => $query->where('business_id', $business->id))
                ->lockForUpdate()
                ->first();

            if (!$listing) {
                return ['message' => 'Listing surplus tidak ditemukan.', 'status' => 404];
            }

            if ($listing->reservations()->exists()) {
                return [
                    'message' => 'Listing tidak dapat dihapus karena sudah memiliki histori reservasi. Tutup listing untuk menghentikan penawaran tanpa menghapus histori.',
                    'status' => 409,
                ];
            }

            $listing->delete();

            return ['message' => 'Listing surplus berhasil dihapus.', 'status' => 200];
        });

        return response()->json(['message' => $result['message']], $result['status']);
    }
}
