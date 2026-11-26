<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\ProductionRecord;
use App\Models\Reservation;
use App\Models\SurplusListing;
use App\Services\StaleReservationSweeper;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BusinessDashboardController extends Controller
{
    public function index(Request $request)
    {
        abort_unless($request->user()->role === 'business', 403);

        $business = Business::where('user_id', $request->user()->id)->firstOrFail();

        app(StaleReservationSweeper::class)->sweepThrottled();

        $today = Carbon::today();
        $period = $request->query('period', 'week_current');

        $periods = [
            'today' => [
                'start' => $today->copy(),
                'end' => $today->copy(),
                'label' => 'Hari ini (' . $today->translatedFormat('d M Y') . ')',
            ],
            'week_current' => [
                'start' => $today->copy()->startOfWeek(Carbon::MONDAY),
                'end' => $today->copy(),
                'label' => $today->copy()->startOfWeek(Carbon::MONDAY)->translatedFormat('d M Y') . ' - ' . $today->translatedFormat('d M Y'),
            ],
            'month_current' => [
                'start' => $today->copy()->startOfMonth(),
                'end' => $today->copy(),
                'label' => 'Bulan ini (' . $today->translatedFormat('F Y') . ')',
            ],
        ];

        if (!array_key_exists($period, $periods)) {
            $period = 'week_current';
        }

        $startDate = $periods[$period]['start'];
        $endDate = $periods[$period]['end'];

        $businessReservations = Reservation::whereHas('surplusListing.food', function ($query) use ($business) {
            $query->where('business_id', $business->id);
        });

        $periodReservations = (clone $businessReservations)
            ->whereBetween('reserved_at', [
                $startDate->copy()->startOfDay(),
                $endDate->copy()->endOfDay(),
            ])
            ->get();

        $validReservations = $periodReservations->whereIn('reservation_status', [
            'pending',
            'confirmed',
            'completed',
        ]);
        $completedReservations = $periodReservations->where('reservation_status', 'completed');

        $totalSaved = (int) $completedReservations->sum('portion_quantity');
        $totalOrders = $validReservations->count();
        $totalRevenue = (int) round($completedReservations->sum(function ($reservation) {
            return (int) $reservation->portion_quantity * (float) $reservation->price_at_reservation;
        }));
        $totalCustomers = $validReservations->pluck('user_id')->filter()->unique()->count();
        $pendingReservationsCount = $periodReservations->where('reservation_status', 'pending')->count();

        $surplusFoods = SurplusListing::with('food')
            ->withSum([
                'reservations as reserved_portions' => function ($query) {
                    $query->whereIn('reservation_status', ['pending', 'confirmed', 'completed']);
                },
            ], 'portion_quantity')
            ->whereHas('food', function ($query) use ($business) {
                $query->where('business_id', $business->id);
            })
            ->available()
            ->orderByDesc('listing_date')
            ->limit(4)
            ->get()
            ->map(function ($listing) {
                $pickupStart = $listing->pickup_start_time
                    ? Carbon::parse($listing->pickup_start_time)->format('H:i')
                    : '-';
                $pickupEnd = $listing->pickup_end_time
                    ? Carbon::parse($listing->pickup_end_time)->format('H:i')
                    : '-';

                return [
                    'id' => $listing->id,
                    'title' => $listing->food?->food_name ?? 'Makanan',
                    'stock' => (int) $listing->available_quantity,
                    'unit' => 'porsi',
                    'price' => (int) $listing->discounted_price,
                    'pickupTime' => 'Ambil ' . $listing->listing_date->translatedFormat('d M Y') . ', ' . $pickupStart . ' - ' . $pickupEnd,
                    'status' => 'Tersedia',
                    'reserved' => (int) ($listing->reserved_portions ?? 0),
                    'image' => $listing->food?->image_url,
                ];
            });

        $productionRecords = ProductionRecord::whereHas('food', function ($query) use ($business) {
                $query->where('business_id', $business->id);
            })
            ->whereBetween('record_date', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ])
            ->get();

        $recordsByDate = $productionRecords->groupBy(function ($record) {
            return $record->record_date->toDateString();
        });

        $totalProduction = (int) $productionRecords->sum('production_quantity');
        $totalRegularSold = (int) $productionRecords->sum('regular_sold_quantity');
        $totalSurplus = (int) $productionRecords->sum('surplus_quantity');
        $totalWaste = (int) $productionRecords->sum('waste_quantity');
        $recordDays = $recordsByDate->count();

        $productionChart = [];
        if ($productionRecords->isNotEmpty()) {
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $dateKey = $date->toDateString();
                $records = $recordsByDate->get($dateKey, collect());

                $productionChart[] = [
                    'date' => $dateKey,
                    'production' => (int) $records->sum('production_quantity'),
                    'regularSold' => (int) $records->sum('regular_sold_quantity'),
                    'surplus' => (int) $records->sum('surplus_quantity'),
                    'waste' => (int) $records->sum('waste_quantity'),
                ];
            }
        }

        $averageRegularSales = $recordDays > 0
            ? $totalRegularSold / $recordDays
            : null;
        // Rekomendasi hanya muncul jika ada minimal 3 hari catatan produksi pada periode terpilih.
        $recommendedProduction = ($averageRegularSales !== null && $recordDays >= 3)
            ? (int) ceil($averageRegularSales)
            : null;

        $recentOrders = (clone $businessReservations)
            ->with(['user', 'surplusListing.food'])
            ->orderByDesc('reserved_at')
            ->limit(5)
            ->get()
            ->map(function ($reservation) {
                $customerName = trim(
                    ($reservation->user?->first_name ?? '') . ' ' .
                    ($reservation->user?->last_name ?? '')
                );

                $statusLabels = [
                    'pending' => 'Menunggu',
                    'confirmed' => 'Dikonfirmasi',
                    'completed' => 'Selesai',
                    'cancelled' => 'Dibatalkan',
                    'no_show' => 'Tidak Hadir',
                ];

                return [
                    'id' => 'RES-' . str_pad($reservation->id, 6, '0', STR_PAD_LEFT),
                    'name' => $customerName ?: 'Pelanggan',
                    'time' => $reservation->reserved_at
                        ? $reservation->reserved_at->translatedFormat('d M Y, H.i')
                        : '-',
                    'items' => (int) $reservation->portion_quantity,
                    'total' => (int) (
                        $reservation->portion_quantity *
                        $reservation->price_at_reservation
                    ),
                    'status' => $statusLabels[$reservation->reservation_status] ?? $reservation->reservation_status,
                    'avatar' => null,
                ];
            });

        return view('business.dashboard', [
            'business' => $business,
            'selectedPeriod' => $period,
            'selectedDateRange' => $periods[$period]['label'],
            'periodOptions' => $periods,
            'totalSaved' => $totalSaved,
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'totalCustomers' => $totalCustomers,
            'pendingReservationsCount' => $pendingReservationsCount,
            'surplusFoods' => $surplusFoods,
            'totalProduction' => $totalProduction,
            'totalRegularSold' => $totalRegularSold,
            'totalSurplus' => $totalSurplus,
            'totalWaste' => $totalWaste,
            'productionChart' => $productionChart,
            'averageRegularSales' => $averageRegularSales,
            'recommendedProduction' => $recommendedProduction,
            'recentOrders' => $recentOrders,
        ]);
    }

    public function notifications(Request $request)
    {
        $user = $request->user();
        abort_unless($user->role === 'business', 403);

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

        return view('business.notifications', compact('notifications'));
    }
}
