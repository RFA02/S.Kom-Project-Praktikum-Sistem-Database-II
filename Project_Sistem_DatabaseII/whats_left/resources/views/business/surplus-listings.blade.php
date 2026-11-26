@extends('layouts.business')

@section('title', 'Daftar Makanan Surplus - ' . $business->business_name)

@section('content')
<div class="flex h-screen overflow-hidden bg-[#FAF7F2]"
     x-data="{
    mobileSidebarOpen: false,
    activeNav: 'surplus',
    profileDropdownOpen: false,
    activeStatusTab: 'all',
    searchQuery: '',
    selectedCategory: 'Semua Kategori',
    categoryDropdownOpen: false,
    selectedDate: '',
    dateFilterOpen: false,
    sortBy: 'terbaru',
    sortDropdownOpen: false,
    currentPage: 1,
    itemsPerPage: 5,
    summaryPeriod: 'Minggu ini',
    summaryPeriodDropdownOpen: false,
    isAddModalOpen: false,
    isEditMode: false,
    editingId: null,
    isGuideModalOpen: false,
    isDetailModalOpen: false,
    selectedDetailItem: null,
    activeActionMenuId: null,
    formError: '',
    categoriesList: ['Semua Kategori', 'Roti & Kue', 'Makanan Berat', 'Snack', 'Dessert', 'Minuman', 'Lainnya'],
    statusLabels: {
        active: 'Aktif',
        sold_out: 'Stok Habis',
        closed: 'Ditutup',
        expired: 'Kedaluwarsa'
    },
    foodOptions: @js($foodOptions),
    foods: @js($listings),
    newListing: {
        food_id: '',
        listing_date: @js(now()->toDateString()),
        pickupStart: '',
        pickupEnd: '',
        stock: 1,
        discountPercentage: 30
    },

    get selectedFood() {
        return this.foodOptions.find(food => String(food.id) === String(this.newListing.food_id));
    },

    get calculatedDiscountPercent() {
        return Math.max(0, Math.min(90, Number(this.newListing.discountPercentage) || 0));
    },

    get calculatedDiscountPrice() {
        if (!this.selectedFood) return 0;
        return Math.round(this.selectedFood.normalPrice * (1 - this.calculatedDiscountPercent / 100));
    },

    get summaryData() {
        const today = new Date();
        const start = new Date(today);
        start.setHours(0, 0, 0, 0);
        const end = new Date(today);
        end.setHours(23, 59, 59, 999);

        if (this.summaryPeriod === 'Minggu ini') {
            const day = start.getDay();
            const diffToMonday = day === 0 ? 6 : day - 1;
            start.setDate(start.getDate() - diffToMonday);
            end.setTime(start.getTime());
            end.setDate(end.getDate() + 6);
            end.setHours(23, 59, 59, 999);
        } else if (this.summaryPeriod === 'Bulan ini') {
            start.setDate(1);
            end.setFullYear(today.getFullYear(), today.getMonth() + 1, 0);
            end.setHours(23, 59, 59, 999);
        }

        const rows = this.summaryPeriod === 'Semua waktu'
            ? this.foods
            : this.foods.filter(item => {
                const date = new Date(item.listingDateInput + 'T00:00:00');
                return date >= start && date <= end;
            });

        return {
            totalListings: rows.length,
            activeListings: rows.filter(item => item.statusCode === 'active').length,
            availableQuantity: rows.filter(item => item.statusCode === 'active').reduce((sum, item) => sum + item.stock, 0),
            reservations: rows.reduce((sum, item) => sum + item.reservationsCount, 0),
            savedPortions: rows.reduce((sum, item) => sum + item.completedPortions, 0),
            revenue: rows.reduce((sum, item) => sum + item.completedRevenue, 0)
        };
    },

    get filteredFoods() {
        return this.foods.filter(item => {
            if (this.activeStatusTab !== 'all' && item.statusCode !== this.activeStatusTab) return false;

            if (this.searchQuery.trim() !== '') {
                const query = this.searchQuery.toLowerCase().trim();
                if (!item.title.toLowerCase().includes(query) && !item.category.toLowerCase().includes(query)) return false;
            }

            if (this.selectedCategory !== 'Semua Kategori' && item.category !== this.selectedCategory) return false;
            if (this.selectedDate && item.listingDateInput !== this.selectedDate) return false;

            return true;
        }).sort((a, b) => {
            if (this.sortBy === 'harga-rendah') return a.price - b.price;
            if (this.sortBy === 'harga-tinggi') return b.price - a.price;
            if (this.sortBy === 'stok-banyak') return b.stock - a.stock;
            if (this.sortBy === 'nama-az') return a.title.localeCompare(b.title);
            return b.id - a.id;
        });
    },

    get paginatedFoods() {
        const start = (this.currentPage - 1) * this.itemsPerPage;
        return this.filteredFoods.slice(start, start + this.itemsPerPage);
    },

    get totalPages() {
        return Math.ceil(this.filteredFoods.length / this.itemsPerPage) || 1;
    },

    countByStatus(status) {
        if (status === 'all') return this.foods.length;
        return this.foods.filter(item => item.statusCode === status).length;
    },

    formatRupiah(value) {
        return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
    },

    openAddModal() {
        this.isEditMode = false;
        this.editingId = null;
        this.formError = '';
        this.newListing = {
            food_id: '',
            listing_date: @js(now()->toDateString()),
            pickupStart: '',
            pickupEnd: '',
            stock: 1,
            discountPercentage: 30
        };
        this.isAddModalOpen = true;
    },

    openEditModal(item) {
        this.isEditMode = true;
        this.editingId = item.id;
        this.formError = '';
        this.newListing = {
            food_id: item.food_id,
            listing_date: item.listingDateInput,
            pickupStart: item.pickupStartInput,
            pickupEnd: item.pickupEndInput,
            stock: item.stock,
            discountPercentage: item.discountPercentage
        };
        this.isAddModalOpen = true;
        this.activeActionMenuId = null;
    },

    openDetail(item) {
        this.selectedDetailItem = item;
        this.isDetailModalOpen = true;
        this.activeActionMenuId = null;
    },

    showToast(message, type = 'success') {
        this.$dispatch('show-toast', { msg: message, type });
    },

    async saveNewListing() {
        this.formError = '';

        const quantity = Number(this.newListing.stock);
        const discount = Number(this.newListing.discountPercentage);

        if (!this.newListing.food_id || !this.newListing.listing_date || !this.newListing.pickupStart || !this.newListing.pickupEnd) {
            this.formError = 'Lengkapi makanan, tanggal listing, dan jadwal pickup.';
            return;
        }

        if (!Number.isInteger(quantity) || quantity < 1) {
            this.formError = 'Jumlah porsi harus berupa bilangan bulat minimal 1.';
            return;
        }

        if (!Number.isFinite(discount) || discount < 1 || discount > 90) {
            this.formError = 'Diskon harus berada pada rentang 1 sampai 90 persen.';
            return;
        }

        if (new Date(this.newListing.pickupEnd) <= new Date(this.newListing.pickupStart)) {
            this.formError = 'Waktu akhir pickup harus setelah waktu mulai pickup.';
            return;
        }

        const payload = {
            food_id: Number(this.newListing.food_id),
            listing_date: this.newListing.listing_date,
            available_quantity: quantity,
            discount_percentage: discount,
            discounted_price: this.calculatedDiscountPrice,
            pickup_start_time: this.newListing.pickupStart,
            pickup_end_time: this.newListing.pickupEnd
        };

        const editing = this.isEditMode && this.editingId;
        const url = editing ? `/business/surplus-listings/${this.editingId}` : '/business/surplus-listings';

        try {
            const response = await fetch(url, {
                method: editing ? 'PUT' : 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify(payload)
            });
            const result = await response.json();

            if (!response.ok) {
                const firstError = result.errors ? Object.values(result.errors).flat()[0] : null;
                this.formError = firstError || result.message || 'Listing surplus gagal disimpan.';
                return;
            }

            this.isAddModalOpen = false;
            this.showToast(result.message || 'Listing surplus berhasil disimpan.');
            window.location.reload();
        } catch (error) {
            this.formError = 'Tidak dapat terhubung ke server. Silakan coba kembali.';
        }
    },

    async updateItemStatus(item, newStatus) {
        try {
            const response = await fetch(`/business/surplus-listings/${item.id}`, {
                method: 'PUT',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify({ listing_status: newStatus })
            });
            const result = await response.json();

            if (!response.ok) {
                this.showToast(result.message || 'Status listing gagal diperbarui.', 'error');
                return;
            }

            this.showToast(result.message || 'Status listing berhasil diperbarui.');
            window.location.reload();
        } catch (error) {
            this.showToast('Tidak dapat terhubung ke server. Silakan coba kembali.', 'error');
        }
    },

    async deleteItem(id) {
        if (!window.confirm('Hapus listing surplus ini? Listing yang memiliki histori reservasi tidak dapat dihapus.')) return;

        try {
            const response = await fetch(`/business/surplus-listings/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                }
            });
            const result = await response.json();

            if (!response.ok) {
                this.showToast(result.message || 'Listing gagal dihapus.', 'error');
                return;
            }

            this.showToast(result.message || 'Listing berhasil dihapus.');
            window.location.reload();
        } catch (error) {
            this.showToast('Tidak dapat terhubung ke server. Silakan coba kembali.', 'error');
        }
    }
}"
     @click="activeActionMenuId = null; categoryDropdownOpen = false; sortDropdownOpen = false; summaryPeriodDropdownOpen = false">

    <!-- ======================================================= -->
    <!-- 1. SIDEBAR KIRI (DESKTOP DOCKED & MOBILE DRAWER)        -->
    <!-- ======================================================= -->

    <x-business.sidebar active="surplus-listings" />

    <!-- ======================================================= -->
    <!-- 2. MAIN CONTENT AREA (TOPBAR + CANVAS LISTING SURPLUS)  -->
    <!-- ======================================================= -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-[#FAF7F2]">
        
        <x-business.topbar />

        <!-- MAIN SCROLLABLE CONTENT -->
        <main class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 py-7 custom-scrollbar">
            
            <div class="max-w-7xl mx-auto space-y-6">

                <!-- ======================================================= -->
                <!-- HEADER HALAMAN & TOMBOL AKSI UTAMA                      -->
                <!-- ======================================================= -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-stone-900 tracking-tight">
                                Daftar Makanan Surplus
                            </h1>
                            <span class="text-2xl">🌱</span>
                        </div>
                        <p class="text-xs sm:text-sm text-stone-500 mt-1">
                            Kelola dan tawarkan makanan surplusmu agar tidak terbuang sia-sia.
                        </p>
                    </div>

                    <!-- Tombol Aksi Kanan Atas (Sesuai Screenshot) -->
                    <div class="flex items-center gap-3 shrink-0">
                        <!-- 1. Tombol Panduan Surplus (Outline) -->
                        <button type="button"
                                @click="isGuideModalOpen = true"
                                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-stone-700 bg-white border border-stone-200 hover:bg-stone-50 hover:border-stone-300 transition shadow-2xs">
                            <svg class="w-4 h-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Panduan Surplus</span>
                        </button>

                        <!-- 2. Tombol + Buat Listing Surplus (Hijau Utama) -->
                        <button type="button"
                                @click="openAddModal()"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-white bg-[#1B5E3A] hover:bg-[#14472C] transition shadow-sm shadow-[#1B5E3A]/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Buat Listing Surplus</span>
                        </button>
                    </div>
                </div>

                <!-- ======================================================= -->
                <!-- FILTER TAB STATUS CEPAT DENGAN BADGE ANGKA              -->
                <!-- ======================================================= -->
                <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1">
                    
                    <!-- Tab: Semua (12) -->
                    <button type="button"
                            @click="activeStatusTab = 'all'; currentPage = 1"
                            :class="activeStatusTab === 'all' 
                                ? 'text-[#1B5E3A] font-bold' 
                                : 'text-stone-500 hover:text-stone-800 font-medium'"
                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs sm:text-sm transition shrink-0">
                        <span>Semua</span>
                        <span :class="activeStatusTab === 'all' 
                            ? 'bg-[#1B5E3A] text-white' 
                            : 'bg-stone-200 text-stone-600'"
                              class="text-[11px] font-bold px-2 py-0.5 rounded-full"
                              x-text="countByStatus('all')">
                        </span>
                    </button>

                    <!-- Tab: Tersedia (8) -->
                    <button type="button"
                            @click="activeStatusTab = 'active'; currentPage = 1"
                            :class="activeStatusTab === 'active' 
                                ? 'text-[#1B5E3A] font-bold' 
                                : 'text-stone-500 hover:text-stone-800 font-medium'"
                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs sm:text-sm transition shrink-0">
                        <span>Aktif</span>
                        <span :class="activeStatusTab === 'active' 
                            ? 'bg-[#1B5E3A] text-white' 
                            : 'bg-stone-200 text-stone-600'"
                              class="text-[11px] font-bold px-2 py-0.5 rounded-full"
                              x-text="countByStatus('active')">
                        </span>
                    </button>

                    <!-- Tab: Dalam Reservasi (2) -->
                    <button type="button"
                            @click="activeStatusTab = 'sold_out'; currentPage = 1"
                            :class="activeStatusTab === 'sold_out' 
                                ? 'text-[#1B5E3A] font-bold' 
                                : 'text-stone-500 hover:text-stone-800 font-medium'"
                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs sm:text-sm transition shrink-0">
                        <span>Stok Habis</span>
                        <span :class="activeStatusTab === 'sold_out' 
                            ? 'bg-[#1B5E3A] text-white' 
                            : 'bg-stone-200 text-stone-600'"
                              class="text-[11px] font-bold px-2 py-0.5 rounded-full"
                              x-text="countByStatus('sold_out')">
                        </span>
                    </button>

                    <!-- Tab: Selesai (10) -->
                    <button type="button"
                            @click="activeStatusTab = 'closed'; currentPage = 1"
                            :class="activeStatusTab === 'closed' 
                                ? 'text-[#1B5E3A] font-bold' 
                                : 'text-stone-500 hover:text-stone-800 font-medium'"
                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs sm:text-sm transition shrink-0">
                        <span>Ditutup</span>
                        <span :class="activeStatusTab === 'closed' 
                            ? 'bg-[#1B5E3A] text-white' 
                            : 'bg-stone-200 text-stone-600'"
                              class="text-[11px] font-bold px-2 py-0.5 rounded-full"
                              x-text="countByStatus('closed')">
                        </span>
                    </button>

                    <!-- Tab: Dibatalkan (2) -->
                    <button type="button"
                            @click="activeStatusTab = 'expired'; currentPage = 1"
                            :class="activeStatusTab === 'expired' 
                                ? 'text-[#1B5E3A] font-bold' 
                                : 'text-stone-500 hover:text-stone-800 font-medium'"
                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs sm:text-sm transition shrink-0">
                        <span>Kedaluwarsa</span>
                        <span :class="activeStatusTab === 'expired' 
                            ? 'bg-[#1B5E3A] text-white' 
                            : 'bg-stone-200 text-stone-600'"
                              class="text-[11px] font-bold px-2 py-0.5 rounded-full"
                              x-text="countByStatus('expired')">
                        </span>
                    </button>

                </div>

                <!-- ======================================================= -->
                <!-- GRID UTAMA: DAFTAR KARTU (KIRI) & PANEL RINGKASAN (KANAN)-->
                <!-- ======================================================= -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                    
                    <!-- KOLOM KIRI (DAFTAR MAKANAN SURPLUS + PAGINASI) -->
                    <div class="lg:col-span-8 space-y-4">
                        
                        <!-- BARIS KONTROL FILTER & PENCARIAN -->
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                            
                            <!-- 1. Search Bar -->
                            <div class="relative flex-1">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input type="text"
                                       x-model="searchQuery"
                                       @input="currentPage = 1"
                                       placeholder="Cari makanan surplus..."
                                       class="w-full pl-10 pr-4 py-2.5 bg-white border border-stone-200 rounded-xl text-xs sm:text-sm text-stone-800 placeholder-stone-400 focus:outline-none focus:border-[#1B5E3A] focus:ring-1 focus:ring-[#1B5E3A] shadow-2xs">
                            </div>

                            <!-- 2. Dropdown Filter Kategori -->
                            <div class="relative shrink-0" @click.stop>
                                <button type="button"
                                        @click="categoryDropdownOpen = !categoryDropdownOpen; sortDropdownOpen = false"
                                        class="w-full sm:w-auto inline-flex items-center justify-between gap-3 px-3.5 py-2.5 bg-white border border-stone-200 rounded-xl text-xs sm:text-sm font-medium text-stone-700 hover:bg-stone-50 transition shadow-2xs">
                                    <span x-text="selectedCategory"></span>
                                    <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                
                                <div x-cloak x-show="categoryDropdownOpen"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute right-0 sm:left-0 mt-1.5 w-48 bg-white border border-stone-200 rounded-xl shadow-lg py-1.5 z-30">
                                    <template x-for="cat in categoriesList" :key="cat">
                                        <button type="button"
                                                @click="selectedCategory = cat; categoryDropdownOpen = false; currentPage = 1"
                                                :class="selectedCategory === cat ? 'bg-[#EBF5F0] text-[#1B5E3A] font-bold' : 'text-stone-700 hover:bg-stone-50'"
                                                class="w-full text-left px-3.5 py-2 text-xs transition">
                                            <span x-text="cat"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <!-- 3. Filter Tanggal -->
                            <div class="relative shrink-0 flex items-center gap-1.5">
                                <input type="date"
                                       x-model="selectedDate"
                                       @change="currentPage = 1"
                                       aria-label="Filter tanggal listing"
                                       class="w-full sm:w-auto px-3 py-2 bg-white border border-stone-200 rounded-xl text-xs sm:text-sm font-medium text-stone-700 focus:outline-none focus:border-[#1B5E3A] focus:ring-1 focus:ring-[#1B5E3A] shadow-2xs">
                                <button type="button"
                                        x-show="selectedDate"
                                        x-cloak
                                        @click="selectedDate = ''; currentPage = 1"
                                        title="Hapus filter tanggal"
                                        class="px-2 py-2 rounded-xl bg-white border border-stone-200 text-stone-500 hover:text-stone-800 text-xs font-bold shadow-2xs">✕</button>
                            </div>

                            <!-- 4. Dropdown Urutkan: Terbaru -->
                            <div class="relative shrink-0" @click.stop>
                                <button type="button"
                                        @click="sortDropdownOpen = !sortDropdownOpen; categoryDropdownOpen = false"
                                        class="w-full sm:w-auto inline-flex items-center justify-between gap-3 px-3.5 py-2.5 bg-white border border-stone-200 rounded-xl text-xs sm:text-sm font-medium text-stone-700 hover:bg-stone-50 transition shadow-2xs">
                                    <span>Urutkan: <strong class="text-stone-900" x-text="sortBy === 'terbaru' ? 'Terbaru' : (sortBy === 'harga-rendah' ? 'Harga Terendah' : (sortBy === 'harga-tinggi' ? 'Harga Tertinggi' : 'Stok'))"></strong></span>
                                    <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                
                                <div x-cloak x-show="sortDropdownOpen"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute right-0 mt-1.5 w-44 bg-white border border-stone-200 rounded-xl shadow-lg py-1.5 z-30">
                                    <button type="button" @click="sortBy = 'terbaru'; sortDropdownOpen = false" class="w-full text-left px-3.5 py-2 text-xs hover:bg-stone-50 text-stone-700">Terbaru</button>
                                    <button type="button" @click="sortBy = 'harga-rendah'; sortDropdownOpen = false" class="w-full text-left px-3.5 py-2 text-xs hover:bg-stone-50 text-stone-700">Harga Terendah</button>
                                    <button type="button" @click="sortBy = 'harga-tinggi'; sortDropdownOpen = false" class="w-full text-left px-3.5 py-2 text-xs hover:bg-stone-50 text-stone-700">Harga Tertinggi</button>
                                    <button type="button" @click="sortBy = 'stok-banyak'; sortDropdownOpen = false" class="w-full text-left px-3.5 py-2 text-xs hover:bg-stone-50 text-stone-700">Stok Terbanyak</button>
                                </div>
                            </div>

                        </div>

                        <!-- DAFTAR ITEM KARTU SURPLUS (MATCHING SCREENSHOT LAYOUT) -->
                        <div class="space-y-3.5">
                            
                            <template x-for="item in paginatedFoods" :key="item.id">
                                <div class="bg-white rounded-2xl border border-stone-200/90 p-4 sm:p-5 shadow-xs hover:border-stone-300 transition-all duration-150 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    
                                    <!-- SECTION 1: THUMBNAIL + DETAIL INFORMASI -->
                                    <div class="flex items-start gap-4 flex-1 min-w-0">
                                        <!-- Thumbnail Foto Makanan -->
                                        <div class="relative shrink-0 w-24 h-24 sm:w-28 sm:h-28 rounded-xl bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center text-4xl shadow-2xs border border-stone-100 overflow-hidden">
                                            <img x-show="item.image" x-cloak :src="item.image" :alt="item.title" loading="lazy" class="absolute inset-0 w-full h-full object-cover">
                                            <span x-text="item.categoryIcon"></span>
                                        </div>

                                        <!-- Informasi Item -->
                                        <div class="flex-1 min-w-0 pt-0.5">
                                            <!-- Status Badge -->
                                            <div>
                                                <span :class="{
                                                    'bg-[#DCF2E4] text-[#1B5E3A]': item.statusCode === 'active',
                                                    'bg-[#FEF3C7] text-[#D97706]': item.statusCode === 'sold_out',
                                                    'bg-stone-100 text-stone-600': item.statusCode === 'closed',
                                                    'bg-rose-50 text-rose-600': item.statusCode === 'expired'
                                                }"
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold">
                                                    <span x-text="item.status"></span>
                                                </span>
                                            </div>

                                            <!-- Nama Makanan -->
                                            <h3 class="text-base sm:text-lg font-bold text-stone-900 truncate mt-1" x-text="item.title"></h3>

                                            <!-- Kategori -->
                                            <div class="flex items-center gap-1.5 text-xs text-stone-500 font-medium mt-0.5">
                                                <span x-text="item.categoryIcon"></span>
                                                <span x-text="item.category"></span>
                                            </div>

                                            <!-- Metadata Baris: Tanggal | Waktu Ambil | Sisa Stok -->
                                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-stone-500 mt-2">
                                                <!-- Tanggal -->
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    <span x-text="item.date"></span>
                                                </div>

                                                <!-- Waktu Ambil -->
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span x-text="item.pickupTime"></span>
                                                </div>

                                                <!-- Sisa Stok -->
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                    </svg>
                                                    <span class="font-semibold text-stone-700" x-text="item.stock + ' porsi'"></span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <!-- SECTION 2: PRICING (HARGA DISKON & HEMAT) -->
                                    <div class="md:text-right shrink-0 md:min-w-[150px] border-t md:border-t-0 pt-3 md:pt-0 border-stone-100">
                                        <!-- Harga Diskon Hijau Tebal -->
                                        <div class="text-lg sm:text-xl font-black text-[#1B5E3A]" x-text="formatRupiah(item.price)"></div>

                                        <!-- Badge Persentase Hemat Hijau Muda -->
                                        <div class="mt-0.5">
                                            <span class="inline-block px-2 py-0.5 rounded-md text-[11px] font-bold bg-[#DCF2E4] text-[#1B5E3A]"
                                                  x-text="item.discountBadge">
                                            </span>
                                        </div>

                                        <!-- Harga Normal Coret Abu-abu -->
                                        <div class="text-[11px] sm:text-xs text-stone-400 line-through mt-0.5">
                                            Harga Normal <span x-text="formatRupiah(item.originalPrice)"></span>
                                        </div>
                                    </div>

                                    <!-- SECTION 3: STATUS RESERVASI & TOMBOL AKSI -->
                                    <div class="flex flex-col sm:flex-row md:flex-col items-start md:items-end justify-between md:justify-center gap-3 shrink-0 md:min-w-[140px] border-t md:border-t-0 pt-3 md:pt-0 border-stone-100">
                                        
                                        <!-- Reservasi & Waktu Pembuatan -->
                                        <div class="md:text-right">
                                            <p class="text-xs font-semibold text-stone-700" x-text="item.reservationsCount + ' reservasi'"></p>
                                            <p class="text-[11px] text-stone-400 mt-0.5" x-text="item.completedPortions + ' porsi diselamatkan'"></p>
                                        </div>

                                        <!-- Tombol 'Lihat Detail' & Dropdown '...' -->
                                        <div class="flex items-center gap-2 w-full sm:w-auto">
                                            
                                            <!-- Tombol Lihat Detail -->
                                            <button type="button"
                                                    @click="openDetail(item)"
                                                    class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold text-stone-700 bg-white border border-stone-200 hover:bg-stone-50 hover:border-stone-300 transition shadow-2xs">
                                                <svg class="w-3.5 h-3.5 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                <span>Lihat Detail</span>
                                            </button>

                                            <!-- Tombol Titik Tiga (...) -->
                                            <div class="relative" @click.stop>
                                                <button type="button"
                                                        @click="activeActionMenuId = (activeActionMenuId === item.id ? null : item.id)"
                                                        class="p-2 rounded-xl text-stone-500 bg-white border border-stone-200 hover:bg-stone-50 transition shadow-2xs">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                                                    </svg>
                                                </button>

                                                <!-- Dropdown Aksi Titik Tiga -->
                                                <div x-cloak x-show="activeActionMenuId === item.id"
                                                     x-transition:enter="transition ease-out duration-100"
                                                     x-transition:enter-start="opacity-0 scale-95"
                                                     x-transition:enter-end="opacity-100 scale-100"
                                                     x-transition:leave="transition ease-in duration-75"
                                                     x-transition:leave-start="opacity-100 scale-100"
                                                     x-transition:leave-end="opacity-0 scale-95"
                                                     class="absolute right-0 bottom-full sm:bottom-auto sm:top-full mb-1 sm:mb-0 sm:mt-1.5 w-44 bg-white border border-stone-200 rounded-xl shadow-xl py-1.5 z-40 text-xs">
                                                    
                                                    <button type="button"
                                                            @click="openDetail(item)"
                                                            class="w-full text-left px-3.5 py-2 text-stone-700 hover:bg-stone-50 flex items-center gap-2">
                                                        <span>👁️</span> Lihat Detail
                                                    </button>

                                                    <button type="button"
                                                            @click="openEditModal(item)"
                                                            class="w-full text-left px-3.5 py-2 text-stone-700 hover:bg-stone-50 flex items-center gap-2">
                                                        <span>✏️</span> Edit Listing
                                                    </button>

                                                    <button type="button" x-show="item.statusCode === 'active'"
                                                            @click="updateItemStatus(item, 'closed')"
                                                            class="w-full text-left px-3.5 py-2 text-amber-700 hover:bg-amber-50 flex items-center gap-2">
                                                        <span>⏸️</span> Tutup Listing
                                                    </button>

                                                    <button type="button" x-show="item.statusCode === 'closed'"
                                                            @click="updateItemStatus(item, 'active')"
                                                            class="w-full text-left px-3.5 py-2 text-[#1B5E3A] hover:bg-[#EBF5F0] flex items-center gap-2">
                                                        <span>🟢</span> Aktifkan Listing
                                                    </button>

                                                    <div class="my-1 border-t border-stone-100"></div>

                                                    <button type="button"
                                                            @click="deleteItem(item.id)"
                                                            class="w-full text-left px-3.5 py-2 text-red-600 hover:bg-red-50 flex items-center gap-2">
                                                        <span>🗑️</span> Hapus Listing
                                                    </button>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                </div>
                            </template>

                            <!-- State Kosong jika hasil filter tidak ditemukan -->
                            <div x-cloak x-show="filteredFoods.length === 0" 
                                 class="bg-white rounded-2xl border border-stone-200 p-12 text-center">
                                <div class="w-16 h-16 rounded-full bg-stone-100 text-stone-400 mx-auto flex items-center justify-center text-2xl mb-3">
                                    🔍
                                </div>
                                <h4 class="text-sm font-bold text-stone-800">Tidak ada makanan surplus ditemukan</h4>
                                <p class="text-xs text-stone-500 mt-1">Coba sesuaikan kata kunci pencarian atau ubah tab status di atas.</p>
                                <button type="button"
                                        @click="searchQuery = ''; activeStatusTab = 'all'; selectedCategory = 'Semua Kategori'; selectedDate = ''; currentPage = 1"
                                        class="mt-4 px-4 py-2 text-xs font-bold text-[#1B5E3A] bg-[#EBF5F0] hover:bg-[#DCF2E4] rounded-xl transition">
                                    Reset Semua Filter
                                </button>
                            </div>

                        </div>

                        <!-- ======================================================= -->
                        <!-- NAVIGASI PAGINASI ANGKA (SESUAI SCREENSHOT: < 1 2 3 >)  -->
                        <!-- ======================================================= -->
                        <div class="pt-4 flex items-center justify-center gap-1.5" x-show="filteredFoods.length > 0">
                            <!-- Tombol Sebelumnya (<) -->
                            <button type="button"
                                    @click="if (currentPage > 1) currentPage--"
                                    :disabled="currentPage === 1"
                                    :class="currentPage === 1 ? 'opacity-40 cursor-not-allowed text-stone-400' : 'text-stone-700 hover:bg-white hover:border-stone-300'"
                                    class="w-8 h-8 rounded-lg border border-stone-200 bg-white flex items-center justify-center text-xs font-bold transition shadow-2xs">
                                &lt;
                            </button>

                            <!-- Paginasi Angka (1, 2, 3) -->
                            <template x-for="p in totalPages" :key="p">
                                <button type="button"
                                        @click="currentPage = p"
                                        :class="currentPage === p 
                                            ? 'bg-[#1B5E3A] text-white font-bold border-[#1B5E3A] shadow-xs' 
                                            : 'bg-white text-stone-700 border-stone-200 hover:bg-stone-50 font-medium'"
                                        class="w-8 h-8 rounded-lg border flex items-center justify-center text-xs transition">
                                    <span x-text="p"></span>
                                </button>
                            </template>

                            <!-- Tombol Selanjutnya (>) -->
                            <button type="button"
                                    @click="if (currentPage < totalPages) currentPage++"
                                    :disabled="currentPage === totalPages"
                                    :class="currentPage === totalPages ? 'opacity-40 cursor-not-allowed text-stone-400' : 'text-stone-700 hover:bg-white hover:border-stone-300'"
                                    class="w-8 h-8 rounded-lg border border-stone-200 bg-white flex items-center justify-center text-xs font-bold transition shadow-2xs">
                                &gt;
                            </button>
                        </div>

                    </div>

                    <!-- ======================================================= -->
                    <!-- KOLOM KANAN (SIDEBAR RINGKASAN, TIPS, & BANTUAN)        -->
                    <!-- ======================================================= -->
                    <div class="lg:col-span-4 space-y-4">
                        
                        <!-- 1. CARD: RINGKASAN SURPLUS (DENGAN DROPDOWN MINGGU INI) -->
                        <div class="bg-white rounded-2xl border border-stone-200/90 p-5 shadow-xs">
                            
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-sm font-extrabold text-stone-900 tracking-tight">Ringkasan Surplus</h3>
                                
                                <!-- Dropdown Periode 'Minggu ini' -->
                                <div class="relative" @click.stop>
                                    <button type="button"
                                            @click="summaryPeriodDropdownOpen = !summaryPeriodDropdownOpen"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium text-stone-600 bg-stone-50 border border-stone-200 rounded-lg hover:bg-stone-100 transition">
                                        <span x-text="summaryPeriod"></span>
                                        <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>

                                    <div x-cloak x-show="summaryPeriodDropdownOpen"
                                         x-transition
                                         class="absolute right-0 mt-1 w-32 bg-white border border-stone-200 rounded-xl shadow-lg py-1 z-30 text-xs">
                                        <button type="button" @click="summaryPeriod = 'Minggu ini'; summaryPeriodDropdownOpen = false" class="w-full text-left px-3 py-1.5 hover:bg-stone-50 text-stone-700">Minggu ini</button>
                                        <button type="button" @click="summaryPeriod = 'Bulan ini'; summaryPeriodDropdownOpen = false" class="w-full text-left px-3 py-1.5 hover:bg-stone-50 text-stone-700">Bulan ini</button>
                                        <button type="button" @click="summaryPeriod = 'Semua waktu'; summaryPeriodDropdownOpen = false" class="w-full text-left px-3 py-1.5 hover:bg-stone-50 text-stone-700">Semua waktu</button>
                                    </div>
                                </div>
                            </div>

                            <!-- 4 Kotak Metrik (Grid 2x2 Sesuai Screenshot) -->
                            <div class="grid grid-cols-2 gap-3">
                                
                                <!-- Metrik 1: Total Listing (12 listing) -->
                                <div class="p-3.5 rounded-xl border border-stone-100 bg-[#FAF7F2]/60 flex flex-col justify-between">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[11px] font-semibold text-stone-500">Total Listing</span>
                                        <div class="w-7 h-7 rounded-lg bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center text-xs">
                                            👨‍🍳
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <div class="text-2xl font-black text-stone-900 leading-none" x-text="summaryData.totalListings.toLocaleString('id-ID')"></div>
                                        <span class="text-[10px] text-stone-400">listing</span>
                                    </div>
                                </div>

                                <!-- Metrik 2: Total Porsi (176 porsi) -->
                                <div class="p-3.5 rounded-xl border border-stone-100 bg-[#FAF7F2]/60 flex flex-col justify-between">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[11px] font-semibold text-stone-500">Porsi Tersedia</span>
                                        <div class="w-7 h-7 rounded-lg bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center text-xs">
                                            🌱
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <div class="text-2xl font-black text-stone-900 leading-none" x-text="summaryData.availableQuantity.toLocaleString('id-ID')"></div>
                                        <span class="text-[10px] text-stone-400">porsi</span>
                                    </div>
                                </div>

                                <!-- Metrik 3: Berhasil Diselamatkan (86 porsi) -->
                                <div class="p-3.5 rounded-xl border border-stone-100 bg-[#FAF7F2]/60 flex flex-col justify-between">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[11px] font-semibold text-stone-500">Berhasil Diselamatkan</span>
                                        <div class="w-7 h-7 rounded-lg bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center text-xs">
                                            🛡️
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <div class="text-2xl font-black text-stone-900 leading-none" x-text="summaryData.savedPortions.toLocaleString('id-ID')"></div>
                                        <span class="text-[10px] text-stone-400">porsi</span>
                                    </div>
                                </div>

                                <!-- Metrik 4: Potensi Penghematan (Rp 1.860.000) -->
                                <div class="p-3.5 rounded-xl border border-stone-100 bg-[#FAF7F2]/60 flex flex-col justify-between">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[11px] font-semibold text-stone-500">Pendapatan Reservasi Selesai</span>
                                        <div class="w-7 h-7 rounded-lg bg-[#FEF3C7] text-[#D97706] flex items-center justify-center text-xs">
                                            🛍️
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <div class="text-sm font-extrabold text-stone-900 leading-tight" x-text="formatRupiah(summaryData.revenue)"></div>
                                        <span class="text-[10px] text-stone-400">reservasi completed</span>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <!-- 2. CARD: TIPS MENINGKATKAN RESERVASI (WARM YELLOW/AMBER) -->
                        <div class="bg-[#FFFBEB] rounded-2xl border border-[#FDE68A] p-5 shadow-xs">
                            
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-base">💡</span>
                                <h3 class="text-xs sm:text-sm font-extrabold text-stone-900">
                                    Tips Meningkatkan Reservasi
                                </h3>
                            </div>

                            <div class="space-y-2 text-xs text-stone-700">
                                <div class="flex items-start gap-2">
                                    <span class="text-[#1B5E3A] font-bold mt-0.5">✓</span>
                                    <p class="leading-relaxed">Gunakan nama makanan dan kategori yang mudah dikenali.</p>
                                </div>
                                <div class="flex items-start gap-2">
                                    <span class="text-[#1B5E3A] font-bold mt-0.5">✓</span>
                                    <p class="leading-relaxed">Tentukan harga yang wajar dan kompetitif.</p>
                                </div>
                                <div class="flex items-start gap-2">
                                    <span class="text-[#1B5E3A] font-bold mt-0.5">✓</span>
                                    <p class="leading-relaxed">Perbarui listing secara rutin.</p>
                                </div>
                            </div>

                            <button type="button"
                                    @click="isGuideModalOpen = true"
                                    class="mt-4 text-xs font-bold text-stone-900 hover:text-[#1B5E3A] inline-flex items-center gap-1 transition">
                                <span>Lihat semua tips</span>
                                <span>&rarr;</span>
                            </button>

                        </div>

                        <!-- 3. CARD: BUTUH BANTUAN? -->
                        <div class="bg-white rounded-2xl border border-stone-200/90 p-5 shadow-xs space-y-3.5">
                            
                            <div>
                                <h3 class="text-xs sm:text-sm font-extrabold text-stone-900">Butuh bantuan?</h3>
                                <p class="text-xs text-stone-500 mt-1 leading-relaxed">
                                    Tim kami siap membantumu mengelola surplus dengan lebih baik.
                                </p>
                            </div>

                            <!-- Tombol: Chat dengan Tim Support -->
                            <button type="button"
                                    disabled aria-disabled="true"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-stone-700 bg-white border border-stone-200 hover:bg-stone-50 hover:border-stone-300 transition shadow-2xs">
                                <svg class="w-4 h-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <span>Chat dengan Tim Support</span>
                            </button>

                            <!-- Link: Pusat Bantuan (FAQ) -->
                            <div class="pt-1 border-t border-stone-100">
                                <button type="button"
                                        @click="isGuideModalOpen = true"
                                        class="w-full flex items-center justify-between text-xs font-semibold text-stone-700 hover:text-[#1B5E3A] py-1 transition">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>Pusat Bantuan</span>
                                    </div>
                                    <span class="text-stone-400 text-sm">&rsaquo;</span>
                                </button>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </main>

    </div>

    <!-- ======================================================= -->
    <!-- 3. MODAL: + BUAT LISTING SURPLUS BARU                   -->
    <!-- ======================================================= -->
    <div x-cloak x-show="isAddModalOpen" 
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-add-title" 
         role="dialog" 
         aria-modal="true">
        
        <!-- Backdrop Blur -->
        <div x-show="isAddModalOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-stone-900/60 backdrop-blur-xs transition-opacity"
             @click="isAddModalOpen = false"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="isAddModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-stone-200">
                
                <!-- Modal Header -->
                <div class="px-6 py-5 border-b border-stone-100 flex items-center justify-between bg-[#FAF7F2]">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-[#DCF2E4] text-[#1B5E3A] flex items-center justify-center font-bold text-sm">
                            🌱
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-stone-900" id="modal-add-title"
                                x-text="isEditMode ? 'Edit Listing Surplus' : 'Buat Listing Makanan Surplus'"></h3>
                            <p class="text-xs text-stone-500">Pilih master makanan, jumlah porsi, diskon, dan jadwal pickup.</p>
                        </div>
                    </div>
                    <button type="button" 
                            @click="isAddModalOpen = false"
                            class="p-2 rounded-xl text-stone-400 hover:text-stone-700 hover:bg-stone-200/50 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body Form -->
                <form @submit.prevent="saveNewListing" class="p-6 space-y-4 text-xs sm:text-sm">
                    
                    <div>
                        <label class="block font-bold text-stone-700 mb-1">Makanan <span class="text-red-500">*</span></label>
                        <select x-model="newListing.food_id" required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 bg-white text-stone-800 focus:outline-none focus:border-[#1B5E3A]">
                            <option value="">Pilih master makanan</option>
                            <template x-for="food in foodOptions" :key="food.id">
                                <option :value="food.id" x-text="food.name + ' — ' + formatRupiah(food.normalPrice)"></option>
                            </template>
                        </select>
                        <p x-show="selectedFood" class="text-[11px] text-stone-500 mt-1"
                           x-text="selectedFood ? selectedFood.category : ''"></p>
                        <p x-show="foodOptions.length === 0" class="text-xs text-amber-700 mt-1">
                            Belum ada master makanan. Tambahkan makanan terlebih dahulu.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-stone-700 mb-1">Tanggal Listing <span class="text-red-500">*</span></label>
                            <input type="date" x-model="newListing.listing_date" required
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 bg-white text-stone-800 focus:outline-none focus:border-[#1B5E3A]">
                        </div>
                        <div>
                            <label class="block font-bold text-stone-700 mb-1">Jumlah Porsi <span class="text-red-500">*</span></label>
                            <input type="number" x-model="newListing.stock" min="1" step="1" required
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 bg-white text-stone-800 focus:outline-none focus:border-[#1B5E3A]">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 rounded-2xl bg-[#FDFBF7] border border-[#DCF2E4]">
                        <div>
                            <label class="block font-bold text-stone-700 mb-1">Harga Normal</label>
                            <div class="h-10 px-3.5 rounded-xl bg-stone-100 border border-stone-200 flex items-center text-stone-600 font-semibold"
                                 x-text="selectedFood ? formatRupiah(selectedFood.normalPrice) : 'Pilih makanan'"></div>
                        </div>
                        <div>
                            <label class="block font-bold text-stone-700 mb-1">Diskon (%) <span class="text-red-500">*</span></label>
                            <input type="number" x-model="newListing.discountPercentage" min="1" max="90" step="1" required
                                   class="w-full h-10 px-3.5 rounded-xl border border-stone-200 bg-white text-stone-800 focus:outline-none focus:border-[#1B5E3A]">
                        </div>
                        <div>
                            <label class="block font-bold text-stone-700 mb-1">Harga Listing</label>
                            <div class="h-10 px-3.5 rounded-xl bg-[#DCF2E4] border border-[#22C55E]/30 flex items-center font-extrabold text-[#1B5E3A]"
                                 x-text="selectedFood ? formatRupiah(calculatedDiscountPrice) : '—'"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-stone-700 mb-1">Mulai Pickup <span class="text-red-500">*</span></label>
                            <input type="datetime-local" x-model="newListing.pickupStart" required
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 bg-white text-stone-800 focus:outline-none focus:border-[#1B5E3A]">
                        </div>
                        <div>
                            <label class="block font-bold text-stone-700 mb-1">Selesai Pickup <span class="text-red-500">*</span></label>
                            <input type="datetime-local" x-model="newListing.pickupEnd" required
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 bg-white text-stone-800 focus:outline-none focus:border-[#1B5E3A]">
                        </div>
                    </div>

                    <div x-show="formError" x-cloak class="px-3.5 py-2.5 rounded-xl bg-red-50 border border-red-200 text-xs font-semibold text-red-600" x-text="formError"></div>

                    <!-- Modal Actions -->
                    <div class="pt-4 border-t border-stone-100 flex items-center justify-end gap-3">
                        <button type="button"
                                @click="isAddModalOpen = false"
                                class="px-5 py-2.5 rounded-xl font-bold text-stone-600 hover:bg-stone-100 transition">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-6 py-2.5 rounded-xl font-bold text-white bg-[#1B5E3A] hover:bg-[#14472C] transition shadow-md shadow-[#1B5E3A]/25">
                            <span x-text="isEditMode ? 'Simpan Perubahan' : 'Publikasikan Listing Surplus'"></span>
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <!-- ======================================================= -->
    <!-- 4. MODAL: PANDUAN SURPLUS                               -->
    <!-- ======================================================= -->
    <div x-cloak x-show="isGuideModalOpen" 
         class="fixed inset-0 z-50 overflow-y-auto"
         role="dialog" 
         aria-modal="true">
        
        <div class="fixed inset-0 bg-stone-900/60 backdrop-blur-xs transition-opacity"
             @click="isGuideModalOpen = false"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-xl border border-stone-200 p-6 space-y-4">
                
                <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">📘</span>
                        <h3 class="text-base font-extrabold text-stone-900">Panduan Makanan Surplus What's Left</h3>
                    </div>
                    <button type="button" 
                            @click="isGuideModalOpen = false"
                            class="p-1 rounded-lg text-stone-400 hover:text-stone-700">
                        ✕
                    </button>
                </div>

                <div class="space-y-3.5 text-xs sm:text-sm text-stone-600 leading-relaxed">
                    <div class="p-3.5 rounded-xl bg-[#FAF7F2] border border-stone-200/80">
                        <h4 class="font-bold text-stone-900 mb-1">1. Standar Kelayakan Makanan</h4>
                        <p>Pastikan makanan yang ditawarkan adalah surplus produksi yang masih sangat aman dan higienis dikonsumsi (bukan basi atau rusak).</p>
                    </div>

                    <div class="p-3.5 rounded-xl bg-[#FAF7F2] border border-stone-200/80">
                        <h4 class="font-bold text-stone-900 mb-1">2. Diskon Menarik (30% - 70%)</h4>
                        <p>Konsumen menyukai diskon penyelamatan makanan. Diskon yang kompetitif membantu mengosongkan stok surplus lebih cepat sebelum jam tutup.</p>
                    </div>

                    <div class="p-3.5 rounded-xl bg-[#FAF7F2] border border-stone-200/80">
                        <h4 class="font-bold text-stone-900 mb-1">3. Rentang Waktu Pengambilan (Pickup Window)</h4>
                        <p>Tentukan batas jam pengambilan yang jelas (misal 16.00 - 20.00) agar konsumen dapat tiba tepat waktu sesuai jam operasional toko.</p>
                    </div>

                    <div class="p-3.5 rounded-xl bg-[#FAF7F2] border border-stone-200/80">
                        <h4 class="font-bold text-stone-900 mb-1">4. Verifikasi Kode Booking Konsumen</h4>
                        <p>Saat konsumen datang mengambil, periksa kode booking reservasi unik mereka melalui menu 'Reservasi' sebelum menyerahkan pesanan.</p>
                    </div>
                </div>

                <div class="pt-3 border-t border-stone-100 text-right">
                    <button type="button"
                            @click="isGuideModalOpen = false"
                            class="px-5 py-2.5 rounded-xl font-bold text-white bg-[#1B5E3A] hover:bg-[#14472C] transition">
                        Saya Mengerti
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- ======================================================= -->
    <!-- 5. MODAL: LIHAT DETAIL LISTING                          -->
    <!-- ======================================================= -->
    <div x-cloak x-show="isDetailModalOpen" 
         class="fixed inset-0 z-50 overflow-y-auto"
         role="dialog" 
         aria-modal="true">
        
        <div class="fixed inset-0 bg-stone-900/60 backdrop-blur-xs transition-opacity"
             @click="isDetailModalOpen = false"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <template x-if="selectedDetailItem">
                <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-xl border border-stone-200 p-6 space-y-5">
                    
                    <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                        <div class="flex items-center gap-2">
                            <span class="text-xl" x-text="selectedDetailItem.categoryIcon"></span>
                            <h3 class="text-base font-extrabold text-stone-900" x-text="selectedDetailItem.title"></h3>
                        </div>
                        <button type="button" 
                                @click="isDetailModalOpen = false"
                                class="p-1 rounded-lg text-stone-400 hover:text-stone-700">
                            ✕
                        </button>
                    </div>

                    <!-- Foto & Status -->
                    <div class="flex flex-col sm:flex-row gap-4 items-start">
                        <div class="relative w-full sm:w-40 h-40 rounded-2xl bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center text-6xl shadow-sm overflow-hidden shrink-0">
                            <img x-show="selectedDetailItem.image" x-cloak :src="selectedDetailItem.image" :alt="selectedDetailItem.title" class="absolute inset-0 w-full h-full object-cover">
                            <span x-text="selectedDetailItem.categoryIcon"></span>
                        </div>
                        
                        <div class="flex-1 space-y-2 text-xs sm:text-sm">
                            <div>
                                <span class="text-stone-400 block text-[11px]">Status Listing</span>
                                <span :class="{
                                    'bg-[#DCF2E4] text-[#1B5E3A]': selectedDetailItem.statusCode === 'active',
                                    'bg-[#FEF3C7] text-[#D97706]': selectedDetailItem.statusCode === 'sold_out',
                                    'bg-stone-100 text-stone-600': selectedDetailItem.statusCode === 'closed',
                                    'bg-rose-50 text-rose-600': selectedDetailItem.statusCode === 'expired'
                                }"
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold mt-0.5">
                                    <span x-text="selectedDetailItem.status"></span>
                                </span>
                            </div>

                            <div>
                                <span class="text-stone-400 block text-[11px]">Harga Penawaran</span>
                                <div class="flex items-baseline gap-2 mt-0.5">
                                    <span class="text-lg font-black text-[#1B5E3A]" x-text="formatRupiah(selectedDetailItem.price)"></span>
                                    <span class="text-xs text-stone-400 line-through" x-text="formatRupiah(selectedDetailItem.originalPrice)"></span>
                                </div>
                            </div>

                            <div>
                                <span class="text-stone-400 block text-[11px]">Porsi Tersedia</span>
                                <span class="font-bold text-stone-900" x-text="selectedDetailItem.stock + ' porsi'"></span>
                            </div>

                            <div>
                                <span class="text-stone-400 block text-[11px]">Waktu Pengambilan</span>
                                <span class="font-semibold text-stone-800" x-text="selectedDetailItem.date + ' (' + selectedDetailItem.pickupTime + ')'"></span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-2 text-xs">
                        <div class="p-3 rounded-xl bg-[#FAF7F2] border border-stone-200/80">
                            <span class="text-stone-500 block">Reservasi Valid</span>
                            <strong class="text-stone-900 text-base" x-text="selectedDetailItem.reservationsCount"></strong>
                        </div>
                        <div class="p-3 rounded-xl bg-[#FAF7F2] border border-stone-200/80">
                            <span class="text-stone-500 block">Porsi Diselamatkan</span>
                            <strong class="text-stone-900 text-base" x-text="selectedDetailItem.completedPortions"></strong>
                        </div>
                        <div class="p-3 rounded-xl bg-[#FAF7F2] border border-stone-200/80">
                            <span class="text-stone-500 block">Pendapatan</span>
                            <strong class="text-stone-900 text-sm" x-text="formatRupiah(selectedDetailItem.completedRevenue)"></strong>
                        </div>
                    </div>

                    <!-- Footer Action -->
                    <div class="pt-3 border-t border-stone-100 flex items-center justify-between">
                        <button type="button"
                                @click="openEditModal(selectedDetailItem); isDetailModalOpen = false"
                                class="px-4 py-2 rounded-xl text-xs font-bold text-stone-700 bg-stone-100 hover:bg-stone-200 transition">
                            ✏️ Edit Listing
                        </button>

                        <div class="flex items-center gap-2">
                            <button type="button"
                                    x-show="selectedDetailItem.statusCode === 'active'"
                                    @click="updateItemStatus(selectedDetailItem, 'closed'); isDetailModalOpen = false"
                                    class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-amber-600 hover:bg-amber-700 transition">
                                Tutup Listing
                            </button>
                            <button type="button"
                                    x-show="selectedDetailItem.statusCode === 'closed'"
                                    @click="updateItemStatus(selectedDetailItem, 'active'); isDetailModalOpen = false"
                                    class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-[#1B5E3A] hover:bg-[#14472C] transition">
                                Aktifkan Listing
                            </button>
                        </div>
                    </div>

                </div>
            </template>
        </div>
    </div>

</div>
@endsection
