@extends('layouts.business')

@section('title', 'Dashboard Merchant - ' . $business->business_name)

@section('content')
<div class="flex h-screen min-h-0 w-full overflow-hidden bg-[#FAF7F2]"
     x-data="{
         activeNav: 'dashboard',
         mobileSidebarOpen: false,
         dateDropdownOpen: false,
         selectedDateRange: @js($selectedDateRange),
         selectedPeriod: @js($selectedPeriod),
         hoveredPoint: null,

         setPeriod(period) {
             const url = new URL(window.location.href);
             url.searchParams.set('period', period);
             window.location.href = url.toString();
         }
     }">

    <!-- ======================================================= -->
    <!-- 1. SIDEBAR KIRI -->
    <!-- ======================================================= -->

    <x-business.sidebar active="dashboard" />

    <!-- ======================================================= -->
    <!-- 2. MAIN CONTENT AREA -->
    <!-- ======================================================= -->

    <div class="flex-1 flex flex-col min-w-0 min-h-0 overflow-hidden">

        <x-business.topbar />

        <!-- DASHBOARD SCROLLABLE BODY -->
        <main class="flex-1 min-h-0 overflow-y-auto overscroll-contain px-4 sm:px-6 lg:px-8 py-6 space-y-6 custom-scrollbar">

            <!-- ======================================================= -->
            <!-- 3. HEADER GREETING, DATE FILTER & CTA BUTTON -->
            <!-- ======================================================= -->

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">

                <div>
                    <h1 class="text-2xl sm:text-[26px] font-black text-stone-900 tracking-tight flex items-center gap-2">
                        <span>Selamat datang, {{ $business->business_name }}!</span>
                        <span class="inline-block transform hover:rotate-12 transition">👋</span>
                    </h1>

                    <p class="text-xs sm:text-sm text-stone-500 mt-1">
                        Kelola makanan surplusmu dan bantu lebih banyak orang hari ini.
                    </p>
                </div>

                <!-- Action Controls -->
                <div class="flex flex-wrap items-center gap-3">

                    <!-- Date Filter -->
                    <div class="relative">
                        <button
                            type="button"
                            @click="dateDropdownOpen = !dateDropdownOpen"
                            class="bg-white border border-stone-200/90 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-stone-700 flex items-center gap-2.5 shadow-xs hover:border-stone-300 transition">

                            <svg class="w-4 h-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>

                            <span x-text="selectedDateRange"></span>

                            <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div
                            x-cloak
                            x-show="dateDropdownOpen"
                            @click.outside="dateDropdownOpen = false"
                            class="absolute right-0 mt-1.5 w-48 bg-white rounded-xl shadow-xl border border-stone-200 py-1 z-30">

                            <button type="button"
                                    @click="setPeriod('week_current'); dateDropdownOpen = false"
                                    class="w-full text-left px-3.5 py-2 text-xs hover:bg-stone-50 font-medium"
                                    :class="selectedPeriod === 'week_current' ? 'text-[#1B5E3A] font-bold bg-[#EBF5F0]/50' : 'text-stone-700'">
                                Minggu Ini
                            </button>

                            <button type="button"
                                    @click="setPeriod('today'); dateDropdownOpen = false"
                                    class="w-full text-left px-3.5 py-2 text-xs hover:bg-stone-50 font-medium"
                                    :class="selectedPeriod === 'today' ? 'text-[#1B5E3A] font-bold bg-[#EBF5F0]/50' : 'text-stone-700'">
                                Hari Ini
                            </button>

                            <button type="button"
                                    @click="setPeriod('month_current'); dateDropdownOpen = false"
                                    class="w-full text-left px-3.5 py-2 text-xs hover:bg-stone-50 font-medium"
                                    :class="selectedPeriod === 'month_current' ? 'text-[#1B5E3A] font-bold bg-[#EBF5F0]/50' : 'text-stone-700'">
                                Bulan Ini
                            </button>
                        </div>
                    </div>

                    <!-- Add Surplus Button -->
                    <a
                        href="{{ route('business.surplus-listings') }}"
                        class="bg-[#1B5E3A] hover:bg-[#14472C] text-white px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold flex items-center gap-2 shadow-sm shadow-[#1B5E3A]/25 transition active:scale-98">

                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                  d="M12 4v16m8-8H4"/>
                        </svg>

                        <span>Tambah Surplus Makanan</span>
                    </a>

                </div>
            </div>


            <!-- ======================================================= -->
            <!-- 4. STATISTIK UTAMA -->
            <!-- ======================================================= -->

            <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

                <!-- Total Pesanan -->
                <div class="bg-white rounded-2xl border border-stone-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-stone-500">Total Pesanan</p>
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-stone-900 mt-4">
                        {{ number_format($totalOrders, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-stone-400 mt-1">Reservasi pending, confirmed, dan completed</p>
                </div>

                <!-- Total Pendapatan -->
                <div class="bg-white rounded-2xl border border-stone-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-stone-500">Total Pendapatan</p>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 2v8m0 0v2m0-2c-1.657 0-3-.895-3-2m6 0c0 1.105-1.343 2-3 2m7-4a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-black text-stone-900 mt-4">
                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-stone-400 mt-1">Dari pesanan yang selesai</p>
                </div>

                <!-- Total Pelanggan -->
                <div class="bg-white rounded-2xl border border-stone-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-stone-500">Total Pelanggan</p>
                        <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-stone-900 mt-4">
                        {{ number_format($totalCustomers, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-stone-400 mt-1">Pelanggan unik</p>
                </div>

                <!-- Makanan Terselamatkan -->
                <div class="bg-white rounded-2xl border border-stone-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-stone-500">Porsi Terselamatkan</p>
                        <div class="w-10 h-10 rounded-xl bg-green-50 text-green-700 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 21c4.418 0 8-3.582 8-8 0-5-8-11-8-11S4 8 4 13c0 4.418 3.582 8 8 8z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-stone-900 mt-4">
                        {{ number_format($totalSaved, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-stone-400 mt-1">Porsi dari pesanan selesai</p>
                </div>

            </section>


            <!-- ======================================================= -->
            <!-- 5. GRAFIK PRODUKSI HARIAN -->
            <!-- ======================================================= -->

            <section class="bg-white rounded-2xl border border-stone-200/80 p-5 sm:p-6 shadow-xs">

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                    <div>
                        <h2 class="text-lg font-extrabold text-stone-900">Produksi Harian</h2>
                        <p class="text-xs text-stone-500 mt-1">
                            Ringkasan produksi makanan pada periode terpilih
                        </p>
                    </div>
                    <span class="text-xs font-semibold text-[#1B5E3A] bg-[#EBF5F0] px-3 py-2 rounded-lg">
                        {{ $selectedDateRange }}
                    </span>
                </div>

                @if(isset($productionChart) && count($productionChart) > 0)

                    @php
                        $maxProduction = max(1, collect($productionChart)->max('production'));
                    @endphp

                    <div class="flex items-end gap-2 sm:gap-4 h-56 border-b border-stone-200 pb-2">

                        @foreach($productionChart as $day)
                            @php
                                $barHeight = ($day['production'] / $maxProduction) * 100;
                            @endphp

                            <div class="flex-1 h-full flex flex-col justify-end items-center gap-2 min-w-0">

                                <span class="text-[10px] sm:text-xs font-semibold text-stone-600">
                                    {{ $day['production'] }}
                                </span>

                                <div class="w-full max-w-12 rounded-t-lg bg-[#1B5E3A] hover:bg-[#287A4D] transition"
                                     style="height: {{ max(3, $barHeight) }}%">
                                </div>

                                <span class="text-[10px] sm:text-xs text-stone-500 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($day['date'])->format('d/m') }}
                                </span>

                            </div>
                        @endforeach

                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6">

                        <div class="rounded-xl bg-stone-50 p-3">
                            <p class="text-xs text-stone-500">Total Produksi</p>
                            <p class="text-lg font-extrabold text-stone-900 mt-1">
                                {{ number_format($totalProduction ?? 0, 0, ',', '.') }}
                            </p>
                            <p class="text-[10px] text-stone-400">Porsi</p>
                        </div>

                        <div class="rounded-xl bg-blue-50 p-3">
                            <p class="text-xs text-blue-700">Terjual Normal</p>
                            <p class="text-lg font-extrabold text-blue-900 mt-1">
                                {{ number_format($totalRegularSold ?? 0, 0, ',', '.') }}
                            </p>
                            <p class="text-[10px] text-blue-600">Porsi</p>
                        </div>

                        <div class="rounded-xl bg-orange-50 p-3">
                            <p class="text-xs text-orange-700">Surplus</p>
                            <p class="text-lg font-extrabold text-orange-900 mt-1">
                                {{ number_format($totalSurplus ?? 0, 0, ',', '.') }}
                            </p>
                            <p class="text-[10px] text-orange-600">Porsi</p>
                        </div>

                        <div class="rounded-xl bg-red-50 p-3">
                            <p class="text-xs text-red-700">Terbuang</p>
                            <p class="text-lg font-extrabold text-red-900 mt-1">
                                {{ number_format($totalWaste ?? 0, 0, ',', '.') }}
                            </p>
                            <p class="text-[10px] text-red-600">Porsi</p>
                        </div>

                    </div>

                    <div class="mt-5 rounded-xl bg-[#F0F7F2] border border-[#D6EBDD] p-4">
                        <p class="text-sm font-bold text-[#1B5E3A]">Rekomendasi Produksi</p>
                        @if($recommendedProduction !== null)
                            <p class="text-sm text-stone-700 mt-1">
                                Berdasarkan rata-rata penjualan normal, acuan produksi harian adalah
                                <strong>{{ number_format($recommendedProduction, 0, ',', '.') }} porsi</strong>.
                            </p>
                            <p class="text-[11px] text-stone-500 mt-1">
                                Dihitung dari total penjualan normal dibagi jumlah hari yang memiliki catatan produksi pada periode terpilih.
                            </p>
                        @else
                            <p class="text-sm text-stone-700 mt-1">
                                Belum cukup data. Rekomendasi muncul setelah minimal 3 hari catatan produksi pada periode terpilih.
                            </p>
                        @endif
                    </div>

                @else

                    <div class="py-12 text-center">
                        <div class="w-14 h-14 mx-auto rounded-2xl bg-stone-100 flex items-center justify-center text-stone-400">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 3v18h18M8 15l4-4 4 4 5-7"/>
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-stone-700 mt-3">Data produksi belum tersedia</p>
                        <p class="text-xs text-stone-400 mt-1">
                            Catat produksi harian untuk melihat grafik dan ringkasan di sini.
                        </p>
                    </div>

                @endif

            </section>


            <!-- ======================================================= -->
            <!-- 6. MAKANAN SURPLUS -->
            <!-- ======================================================= -->

            <section class="bg-white rounded-2xl border border-stone-200/80 p-5 sm:p-6 shadow-xs">

                <div class="flex items-center justify-between gap-3 mb-5">
                    <div>
                        <h2 class="text-lg font-extrabold text-stone-900">Makanan Surplus Aktif</h2>
                        <p class="text-xs text-stone-500 mt-1">Daftar makanan yang tersedia untuk dipesan</p>
                    </div>
                    <span class="text-xs font-bold text-[#1B5E3A]">
                        {{ count($surplusFoods) }} Listing
                    </span>
                </div>

                @if(count($surplusFoods) > 0)

                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

                        @foreach($surplusFoods as $food)
                            <div class="border border-stone-200 rounded-xl overflow-hidden hover:shadow-md transition">

                                <div class="h-36 bg-stone-100 overflow-hidden">
                                    @if(!empty($food['image']))
                                        <img src="{{ $food['image'] }}" alt="{{ $food['title'] }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-4xl text-[#1B5E3A] bg-[#EBF5F0]">🥗</div>
                                    @endif
                                </div>

                                <div class="p-4">
                                    <div class="flex items-start justify-between gap-2">
                                        <h3 class="font-bold text-sm text-stone-900">
                                            {{ $food['title'] }}
                                        </h3>
                                        <span class="text-[10px] font-bold text-green-700 bg-green-50 px-2 py-1 rounded-full">
                                            {{ $food['status'] }}
                                        </span>
                                    </div>

                                    <p class="text-lg font-extrabold text-[#1B5E3A] mt-3">
                                        Rp {{ number_format($food['price'], 0, ',', '.') }}
                                    </p>

                                    <div class="flex justify-between text-xs text-stone-500 mt-3">
                                        <span>Stok tersedia</span>
                                        <strong class="text-stone-700">
                                            {{ $food['stock'] }} {{ $food['unit'] }}
                                        </strong>
                                    </div>

                                    <div class="flex justify-between text-xs text-stone-500 mt-1">
                                        <span>Sudah dipesan</span>
                                        <strong class="text-stone-700">
                                            {{ $food['reserved'] }} porsi
                                        </strong>
                                    </div>

                                    <p class="text-[11px] text-stone-400 mt-3 pt-3 border-t border-stone-100">
                                        {{ $food['pickupTime'] }}
                                    </p>
                                </div>

                            </div>
                        @endforeach

                    </div>

                @else

                    <div class="py-10 text-center">
                        <p class="text-sm font-semibold text-stone-600">Belum ada makanan surplus aktif.</p>
                        <p class="text-xs text-stone-400 mt-1">Listing makanan surplus akan muncul di bagian ini.</p>
                    </div>

                @endif

            </section>


            <!-- ======================================================= -->
            <!-- 7. PESANAN TERBARU -->
            <!-- ======================================================= -->

            <section class="bg-white rounded-2xl border border-stone-200/80 p-5 sm:p-6 shadow-xs">

                <div class="mb-5">
                    <h2 class="text-lg font-extrabold text-stone-900">Pesanan Terbaru</h2>
                    <p class="text-xs text-stone-500 mt-1">Aktivitas reservasi terbaru dari pelanggan</p>
                </div>

                @if(count($recentOrders) > 0)

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[650px] text-left">
                            <thead>
                                <tr class="text-xs text-stone-500 border-b border-stone-200">
                                    <th class="py-3 pr-4 font-semibold">ID Pesanan</th>
                                    <th class="py-3 pr-4 font-semibold">Pelanggan</th>
                                    <th class="py-3 pr-4 font-semibold">Waktu</th>
                                    <th class="py-3 pr-4 font-semibold">Jumlah</th>
                                    <th class="py-3 pr-4 font-semibold">Total</th>
                                    <th class="py-3 font-semibold">Status</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-stone-100">

                                @foreach($recentOrders as $order)
                                    <tr class="text-sm">
                                        <td class="py-4 pr-4 font-bold text-stone-800">
                                            {{ $order['id'] }}
                                        </td>

                                        <td class="py-4 pr-4">
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-8 h-8 rounded-full bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center text-xs font-bold">
                                                    {{ mb_substr($order['name'] ?: 'P', 0, 1) }}
                                                </div>
                                                <span class="font-semibold text-stone-700">
                                                    {{ $order['name'] ?: 'Pelanggan' }}
                                                </span>
                                            </div>
                                        </td>

                                        <td class="py-4 pr-4 text-xs text-stone-500">
                                            {{ $order['time'] }}
                                        </td>

                                        <td class="py-4 pr-4 text-stone-600">
                                            {{ $order['items'] }} porsi
                                        </td>

                                        <td class="py-4 pr-4 font-semibold text-stone-800">
                                            Rp {{ number_format($order['total'], 0, ',', '.') }}
                                        </td>

                                        <td class="py-4">
                                            @php
                                                $statusClass = match($order['status']) {
                                                    'Selesai' => 'bg-green-50 text-green-700',
                                                    'Dikonfirmasi' => 'bg-blue-50 text-blue-700',
                                                    'Menunggu' => 'bg-amber-50 text-amber-700',
                                                    'Dibatalkan' => 'bg-red-50 text-red-700',
                                                    'Tidak Hadir' => 'bg-stone-100 text-stone-600',
                                                    default => 'bg-stone-100 text-stone-600',
                                                };
                                            @endphp

                                            <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold {{ $statusClass }}">
                                                {{ $order['status'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                @else

                    <div class="py-10 text-center">
                        <p class="text-sm font-semibold text-stone-600">Belum ada pesanan.</p>
                        <p class="text-xs text-stone-400 mt-1">Pesanan pelanggan akan muncul di bagian ini.</p>
                    </div>

                @endif

            </section>

        </main>
    </div>
</div>
@endsection
