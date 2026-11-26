@extends('layouts.main')

@section('title', "Jelajahi Makanan Surplus - What's Left")

@section('content')
<div x-data="{
    foods: @js($listings),
    isAuthenticated: @js(auth()->check()),
    isCustomer: @js(auth()->check() && auth()->user()->role === 'customer'),
    loginUrl: @js(route('login')),

    searchQuery: @js($searchQuery),
    selectedCategory: 'Semua',
    priceRange: 'all',
    sortBy: 'terbaru',
    viewMode: 'grid',
    currentPage: 1,
    itemsPerPage: 10,
    mobileFilterOpen: false,
    collapseCategory: false,
    collapsePrice: false,

    init() {
        ['searchQuery', 'selectedCategory', 'priceRange', 'sortBy'].forEach(key => {
            this.$watch(key, () => { this.currentPage = 1; });
        });

        const initialListingId = @js($selectedListingId);
        if (initialListingId) {
            const match = this.foods.find(item => String(item.id) === String(initialListingId));
            if (match) {
                this.openDetail(match);
            }
        }
    },

    selectedFood: null,
    orderQuantity: 1,

    reservationModalOpen: false,
    reservationStep: 1,
    resItem: {},
    resQuantity: 1,
    createdReservation: null,
    reservationError: '',
    reservationLoading: false,

    get filteredFoods() {
        return this.foods.filter(food => {
            if (this.searchQuery.trim() !== '') {
                const query = this.searchQuery.toLowerCase().trim();
                if (!food.title.toLowerCase().includes(query) &&
                    !food.merchant.toLowerCase().includes(query) &&
                    !food.category.toLowerCase().includes(query) &&
                    !(food.pickupLocation || '').toLowerCase().includes(query) &&
                    !(food.description || '').toLowerCase().includes(query)) return false;
            }

            if (this.selectedCategory !== 'Semua' && food.category !== this.selectedCategory) return false;

            if (this.priceRange === 'under-10k' && food.discountPrice >= 10000) return false;
            if (this.priceRange === '10k-20k' && (food.discountPrice < 10000 || food.discountPrice >= 20000)) return false;
            if (this.priceRange === '20k-30k' && (food.discountPrice < 20000 || food.discountPrice >= 30000)) return false;
            if (this.priceRange === 'above-30k' && food.discountPrice < 30000) return false;

            return true;
        }).sort((a, b) => {
            if (this.sortBy === 'harga-terendah') return a.discountPrice - b.discountPrice;
            if (this.sortBy === 'harga-tertinggi') return b.discountPrice - a.discountPrice;
            if (this.sortBy === 'diskon-tertinggi') return b.discount - a.discount;
            return b.listingDateInput.localeCompare(a.listingDateInput);
        });
    },

    get paginatedFoods() {
        const start = (this.currentPage - 1) * this.itemsPerPage;
        return this.filteredFoods.slice(start, start + this.itemsPerPage);
    },

    get totalPages() {
        return Math.ceil(this.filteredFoods.length / this.itemsPerPage) || 1;
    },

    formatRupiah(value) {
        return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
    },

    resetFilters() {
        this.searchQuery = '';
        this.selectedCategory = 'Semua';
        this.priceRange = 'all';
        this.sortBy = 'terbaru';
        this.currentPage = 1;
    },

    openDetail(food) {
        this.selectedFood = food;
        this.orderQuantity = 1;
    },

    closeDetail() {
        this.selectedFood = null;
        this.orderQuantity = 1;
    },

    openReservation(food) {
        if (!this.isCustomer) {
            if (this.isAuthenticated) {
                this.showToast('Reservasi hanya dapat dibuat menggunakan akun Consumer.', 'error');
            } else {
                window.location.href = this.loginUrl;
            }
            return;
        }

        this.resItem = food || this.selectedFood;
        this.resQuantity = this.orderQuantity;
        this.reservationStep = 1;
        this.reservationError = '';
        this.createdReservation = null;
        this.closeDetail();
        this.reservationModalOpen = true;
    },

    closeReservationModal() {
        this.reservationModalOpen = false;
        this.reservationStep = 1;
        this.reservationError = '';
    },

    submitReservation() {
        if (!this.selectedFood) return;
        this.openReservation(this.selectedFood);
    },

    async finishReservation() {
        if (!this.isCustomer || this.reservationLoading) return;

        this.reservationError = '';
        this.reservationLoading = true;

        try {
            const response = await fetch(@js(route('consumer.reservations.store')), {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify({
                    surplus_listing_id: this.resItem.id,
                    portion_quantity: Number(this.resQuantity)
                })
            });

            const result = await response.json();

            if (!response.ok) {
                this.reservationError = result.message || 'Reservasi gagal dibuat.';
                return;
            }

            this.createdReservation = result.data;
            const reservedQty = Number(this.resQuantity);
            const targetId = this.resItem.id;
            this.foods = this.foods
                .map(item => item.id === targetId ? { ...item, stock: Math.max(0, Number(item.stock) - reservedQty) } : item)
                .filter(item => item.stock > 0);
            this.reservationStep = 2;
            this.showToast(result.message || 'Reservasi berhasil dibuat.', 'success');
        } catch (error) {
            this.reservationError = 'Tidak dapat terhubung ke server. Silakan coba kembali.';
        } finally {
            this.reservationLoading = false;
        }
    },

    showToast(message, type = 'success') {
        this.$dispatch('show-toast', { msg: message, type });
    }
}" 
class="bg-[#FAF6F0] min-h-screen pb-24">

    <!-- ========================================================================= -->
    <!-- 1. HERO / BANNER HEADER: "Jelajahi Makanan Surplus" (Persis Sesuai Mockup) -->
    <!-- ========================================================================= -->
    <section class="relative overflow-hidden pt-8 pb-6 sm:pb-8 border-b border-stone-200/60 bg-gradient-to-b from-[#FDFBF7] to-[#FAF6F0]">
        
        <!-- Ornamen Latar Belakang Halus -->
        <div class="absolute -top-24 -left-20 w-96 h-96 rounded-full bg-[#1B5E3A]/5 blur-3xl pointer-events-none"></div>
        <div class="absolute top-0 right-10 w-96 h-96 rounded-full bg-[#E67E22]/5 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                
                <!-- Sisi Kiri: Teks Judul Besar & Sub-judul -->
                <div class="w-full md:max-w-2xl text-left">
                    <div class="inline-flex items-center gap-1.5 mb-2">
                        <span class="w-2 h-2 rounded-full bg-[#1B5E3A] animate-ping"></span>
                        <span class="text-xs font-extrabold uppercase tracking-widest text-[#1B5E3A]">Katalog Food Rescue</span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-[42px] font-black text-[#1F2937] tracking-tight leading-tight">
                        Jelajahi 
                        <span class="text-[#E67E22] relative inline-block">
                            Makanan Surplus
                            <!-- 3 Aksen Garis Melengkung Hijau Radiating Persis Mockup -->
                            <svg class="absolute -top-3.5 -right-6 w-8 h-8 text-[#1B5E3A]" viewBox="0 0 32 32" fill="none" stroke="currentColor">
                                <path d="M4 18 C 7 12, 10 9, 13 8" stroke-width="2.5" stroke-linecap="round"/>
                                <path d="M14 6 C 16 3, 19 2, 22 2" stroke-width="2.5" stroke-linecap="round"/>
                                <path d="M25 8 C 28 9, 30 12, 31 16" stroke-width="2.5" stroke-linecap="round"/>
                            </svg>
                        </span>
                    </h1>

                    <p class="text-stone-600 text-sm sm:text-base font-medium mt-2 max-w-xl leading-relaxed">
                        Temukan makanan lezat dengan harga hemat dari berbagai bisnis di sekitarmu.
                    </p>
                </div>

                <!-- Sisi Kanan: Ilustrasi Visual Cantik (Tas Belanja What's Left, Salad Bowl, Pastry) Persis Mockup -->
                <div class="hidden md:flex items-center justify-end relative w-80 h-32 lg:h-36 shrink-0">
                    
                    <!-- Lingkaran Pastel Aksen -->
                    <div class="absolute right-4 w-28 h-28 rounded-full bg-[#FEF3C7] opacity-80 -z-10"></div>
                    <div class="absolute right-24 top-2 w-20 h-20 rounded-full bg-[#EBF5F0] opacity-80 -z-10"></div>

                    <div class="flex items-center gap-3">
                        
                        <!-- Mangkuk Salad / Food Fresh -->
                        <div class="w-20 h-20 rounded-2xl overflow-hidden shadow-md border-2 border-white transform -rotate-6 hover:rotate-0 transition duration-300">
                            <div class="w-full h-full flex items-center justify-center text-5xl bg-[#EBF5F0]">🥗</div>
                        </div>

                        <!-- Paperbag Kraft Mockup "What's Left" -->
                        <div class="relative w-24 h-28 rounded-xl bg-[#E8DCC4] border border-[#D5C4A1] shadow-lg flex flex-col items-center justify-between p-2 transform rotate-3 hover:scale-105 transition duration-300">
                            <!-- Handle Paperbag -->
                            <div class="w-10 h-4 border-2 border-[#B8A37A] rounded-t-full -mt-4 bg-transparent"></div>
                            
                            <!-- Logo Daun & What's Left di Tas -->
                            <div class="flex flex-col items-center text-center my-auto">
                                <div class="w-7 h-7 rounded-lg bg-[#1B5E3A] flex items-center justify-center text-white mb-1 shadow-xs">
                                    <svg class="w-4 h-4 text-[#F59E0B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <span class="text-[9px] font-black tracking-tight text-[#1B5E3A] leading-tight">What's Left</span>
                            </div>

                            <span class="text-[7px] font-bold text-stone-500 uppercase tracking-widest">Food Rescue</span>
                        </div>

                        <!-- Piring Roti / Croissant -->
                        <div class="w-18 h-18 rounded-2xl overflow-hidden shadow-md border-2 border-white transform rotate-6 hover:rotate-0 transition duration-300">
                            <div class="w-full h-full flex items-center justify-center text-5xl bg-[#EBF5F0]">🥐</div>
                        </div>

                    </div>
                </div>

            </div>

            <!-- ========================================================================= -->
            <!-- 2. SEARCH BAR & FILTER LOKASI MENGAMBANG (Persis Sesuai Mockup)           -->
            <!-- ========================================================================= -->
            <div class="mt-6 sm:mt-8">
                <div class="bg-white rounded-2xl shadow-card border border-stone-200/90 p-2 sm:p-2.5 flex flex-col md:flex-row items-center gap-2">
                    
                    <!-- Sisi Kiri: Kolom Input Pencarian Makanan -->
                    <div class="w-full md:flex-1 flex items-center gap-3 px-3.5 py-1.5">
                        <svg class="w-5 h-5 text-stone-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" 
                               x-model="searchQuery" 
                               placeholder="Cari makanan, bisnis, atau kategori..." 
                               class="w-full bg-transparent border-none text-stone-800 placeholder-stone-400 text-sm sm:text-base font-medium focus:outline-none focus:ring-0">
                        <button type="button" 
                                x-show="searchQuery.length > 0" 
                                @click="searchQuery = ''"
                                class="text-stone-400 hover:text-stone-600 p-1">
                            ✕
                        </button>
                    </div>

                    <!-- Garis Pemisah Vertikal Desktop -->
                    <div class="hidden md:block w-px h-8 bg-stone-200"></div>

                    <div class="w-full md:w-72 flex items-center gap-2.5 px-3.5 py-1.5 border-t md:border-t-0 border-stone-100">
                        <svg class="w-5 h-5 text-stone-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-stone-600 text-sm font-semibold truncate">Alamat pickup tersedia pada detail mitra</span>
                    </div>

                    <!-- Sisi Kanan: Tombol Pengaturan Filter (Membuka Mobile Filter di Layar Kecil) -->
                    <div class="w-full md:w-auto flex items-center justify-end px-1">
                        <button type="button" 
                                @click="mobileFilterOpen = !mobileFilterOpen"
                                class="w-full md:w-11 h-11 rounded-xl bg-stone-50 hover:bg-[#EBF5F0] text-stone-600 hover:text-[#1B5E3A] border border-stone-200/90 flex items-center justify-center transition-colors group"
                                title="Pengaturan Filter">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                            </svg>
                            <span class="md:hidden ml-2 text-xs font-bold">Buka Filter Lengkap</span>
                        </button>
                    </div>

                </div>
            </div>

            <!-- ========================================================================= -->
            <!-- 3. BAR KATEGORI CEPAT & SORTING / TOGGLE GRID VIEW (Persis Sesuai Mockup) -->
            <!-- ========================================================================= -->
            <div class="mt-6 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                
                <!-- Sisi Kiri: Tabs Kategori Cepat (Semua, Makanan Berat, Roti & Kue, Minuman, Snack, Dessert) -->
                <div class="flex items-center gap-2.5 overflow-x-auto no-scrollbar pb-1">
                    
                    <!-- 1. Semua [Aktif Hijau Tua #1B5E3A] -->
                    <button type="button" 
                            @click="selectedCategory = 'Semua'; currentPage = 1"
                            class="shrink-0 flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all duration-200 shadow-xs"
                            :class="selectedCategory === 'Semua' 
                                ? 'bg-[#1B5E3A] text-white shadow-[#1B5E3A]/20' 
                                : 'bg-white text-stone-700 hover:bg-stone-50 border border-stone-200/90'">
                        <!-- Ikon Grid 4 Kotak -->
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        <span>Semua</span>
                    </button>

                    <!-- 2. Makanan Berat -->
                    <button type="button" 
                            @click="selectedCategory = 'Makanan Berat'; currentPage = 1"
                            class="shrink-0 flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all duration-200 shadow-xs"
                            :class="selectedCategory === 'Makanan Berat' 
                                ? 'bg-[#1B5E3A] text-white shadow-[#1B5E3A]/20' 
                                : 'bg-white text-stone-700 hover:bg-stone-50 border border-stone-200/90'">
                        <!-- Ikon Sendok Garpu -->
                        <span class="text-sm">🍴</span>
                        <span>Makanan Berat</span>
                    </button>

                    <!-- 3. Roti & Kue -->
                    <button type="button" 
                            @click="selectedCategory = 'Roti & Kue'; currentPage = 1"
                            class="shrink-0 flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all duration-200 shadow-xs"
                            :class="selectedCategory === 'Roti & Kue' 
                                ? 'bg-[#1B5E3A] text-white shadow-[#1B5E3A]/20' 
                                : 'bg-white text-stone-700 hover:bg-stone-50 border border-stone-200/90'">
                        <!-- Ikon Croissant / Kue -->
                        <span class="text-sm">🥐</span>
                        <span>Roti & Kue</span>
                    </button>

                    <!-- 4. Minuman -->
                    <button type="button" 
                            @click="selectedCategory = 'Minuman'; currentPage = 1"
                            class="shrink-0 flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all duration-200 shadow-xs"
                            :class="selectedCategory === 'Minuman' 
                                ? 'bg-[#1B5E3A] text-white shadow-[#1B5E3A]/20' 
                                : 'bg-white text-stone-700 hover:bg-stone-50 border border-stone-200/90'">
                        <!-- Ikon Cangkir Kopi -->
                        <span class="text-sm">☕</span>
                        <span>Minuman</span>
                    </button>

                    <!-- 5. Snack -->
                    <button type="button" 
                            @click="selectedCategory = 'Snack'; currentPage = 1"
                            class="shrink-0 flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all duration-200 shadow-xs"
                            :class="selectedCategory === 'Snack' 
                                ? 'bg-[#1B5E3A] text-white shadow-[#1B5E3A]/20' 
                                : 'bg-white text-stone-700 hover:bg-stone-50 border border-stone-200/90'">
                        <!-- Ikon Kotak Snack -->
                        <span class="text-sm">🍿</span>
                        <span>Snack</span>
                    </button>

                    <!-- 6. Dessert -->
                    <button type="button" 
                            @click="selectedCategory = 'Dessert'; currentPage = 1"
                            class="shrink-0 flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all duration-200 shadow-xs"
                            :class="selectedCategory === 'Dessert' 
                                ? 'bg-[#1B5E3A] text-white shadow-[#1B5E3A]/20' 
                                : 'bg-white text-stone-700 hover:bg-stone-50 border border-stone-200/90'">
                        <!-- Ikon Es Krim -->
                        <span class="text-sm">🍦</span>
                        <span>Dessert</span>
                    </button>

                    <!-- 7. Lainnya -->
                    <button type="button" 
                            @click="selectedCategory = 'Lainnya'; currentPage = 1"
                            class="shrink-0 flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all duration-200 shadow-xs"
                            :class="selectedCategory === 'Lainnya' 
                                ? 'bg-[#1B5E3A] text-white shadow-[#1B5E3A]/20' 
                                : 'bg-white text-stone-700 hover:bg-stone-50 border border-stone-200/90'">
                        <span class="text-sm">🍽️</span>
                        <span>Lainnya</span>
                    </button>

                </div>

                <!-- Sisi Kanan: Dropdown Urutkan & Toggle View (Grid vs List) -->
                <div class="flex items-center justify-between sm:justify-end gap-3 shrink-0">
                    
                    <!-- Dropdown Sorting ("Urutkan: Terbaru") -->
                    <div class="relative" x-data="{ sortMenuOpen: false }">
                        <button type="button" 
                                @click="sortMenuOpen = !sortMenuOpen" 
                                @click.outside="sortMenuOpen = false"
                                class="flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white border border-stone-200/90 text-xs sm:text-sm font-semibold text-stone-700 shadow-xs hover:border-[#1B5E3A] transition-colors">
                            <span class="text-stone-400 font-normal">Urutkan:</span>
                            <span class="font-bold text-stone-900" 
                                  x-text="sortBy === 'terbaru' ? 'Terbaru' : (sortBy === 'harga-terendah' ? 'Harga Terendah' : (sortBy === 'diskon-tertinggi' ? 'Diskon Tertinggi' : 'Harga Tertinggi'))">
                                Terbaru
                            </span>
                            <svg class="w-3.5 h-3.5 text-stone-400 ml-1 transition-transform" :class="sortMenuOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-cloak x-show="sortMenuOpen" 
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="absolute right-0 mt-2 w-48 rounded-xl bg-white shadow-xl border border-stone-200 py-1.5 z-40">
                            <button type="button" @click="sortBy = 'terbaru'; sortMenuOpen = false" class="w-full text-left px-3.5 py-2 text-xs font-semibold text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A]">Terbaru</button>
                            <button type="button" @click="sortBy = 'diskon-tertinggi'; sortMenuOpen = false" class="w-full text-left px-3.5 py-2 text-xs font-semibold text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A]">Diskon Tertinggi</button>
                            <button type="button" @click="sortBy = 'harga-terendah'; sortMenuOpen = false" class="w-full text-left px-3.5 py-2 text-xs font-semibold text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A]">Harga Terendah</button>
                            <button type="button" @click="sortBy = 'harga-tertinggi'; sortMenuOpen = false" class="w-full text-left px-3.5 py-2 text-xs font-semibold text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A]">Harga Tertinggi</button>
                        </div>
                    </div>

                    <!-- Tombol Toggle Grid vs List (Persis Mockup) -->
                    <div class="flex items-center bg-white border border-stone-200/90 rounded-xl p-1 shadow-xs">
                        <!-- Tombol Grid View (4 Squares) -->
                        <button type="button" 
                                @click="viewMode = 'grid'" 
                                class="p-1.5 rounded-lg transition-colors"
                                :class="viewMode === 'grid' ? 'bg-[#EBF5F0] text-[#1B5E3A]' : 'text-stone-400 hover:text-stone-600'"
                                title="Tampilan Grid">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                        </button>

                        <!-- Tombol List View (Horizontal Lines) -->
                        <button type="button" 
                                @click="viewMode = 'list'" 
                                class="p-1.5 rounded-lg transition-colors"
                                :class="viewMode === 'list' ? 'bg-[#EBF5F0] text-[#1B5E3A]' : 'text-stone-400 hover:text-stone-600'"
                                title="Tampilan List">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>

                </div>

            </div>

        </div>
    </section>


    <!-- ========================================================================= -->
    <!-- 4. MAIN LAYOUT DUA KOLOM: SIDEBAR FILTER KIRI + HASIL GRID KANAN          -->
    <!-- ========================================================================= -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 sm:mt-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
            
            <!-- ===================================================================== -->
            <!-- KOLOM KIRI: SIDEBAR FILTER (Desktop Fixed-Width 280px / 3 Kolom)      -->
            <!-- ===================================================================== -->
            <aside class="hidden lg:block lg:col-span-3">
                <div class="bg-white rounded-2xl shadow-card border border-stone-200/90 p-5 space-y-6 sticky top-24">
                    
                    <!-- Header Sidebar: Filter + Tombol Reset (Hijau) -->
                    <div class="flex items-center justify-between pb-4 border-b border-stone-100">
                        <div class="flex items-center gap-2 text-stone-900 font-extrabold text-base">
                            <svg class="w-5 h-5 text-stone-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span>Filter</span>
                        </div>
                        <button type="button" 
                                @click="resetFilters()"
                                class="text-xs font-bold text-[#1B5E3A] hover:underline focus:outline-none">
                            Reset
                        </button>
                    </div>

                    <!-- 1. Bagian Kategori (Collapsible) -->
                    <div>
                        <button type="button" 
                                @click="collapseCategory = !collapseCategory"
                                class="w-full flex items-center justify-between text-xs font-extrabold uppercase tracking-wider text-stone-800 mb-3">
                            <span>Kategori</span>
                            <svg class="w-4 h-4 text-stone-400 transition-transform duration-200" :class="collapseCategory ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                            </svg>
                        </button>

                        <div x-show="!collapseCategory" class="space-y-2.5 text-xs font-medium text-stone-700">
                            <!-- Checkbox: Semua Kategori -->
                            <label class="flex items-center gap-2.5 cursor-pointer select-none group">
                                <input type="checkbox" 
                                       :checked="selectedCategory === 'Semua'" 
                                       @change="selectedCategory = 'Semua'"
                                       class="w-4 h-4 rounded text-[#1B5E3A] focus:ring-[#1B5E3A]/20 border-stone-300">
                                <span class="group-hover:text-[#1B5E3A]" :class="selectedCategory === 'Semua' ? 'font-bold text-stone-900' : ''">Semua Kategori</span>
                            </label>

                            <!-- Checkbox: Makanan Berat -->
                            <label class="flex items-center gap-2.5 cursor-pointer select-none group">
                                <input type="checkbox" 
                                       :checked="selectedCategory === 'Makanan Berat'" 
                                       @change="selectedCategory = selectedCategory === 'Makanan Berat' ? 'Semua' : 'Makanan Berat'"
                                       class="w-4 h-4 rounded text-[#1B5E3A] focus:ring-[#1B5E3A]/20 border-stone-300">
                                <span class="group-hover:text-[#1B5E3A]" :class="selectedCategory === 'Makanan Berat' ? 'font-bold text-stone-900' : ''">Makanan Berat</span>
                            </label>

                            <!-- Checkbox: Roti & Kue -->
                            <label class="flex items-center gap-2.5 cursor-pointer select-none group">
                                <input type="checkbox" 
                                       :checked="selectedCategory === 'Roti & Kue'" 
                                       @change="selectedCategory = selectedCategory === 'Roti & Kue' ? 'Semua' : 'Roti & Kue'"
                                       class="w-4 h-4 rounded text-[#1B5E3A] focus:ring-[#1B5E3A]/20 border-stone-300">
                                <span class="group-hover:text-[#1B5E3A]" :class="selectedCategory === 'Roti & Kue' ? 'font-bold text-stone-900' : ''">Roti & Kue</span>
                            </label>

                            <!-- Checkbox: Minuman -->
                            <label class="flex items-center gap-2.5 cursor-pointer select-none group">
                                <input type="checkbox" 
                                       :checked="selectedCategory === 'Minuman'" 
                                       @change="selectedCategory = selectedCategory === 'Minuman' ? 'Semua' : 'Minuman'"
                                       class="w-4 h-4 rounded text-[#1B5E3A] focus:ring-[#1B5E3A]/20 border-stone-300">
                                <span class="group-hover:text-[#1B5E3A]" :class="selectedCategory === 'Minuman' ? 'font-bold text-stone-900' : ''">Minuman</span>
                            </label>

                            <!-- Checkbox: Snack -->
                            <label class="flex items-center gap-2.5 cursor-pointer select-none group">
                                <input type="checkbox" 
                                       :checked="selectedCategory === 'Snack'" 
                                       @change="selectedCategory = selectedCategory === 'Snack' ? 'Semua' : 'Snack'"
                                       class="w-4 h-4 rounded text-[#1B5E3A] focus:ring-[#1B5E3A]/20 border-stone-300">
                                <span class="group-hover:text-[#1B5E3A]" :class="selectedCategory === 'Snack' ? 'font-bold text-stone-900' : ''">Snack</span>
                            </label>

                            <!-- Checkbox: Dessert -->
                            <label class="flex items-center gap-2.5 cursor-pointer select-none group">
                                <input type="checkbox" 
                                       :checked="selectedCategory === 'Dessert'" 
                                       @change="selectedCategory = selectedCategory === 'Dessert' ? 'Semua' : 'Dessert'"
                                       class="w-4 h-4 rounded text-[#1B5E3A] focus:ring-[#1B5E3A]/20 border-stone-300">
                                <span class="group-hover:text-[#1B5E3A]" :class="selectedCategory === 'Dessert' ? 'font-bold text-stone-900' : ''">Dessert</span>
                            </label>

                            <!-- Checkbox: Lainnya -->
                            <label class="flex items-center gap-2.5 cursor-pointer select-none group">
                                <input type="checkbox" 
                                       :checked="selectedCategory === 'Lainnya'" 
                                       @change="selectedCategory = selectedCategory === 'Lainnya' ? 'Semua' : 'Lainnya'"
                                       class="w-4 h-4 rounded text-[#1B5E3A] focus:ring-[#1B5E3A]/20 border-stone-300">
                                <span class="group-hover:text-[#1B5E3A]" :class="selectedCategory === 'Lainnya' ? 'font-bold text-stone-900' : ''">Lainnya</span>
                            </label>
                        </div>
                    </div>

                    <div class="border-t border-stone-100"></div>

                    <!-- 4. Bagian Harga (Pilih rentang harga) Sesuai Mockup -->
                    <div>
                        <button type="button" 
                                @click="collapsePrice = !collapsePrice"
                                class="w-full flex items-center justify-between text-xs font-extrabold uppercase tracking-wider text-stone-800 mb-1">
                            <span>Harga</span>
                            <svg class="w-4 h-4 text-stone-400 transition-transform duration-200" :class="collapsePrice ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                            </svg>
                        </button>
                        <p class="text-[11px] text-stone-400 mb-3">Pilih rentang harga</p>

                        <div x-show="!collapsePrice" class="space-y-2.5 text-xs font-medium text-stone-700">
                            <label class="flex items-center gap-2.5 cursor-pointer select-none">
                                <input type="radio" name="price" value="all" x-model="priceRange" class="text-[#1B5E3A] focus:ring-[#1B5E3A]/20 border-stone-300">
                                <span :class="priceRange === 'all' ? 'font-bold text-stone-900' : ''">Semua Harga</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer select-none">
                                <input type="radio" name="price" value="under-10k" x-model="priceRange" class="text-[#1B5E3A] focus:ring-[#1B5E3A]/20 border-stone-300">
                                <span :class="priceRange === 'under-10k' ? 'font-bold text-stone-900' : ''">Di bawah Rp 10.000</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer select-none">
                                <input type="radio" name="price" value="10k-20k" x-model="priceRange" class="text-[#1B5E3A] focus:ring-[#1B5E3A]/20 border-stone-300">
                                <span :class="priceRange === '10k-20k' ? 'font-bold text-stone-900' : ''">Rp 10.000 - Rp 20.000</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer select-none">
                                <input type="radio" name="price" value="20k-30k" x-model="priceRange" class="text-[#1B5E3A] focus:ring-[#1B5E3A]/20 border-stone-300">
                                <span :class="priceRange === '20k-30k' ? 'font-bold text-stone-900' : ''">Rp 20.000 - Rp 30.000</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer select-none">
                                <input type="radio" name="price" value="above-30k" x-model="priceRange" class="text-[#1B5E3A] focus:ring-[#1B5E3A]/20 border-stone-300">
                                <span :class="priceRange === 'above-30k' ? 'font-bold text-stone-900' : ''">Di atas Rp 30.000</span>
                            </label>
                        </div>
                    </div>
                </div>
            </aside>


            <!-- ===================================================================== -->
            <!-- KOLOM KANAN: AREA HASIL GRID MAKANAN (9 Kolom Desktop / Full Mobile)  -->
            <!-- ===================================================================== -->
            <main class="col-span-1 lg:col-span-9">
                
                <!-- Ringkasan Jumlah Hasil Makanan: "Menampilkan 24 hasil" Persis Mockup -->
                <div class="flex items-center justify-between mb-4">
                    <p class="text-xs sm:text-sm font-extrabold text-stone-800">
                        Menampilkan <span class="text-[#1B5E3A]" x-text="filteredFoods.length">24</span> hasil
                    </p>

                    <!-- Filter Tag Aktif Jika Ada Pencarian atau Filter Kategori -->
                    <div class="flex items-center gap-2 flex-wrap" x-show="selectedCategory !== 'Semua' || searchQuery !== '' || priceRange !== 'all'">
                        <span class="text-[11px] font-bold text-stone-400">Filter aktif:</span>
                        <template x-if="selectedCategory !== 'Semua'">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-[#EBF5F0] text-[#1B5E3A] text-xs font-bold">
                                <span x-text="selectedCategory"></span>
                                <button type="button" @click="selectedCategory = 'Semua'" class="hover:text-red-600">×</button>
                            </span>
                        </template>
                        <template x-if="searchQuery !== ''">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-stone-100 text-stone-700 text-xs font-bold">
                                <span x-text="'&quot;' + searchQuery + '&quot;'"></span>
                                <button type="button" @click="searchQuery = ''" class="hover:text-red-600">×</button>
                            </span>
                        </template>
                    </div>
                </div>

                <!-- ================================================================= -->
                <!-- GRID TAMPILAN KARTU MAKANAN (5 Kolom di Desktop Sesuai Mockup)   -->
                <!-- ================================================================= -->
                <template x-if="viewMode === 'grid'">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-3.5 sm:gap-4">
                        <template x-for="food in paginatedFoods" :key="food.id">
                            <div class="group bg-white rounded-2xl border border-stone-200/90 shadow-card hover:shadow-lg transition-all duration-300 flex flex-col justify-between overflow-hidden cursor-pointer"
                                 @click="openDetail(food)">
                                
                                <div>
                                    <!-- Foto Produk Makanan (Aspect 4:3) -->
                                    <div class="relative w-full aspect-[4/3] overflow-hidden bg-stone-100">
                                        <img x-show="food.image" x-cloak :src="food.image" alt="" loading="lazy" class="absolute inset-0 w-full h-full object-cover">
                                        <div class="w-full h-full flex items-center justify-center text-6xl bg-[#EBF5F0] text-[#1B5E3A]" x-text="food.categoryIcon"></div>

                                        <!-- Badge Diskon Oranye (Kiri Atas) Persis Mockup ("30% OFF", "25% OFF", dll.) -->
                                        <div class="absolute top-2.5 left-2.5 px-2.5 py-1 rounded-md bg-[#F59E0B] text-stone-900 font-black text-[11px] shadow-sm tracking-tight">
                                            <span x-text="food.discount + '% OFF'">30% OFF</span>
                                        </div>

                                        <!-- Tombol Ikon Wishlist (Kanan Atas) Persis Mockup -->
                                        
                                    </div>

                                    <!-- Konten Kartu Makanan -->
                                    <div class="p-3 sm:p-3.5 space-y-1.5">
                                        
                                        <!-- Nama Toko / Merchant & Centang Hijau Verified -->
                                        <div class="flex items-center gap-1.5 text-xs text-stone-500 font-medium">
                                            <span class="truncate" x-text="food.merchant"></span>
                                        </div>

                                        <!-- Judul Makanan (Tebal) -->
                                        <h3 class="text-xs sm:text-sm font-bold text-stone-900 group-hover:text-[#1B5E3A] transition-colors line-clamp-1" 
                                            x-text="food.title">
                                            Croissant Butter
                                        </h3>

                                        <!-- Harga Diskon & Harga Coret Persis Mockup -->
                                        <div class="flex items-baseline gap-1.5 flex-wrap pt-0.5">
                                            <span class="text-xs sm:text-sm font-extrabold text-[#E67E22]" 
                                                  x-text="formatRupiah(food.discountPrice)">
                                                Rp 10.500
                                            </span>
                                            <span class="text-[11px] line-through text-stone-400 font-medium" 
                                                  x-text="formatRupiah(food.originalPrice)">
                                                Rp 15.000
                                            </span>
                                        </div>

                                    </div>
                                </div>

                                <!-- Metadata Footer Kartu: Waktu Ambil & Jarak -->
                                <div class="px-3 sm:px-3.5 pb-3 pt-1 border-t border-stone-100/80 space-y-1 text-[11px] text-stone-500">
                                    
                                    <!-- Waktu Ambil dengan Ikon Jam -->
                                    <div class="flex items-center gap-1.5 text-stone-600 truncate">
                                        <svg class="w-3.5 h-3.5 text-stone-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="truncate" x-text="food.pickupTime">Ambil hari ini 18.00 - 20.00</span>
                                    </div>

                                    <div class="flex items-center gap-1.5 text-stone-500">
                                        <span class="text-[11px] truncate" x-text="food.pickupLocation"></span>
                                    </div>
                                </div>

                            </div>
                        </template>
                    </div>
                </template>


                <!-- ================================================================= -->
                <!-- LIST TAMPILAN KARTU MAKANAN (Ketika View Mode = 'list')           -->
                <!-- ================================================================= -->
                <template x-if="viewMode === 'list'">
                    <div class="space-y-3.5">
                        <template x-for="food in paginatedFoods" :key="food.id">
                            <div class="group bg-white rounded-2xl border border-stone-200/90 shadow-card hover:shadow-lg transition-all duration-300 p-3 sm:p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 cursor-pointer"
                                 @click="openDetail(food)">
                                
                                <div class="flex items-center gap-3.5 sm:gap-4 w-full sm:w-auto">
                                    <!-- Foto Thumbnail 4:3 -->
                                    <div class="relative w-24 h-20 sm:w-28 sm:h-24 rounded-xl overflow-hidden bg-stone-100 shrink-0">
                                        <img x-show="food.image" x-cloak :src="food.image" alt="" loading="lazy" class="absolute inset-0 w-full h-full object-cover">
                                        <div class="w-full h-full flex items-center justify-center text-4xl bg-[#EBF5F0] text-[#1B5E3A]" x-text="food.categoryIcon"></div>
                                        <span class="absolute top-1.5 left-1.5 px-2 py-0.5 rounded bg-[#F59E0B] text-stone-900 font-extrabold text-[10px]">
                                            <span x-text="food.discount + '% OFF'"></span>
                                        </span>
                                    </div>

                                    <!-- Detail Informasi -->
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-1.5 text-xs text-stone-500 font-medium">
                                            <span x-text="food.merchant"></span>
                                            
                                            <span class="text-stone-300">•</span>
                                            <span class="text-stone-500" x-text="food.category">Mitra lokal</span>
                                        </div>

                                        <h3 class="text-sm sm:text-base font-bold text-stone-900 group-hover:text-[#1B5E3A] transition-colors" x-text="food.title"></h3>

                                        <div class="flex items-center gap-2 text-xs text-stone-500">
                                            <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span x-text="food.pickupTime"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sisi Kanan: Harga & Tombol Aksi -->
                                <div class="flex items-center justify-between sm:justify-end gap-4 w-full sm:w-auto border-t sm:border-t-0 pt-2 sm:pt-0 border-stone-100">
                                    <div class="text-left sm:text-right">
                                        <p class="text-base sm:text-lg font-black text-[#E67E22]" x-text="formatRupiah(food.discountPrice)"></p>
                                        <p class="text-xs line-through text-stone-400" x-text="formatRupiah(food.originalPrice)"></p>
                                    </div>
                                    
                                    <button type="button" 
                                            @click.stop="openDetail(food)"
                                            class="px-4 py-2 rounded-xl bg-[#1B5E3A] hover:bg-[#14472C] text-white text-xs font-bold transition shadow-xs">
                                        Detail
                                    </button>
                                </div>

                            </div>
                        </template>
                    </div>
                </template>


                <!-- ================================================================= -->
                <!-- KONDISI KOSONG (EMPTY STATE) JIKA HASIL FILTER = 0                -->
                <!-- ================================================================= -->
                <div x-show="filteredFoods.length === 0" class="text-center py-16 bg-white rounded-3xl border border-stone-200/80 p-8 shadow-xs">
                    <div class="w-16 h-16 rounded-full bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-stone-900">Tidak ada makanan surplus ditemukan</h3>
                    <p class="text-xs sm:text-sm text-stone-500 mt-1 max-w-md mx-auto">
                        Coba sesuaikan kata kunci pencarian, rentang jarak radius, atau ubah kategori untuk menemukan pilihan lainnya.
                    </p>
                    <button type="button" 
                            @click="resetFilters()"
                            class="mt-5 px-5 py-2.5 rounded-xl bg-[#1B5E3A] text-white text-xs font-bold shadow-md hover:bg-[#14472C] transition">
                        Reset Semua Filter
                    </button>
                </div>


                <!-- ========================================================================= -->
                <!-- 5. PAGINASI BAWAH: "< 1 2 3 ... 5 >" DENGAN HALAMAN 1 HIJAU (Persis Mockup) -->
                <!-- ========================================================================= -->
                <div x-show="totalPages > 1" class="mt-10 sm:mt-12 flex items-center justify-center gap-1.5 sm:gap-2">
                    
                                <!-- Tombol Prev -->
                    <button type="button"
                            @click="currentPage = Math.max(1, currentPage - 1); window.scrollTo({ top: 300, behavior: 'smooth' });"
                            :disabled="currentPage === 1"
                            class="w-9 h-9 rounded-xl border border-stone-200/90 bg-white flex items-center justify-center text-stone-500 hover:text-stone-900 disabled:opacity-40 disabled:cursor-not-allowed transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>

                    <template x-for="page in totalPages" :key="page">
                        <button type="button"
                                @click="currentPage = page; window.scrollTo({ top: 300, behavior: 'smooth' });"
                                class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-bold transition-all shadow-xs"
                                :class="currentPage === page
                                    ? 'bg-[#1B5E3A] text-white shadow-[#1B5E3A]/25'
                                    : 'border border-stone-200/90 bg-white text-stone-700 hover:bg-stone-50'"
                                x-text="page"></button>
                    </template>

                    <!-- Tombol Next -->
                    <button type="button"
                            @click="currentPage = Math.min(totalPages, currentPage + 1); window.scrollTo({ top: 300, behavior: 'smooth' });"
                            :disabled="currentPage >= totalPages"
                            class="w-9 h-9 rounded-xl border border-stone-200/90 bg-white flex items-center justify-center text-stone-500 hover:text-stone-900 disabled:opacity-40 disabled:cursor-not-allowed transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>

                </div>

            </main>

        </div>
    </div>


    <!-- ========================================================================= -->
    <!-- 8. MOBILE SLIDE-OVER DRAWER FILTER (RESPONSIF MOBILE-FIRST)               -->
    <!-- ========================================================================= -->
    <div x-cloak x-show="mobileFilterOpen" class="relative z-50 lg:hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-stone-900/50 backdrop-blur-xs" @click="mobileFilterOpen = false"></div>

        <div class="fixed inset-y-0 right-0 z-50 w-full max-w-xs bg-white shadow-2xl p-5 flex flex-col justify-between overflow-y-auto">
            <div class="space-y-6">
                <div class="flex items-center justify-between pb-3 border-b border-stone-200">
                    <h3 class="text-base font-extrabold text-stone-900">Filter Pencarian</h3>
                    <button type="button" @click="mobileFilterOpen = false" class="text-stone-400 hover:text-stone-700 font-bold text-lg">✕</button>
                </div>

                <!-- Kategori di Mobile Drawer -->
                <div>
                    <h4 class="text-xs font-bold uppercase text-stone-500 mb-2">Kategori</h4>
                    <div class="space-y-2 text-xs">
                        <template x-for="cat in ['Semua', 'Makanan Berat', 'Roti & Kue', 'Minuman', 'Snack', 'Dessert', 'Lainnya']" :key="cat">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="mobile_cat" :value="cat" x-model="selectedCategory" class="text-[#1B5E3A] focus:ring-[#1B5E3A]">
                                <span x-text="cat"></span>
                            </label>
                        </template>
                    </div>
                </div>

                <!-- Harga di Mobile Drawer -->
                <div>
                    <h4 class="text-xs font-bold uppercase text-stone-500 mb-2">Rentang Harga</h4>
                    <div class="space-y-2 text-xs">
                        <label class="flex items-center gap-2"><input type="radio" value="all" x-model="priceRange" class="text-[#1B5E3A]"> Semua Harga</label>
                        <label class="flex items-center gap-2"><input type="radio" value="under-10k" x-model="priceRange" class="text-[#1B5E3A]"> &lt; Rp 10.000</label>
                        <label class="flex items-center gap-2"><input type="radio" value="10k-20k" x-model="priceRange" class="text-[#1B5E3A]"> Rp 10.000 - Rp 20.000</label>
                        <label class="flex items-center gap-2"><input type="radio" value="20k-30k" x-model="priceRange" class="text-[#1B5E3A]"> Rp 20.000 - Rp 30.000</label>
                        <label class="flex items-center gap-2"><input type="radio" value="above-30k" x-model="priceRange" class="text-[#1B5E3A]"> &gt; Rp 30.000</label>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-stone-100 flex gap-2">
                <button type="button" @click="resetFilters(); mobileFilterOpen = false" class="w-1/2 py-2.5 rounded-xl border border-stone-300 text-stone-700 text-xs font-bold">Reset</button>
                <button type="button" @click="mobileFilterOpen = false" class="w-1/2 py-2.5 rounded-xl bg-[#1B5E3A] text-white text-xs font-bold">Terapkan</button>
            </div>
        </div>
    </div>


    <!-- ========================================================================= -->
    <!-- 9. MODAL POP-UP DETAIL MAKANAN (MEMAKAI KOMPONEN RESMI YANG SUDAH ADA)     -->
    <!-- ========================================================================= -->
    @include('components.consumer.food-detail-modal')

    <!-- ========================================================================= -->
    <!-- 10. MODAL POP-UP RESERVASI MAKANAN MULTI-STEP SESUAI SCREENSHOT MOCKUP    -->
    <!-- ========================================================================= -->
    @include('components.consumer.reservation-modal')

</div>
@endsection
