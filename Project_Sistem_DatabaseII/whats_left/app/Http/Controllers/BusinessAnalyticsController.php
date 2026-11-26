<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\ProductionRecord;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class BusinessAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        abort_unless($request->user()->role === 'business', 403);

        $business = $request->user()->business;

        abort_if(!$business, 404, 'Data Business tidak ditemukan.');

        $periods = $this->getPeriods();
        $periodKey = $request->query('period', 'week_current');
        $selectedTab = $request->query('tab', 'ringkasan');

        if (!in_array($selectedTab, ['ringkasan', 'penjualan', 'surplus', 'pelanggan', 'dampak', 'performa-menu'], true)) {
            $selectedTab = 'ringkasan';
        }

        if (!array_key_exists($periodKey, $periods)) {
            $periodKey = 'week_current';
        }

        $period = $periods[$periodKey];
        $startDate = $period['start'];
        $endDate = $period['end'];

        $reservations = $this->getReservations($business, $startDate, $endDate);
        $productionRecords = $this->getProductionRecords($business, $startDate, $endDate);

        $periodLength = $startDate->diffInDays($endDate) + 1;
        $previousEnd = $startDate->copy()->subDay();
        $previousStart = $previousEnd->copy()->subDays($periodLength - 1);
        $previousReservations = $this->getReservations($business, $previousStart, $previousEnd);

        $dataset = $this->buildDataset($reservations, $previousReservations, $productionRecords, $startDate, $endDate);

        $analyticsData = [
            'datasets' => [
                $periodKey => $dataset,
            ],
            'topMenus' => $this->buildTopMenus($reservations, $productionRecords),
            'heatmap' => $this->buildHeatmap($reservations),
            'weeklyInsights' => $this->buildInsights($dataset, $productionRecords, $reservations),
            'surplusWasteAnalysis' => $this->buildSurplusWasteAnalysis($reservations, $productionRecords),
            'topCustomers' => $this->buildTopCustomers($reservations),
        ];

        $dateRanges = collect($periods)->map(function ($item, $key) {
            return [
                'key' => $key,
                'label' => $item['label'],
            ];
        })->values()->all();

        return view('business.analytics', [
            'business' => $business,
            'analyticsData' => $analyticsData,
            'dateRanges' => $dateRanges,
            'selectedDateKey' => $periodKey,
            'selectedTab' => $selectedTab,
            'selectedDateLabel' => $period['label'],
            'defaultActiveIndex' => max(0, $dataset['revenueTrend'] ? count($dataset['revenueTrend']) - 1 : 0),
        ]);
    }

    private function getPeriods(): array
    {
        $today = Carbon::today();
        $currentWeekStart = $today->copy()->startOfWeek(Carbon::MONDAY);
        $previousWeekStart = $currentWeekStart->copy()->subWeek();
        $previousWeekEnd = $currentWeekStart->copy()->subDay();
        $currentMonthStart = $today->copy()->startOfMonth();

        return [
            'week_current' => [
                'start' => $currentWeekStart,
                'end' => $today,
                'label' => $currentWeekStart->translatedFormat('d M Y') . ' - ' . $today->translatedFormat('d M Y'),
            ],
            'week_prev' => [
                'start' => $previousWeekStart,
                'end' => $previousWeekEnd,
                'label' => $previousWeekStart->translatedFormat('d M Y') . ' - ' . $previousWeekEnd->translatedFormat('d M Y'),
            ],
            'month_current' => [
                'start' => $currentMonthStart,
                'end' => $today,
                'label' => $currentMonthStart->translatedFormat('d M Y') . ' - ' . $today->translatedFormat('d M Y'),
            ],
        ];
    }

    private function getReservations(Business $business, Carbon $startDate, Carbon $endDate): Collection
    {
        return Reservation::with(['user', 'surplusListing.food'])
            ->whereHas('surplusListing.food', function ($query) use ($business) {
                $query->where('business_id', $business->id);
            })
            ->whereBetween('reserved_at', [
                $startDate->copy()->startOfDay(),
                $endDate->copy()->endOfDay(),
            ])
            ->get();
    }

    private function getProductionRecords(Business $business, Carbon $startDate, Carbon $endDate): Collection
    {
        return ProductionRecord::with('food')
            ->whereHas('food', function ($query) use ($business) {
                $query->where('business_id', $business->id);
            })
            ->whereBetween('record_date', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ])
            ->get();
    }

    private function buildDataset(
        Collection $reservations,
        Collection $previousReservations,
        Collection $productionRecords,
        Carbon $startDate,
        Carbon $endDate
    ): array {
        $completed = $reservations->where('reservation_status', 'completed');
        $previousCompleted = $previousReservations->where('reservation_status', 'completed');

        $validOrders = $reservations->whereIn('reservation_status', ['pending', 'confirmed', 'completed']);
        $previousValidOrders = $previousReservations->whereIn('reservation_status', ['pending', 'confirmed', 'completed']);

        $revenue = $this->sumRevenue($completed);
        $previousRevenue = $this->sumRevenue($previousCompleted);
        $portions = (int) $completed->sum('portion_quantity');
        $previousPortions = (int) $previousCompleted->sum('portion_quantity');
        $orders = $validOrders->count();
        $previousOrders = $previousValidOrders->count();
        $customers = $validOrders->pluck('user_id')->filter()->unique()->count();
        $previousCustomers = $previousValidOrders->pluck('user_id')->filter()->unique()->count();

        $cancelledReservations = $reservations->where('reservation_status', 'cancelled')->count();
        $previousCancelledReservations = $previousReservations->where('reservation_status', 'cancelled')->count();

        $averageOrderValue = $completed->count() > 0 ? (int) round($revenue / $completed->count()) : 0;
        $previousAverageOrderValue = $previousCompleted->count() > 0
            ? (int) round($previousRevenue / $previousCompleted->count())
            : 0;

        $dailyRevenue = [];
        $dailyPortions = [];

        foreach ($completed as $reservation) {
            if (!$reservation->reserved_at) {
                continue;
            }

            $dateKey = $reservation->reserved_at->toDateString();
            $amount = (int) $reservation->portion_quantity * (float) $reservation->price_at_reservation;
            $foodCategory = strtolower((string) optional(optional($reservation->surplusListing)->food)->category);
            $isBeverage = str_contains($foodCategory, 'beverage') || str_contains($foodCategory, 'minuman');

            $dailyRevenue[$dateKey]['value'] = ($dailyRevenue[$dateKey]['value'] ?? 0) + $amount;
            $dailyRevenue[$dateKey]['orders'] = ($dailyRevenue[$dateKey]['orders'] ?? 0) + 1;

            $dailyPortions[$dateKey]['makanan'] = ($dailyPortions[$dateKey]['makanan'] ?? 0)
                + ($isBeverage ? 0 : (int) $reservation->portion_quantity);
            $dailyPortions[$dateKey]['minuman'] = ($dailyPortions[$dateKey]['minuman'] ?? 0)
                + ($isBeverage ? (int) $reservation->portion_quantity : 0);
        }

        $revenueTrend = [];
        $portionsSaved = [];

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $key = $date->toDateString();
            $revenueTrend[] = [
                'day' => $date->translatedFormat('d M'),
                'fullDate' => $date->translatedFormat('d M Y'),
                'value' => (int) ($dailyRevenue[$key]['value'] ?? 0),
                'orders' => (int) ($dailyRevenue[$key]['orders'] ?? 0),
            ];
            $portionsSaved[] = [
                'day' => $date->translatedFormat('d M'),
                'makanan' => (int) ($dailyPortions[$key]['makanan'] ?? 0),
                'minuman' => (int) ($dailyPortions[$key]['minuman'] ?? 0),
            ];
        }

        $categoryTotals = [];
        foreach ($completed as $reservation) {
            $food = $reservation->surplusListing?->food;
            if (!$food) {
                continue;
            }

            $category = $this->categoryLabel($food->category);
            $categoryTotals[$category]['revenue'] = ($categoryTotals[$category]['revenue'] ?? 0)
                + ((int) $reservation->portion_quantity * (float) $reservation->price_at_reservation);
            $categoryTotals[$category]['portions'] = ($categoryTotals[$category]['portions'] ?? 0)
                + (int) $reservation->portion_quantity;
        }

        $totalCategoryRevenue = array_sum(array_column($categoryTotals, 'revenue'));
        $palette = ['#22A05B', '#60A5FA', '#8B5CF6', '#F87171', '#F59E0B', '#14B8A6'];

        $categories = [];
        $index = 0;
        foreach ($categoryTotals as $name => $values) {
            $categoryRevenue = (int) $values['revenue'];
            $categories[] = [
                'name' => $name,
                'revenue' => $categoryRevenue,
                'percent' => $totalCategoryRevenue > 0
                    ? round(($categoryRevenue / $totalCategoryRevenue) * 100, 1)
                    : 0,
                'color' => $palette[$index % count($palette)],
                'dotColor' => $palette[$index % count($palette)],
                'portions' => (int) $values['portions'],
            ];
            $index++;
        }

        return [
            'kpis' => [
                'revenue' => $revenue,
                'revenueGrowth' => $this->percentageChange($revenue, $previousRevenue),
                'orders' => $orders,
                'completedOrders' => $completed->count(),
                'ordersGrowth' => $this->percentageChange($orders, $previousOrders),
                'portions' => $portions,
                'portionsGrowth' => $this->percentageChange($portions, $previousPortions),
                'newCustomers' => $customers,
                'customersGrowth' => $this->percentageChange($customers, $previousCustomers),
            ],
            'revenueTrend' => $revenueTrend,
            'portionsSaved' => $portionsSaved,
            'categories' => $categories,
            'summary' => [
                'aov' => $averageOrderValue,
                'aovDelta' => $this->percentageChange($averageOrderValue, $previousAverageOrderValue),
                'conversion' => null,
                'conversionDelta' => null,
                'cancelledListings' => $cancelledReservations,
                'cancelledDelta' => $this->percentageChange($cancelledReservations, $previousCancelledReservations),
                'rating' => null,
                'ratingDelta' => null,
                'averagePortionsPerOrder' => $completed->count() > 0 ? round($portions / $completed->count(), 1) : 0,
            ],
            'impact' => [
                'portions' => $portions,
                'co2' => null,
                'water' => null,
                'treesEquivalent' => null,
            ],
        ];
    }

    private function buildTopMenus(Collection $reservations, Collection $productionRecords): array
    {
        $completed = $reservations->where('reservation_status', 'completed');
        $menuData = [];

        foreach ($productionRecords as $record) {
            $food = $record->food;
            if (!$food) {
                continue;
            }

            $id = $food->id;
            $menuData[$id]['food'] = $food;
            $menuData[$id]['produced'] = ($menuData[$id]['produced'] ?? 0) + (int) $record->production_quantity;
            $menuData[$id]['regularSold'] = ($menuData[$id]['regularSold'] ?? 0) + (int) $record->regular_sold_quantity;
            $menuData[$id]['surplus'] = ($menuData[$id]['surplus'] ?? 0) + (int) $record->surplus_quantity;
            $menuData[$id]['wasted'] = ($menuData[$id]['wasted'] ?? 0) + (int) $record->waste_quantity;
            $menuData[$id]['recordDays'][$record->record_date->toDateString()] = true;
        }

        foreach ($completed as $reservation) {
            $food = $reservation->surplusListing?->food;
            if (!$food) {
                continue;
            }

            $id = $food->id;
            $menuData[$id]['food'] = $food;
            $menuData[$id]['revenue'] = ($menuData[$id]['revenue'] ?? 0)
                + ((int) $reservation->portion_quantity * (float) $reservation->price_at_reservation);
            $menuData[$id]['portions'] = ($menuData[$id]['portions'] ?? 0) + (int) $reservation->portion_quantity;
            $menuData[$id]['discounts'][] = (float) optional($reservation->surplusListing)->discount_percentage;
        }

        $menus = [];
        foreach ($menuData as $data) {
            $food = $data['food'];
            $produced = (int) ($data['produced'] ?? 0);
            $regularSold = (int) ($data['regularSold'] ?? 0);
            $rescued = (int) ($data['portions'] ?? 0);
            $recordDays = count($data['recordDays'] ?? []);
            $revenue = (int) ($data['revenue'] ?? 0);
            $discounts = $data['discounts'] ?? [];

            $menus[] = [
                'id' => $food->id,
                'name' => $food->food_name,
                'category' => $this->categoryLabel($food->category),
                'revenue' => $revenue,
                'portions' => $rescued,
                'sellThrough' => $produced > 0 ? min(100, round((($regularSold + $rescued) / $produced) * 100)) : null,
                'avgDiscount' => count($discounts) > 0 ? round(array_sum($discounts) / count($discounts), 1) : null,
                'surplusRate' => $produced > 0 ? round(((int) ($data['surplus'] ?? 0) / $produced) * 100, 1) : null,
                'recommendedProd' => $recordDays > 0 ? (int) ceil($regularSold / $recordDays) : null,
                'image' => $food->image_url,
            ];
        }

        usort($menus, fn ($a, $b) => $b['revenue'] <=> $a['revenue']);

        return array_slice($menus, 0, 10);
    }

    private function buildHeatmap(Collection $reservations): array
    {
        $slots = [
            ['time' => '06.00', 'range' => '06.00 - 09.00', 'start' => 6, 'end' => 9],
            ['time' => '09.00', 'range' => '09.00 - 12.00', 'start' => 9, 'end' => 12],
            ['time' => '12.00', 'range' => '12.00 - 15.00', 'start' => 12, 'end' => 15],
            ['time' => '15.00', 'range' => '15.00 - 18.00', 'start' => 15, 'end' => 18],
            ['time' => '18.00', 'range' => '18.00 - 21.00', 'start' => 18, 'end' => 21],
            ['time' => '21.00', 'range' => '21.00 - 22.00', 'start' => 21, 'end' => 22],
        ];

        $counts = array_fill(0, count($slots), array_fill(0, 7, 0));

        foreach ($reservations->whereIn('reservation_status', ['pending', 'confirmed', 'completed']) as $reservation) {
            if (!$reservation->reserved_at) {
                continue;
            }

            $hour = (int) $reservation->reserved_at->format('G');
            $dayIndex = (int) $reservation->reserved_at->dayOfWeekIso - 1;

            foreach ($slots as $slotIndex => $slot) {
                if ($hour >= $slot['start'] && $hour < $slot['end']) {
                    $counts[$slotIndex][$dayIndex]++;
                    break;
                }
            }
        }

        $maxCount = 0;
        foreach ($counts as $row) {
            $maxCount = max($maxCount, max($row));
        }

        $rows = [];
        foreach ($slots as $slotIndex => $slot) {
            $levels = [];
            foreach ($counts[$slotIndex] as $count) {
                $levels[] = $maxCount > 0 ? min(4, (int) round(($count / $maxCount) * 4)) : 0;
            }

            $rows[] = [
                'time' => $slot['time'],
                'range' => $slot['range'],
                'levels' => $levels,
                'orders' => $counts[$slotIndex],
            ];
        }

        return $rows;
    }

    private function buildInsights(array $dataset, Collection $productionRecords, Collection $reservations): array
    {
        $insights = [];
        $categories = $dataset['categories'];

        if (count($categories) > 0) {
            $topCategory = collect($categories)->sortByDesc('revenue')->first();
            $insights[] = [
                'id' => 1,
                'badge' => 'Pendapatan',
                'badgeColor' => 'bg-[#EBF5F0] text-[#1B5E3A]',
                'title' => 'Kategori pendapatan tertinggi: ' . $topCategory['name'],
                'body' => $topCategory['name'] . ' menghasilkan ' . $this->rupiah($topCategory['revenue']) . ' dari reservasi yang selesai pada periode ini.',
                'action' => 'Gunakan data ini sebagai bahan evaluasi ketersediaan menu.',
            ];
        }

        $recordsByFood = $productionRecords->groupBy('food_id');
        $highestSurplus = null;

        foreach ($recordsByFood as $records) {
            $produced = (int) $records->sum('production_quantity');
            if ($produced < 1) {
                continue;
            }

            $surplus = (int) $records->sum('surplus_quantity');
            $rate = ($surplus / $produced) * 100;
            $food = $records->first()->food;

            if (!$highestSurplus || $rate > $highestSurplus['rate']) {
                $highestSurplus = [
                    'name' => $food ? $food->food_name : 'Menu',
                    'rate' => $rate,
                ];
            }
        }

        if ($highestSurplus) {
            $insights[] = [
                'id' => 2,
                'badge' => 'Catatan Produksi',
                'badgeColor' => 'bg-[#FEF3C7] text-[#B45309]',
                'title' => 'Rasio surplus tertinggi: ' . $highestSurplus['name'],
                'body' => 'Catatan produksi menunjukkan surplus sebesar ' . number_format($highestSurplus['rate'], 1, ',', '.') . '% dari jumlah produksi pada periode ini.',
                'action' => 'Bandingkan dengan penjualan reguler sebelum menentukan jumlah produksi berikutnya.',
            ];
        }

        if (count($insights) === 0) {
            $insights[] = [
                'id' => 1,
                'badge' => 'Belum ada data',
                'badgeColor' => 'bg-stone-100 text-stone-600',
                'title' => 'Belum cukup data untuk membuat insight',
                'body' => 'Insight akan muncul setelah terdapat reservasi selesai atau catatan produksi pada periode yang dipilih.',
                'action' => 'Catat produksi dan kelola reservasi agar analisis dapat dihitung.',
            ];
        }

        return $insights;
    }

    private function buildSurplusWasteAnalysis(Collection $reservations, Collection $productionRecords): array
    {
        $rescuedByFood = [];
        foreach ($reservations->where('reservation_status', 'completed') as $reservation) {
            $food = $reservation->surplusListing?->food;
            if ($food) {
                $rescuedByFood[$food->id] = ($rescuedByFood[$food->id] ?? 0) + (int) $reservation->portion_quantity;
            }
        }

        $rows = [];
        foreach ($productionRecords->groupBy('food_id') as $foodId => $records) {
            $food = $records->first()->food;
            if (!$food) {
                continue;
            }

            $produced = (int) $records->sum('production_quantity');
            $regularSold = (int) $records->sum('regular_sold_quantity');
            $recordDays = $records->pluck('record_date')->map(fn ($date) => $date->toDateString())->unique()->count();
            $averageSales = $recordDays > 0 ? (int) ceil($regularSold / $recordDays) : null;

            $rows[] = [
                'menu' => $food->food_name,
                'produced' => $produced,
                'soldRegular' => $regularSold,
                'rescued' => (int) ($rescuedByFood[$foodId] ?? 0),
                'wasted' => (int) $records->sum('waste_quantity'),
                'recommendation' => $averageSales === null
                    ? 'Belum cukup data'
                    : 'Rata-rata penjualan reguler: ' . $averageSales . ' porsi/hari',
            ];
        }

        return $rows;
    }

    private function buildTopCustomers(Collection $reservations): array
    {
        $customers = [];

        foreach ($reservations->where('reservation_status', 'completed') as $reservation) {
            $user = $reservation->user;
            if (!$user) {
                continue;
            }

            $id = $user->id;
            $customers[$id]['id'] = $id;
            $customers[$id]['name'] = trim($user->first_name . ' ' . $user->last_name) ?: 'Pelanggan';
            $customers[$id]['orders'] = ($customers[$id]['orders'] ?? 0) + 1;
            $customers[$id]['portions'] = ($customers[$id]['portions'] ?? 0) + (int) $reservation->portion_quantity;
            $customers[$id]['spent'] = ($customers[$id]['spent'] ?? 0)
                + ((int) $reservation->portion_quantity * (float) $reservation->price_at_reservation);
            $orderTimestamp = $reservation->reserved_at?->timestamp ?? 0;
            if (!isset($customers[$id]['lastOrderTimestamp']) || $orderTimestamp > $customers[$id]['lastOrderTimestamp']) {
                $customers[$id]['lastOrder'] = $reservation->reserved_at
                    ? $reservation->reserved_at->translatedFormat('d M Y')
                    : '-';
                $customers[$id]['lastOrderTimestamp'] = $orderTimestamp;
            }
            $customers[$id]['badge'] = 'Pelanggan';
        }

        $rows = array_values($customers);
        usort($rows, fn ($a, $b) => $b['portions'] <=> $a['portions']);

        return array_slice($rows, 0, 10);
    }

    private function sumRevenue(Collection $reservations): int
    {
        return (int) round($reservations->sum(function ($reservation) {
            return (int) $reservation->portion_quantity * (float) $reservation->price_at_reservation;
        }));
    }

    private function percentageChange(float|int $current, float|int $previous): string
    {
        if ($previous == 0) {
            return '—';
        }

        $change = (($current - $previous) / $previous) * 100;

        return ($change > 0 ? '+' : '') . number_format($change, 1, ',', '.') . '%';
    }

    private function categoryLabel(?string $category): string
    {
        return match ($category) {
            'Main Course' => 'Makanan Berat',
            'Bread & Pastry' => 'Roti & Kue',
            'Beverage' => 'Minuman',
            'Snack' => 'Snack',
            'Dessert' => 'Dessert',
            'Other' => 'Lainnya',
            default => $category ?: 'Lainnya',
        };
    }

    private function rupiah(int $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}
