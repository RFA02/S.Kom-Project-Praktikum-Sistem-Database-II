@extends('layouts.business')

@section('title', 'Analitik - ' . $business->business_name)

@section('content')
<div class="flex h-screen overflow-hidden bg-[#FAF7F2]"
     x-data="{
         mobileSidebarOpen: false,
                  profileDropdownOpen: false,
                  dateDropdownOpen: false,
         exportDropdownOpen: false,
         revenueFilterOpen: false,
         portionFilterOpen: false,
         summaryFilterOpen: false,
         impactFilterOpen: false,
         menuSortOpen: false,
         insightsModalOpen: false,

         activeTab: @js($selectedTab),
         tabs: [
             { id: 'ringkasan', label: 'Ringkasan' },
             { id: 'penjualan', label: 'Penjualan' },
             { id: 'surplus', label: 'Surplus' },
             { id: 'pelanggan', label: 'Pelanggan' },
             { id: 'dampak', label: 'Dampak' },
             { id: 'performa-menu', label: 'Performa Menu' }
         ],

         selectedDateKey: @js($selectedDateKey),
         dateRanges: @js($dateRanges),
         revenuePeriod: 'Harian',
         portionPeriod: 'Harian',
         summaryPeriod: 'Periode terpilih',
         impactPeriod: 'Periode terpilih',
         menuSortBy: 'revenue',

         activeRevenueIndex: @js($defaultActiveIndex),
         hoveredPortionIndex: null,
         hoveredCategoryIndex: null,
         hoveredHeatCell: null,

         datasets: @js($analyticsData['datasets']),
         topMenus: @js($analyticsData['topMenus']),
         heatmapDays: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
         heatmapDaysFull: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
         heatmapRows: @js($analyticsData['heatmap']),
         weeklyInsights: @js($analyticsData['weeklyInsights']),
         surplusWasteAnalysis: @js($analyticsData['surplusWasteAnalysis']),
         topCustomers: @js($analyticsData['topCustomers']),

         get currentData() {
             return this.datasets[this.selectedDateKey];
         },

         get selectedDateLabel() {
             const found = this.dateRanges.find(item => item.key === this.selectedDateKey);
             return found ? found.label : @js($selectedDateLabel);
         },

         get sortedTopMenus() {
             const list = [...this.topMenus];
             if (this.menuSortBy === 'portions') {
                 return list.sort((a, b) => b.portions - a.portions);
             }
             return list.sort((a, b) => b.revenue - a.revenue);
         },

         get maxMenuMetric() {
             const values = this.topMenus.map(menu => this.menuSortBy === 'portions' ? menu.portions : menu.revenue);
             return Math.max(1, ...values);
         },

         get revenueMaxY() {
             const values = this.currentData.revenueTrend.map(item => item.value);
             const maxValue = values.length ? Math.max(...values) : 0;
             if (maxValue <= 1000000) return 1000000;
             return Math.ceil(maxValue / 1000000) * 1000000;
         },

         get revenueYTicks() {
             const max = this.revenueMaxY;
             return [
                 { val: max, label: max >= 1000000 ? (max / 1000000) + 'M' : (max / 1000) + 'K', y: 18 },
                 { val: max * 0.75, label: (max * 0.75) >= 1000000 ? ((max * 0.75) / 1000000).toFixed(1).replace('.0','') + 'M' : Math.round((max * 0.75) / 1000) + 'K', y: 58 },
                 { val: max * 0.5, label: (max * 0.5) >= 1000000 ? ((max * 0.5) / 1000000).toFixed(1).replace('.0','') + 'M' : Math.round((max * 0.5) / 1000) + 'K', y: 98 },
                 { val: max * 0.25, label: (max * 0.25) >= 1000000 ? ((max * 0.25) / 1000000).toFixed(1).replace('.0','') + 'M' : Math.round((max * 0.25) / 1000) + 'K', y: 138 },
                 { val: 0, label: '0', y: 178 }
             ];
         },

         get revenuePoints() {
             const items = this.currentData.revenueTrend;
             const max = this.revenueMaxY;
             const startX = 48;
             const endX = 498;
             const step = items.length > 1 ? (endX - startX) / (items.length - 1) : 0;
             return items.map((item, index) => ({
                 ...item,
                 index,
                 x: Math.round((startX + index * step) * 10) / 10,
                 y: Math.round((178 - (item.value / max) * 160) * 10) / 10
             }));
         },

         get revenueLinePath() {
             const points = this.revenuePoints;
             if (!points.length) return '';
             if (points.length === 1) return `M ${points[0].x} ${points[0].y}`;
             let path = `M ${points[0].x} ${points[0].y}`;
             for (let index = 0; index < points.length - 1; index++) {
                 const first = points[index];
                 const second = points[index + 1];
                 const control1 = first.x + (second.x - first.x) * 0.45;
                 const control2 = first.x + (second.x - first.x) * 0.55;
                 path += ` C ${control1} ${first.y}, ${control2} ${second.y}, ${second.x} ${second.y}`;
             }
             return path;
         },

         get revenueAreaPath() {
             const points = this.revenuePoints;
             if (!points.length) return '';
             return `${this.revenueLinePath} L ${points[points.length - 1].x} 178 L ${points[0].x} 178 Z`;
         },

         get activeRevenuePoint() {
             const points = this.revenuePoints;
             const safeIndex = Math.min(this.activeRevenueIndex, points.length - 1);
             return points[safeIndex] || points[0] || { day: '-', fullDate: '-', value: 0, orders: 0, x: 48, y: 178 };
         },

         get portionMaxY() {
             const totals = this.currentData.portionsSaved.map(item => item.makanan + item.minuman);
             const maxTotal = totals.length ? Math.max(...totals) : 0;
             if (maxTotal <= 250) return 260;
             if (maxTotal <= 800) return 800;
             return Math.ceil(maxTotal / 400) * 400;
         },

         get portionYTicks() {
             const max = this.portionMaxY;
             if (max === 260) return [240, 180, 120, 60, 0];
             return [max, Math.round(max * 0.75), Math.round(max * 0.5), Math.round(max * 0.25), 0];
         },

         get donutSegments() {
             const radius = 50;
             const circumference = 2 * Math.PI * radius;
             let cumulativePercent = 0;
             return this.currentData.categories.map((category, index) => {
                 const dash = (category.percent / 100) * circumference;
                 const offset = - (cumulativePercent / 100) * circumference;
                 cumulativePercent += category.percent;
                 return {
                     ...category,
                     index,
                     dashArray: `${dash.toFixed(2)} ${(circumference - dash).toFixed(2)}`,
                     dashOffset: offset.toFixed(2)
                 };
             });
         },

         formatRupiah(value) {
             if (value === null || value === undefined) return 'Belum tersedia';
             return 'Rp ' + Number(value).toLocaleString('id-ID');
         },

         formatNumber(value) {
             if (value === null || value === undefined) return 'Belum tersedia';
             return Number(value).toLocaleString('id-ID');
         },

         growthClass(delta) {
             if (!delta || delta === '—') return 'text-stone-400';
             return String(delta).startsWith('-') ? 'text-rose-600' : 'text-[#1B5E3A]';
         },

         growthArrow(delta) {
             if (!delta || delta === '—') return '•';
             return String(delta).startsWith('-') ? '↘' : '↗';
         },

         getHeatColor(level) {
             const palette = [
                 'bg-[#F1F8F4] hover:ring-[#1B5E3A]/40',
                 'bg-[#CFEAD9] hover:ring-[#1B5E3A]/50',
                 'bg-[#8FD2AE] hover:ring-[#1B5E3A]/60',
                 'bg-[#42A972] hover:ring-[#1B5E3A]/80',
                 'bg-[#1B5E3A] hover:ring-[#14472C]'
             ];
             return palette[level] || palette[0];
         },

         selectDateRange(key) {
             const url = new URL(window.location.href);
             url.searchParams.set('period', key);
             window.location.href = url.toString();
         },

         exportCSV() {
             const rows = [
                 ['Periode', this.selectedDateLabel],
                 ['Total Pendapatan', this.currentData.kpis.revenue],
                 ['Total Pesanan', this.currentData.kpis.orders],
                 ['Total Porsi Diselamatkan', this.currentData.kpis.portions],
                 ['Pelanggan Unik', this.currentData.kpis.newCustomers],
                 [],
                 ['Tanggal Reservasi', 'Pendapatan (Rp)', 'Reservasi Selesai'],
                 ...this.currentData.revenueTrend.map(item => [item.fullDate, item.value, item.orders])
             ];
             const csv = rows.map(row => row.join(',')).join('\n');
             const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
             const url = URL.createObjectURL(blob);
             const link = document.createElement('a');
             link.href = url;
             link.setAttribute('download', 'laporan-analitik-whats-left-' + this.selectedDateKey + '.csv');
             document.body.appendChild(link);
             link.click();
             document.body.removeChild(link);
             URL.revokeObjectURL(url);
             this.exportDropdownOpen = false;
             this.$dispatch('show-toast', { msg: 'Laporan analitik (.csv) berhasil diunduh!' });
         },

         printReport() {
             this.exportDropdownOpen = false;
             window.print();
         }
     }">

    <!-- ======================================================= -->
    <!-- 1. SIDEBAR KIRI (NAVIGATION PANEL)                      -->
    <!-- ======================================================= -->

    <x-business.sidebar active="analytics" />

    <!-- ======================================================= -->
    <!-- 2. MAIN WORKSPACE (TOPBAR + ANALYTICS CONTENT)          -->
    <!-- ======================================================= -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <x-business.topbar />

        <!-- ======================================================= -->
        <!-- 3. SCROLLABLE MAIN CONTENT                              -->
        <!-- ======================================================= -->
        <main class="flex-1 overflow-y-auto custom-scrollbar p-4 sm:p-6 lg:p-7">
            <div class="max-w-[1440px] mx-auto">

                <!-- PAGE HEADER ROW -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-5">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-[#1F2937] tracking-tight flex items-center gap-2">
                            <span>Analitik</span>
                            <span aria-hidden="true">🌱</span>
                        </h1>
                        <p class="text-[13px] text-[#6B7280] mt-0.5">
                            Pantau performa bisnis dan dampak positif yang kamu berikan.
                        </p>
                    </div>

                    <!-- Right Header Controls: Date Range Selector + Export Laporan -->
                    <div class="flex flex-wrap items-center gap-2.5">
                        <!-- Date Range Picker Dropdown -->
                        <div class="relative" @click.away="dateDropdownOpen = false">
                            <button type="button"
                                    @click="dateDropdownOpen = !dateDropdownOpen"
                                    aria-label="Pilih rentang tanggal analitik"
                                    class="inline-flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl bg-white border border-stone-200/90 hover:border-[#1B5E3A]/40 text-[13px] font-semibold text-[#1F2937] shadow-2xs transition cursor-pointer">
                                <svg class="w-4 h-4 text-[#1B5E3A] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span x-text="selectedDateLabel"></span>
                                <svg class="w-4 h-4 text-stone-400 transition-transform" :class="dateDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div x-cloak x-show="dateDropdownOpen"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-stone-200/80 py-1.5 z-40">
                                <template x-for="range in dateRanges" :key="range.key">
                                    <button type="button"
                                            @click="selectDateRange(range.key)"
                                            :class="selectedDateKey === range.key ? 'bg-[#EBF5F0] text-[#1B5E3A] font-bold' : 'text-stone-700 hover:bg-stone-50 font-medium'"
                                            class="w-full text-left px-4 py-2 text-xs flex items-center justify-between transition cursor-pointer">
                                        <span x-text="range.label"></span>
                                        <svg x-show="selectedDateKey === range.key" class="w-3.5 h-3.5 text-[#1B5E3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Export Laporan Button + Dropdown -->
                        <div class="relative" @click.away="exportDropdownOpen = false">
                            <button type="button"
                                    @click="exportDropdownOpen = !exportDropdownOpen"
                                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-stone-200/90 hover:border-[#1B5E3A]/40 hover:bg-[#FAFBF9] text-[13px] font-semibold text-[#1F2937] shadow-2xs transition cursor-pointer">
                                <svg class="w-4 h-4 text-stone-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                <span>Export Laporan</span>
                            </button>

                            <div x-cloak x-show="exportDropdownOpen"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-stone-200/80 py-1.5 z-40">
                                <button type="button"
                                        @click="exportCSV()"
                                        class="w-full text-left px-4 py-2.5 text-xs font-semibold text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A] flex items-center gap-2.5 transition cursor-pointer">
                                    <svg class="w-4 h-4 text-[#1B5E3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span>Unduh Data CSV (.csv)</span>
                                </button>
                                <button type="button"
                                        @click="printReport()"
                                        class="w-full text-left px-4 py-2.5 text-xs font-semibold text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A] flex items-center gap-2.5 transition cursor-pointer">
                                    <svg class="w-4 h-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                    </svg>
                                    <span>Cetak / Simpan PDF</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SUB-NAVIGATION TABS BAR -->
                <div class="border-b border-stone-200/80 mb-5 overflow-x-auto no-scrollbar">
                    <div class="flex items-center gap-2 sm:gap-6 min-w-max" role="tablist" aria-label="Kategori Analitik">
                        <template x-for="tab in tabs" :key="tab.id">
                            <button type="button"
                                    role="tab"
                                    :aria-selected="activeTab === tab.id"
                                    @click="activeTab = tab.id"
                                    :class="activeTab === tab.id
                                        ? 'border-[#1B5E3A] text-[#1B5E3A] font-bold'
                                        : 'border-transparent text-[#6B7280] hover:text-[#1F2937] font-semibold'"
                                    class="px-2 pb-3 pt-1 text-[13.5px] border-b-2 transition-colors whitespace-nowrap cursor-pointer"
                                    x-text="tab.label">
                            </button>
                        </template>
                    </div>
                </div>

                <!-- ======================================================= -->
                <!-- TAB 1: RINGKASAN (LAYOUT UTAMA SESUAI REFERENSI VISUAL) -->
                <!-- ======================================================= -->
                <div x-show="activeTab === 'ringkasan'" x-transition.opacity.duration.150ms>
                    <div class="grid grid-cols-1 xl:grid-cols-12 gap-4 items-start">

                        <!-- =============================================== -->
                        <!-- KOLOM KIRI UTAMA (9/12): KPI + CHARTS + DETAIL  -->
                        <!-- =============================================== -->
                        <div class="xl:col-span-9 space-y-4 min-w-0">

                            <!-- BARIS 1: 4 KARTU KPI UTAMA -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">

                                <!-- KPI 1: Total Pendapatan -->
                                <div class="bg-white rounded-2xl p-4 border border-stone-200/70 shadow-soft hover:shadow-card transition-shadow flex items-center gap-3.5">
                                    <div class="w-11 h-11 rounded-full bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[11.5px] font-medium text-[#6B7280] truncate">Total Pendapatan</p>
                                        <p class="text-[17px] font-extrabold text-[#1F2937] tracking-tight mt-0.5 truncate" x-text="formatRupiah(currentData.kpis.revenue)"></p>
                                        <p class="text-[11px] font-semibold mt-0.5 flex items-center gap-1" :class="growthClass(currentData.kpis.revenueGrowth)">
                                            <span aria-hidden="true" x-text="growthArrow(currentData.kpis.revenueGrowth)"></span>
                                            <span x-text="currentData.kpis.revenueGrowth + ' dibanding periode sebelumnya'"></span>
                                        </p>
                                    </div>
                                </div>

                                <!-- KPI 2: Total Pesanan -->
                                <div class="bg-white rounded-2xl p-4 border border-stone-200/70 shadow-soft hover:shadow-card transition-shadow flex items-center gap-3.5">
                                    <div class="w-11 h-11 rounded-full bg-[#FEF3C7] text-[#D97706] flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[11.5px] font-medium text-[#6B7280] truncate">Total Pesanan</p>
                                        <p class="text-[17px] font-extrabold text-[#1F2937] tracking-tight mt-0.5">
                                            <span x-text="formatNumber(currentData.kpis.orders)"></span>
                                            <span class="text-xs font-semibold text-[#6B7280] ml-0.5">pesanan</span>
                                        </p>
                                        <p class="text-[11px] font-semibold mt-0.5 flex items-center gap-1" :class="growthClass(currentData.kpis.ordersGrowth)">
                                            <span aria-hidden="true" x-text="growthArrow(currentData.kpis.ordersGrowth)"></span>
                                            <span x-text="currentData.kpis.ordersGrowth + ' dibanding periode sebelumnya'"></span>
                                        </p>
                                    </div>
                                </div>

                                <!-- KPI 3: Total Porsi Diselamatkan -->
                                <div class="bg-white rounded-2xl p-4 border border-stone-200/70 shadow-soft hover:shadow-card transition-shadow flex items-center gap-3.5">
                                    <div class="w-11 h-11 rounded-full bg-[#F3E8FF] text-[#7C3AED] flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M12 3v3m0 0a7 7 0 017 7H5a7 7 0 017-7zm-8 7h16a1 1 0 01.98 1.2A8 8 0 0112 20a8 8 0 01-8.98-5.8A1 1 0 014 13z"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[11.5px] font-medium text-[#6B7280] truncate">Total Porsi Diselamatkan</p>
                                        <p class="text-[17px] font-extrabold text-[#1F2937] tracking-tight mt-0.5">
                                            <span x-text="formatNumber(currentData.kpis.portions)"></span>
                                            <span class="text-xs font-semibold text-[#6B7280] ml-0.5">porsi</span>
                                        </p>
                                        <p class="text-[11px] font-semibold mt-0.5 flex items-center gap-1" :class="growthClass(currentData.kpis.portionsGrowth)">
                                            <span aria-hidden="true" x-text="growthArrow(currentData.kpis.portionsGrowth)"></span>
                                            <span x-text="currentData.kpis.portionsGrowth + ' dibanding periode sebelumnya'"></span>
                                        </p>
                                    </div>
                                </div>

                                <!-- KPI 4: Pelanggan Unik -->
                                <div class="bg-white rounded-2xl p-4 border border-stone-200/70 shadow-soft hover:shadow-card transition-shadow flex items-center gap-3.5">
                                    <div class="w-11 h-11 rounded-full bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[11.5px] font-medium text-[#6B7280] truncate">Pelanggan Unik</p>
                                        <p class="text-[17px] font-extrabold text-[#1F2937] tracking-tight mt-0.5">
                                            <span x-text="formatNumber(currentData.kpis.newCustomers)"></span>
                                            <span class="text-xs font-semibold text-[#6B7280] ml-0.5">pelanggan</span>
                                        </p>
                                        <p class="text-[11px] font-semibold mt-0.5 flex items-center gap-1" :class="growthClass(currentData.kpis.customersGrowth)">
                                            <span aria-hidden="true" x-text="growthArrow(currentData.kpis.customersGrowth)"></span>
                                            <span x-text="currentData.kpis.customersGrowth + ' dibanding periode sebelumnya'"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- BARIS 2: TREN PENDAPATAN (KIRI) + PORSI DISELAMATKAN (KANAN) -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

                                <!-- CHART 1: Tren Pendapatan (Rp) -->
                                <div class="bg-white rounded-2xl p-5 border border-stone-200/70 shadow-soft">
                                    <div class="flex items-center justify-between gap-2 mb-4">
                                        <h2 class="text-[15px] font-bold text-[#1F2937]">Tren Pendapatan (Rp)</h2>

                                        <!-- Period Dropdown -->
                                        <div class="relative" @click.away="revenueFilterOpen = false">
                                            <button type="button"
                                                    @click="revenueFilterOpen = !revenueFilterOpen"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-stone-200 text-xs font-semibold text-stone-600 hover:bg-stone-50 transition cursor-pointer">
                                                <span x-text="revenuePeriod"></span>
                                                <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </button>
                                            <div x-cloak x-show="revenueFilterOpen"
                                                 class="absolute right-0 mt-1.5 w-32 bg-white rounded-xl shadow-lg border border-stone-200/80 py-1 z-20">
                                                <button type="button" @click="revenuePeriod = 'Mingguan'; selectDateRange('week_current'); revenueFilterOpen = false" class="w-full text-left px-3 py-1.5 text-xs hover:bg-[#EBF5F0] hover:text-[#1B5E3A] font-medium cursor-pointer">Mingguan</button>
                                                <button type="button" @click="revenuePeriod = 'Bulanan'; selectDateRange('month_current'); revenueFilterOpen = false" class="w-full text-left px-3 py-1.5 text-xs hover:bg-[#EBF5F0] hover:text-[#1B5E3A] font-medium cursor-pointer">Bulanan</button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Interactive SVG Area Line Chart -->
                                    <div class="relative w-full">
                                        <!-- Floating Dynamic Tooltip -->
                                        <div class="pointer-events-none absolute z-10 -translate-x-1/2 -translate-y-full transition-all duration-200"
                                             :style="`left: ${(activeRevenuePoint.x / 520) * 100}%; top: ${Math.max(12, (activeRevenuePoint.y / 210) * 100 - 4)}%;`">
                                            <div class="bg-white/95 backdrop-blur-xs border border-stone-200/90 shadow-lg rounded-xl px-3 py-2 text-left whitespace-nowrap">
                                                <p class="text-[10.5px] font-medium text-[#6B7280]" x-text="activeRevenuePoint.fullDate"></p>
                                                <p class="text-xs font-extrabold text-[#1F2937] flex items-center gap-1.5 mt-0.5">
                                                    <span class="w-2 h-2 rounded-full bg-[#22A05B] inline-block"></span>
                                                    <span x-text="formatRupiah(activeRevenuePoint.value)"></span>
                                                </p>
                                            </div>
                                        </div>

                                        <svg viewBox="0 0 520 210" class="w-full h-56 overflow-visible" role="img" aria-label="Grafik tren pendapatan harian">
                                            <defs>
                                                <linearGradient id="revenueAreaGrad" x1="0" y1="0" x2="0" y2="1">
                                                    <stop offset="0%" stop-color="#22A05B" stop-opacity="0.24"/>
                                                    <stop offset="100%" stop-color="#22A05B" stop-opacity="0.01"/>
                                                </linearGradient>
                                            </defs>

                                            <!-- Horizontal Gridlines & Y-Axis Labels -->
                                            <template x-for="tick in revenueYTicks" :key="tick.label">
                                                <g>
                                                    <line x1="42" :y1="tick.y" x2="508" :y2="tick.y" stroke="#F1F0EC" stroke-width="1"/>
                                                    <text x="34" :y="tick.y + 4" text-anchor="end" class="fill-[#9CA3AF] text-[10.5px] font-medium" x-text="tick.label"></text>
                                                </g>
                                            </template>

                                            <!-- Vertical Guide Line for Active Point -->
                                            <line :x1="activeRevenuePoint.x" :y1="activeRevenuePoint.y" :x2="activeRevenuePoint.x" y2="178"
                                                  stroke="#1B5E3A" stroke-width="1" stroke-dasharray="3 3" opacity="0.35"/>

                                            <!-- Area Fill -->
                                            <path :d="revenueAreaPath" fill="url(#revenueAreaGrad)"/>

                                            <!-- Smooth Trend Line -->
                                            <path :d="revenueLinePath" fill="none" stroke="#22A05B" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>

                                            <!-- Data Points + Interactive Hit Areas + X-Axis Labels -->
                                            <template x-for="pt in revenuePoints" :key="pt.day">
                                                <g class="cursor-pointer"
                                                   @mouseenter="activeRevenueIndex = pt.index"
                                                   @click="activeRevenueIndex = pt.index">
                                                    <!-- Invisible generous hit area -->
                                                    <rect :x="pt.x - 24" y="10" width="48" height="175" fill="transparent"/>

                                                    <!-- Active halo -->
                                                    <circle x-show="activeRevenueIndex === pt.index"
                                                            :cx="pt.x" :cy="pt.y" r="9"
                                                            fill="#22A05B" opacity="0.16"/>

                                                    <!-- Point dot -->
                                                    <circle :cx="pt.x" :cy="pt.y"
                                                            :r="activeRevenueIndex === pt.index ? 5 : 3.8"
                                                            fill="#22A05B" stroke="#FFFFFF" stroke-width="2"/>

                                                    <!-- X-Axis Date Label -->
                                                    <text :x="pt.x" y="198" text-anchor="middle"
                                                          :class="activeRevenueIndex === pt.index ? 'fill-[#1B5E3A] font-bold' : 'fill-[#6B7280] font-medium'"
                                                          class="text-[10.5px]" x-text="pt.day"></text>
                                                </g>
                                            </template>
                                        </svg>
                                    </div>
                                </div>

                                <!-- CHART 2: Porsi Diselamatkan (Stacked Bar Chart) -->
                                <div class="bg-white rounded-2xl p-5 border border-stone-200/70 shadow-soft">
                                    <div class="flex items-center justify-between gap-2 mb-2">
                                        <h2 class="text-[15px] font-bold text-[#1F2937]">Porsi Diselamatkan</h2>

                                        <!-- Period Dropdown -->
                                        <div class="relative" @click.away="portionFilterOpen = false">
                                            <button type="button"
                                                    @click="portionFilterOpen = !portionFilterOpen"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-stone-200 text-xs font-semibold text-stone-600 hover:bg-stone-50 transition cursor-pointer">
                                                <span x-text="portionPeriod"></span>
                                                <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </button>
                                            <div x-cloak x-show="portionFilterOpen"
                                                 class="absolute right-0 mt-1.5 w-32 bg-white rounded-xl shadow-lg border border-stone-200/80 py-1 z-20">
                                                <button type="button" @click="portionPeriod = 'Mingguan'; selectDateRange('week_current'); portionFilterOpen = false" class="w-full text-left px-3 py-1.5 text-xs hover:bg-[#EBF5F0] hover:text-[#1B5E3A] font-medium cursor-pointer">Mingguan</button>
                                                <button type="button" @click="portionPeriod = 'Bulanan'; selectDateRange('month_current'); portionFilterOpen = false" class="w-full text-left px-3 py-1.5 text-xs hover:bg-[#EBF5F0] hover:text-[#1B5E3A] font-medium cursor-pointer">Bulanan</button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Legend -->
                                    <div class="flex items-center gap-4 mb-3">
                                        <div class="flex items-center gap-1.5 text-[11.5px] font-medium text-[#6B7280]">
                                            <span class="w-2.5 h-2.5 rounded-sm bg-[#229A55] inline-block"></span>
                                            <span>Makanan</span>
                                        </div>
                                        <div class="flex items-center gap-1.5 text-[11.5px] font-medium text-[#6B7280]">
                                            <span class="w-2.5 h-2.5 rounded-sm bg-[#A7D7B8] inline-block"></span>
                                            <span>Minuman</span>
                                        </div>
                                    </div>

                                    <!-- Stacked Bar Chart Container -->
                                    <div class="relative h-52 flex items-end pt-2">
                                        <!-- Y-Axis Labels & Horizontal Gridlines -->
                                        <div class="w-9 h-[176px] flex flex-col justify-between text-right pr-2 text-[10.5px] font-medium text-[#9CA3AF] shrink-0 mb-5">
                                            <template x-for="tick in portionYTicks" :key="tick">
                                                <span x-text="tick"></span>
                                            </template>
                                        </div>

                                        <!-- Bars Grid -->
                                        <div class="flex-1 relative h-[176px] mb-5">
                                            <!-- Background horizontal gridlines -->
                                            <div class="absolute inset-0 flex flex-col justify-between pointer-events-none">
                                                <template x-for="tick in portionYTicks" :key="'line-' + tick">
                                                    <div class="w-full border-t border-stone-100"></div>
                                                </template>
                                            </div>

                                            <!-- Stacked Bars -->
                                            <div class="relative z-10 h-full flex items-end justify-around px-1 sm:px-2 gap-2">
                                                <template x-for="(item, idx) in currentData.portionsSaved" :key="item.day">
                                                    <div class="relative flex-1 flex flex-col items-center h-full justify-end group cursor-pointer"
                                                         @mouseenter="hoveredPortionIndex = idx"
                                                         @mouseleave="hoveredPortionIndex = null">

                                                        <!-- Hover Popover -->
                                                        <div x-cloak x-show="hoveredPortionIndex === idx"
                                                             x-transition.opacity.duration.100ms
                                                             class="absolute -top-14 left-1/2 -translate-x-1/2 bg-[#1F2937] text-white rounded-xl px-2.5 py-1.5 text-[10.5px] shadow-lg whitespace-nowrap z-20 pointer-events-none">
                                                            <div class="font-bold text-center mb-0.5" x-text="item.day + ' • ' + (item.makanan + item.minuman) + ' porsi'"></div>
                                                            <div class="flex items-center gap-2 text-[10px] text-stone-200">
                                                                <span>Makanan: <strong class="text-[#86EFAC]" x-text="item.makanan"></strong></span>
                                                                <span>Minuman: <strong class="text-[#D1FAE5]" x-text="item.minuman"></strong></span>
                                                            </div>
                                                        </div>

                                                        <!-- Stacked Bar Body -->
                                                        <div class="w-5 sm:w-6 flex flex-col justify-end rounded-t-md overflow-hidden transition-transform duration-200 group-hover:scale-x-110"
                                                             :style="`height: ${Math.min(100, ((item.makanan + item.minuman) / portionMaxY) * 100)}%;`">
                                                            <!-- Top segment: Minuman -->
                                                            <div class="w-full bg-[#A7D7B8] transition-all duration-300"
                                                                 :style="`height: ${(item.makanan + item.minuman) > 0 ? (item.minuman / (item.makanan + item.minuman)) * 100 : 0}%;`"></div>
                                                            <!-- Bottom segment: Makanan -->
                                                            <div class="w-full bg-[#229A55] transition-all duration-300"
                                                                 :style="`height: ${(item.makanan + item.minuman) > 0 ? (item.makanan / (item.makanan + item.minuman)) * 100 : 0}%;`"></div>
                                                        </div>

                                                        <!-- X-Axis Day Label -->
                                                        <span class="absolute -bottom-6 text-[10.5px] whitespace-nowrap transition-colors"
                                                              :class="hoveredPortionIndex === idx ? 'text-[#1B5E3A] font-bold' : 'text-[#6B7280] font-medium'"
                                                              x-text="item.day"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- BARIS 3: 3 KOLOM ANALITIK DETAIL -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                                <!-- DETAIL 1: Penjualan per Kategori (Donut Chart) -->
                                <div class="bg-white rounded-2xl p-5 border border-stone-200/70 shadow-soft flex flex-col justify-between">
                                    <h2 class="text-[15px] font-bold text-[#1F2937] mb-4">Penjualan per Kategori</h2>

                                    <div class="flex flex-col sm:flex-row lg:flex-col xl:flex-row items-center gap-4 my-auto">
                                        <!-- SVG Donut Chart -->
                                        <div class="relative w-32 h-32 shrink-0 flex items-center justify-center">
                                            <svg viewBox="0 0 130 130" class="w-full h-full -rotate-90" role="img" aria-label="Diagram donat penjualan per kategori">
                                                <circle cx="65" cy="65" r="50" fill="none" stroke="#F3F4F6" stroke-width="20"/>
                                                <template x-for="seg in donutSegments" :key="seg.name">
                                                    <circle cx="65" cy="65" r="50"
                                                            fill="none"
                                                            :stroke="seg.color"
                                                            :stroke-width="hoveredCategoryIndex === seg.index ? 23 : 20"
                                                            :stroke-dasharray="seg.dashArray"
                                                            :stroke-dashoffset="seg.dashOffset"
                                                            class="transition-all duration-200 cursor-pointer"
                                                            @mouseenter="hoveredCategoryIndex = seg.index"
                                                            @mouseleave="hoveredCategoryIndex = null"/>
                                                </template>
                                            </svg>
                                            <!-- Center Readout on Hover -->
                                            <div class="absolute inset-0 flex flex-col items-center justify-center text-center pointer-events-none px-3">
                                                <template x-if="hoveredCategoryIndex !== null">
                                                    <div>
                                                        <p class="text-[10px] font-semibold text-[#6B7280] leading-tight" x-text="currentData.categories[hoveredCategoryIndex].name"></p>
                                                        <p class="text-xs font-extrabold text-[#1F2937] mt-0.5" x-text="currentData.categories[hoveredCategoryIndex].percent + '%'"></p>
                                                    </div>
                                                </template>
                                                <template x-if="hoveredCategoryIndex === null">
                                                    <div>
                                                        <p class="text-[9.5px] font-semibold text-[#6B7280]">Total</p>
                                                        <p class="text-[11px] font-extrabold text-[#1B5E3A]" x-text="currentData.categories.length + ' Kategori'"></p>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>

                                        <!-- Category Breakdown List -->
                                        <div class="flex-1 w-full space-y-2">
                                            <template x-for="(cat, idx) in currentData.categories" :key="cat.name">
                                                <div @mouseenter="hoveredCategoryIndex = idx"
                                                     @mouseleave="hoveredCategoryIndex = null"
                                                     :class="hoveredCategoryIndex === idx ? 'bg-stone-50' : ''"
                                                     class="flex items-start gap-2 p-1 rounded-lg transition-colors cursor-pointer">
                                                    <span class="w-2.5 h-2.5 rounded-full mt-1 shrink-0" :style="`background-color: ${cat.dotColor}`"></span>
                                                    <div class="min-w-0 leading-tight">
                                                        <p class="text-xs font-bold text-[#1F2937] truncate" x-text="cat.name"></p>
                                                        <p class="text-[11px] text-[#6B7280] mt-0.5">
                                                            <span x-text="formatRupiah(cat.revenue)"></span>
                                                            <span class="font-semibold text-stone-500" x-text="' (' + cat.percent + '%)'"></span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <!-- DETAIL 2: Performa Menu Terbaik -->
                                <div class="bg-white rounded-2xl p-5 border border-stone-200/70 shadow-soft flex flex-col">
                                    <div class="flex items-center justify-between gap-2 mb-4">
                                        <h2 class="text-[15px] font-bold text-[#1F2937] truncate">Performa Menu Terbaik</h2>

                                        <!-- Sort Filter Dropdown -->
                                        <div class="relative shrink-0" @click.away="menuSortOpen = false">
                                            <button type="button"
                                                    @click="menuSortOpen = !menuSortOpen"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg border border-stone-200 text-[11px] font-semibold text-stone-600 hover:bg-stone-50 transition cursor-pointer">
                                                <span x-text="menuSortBy === 'revenue' ? 'Berdasarkan Pendapatan' : 'Berdasarkan Porsi'"></span>
                                                <svg class="w-3 h-3 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </button>
                                            <div x-cloak x-show="menuSortOpen"
                                                 class="absolute right-0 mt-1.5 w-44 bg-white rounded-xl shadow-lg border border-stone-200/80 py-1 z-20">
                                                <button type="button"
                                                        @click="menuSortBy = 'revenue'; menuSortOpen = false"
                                                        :class="menuSortBy === 'revenue' ? 'bg-[#EBF5F0] text-[#1B5E3A] font-bold' : 'text-stone-700 hover:bg-stone-50'"
                                                        class="w-full text-left px-3 py-1.5 text-xs cursor-pointer">
                                                    Berdasarkan Pendapatan
                                                </button>
                                                <button type="button"
                                                        @click="menuSortBy = 'portions'; menuSortOpen = false"
                                                        :class="menuSortBy === 'portions' ? 'bg-[#EBF5F0] text-[#1B5E3A] font-bold' : 'text-stone-700 hover:bg-stone-50'"
                                                        class="w-full text-left px-3 py-1.5 text-xs cursor-pointer">
                                                    Berdasarkan Porsi
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Top 5 Menu Rows -->
                                    <div class="space-y-3 my-auto">
                                        <template x-for="(menu, idx) in sortedTopMenus" :key="menu.id">
                                            <div class="flex items-center gap-2.5 group">
                                                <!-- Rank Number -->
                                                <span class="w-4 text-xs font-extrabold text-[#6B7280] text-center shrink-0" x-text="idx + 1"></span>

                                                <!-- Food Thumbnail -->
                                                <div class="relative w-9 h-9 rounded-lg bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center border border-stone-200/80 shrink-0 overflow-hidden" aria-hidden="true">
                                                    <img x-show="menu.image" x-cloak :src="menu.image" :alt="menu.name" loading="lazy" class="absolute inset-0 w-full h-full object-cover">
                                                    <span>🍽️</span>
                                                </div>

                                                <!-- Name + Progress Bar + Values -->
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-baseline justify-between gap-2">
                                                        <p class="text-xs font-bold text-[#1F2937] truncate group-hover:text-[#1B5E3A] transition-colors" x-text="menu.name"></p>
                                                        <span class="text-[11px] font-semibold text-[#6B7280] shrink-0"
                                                              x-text="menuSortBy === 'revenue' ? (menu.portions + ' porsi') : formatRupiah(menu.revenue)"></span>
                                                    </div>
                                                    <div class="flex items-center justify-between gap-2 mt-1">
                                                        <div class="flex-1 h-1.5 bg-[#EBF5F0] rounded-full overflow-hidden">
                                                            <div class="h-full bg-[#229A55] rounded-full transition-all duration-300"
                                                                 :style="`width: ${Math.round(((menuSortBy === 'revenue' ? menu.revenue : menu.portions) / maxMenuMetric) * 100)}%;`"></div>
                                                        </div>
                                                        <span class="text-[11px] font-bold text-[#1F2937] shrink-0"
                                                              x-text="menuSortBy === 'revenue' ? formatRupiah(menu.revenue) : (menu.portions + ' porsi')"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <!-- DETAIL 3: Penjualan per Waktu (Heatmap Matrix) -->
                                <div class="bg-white rounded-2xl p-5 border border-stone-200/70 shadow-soft flex flex-col justify-between md:col-span-2 lg:col-span-1">
                                    <div class="flex items-center justify-between gap-2 mb-3">
                                        <h2 class="text-[15px] font-bold text-[#1F2937]">Penjualan per Waktu</h2>
                                        <span x-show="hoveredHeatCell" x-cloak
                                              class="text-[10.5px] font-semibold px-2 py-0.5 rounded-full bg-[#EBF5F0] text-[#1B5E3A]"
                                              x-text="hoveredHeatCell ? `${hoveredHeatCell.day}, ${hoveredHeatCell.time} • ${hoveredHeatCell.orders} pesanan` : ''"></span>
                                    </div>

                                    <!-- Heatmap Grid -->
                                    <div class="my-auto">
                                        <div class="space-y-1.5">
                                            <template x-for="(row, rIdx) in heatmapRows" :key="row.time">
                                                <div class="grid grid-cols-8 items-center gap-1.5">
                                                    <!-- Time Row Label -->
                                                    <span class="text-[10.5px] font-medium text-[#6B7280] text-right pr-1" x-text="row.time"></span>

                                                    <!-- 7 Day Cells -->
                                                    <template x-for="(lvl, cIdx) in row.levels" :key="row.time + '-' + cIdx">
                                                        <button type="button"
                                                                @mouseenter="hoveredHeatCell = { day: heatmapDaysFull[cIdx], time: row.range, orders: row.orders[cIdx] }"
                                                                @mouseleave="hoveredHeatCell = null"
                                                                @click="hoveredHeatCell = { day: heatmapDaysFull[cIdx], time: row.range, orders: row.orders[cIdx] }"
                                                                :aria-label="`${heatmapDaysFull[cIdx]} pukul ${row.range}, ${row.orders[cIdx]} pesanan`"
                                                                :class="getHeatColor(lvl)"
                                                                class="h-6 w-full rounded-[5px] transition-all duration-150 hover:ring-2 hover:scale-105 cursor-pointer">
                                                        </button>
                                                    </template>
                                                </div>
                                            </template>

                                            <!-- Day Column Labels -->
                                            <div class="grid grid-cols-8 items-center gap-1.5 pt-1">
                                                <span></span>
                                                <template x-for="day in heatmapDays" :key="day">
                                                    <span class="text-[10.5px] font-medium text-[#6B7280] text-center" x-text="day"></span>
                                                </template>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Heatmap Intensity Legend -->
                                    <div class="mt-4 pt-2 flex items-center justify-between gap-2.5">
                                        <span class="text-[10.5px] font-semibold text-[#6B7280]">Rendah</span>
                                        <div class="flex-1 h-2 rounded-full bg-gradient-to-r from-[#EBF5F0] via-[#6EC496] to-[#1B5E3A]"></div>
                                        <span class="text-[10.5px] font-semibold text-[#6B7280]">Tinggi</span>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- =============================================== -->
                        <!-- KOLOM KANAN (3/12): RINGKASAN + DAMPAK + INSIGHT-->
                        <!-- =============================================== -->
                        <div class="xl:col-span-3 space-y-4">

                            <!-- PANEL KANAN ATAS: Ringkasan Kinerja -->
                            <div class="bg-white rounded-2xl p-5 border border-stone-200/70 shadow-soft">
                                <div class="flex items-center justify-between gap-2 mb-4">
                                    <h2 class="text-[15px] font-bold text-[#1F2937]">Ringkasan Kinerja</h2>

                                    <div class="relative" @click.away="summaryFilterOpen = false">
                                        <button type="button"
                                                @click="summaryFilterOpen = !summaryFilterOpen"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg border border-stone-200 text-[11px] font-semibold text-stone-600 hover:bg-stone-50 transition cursor-pointer">
                                            <span x-text="summaryPeriod"></span>
                                            <svg class="w-3 h-3 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>
                                        <div x-cloak x-show="summaryFilterOpen"
                                             class="absolute right-0 mt-1.5 w-32 bg-white rounded-xl shadow-lg border border-stone-200/80 py-1 z-20">
                                            <button type="button" @click="summaryPeriod = 'Minggu ini'; selectDateRange('week_current'); summaryFilterOpen = false" class="w-full text-left px-3 py-1.5 text-xs hover:bg-[#EBF5F0] hover:text-[#1B5E3A] cursor-pointer">Minggu ini</button>
                                            <button type="button" @click="summaryPeriod = 'Bulan ini'; selectDateRange('month_current'); summaryFilterOpen = false" class="w-full text-left px-3 py-1.5 text-xs hover:bg-[#EBF5F0] hover:text-[#1B5E3A] cursor-pointer">Bulan ini</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="divide-y divide-stone-100">
                                    <!-- Metric 1: Rata-rata Nilai Pesanan -->
                                    <div class="py-3 first:pt-0 flex items-center justify-between gap-2.5">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-9 h-9 rounded-full bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center shrink-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-[11px] font-medium text-[#6B7280] truncate">Rata-rata Nilai Pesanan</p>
                                                <p class="text-sm font-extrabold text-[#1F2937] mt-0.5" x-text="formatRupiah(currentData.summary.aov)"></p>
                                            </div>
                                        </div>
                                        <span class="text-[11px] font-bold text-[#1B5E3A] shrink-0" x-text="'Perubahan: ' + currentData.summary.aovDelta"></span>
                                    </div>

                                    <!-- Metric 2: Rata-rata Porsi per Reservasi Selesai -->
                                    <div class="py-3 flex items-center justify-between gap-2.5">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-9 h-9 rounded-full bg-[#F3E8FF] text-[#7C3AED] flex items-center justify-center shrink-0">🍽️</div>
                                            <div class="min-w-0">
                                                <p class="text-[11px] font-medium text-[#6B7280] truncate">Rata-rata Porsi per Reservasi Selesai</p>
                                                <p class="text-sm font-extrabold text-[#1F2937] mt-0.5" x-text="currentData.summary.averagePortionsPerOrder + ' porsi'"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Metric 3: Reservasi Dibatalkan -->
                                    <div class="py-3 flex items-center justify-between gap-2.5">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-9 h-9 rounded-full bg-[#FFF1E6] text-[#E67E22] flex items-center justify-center shrink-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                                </svg>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-[11px] font-medium text-[#6B7280] truncate">Reservasi Dibatalkan</p>
                                                <p class="text-sm font-extrabold text-[#1F2937] mt-0.5">
                                                    <span x-text="currentData.summary.cancelledListings"></span>
                                                    <span class="text-xs font-semibold text-[#6B7280]">reservasi</span>
                                                </p>
                                            </div>
                                        </div>
                                        <span class="text-[11px] font-bold text-[#E11D48] shrink-0" x-text="currentData.summary.cancelledDelta + ' dibanding periode sebelumnya'"></span>
                                    </div>

                                    <!-- Metric 4: Reservasi Selesai -->
                                    <div class="py-3 last:pb-0 flex items-center justify-between gap-2.5">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-9 h-9 rounded-full bg-[#FEF3C7] text-[#D97706] flex items-center justify-center shrink-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                                </svg>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-[11px] font-medium text-[#6B7280] truncate">Reservasi Selesai</p>
                                                <p class="text-sm font-extrabold text-[#1F2937] mt-0.5 flex items-center gap-1">
                                                    <span x-text="formatNumber(currentData.kpis.completedOrders) + ' reservasi'"></span>
                                                </p>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>

                            <!-- PANEL KANAN TENGAH: Dampak Positif -->
                            <div class="bg-white rounded-2xl p-5 border border-stone-200/70 shadow-soft">
                                <div class="flex items-center justify-between gap-2 mb-3.5">
                                    <h2 class="text-[15px] font-bold text-[#1F2937]">Dampak Positif</h2>

                                    <div class="relative" @click.away="impactFilterOpen = false">
                                        <button type="button"
                                                @click="impactFilterOpen = !impactFilterOpen"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg border border-stone-200 text-[11px] font-semibold text-stone-600 hover:bg-stone-50 transition cursor-pointer">
                                            <span x-text="impactPeriod"></span>
                                            <svg class="w-3 h-3 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>
                                        <div x-cloak x-show="impactFilterOpen"
                                             class="absolute right-0 mt-1.5 w-32 bg-white rounded-xl shadow-lg border border-stone-200/80 py-1 z-20">
                                            <button type="button" @click="impactPeriod = 'Minggu ini'; selectDateRange('week_current'); impactFilterOpen = false" class="w-full text-left px-3 py-1.5 text-xs hover:bg-[#EBF5F0] hover:text-[#1B5E3A] cursor-pointer">Minggu ini</button>
                                            <button type="button" @click="impactPeriod = 'Bulan ini'; selectDateRange('month_current'); impactFilterOpen = false" class="w-full text-left px-3 py-1.5 text-xs hover:bg-[#EBF5F0] hover:text-[#1B5E3A] cursor-pointer">Bulan ini</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-2.5">
                                    <!-- Dampak 1: Porsi Diselamatkan -->
                                    <button type="button"
                                            @click="activeTab = 'dampak'"
                                            class="w-full flex items-center justify-between gap-3 p-3 rounded-xl bg-[#F4FAF6] hover:bg-[#EBF5F0] border border-[#DCF2E4]/70 transition text-left cursor-pointer group">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-9 h-9 rounded-full bg-white text-[#1B5E3A] flex items-center justify-center shrink-0 shadow-2xs">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                                </svg>
                                            </div>
                                            <div class="min-w-0 leading-tight">
                                                <p class="text-[13px] font-extrabold text-[#1F2937] truncate" x-text="formatNumber(currentData.impact.portions) + ' porsi makanan'"></p>
                                                <p class="text-[11px] text-[#6B7280] mt-0.5">berhasil diselamatkan</p>
                                            </div>
                                        </div>
                                        <svg class="w-4 h-4 text-stone-400 group-hover:text-[#1B5E3A] group-hover:translate-x-0.5 transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>

                                    <!-- Dampak 2: Emisi Karbon -->
                                    <button type="button"
                                            @click="activeTab = 'dampak'"
                                            class="w-full flex items-center justify-between gap-3 p-3 rounded-xl bg-[#F4FAF6] hover:bg-[#EBF5F0] border border-[#DCF2E4]/70 transition text-left cursor-pointer group">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-9 h-9 rounded-full bg-white text-[#1B5E3A] flex items-center justify-center shrink-0 shadow-2xs">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
                                                </svg>
                                            </div>
                                            <div class="min-w-0 leading-tight">
                                                <p class="text-[13px] font-extrabold text-[#1F2937] truncate" x-text="currentData.impact.co2 === null ? 'Belum tersedia' : formatNumber(currentData.impact.co2) + ' kg CO₂e'"></p>
                                                <p class="text-[11px] text-[#6B7280] mt-0.5">emisi karbon dihindari</p>
                                            </div>
                                        </div>
                                        <svg class="w-4 h-4 text-stone-400 group-hover:text-[#1B5E3A] group-hover:translate-x-0.5 transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>

                                    <!-- Dampak 3: Penggunaan Air -->
                                    <button type="button"
                                            @click="activeTab = 'dampak'"
                                            class="w-full flex items-center justify-between gap-3 p-3 rounded-xl bg-[#F4F9FD] hover:bg-[#EBF4FC] border border-[#DBEAFE]/80 transition text-left cursor-pointer group">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-9 h-9 rounded-full bg-white text-[#2563EB] flex items-center justify-center shrink-0 shadow-2xs">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3.1c-1.8 2.9-6 7.5-6 11.4a6 6 0 1012 0c0-3.9-4.2-8.5-6-11.4z"/>
                                                </svg>
                                            </div>
                                            <div class="min-w-0 leading-tight">
                                                <p class="text-[13px] font-extrabold text-[#1F2937] truncate" x-text="currentData.impact.water === null ? 'Belum tersedia' : formatNumber(currentData.impact.water) + ' liter air'"></p>
                                                <p class="text-[11px] text-[#6B7280] mt-0.5">penggunaan air dihemat</p>
                                            </div>
                                        </div>
                                        <svg class="w-4 h-4 text-stone-400 group-hover:text-[#2563EB] group-hover:translate-x-0.5 transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- PANEL KANAN BAWAH: Insight Minggu Ini -->
                            <div class="rounded-2xl p-5 bg-[#FFF9EE] border border-[#F5E4C3] shadow-soft">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="w-7 h-7 rounded-lg bg-[#FEF3C7] text-[#D97706] flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                        </svg>
                                    </span>
                                    <h3 class="text-[14px] font-bold text-[#92400E]">Insight Minggu Ini</h3>
                                </div>
                                <p class="text-xs font-bold text-[#1F2937] mb-1" x-text="weeklyInsights[0] ? weeklyInsights[0].title : 'Belum cukup data'"></p>
                                <p class="text-xs text-[#4B5563] leading-relaxed" x-text="weeklyInsights[0] ? weeklyInsights[0].body : 'Insight akan muncul setelah terdapat reservasi selesai atau catatan produksi.'"></p>
                                <button type="button"
                                        @click="insightsModalOpen = true"
                                        class="mt-3 inline-flex items-center gap-1 text-xs font-bold text-[#1B5E3A] hover:text-[#14472C] hover:underline cursor-pointer">
                                    <span>Lihat semua insight</span>
                                    <span aria-hidden="true">→</span>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- ======================================================= -->
                <!-- TAB 2: PENJUALAN (DETAIL PENDAPATAN & TRANSAKSI)        -->
                <!-- ======================================================= -->
                <div x-cloak x-show="activeTab === 'penjualan'" x-transition.opacity.duration.150ms class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white rounded-2xl p-5 border border-stone-200/70 shadow-soft">
                            <p class="text-xs font-medium text-[#6B7280]">Total Pendapatan Surplus</p>
                            <p class="text-2xl font-extrabold text-[#1F2937] mt-1" x-text="formatRupiah(currentData.kpis.revenue)"></p>
                            <p class="text-xs font-semibold text-[#1B5E3A] mt-1" x-text="'Perubahan: ' + currentData.kpis.revenueGrowth + ' dibanding periode sebelumnya'"></p>
                        </div>
                        <div class="bg-white rounded-2xl p-5 border border-stone-200/70 shadow-soft">
                            <p class="text-xs font-medium text-[#6B7280]">Rata-rata Nilai Pesanan (AOV)</p>
                            <p class="text-2xl font-extrabold text-[#1F2937] mt-1" x-text="formatRupiah(currentData.summary.aov)"></p>
                            <p class="text-xs font-semibold text-[#1B5E3A] mt-1" x-text="'Perubahan: ' + currentData.summary.aovDelta + ' dibanding periode sebelumnya'"></p>
                        </div>
                        <div class="bg-white rounded-2xl p-5 border border-stone-200/70 shadow-soft">
                            <p class="text-xs font-medium text-[#6B7280]">Reservasi Selesai</p>
                            <p class="text-2xl font-extrabold text-[#1F2937] mt-1" x-text="formatNumber(currentData.kpis.completedOrders)"></p>
                            <p class="text-xs font-medium text-[#6B7280] mt-1">Status completed pada database</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-5 border border-stone-200/70 shadow-soft">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">
                            <div>
                                <h2 class="text-[15px] font-bold text-[#1F2937]">Rincian Pendapatan Harian</h2>
                                <p class="text-xs text-[#6B7280]">Rekap penjualan makanan surplus pada periode <span class="font-semibold text-[#1F2937]" x-text="selectedDateLabel"></span>.</p>
                            </div>
                            <button type="button" @click="activeTab = 'ringkasan'" class="text-xs font-bold text-[#1B5E3A] hover:underline self-start sm:self-auto cursor-pointer">
                                ← Kembali ke Ringkasan
                            </button>
                        </div>
                        <div class="overflow-x-auto custom-scrollbar">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-stone-200/80 text-[11px] font-bold uppercase tracking-wider text-[#6B7280]">
                                        <th class="py-3 px-4">Tanggal</th>
                                        <th class="py-3 px-4">Jumlah Pesanan</th>
                                        <th class="py-3 px-4">Total Pendapatan</th>
                                        <th class="py-3 px-4">Rata-rata / Pesanan</th>
                                        <th class="py-3 px-4">Kontribusi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-stone-100 text-xs">
                                    <template x-for="row in currentData.revenueTrend" :key="row.fullDate">
                                        <tr class="hover:bg-[#FAFBF9] transition">
                                            <td class="py-3 px-4 font-bold text-[#1F2937]" x-text="row.fullDate"></td>
                                            <td class="py-3 px-4 text-[#4B5563]" x-text="row.orders + ' pesanan'"></td>
                                            <td class="py-3 px-4 font-bold text-[#1B5E3A]" x-text="formatRupiah(row.value)"></td>
                                            <td class="py-3 px-4 text-[#4B5563]" x-text="row.orders > 0 ? formatRupiah(Math.round(row.value / row.orders)) : 'Rp 0'"></td>
                                            <td class="py-3 px-4 w-48">
                                                <div class="flex items-center gap-2">
                                                    <div class="flex-1 h-2 bg-[#EBF5F0] rounded-full overflow-hidden">
                                                        <div class="h-full bg-[#229A55] rounded-full"
                                                             :style="`width: ${currentData.kpis.revenue > 0 ? Math.min(100, Math.round((row.value / currentData.kpis.revenue) * 100)) : 0}%;`"></div>
                                                    </div>
                                                    <span class="font-semibold text-[#6B7280] w-10 text-right"
                                                          x-text="(currentData.kpis.revenue > 0 ? ((row.value / currentData.kpis.revenue) * 100).toFixed(1) : '0.0') + '%'"></span>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ======================================================= -->
                <!-- TAB 3: SURPLUS & REKOMENDASI PRODUKSI                   -->
                <!-- ======================================================= -->
                <div x-cloak x-show="activeTab === 'surplus'" x-transition.opacity.duration.150ms class="space-y-4">
                    <div class="bg-[#F4FAF6] border border-[#DCF2E4] rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-[#1B5E3A] text-white flex items-center justify-center shrink-0 text-lg">📊</div>
                            <div>
                                <h2 class="text-sm font-extrabold text-[#1B5E3A]">Analisis Pencegahan Surplus & Rekomendasi Produksi</h2>
                                <p class="text-xs text-[#374151] mt-0.5 leading-relaxed">
                                    Dihitung otomatis dari riwayat <strong>Catatan Produksi Harian</strong> dan <strong>Daftar Makanan Surplus</strong> untuk membantu menekan potensi food waste pada hari berikutnya.
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('business.production-records') }}"
                           class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-[#1B5E3A] hover:bg-[#14472C] text-white text-xs font-bold shrink-0 transition">
                            <span>Buka Produksi Harian</span>
                            <span>→</span>
                        </a>
                    </div>

                    <div class="bg-white rounded-2xl p-5 border border-stone-200/70 shadow-soft">
                        <h3 class="text-[15px] font-bold text-[#1F2937] mb-4">Efisiensi Produksi vs Penyelamatan Surplus per Menu</h3>
                        <div class="overflow-x-auto custom-scrollbar">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-stone-200/80 text-[11px] font-bold uppercase tracking-wider text-[#6B7280]">
                                        <th class="py-3 px-4">Nama Menu</th>
                                        <th class="py-3 px-4">Total Produksi</th>
                                        <th class="py-3 px-4">Terjual Reguler</th>
                                        <th class="py-3 px-4">Diselamatkan (Surplus)</th>
                                        <th class="py-3 px-4">Tidak Terjual</th>
                                        <th class="py-3 px-4">Rekomendasi Produksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-stone-100 text-xs">
                                    <template x-for="item in surplusWasteAnalysis" :key="item.menu">
                                        <tr class="hover:bg-[#FAFBF9] transition">
                                            <td class="py-3.5 px-4 font-bold text-[#1F2937]" x-text="item.menu"></td>
                                            <td class="py-3.5 px-4 text-[#4B5563]" x-text="item.produced + ' porsi'"></td>
                                            <td class="py-3.5 px-4 text-[#4B5563]" x-text="item.soldRegular + ' porsi'"></td>
                                            <td class="py-3.5 px-4 font-bold text-[#1B5E3A]" x-text="item.rescued + ' porsi'"></td>
                                            <td class="py-3.5 px-4">
                                                <span :class="item.wasted >= 10 ? 'bg-rose-50 text-rose-700' : 'bg-amber-50 text-amber-700'"
                                                      class="px-2 py-0.5 rounded-full text-[11px] font-bold"
                                                      x-text="item.wasted + ' porsi'"></span>
                                            </td>
                                            <td class="py-3.5 px-4">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-[#EBF5F0] text-[#1B5E3A] font-semibold text-[11.5px]">
                                                    <span>💡</span>
                                                    <span x-text="item.recommendation"></span>
                                                </span>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ======================================================= -->
                <!-- TAB 4: PELANGGAN                                        -->
                <!-- ======================================================= -->
                <div x-cloak x-show="activeTab === 'pelanggan'" x-transition.opacity.duration.150ms class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white rounded-2xl p-5 border border-stone-200/70 shadow-soft">
                            <p class="text-xs font-medium text-[#6B7280]">Pelanggan Unik</p>
                            <p class="text-2xl font-extrabold text-[#1F2937] mt-1" x-text="currentData.kpis.newCustomers + ' pelanggan'"></p>
                            <p class="text-xs font-medium text-[#6B7280] mt-1">Berdasarkan reservasi valid pada periode ini</p>
                        </div>
                        <div class="bg-white rounded-2xl p-5 border border-stone-200/70 shadow-soft">
                            <p class="text-xs font-medium text-[#6B7280]">Reservasi Selesai</p>
                            <p class="text-2xl font-extrabold text-[#1F2937] mt-1" x-text="formatNumber(currentData.kpis.completedOrders)"></p>
                            <p class="text-xs font-medium text-[#6B7280] mt-1">Status completed pada database</p>
                        </div>
                        <div class="bg-white rounded-2xl p-5 border border-stone-200/70 shadow-soft">
                            <p class="text-xs font-medium text-[#6B7280]">Total Belanja Pelanggan</p>
                            <p class="text-2xl font-extrabold text-[#1F2937] mt-1" x-text="formatRupiah(currentData.kpis.revenue)"></p>
                            <p class="text-xs text-[#6B7280] mt-1">Dari reservasi yang selesai</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-5 border border-stone-200/70 shadow-soft">
                        <h3 class="text-[15px] font-bold text-[#1F2937] mb-4">Pelanggan Paling Aktif Menyelamatkan Makanan</h3>
                        <div class="overflow-x-auto custom-scrollbar">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-stone-200/80 text-[11px] font-bold uppercase tracking-wider text-[#6B7280]">
                                        <th class="py-3 px-4">Pelanggan</th>
                                        <th class="py-3 px-4">Total Reservasi</th>
                                        <th class="py-3 px-4">Porsi Diselamatkan</th>
                                        <th class="py-3 px-4">Total Belanja</th>
                                        <th class="py-3 px-4">Terakhir Memesan</th>
                                        <th class="py-3 px-4">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-stone-100 text-xs">
                                    <template x-for="cust in topCustomers" :key="cust.id">
                                        <tr class="hover:bg-[#FAFBF9] transition">
                                            <td class="py-3 px-4 font-bold text-[#1F2937]" x-text="cust.name"></td>
                                            <td class="py-3 px-4 text-[#4B5563]" x-text="cust.orders + ' pesanan'"></td>
                                            <td class="py-3 px-4 font-bold text-[#1B5E3A]" x-text="cust.portions + ' porsi'"></td>
                                            <td class="py-3 px-4 font-semibold text-[#1F2937]" x-text="formatRupiah(cust.spent)"></td>
                                            <td class="py-3 px-4 text-[#6B7280]" x-text="cust.lastOrder"></td>
                                            <td class="py-3 px-4">
                                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#EBF5F0] text-[#1B5E3A]" x-text="cust.badge"></span>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ======================================================= -->
                <!-- TAB 5: DAMPAK LINGKUNGAN (FOOD RESCUE IMPACT)           -->
                <!-- ======================================================= -->
                <div x-cloak x-show="activeTab === 'dampak'" x-transition.opacity.duration.150ms class="space-y-4">
                    <div class="bg-gradient-to-r from-[#1B5E3A] to-[#2E7D32] rounded-2xl p-6 text-white shadow-card flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/15 text-xs font-bold mb-2">
                                🌱 Kontribusi SDG 12 & 13
                            </span>
                            <h2 class="text-xl sm:text-2xl font-extrabold">Dampak Positif {{ $business->business_name }}</h2>
                            <p class="text-sm text-emerald-100 mt-1 max-w-2xl">
                                Pada periode <strong x-text="selectedDateLabel"></strong>, bisnismu berhasil menyelamatkan <strong x-text="formatNumber(currentData.impact.portions) + ' porsi makanan'"></strong> agar tidak berakhir menjadi limbah pangan.
                            </p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-xs rounded-2xl px-5 py-3.5 border border-white/20 text-center shrink-0">
                            <p class="text-[11px] uppercase tracking-wider text-emerald-100 font-semibold">Data dampak lanjutan</p>
                            <p class="text-lg font-black mt-0.5">Belum tersedia</p>
                            <p class="text-[11px] text-emerald-100">Memerlukan faktor konversi terverifikasi</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white rounded-2xl p-5 border border-stone-200/70 shadow-soft">
                            <div class="w-11 h-11 rounded-2xl bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center text-xl mb-3">🥗</div>
                            <p class="text-xs font-semibold text-[#6B7280]">Makanan Terselamatkan</p>
                            <p class="text-2xl font-extrabold text-[#1F2937] mt-1" x-text="formatNumber(currentData.impact.portions) + ' porsi'"></p>
                            <p class="text-xs text-[#6B7280] mt-1">Dihitung dari jumlah porsi reservasi berstatus completed</p>
                        </div>
                        <div class="bg-white rounded-2xl p-5 border border-stone-200/70 shadow-soft">
                            <div class="w-11 h-11 rounded-2xl bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center text-xl mb-3">🌍</div>
                            <p class="text-xs font-semibold text-[#6B7280]">Emisi Karbon Dihindari</p>
                            <p class="text-2xl font-extrabold text-[#1F2937] mt-1">Belum tersedia</p>
                            <p class="text-xs text-[#6B7280] mt-1">Database belum menyimpan faktor emisi per jenis makanan</p>
                        </div>
                        <div class="bg-white rounded-2xl p-5 border border-stone-200/70 shadow-soft">
                            <div class="w-11 h-11 rounded-2xl bg-[#EFF6FF] text-[#2563EB] flex items-center justify-center text-xl mb-3">💧</div>
                            <p class="text-xs font-semibold text-[#6B7280]">Penggunaan Air Dihemat</p>
                            <p class="text-2xl font-extrabold text-[#1F2937] mt-1">Belum tersedia</p>
                            <p class="text-xs text-[#6B7280] mt-1">Database belum menyimpan faktor penggunaan air</p>
                        </div>
                    </div>
                </div>

                <!-- ======================================================= -->
                <!-- TAB 6: PERFORMA MENU                                    -->
                <!-- ======================================================= -->
                <div x-cloak x-show="activeTab === 'performa-menu'" x-transition.opacity.duration.150ms class="space-y-4">
                    <div class="bg-white rounded-2xl p-5 border border-stone-200/70 shadow-soft">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                            <div>
                                <h2 class="text-[15px] font-bold text-[#1F2937]">Peringkat & Detail Performa Menu</h2>
                                <p class="text-xs text-[#6B7280]">Bandingkan kontribusi pendapatan, porsi terselamatkan, dan tingkat serapan tiap menu.</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button"
                                        @click="menuSortBy = 'revenue'"
                                        :class="menuSortBy === 'revenue' ? 'bg-[#1B5E3A] text-white' : 'bg-stone-100 text-stone-600 hover:bg-stone-200'"
                                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer">
                                    Pendapatan
                                </button>
                                <button type="button"
                                        @click="menuSortBy = 'portions'"
                                        :class="menuSortBy === 'portions' ? 'bg-[#1B5E3A] text-white' : 'bg-stone-100 text-stone-600 hover:bg-stone-200'"
                                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer">
                                    Porsi Terjual
                                </button>
                            </div>
                        </div>

                        <div class="overflow-x-auto custom-scrollbar">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-stone-200/80 text-[11px] font-bold uppercase tracking-wider text-[#6B7280]">
                                        <th class="py-3 px-4">#</th>
                                        <th class="py-3 px-4">Menu</th>
                                        <th class="py-3 px-4">Kategori</th>
                                        <th class="py-3 px-4">Pendapatan</th>
                                        <th class="py-3 px-4">Porsi Diselamatkan</th>
                                        <th class="py-3 px-4">Rata-rata Diskon</th>
                                        <th class="py-3 px-4">Tingkat Serapan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-stone-100 text-xs">
                                    <template x-for="(menu, idx) in sortedTopMenus" :key="'tbl-' + menu.id">
                                        <tr class="hover:bg-[#FAFBF9] transition">
                                            <td class="py-3 px-4 font-extrabold text-[#6B7280]" x-text="idx + 1"></td>
                                            <td class="py-3 px-4">
                                                <div class="flex items-center gap-2.5">
                                                    <div class="relative w-9 h-9 rounded-lg bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center border border-stone-200/80 overflow-hidden shrink-0" aria-hidden="true">
                                                        <img x-show="menu.image" x-cloak :src="menu.image" :alt="menu.name" loading="lazy" class="absolute inset-0 w-full h-full object-cover">
                                                        <span>🍽️</span>
                                                    </div>
                                                    <span class="font-bold text-[#1F2937]" x-text="menu.name"></span>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4 text-[#6B7280]" x-text="menu.category"></td>
                                            <td class="py-3 px-4 font-bold text-[#1B5E3A]" x-text="formatRupiah(menu.revenue)"></td>
                                            <td class="py-3 px-4 font-semibold text-[#1F2937]" x-text="menu.portions + ' porsi'"></td>
                                            <td class="py-3 px-4">
                                                <span class="px-2 py-0.5 rounded-full bg-[#FEF3C7] text-[#B45309] font-bold text-[11px]" x-text="menu.avgDiscount === null ? 'Diskon tidak tersedia' : menu.avgDiscount + '% OFF'"></span>
                                            </td>
                                            <td class="py-3 px-4 w-44">
                                                <div class="flex items-center gap-2">
                                                    <div class="flex-1 h-2 bg-[#EBF5F0] rounded-full overflow-hidden">
                                                        <div class="h-full bg-[#229A55] rounded-full" :style="`width: ${menu.sellThrough === null ? 0 : menu.sellThrough}%;`"></div>
                                                    </div>
                                                    <span class="font-bold text-[#1B5E3A] w-9 text-right" x-text="menu.sellThrough === null ? '—' : menu.sellThrough + '%'"></span>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- ======================================================= -->
    <!-- 4. MODAL: SEMUA INSIGHT & REKOMENDASI PRODUKSI          -->
    <!-- ======================================================= -->
    <div x-cloak x-show="insightsModalOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-stone-900/50 backdrop-blur-xs"
         @keydown.escape.window="insightsModalOpen = false">
        <div @click.away="insightsModalOpen = false"
             class="bg-white rounded-3xl max-w-2xl w-full shadow-modal border border-stone-200/80 overflow-hidden max-h-[88vh] flex flex-col">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-stone-100 flex items-center justify-between bg-[#FDFBF7]">
                <div class="flex items-center gap-2.5">
                    <span class="w-9 h-9 rounded-xl bg-[#FEF3C7] text-[#D97706] flex items-center justify-center text-base">💡</span>
                    <div>
                        <h3 class="text-base font-extrabold text-[#1F2937]">Semua Insight & Rekomendasi Minggu Ini</h3>
                        <p class="text-xs text-[#6B7280]">Periode <span x-text="selectedDateLabel"></span> • {{ $business->business_name }}</p>
                    </div>
                </div>
                <button type="button"
                        @click="insightsModalOpen = false"
                        aria-label="Tutup modal insight"
                        class="p-2 rounded-xl text-stone-400 hover:text-stone-700 hover:bg-stone-100 transition cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto custom-scrollbar space-y-3.5">
                <template x-for="item in weeklyInsights" :key="item.id">
                    <div class="p-4 rounded-2xl border border-stone-200/80 bg-[#FAFBF9] hover:border-[#1B5E3A]/30 transition">
                        <div class="flex items-center justify-between gap-2 mb-1.5">
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold" :class="item.badgeColor" x-text="item.badge"></span>
                        </div>
                        <h4 class="text-sm font-extrabold text-[#1F2937]" x-text="item.title"></h4>
                        <p class="text-xs text-[#4B5563] mt-1 leading-relaxed" x-text="item.body"></p>
                        <div class="mt-2.5 pt-2.5 border-t border-stone-200/60 flex items-center gap-2 text-xs font-semibold text-[#1B5E3A]">
                            <span>✓ Saran Aksi:</span>
                            <span x-text="item.action"></span>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-stone-100 bg-white flex items-center justify-end gap-2.5">
                <a href="{{ route('business.production-records') }}"
                   class="px-4 py-2 rounded-xl border border-stone-200 text-xs font-bold text-[#1F2937] hover:bg-stone-50 transition">
                    Sesuaikan Produksi Harian
                </a>
                <button type="button"
                        @click="insightsModalOpen = false"
                        class="px-5 py-2 rounded-xl bg-[#1B5E3A] hover:bg-[#14472C] text-white text-xs font-bold transition cursor-pointer">
                    Mengerti
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
