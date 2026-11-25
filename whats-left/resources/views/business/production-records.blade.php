@extends('layouts.business')

@section('title', 'Catatan Produksi - Sunny Bakery')

@section('content')
<div class="flex h-screen overflow-hidden bg-[#FAF7F2]"
     x-data="{
         // Sidebar Mobile & Desktop Collapse State
         mobileSidebarOpen: false,
         sidebarCollapsed: false,

         // Topbar Dropdowns
         notificationDropdownOpen: false,
         profileDropdownOpen: false,

         // Date Range Selector
         dateRangeIndex: 1,
         dateRanges: [
             '25 Apr - 1 Mei 2025',
             '2 - 8 Mei 2025',
             '9 - 15 Mei 2025'
         ],
         prevDateRange() {
             if (this.dateRangeIndex > 0) {
                 this.dateRangeIndex--;
                 this.$dispatch('show-toast', { msg: 'Menampilkan periode ' + this.dateRanges[this.dateRangeIndex] });
             }
         },
         nextDateRange() {
             if (this.dateRangeIndex < this.dateRanges.length - 1) {
                 this.dateRangeIndex++;
                 this.$dispatch('show-toast', { msg: 'Menampilkan periode ' + this.dateRanges[this.dateRangeIndex] });
             }
         },

         // Category Filter
         selectedCategory: 'Semua Kategori',
         categoryDropdownOpen: false,
         categoriesList: [
             'Semua Kategori',
             'Roti & Kue',
             'Makanan Berat',
             'Makanan Sehat',
             'Snack',
             'Minuman',
             'Dessert'
         ],

         // Quick Period Tabs ('all', 'today', 'yesterday', 'week', 'month')
         activePeriodTab: 'all',

         // Load More toggle
         showExtraRecords: false,

         // Right Panel Period Filters
         summaryPeriod: 'Minggu ini',
         summaryPeriodOpen: false,
         categoryChartPeriod: 'Minggu ini',
         categoryChartPeriodOpen: false,
         trendPeriod: 'Minggu ini',
         trendPeriodOpen: false,

         // Chart Hover States
         hoveredDonutIndex: null,
         hoveredTrendIndex: null,

         // Modals
         isFormModalOpen: false,
         isEditMode: false,
         editingId: null,
         deleteTarget: null,
         isGuideModalOpen: false,
         isTrendInsightOpen: false,

         // Form State
         form: {
             product: '',
             category: 'Roti & Kue',
             dateLabel: '2 Mei 2025',
             dateGroup: 'Hari ini, 2 Mei 2025',
             periodKey: 'today',
             time: '08.00',
             produced: '',
             unit: 'pcs',
             surplus: '',
             image: 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=200&q=80'
         },

         // Master Production Records Data (Dummy / Statis)
         records: [
             {
                 id: 1,
                 dateGroup: 'Hari ini, 2 Mei 2025',
                 dateLabel: '2 Mei 2025',
                 time: '08.00',
                 periodKey: 'today',
                 product: 'Croissant Butter',
                 category: 'Roti & Kue',
                 produced: 40,
                 unit: 'pcs',
                 surplus: 12,
                 image: 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=200&q=80'
             },
             {
                 id: 2,
                 dateGroup: 'Hari ini, 2 Mei 2025',
                 dateLabel: '2 Mei 2025',
                 time: '08.30',
                 periodKey: 'today',
                 product: 'Chicken Sandwich',
                 category: 'Makanan Berat',
                 produced: 20,
                 unit: 'pcs',
                 surplus: 8,
                 image: 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?auto=format&fit=crop&w=200&q=80'
             },
             {
                 id: 3,
                 dateGroup: 'Hari ini, 2 Mei 2025',
                 dateLabel: '2 Mei 2025',
                 time: '09.00',
                 periodKey: 'today',
                 product: 'Chicken Teriyaki Bowl',
                 category: 'Makanan Berat',
                 produced: 15,
                 unit: 'porsi',
                 surplus: 5,
                 image: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=200&q=80'
             },
             {
                 id: 4,
                 dateGroup: 'Hari ini, 2 Mei 2025',
                 dateLabel: '2 Mei 2025',
                 time: '10.00',
                 periodKey: 'today',
                 product: 'Salad Sayur Segar',
                 category: 'Makanan Sehat',
                 produced: 10,
                 unit: 'porsi',
                 surplus: 3,
                 image: 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=200&q=80'
             },
             {
                 id: 5,
                 dateGroup: 'Hari ini, 2 Mei 2025',
                 dateLabel: '2 Mei 2025',
                 time: '11.00',
                 periodKey: 'today',
                 product: 'Donut Mix',
                 category: 'Snack',
                 produced: 30,
                 unit: 'pcs',
                 surplus: 10,
                 image: 'https://images.unsplash.com/photo-1527515862127-a4fc05baf7a5?auto=format&fit=crop&w=200&q=80'
             },
             {
                 id: 6,
                 dateGroup: 'Kemarin, 1 Mei 2025',
                 dateLabel: '1 Mei 2025',
                 time: '08.00',
                 periodKey: 'yesterday',
                 product: 'Es Kopi Susu Gula Aren',
                 category: 'Minuman',
                 produced: 25,
                 unit: 'cup',
                 surplus: 7,
                 image: 'https://images.unsplash.com/photo-1517256064527-09c73fc73e38?auto=format&fit=crop&w=200&q=80'
             },
             {
                 id: 7,
                 dateGroup: 'Kemarin, 1 Mei 2025',
                 dateLabel: '1 Mei 2025',
                 time: '08.30',
                 periodKey: 'yesterday',
                 product: 'Red Velvet Slice',
                 category: 'Dessert',
                 produced: 12,
                 unit: 'pcs',
                 surplus: 0,
                 image: 'https://images.unsplash.com/photo-1586788680434-30d324b2d46f?auto=format&fit=crop&w=200&q=80'
             },
             {
                 id: 8,
                 dateGroup: 'Kemarin, 1 Mei 2025',
                 dateLabel: '1 Mei 2025',
                 time: '09.00',
                 periodKey: 'yesterday',
                 product: 'Roti Cokelat',
                 category: 'Roti & Kue',
                 produced: 35,
                 unit: 'pcs',
                 surplus: 15,
                 image: 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=200&q=80'
             },
             {
                 id: 9,
                 dateGroup: 'Rabu, 30 April 2025',
                 dateLabel: '30 Apr 2025',
                 time: '08.15',
                 periodKey: 'week',
                 product: 'Cinnamon Roll Glaze',
                 category: 'Roti & Kue',
                 produced: 28,
                 unit: 'pcs',
                 surplus: 6,
                 image: 'https://images.unsplash.com/photo-1509365465985-25d11c17e812?auto=format&fit=crop&w=200&q=80',
                 isExtra: true
             },
             {
                 id: 10,
                 dateGroup: 'Rabu, 30 April 2025',
                 dateLabel: '30 Apr 2025',
                 time: '09.30',
                 periodKey: 'week',
                 product: 'Matcha Latte Dingin',
                 category: 'Minuman',
                 produced: 20,
                 unit: 'cup',
                 surplus: 4,
                 image: 'https://images.unsplash.com/photo-1515823064-d6e0c04616a7?auto=format&fit=crop&w=200&q=80',
                 isExtra: true
             },
             {
                 id: 11,
                 dateGroup: 'Selasa, 29 April 2025',
                 dateLabel: '29 Apr 2025',
                 time: '10.00',
                 periodKey: 'month',
                 product: 'Tiramisu Cup',
                 category: 'Dessert',
                 produced: 18,
                 unit: 'cup',
                 surplus: 3,
                 image: 'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?auto=format&fit=crop&w=200&q=80',
                 isExtra: true
             }
         ],

         // Summary Metrics by Period
         summaryPresets: {
             'Hari ini': { produced: 115, surplus: 38, avgPercent: '33.0%', topCategory: 'Roti & Kue', topCategoryCount: '40 item' },
             'Minggu ini': { produced: 320, surplus: 98, avgPercent: '30.6%', topCategory: 'Roti & Kue', topCategoryCount: '120 item' },
             'Bulan ini': { produced: 1280, surplus: 342, avgPercent: '26.7%', topCategory: 'Roti & Kue', topCategoryCount: '465 item' }
         },

         get currentSummary() {
             return this.summaryPresets[this.summaryPeriod] || this.summaryPresets['Minggu ini'];
         },

         // Surplus per Category Donut Data (Matching Mockup)
         categorySurplusData: [
             { name: 'Roti & Kue', count: 120, percent: 38.7, color: '#FACC15' },
             { name: 'Makanan Berat', count: 90, percent: 29.0, color: '#60A5FA' },
             { name: 'Snack', count: 60, percent: 19.4, color: '#A78BFA' },
             { name: 'Minuman', count: 30, percent: 9.7, color: '#FB923C' },
             { name: 'Dessert', count: 20, percent: 6.5, color: '#F472B6' }
         ],

         // Compute SVG Donut Segments (circumference = 2 * pi * 36 ≈ 226.19)
         get donutSegments() {
             const total = this.categorySurplusData.reduce((acc, item) => acc + item.count, 0);
             const circumference = 2 * Math.PI * 36;
             let cumulativeOffset = 0;

             return this.categorySurplusData.map((item, idx) => {
                 const fraction = item.count / total;
                 const length = fraction * circumference;
                 const segment = {
                     ...item,
                     index: idx,
                     dashArray: `${length.toFixed(2)} ${(circumference - length).toFixed(2)}`,
                     dashOffset: (-cumulativeOffset).toFixed(2)
                 };
                 cumulativeOffset += length;
                 return segment;
             });
         },

         // Surplus Trend (%) Line Chart Data (26 Apr - 2 Mei, Matching Mockup)
         trendData: [
             { label: '26 Apr', value: 34, x: 34, y: 66 },
             { label: '27 Apr', value: 50, x: 75, y: 42 },
             { label: '28 Apr', value: 57, x: 116, y: 31 },
             { label: '29 Apr', value: 42, x: 157, y: 54 },
             { label: '30 Apr', value: 30, x: 198, y: 72 },
             { label: '1 Mei', value: 38, x: 239, y: 60 },
             { label: '2 Mei', value: 31, x: 280, y: 70 }
         ],

         get trendLinePath() {
             return this.trendData.map((p, i) => (i === 0 ? 'M ' : 'L ') + p.x + ' ' + p.y).join(' ');
         },

         get trendAreaPath() {
             const first = this.trendData[0];
             const last = this.trendData[this.trendData.length - 1];
             return this.trendLinePath + ` L ${last.x} 110 L ${first.x} 110 Z`;
         },

         // Filtered & Grouped Records
         get filteredRecords() {
             return this.records.filter(item => {
                 if (!this.showExtraRecords && item.isExtra && this.activePeriodTab === 'all') {
                     return false;
                 }
                 const matchCategory = this.selectedCategory === 'Semua Kategori' || item.category === this.selectedCategory;
                 let matchPeriod = true;
                 if (this.activePeriodTab === 'today') matchPeriod = item.periodKey === 'today';
                 else if (this.activePeriodTab === 'yesterday') matchPeriod = item.periodKey === 'yesterday';
                 else if (this.activePeriodTab === 'week') matchPeriod = ['today', 'yesterday', 'week'].includes(item.periodKey);
                 else if (this.activePeriodTab === 'month') matchPeriod = true;

                 return matchCategory && matchPeriod;
             });
         },

         get groupedRecords() {
             const groups = {};
             this.filteredRecords.forEach(rec => {
                 if (!groups[rec.dateGroup]) {
                     groups[rec.dateGroup] = [];
                 }
                 groups[rec.dateGroup].push(rec);
             });
             return Object.keys(groups).map(dateGroup => ({
                 dateGroup,
                 items: groups[dateGroup]
             }));
         },

         // Helpers
         surplusPercent(item) {
             if (!item.produced || item.produced <= 0) return 0;
             return Math.round((item.surplus / item.produced) * 100);
         },

         get formSurplusPercent() {
             const p = Number(this.form.produced);
             const s = Number(this.form.surplus);
             if (p > 0 && s >= 0) {
                 return Math.min(100, Math.round((s / p) * 100));
             }
             return 0;
         },

         formatNumber(num) {
             return Number(num).toLocaleString('id-ID');
         },

         categoryBadgeClass(category) {
             const map = {
                 'Roti & Kue': 'bg-[#FEF3C7] text-[#B45309] border-[#FDE68A]',
                 'Makanan Berat': 'bg-[#E0F2FE] text-[#0369A1] border-[#BAE6FD]',
                 'Makanan Sehat': 'bg-[#DCFCE7] text-[#15803D] border-[#BBF7D0]',
                 'Snack': 'bg-[#F3E8FF] text-[#7E22CE] border-[#E9D5FF]',
                 'Minuman': 'bg-[#FFEDD5] text-[#C2410C] border-[#FED7AA]',
                 'Dessert': 'bg-[#FCE7F3] text-[#BE185D] border-[#FBCFE8]'
             };
             return map[category] || 'bg-stone-100 text-stone-700 border-stone-200';
         },

         // CRUD Actions (Frontend State)
         openAddModal() {
             this.isEditMode = false;
             this.editingId = null;
             this.form = {
                 product: '',
                 category: 'Roti & Kue',
                 dateLabel: '2 Mei 2025',
                 dateGroup: 'Hari ini, 2 Mei 2025',
                 periodKey: 'today',
                 time: '12.00',
                 produced: '',
                 unit: 'pcs',
                 surplus: '0',
                 image: 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=200&q=80'
             };
             this.isFormModalOpen = true;
         },

         openEditModal(item) {
             this.isEditMode = true;
             this.editingId = item.id;
             this.form = {
                 product: item.product,
                 category: item.category,
                 dateLabel: item.dateLabel,
                 dateGroup: item.dateGroup,
                 periodKey: item.periodKey,
                 time: item.time,
                 produced: item.produced,
                 unit: item.unit,
                 surplus: item.surplus,
                 image: item.image
             };
             this.isFormModalOpen = true;
         },

         saveRecord() {
             const prod = Number(this.form.produced);
             const surp = Number(this.form.surplus);
             if (!this.form.product.trim() || prod <= 0) {
                 this.$dispatch('show-toast', { msg: 'Mohon isi nama produk dan jumlah produksi yang valid.', type: 'error' });
                 return;
             }
             if (surp < 0 || surp > prod) {
                 this.$dispatch('show-toast', { msg: 'Jumlah sisa (surplus) tidak boleh melebihi jumlah diproduksi.', type: 'error' });
                 return;
             }

             const categoryImages = {
                 'Roti & Kue': 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=200&q=80',
                 'Makanan Berat': 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=200&q=80',
                 'Makanan Sehat': 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=200&q=80',
                 'Snack': 'https://images.unsplash.com/photo-1527515862127-a4fc05baf7a5?auto=format&fit=crop&w=200&q=80',
                 'Minuman': 'https://images.unsplash.com/photo-1517256064527-09c73fc73e38?auto=format&fit=crop&w=200&q=80',
                 'Dessert': 'https://images.unsplash.com/photo-1586788680434-30d324b2d46f?auto=format&fit=crop&w=200&q=80'
             };

             if (this.isEditMode && this.editingId !== null) {
                 const idx = this.records.findIndex(r => r.id === this.editingId);
                 if (idx !== -1) {
                     this.records[idx] = {
                         ...this.records[idx],
                         product: this.form.product.trim(),
                         category: this.form.category,
                         time: this.form.time,
                         produced: prod,
                         unit: this.form.unit,
                         surplus: surp
                     };
                 }
                 this.$dispatch('show-toast', { msg: `Catatan produksi '${this.form.product}' berhasil diperbarui!` });
             } else {
                 this.records.unshift({
                     id: Date.now(),
                     dateGroup: 'Hari ini, 2 Mei 2025',
                     dateLabel: '2 Mei 2025',
                     time: this.form.time || '12.00',
                     periodKey: 'today',
                     product: this.form.product.trim(),
                     category: this.form.category,
                     produced: prod,
                     unit: this.form.unit,
                     surplus: surp,
                     image: categoryImages[this.form.category] || this.form.image
                 });
                 this.$dispatch('show-toast', { msg: `Catatan produksi '${this.form.product}' berhasil ditambahkan!` });
             }

             this.isFormModalOpen = false;
         },

         confirmDelete(item) {
             this.deleteTarget = item;
         },

         executeDelete() {
             if (!this.deleteTarget) return;
             const name = this.deleteTarget.product;
             this.records = this.records.filter(r => r.id !== this.deleteTarget.id);
             this.deleteTarget = null;
             this.$dispatch('show-toast', { msg: `Catatan produksi '${name}' berhasil dihapus.` });
         },

         exportCsv() {
             const headers = ['Tanggal', 'Jam', 'Produk', 'Kategori', 'Jumlah Diproduksi', 'Satuan', 'Sisa (Surplus)', 'Persentase Surplus'];
             const rows = this.filteredRecords.map(r => [
                 r.dateLabel,
                 r.time,
                 r.product,
                 r.category,
                 r.produced,
                 r.unit,
                 r.surplus,
                 this.surplusPercent(r) + '%'
             ]);
             const q = String.fromCharCode(34);
             const csv = [headers, ...rows].map(row => row.map(v => q + String(v).split(q).join(q + q) + q).join(',')).join('\n');
             const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
             const url = URL.createObjectURL(blob);
             const a = document.createElement('a');
             a.href = url;
             a.download = 'catatan-produksi-sunny-bakery.csv';
             a.click();
             URL.revokeObjectURL(url);
             this.$dispatch('show-toast', { msg: 'Laporan Catatan Produksi (.csv) berhasil diunduh!' });
         }
     }">

    <!-- ======================================================= -->
    <!-- 1. SIDEBAR KIRI (DESKTOP DOCKED & MOBILE DRAWER)        -->
    <!-- ======================================================= -->

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div x-cloak x-show="mobileSidebarOpen"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileSidebarOpen = false"
         class="fixed inset-0 z-40 bg-stone-900/50 backdrop-blur-xs lg:hidden">
    </div>

    <!-- Sidebar Container -->
    <aside :class="[
               mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
               sidebarCollapsed ? 'lg:w-20' : 'lg:w-64'
           ]"
           class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-stone-200/80 flex flex-col justify-between transition-all duration-300 ease-in-out lg:static lg:z-auto shrink-0">

        <div class="px-4 pt-6 pb-4 flex flex-col h-full overflow-y-auto custom-scrollbar">

            <!-- Logo What's Left & Label 'Business' -->
            <div class="flex items-center justify-between mb-6 px-1.5">
                <a href="{{ url('/?view=dashboard') }}" class="inline-flex flex-col group">
                    <div class="flex items-center text-[22px] font-black tracking-tight leading-none">
                        <span class="text-[#F59E0B] text-xs -mt-2 mr-0.5 font-bold" x-show="!sidebarCollapsed">✨</span>
                        <span class="text-[#1B5E3A]" x-show="!sidebarCollapsed">What's</span>
                        <span class="text-[#E67E22] ml-1" x-show="!sidebarCollapsed">Left</span>
                        <span class="text-[#1B5E3A] text-xl font-black" x-show="sidebarCollapsed" x-cloak>WL</span>
                        <span class="text-[#F59E0B] text-xs -mt-2 ml-0.5 font-bold" x-show="!sidebarCollapsed">✨</span>
                    </div>
                    <div class="mt-1.5 self-center" x-show="!sidebarCollapsed">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-[#EBF5F0] text-[#1B5E3A] tracking-wide">
                            Business
                        </span>
                    </div>
                </a>

                <!-- Mobile Close Button -->
                <button type="button"
                        @click="mobileSidebarOpen = false"
                        aria-label="Tutup menu sidebar"
                        class="p-1.5 rounded-lg text-stone-400 hover:text-stone-700 hover:bg-stone-100 lg:hidden">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Navigation Menu Items -->
            <nav class="space-y-1 flex-1" aria-label="Navigasi Merchant">

                <!-- 1. Dashboard -->
                <a href="{{ url('/?view=dashboard') }}"
                   class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Dashboard</span>
                </a>

                <!-- 2. Makanan Surplus -->
                <a href="{{ url('/?view=makanan-surplus') }}"
                   class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Makanan Surplus</span>
                </a>

                <!-- 3. Produksi Harian (AKTIF / MENYALA HIJAU) -->
                <a href="{{ url('/?view=produksi-harian') }}"
                   aria-current="page"
                   class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold bg-[#EBF5F0] text-[#1B5E3A] shadow-2xs transition-all duration-150">
                    <svg class="w-5 h-5 shrink-0 text-[#1B5E3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Produksi Harian</span>
                </a>

                <!-- 4. Reservasi (Badge Angka 5) -->
                <a href="{{ url('/?view=reservations-management') }}"
                   class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium">
                    <div class="flex items-center gap-3 min-w-0">
                        <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span x-show="!sidebarCollapsed" class="truncate">Reservasi</span>
                    </div>
                    <span x-show="!sidebarCollapsed"
                          class="w-5 h-5 rounded-full bg-[#EA580C] text-white text-[11px] font-bold flex items-center justify-center shrink-0 shadow-xs">
                        5
                    </span>
                </a>

                <!-- 5. Pelanggan -->
                <button type="button"
                        @click="$dispatch('show-toast', { msg: 'Menu Pelanggan dipilih' })"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Pelanggan</span>
                </button>

                <div class="my-2.5 border-t border-stone-100"></div>

                <!-- 6. Analitik -->
                <button type="button"
                        @click="$dispatch('show-toast', { msg: 'Menu Analitik dipilih' })"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Analitik</span>
                </button>

                <!-- 7. Promo & Diskon -->
                <button type="button"
                        @click="$dispatch('show-toast', { msg: 'Menu Promo & Diskon dipilih' })"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Promo & Diskon</span>
                </button>

                <!-- 8. Review -->
                <button type="button"
                        @click="$dispatch('show-toast', { msg: 'Menu Review dipilih' })"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Review</span>
                </button>

                <div class="my-2.5 border-t border-stone-100"></div>

                <!-- 9. Pengaturan Toko -->
                <button type="button"
                        @click="$dispatch('show-toast', { msg: 'Menu Pengaturan Toko dipilih' })"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Pengaturan Toko</span>
                </button>

                <!-- 10. Akun & Tim -->
                <button type="button"
                        @click="$dispatch('show-toast', { msg: 'Menu Akun & Tim dipilih' })"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Akun & Tim</span>
                </button>
            </nav>

            <!-- Bottom Education Card (Sesuai Screenshot) -->
            <div x-show="!sidebarCollapsed"
                 class="mt-6 p-4 rounded-2xl bg-[#F4F9F5] border border-[#DCF2E4] relative overflow-hidden">
                <p class="text-[13px] font-bold text-[#1B5E3A] leading-snug">
                    Catat produksi harian dengan konsisten
                </p>

                <!-- Eco Bag & Food Illustration -->
                <div class="my-3.5 flex items-center justify-center">
                    <div class="relative w-24 h-16 flex items-center justify-center">
                        <!-- Green Eco Paper Bag -->
                        <div class="w-12 h-14 rounded-lg bg-[#86EFAC]/55 border border-[#22C55E]/50 flex flex-col items-center justify-center shadow-2xs relative">
                            <div class="w-5 h-2.5 border-2 border-[#1B5E3A] border-b-0 rounded-t-full -mt-6 mb-1.5 bg-transparent"></div>
                            <span class="text-base leading-none">🌱</span>
                        </div>
                        <span class="absolute -bottom-0.5 left-1 text-xl drop-shadow-xs">🥗</span>
                        <span class="absolute -bottom-0.5 right-1 text-lg drop-shadow-xs">🥐</span>
                    </div>
                </div>

                <p class="text-[11px] text-[#6B7280] leading-relaxed">
                    Data yang akurat membantumu mengelola surplus dengan lebih efektif.
                </p>

                <button type="button"
                        @click="isGuideModalOpen = true"
                        class="mt-2.5 inline-flex items-center gap-1 text-[11.5px] font-bold text-[#1B5E3A] hover:text-[#14472C] transition-colors group">
                    <span>Pelajari lebih lanjut</span>
                    <span class="transition-transform group-hover:translate-x-0.5">→</span>
                </button>
            </div>
        </div>
    </aside>

    <!-- ======================================================= -->
    <!-- 2. MAIN WORKSPACE (TOPBAR + CONTENT 2-KOLOM)            -->
    <!-- ======================================================= -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <!-- TOP NAVBAR -->
        <header class="h-16 bg-white border-b border-stone-200/80 px-4 sm:px-6 lg:px-8 flex items-center justify-between shrink-0 z-30">
            <!-- Left: Hamburger Toggle -->
            <div class="flex items-center gap-3">
                <button type="button"
                        @click="mobileSidebarOpen = true"
                        aria-label="Buka menu navigasi"
                        class="p-2 rounded-xl text-stone-600 hover:bg-stone-100 hover:text-stone-900 transition lg:hidden">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <button type="button"
                        @click="sidebarCollapsed = !sidebarCollapsed"
                        aria-label="Perkecil atau perluas sidebar"
                        class="hidden lg:inline-flex p-2 rounded-xl text-stone-500 hover:bg-stone-100 hover:text-stone-900 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <!-- Right: Notification Bell + Merchant Profile -->
            <div class="flex items-center gap-3 sm:gap-4">

                <!-- Notification Bell Dropdown -->
                <div class="relative" @click.away="notificationDropdownOpen = false">
                    <button type="button"
                            @click="notificationDropdownOpen = !notificationDropdownOpen"
                            aria-label="Notifikasi"
                            class="relative p-2 rounded-xl text-stone-600 hover:bg-stone-100 hover:text-[#1B5E3A] transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute top-1 right-1 w-4 h-4 rounded-full bg-[#EA580C] text-white text-[10px] font-bold flex items-center justify-center ring-2 ring-white">
                            5
                        </span>
                    </button>

                    <!-- Notification Popover -->
                    <div x-cloak x-show="notificationDropdownOpen"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-stone-200/80 py-2 z-50">
                        <div class="px-4 py-2.5 border-b border-stone-100 flex items-center justify-between">
                            <span class="text-sm font-bold text-[#1F2937]">Notifikasi Produksi</span>
                            <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-[#EBF5F0] text-[#1B5E3A]">5 baru</span>
                        </div>
                        <div class="divide-y divide-stone-100 max-h-64 overflow-y-auto custom-scrollbar">
                            <div class="px-4 py-3 hover:bg-stone-50 transition">
                                <p class="text-xs font-semibold text-[#1F2937]">Surplus Roti Cokelat mencapai 43%</p>
                                <p class="text-[11px] text-[#6B7280] mt-0.5">Pertimbangkan menurunkan produksi besok menjadi 25 pcs.</p>
                            </div>
                            <div class="px-4 py-3 hover:bg-stone-50 transition">
                                <p class="text-xs font-semibold text-[#1F2937]">Red Velvet Slice habis terjual (0% surplus) 🎉</p>
                                <p class="text-[11px] text-[#6B7280] mt-0.5">Produksi 12 pcs hari kemarin optimal.</p>
                            </div>
                            <div class="px-4 py-3 hover:bg-stone-50 transition">
                                <p class="text-xs font-semibold text-[#1F2937]">Pengingat input produksi sore</p>
                                <p class="text-[11px] text-[#6B7280] mt-0.5">Catat sisa batch siang sebelum jam 16.00 WIB.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Divider -->
                <div class="h-7 w-px bg-stone-200/80 hidden sm:block"></div>

                <!-- Merchant Profile Dropdown -->
                <div class="relative" @click.away="profileDropdownOpen = false">
                    <button type="button"
                            @click="profileDropdownOpen = !profileDropdownOpen"
                            class="flex items-center gap-2.5 p-1 pr-2 rounded-xl hover:bg-stone-50 transition">
                        <div class="w-9 h-9 rounded-full bg-[#DCF2E4] border border-[#1B5E3A]/20 flex items-center justify-center overflow-hidden shrink-0">
                            <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=100&q=80"
                                 alt="Logo Sunny Bakery"
                                 class="w-full h-full object-cover">
                        </div>
                        <div class="text-left hidden sm:block leading-tight">
                            <p class="text-sm font-bold text-[#1F2937]">Sunny Bakery</p>
                            <p class="text-[11px] font-medium text-[#6B7280]">Owner</p>
                        </div>
                        <svg class="w-4 h-4 text-stone-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Profile Menu -->
                    <div x-cloak x-show="profileDropdownOpen"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-stone-200/80 py-1.5 z-50">
                        <a href="{{ url('/?view=dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-stone-700 hover:bg-stone-50">
                            <span>🏠</span> Dashboard Merchant
                        </a>
                        <a href="{{ url('/?view=makanan-surplus') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-stone-700 hover:bg-stone-50">
                            <span>🥖</span> Makanan Surplus
                        </a>
                        <a href="{{ url('/') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-stone-700 hover:bg-stone-50">
                            <span>🌐</span> Halaman Konsumen
                        </a>
                        <div class="my-1 border-t border-stone-100"></div>
                        <a href="{{ url('/?view=login') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-red-600 hover:bg-red-50">
                            <span>🚪</span> Keluar
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- MAIN SCROLLABLE AREA -->
        <main class="flex-1 overflow-y-auto custom-scrollbar p-4 sm:p-6 lg:p-7">
            <div class="max-w-[1440px] mx-auto flex flex-col xl:flex-row gap-6 items-start">

                <!-- =============================================== -->
                <!-- KOLOM KIRI: HEADER, FILTER & TABEL PRODUKSI     -->
                <!-- =============================================== -->
                <div class="flex-1 min-w-0 w-full space-y-5">

                    <!-- Page Header Row -->
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl sm:text-[26px] font-extrabold text-[#1F2937] tracking-tight flex items-center gap-2">
                                <span>Catatan Produksi</span>
                                <span class="text-xl" aria-hidden="true">🌱</span>
                            </h1>
                            <p class="text-sm text-[#6B7280] mt-1">
                                Kelola dan catat semua produksi makanan harian tokomu.
                            </p>
                        </div>

                        <!-- Right CTA Buttons (Stacked on Desktop like Mockup, Side-by-side on Mobile) -->
                        <div class="flex flex-row sm:flex-col items-stretch sm:items-end gap-2.5 shrink-0">
                            <button type="button"
                                    @click="openAddModal()"
                                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#1B5E3A] hover:bg-[#14472C] text-white text-sm font-bold shadow-sm shadow-[#1B5E3A]/20 transition active:scale-[0.98]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                </svg>
                                <span>Tambah Catatan Produksi</span>
                            </button>

                            <button type="button"
                                    @click="exportCsv()"
                                    class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-white hover:bg-stone-50 text-[#1F2937] text-xs sm:text-sm font-semibold border border-stone-200/90 shadow-2xs transition active:scale-[0.98]">
                                <svg class="w-4 h-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                <span>Export Laporan</span>
                            </button>
                        </div>
                    </div>

                    <!-- Baris Filter Tanggal & Kategori -->
                    <div class="flex flex-wrap items-center gap-3">
                        <!-- Date Range Selector Pill -->
                        <div class="inline-flex items-center bg-white border border-stone-200/90 rounded-xl shadow-2xs overflow-hidden">
                            <div class="flex items-center gap-2 px-3.5 py-2 text-sm font-semibold text-[#1F2937]">
                                <svg class="w-4 h-4 text-stone-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span x-text="dateRanges[dateRangeIndex]"></span>
                            </div>
                            <div class="flex items-center border-l border-stone-200/80">
                                <button type="button"
                                        @click="prevDateRange()"
                                        :disabled="dateRangeIndex === 0"
                                        :class="dateRangeIndex === 0 ? 'opacity-35 cursor-not-allowed' : 'hover:bg-stone-50'"
                                        aria-label="Periode sebelumnya"
                                        class="px-2.5 py-2 text-stone-600 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </button>
                                <button type="button"
                                        @click="nextDateRange()"
                                        :disabled="dateRangeIndex === dateRanges.length - 1"
                                        :class="dateRangeIndex === dateRanges.length - 1 ? 'opacity-35 cursor-not-allowed' : 'hover:bg-stone-50'"
                                        aria-label="Periode berikutnya"
                                        class="px-2.5 py-2 text-stone-600 border-l border-stone-100 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Category Dropdown Filter -->
                        <div class="relative" @click.away="categoryDropdownOpen = false">
                            <button type="button"
                                    @click="categoryDropdownOpen = !categoryDropdownOpen"
                                    class="inline-flex items-center justify-between gap-3 min-w-[175px] px-3.5 py-2 rounded-xl bg-white border border-stone-200/90 text-sm font-semibold text-[#1F2937] hover:bg-stone-50 shadow-2xs transition">
                                <span x-text="selectedCategory"></span>
                                <svg class="w-4 h-4 text-stone-400 transition-transform duration-150"
                                     :class="categoryDropdownOpen ? 'rotate-180' : ''"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div x-cloak x-show="categoryDropdownOpen"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute left-0 mt-1.5 w-52 bg-white rounded-xl shadow-xl border border-stone-200/80 py-1.5 z-40">
                                <template x-for="cat in categoriesList" :key="cat">
                                    <button type="button"
                                            @click="selectedCategory = cat; categoryDropdownOpen = false"
                                            :class="selectedCategory === cat ? 'bg-[#EBF5F0] text-[#1B5E3A] font-bold' : 'text-stone-700 hover:bg-stone-50 font-medium'"
                                            class="w-full text-left px-3.5 py-2 text-xs sm:text-sm flex items-center justify-between transition">
                                        <span x-text="cat"></span>
                                        <svg x-show="selectedCategory === cat" class="w-4 h-4 text-[#1B5E3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Periode Cepat -->
                    <div class="flex items-center gap-1 sm:gap-2 overflow-x-auto no-scrollbar border-b border-stone-200/80 pb-px">
                        <button type="button"
                                @click="activePeriodTab = 'all'"
                                :class="activePeriodTab === 'all' ? 'border-[#1B5E3A] text-[#1B5E3A] font-bold' : 'border-transparent text-[#6B7280] hover:text-[#1F2937] font-medium'"
                                class="px-3 py-2.5 text-sm border-b-2 transition whitespace-nowrap">
                            Semua
                        </button>

                        <button type="button"
                                @click="activePeriodTab = 'today'"
                                :class="activePeriodTab === 'today' ? 'border-[#1B5E3A] text-[#1B5E3A] font-bold' : 'border-transparent text-[#6B7280] hover:text-[#1F2937] font-medium'"
                                class="inline-flex items-center gap-1.5 px-3 py-2.5 text-sm border-b-2 transition whitespace-nowrap">
                            <span>Hari Ini</span>
                            <span class="px-1.5 py-0.5 rounded-md text-[11px] font-bold bg-stone-200/70 text-stone-600">8</span>
                        </button>

                        <button type="button"
                                @click="activePeriodTab = 'yesterday'"
                                :class="activePeriodTab === 'yesterday' ? 'border-[#1B5E3A] text-[#1B5E3A] font-bold' : 'border-transparent text-[#6B7280] hover:text-[#1F2937] font-medium'"
                                class="inline-flex items-center gap-1.5 px-3 py-2.5 text-sm border-b-2 transition whitespace-nowrap">
                            <span>Kemarin</span>
                            <span class="px-1.5 py-0.5 rounded-md text-[11px] font-bold bg-stone-200/70 text-stone-600">12</span>
                        </button>

                        <button type="button"
                                @click="activePeriodTab = 'week'"
                                :class="activePeriodTab === 'week' ? 'border-[#1B5E3A] text-[#1B5E3A] font-bold' : 'border-transparent text-[#6B7280] hover:text-[#1F2937] font-medium'"
                                class="inline-flex items-center gap-1.5 px-3 py-2.5 text-sm border-b-2 transition whitespace-nowrap">
                            <span>Minggu Ini</span>
                            <span class="px-1.5 py-0.5 rounded-md text-[11px] font-bold bg-stone-200/70 text-stone-600">32</span>
                        </button>

                        <button type="button"
                                @click="activePeriodTab = 'month'"
                                :class="activePeriodTab === 'month' ? 'border-[#1B5E3A] text-[#1B5E3A] font-bold' : 'border-transparent text-[#6B7280] hover:text-[#1F2937] font-medium'"
                                class="inline-flex items-center gap-1.5 px-3 py-2.5 text-sm border-b-2 transition whitespace-nowrap">
                            <span>Bulan Ini</span>
                            <span class="px-1.5 py-0.5 rounded-md text-[11px] font-bold bg-stone-200/70 text-stone-600">128</span>
                        </button>
                    </div>

                    <!-- =========================================== -->
                    <!-- TABEL CATATAN PRODUKSI (DIKELOMPOKKAN HARI) -->
                    <!-- =========================================== -->
                    <div class="bg-white rounded-2xl border border-stone-200/80 shadow-card overflow-hidden">
                        <div class="overflow-x-auto custom-scrollbar">
                            <table class="w-full text-left border-collapse min-w-[740px]">
                                <thead>
                                    <tr class="bg-[#FAF8F5] border-b border-stone-200/70 text-[12px] font-semibold text-[#6B7280]">
                                        <th class="py-3.5 pl-5 pr-3 w-[118px]">Tanggal</th>
                                        <th class="py-3.5 px-3">Produk</th>
                                        <th class="py-3.5 px-3 w-[135px]">Kategori</th>
                                        <th class="py-3.5 px-3 text-center w-[135px]">Jumlah Diproduksi</th>
                                        <th class="py-3.5 px-3 text-center w-[80px]">Satuan</th>
                                        <th class="py-3.5 px-3 text-center w-[120px]">Sisa (Surplus)</th>
                                        <th class="py-3.5 pl-3 pr-5 text-center w-[95px]">Aksi</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-stone-100">
                                    <!-- Empty State -->
                                    <tr x-show="groupedRecords.length === 0" x-cloak>
                                        <td colspan="7" class="py-12 px-6 text-center">
                                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-[#EBF5F0] text-2xl mb-3">🌱</div>
                                            <p class="text-sm font-bold text-[#1F2937]">Belum ada catatan produksi</p>
                                            <p class="text-xs text-[#6B7280] mt-1">Ubah filter kategori/periode atau tambahkan catatan produksi baru.</p>
                                        </td>
                                    </tr>

                                    <!-- Grouped Rows -->
                                    <template x-for="group in groupedRecords" :key="group.dateGroup">
                                        <tr class="contents">
                                            <td colspan="7" class="p-0">
                                                <!-- Date Group Header Bar -->
                                                <div class="px-5 py-2.5 bg-[#FDFBF8] border-y border-stone-100 first:border-t-0">
                                                    <span class="text-xs font-bold text-[#1F2937]" x-text="group.dateGroup"></span>
                                                </div>

                                                <!-- Group Records -->
                                                <table class="w-full text-left border-collapse min-w-[740px]">
                                                    <tbody class="divide-y divide-stone-100">
                                                        <template x-for="item in group.items" :key="item.id">
                                                            <tr class="hover:bg-[#FAF7F2]/60 transition-colors">
                                                                <!-- Tanggal & Jam -->
                                                                <td class="py-3.5 pl-5 pr-3 w-[118px] align-middle">
                                                                    <div class="text-xs font-semibold text-[#1F2937]" x-text="item.dateLabel"></div>
                                                                    <div class="text-[11px] font-medium text-[#6B7280] mt-0.5" x-text="item.time"></div>
                                                                </td>

                                                                <!-- Produk (Thumbnail + Nama) -->
                                                                <td class="py-3.5 px-3 align-middle">
                                                                    <div class="flex items-center gap-3">
                                                                        <img :src="item.image"
                                                                             :alt="item.product"
                                                                             class="w-11 h-11 rounded-xl object-cover border border-stone-200/80 shrink-0 bg-stone-100">
                                                                        <span class="text-sm font-bold text-[#1F2937] leading-snug" x-text="item.product"></span>
                                                                    </div>
                                                                </td>

                                                                <!-- Kategori Badge -->
                                                                <td class="py-3.5 px-3 w-[135px] align-middle">
                                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold border whitespace-nowrap"
                                                                          :class="categoryBadgeClass(item.category)"
                                                                          x-text="item.category">
                                                                    </span>
                                                                </td>

                                                                <!-- Jumlah Diproduksi -->
                                                                <td class="py-3.5 px-3 w-[135px] text-center align-middle">
                                                                    <span class="text-sm font-bold text-[#1F2937]" x-text="formatNumber(item.produced)"></span>
                                                                </td>

                                                                <!-- Satuan -->
                                                                <td class="py-3.5 px-3 w-[80px] text-center align-middle">
                                                                    <span class="text-xs font-medium text-[#6B7280]" x-text="item.unit"></span>
                                                                </td>

                                                                <!-- Sisa (Surplus) -->
                                                                <td class="py-3.5 px-3 w-[120px] text-center align-middle">
                                                                    <span class="text-sm font-bold whitespace-nowrap"
                                                                          :class="item.surplus === 0 ? 'text-[#E11D48]' : 'text-[#1B5E3A]'"
                                                                          x-text="`${formatNumber(item.surplus)} (${surplusPercent(item)}%)`">
                                                                    </span>
                                                                </td>

                                                                <!-- Aksi (Edit & Delete) -->
                                                                <td class="py-3.5 pl-3 pr-5 w-[95px] align-middle">
                                                                    <div class="flex items-center justify-center gap-1.5">
                                                                        <button type="button"
                                                                                @click="openEditModal(item)"
                                                                                :aria-label="'Edit catatan ' + item.product"
                                                                                class="p-1.5 rounded-lg text-stone-500 hover:text-[#1B5E3A] hover:bg-[#EBF5F0] border border-transparent hover:border-[#DCF2E4] transition">
                                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                                            </svg>
                                                                        </button>
                                                                        <button type="button"
                                                                                @click="confirmDelete(item)"
                                                                                :aria-label="'Hapus catatan ' + item.product"
                                                                                class="p-1.5 rounded-lg text-stone-500 hover:text-red-600 hover:bg-red-50 border border-transparent hover:border-red-100 transition">
                                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                                            </svg>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </template>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <!-- Bottom "Muat lebih banyak" Button -->
                        <div class="px-5 py-3.5 bg-white border-t border-stone-100 flex justify-center">
                            <button type="button"
                                    @click="showExtraRecords = !showExtraRecords"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-[#1F2937] bg-white hover:bg-stone-50 border border-stone-200/90 shadow-2xs transition">
                                <span x-text="showExtraRecords ? 'Tampilkan lebih sedikit' : 'Muat lebih banyak'"></span>
                                <svg class="w-3.5 h-3.5 text-stone-500 transition-transform duration-200"
                                     :class="showExtraRecords ? 'rotate-180' : ''"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- =============================================== -->
                <!-- KOLOM KANAN: RINGKASAN, DONUT & TREN SURPLUS    -->
                <!-- =============================================== -->
                <aside class="w-full xl:w-[345px] 2xl:w-[365px] shrink-0 space-y-5">

                    <!-- 1. CARD RINGKASAN PRODUKSI -->
                    <div class="bg-white rounded-2xl border border-stone-200/80 shadow-card p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-[15px] font-extrabold text-[#1F2937]">Ringkasan Produksi</h2>

                            <!-- Period Selector -->
                            <div class="relative" @click.away="summaryPeriodOpen = false">
                                <button type="button"
                                        @click="summaryPeriodOpen = !summaryPeriodOpen"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-[#FAF7F2] border border-stone-200/80 text-xs font-semibold text-[#1F2937] hover:bg-stone-100 transition">
                                    <span x-text="summaryPeriod"></span>
                                    <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div x-cloak x-show="summaryPeriodOpen"
                                     class="absolute right-0 mt-1 w-32 bg-white rounded-xl shadow-lg border border-stone-200/80 py-1 z-30">
                                    <template x-for="p in ['Hari ini', 'Minggu ini', 'Bulan ini']" :key="p">
                                        <button type="button"
                                                @click="summaryPeriod = p; summaryPeriodOpen = false"
                                                :class="summaryPeriod === p ? 'bg-[#EBF5F0] text-[#1B5E3A] font-bold' : 'text-stone-700 hover:bg-stone-50'"
                                                class="w-full text-left px-3 py-1.5 text-xs transition"
                                                x-text="p">
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- 4 Metric Mini-Cards (2x2 Grid) -->
                        <div class="grid grid-cols-2 gap-3">
                            <!-- Total Diproduksi -->
                            <div class="p-3.5 rounded-xl bg-[#FAFBF9] border border-stone-100 flex items-start gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M7 12v5a2 2 0 002 2h6a2 2 0 002-2v-5"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[10.5px] font-medium text-[#6B7280] leading-tight">Total Diproduksi</p>
                                    <p class="text-lg font-extrabold text-[#1F2937] leading-tight mt-1" x-text="formatNumber(currentSummary.produced)"></p>
                                    <p class="text-[11px] font-medium text-[#6B7280]">item</p>
                                </div>
                            </div>

                            <!-- Total Surplus -->
                            <div class="p-3.5 rounded-xl bg-[#F4FAF6] border border-[#DCF2E4]/70 flex items-start gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-[#DCF2E4] text-[#1B5E3A] flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 22V12m0 0C12 7 7 4 4 4c0 5 3 8 8 8zm0 0c0-5 5-8 8-8 0 5-3 8-8 8z"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[10.5px] font-medium text-[#6B7280] leading-tight">Total Surplus</p>
                                    <p class="text-lg font-extrabold text-[#1F2937] leading-tight mt-1" x-text="formatNumber(currentSummary.surplus)"></p>
                                    <p class="text-[10.5px] font-semibold text-[#1B5E3A] truncate" x-text="`item (${currentSummary.avgPercent})`"></p>
                                </div>
                            </div>

                            <!-- Rata-rata Surplus -->
                            <div class="p-3.5 rounded-xl bg-[#FFFDF7] border border-[#FEF3C7] flex items-start gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-[#FEF3C7] text-[#D97706] flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[10.5px] font-medium text-[#6B7280] leading-tight">Rata-rata Surplus</p>
                                    <p class="text-lg font-extrabold text-[#1F2937] leading-tight mt-1.5" x-text="currentSummary.avgPercent"></p>
                                </div>
                            </div>

                            <!-- Kategori Terbanyak -->
                            <div class="p-3.5 rounded-xl bg-[#FAF8FF] border border-[#F3E8FF] flex items-start gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-[#F3E8FF] text-[#7E22CE] flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[10.5px] font-medium text-[#6B7280] leading-tight">Kategori Terbanyak</p>
                                    <p class="text-xs font-extrabold text-[#1F2937] leading-snug mt-1 truncate" x-text="currentSummary.topCategory"></p>
                                    <p class="text-[10.5px] font-medium text-[#6B7280]" x-text="currentSummary.topCategoryCount"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. CARD SURPLUS PER KATEGORI (DONUT CHART) -->
                    <div class="bg-white rounded-2xl border border-stone-200/80 shadow-card p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-[15px] font-extrabold text-[#1F2937]">Surplus per Kategori</h2>

                            <div class="relative" @click.away="categoryChartPeriodOpen = false">
                                <button type="button"
                                        @click="categoryChartPeriodOpen = !categoryChartPeriodOpen"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-[#FAF7F2] border border-stone-200/80 text-xs font-semibold text-[#1F2937] hover:bg-stone-100 transition">
                                    <span x-text="categoryChartPeriod"></span>
                                    <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div x-cloak x-show="categoryChartPeriodOpen"
                                     class="absolute right-0 mt-1 w-32 bg-white rounded-xl shadow-lg border border-stone-200/80 py-1 z-30">
                                    <template x-for="p in ['Hari ini', 'Minggu ini', 'Bulan ini']" :key="p">
                                        <button type="button"
                                                @click="categoryChartPeriod = p; categoryChartPeriodOpen = false"
                                                :class="categoryChartPeriod === p ? 'bg-[#EBF5F0] text-[#1B5E3A] font-bold' : 'text-stone-700 hover:bg-stone-50'"
                                                class="w-full text-left px-3 py-1.5 text-xs transition"
                                                x-text="p">
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <!-- Interactive SVG Donut Chart -->
                            <div class="relative w-[108px] h-[108px] shrink-0">
                                <svg viewBox="0 0 100 100" class="w-full h-full -rotate-90">
                                    <circle cx="50" cy="50" r="36" fill="transparent" stroke="#F3F4F6" stroke-width="18"></circle>
                                    <template x-for="seg in donutSegments" :key="seg.name">
                                        <circle cx="50" cy="50" r="36"
                                                fill="transparent"
                                                :stroke="seg.color"
                                                :stroke-width="hoveredDonutIndex === seg.index ? 20 : 18"
                                                :stroke-dasharray="seg.dashArray"
                                                :stroke-dashoffset="seg.dashOffset"
                                                @mouseenter="hoveredDonutIndex = seg.index"
                                                @mouseleave="hoveredDonutIndex = null"
                                                class="transition-all duration-200 cursor-pointer">
                                        </circle>
                                    </template>
                                </svg>
                                <!-- Center Label on Hover -->
                                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none text-center px-2">
                                    <template x-if="hoveredDonutIndex !== null">
                                        <div>
                                            <p class="text-xs font-extrabold text-[#1F2937] leading-none" x-text="categorySurplusData[hoveredDonutIndex].percent + '%'"></p>
                                            <p class="text-[9px] font-semibold text-[#6B7280] mt-0.5 truncate max-w-[54px]" x-text="categorySurplusData[hoveredDonutIndex].count + ' item'"></p>
                                        </div>
                                    </template>
                                    <template x-if="hoveredDonutIndex === null">
                                        <div>
                                            <p class="text-xs font-extrabold text-[#1F2937] leading-none">320</p>
                                            <p class="text-[9px] font-medium text-[#6B7280] mt-0.5">Total</p>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Legend List -->
                            <div class="flex-1 min-w-0 space-y-2">
                                <template x-for="(item, idx) in categorySurplusData" :key="item.name">
                                    <div @mouseenter="hoveredDonutIndex = idx"
                                         @mouseleave="hoveredDonutIndex = null"
                                         :class="hoveredDonutIndex === idx ? 'bg-stone-50' : ''"
                                         class="flex items-center justify-between gap-2 text-[11.5px] px-1.5 py-0.5 -mx-1.5 rounded-md transition cursor-default">
                                        <div class="flex items-center gap-1.5 min-w-0">
                                            <span class="w-2.5 h-2.5 rounded-full shrink-0" :style="`background-color: ${item.color}`"></span>
                                            <span class="font-semibold text-[#1F2937] truncate" x-text="item.name"></span>
                                        </div>
                                        <span class="font-medium text-[#6B7280] whitespace-nowrap shrink-0" x-text="`${item.count} (${item.percent}%)`"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- 3. CARD TREN SURPLUS (%) (LINE CHART) -->
                    <div class="bg-white rounded-2xl border border-stone-200/80 shadow-card p-5">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-[15px] font-extrabold text-[#1F2937]">Tren Surplus (%)</h2>

                            <div class="relative" @click.away="trendPeriodOpen = false">
                                <button type="button"
                                        @click="trendPeriodOpen = !trendPeriodOpen"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-[#FAF7F2] border border-stone-200/80 text-xs font-semibold text-[#1F2937] hover:bg-stone-100 transition">
                                    <span x-text="trendPeriod"></span>
                                    <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div x-cloak x-show="trendPeriodOpen"
                                     class="absolute right-0 mt-1 w-32 bg-white rounded-xl shadow-lg border border-stone-200/80 py-1 z-30">
                                    <template x-for="p in ['Minggu ini', '14 Hari', 'Bulan ini']" :key="p">
                                        <button type="button"
                                                @click="trendPeriod = p; trendPeriodOpen = false"
                                                :class="trendPeriod === p ? 'bg-[#EBF5F0] text-[#1B5E3A] font-bold' : 'text-stone-700 hover:bg-stone-50'"
                                                class="w-full text-left px-3 py-1.5 text-xs transition"
                                                x-text="p">
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- SVG Line & Area Chart -->
                        <div class="relative">
                            <svg viewBox="0 0 305 135" class="w-full h-auto overflow-visible">
                                <defs>
                                    <linearGradient id="surplusTrendGradient" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#1B5E3A" stop-opacity="0.22"/>
                                        <stop offset="100%" stop-color="#1B5E3A" stop-opacity="0.01"/>
                                    </linearGradient>
                                </defs>

                                <!-- Y-Axis Label % -->
                                <text x="12" y="14" class="fill-[#6B7280] text-[9px] font-bold">%</text>

                                <!-- Horizontal Gridlines & Y Labels -->
                                <g class="text-[9px] fill-[#9CA3AF]">
                                    <line x1="26" y1="26" x2="294" y2="26" stroke="#F3F4F6" stroke-width="1"/>
                                    <text x="18" y="29" text-anchor="end">60</text>

                                    <line x1="26" y1="54" x2="294" y2="54" stroke="#F3F4F6" stroke-width="1"/>
                                    <text x="18" y="57" text-anchor="end">40</text>

                                    <line x1="26" y1="82" x2="294" y2="82" stroke="#F3F4F6" stroke-width="1"/>
                                    <text x="18" y="85" text-anchor="end">20</text>

                                    <line x1="26" y1="110" x2="294" y2="110" stroke="#E5E7EB" stroke-width="1"/>
                                    <text x="18" y="113" text-anchor="end">0</text>
                                </g>

                                <!-- Area Fill -->
                                <path :d="trendAreaPath" fill="url(#surplusTrendGradient)"/>

                                <!-- Line Path -->
                                <path :d="trendLinePath" fill="none" stroke="#1B5E3A" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>

                                <!-- Data Nodes & X-Axis Labels -->
                                <template x-for="(point, idx) in trendData" :key="point.label">
                                    <g>
                                        <circle :cx="point.x" :cy="point.y"
                                                :r="hoveredTrendIndex === idx ? 5 : 3.5"
                                                fill="#1B5E3A"
                                                stroke="#FFFFFF"
                                                stroke-width="1.5"
                                                @mouseenter="hoveredTrendIndex = idx"
                                                @mouseleave="hoveredTrendIndex = null"
                                                class="cursor-pointer transition-all duration-150"/>
                                        <text :x="point.x" y="126" text-anchor="middle" class="fill-[#6B7280] text-[8.5px] font-medium" x-text="point.label"></text>
                                    </g>
                                </template>

                                <!-- Hover Tooltip -->
                                <template x-if="hoveredTrendIndex !== null">
                                    <g class="pointer-events-none">
                                        <rect :x="Math.min(230, Math.max(26, trendData[hoveredTrendIndex].x - 32))"
                                              :y="trendData[hoveredTrendIndex].y - 30"
                                              width="64" height="22" rx="6"
                                              fill="#1F2937"/>
                                        <text :x="Math.min(262, Math.max(58, trendData[hoveredTrendIndex].x))"
                                              :y="trendData[hoveredTrendIndex].y - 16"
                                              text-anchor="middle"
                                              class="fill-white text-[9.5px] font-bold"
                                              x-text="`${trendData[hoveredTrendIndex].label}: ${trendData[hoveredTrendIndex].value}%`">
                                        </text>
                                    </g>
                                </template>
                            </svg>
                        </div>
                    </div>

                    <!-- 4. CARD INSIGHT "TREN PRODUKSI" -->
                    <button type="button"
                            @click="isTrendInsightOpen = true"
                            class="w-full text-left bg-[#F4F9F5] hover:bg-[#EBF5F0] rounded-2xl border border-[#DCF2E4] shadow-2xs p-4 flex items-center gap-3.5 transition group">
                        <div class="w-10 h-10 rounded-full bg-[#DCF2E4] text-[#1B5E3A] flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 22V12m0 0C12 7 7 4 4 4c0 5 3 8 8 8zm0 0c0-5 5-8 8-8 0 5-3 8-8 8z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-extrabold text-[#1F2937]">Tren Produksi</h3>
                            <p class="text-[11.5px] text-[#6B7280] leading-relaxed mt-0.5">
                                Produksi sesuai data historis permintaan dapat membantu mengurangi surplus berlebih.
                            </p>
                        </div>
                        <svg class="w-4 h-4 text-stone-400 group-hover:text-[#1B5E3A] group-hover:translate-x-0.5 transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>

                </aside>
            </div>
        </main>
    </div>

    <!-- ======================================================= -->
    <!-- 3. MODAL TAMBAH / EDIT CATATAN PRODUKSI                 -->
    <!-- ======================================================= -->
    <div x-cloak x-show="isFormModalOpen"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-stone-900/50 backdrop-blur-xs"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div @click.away="isFormModalOpen = false"
             class="bg-white rounded-3xl shadow-modal border border-stone-200/80 w-full max-w-lg overflow-hidden">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-stone-100 flex items-center justify-between bg-[#FDFBF7]">
                <div>
                    <h3 class="text-lg font-extrabold text-[#1F2937]" x-text="isEditMode ? 'Edit Catatan Produksi' : 'Tambah Catatan Produksi'"></h3>
                    <p class="text-xs text-[#6B7280] mt-0.5">Catat jumlah produksi dan sisa harian untuk analisis surplus.</p>
                </div>
                <button type="button"
                        @click="isFormModalOpen = false"
                        aria-label="Tutup modal"
                        class="p-1.5 rounded-xl text-stone-400 hover:text-stone-700 hover:bg-stone-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Modal Form Body -->
            <form @submit.prevent="saveRecord()" class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-[#1F2937] mb-1.5">Nama Produk</label>
                    <input type="text"
                           x-model="form.product"
                           placeholder="Contoh: Croissant Butter"
                           required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 text-sm text-[#1F2937] focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A] transition">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-[#1F2937] mb-1.5">Kategori</label>
                        <select x-model="form.category"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 text-sm text-[#1F2937] bg-white focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A]">
                            <option value="Roti & Kue">Roti & Kue</option>
                            <option value="Makanan Berat">Makanan Berat</option>
                            <option value="Makanan Sehat">Makanan Sehat</option>
                            <option value="Snack">Snack</option>
                            <option value="Minuman">Minuman</option>
                            <option value="Dessert">Dessert</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#1F2937] mb-1.5">Waktu Produksi (WIB)</label>
                        <input type="text"
                               x-model="form.time"
                               placeholder="08.00"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 text-sm text-[#1F2937] focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A]">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-[#1F2937] mb-1.5">Diproduksi</label>
                        <input type="number" min="1"
                               x-model="form.produced"
                               placeholder="40"
                               required
                               class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 text-sm text-[#1F2937] focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#1F2937] mb-1.5">Satuan</label>
                        <select x-model="form.unit"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 text-sm text-[#1F2937] bg-white focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A]">
                            <option value="pcs">pcs</option>
                            <option value="porsi">porsi</option>
                            <option value="cup">cup</option>
                            <option value="box">box</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#1F2937] mb-1.5">Sisa (Surplus)</label>
                        <input type="number" min="0"
                               x-model="form.surplus"
                               placeholder="12"
                               required
                               class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 text-sm text-[#1F2937] focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A]">
                    </div>
                </div>

                <!-- Live Surplus Ratio Preview -->
                <div class="p-3.5 rounded-xl bg-[#F4F9F5] border border-[#DCF2E4] flex items-center justify-between">
                    <span class="text-xs font-semibold text-[#1B5E3A]">Estimasi Rasio Surplus</span>
                    <span class="text-sm font-extrabold"
                          :class="formSurplusPercent === 0 ? 'text-[#E11D48]' : 'text-[#1B5E3A]'"
                          x-text="`${form.surplus || 0} ${form.unit} (${formSurplusPercent}%)`">
                    </span>
                </div>

                <!-- Actions -->
                <div class="pt-2 flex items-center justify-end gap-2.5">
                    <button type="button"
                            @click="isFormModalOpen = false"
                            class="px-4 py-2.5 rounded-xl text-sm font-semibold text-stone-600 hover:bg-stone-100 transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl bg-[#1B5E3A] hover:bg-[#14472C] text-white text-sm font-bold shadow-sm transition"
                            x-text="isEditMode ? 'Simpan Perubahan' : 'Simpan Catatan'">
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ======================================================= -->
    <!-- 4. MODAL KONFIRMASI HAPUS                               -->
    <!-- ======================================================= -->
    <div x-cloak x-show="deleteTarget !== null"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-stone-900/50 backdrop-blur-xs">
        <div @click.away="deleteTarget = null"
             class="bg-white rounded-3xl shadow-modal border border-stone-200/80 w-full max-w-sm p-6 text-center">
            <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3 class="text-base font-extrabold text-[#1F2937]">Hapus Catatan Produksi?</h3>
            <p class="text-xs text-[#6B7280] mt-1.5 leading-relaxed">
                Catatan produksi <strong class="text-[#1F2937]" x-text="deleteTarget?.product"></strong> akan dihapus dari daftar harian.
            </p>
            <div class="mt-5 flex items-center justify-center gap-2.5">
                <button type="button"
                        @click="deleteTarget = null"
                        class="flex-1 px-4 py-2.5 rounded-xl text-xs font-bold text-stone-600 bg-stone-100 hover:bg-stone-200 transition">
                    Batal
                </button>
                <button type="button"
                        @click="executeDelete()"
                        class="flex-1 px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-red-600 hover:bg-red-700 transition">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>

    <!-- ======================================================= -->
    <!-- 5. MODAL PANDUAN KONSISTENSI PRODUKSI                   -->
    <!-- ======================================================= -->
    <div x-cloak x-show="isGuideModalOpen"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-stone-900/50 backdrop-blur-xs">
        <div @click.away="isGuideModalOpen = false"
             class="bg-white rounded-3xl shadow-modal border border-stone-200/80 w-full max-w-md overflow-hidden">
            <div class="px-6 py-4 bg-[#F4F9F5] border-b border-[#DCF2E4] flex items-center justify-between">
                <h3 class="text-base font-extrabold text-[#1B5E3A]">Tips Pencatatan Produksi Harian 🌱</h3>
                <button type="button" @click="isGuideModalOpen = false" aria-label="Tutup panduan" class="p-1 rounded-lg text-stone-500 hover:bg-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-6 space-y-3 text-xs text-[#6B7280] leading-relaxed">
                <p><strong class="text-[#1F2937]">1. Catat setiap batch pagi & siang:</strong> Input jumlah produksi segera setelah matang agar catatan tidak terlewat.</p>
                <p><strong class="text-[#1F2937]">2. Evaluasi persentase surplus > 30%:</strong> Produk dengan rasio surplus tinggi sebaiknya dikurangi kuota produksinya di hari kerja berikutnya.</p>
                <p><strong class="text-[#1F2937]">3. Konversi cepat ke Makanan Surplus:</strong> Sisa produksi yang masih segar dapat langsung dipublikasikan ke halaman Makanan Surplus sebelum jam tutup.</p>
            </div>
            <div class="px-6 py-3.5 bg-stone-50 border-t border-stone-100 flex justify-end">
                <button type="button" @click="isGuideModalOpen = false" class="px-4 py-2 rounded-xl bg-[#1B5E3A] text-white text-xs font-bold hover:bg-[#14472C] transition">
                    Mengerti
                </button>
            </div>
        </div>
    </div>

    <!-- ======================================================= -->
    <!-- 6. MODAL INSIGHT TREN PRODUKSI                          -->
    <!-- ======================================================= -->
    <div x-cloak x-show="isTrendInsightOpen"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-stone-900/50 backdrop-blur-xs">
        <div @click.away="isTrendInsightOpen = false"
             class="bg-white rounded-3xl shadow-modal border border-stone-200/80 w-full max-w-md overflow-hidden">
            <div class="px-6 py-4 bg-[#F4F9F5] border-b border-[#DCF2E4] flex items-center justify-between">
                <h3 class="text-base font-extrabold text-[#1B5E3A]">Rekomendasi Produksi Besok 📊</h3>
                <button type="button" @click="isTrendInsightOpen = false" aria-label="Tutup rekomendasi" class="p-1 rounded-lg text-stone-500 hover:bg-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-6 space-y-3 text-xs text-[#6B7280]">
                <div class="p-3 rounded-xl bg-[#FEF3C7]/60 border border-[#FDE68A]">
                    <p class="font-bold text-[#92400E]">Roti Cokelat (Surplus 43%)</p>
                    <p class="mt-0.5 text-[#78350F]">Disarankan turunkan produksi dari 35 pcs menjadi <strong>24 pcs</strong> untuk menekan surplus di bawah 15%.</p>
                </div>
                <div class="p-3 rounded-xl bg-[#EBF5F0] border border-[#DCF2E4]">
                    <p class="font-bold text-[#1B5E3A]">Red Velvet Slice (Surplus 0%)</p>
                    <p class="mt-0.5 text-[#14472C]">Permintaan sangat baik! Kamu bisa mencoba menaikkan produksi menjadi <strong>15 pcs</strong>.</p>
                </div>
            </div>
            <div class="px-6 py-3.5 bg-stone-50 border-t border-stone-100 flex justify-end">
                <button type="button" @click="isTrendInsightOpen = false" class="px-4 py-2 rounded-xl bg-[#1B5E3A] text-white text-xs font-bold hover:bg-[#14472C] transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
