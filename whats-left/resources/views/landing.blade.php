@extends('layouts.main')

@section('title', "What's Left - Good Food Deserves a Second Chance")

@section('content')
<div x-data="{
    // Mock data for popular surplus foods matching mockup screenshot
    foods: [
        {
            id: 1,
            title: 'Croissant Butter',
            merchant: 'Sunny Bakery',
            merchantAvatar: 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=120&q=80',
            merchantVerified: true,
            rating: 4.8,
            reviewsCount: 120,
            distance: '1.2 km',
            category: 'Bakery',
            categorySpec: 'Roti & Kue',
            discount: 30,
            originalPrice: 15000,
            discountPrice: 10500,
            pickupTime: 'Hari ini, 18.00 - 20.00',
            pickupLocation: 'Jl. Kemang Raya No. 12, Jakarta',
            stock: 12,
            unit: 'pcs',
            freshness: 'Dibuat hari ini pukul 07.00',
            image: 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=800&q=80',
            gallery: [
                'https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=800&q=80',
                'https://images.unsplash.com/photo-1549931319-a545dcf3bc73?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1586788680434-30d324b2d46f?auto=format&fit=crop&w=600&q=80'
            ],
            isWishlist: false,
            description: 'Croissant butter premium yang lembut di dalam dan renyah di luar. Pas dinikmati bersama kopi atau teh hangat.'
        },
        {
            id: 2,
            title: 'Chicken Teriyaki Bowl',
            merchant: 'RiceBowl Kitchen',
            merchantAvatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=120&q=80',
            merchantVerified: true,
            rating: 4.9,
            reviewsCount: 95,
            distance: '1.2 km',
            category: 'Makanan Berat',
            categorySpec: 'Makanan Berat',
            discount: 35,
            originalPrice: 28000,
            discountPrice: 18000,
            pickupTime: 'Hari ini, 17.00 - 19.30',
            pickupLocation: 'Jl. Surya Sumantri No. 45, Bandung',
            stock: 6,
            unit: 'porsi',
            freshness: 'Dimasak hangat hari ini pukul 11.30 WIB',
            image: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&q=80',
            gallery: [
                'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&q=80',
                'https://images.unsplash.com/photo-1512058564366-18510be2db19?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?auto=format&fit=crop&w=600&q=80'
            ],
            isWishlist: false,
            description: 'Semangkuk nasi pulen dengan paha ayam panggang saus teriyaki gurih manis, taburan wijen, dan brokoli kukus segar.'
        },
        {
            id: 3,
            title: 'Spaghetti Bolognese',
            merchant: 'Pasta La Vista',
            merchantAvatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=120&q=80',
            merchantVerified: true,
            rating: 4.7,
            reviewsCount: 84,
            distance: '1.5 km',
            category: 'Makanan Berat',
            categorySpec: 'Makanan Berat',
            discount: 25,
            originalPrice: 27000,
            discountPrice: 20500,
            pickupTime: 'Hari ini, 19.00 - 21.00',
            pickupLocation: 'Jl. Riau No. 88, Bandung',
            stock: 5,
            unit: 'porsi',
            freshness: 'Dibuat hari ini pukul 12.00 WIB',
            image: 'https://images.unsplash.com/photo-1621996346565-e3d5d6281691?auto=format&fit=crop&w=800&q=80',
            gallery: [
                'https://images.unsplash.com/photo-1621996346565-e3d5d6281691?auto=format&fit=crop&w=800&q=80',
                'https://images.unsplash.com/photo-1551183053-bf91a1d81141?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=600&q=80'
            ],
            isWishlist: false,
            description: 'Pasta al dente dipadukan dengan saus tomat daging cincang sapi gurih, rempah oregano, dan taburan keju parmesan.'
        },
        {
            id: 4,
            title: 'Red Velvet Slice',
            merchant: 'Sweet Moments',
            merchantAvatar: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=120&q=80',
            merchantVerified: true,
            rating: 4.9,
            reviewsCount: 140,
            distance: '2.1 km',
            category: 'Dessert',
            categorySpec: 'Dessert & Kue',
            discount: 35,
            originalPrice: 18500,
            discountPrice: 12000,
            pickupTime: 'Hari ini, 18.00 - 20.30',
            pickupLocation: 'Jl. Dipati Ukur No. 22, Bandung',
            stock: 4,
            unit: 'slice',
            freshness: 'Kulkas pendingin toko suhu 4°C',
            image: 'https://images.unsplash.com/photo-1586788680434-30d324b2d46f?auto=format&fit=crop&w=800&q=80',
            gallery: [
                'https://images.unsplash.com/photo-1586788680434-30d324b2d46f?auto=format&fit=crop&w=800&q=80',
                'https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1535141192574-5d4897c13136?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1549931319-a545dcf3bc73?auto=format&fit=crop&w=600&q=80'
            ],
            isWishlist: false,
            description: 'Kue red velvet lembut dengan lapisan cream cheese frosting premium yang manis asam segar sempurna untuk penutup hari.'
        },
        {
            id: 5,
            title: 'Salmon Sushi Set',
            merchant: 'Sushi Sato',
            merchantAvatar: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=120&q=80',
            merchantVerified: true,
            rating: 4.8,
            reviewsCount: 110,
            distance: '2.8 km',
            category: 'Makanan Berat',
            categorySpec: 'Japanese Cuisine',
            discount: 40,
            originalPrice: 37000,
            discountPrice: 22000,
            pickupTime: 'Hari ini, 19.30 - 21.30',
            pickupLocation: 'Jl. Ir. H. Juanda No. 102, Bandung',
            stock: 3,
            unit: 'set',
            freshness: 'Disiapkan chef hari ini pukul 14.00 WIB',
            image: 'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?auto=format&fit=crop&w=800&q=80',
            gallery: [
                'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?auto=format&fit=crop&w=800&q=80',
                'https://images.unsplash.com/photo-1611143669185-af224c5e3252?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1553621042-f6e147245754?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1563245372-f21724e3856d?auto=format&fit=crop&w=600&q=80'
            ],
            isWishlist: false,
            description: 'Paket kombinasi salmon nigiri segar, salmon maki roll, dan wasabi shoyu. Higienis dalam kemasan bento tertutup rapat.'
        }
    ],

    // Search query & location state
    searchQuery: '',
    selectedLocation: 'Lokasi Saya',
    locations: ['Lokasi Saya', 'Jatinangor, Sumedang', 'Coblong, Bandung', 'Sukajadi, Bandung', 'Cibiru, Bandung Timur'],

    // Modal & Interactive Detail State
    selectedFood: null,
    activeImage: null,
    orderQuantity: 1,

    // Live Countdown Timer (Default 02:45:30 -> 9930 detik)
    timerSeconds: 9930,
    timerInterval: null,

    get modalTimerFormatted() {
        const hours = Math.floor(this.timerSeconds / 3600);
        const minutes = Math.floor((this.timerSeconds % 3600) / 60);
        const seconds = this.timerSeconds % 60;
        const pad = (n) => String(n).padStart(2, '0');
        return `${pad(hours)} : ${pad(minutes)} : ${pad(seconds)}`;
    },

    startTimer() {
        if (this.timerInterval) clearInterval(this.timerInterval);
        this.timerInterval = setInterval(() => {
            if (this.timerSeconds > 0) {
                this.timerSeconds--;
            } else {
                clearInterval(this.timerInterval);
            }
        }, 1000);
    },

    stopTimer() {
        if (this.timerInterval) {
            clearInterval(this.timerInterval);
            this.timerInterval = null;
        }
    },

    // Carousel scroll helper
    scrollCarousel(direction) {
        const container = this.$refs.foodCarousel;
        if (container) {
            const scrollAmount = 320;
            container.scrollBy({
                left: direction === 'next' ? scrollAmount : -scrollAmount,
                behavior: 'smooth'
            });
        }
    },

    formatRupiah(number) {
        return 'Rp ' + (number || 0).toLocaleString('id-ID');
    },

    toggleWishlist(food) {
        food.isWishlist = !food.isWishlist;
        $dispatch('show-toast', {
            msg: food.isWishlist 
                ? `${food.title} ditambahkan ke favorit! ❤️` 
                : `${food.title} dihapus dari favorit.`
        });
    },

    openDetail(food) {
        this.selectedFood = food;
        this.activeImage = food.image;
        this.orderQuantity = 1;
        this.timerSeconds = 9930; // 02:45:30
        this.startTimer();
    },

    closeDetail() {
        this.selectedFood = null;
        this.activeImage = null;
        this.stopTimer();
    },

    submitReservation() {
        if (!this.selectedFood) return;
        const total = this.selectedFood.discountPrice * this.orderQuantity;
        const bookingCode = 'WL-' + Math.floor(100000 + Math.random() * 900000);
        
        $dispatch('show-toast', {
            msg: `🎉 Reservasi Berhasil! Kode: ${bookingCode} (${this.orderQuantity} porsi - ${this.formatRupiah(total)}). Bayar saat ambil!`
        });

        // Pengurangan stok simulasi frontend
        this.selectedFood.stock = Math.max(0, this.selectedFood.stock - this.orderQuantity);
        
        setTimeout(() => {
            this.closeDetail();
        }, 1500);
    },

    init() {
        // Auto open modal jika URL mengandung parameter ?view=food-detail atau ?detail=1
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('view') === 'food-detail' || urlParams.get('view') === 'detail' || urlParams.get('open') === 'croissant') {
            this.$nextTick(() => {
                this.openDetail(this.foods[0]);
            });
        }
    }
}" 
x-init="init()"
x-on:show-toast.window="showToast($event.detail.msg)">

    <!-- ========================================== -->
    <!-- 2. HERO SECTION                            -->
    <!-- ========================================== -->
    <section class="relative pt-8 pb-20 md:pt-14 md:pb-28 overflow-hidden bg-[#FAF6F0]">
        
        <!-- Subtle Eco Decorative Background Shapes -->
        <div class="absolute top-0 right-0 w-full lg:w-1/2 h-full pointer-events-none z-0">
            <!-- Organic yellow-cream blob -->
            <div class="absolute top-10 right-10 w-[500px] h-[500px] rounded-full bg-[#FEF3C7]/40 blur-3xl"></div>
            <!-- Organic pale green blob -->
            <div class="absolute top-40 right-48 w-[400px] h-[400px] rounded-full bg-[#EBF5F0]/80 blur-2xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                
                <!-- LEFT COLUMN: Headline, Subtext, Search Bar, Value Props -->
                <div class="lg:col-span-7 space-y-7">
                    
                    <!-- Headline (Exact match to Mockup) -->
                    <div class="space-y-1">
                        <h1 class="text-4xl sm:text-5xl lg:text-[58px] font-black text-[#1F2937] tracking-tight leading-[1.12]">
                            Good Food <br>
                            <span class="text-[#1B5E3A]">Deserves a</span> <br>
                            <span class="text-[#E67E22]">Second Chance.</span>
                        </h1>
                        <p class="pt-2 text-base sm:text-lg text-stone-600 max-w-xl leading-relaxed font-medium">
                            Sambungkan makanan lezat yang masih layak konsumsi dengan orang yang tepat. Bersama kurangi food waste, mulai dari sekarang! 💚
                        </p>
                    </div>

                    <!-- Interactive Search Bar -->
                    <div class="bg-white rounded-2xl sm:rounded-full p-2 sm:p-2.5 shadow-xl shadow-[#1B5E3A]/8 border border-stone-200/90 max-w-xl">
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                            
                            <!-- Search Input (Food, Resto, Category) -->
                            <div class="flex items-center gap-2.5 px-3 flex-1">
                                <svg class="w-5 h-5 text-stone-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input id="hero-search-input"
                                       type="text" 
                                       x-model="searchQuery"
                                       placeholder="Cari makanan, resto, atau kategori..." 
                                       class="w-full py-2 text-sm text-stone-800 placeholder:text-stone-400 bg-transparent focus:outline-none font-medium">
                            </div>

                            <!-- Vertical Divider -->
                            <div class="hidden sm:block w-[1px] h-7 bg-stone-200 shrink-0"></div>

                            <!-- Location Selector -->
                            <div class="flex items-center gap-2 px-3 sm:px-2 shrink-0 relative" x-data="{ locDropdown: false }">
                                <svg class="w-4 h-4 text-[#E67E22] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <button type="button" 
                                        @click="locDropdown = !locDropdown"
                                        class="flex items-center gap-1.5 text-xs font-bold text-stone-700 hover:text-[#1B5E3A] py-1">
                                    <span x-text="selectedLocation"></span>
                                    <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <!-- Dropdown options -->
                                <div x-cloak x-show="locDropdown" 
                                     @click.outside="locDropdown = false"
                                     class="absolute left-0 top-full mt-2 w-48 bg-white rounded-xl shadow-xl border border-stone-200 py-1.5 z-30">
                                    <template x-for="loc in locations" :key="loc">
                                        <button type="button" 
                                                @click="selectedLocation = loc; locDropdown = false"
                                                class="w-full text-left px-3.5 py-1.5 text-xs font-medium hover:bg-[#EBF5F0] hover:text-[#1B5E3A] transition"
                                                :class="selectedLocation === loc ? 'font-bold text-[#1B5E3A] bg-[#EBF5F0]/50' : 'text-stone-700'"
                                                x-text="loc">
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <!-- CTA Search Button -->
                            <a href="#makanan-populer" 
                               @click="showToast('Menampilkan makanan surplus terdekat di ' + selectedLocation)"
                               class="bg-[#1B5E3A] hover:bg-[#14472C] text-white text-xs font-bold px-6 py-3 rounded-xl sm:rounded-full text-center shadow-md transition duration-200 shrink-0">
                                Cari
                            </a>
                        </div>
                    </div>

                    <!-- 3 Value Proposition Badges (Exact from Mockup) -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-3 max-w-xl">
                        
                        <!-- Value 1: Hemat & Terjangkau -->
                        <div class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-white/60 transition">
                            <div class="w-9 h-9 rounded-xl bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center shrink-0 border border-[#DCF2E4]">
                                <!-- Discount/Tag Icon -->
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-stone-900 leading-tight">Hemat & Terjangkau</h4>
                                <p class="text-[11px] text-stone-500 mt-0.5 leading-snug">Dapatkan makanan lezat dengan harga miring.</p>
                            </div>
                        </div>

                        <!-- Value 2: Selamatkan Makanan -->
                        <div class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-white/60 transition">
                            <div class="w-9 h-9 rounded-xl bg-[#FEF3C7] text-[#E67E22] flex items-center justify-center shrink-0 border border-[#FDE68A]">
                                <!-- Heart / Food Rescue Icon -->
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-stone-900 leading-tight">Selamatkan Makanan</h4>
                                <p class="text-[11px] text-stone-500 mt-0.5 leading-snug">Bantu mengurangi food waste.</p>
                            </div>
                        </div>

                        <!-- Value 3: Dampak Nyata -->
                        <div class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-white/60 transition">
                            <div class="w-9 h-9 rounded-xl bg-[#E0F2FE] text-[#0284C7] flex items-center justify-center shrink-0 border border-[#BAE6FD]">
                                <!-- Globe / Planet Earth Icon -->
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-stone-900 leading-tight">Dampak Nyata</h4>
                                <p class="text-[11px] text-stone-500 mt-0.5 leading-snug">Bersama jaga bumi untuk masa depan.</p>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- RIGHT COLUMN: Visual Composition (Kraft Bag, Salad Bowl, Sandwich, Sushi, Croissant) -->
                <div class="lg:col-span-5 relative flex items-center justify-center">
                    
                    <!-- Main Organic Shape Background for Visual -->
                    <div class="relative w-full max-w-[460px] aspect-square">
                        
                        <!-- Organic Blob Canvas -->
                        <svg class="w-full h-full drop-shadow-xl" viewBox="0 0 500 500" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M428.5 321C466.5 244 433.5 137.5 373 78C312.5 18.5 224.5 6 150.5 44C76.5 82 16.5 170.5 20.5 258C24.5 345.5 92.5 432 177 459C261.5 486 390.5 398 428.5 321Z" fill="#FDF4DE"/>
                            <path d="M400 330C435 260 410 160 355 105C300 50 220 40 150 75C80 110 30 190 35 270C40 350 100 425 180 445C260 465 365 400 400 330Z" fill="#EBF5EE" fill-opacity="0.7"/>
                        </svg>

                        <!-- Centered Grocery Kraft Bag (Branded "What's Left") -->
                        <div class="absolute top-6 right-6 sm:right-12 w-44 sm:w-52 h-56 sm:h-64 bg-[#C89D6B] rounded-2xl shadow-2xl p-4 flex flex-col justify-between transform rotate-2 hover:rotate-0 transition-transform duration-300 border-t-4 border-[#B08655]">
                            <!-- Bag Handle cutout -->
                            <div class="w-16 h-4 mx-auto rounded-full bg-[#FAF6F0]/60 border border-[#A67C4A]"></div>
                            
                            <!-- Brand Stamp on Bag -->
                            <div class="text-center my-auto space-y-1">
                                <div class="w-10 h-10 mx-auto rounded-full bg-[#1B5E3A] flex items-center justify-center text-white shadow-inner">
                                    <svg class="w-5 h-5 text-[#F59E0B]" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="text-lg font-black text-[#1B5E3A] leading-tight">What's Left</div>
                                <div class="text-[9px] font-bold text-stone-700 tracking-wider uppercase">Good Food Great Impact</div>
                            </div>

                            <div class="h-2 w-full bg-[#B08655]/30 rounded"></div>
                        </div>

                        <!-- Salad Bowl (Top Left) -->
                        <div class="absolute top-2 left-2 sm:left-4 w-36 sm:w-44 h-36 sm:h-44 rounded-full overflow-hidden shadow-xl border-4 border-white transform -rotate-3 hover:scale-105 transition duration-300">
                            <img src="https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=400&q=80" 
                                 alt="Salad Bowl" 
                                 class="w-full h-full object-cover">
                        </div>

                        <!-- Fresh Sandwich Container with Eco Sticker (Center Left) -->
                        <div class="absolute bottom-12 left-0 sm:left-4 w-44 sm:w-52 rounded-2xl overflow-hidden shadow-2xl border-2 border-white/90 bg-white transform -rotate-6 hover:rotate-0 transition duration-300">
                            <div class="relative h-28 sm:h-32">
                                <img src="https://images.unsplash.com/photo-1528735602780-2552fd46c7af?auto=format&fit=crop&w=400&q=80" 
                                     alt="Fresh Sandwich" 
                                     class="w-full h-full object-cover">
                                <!-- Eco Badge Sticker -->
                                <div class="absolute bottom-2 left-2 px-2.5 py-1 rounded-lg bg-[#1B5E3A] text-white text-[10px] font-bold shadow-md flex items-center gap-1">
                                    <span>Save Food Save Earth 🥗</span>
                                </div>
                            </div>
                        </div>

                        <!-- Butter Croissant Plate (Top Right Floating) -->
                        <div class="absolute -top-3 right-0 sm:-right-4 w-24 sm:w-28 h-24 sm:h-28 rounded-2xl overflow-hidden shadow-lg border-2 border-white transform rotate-12 hover:scale-105 transition">
                            <img src="https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=300&q=80" 
                                 alt="Croissant" 
                                 class="w-full h-full object-cover">
                        </div>

                        <!-- Sushi Bento Tray (Bottom Right) -->
                        <div class="absolute -bottom-4 right-2 sm:right-6 w-40 sm:w-48 h-28 sm:h-32 rounded-xl overflow-hidden shadow-2xl border-2 border-white transform rotate-3 hover:scale-105 transition">
                            <img src="https://images.unsplash.com/photo-1579871494447-9811cf80d66c?auto=format&fit=crop&w=400&q=80" 
                                 alt="Sushi Bento" 
                                 class="w-full h-full object-cover">
                        </div>

                        <!-- Floating Pill Badge ("Yummy Food Happy Planet!") -->
                        <div class="absolute bottom-24 right-0 sm:-right-2 bg-white/95 backdrop-blur-md px-3.5 py-1.5 rounded-full shadow-lg border border-[#DCF2E4] flex items-center gap-2 transform translate-y-2 hover:scale-105 transition">
                            <span class="text-sm">🌱</span>
                            <span class="text-xs font-extrabold text-[#1B5E3A]">Yummy Food Happy Planet!</span>
                        </div>

                    </div>

                </div>

            </div>
        </div>

        <!-- Wave transition at bottom of hero -->
        <div class="absolute bottom-0 left-0 right-0 w-full overflow-hidden leading-none z-10">
            <svg class="relative block w-full h-8 sm:h-12 text-[#FDFBF7]" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M0,0 C150,90 350,-40 500,40 C650,120 900,20 1200,60 L1200,120 L0,120 Z" fill="currentColor"></path>
            </svg>
        </div>

    </section>


    <!-- ========================================== -->
    <!-- 3. SECTION MAKANAN SURPLUS POPULER         -->
    <!-- ========================================== -->
    <section id="makanan-populer" class="py-12 sm:py-16 bg-[#FDFBF7] relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Header: Title & "Lihat semua" link -->
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
                
                <div class="text-center sm:text-left">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#EBF5F0] text-[#1B5E3A] text-xs font-bold uppercase tracking-wider mb-2">
                        <span>🔥 Kesempatan Terbatas Hari Ini</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#1F2937] tracking-tight">
                        Makanan Surplus <span class="text-[#1B5E3A]">Populer</span>
                    </h2>
                    <p class="text-sm text-stone-500 mt-1 font-medium">
                        Makanan lezat dari berbagai bisnis di sekitarmu!
                    </p>
                </div>

                <div class="flex items-center justify-between sm:justify-end gap-4">
                    <!-- Carousel Prev/Next Buttons (As seen on mockup edges) -->
                    <div class="flex items-center gap-2">
                        <button type="button" 
                                @click="scrollCarousel('prev')"
                                class="w-9 h-9 rounded-full bg-white border border-stone-200 shadow-sm flex items-center justify-center text-stone-600 hover:text-[#1B5E3A] hover:border-[#1B5E3A] active:scale-95 transition"
                                title="Sebelumnya">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button type="button" 
                                @click="scrollCarousel('next')"
                                class="w-9 h-9 rounded-full bg-white border border-stone-200 shadow-sm flex items-center justify-center text-stone-600 hover:text-[#1B5E3A] hover:border-[#1B5E3A] active:scale-95 transition"
                                title="Berikutnya">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>

                    <!-- "Lihat semua ->" Link (Exact from Mockup) -->
                    <a href="{{ Route::has('register') ? route('register') : url('/?view=register') }}" 
                       class="inline-flex items-center gap-1.5 text-sm font-bold text-[#1B5E3A] hover:text-[#14472C] group transition">
                        <span>Lihat semua</span>
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

            </div>

            <!-- Carousel / Grid Container (5 Cards matching mockup) -->
            <div x-ref="foodCarousel" 
                 class="flex gap-5 overflow-x-auto pb-6 pt-2 no-scrollbar scroll-smooth snap-x snap-mandatory">
                
                <template x-for="food in foods" :key="food.id">
                    <div class="w-64 sm:w-72 shrink-0 snap-start bg-white rounded-2xl overflow-hidden border border-stone-200/90 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
                        
                        <!-- Top Image & Badges Container -->
                        <div class="relative aspect-[4/3] overflow-hidden bg-stone-100">
                            <!-- Food Photo -->
                            <img :src="food.image" 
                                 :alt="food.title"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            
                            <!-- Discount Badge (Top Left, Warm Amber/Orange tag) -->
                            <div class="absolute top-3 left-3 px-2.5 py-1 rounded-lg bg-[#F59E0B] text-white text-xs font-black shadow-md tracking-wider">
                                <span x-text="food.discount + '% OFF'"></span>
                            </div>

                            <!-- Wishlist Heart Button (Top Right) -->
                            <button type="button" 
                                    @click="toggleWishlist(food)"
                                    class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center text-stone-600 hover:text-red-500 shadow-sm transition-transform active:scale-90"
                                    :title="food.isWishlist ? 'Hapus dari favorit' : 'Simpan favorit'">
                                <svg class="w-4 h-4" :class="food.isWishlist ? 'text-red-500 fill-red-500' : 'text-stone-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>

                            <!-- Sisa Stok Pill Indicator -->
                            <div class="absolute bottom-2.5 left-2.5 px-2 py-0.5 rounded-md bg-black/60 backdrop-blur-sm text-white text-[10px] font-semibold flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                <span x-text="'Sisa ' + food.stock + ' porsi'"></span>
                            </div>
                        </div>

                        <!-- Card Body Content -->
                        <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                            <div>
                                <!-- Merchant Name with Verified Badge -->
                                <div class="flex items-center gap-1 text-xs text-stone-500 font-medium mb-1">
                                    <span x-text="food.merchant"></span>
                                    <template x-if="food.merchantVerified">
                                        <svg class="w-3.5 h-3.5 text-[#1B5E3A]" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </template>
                                </div>

                                <!-- Item Title -->
                                <h3 class="text-base font-bold text-stone-900 group-hover:text-[#1B5E3A] transition line-clamp-1" 
                                    x-text="food.title"></h3>

                                <!-- Price (Discount Price in Bold Orange + Original Strikethrough) -->
                                <div class="flex items-baseline gap-2 mt-1.5">
                                    <span class="text-base font-extrabold text-[#E67E22]" x-text="formatRupiah(food.discountPrice)"></span>
                                    <span class="text-xs line-through text-stone-400 font-medium" x-text="formatRupiah(food.originalPrice)"></span>
                                </div>
                            </div>

                            <!-- Bottom Meta: Pickup Time Slot & Distance -->
                            <div class="pt-2.5 border-t border-stone-100 flex items-center justify-between text-[11px] text-stone-500">
                                <!-- Clock icon with pickup time -->
                                <div class="flex items-center gap-1 font-medium">
                                    <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span x-text="'Ambil hari ini ' + food.pickupTime"></span>
                                </div>

                                <!-- Distance pin -->
                                <div class="flex items-center gap-0.5 text-stone-600 font-semibold">
                                    <svg class="w-3 h-3 text-[#1B5E3A]" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                    </svg>
                                    <span x-text="food.distance"></span>
                                </div>
                            </div>

                            <!-- CTA Quick Detail Button -->
                            <button type="button" 
                                    @click="openDetail(food)"
                                    class="w-full mt-2 py-2 rounded-xl bg-[#EBF5F0] hover:bg-[#1B5E3A] text-[#1B5E3A] hover:text-white text-xs font-bold transition duration-200 text-center">
                                Lihat Detail & Pesan
                            </button>

                        </div>

                    </div>
                </template>

            </div>

        </div>
    </section>


    <!-- ========================================== -->
    <!-- 4. SECTION BAGAIMANA CARA KERJANYA?        -->
    <!-- ========================================== -->
    <section id="cara-kerja" class="py-16 sm:py-20 bg-[#FAF6F0]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Green Tinted Rounded Container (As depicted in Mockup) -->
            <div class="bg-[#EAF5EF] rounded-3xl p-6 sm:p-10 lg:p-14 border border-[#DCF2E4] shadow-sm relative overflow-hidden">
                
                <!-- Section Title Header -->
                <div class="text-center max-w-xl mx-auto mb-12 sm:mb-16">
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#1F2937]">
                        Bagaimana <span class="text-[#1B5E3A] relative inline-block">Cara Kerjanya?
                            <svg class="absolute -bottom-2 left-0 w-full h-2 text-[#F59E0B]" viewBox="0 0 100 10" preserveAspectRatio="none">
                                <path d="M0 5 Q 50 10 100 5" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round"/>
                            </svg>
                        </span>
                    </h2>
                    <p class="text-xs sm:text-sm text-stone-600 mt-3 font-medium">
                        Alur praktis menyelamatkan makanan surplus berkualitas dalam 4 langkah mudah
                    </p>
                </div>

                <!-- 4 Steps Flow Grid with Connectors -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 relative z-10">
                    
                    <!-- STEP 1: Bisnis Unggah Makanan Surplus -->
                    <div class="flex flex-col items-center text-center space-y-4 group">
                        <!-- Step Badge -->
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-full bg-[#1B5E3A] text-white font-extrabold text-xs flex items-center justify-center shadow-md">1</span>
                            <h3 class="text-sm font-extrabold text-stone-900">Bisnis Unggah Makanan Surplus</h3>
                        </div>

                        <p class="text-xs text-stone-600 leading-relaxed max-w-[220px]">
                            Bisnis mengunggah makanan yang masih layak konsumsi namun berpotensi tidak terjual.
                        </p>

                        <!-- Step 1 Illustration: Storefront Vector Art -->
                        <div class="w-36 h-36 rounded-2xl bg-white p-3 shadow-md flex items-center justify-center border border-stone-200/80 group-hover:scale-105 transition transform">
                            <svg class="w-24 h-24" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <!-- Shop Building -->
                                <rect x="25" y="45" width="70" height="55" rx="4" fill="#FAF6F0" stroke="#1F2937" stroke-width="2.5"/>
                                <!-- Striped Awning -->
                                <path d="M20 45 L100 45 L95 28 L25 28 Z" fill="#1B5E3A"/>
                                <path d="M35 28 L32 45 M50 28 L50 45 M65 28 L68 45 M80 28 L85 45" stroke="#FFFFFF" stroke-width="2.5"/>
                                <path d="M20 45 Q 27 52 35 45 Q 42 52 50 45 Q 57 52 65 45 Q 72 52 80 45 Q 87 52 95 45 Q 100 50 100 45" fill="#1B5E3A"/>
                                <!-- Door & Window -->
                                <rect x="35" y="60" width="22" height="40" rx="2" fill="#E67E22" stroke="#1F2937" stroke-width="2"/>
                                <circle cx="51" cy="80" r="1.5" fill="#FFFFFF"/>
                                <rect x="67" y="60" width="20" height="22" rx="2" fill="#E0F2FE" stroke="#1F2937" stroke-width="2"/>
                                <!-- Mini Potted Plant -->
                                <path d="M96 90 L102 90 L100 100 L98 100 Z" fill="#C89D6B"/>
                                <circle cx="99" cy="86" r="4" fill="#1B5E3A"/>
                            </svg>
                        </div>
                    </div>

                    <!-- STEP 2: Konsumen Pesan & Reservasi -->
                    <div class="flex flex-col items-center text-center space-y-4 group">
                        <!-- Step Badge -->
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-full bg-[#1B5E3A] text-white font-extrabold text-xs flex items-center justify-center shadow-md">2</span>
                            <h3 class="text-sm font-extrabold text-stone-900">Konsumen Pesan & Reservasi</h3>
                        </div>

                        <p class="text-xs text-stone-600 leading-relaxed max-w-[220px]">
                            Pilih makanan favorit dan lakukan reservasi melalui aplikasi dengan mudah.
                        </p>

                        <!-- Step 2 Illustration: Smartphone App Interface -->
                        <div class="w-36 h-36 rounded-2xl bg-white p-3 shadow-md flex items-center justify-center border border-stone-200/80 group-hover:scale-105 transition transform relative">
                            <svg class="w-24 h-24" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <!-- Smartphone Body -->
                                <rect x="38" y="16" width="44" height="88" rx="8" fill="#FFFFFF" stroke="#1F2937" stroke-width="2.5"/>
                                <!-- Screen -->
                                <rect x="42" y="24" width="36" height="72" rx="4" fill="#FDFBF7"/>
                                <!-- Speaker notch -->
                                <rect x="54" y="20" width="12" height="2" rx="1" fill="#1F2937"/>
                                <!-- App Header -->
                                <rect x="44" y="28" width="32" height="8" rx="2" fill="#1B5E3A"/>
                                <!-- Card 1 -->
                                <rect x="44" y="40" width="32" height="14" rx="2" fill="#FFFFFF" stroke="#E5E7EB" stroke-width="1"/>
                                <circle cx="49" cy="47" r="3" fill="#E67E22"/>
                                <rect x="54" y="44" width="18" height="3" rx="1" fill="#1F2937"/>
                                <rect x="54" y="49" width="12" height="2" rx="1" fill="#9CA3AF"/>
                                <!-- Card 2 -->
                                <rect x="44" y="58" width="32" height="14" rx="2" fill="#FFFFFF" stroke="#E5E7EB" stroke-width="1"/>
                                <circle cx="49" cy="65" r="3" fill="#10B981"/>
                                <rect x="54" y="62" width="18" height="3" rx="1" fill="#1F2937"/>
                                <!-- Checkout Button -->
                                <rect x="46" y="78" width="28" height="6" rx="3" fill="#E67E22"/>
                            </svg>
                            <!-- Floating mini basket -->
                            <div class="absolute bottom-2 right-2 w-7 h-7 rounded-lg bg-[#F59E0B] text-white flex items-center justify-center shadow">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 3: Ambil Makanan Sesuai Waktu -->
                    <div class="flex flex-col items-center text-center space-y-4 group">
                        <!-- Step Badge -->
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-full bg-[#1B5E3A] text-white font-extrabold text-xs flex items-center justify-center shadow-md">3</span>
                            <h3 class="text-sm font-extrabold text-stone-900">Ambil Makanan Sesuai Waktu</h3>
                        </div>

                        <p class="text-xs text-stone-600 leading-relaxed max-w-[220px]">
                            Ambil makanan di waktu yang telah ditentukan dan nikmati hidangan lezat!
                        </p>

                        <!-- Step 3 Illustration: Paper Bag Handoff with Eco Heart -->
                        <div class="w-36 h-36 rounded-2xl bg-white p-3 shadow-md flex items-center justify-center border border-stone-200/80 group-hover:scale-105 transition transform">
                            <svg class="w-24 h-24" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <!-- Hand holding bag -->
                                <path d="M15 45 C25 45 35 48 45 42 L60 38" stroke="#D19C75" stroke-width="5" stroke-linecap="round"/>
                                <!-- Paper Bag -->
                                <path d="M50 40 L85 40 L80 95 L55 95 Z" fill="#C89D6B" stroke="#1F2937" stroke-width="2"/>
                                <!-- Handle -->
                                <path d="M60 40 C60 30 75 30 75 40" stroke="#B08655" stroke-width="2.5" fill="none"/>
                                <!-- Green Heart Stamp on Bag -->
                                <circle cx="67" cy="65" r="9" fill="#1B5E3A"/>
                                <path d="M64 63 C64 61 67 61 67 63 C67 61 70 61 70 63 C70 66 67 68 67 68 C67 68 64 66 64 63 Z" fill="#F59E0B"/>
                                <!-- Sparkles -->
                                <path d="M88 50 L91 55 L96 58 L91 61 L88 66 L85 61 L80 58 L85 55 Z" fill="#F59E0B"/>
                            </svg>
                        </div>
                    </div>

                    <!-- STEP 4: Bersama Kurangi Food Waste -->
                    <div class="flex flex-col items-center text-center space-y-4 group">
                        <!-- Step Badge -->
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-full bg-[#1B5E3A] text-white font-extrabold text-xs flex items-center justify-center shadow-md">4</span>
                            <h3 class="text-sm font-extrabold text-stone-900">Bersama Kurangi Food Waste</h3>
                        </div>

                        <p class="text-xs text-stone-600 leading-relaxed max-w-[220px]">
                            Setiap tindakan kecil kita berdampak besar untuk bumi yang lebih baik.
                        </p>

                        <!-- Step 4 Illustration: Smiling Happy Earth Planet -->
                        <div class="w-36 h-36 rounded-2xl bg-white p-3 shadow-md flex items-center justify-center border border-stone-200/80 group-hover:scale-105 transition transform">
                            <svg class="w-24 h-24" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <!-- Planet Sphere -->
                                <circle cx="60" cy="60" r="35" fill="#93C5FD" stroke="#1F2937" stroke-width="2.5"/>
                                <!-- Green Continents -->
                                <path d="M42 42 C48 38 52 46 62 40 C70 48 65 55 58 52 C50 60 40 52 42 42 Z" fill="#86EFAC"/>
                                <path d="M50 72 C58 68 65 72 74 65 C82 72 78 84 68 85 C58 84 48 78 50 72 Z" fill="#86EFAC"/>
                                <path d="M75 45 C80 43 85 47 82 52 C78 55 74 52 75 45 Z" fill="#86EFAC"/>
                                <!-- Smiling Face on Planet -->
                                <circle cx="53" cy="58" r="2.5" fill="#1F2937"/>
                                <circle cx="67" cy="58" r="2.5" fill="#1F2937"/>
                                <path d="M56 64 Q 60 68 64 64" stroke="#1F2937" stroke-width="2" stroke-linecap="round" fill="none"/>
                                <!-- Cute Rosy Cheeks -->
                                <circle cx="49" cy="61" r="2" fill="#FCA5A5"/>
                                <circle cx="71" cy="61" r="2" fill="#FCA5A5"/>
                                <!-- Surrounding Floating Leaves & Stars -->
                                <circle cx="22" cy="40" r="2" fill="#F59E0B"/>
                                <circle cx="98" cy="35" r="2.5" fill="#F59E0B"/>
                                <circle cx="95" cy="85" r="2" fill="#1B5E3A"/>
                                <circle cx="25" cy="80" r="2.5" fill="#1B5E3A"/>
                            </svg>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </section>


    <!-- ========================================== -->
    <!-- 5. CALL TO ACTION BANNER (DUAL PORTAL)     -->
    <!-- ========================================== -->
    <section class="py-12 bg-[#FAF6F0]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl bg-gradient-to-r from-[#1B5E3A] to-[#14472C] text-white p-8 sm:p-12 shadow-2xl relative overflow-hidden">
                
                <!-- Background decorative leaves -->
                <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none">
                    <svg class="w-80 h-80 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L2.414 11l3.293 3.293a1 1 0 11-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L17.586 11l-3.293-3.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </div>

                <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    <div class="lg:col-span-8 space-y-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 text-[#FEF3C7] text-xs font-bold uppercase tracking-wider">
                            ✨ Bergabunglah Bersama Gerakan Kami
                        </span>
                        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight">
                            Punya Bisnis Kuliner atau Ingin Menyelamatkan Makanan?
                        </h2>
                        <p class="text-sm text-stone-200 max-w-xl leading-relaxed">
                            Mulai selamatkan hidangan terbaik Anda, tingkatkan omzet dari makanan berlebih, atau nikmati berbagai hidangan lezat dengan harga terjangkau setiap hari.
                        </p>
                    </div>

                    <div class="lg:col-span-4 flex flex-col sm:flex-row lg:flex-col gap-3 justify-end">
                        <a href="{{ Route::has('register') ? route('register') : url('/?view=register') }}" 
                           class="w-full text-center py-3.5 px-6 rounded-xl bg-[#F59E0B] hover:bg-[#E67E22] text-[#1F2937] hover:text-white font-extrabold text-sm shadow-lg transition duration-200">
                            Daftar Sekarang (Gratis)
                        </a>
                        <a href="{{ Route::has('login') ? route('login') : url('/?view=login') }}" 
                           class="w-full text-center py-3.5 px-6 rounded-xl bg-white/15 hover:bg-white/25 text-white font-bold text-sm border border-white/20 transition duration-200">
                            Masuk ke Akun Anda
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- ========================================== -->
    <!-- 6. INTERACTIVE FOOD DETAIL POP-UP MODAL    -->
    <!-- Sesuai Visual Rujukan Consumer Mockup      -->
    <!-- ========================================== -->
    @include('components.consumer.food-detail-modal')

</div>
@endsection
