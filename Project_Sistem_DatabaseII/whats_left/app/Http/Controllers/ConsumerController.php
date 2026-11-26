<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\SurplusListing;
use Illuminate\Http\Request;
use App\Services\StaleReservationSweeper;
use Carbon\Carbon;

class ConsumerController extends Controller
{
    public function landing(Request $request)
    {
        $popularListings = SurplusListing::with(['food.business.user'])
            ->visibleToPublic()
            ->orderByDesc('discount_percentage')
            ->orderBy('listing_date')
            ->limit(8)
            ->get()
            ->map(fn ($listing) => $this->mapListing($listing))
            ->values();

        return view('landing', compact('popularListings'));
    }

    public function explore(Request $request)
    {
        $listings = SurplusListing::with(['food.business.user'])
            ->visibleToPublic()
            ->orderBy('listing_date')
            ->get()
            ->map(fn ($listing) => $this->mapListing($listing))
            ->values();

        $selectedListingId = $request->query('listing');
        $searchQuery = (string) $request->query('q', '');

        return view('consumer.explore', compact('listings', 'selectedListingId', 'searchQuery'));
    }

    public function foodDetails($id)
    {
        $listing = SurplusListing::with(['food.business.user'])
            ->where('id', $id)
            ->visibleToPublic()
            ->firstOrFail();

        $food = $this->mapListing($listing);

        return view('consumer.food-details', compact('food'));
    }

    public function myReservations(Request $request)
    {
        $user = $request->user();
        abort_unless($user->role === 'customer', 403);

        app(StaleReservationSweeper::class)->sweepThrottled();

        $reservations = Reservation::with(['surplusListing.food.business.user'])
            ->where('user_id', $user->id)
            ->orderByDesc('reserved_at')
            ->get()
            ->map(fn ($reservation) => $this->mapReservation($reservation));

        $activeReservations = $reservations
            ->whereIn('statusCode', ['pending', 'confirmed'])
            ->values();

        $historyReservations = $reservations
            ->whereIn('statusCode', ['completed', 'cancelled', 'no_show'])
            ->values();

        $savedPortions = (int) Reservation::where('user_id', $user->id)
            ->where('reservation_status', 'completed')
            ->sum('portion_quantity');

        return view('consumer.my-reservations', compact(
            'user',
            'activeReservations',
            'historyReservations',
            'savedPortions'
        ));
    }

    public function profile(Request $request)
    {
        abort_unless($request->user()->role === 'customer', 403);

        return view('consumer.profile', [
            'user' => $request->user(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        abort_unless($user->role === 'customer', 403);

        $validated = $request->validate([
            'first_name' => 'required|string|max:80',
            'last_name' => 'nullable|string|max:80',
            'phone' => 'nullable|string|max:20',
        ]);

        $validated['last_name'] = $validated['last_name'] ?? '';

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function notifications(Request $request)
    {
        abort_unless($request->user()->role === 'customer', 403);

        $user = $request->user();

        $notifications = $user->notifications()
            ->latest()
            ->limit(50)
            ->get()
            ->map(fn ($notification) => [
                'id' => $notification->id,
                'title' => $notification->data['title'] ?? 'Notifikasi',
                'body' => $notification->data['body'] ?? '',
                'time' => $notification->created_at?->diffForHumans() ?? '',
                'unread' => $notification->read_at === null,
            ]);

        $user->unreadNotifications()->update(['read_at' => now()]);

        return view('consumer.notifications', [
            'user' => $user,
            'notifications' => $notifications,
        ]);
    }

    private function mapListing(SurplusListing $listing): array
    {
        $food = $listing->food;
        $business = $food?->business;

        $category = match ($food?->category) {
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

        $pickupStart = $listing->pickup_start_time
            ? Carbon::parse($listing->pickup_start_time)
            : null;
        $pickupEnd = $listing->pickup_end_time
            ? Carbon::parse($listing->pickup_end_time)
            : null;

        $address = collect([
            $business?->street,
            $business?->city,
            $business?->province,
        ])->filter()->implode(', ');

        return [
            'id' => $listing->id,
            'food_id' => $listing->food_id,
            'title' => $food?->food_name ?? 'Makanan',
            'merchant' => $business?->business_name ?? 'Mitra Whats Left',
            'merchantPhone' => $business?->phone ?? $business?->user?->phone,
            'category' => $category,
            'categorySpec' => $category,
            'categoryIcon' => $categoryIcon,
            'discount' => (float) $listing->discount_percentage,
            'originalPrice' => (int) ($food?->normal_price ?? 0),
            'discountPrice' => (int) $listing->discounted_price,
            'pickupTime' => 'Ambil ' . $listing->listing_date->translatedFormat('d M Y') . ', ' .
                ($pickupStart?->format('H:i') ?? '-') . ' - ' .
                ($pickupEnd?->format('H:i') ?? '-'),
            'pickupLocation' => $address ?: 'Alamat belum tersedia',
            'stock' => (int) $listing->available_quantity,
            'unit' => 'porsi',
            'image' => $food?->image_url,
            'description' => $food?->description ?? '',
            'listingDateInput' => $listing->listing_date->toDateString(),
            'pickupStartInput' => $pickupStart?->format('Y-m-d\TH:i') ?? '',
            'pickupEndInput' => $pickupEnd?->format('Y-m-d\TH:i') ?? '',
            'isWishlist' => false,
        ];
    }

    private function mapReservation(Reservation $reservation): array
    {
        $listing = $reservation->surplusListing;
        $food = $listing?->food;
        $business = $food?->business;

        $statusLabels = [
            'pending' => 'Menunggu Konfirmasi',
            'confirmed' => 'Akan Diambil',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'no_show' => 'Tidak Hadir',
        ];

        $pickupStart = $listing?->pickup_start_time
            ? Carbon::parse($listing->pickup_start_time)->format('H:i')
            : '-';
        $pickupEnd = $listing?->pickup_end_time
            ? Carbon::parse($listing->pickup_end_time)->format('H:i')
            : '-';

        $pickupDate = $listing?->listing_date;
        $address = collect([
            $business?->street,
            $business?->city,
            $business?->province,
        ])->filter()->implode(', ');

        return [
            'id' => $reservation->id,
            'title' => $food?->food_name ?? 'Makanan',
            'merchant' => $business?->business_name ?? 'Mitra Whats Left',
            'merchantPhone' => $business?->phone ?? $business?->user?->phone,
            'status' => $statusLabels[$reservation->reservation_status] ?? $reservation->reservation_status,
            'statusKey' => $statusLabels[$reservation->reservation_status] ?? $reservation->reservation_status,
            'statusCode' => $reservation->reservation_status,
            'statusSubtext' => $statusLabels[$reservation->reservation_status] ?? $reservation->reservation_status,
            'dateLabel' => $pickupDate ? $pickupDate->translatedFormat('d M Y') : '-',
            'pickupTime' => $pickupStart . ' - ' . $pickupEnd,
            'pickupAddress' => $address ?: 'Alamat belum tersedia',
            'itemsCount' => 1,
            'qty' => (int) $reservation->portion_quantity,
            'unit' => 'porsi',
            'originalPrice' => (int) ($food?->normal_price ?? 0),
            'discountPrice' => (int) $reservation->price_at_reservation,
            'bookingCode' => 'RES-' . str_pad($reservation->id, 6, '0', STR_PAD_LEFT),
            'createdAt' => $reservation->reserved_at?->translatedFormat('d M Y, H.i') ?? '-',
            'image' => $food?->image_url,
            'categoryIcon' => match ($food?->category) {
                'Main Course' => '🍱',
                'Bread & Pastry' => '🥐',
                'Beverage' => '🥤',
                'Snack' => '🍪',
                'Dessert' => '🍰',
                default => '🍽️',
            },
            'canCancel' => $reservation->reservation_status === 'pending',
        ];
    }
}
