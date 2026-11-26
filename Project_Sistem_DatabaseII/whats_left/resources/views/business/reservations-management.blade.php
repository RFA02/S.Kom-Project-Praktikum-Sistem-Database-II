@extends('layouts.business')

@section('title', 'Manajemen Reservasi - ' . $business->business_name)

@section('content')
<div class="flex h-screen overflow-hidden bg-[#FAF7F2]"
     x-data="{
    mobileSidebarOpen: false,
        profileDropdownOpen: false,
    dateRangeDropdownOpen: false,
    sortDropdownOpen: false,
    summaryPeriodDropdownOpen: false,
    openActionRowId: null,

    activeStatusTab: 'Semua',
    searchQuery: '',
    selectedDateRange: 'Semua tanggal',
    sortBy: 'Terbaru',
    summaryPeriod: 'Minggu ini',

    filterDraft: { status: 'Semua Status' },
    appliedFilter: { status: 'Semua Status' },

    currentPage: 1,
    perPage: 8,

    calendarMonthIndex: @js(now()->month - 1),
    calendarYear: @js(now()->year),
    selectedCalendarDay: null,
    filterByCalendarDay: false,
    monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],

    detailModalItem: null,
    reservations: @js($reservations),

    get currentSummary() {
        const now = new Date();
        const start = new Date(now);
        start.setHours(0, 0, 0, 0);

        if (this.summaryPeriod === 'Minggu ini') {
            const day = start.getDay();
            start.setDate(start.getDate() - (day === 0 ? 6 : day - 1));
        } else if (this.summaryPeriod === 'Bulan ini') {
            start.setDate(1);
        }

        const list = this.reservations.filter(item => {
            const date = new Date(item.createdAtTimestamp * 1000);
            date.setHours(0, 0, 0, 0);
            return date >= start && date <= now;
        });

        return {
            total: list.length,
            confirmed: list.filter(item => item.statusCode === 'confirmed').length,
            noShow: list.filter(item => item.statusCode === 'no_show').length,
            done: list.filter(item => item.statusCode === 'completed').length,
            pending: list.filter(item => item.statusCode === 'pending').length,
            cancelled: list.filter(item => item.statusCode === 'cancelled').length
        };
    },

    get tabCounts() {
        return {
            'Semua': this.reservations.length,
            'Menunggu': this.reservations.filter(item => item.status === 'Menunggu').length,
            'Dikonfirmasi': this.reservations.filter(item => item.status === 'Dikonfirmasi').length,
            'Tidak Hadir': this.reservations.filter(item => item.status === 'Tidak Hadir').length,
            'Selesai': this.reservations.filter(item => item.status === 'Selesai').length,
            'Dibatalkan': this.reservations.filter(item => item.status === 'Dibatalkan').length
        };
    },

    get filteredReservations() {
        let list = this.reservations.filter(item => {
            if (this.activeStatusTab !== 'Semua' && item.status !== this.activeStatusTab) return false;
            if (this.appliedFilter.status !== 'Semua Status' && item.status !== this.appliedFilter.status) return false;

            if (this.filterByCalendarDay && this.selectedCalendarDay !== null) {
                if (
                    item.pickupDay !== this.selectedCalendarDay ||
                    item.pickupMonth !== this.calendarMonthIndex ||
                    item.pickupYear !== this.calendarYear
                ) return false;
            }

            if (this.searchQuery.trim() !== '') {
                const query = this.searchQuery.toLowerCase().trim();
                const matchId = item.id.toLowerCase().includes(query);
                const matchName = item.customerName.toLowerCase().includes(query);
                const matchPhone = item.customerPhone.toLowerCase().includes(query);
                if (!matchId && !matchName && !matchPhone) return false;
            }

            if (this.selectedDateRange !== 'Semua tanggal') {
                const orderDate = new Date(item.createdAtTimestamp * 1000);
                orderDate.setHours(0, 0, 0, 0);
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                if (this.selectedDateRange === 'Hari ini' && orderDate.getTime() !== today.getTime()) return false;

                if (this.selectedDateRange === 'Minggu ini') {
                    const start = new Date(today);
                    const day = start.getDay();
                    start.setDate(start.getDate() - (day === 0 ? 6 : day - 1));
                    if (orderDate < start || orderDate > today) return false;
                }

                if (this.selectedDateRange === 'Bulan ini') {
                    const start = new Date(today.getFullYear(), today.getMonth(), 1);
                    if (orderDate < start || orderDate > today) return false;
                }
            }

            return true;
        });

        if (this.sortBy === 'Terlama') {
            list = [...list].sort((a, b) => a.sortTs - b.sortTs);
        } else if (this.sortBy === 'Total Tertinggi') {
            list = [...list].sort((a, b) => b.total - a.total);
        } else if (this.sortBy === 'Total Terendah') {
            list = [...list].sort((a, b) => a.total - b.total);
        } else if (this.sortBy === 'Nama A-Z') {
            list = [...list].sort((a, b) => a.customerName.localeCompare(b.customerName));
        } else {
            list = [...list].sort((a, b) => b.sortTs - a.sortTs);
        }

        return list;
    },

    get totalPages() {
        return Math.max(1, Math.ceil(this.filteredReservations.length / this.perPage));
    },

    get paginatedReservations() {
        const safePage = Math.min(this.currentPage, this.totalPages);
        const start = (safePage - 1) * this.perPage;
        return this.filteredReservations.slice(start, start + this.perPage);
    },

    get calendarDays() {
        const firstDay = new Date(this.calendarYear, this.calendarMonthIndex, 1);
        const lastDay = new Date(this.calendarYear, this.calendarMonthIndex + 1, 0);
        const offset = (firstDay.getDay() + 6) % 7;
        const days = [];

        for (let index = 0; index < offset; index++) {
            const date = new Date(this.calendarYear, this.calendarMonthIndex, 1 - (offset - index));
            days.push({ day: date.getDate(), isCurrentMonth: false, dots: [] });
        }

        for (let day = 1; day <= lastDay.getDate(); day++) {
            const matching = this.reservations.filter(item =>
                item.pickupDay === day &&
                item.pickupMonth === this.calendarMonthIndex &&
                item.pickupYear === this.calendarYear
            );
            const statusColors = {
                pending: '#D97706',
                confirmed: '#16A34A',
                completed: '#64748B',
                cancelled: '#DC2626',
                no_show: '#7C3AED'
            };
            const dots = [...new Set(matching.map(item => statusColors[item.statusCode]).filter(Boolean))].slice(0, 3);
            days.push({ day, isCurrentMonth: true, dots });
        }

        while (days.length % 7 !== 0) {
            const nextDay = days.length - offset - lastDay.getDate() + 1;
            days.push({ day: nextDay, isCurrentMonth: false, dots: [] });
        }

        return days;
    },

    formatRupiah(amount) {
        return 'Rp ' + Number(amount || 0).toLocaleString('id-ID');
    },

    statusBadgeClass(status) {
        switch (status) {
            case 'Menunggu': return 'bg-[#FEF3C7] text-[#B45309] border border-[#FDE68A]';
            case 'Dikonfirmasi': return 'bg-[#DCF2E4] text-[#1B5E3A] border border-[#BBF7D0]';
            case 'Selesai': return 'bg-[#F3F4F6] text-[#374151] border border-[#E5E7EB]';
            case 'Dibatalkan': return 'bg-[#FEE2E2] text-[#DC2626] border border-[#FECACA]';
            case 'Tidak Hadir': return 'bg-[#F3E8FF] text-[#7C3AED] border border-[#DDD6FE]';
            default: return 'bg-stone-100 text-stone-700';
        }
    },

    setTab(tab) {
        this.activeStatusTab = tab;
        this.currentPage = 1;
    },

    applyRightFilter() {
        this.appliedFilter = { ...this.filterDraft };
        this.activeStatusTab = this.filterDraft.status === 'Semua Status' ? 'Semua' : this.filterDraft.status;
        this.currentPage = 1;
    },

    resetFilters() {
        this.activeStatusTab = 'Semua';
        this.searchQuery = '';
        this.sortBy = 'Terbaru';
        this.selectedDateRange = 'Semua tanggal';
        this.filterByCalendarDay = false;
        this.selectedCalendarDay = null;
        this.filterDraft = { status: 'Semua Status' };
        this.appliedFilter = { status: 'Semua Status' };
        this.currentPage = 1;
    },

    selectCalendarDay(cell) {
        if (!cell.isCurrentMonth) return;
        if (this.filterByCalendarDay && this.selectedCalendarDay === cell.day) {
            this.filterByCalendarDay = false;
            this.selectedCalendarDay = null;
        } else {
            this.selectedCalendarDay = cell.day;
            this.filterByCalendarDay = true;
            this.currentPage = 1;
        }
    },

    prevMonth() {
        if (this.calendarMonthIndex === 0) {
            this.calendarMonthIndex = 11;
            this.calendarYear--;
        } else {
            this.calendarMonthIndex--;
        }
        this.filterByCalendarDay = false;
    },

    nextMonth() {
        if (this.calendarMonthIndex === 11) {
            this.calendarMonthIndex = 0;
            this.calendarYear++;
        } else {
            this.calendarMonthIndex++;
        }
        this.filterByCalendarDay = false;
    },

    async updateReservationStatus(item, newStatusCode) {
        try {
            const response = await fetch(`/business/reservations-management/${item.databaseId}/status`, {
                method: 'PUT',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify({ reservation_status: newStatusCode })
            });

            const result = await response.json();

            if (!response.ok) {
                this.$dispatch('show-toast', { msg: result.message || 'Status reservasi gagal diperbarui.', type: 'error' });
                return;
            }

            this.detailModalItem = null;
            window.location.reload();
        } catch (error) {
            this.$dispatch('show-toast', { msg: 'Tidak dapat terhubung ke server. Silakan coba kembali.', type: 'error' });
        }
    },

    exportCsv() {
        const headers = ['ID Reservasi', 'Pelanggan', 'Nomor Telepon', 'Tanggal Reservasi', 'Tanggal Pengambilan', 'Waktu Pengambilan', 'Makanan', 'Porsi', 'Total', 'Status'];
        const rows = this.filteredReservations.map(item => [
            item.id, item.customerName, item.customerPhone, item.createdAt, item.pickupDate,
            item.pickupSlot, item.foodName, item.itemCount, item.total, item.status
        ]);
        const csv = [headers, ...rows].map(row => row.map(value => String.fromCharCode(34) + String(value ?? '').replaceAll(String.fromCharCode(34), String.fromCharCode(34, 34)) + String.fromCharCode(34)).join(',')).join('\n');
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = 'reservasi-whats-left.csv';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
    }
}">

    <!-- ======================================================= -->
    <!-- 1. SIDEBAR KIRI (DESKTOP DOCKED & MOBILE DRAWER)        -->
    <!-- ======================================================= -->

    <x-business.sidebar active="reservations" />

    <!-- ======================================================= -->
    <!-- 2. MAIN WORKSPACE (TOPBAR + CONTENT 2-KOLOM)            -->
    <!-- ======================================================= -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <x-business.topbar />

        <!-- SCROLLABLE MAIN CONTENT -->
        <main class="flex-1 overflow-y-auto custom-scrollbar p-4 sm:p-6 lg:p-7">
            <div class="max-w-[1440px] mx-auto">

                <!-- PAGE HEADER ROW -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
                    <div>
                        <div class="flex items-center gap-2.5">
                            <h1 class="text-xl sm:text-[22px] font-extrabold text-[#1F2937] tracking-tight">
                                Manajemen Reservasi
                            </h1>
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-[#EBF5F0] text-[#1B5E3A]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </span>
                        </div>
                        <p class="text-[13px] text-[#6B7280] mt-0.5">
                            Pantau dan kelola semua reservasi makanan surplus.
                        </p>
                    </div>

                    <!-- Primary Action Button -->
                    <div class="shrink-0">
                        <a href="{{ route('business.surplus-listings') }}"
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-[#1B5E3A] hover:bg-[#14472C] text-white text-[13px] font-bold shadow-sm shadow-[#1B5E3A]/20 transition-all duration-150 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Kelola Listing Surplus</span>
                        </a>
                    </div>
                </div>

                <!-- 2-COLUMN WORKSPACE GRID -->
                <div class="grid grid-cols-1 xl:grid-cols-12 gap-5 items-start">

                    <!-- =================================================== -->
                    <!-- LEFT COLUMN (9/12): TABS, CONTROLS, TABLE & PAGING  -->
                    <!-- =================================================== -->
                    <div class="xl:col-span-9 space-y-4 min-w-0">

                        <!-- STATUS TABS BAR -->
                        <div class="border-b border-stone-200/80 overflow-x-auto no-scrollbar">
                            <div class="flex items-center gap-1 sm:gap-4 min-w-max" role="tablist" aria-label="Filter status reservasi">
                                <!-- Semua -->
                                <button type="button"
                                        role="tab"
                                        :aria-selected="activeStatusTab === 'Semua'"
                                        @click="setTab('Semua')"
                                        :class="activeStatusTab === 'Semua' ? 'border-[#1B5E3A] text-[#1B5E3A] font-bold' : 'border-transparent text-[#6B7280] hover:text-[#1F2937] font-semibold'"
                                        class="inline-flex items-center gap-1.5 px-2.5 pb-3 pt-1 text-[13px] border-b-2 transition-colors whitespace-nowrap cursor-pointer">
                                    <span>Semua</span>
                                    <span class="px-1.5 py-0.5 rounded-full text-[11px] font-bold bg-[#EBF5F0] text-[#1B5E3A]" x-text="tabCounts['Semua']"></span>
                                </button>

                                <!-- Menunggu -->
                                <button type="button"
                                        role="tab"
                                        :aria-selected="activeStatusTab === 'Menunggu'"
                                        @click="setTab('Menunggu')"
                                        :class="activeStatusTab === 'Menunggu' ? 'border-[#D97706] text-[#B45309] font-bold' : 'border-transparent text-[#D97706] hover:text-[#B45309] font-semibold'"
                                        class="inline-flex items-center gap-1.5 px-2.5 pb-3 pt-1 text-[13px] border-b-2 transition-colors whitespace-nowrap cursor-pointer">
                                    <span>Menunggu</span>
                                    <span class="px-1.5 py-0.5 rounded-full text-[11px] font-bold bg-[#FEF3C7] text-[#B45309]" x-text="tabCounts['Menunggu']"></span>
                                </button>

                                <!-- Dikonfirmasi -->
                                <button type="button"
                                        role="tab"
                                        :aria-selected="activeStatusTab === 'Dikonfirmasi'"
                                        @click="setTab('Dikonfirmasi')"
                                        :class="activeStatusTab === 'Dikonfirmasi' ? 'border-[#1B5E3A] text-[#1B5E3A] font-bold' : 'border-transparent text-[#2E7D32] hover:text-[#1B5E3A] font-semibold'"
                                        class="inline-flex items-center gap-1.5 px-2.5 pb-3 pt-1 text-[13px] border-b-2 transition-colors whitespace-nowrap cursor-pointer">
                                    <span>Dikonfirmasi</span>
                                    <span class="px-1.5 py-0.5 rounded-full text-[11px] font-bold bg-[#DCF2E4] text-[#1B5E3A]" x-text="tabCounts['Dikonfirmasi']"></span>
                                </button>

                                <!-- Tidak Hadir -->
                                <button type="button"
                                        role="tab"
                                        :aria-selected="activeStatusTab === 'Tidak Hadir'"
                                        @click="setTab('Tidak Hadir')"
                                        :class="activeStatusTab === 'Tidak Hadir' ? 'border-[#0284C7] text-[#0369A1] font-bold' : 'border-transparent text-[#0284C7] hover:text-[#0369A1] font-semibold'"
                                        class="inline-flex items-center gap-1.5 px-2.5 pb-3 pt-1 text-[13px] border-b-2 transition-colors whitespace-nowrap cursor-pointer">
                                    <span>Tidak Hadir</span>
                                    <span class="px-1.5 py-0.5 rounded-full text-[11px] font-bold bg-[#E0F2FE] text-[#0369A1]" x-text="tabCounts['Tidak Hadir']"></span>
                                </button>

                                <!-- Selesai -->
                                <button type="button"
                                        role="tab"
                                        :aria-selected="activeStatusTab === 'Selesai'"
                                        @click="setTab('Selesai')"
                                        :class="activeStatusTab === 'Selesai' ? 'border-[#374151] text-[#1F2937] font-bold' : 'border-transparent text-[#4B5563] hover:text-[#1F2937] font-semibold'"
                                        class="inline-flex items-center gap-1.5 px-2.5 pb-3 pt-1 text-[13px] border-b-2 transition-colors whitespace-nowrap cursor-pointer">
                                    <span>Selesai</span>
                                    <span class="px-1.5 py-0.5 rounded-full text-[11px] font-bold bg-[#F3F4F6] text-[#4B5563]" x-text="tabCounts['Selesai']"></span>
                                </button>

                                <!-- Dibatalkan -->
                                <button type="button"
                                        role="tab"
                                        :aria-selected="activeStatusTab === 'Dibatalkan'"
                                        @click="setTab('Dibatalkan')"
                                        :class="activeStatusTab === 'Dibatalkan' ? 'border-[#DC2626] text-[#DC2626] font-bold' : 'border-transparent text-[#DC2626] hover:text-[#B91C1C] font-semibold'"
                                        class="inline-flex items-center gap-1.5 px-2.5 pb-3 pt-1 text-[13px] border-b-2 transition-colors whitespace-nowrap cursor-pointer">
                                    <span>Dibatalkan</span>
                                    <span class="px-1.5 py-0.5 rounded-full text-[11px] font-bold bg-[#FEE2E2] text-[#DC2626]" x-text="tabCounts['Dibatalkan']"></span>
                                </button>
                            </div>
                        </div>

                        <!-- SEARCH & CONTROLS ROW -->
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                            <!-- Search Input -->
                            <div class="relative flex-1 max-w-md">
                                <span class="absolute inset-y-0 left-3.5 flex items-center text-stone-400 pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </span>
                                <input type="text"
                                       x-model="searchQuery"
                                       @input="currentPage = 1"
                                       placeholder="Cari nama pelanggan / ID reservasi..."
                                       aria-label="Cari nama pelanggan atau ID reservasi"
                                       class="w-full pl-10 pr-4 py-2.5 text-[13px] bg-white border border-stone-200/90 rounded-xl text-[#1F2937] placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A] transition">
                            </div>

                            <!-- Right Controls: Date Range, Sort, Export -->
                            <div class="flex flex-wrap items-center gap-2">
                                <!-- Date Range Selector -->
                                <div class="relative" @click.away="dateRangeDropdownOpen = false">
                                    <button type="button"
                                            @click="dateRangeDropdownOpen = !dateRangeDropdownOpen"
                                            class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-white border border-stone-200/90 rounded-xl text-[12.5px] font-semibold text-[#1F2937] hover:border-stone-300 transition cursor-pointer">
                                        <svg class="w-4 h-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span x-text="selectedDateRange"></span>
                                        <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <div x-cloak x-show="dateRangeDropdownOpen"
                                         x-transition
                                         class="absolute right-0 mt-1.5 w-48 bg-white rounded-xl shadow-lg border border-stone-200/80 py-1 z-30">
                                        <template x-for="range in ['Semua tanggal', 'Hari ini', 'Minggu ini', 'Bulan ini']" :key="range">
                                            <button type="button"
                                                    @click="selectedDateRange = range; dateRangeDropdownOpen = false; currentPage = 1"
                                                    :class="selectedDateRange === range ? 'bg-[#EBF5F0] text-[#1B5E3A] font-bold' : 'text-stone-700 hover:bg-stone-50'"
                                                    class="w-full text-left px-3.5 py-2 text-xs transition">
                                                <span x-text="range"></span>
                                            </button>
                                        </template>
                                    </div>
                                </div>

                                <!-- Sort Dropdown -->
                                <div class="relative" @click.away="sortDropdownOpen = false">
                                    <button type="button"
                                            @click="sortDropdownOpen = !sortDropdownOpen"
                                            class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-white border border-stone-200/90 rounded-xl text-[12.5px] font-semibold text-[#1F2937] hover:border-stone-300 transition cursor-pointer">
                                        <span class="text-[#6B7280] font-medium">Urutkan:</span>
                                        <span x-text="sortBy"></span>
                                        <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <div x-cloak x-show="sortDropdownOpen"
                                         x-transition
                                         class="absolute right-0 mt-1.5 w-44 bg-white rounded-xl shadow-lg border border-stone-200/80 py-1 z-30">
                                        <template x-for="opt in ['Terbaru', 'Terlama', 'Total Tertinggi', 'Total Terendah', 'Nama A-Z']" :key="opt">
                                            <button type="button"
                                                    @click="sortBy = opt; sortDropdownOpen = false; currentPage = 1"
                                                    :class="sortBy === opt ? 'bg-[#EBF5F0] text-[#1B5E3A] font-bold' : 'text-stone-700 hover:bg-stone-50'"
                                                    class="w-full text-left px-3.5 py-2 text-xs transition">
                                                <span x-text="opt"></span>
                                            </button>
                                        </template>
                                    </div>
                                </div>

                                <!-- Export Button -->
                                <button type="button"
                                        @click="exportCsv()"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-white border border-stone-200/90 rounded-xl text-[12.5px] font-semibold text-[#1F2937] hover:bg-stone-50 hover:border-stone-300 transition cursor-pointer">
                                    <svg class="w-4 h-4 text-stone-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    <span>Export</span>
                                </button>
                            </div>
                        </div>

                        <!-- ACTIVE CALENDAR FILTER BANNER (IF SELECTED) -->
                        <div x-cloak x-show="filterByCalendarDay"
                             class="flex items-center justify-between px-4 py-2.5 rounded-xl bg-[#EBF5F0] border border-[#DCF2E4] text-xs text-[#1B5E3A]">
                            <div class="flex items-center gap-2 font-semibold">
                                <span>📅 Menampilkan jadwal pengambilan tanggal <strong x-text="selectedCalendarDay + ' ' + monthNames[calendarMonthIndex] + ' ' + calendarYear"></strong></span>
                            </div>
                            <button type="button"
                                    @click="filterByCalendarDay = false"
                                    class="font-bold underline hover:text-[#14472C] cursor-pointer">
                                Tampilkan Semua Tanggal
                            </button>
                        </div>

                        <!-- RESERVATIONS TABLE CARD -->
                        <div class="bg-white rounded-2xl border border-stone-200/80 shadow-card overflow-hidden">

                            <!-- Desktop & Tablet Table -->
                            <div class="overflow-x-auto custom-scrollbar">
                                <table class="w-full text-left border-collapse min-w-[780px]">
                                    <thead>
                                        <tr class="bg-[#F8F8F7] border-b border-stone-200/80 text-[12px] font-bold text-[#4B5563]">
                                            <th class="py-3.5 pl-5 pr-3">Reservasi</th>
                                            <th class="py-3.5 px-3">Pelanggan</th>
                                            <th class="py-3.5 px-3">Tanggal & Waktu</th>
                                            <th class="py-3.5 px-3">Status</th>
                                            <th class="py-3.5 px-3">Total</th>
                                            <th class="py-3.5 px-3">Makanan</th>
                                            <th class="py-3.5 pl-3 pr-5 text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-stone-100 text-[13px]">
                                        <template x-for="item in paginatedReservations" :key="item.id">
                                            <tr class="hover:bg-[#FAF8F5]/80 transition-colors group">
                                                <!-- 1. Reservasi (ID + Timestamp) -->
                                                <td class="py-3.5 pl-5 pr-3 align-middle whitespace-nowrap">
                                                    <div class="font-bold text-[#1F2937] text-[12.5px] tracking-tight" x-text="item.id"></div>
                                                    <div class="text-[11px] text-[#6B7280] mt-0.5" x-text="item.createdAt"></div>
                                                </td>

                                                <!-- 2. Pelanggan (Avatar + Name + Phone) -->
                                                <td class="py-3.5 px-3 align-middle whitespace-nowrap">
                                                    <div class="flex items-center gap-2.5">
                                                        <div class="w-8 h-8 rounded-full bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center text-xs font-bold ring-1 ring-stone-200 shrink-0"
                                                             x-text="item.customerName.charAt(0).toUpperCase()"></div>
                                                        <div>
                                                            <div class="font-bold text-[#1F2937] text-[12.5px]" x-text="item.customerName"></div>
                                                            <div class="text-[11px] text-[#6B7280] mt-0.5" x-text="item.customerPhone"></div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- 3. Tanggal & Waktu Pengambilan -->
                                                <td class="py-3.5 px-3 align-middle whitespace-nowrap">
                                                    <div class="font-semibold text-[#1F2937] text-[12.5px]" x-text="item.pickupDate"></div>
                                                    <div class="text-[11px] text-[#6B7280] mt-0.5" x-text="item.pickupSlot"></div>
                                                </td>

                                                <!-- 4. Status Badge + Stamp -->
                                                <td class="py-3.5 px-3 align-middle whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold"
                                                          :class="statusBadgeClass(item.status)"
                                                          x-text="item.status"></span>
                                                    
                                                </td>

                                                <!-- 5. Total & Jumlah Item -->
                                                <td class="py-3.5 px-3 align-middle whitespace-nowrap">
                                                    <div class="font-bold text-[#1F2937] text-[12.5px]" x-text="formatRupiah(item.total)"></div>
                                                    <div class="text-[11px] text-[#6B7280] mt-0.5" x-text="item.itemCount + ' porsi'"></div>
                                                </td>

                                                <!-- 6. Makanan -->
                                                <td class="py-3.5 px-3 align-middle">
                                                    <div class="font-semibold text-[#1F2937] text-[12px]" x-text="item.foodName"></div>
                                                    <div class="text-[11px] text-[#6B7280] mt-0.5" x-text="item.itemCount + ' porsi'"></div>
                                                </td>

                                                <!-- 7. Aksi (Eye + Three-dots menu) -->
                                                <td class="py-3.5 pl-3 pr-5 align-middle whitespace-nowrap">
                                                    <div class="flex items-center justify-center gap-1.5 relative">
                                                        <!-- Detail Button -->
                                                        <button type="button"
                                                                @click="detailModalItem = item"
                                                                :aria-label="'Lihat detail reservasi ' + item.id"
                                                                title="Lihat Detail"
                                                                class="w-8 h-8 rounded-lg border border-stone-200/80 bg-white text-stone-500 hover:text-[#1B5E3A] hover:border-[#1B5E3A]/40 hover:bg-[#EBF5F0]/50 flex items-center justify-center transition cursor-pointer">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                            </svg>
                                                        </button>

                                                        <!-- Three-Dots Menu -->
                                                        <div class="relative" @click.away="if (openActionRowId === item.id) openActionRowId = null">
                                                            <button type="button"
                                                                    @click="openActionRowId = openActionRowId === item.id ? null : item.id"
                                                                    :aria-label="'Opsi lainnya untuk ' + item.id"
                                                                    title="Aksi Lainnya"
                                                                    class="w-8 h-8 rounded-lg border border-stone-200/80 bg-white text-stone-500 hover:text-[#1F2937] hover:bg-stone-50 flex items-center justify-center transition cursor-pointer">
                                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                                    <circle cx="12" cy="5" r="1.6"/>
                                                                    <circle cx="12" cy="12" r="1.6"/>
                                                                    <circle cx="12" cy="19" r="1.6"/>
                                                                </svg>
                                                            </button>

                                                            <!-- Row Action Dropdown -->
                                                            <div x-cloak x-show="openActionRowId === item.id"
                                                                 x-transition
                                                                 class="absolute right-0 mt-1.5 w-48 bg-white rounded-xl shadow-xl border border-stone-200/90 py-1.5 z-40 text-left">
                                                                <button type="button"
                                                                        @click="detailModalItem = item; openActionRowId = null"
                                                                        class="w-full px-3.5 py-2 text-xs font-semibold text-stone-700 hover:bg-stone-50 flex items-center gap-2">
                                                                    <span>👁️</span> Lihat Detail Reservasi
                                                                </button>
                                                                <button type="button"
                                                                        x-show="item.statusCode === 'pending'"
                                                                        @click="updateReservationStatus(item, 'confirmed')"
                                                                        class="w-full px-3.5 py-2 text-xs font-semibold text-[#1B5E3A] hover:bg-[#EBF5F0] flex items-center gap-2">
                                                                    <span>✅</span> Konfirmasi Pesanan
                                                                </button>
                                                                <button type="button"
                                                                        x-show="item.statusCode === 'confirmed'"
                                                                        @click="updateReservationStatus(item, 'no_show')"
                                                                        class="w-full px-3.5 py-2 text-xs font-semibold text-[#0369A1] hover:bg-[#E0F2FE]/60 flex items-center gap-2">
                                                                    <span>🛍️</span> Tandai Tidak Hadir
                                                                </button>
                                                                <button type="button"
                                                                        x-show="item.statusCode === 'confirmed'"
                                                                        @click="updateReservationStatus(item, 'completed')"
                                                                        class="w-full px-3.5 py-2 text-xs font-semibold text-[#1B5E3A] hover:bg-[#EBF5F0] flex items-center gap-2">
                                                                    <span>🎉</span> Selesaikan Reservasi
                                                                </button>
                                                                <div class="my-1 border-t border-stone-100" x-show="item.statusCode === 'pending' || item.statusCode === 'confirmed'"></div>
                                                                <button type="button"
                                                                        x-show="item.statusCode === 'pending' || item.statusCode === 'confirmed'"
                                                                        @click="updateReservationStatus(item, 'cancelled')"
                                                                        class="w-full px-3.5 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 flex items-center gap-2">
                                                                    <span>❌</span> Batalkan Reservasi
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>

                                        <!-- Empty State -->
                                        <tr x-show="paginatedReservations.length === 0" x-cloak>
                                            <td colspan="7" class="py-12 px-6 text-center">
                                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-[#FAF6F0] text-2xl mb-3">📋</div>
                                                <p class="text-sm font-bold text-[#1F2937]">Tidak ada reservasi ditemukan</p>
                                                <p class="text-xs text-[#6B7280] mt-1">Coba ubah kata kunci pencarian atau reset filter yang aktif.</p>
                                                <button type="button"
                                                        @click="resetFilters()"
                                                        class="mt-3 px-4 py-2 rounded-xl bg-[#EBF5F0] text-[#1B5E3A] text-xs font-bold hover:bg-[#DCF2E4] transition cursor-pointer">
                                                    Reset Filter
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- PAGINATION FOOTER (< 1 2 3 >) -->
                            <div class="px-5 py-4 border-t border-stone-100 flex flex-col sm:flex-row items-center justify-between gap-3 bg-white">
                                <p class="text-xs text-[#6B7280]">
                                    Menampilkan <span class="font-bold text-[#1F2937]" x-text="paginatedReservations.length"></span> dari <span class="font-bold text-[#1F2937]" x-text="filteredReservations.length"></span> reservasi
                                </p>

                                <nav class="flex items-center gap-1.5" aria-label="Navigasi halaman reservasi">
                                    <!-- Prev -->
                                    <button type="button"
                                            @click="if (currentPage > 1) currentPage--"
                                            :disabled="currentPage <= 1"
                                            aria-label="Halaman sebelumnya"
                                            class="w-8 h-8 rounded-lg border border-stone-200/80 flex items-center justify-center text-stone-500 hover:bg-stone-50 disabled:opacity-40 disabled:cursor-not-allowed transition cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                    </button>

                                    <!-- Page Numbers -->
                                    <template x-for="page in totalPages" :key="page">
                                        <button type="button"
                                                @click="currentPage = page"
                                                :aria-current="currentPage === page ? 'page' : false"
                                                :class="currentPage === page
                                                    ? 'bg-[#1B5E3A] text-white border-[#1B5E3A] font-bold shadow-xs'
                                                    : 'bg-white text-[#4B5563] border-stone-200/80 hover:bg-stone-50 font-semibold'"
                                                class="w-8 h-8 rounded-lg border text-xs flex items-center justify-center transition cursor-pointer"
                                                x-text="page">
                                        </button>
                                    </template>

                                    <!-- Next -->
                                    <button type="button"
                                            @click="if (currentPage < totalPages) currentPage++"
                                            :disabled="currentPage >= totalPages"
                                            aria-label="Halaman berikutnya"
                                            class="w-8 h-8 rounded-lg border border-stone-200/80 flex items-center justify-center text-stone-500 hover:bg-stone-50 disabled:opacity-40 disabled:cursor-not-allowed transition cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </nav>
                            </div>
                        </div>
                    </div>

                    <!-- =================================================== -->
                    <!-- RIGHT COLUMN (3/12): RINGKASAN, FILTER & KALENDER   -->
                    <!-- =================================================== -->
                    <div class="xl:col-span-3 space-y-4">

                        <!-- CARD 1: RINGKASAN RESERVASI -->
                        <div class="bg-white rounded-2xl border border-stone-200/80 shadow-card p-4">
                            <div class="flex items-center justify-between mb-3.5">
                                <h2 class="text-[14px] font-bold text-[#1F2937]">Ringkasan Reservasi</h2>

                                <!-- Period Selector -->
                                <div class="relative" @click.away="summaryPeriodDropdownOpen = false">
                                    <button type="button"
                                            @click="summaryPeriodDropdownOpen = !summaryPeriodDropdownOpen"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg border border-stone-200 text-[11px] font-semibold text-[#4B5563] hover:bg-stone-50 transition cursor-pointer">
                                        <span x-text="summaryPeriod"></span>
                                        <svg class="w-3 h-3 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <div x-cloak x-show="summaryPeriodDropdownOpen"
                                         x-transition
                                         class="absolute right-0 mt-1 w-32 bg-white rounded-xl shadow-lg border border-stone-200/80 py-1 z-30">
                                        <template x-for="p in ['Hari ini', 'Minggu ini', 'Bulan ini']" :key="p">
                                            <button type="button"
                                                    @click="summaryPeriod = p; summaryPeriodDropdownOpen = false"
                                                    :class="summaryPeriod === p ? 'bg-[#EBF5F0] text-[#1B5E3A] font-bold' : 'text-stone-700 hover:bg-stone-50'"
                                                    class="w-full text-left px-3 py-1.5 text-[11.5px] transition">
                                                <span x-text="p"></span>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- 2x3 Metric Grid -->
                            <div class="grid grid-cols-2 gap-2.5">
                                <!-- Total Reservasi -->
                                <button type="button" @click="setTab('Semua')"
                                        class="flex items-start gap-2.5 p-2.5 rounded-xl bg-[#FDFBF8] border border-stone-100 hover:border-stone-200 transition text-left cursor-pointer">
                                    <span class="w-8 h-8 rounded-lg bg-[#FEF3C7] text-[#D97706] flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </span>
                                    <div class="min-w-0">
                                        <div class="text-base font-extrabold text-[#1F2937] leading-tight" x-text="currentSummary.total"></div>
                                        <div class="text-[10.5px] font-medium text-[#6B7280] leading-tight mt-0.5">Total Reservasi</div>
                                    </div>
                                </button>

                                <!-- Dikonfirmasi -->
                                <button type="button" @click="setTab('Dikonfirmasi')"
                                        class="flex items-start gap-2.5 p-2.5 rounded-xl bg-[#FDFBF8] border border-stone-100 hover:border-[#DCF2E4] transition text-left cursor-pointer">
                                    <span class="w-8 h-8 rounded-lg bg-[#DCF2E4] text-[#1B5E3A] flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </span>
                                    <div class="min-w-0">
                                        <div class="text-base font-extrabold text-[#1F2937] leading-tight" x-text="currentSummary.confirmed"></div>
                                        <div class="text-[10.5px] font-medium text-[#6B7280] leading-tight mt-0.5">Dikonfirmasi</div>
                                    </div>
                                </button>

                                <!-- Tidak Hadir -->
                                <button type="button" @click="setTab('Tidak Hadir')"
                                        class="flex items-start gap-2.5 p-2.5 rounded-xl bg-[#FDFBF8] border border-stone-100 hover:border-[#BAE6FD] transition text-left cursor-pointer">
                                    <span class="w-8 h-8 rounded-lg bg-[#E0F2FE] text-[#0284C7] flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                        </svg>
                                    </span>
                                    <div class="min-w-0">
                                        <div class="text-base font-extrabold text-[#1F2937] leading-tight" x-text="currentSummary.noShow"></div>
                                        <div class="text-[10.5px] font-medium text-[#6B7280] leading-tight mt-0.5">Tidak Hadir</div>
                                    </div>
                                </button>

                                <!-- Selesai -->
                                <button type="button" @click="setTab('Selesai')"
                                        class="flex items-start gap-2.5 p-2.5 rounded-xl bg-[#FDFBF8] border border-stone-100 hover:border-stone-200 transition text-left cursor-pointer">
                                    <span class="w-8 h-8 rounded-lg bg-[#F3F4F6] text-[#4B5563] flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </span>
                                    <div class="min-w-0">
                                        <div class="text-base font-extrabold text-[#1F2937] leading-tight" x-text="currentSummary.done"></div>
                                        <div class="text-[10.5px] font-medium text-[#6B7280] leading-tight mt-0.5">Selesai</div>
                                    </div>
                                </button>

                                <!-- Menunggu -->
                                <button type="button" @click="setTab('Menunggu')"
                                        class="flex items-start gap-2.5 p-2.5 rounded-xl bg-[#FDFBF8] border border-stone-100 hover:border-[#FDE68A] transition text-left cursor-pointer">
                                    <span class="w-8 h-8 rounded-lg bg-[#FFEDD5] text-[#EA580C] flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </span>
                                    <div class="min-w-0">
                                        <div class="text-base font-extrabold text-[#1F2937] leading-tight" x-text="currentSummary.pending"></div>
                                        <div class="text-[10.5px] font-medium text-[#6B7280] leading-tight mt-0.5">Menunggu</div>
                                    </div>
                                </button>

                                <!-- Dibatalkan -->
                                <button type="button" @click="setTab('Dibatalkan')"
                                        class="flex items-start gap-2.5 p-2.5 rounded-xl bg-[#FDFBF8] border border-stone-100 hover:border-[#FECACA] transition text-left cursor-pointer">
                                    <span class="w-8 h-8 rounded-lg bg-[#FEE2E2] text-[#DC2626] flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </span>
                                    <div class="min-w-0">
                                        <div class="text-base font-extrabold text-[#1F2937] leading-tight" x-text="currentSummary.cancelled"></div>
                                        <div class="text-[10.5px] font-medium text-[#6B7280] leading-tight mt-0.5">Dibatalkan</div>
                                    </div>
                                </button>
                            </div>
                        </div>

                        <!-- CARD 2: FILTER PANEL -->
                        <div class="bg-white rounded-2xl border border-stone-200/80 shadow-card p-4">
                            <div class="flex items-center justify-between mb-3.5">
                                <h2 class="text-[14px] font-bold text-[#1F2937]">Filter</h2>
                                <button type="button"
                                        @click="resetFilters()"
                                        class="text-xs font-bold text-[#1B5E3A] hover:text-[#14472C] hover:underline cursor-pointer">
                                    Reset
                                </button>
                            </div>

                            <div class="space-y-3">
                                <!-- Status Dropdown -->
                                <div>
                                    <label for="filter-status" class="block text-[11.5px] font-bold text-[#374151] mb-1">Status</label>
                                    <select id="filter-status"
                                            x-model="filterDraft.status"
                                            class="w-full px-3 py-2 text-xs font-medium bg-white border border-stone-200/90 rounded-xl text-[#1F2937] focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A]">
                                        <option value="Semua Status">Semua Status</option>
                                        <option value="Menunggu">Menunggu</option>
                                        <option value="Dikonfirmasi">Dikonfirmasi</option>
                                        <option value="Tidak Hadir">Tidak Hadir</option>
                                        <option value="Selesai">Selesai</option>
                                        <option value="Dibatalkan">Dibatalkan</option>
                                    </select>
                                </div>

                                <!-- Tanggal Reservasi -->
                                <div>
                                    <label for="filter-date" class="block text-[11.5px] font-bold text-[#374151] mb-1">Tanggal Reservasi</label>
                                    <select id="filter-date"
                                            x-model="selectedDateRange"
                                            class="w-full px-3 py-2 text-xs font-medium bg-white border border-stone-200/90 rounded-xl text-[#1F2937] focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A]">
                                        <option value="Semua tanggal">Semua tanggal</option>
                                        <option value="Hari ini">Hari ini</option>
                                        <option value="Minggu ini">Minggu ini</option>
                                        <option value="Bulan ini">Bulan ini</option>
                                    </select>
                                </div>

                                <!-- Apply Filter Button -->
                                <button type="button"
                                        @click="applyRightFilter()"
                                        class="w-full mt-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-[#1B5E3A]/40 bg-[#F4F9F5] hover:bg-[#1B5E3A] text-[#1B5E3A] hover:text-white text-xs font-bold transition-colors cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                    </svg>
                                    <span>Terapkan Filter</span>
                                </button>
                            </div>
                        </div>

                        <!-- CARD 3: KALENDER PENGAMBILAN -->
                        <div class="bg-white rounded-2xl border border-stone-200/80 shadow-card p-4">
                            <div class="flex items-center justify-between mb-2">
                                <h2 class="text-[14px] font-bold text-[#1F2937]">Kalender Pengambilan</h2>
                                <div class="flex items-center gap-1">
                                    <button type="button"
                                            @click="prevMonth()"
                                            aria-label="Bulan sebelumnya"
                                            class="w-6 h-6 rounded-lg hover:bg-stone-100 flex items-center justify-center text-stone-500 transition cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                    </button>
                                    <button type="button"
                                            @click="nextMonth()"
                                            aria-label="Bulan berikutnya"
                                            class="w-6 h-6 rounded-lg hover:bg-stone-100 flex items-center justify-center text-stone-500 transition cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="text-xs font-bold text-[#1F2937] mb-3" x-text="monthNames[calendarMonthIndex] + ' ' + calendarYear"></div>

                            <!-- Day Headers -->
                            <div class="grid grid-cols-7 text-center text-[10.5px] font-bold text-[#6B7280] mb-1.5">
                                <span>Sen</span>
                                <span>Sel</span>
                                <span>Rab</span>
                                <span>Kam</span>
                                <span>Jum</span>
                                <span>Sab</span>
                                <span>Min</span>
                            </div>

                            <!-- Calendar Cells -->
                            <div class="grid grid-cols-7 gap-y-1 text-center">
                                <template x-for="(cell, idx) in calendarDays" :key="idx">
                                    <div class="flex flex-col items-center justify-center py-0.5">
                                        <button type="button"
                                                @click="selectCalendarDay(cell)"
                                                :disabled="!cell.isCurrentMonth"
                                                :class="[
                                                    !cell.isCurrentMonth ? 'text-stone-300 cursor-default' : 'cursor-pointer',
                                                    cell.isCurrentMonth && selectedCalendarDay === cell.day ? 'bg-[#1B5E3A] text-white font-bold shadow-xs' : (cell.isCurrentMonth ? 'text-[#1F2937] hover:bg-[#EBF5F0] font-semibold' : '')
                                                ]"
                                                class="w-7 h-7 rounded-full text-[11.5px] flex items-center justify-center transition"
                                                x-text="cell.day">
                                        </button>
                                        <!-- Pickup Indicator Dots -->
                                        <div class="flex items-center justify-center gap-0.5 h-1.5 mt-0.5">
                                            <template x-for="(dotColor, dIdx) in cell.dots" :key="dIdx">
                                                <span class="w-1 h-1 rounded-full" :style="'background-color:' + dotColor"></span>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- ======================================================= -->
    <!-- MODAL 1: DETAIL RESERVASI                               -->
    <!-- ======================================================= -->
    <div x-cloak x-show="detailModalItem !== null"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-stone-900/50 backdrop-blur-xs"
         @keydown.escape.window="detailModalItem = null">
        <div x-show="detailModalItem !== null"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             @click.away="detailModalItem = null"
             class="w-full max-w-lg bg-white rounded-3xl shadow-modal border border-stone-200/80 overflow-hidden">
            <template x-if="detailModalItem">
                <div>
                    <!-- Header -->
                    <div class="px-6 py-4 bg-[#FAF6F0] border-b border-stone-200/70 flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-extrabold text-[#1F2937]" x-text="detailModalItem.id"></h3>
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold"
                                      :class="statusBadgeClass(detailModalItem.status)"
                                      x-text="detailModalItem.status"></span>
                            </div>
                            <p class="text-xs text-[#6B7280] mt-0.5" x-text="'Dibuat ' + detailModalItem.createdAt"></p>
                        </div>
                        <button type="button"
                                @click="detailModalItem = null"
                                aria-label="Tutup detail reservasi"
                                class="w-8 h-8 rounded-xl text-stone-400 hover:text-stone-700 hover:bg-white flex items-center justify-center transition cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto custom-scrollbar">
                        <!-- Customer & Pickup Info -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="p-3.5 rounded-2xl bg-[#FAF8F5] border border-stone-100 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center text-sm font-bold" x-text="detailModalItem.customerName.charAt(0).toUpperCase()"></div>
                                <div class="min-w-0">
                                    <div class="text-xs font-bold text-[#1F2937] truncate" x-text="detailModalItem.customerName"></div>
                                    <div class="text-[11px] text-[#6B7280]" x-text="detailModalItem.customerPhone"></div>
                                </div>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-[#FAF8F5] border border-stone-100">
                                <div class="text-[11px] text-[#6B7280] font-medium">Jadwal Pengambilan</div>
                                <div class="text-xs font-bold text-[#1F2937] mt-0.5" x-text="detailModalItem.pickupDate"></div>
                                <div class="text-[11px] font-semibold text-[#1B5E3A]" x-text="detailModalItem.pickupSlot + ' WIB'"></div>
                            </div>
                        </div>

                        <!-- Ordered Items -->
                        <div>
                            <h4 class="text-xs font-bold text-[#374151] uppercase tracking-wider mb-2">Rincian Makanan Surplus</h4>
                            <div class="rounded-2xl border border-stone-200/70 divide-y divide-stone-100 overflow-hidden">
                                <template x-for="(food, fIdx) in detailModalItem.items" :key="fIdx">
                                    <div class="px-4 py-2.5 flex items-center justify-between text-xs bg-white">
                                        <div>
                                            <span class="font-bold text-[#1F2937]" x-text="food.name"></span>
                                            <span class="text-[#6B7280] ml-1.5" x-text="'× ' + food.qty"></span>
                                        </div>
                                        <span class="font-bold text-[#1F2937]" x-text="formatRupiah(food.qty * food.price)"></span>
                                    </div>
                                </template>
                                <div class="px-4 py-3 bg-[#FAF8F5] flex items-center justify-between text-xs">
                                    <span class="font-bold text-[#4B5563]">Total (<span x-text="detailModalItem.itemCount"></span> porsi)</span>
                                    <span class="text-sm font-extrabold text-[#1B5E3A]" x-text="formatRupiah(detailModalItem.total)"></span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Footer Actions -->
                    <div class="px-6 py-4 bg-stone-50 border-t border-stone-100 flex flex-wrap items-center justify-end gap-2">
                        <button type="button"
                                x-show="detailModalItem.statusCode === 'pending'"
                                @click="updateReservationStatus(detailModalItem, 'confirmed')"
                                class="px-4 py-2 rounded-xl bg-[#1B5E3A] hover:bg-[#14472C] text-white text-xs font-bold transition cursor-pointer">
                            Konfirmasi Pesanan
                        </button>
                        <button type="button"
                                x-show="detailModalItem.statusCode === 'confirmed'"
                                @click="updateReservationStatus(detailModalItem, 'no_show')"
                                class="px-4 py-2 rounded-xl bg-[#0284C7] hover:bg-[#0369A1] text-white text-xs font-bold transition cursor-pointer">
                            Tandai Tidak Hadir
                        </button>
                        <button type="button"
                                x-show="detailModalItem.statusCode === 'confirmed'"
                                @click="updateReservationStatus(detailModalItem, 'completed')"
                                class="px-4 py-2 rounded-xl bg-[#1B5E3A] hover:bg-[#14472C] text-white text-xs font-bold transition cursor-pointer">
                            Selesaikan Reservasi
                        </button>
                        <button type="button"
                                x-show="detailModalItem.statusCode === 'pending' || detailModalItem.statusCode === 'confirmed'"
                                @click="updateReservationStatus(detailModalItem, 'cancelled')"
                                class="px-4 py-2 rounded-xl bg-red-50 hover:bg-red-100 text-red-600 text-xs font-bold transition cursor-pointer">
                            Batalkan Reservasi
                        </button>
                        <button type="button"
                                @click="detailModalItem = null"
                                class="px-4 py-2 rounded-xl border border-stone-200 bg-white text-stone-700 hover:bg-stone-100 text-xs font-bold transition cursor-pointer">
                            Tutup
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

</div>
@endsection
