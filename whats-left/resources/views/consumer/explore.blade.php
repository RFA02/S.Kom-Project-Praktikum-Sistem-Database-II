@extends('layouts.main')

@section('title', "Jelajahi Makanan Surplus - What's Left")

{{-- Custom Consumer Authenticated Header matching mockup --}}
@section('header')
<header class="sticky top-0 z-40 w-full bg-white/95 backdrop-blur-md border-b border-stone-200/80 transition-all duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
        
        <!-- Brand Logo -->
        <a href="{{ url('/?view=landing') }}" class="inline-flex items-center gap-2.5 group">
            <div class="w-10 h-10 rounded-xl bg-[#1B5E3A] flex items-center justify-center text-white shadow-sm shadow-[#1B5E3A]/25 transition-transform group-hover:scale-105">
                <!-- Daun / Sprout Icon -->
                <svg class="w-5 h-5 text-[#F59E0B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div class="flex flex-col">
                <div class="flex items-center text-2xl font-black tracking-tight leading-none">
                    <span class="text-[#1B5E3A]">What's</span>
                    <span class="text-[#E67E22] ml-1">Left</span>
                </div>
                <span class="text-[9px] font-bold tracking-wider uppercase text-[#6B7280] mt-0.5">Food Rescue Indonesia</span>
            </div>
        </a>

        <!-- Desktop Navigation Links -->
        <nav class="hidden lg:flex items-center gap-8">
            <a href="{{ url('/?view=landing') }}" 
               class="py-2 text-sm font-semibold text-[#1F2937] hover:text-[#1B5E3A] transition-colors">
                Beranda
            </a>
            
            <!-- Jelajahi (Active Indicator: Hijau + Underline Hijau Persis Mockup) -->
            <a href="{{ url('/?view=explore') }}" 
               class="relative py-2 text-sm font-bold text-[#1B5E3A] transition-colors">
                Jelajahi
                <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-[#1B5E3A] rounded-full"></span>
            </a>

            <a href="{{ url('/?view=landing#cara-kerja') }}" 
               class="py-2 text-sm font-semibold text-[#1F2937] hover:text-[#1B5E3A] transition-colors">
                Cara Kerja
            </a>

            <a href="{{ url('/?view=dashboard') }}" 
               class="py-2 text-sm font-semibold text-[#1F2937] hover:text-[#1B5E3A] transition-colors">
                Untuk Bisnis
            </a>

            <a href="{{ url('/?view=landing#tentang-kami') }}" 
               class="py-2 text-sm font-semibold text-[#1F2937] hover:text-[#1B5E3A] transition-colors">
                Tentang Kami
            </a>
        </nav>

        <!-- Right Controls: Pemilih Lokasi, Notifikasi, Profil Pengguna (Persis Mockup) -->
        <div class="flex items-center gap-3 sm:gap-4">
            
            <!-- 1. Pemilih Lokasi (Pill Dropdown) -->
            <div class="relative" x-data="{ locationOpen: false }">
                <button type="button" 
                        @click="locationOpen = !locationOpen" 
                        @click.outside="locationOpen = false"
                        class="flex items-center gap-2 px-3.5 py-2 rounded-full bg-stone-50 hover:bg-stone-100/90 text-stone-700 text-xs sm:text-sm font-semibold border border-stone-200/90 transition-all duration-200">
                    <svg class="w-4 h-4 text-[#1B5E3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span x-text="currentCity">Jakarta, Indonesia</span>
                    <svg class="w-3.5 h-3.5 text-stone-400 transition-transform duration-200" :class="locationOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <!-- Dropdown Pilihan Kota -->
                <div x-cloak x-show="locationOpen" 
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-1"
                     class="absolute right-0 mt-2 w-56 rounded-2xl bg-white shadow-xl border border-stone-200/80 py-2 z-50">
                    <div class="px-3.5 py-1.5 text-[11px] font-bold text-stone-400 uppercase tracking-wider">Pilih Wilayah</div>
                    <template x-for="city in ['Jakarta, Indonesia', 'Bandung, Indonesia', 'Surabaya, Indonesia', 'Yogyakarta, Indonesia', 'Tangerang Selatan, Banten']" :key="city">
                        <button type="button" 
                                @click="currentCity = city; locationOpen = false; showToast('Lokasi diubah ke ' + city)"
                                class="w-full text-left px-3.5 py-2 text-xs font-medium text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A] flex items-center justify-between transition-colors">
                            <span x-text="city"></span>
                            <span x-show="currentCity === city" class="text-[#1B5E3A] font-bold">✓</span>
                        </button>
                    </template>
                </div>
            </div>

            <!-- 2. Ikon Lonceng Notifikasi dengan Badge "3" (Persis Mockup) -->
            <div class="relative" x-data="{ notifOpen: false }">
                <button type="button" 
                        @click="notifOpen = !notifOpen" 
                        @click.outside="notifOpen = false"
                        class="relative w-10 h-10 rounded-full flex items-center justify-center text-stone-600 hover:text-[#1B5E3A] hover:bg-stone-100/80 transition-colors"
                        title="Notifikasi">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <!-- Badge Merah/Oranye "3" -->
                    <span class="absolute top-1.5 right-1.5 w-4 h-4 rounded-full bg-[#E67E22] text-white text-[10px] font-black flex items-center justify-center ring-2 ring-white animate-pulse">
                        3
                    </span>
                </button>

                <!-- Notifikasi Dropdown Popover -->
                <div x-cloak x-show="notifOpen" 
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-100"
                     class="absolute right-0 mt-2 w-80 sm:w-96 rounded-2xl bg-white shadow-2xl border border-stone-200/80 p-3 z-50">
                    <div class="flex items-center justify-between pb-2 mb-2 border-b border-stone-100 px-1">
                        <span class="text-xs font-bold text-stone-900">Notifikasi Masuk (3 Baru)</span>
                        <a href="{{ url('/?view=notifications') }}" class="text-[11px] font-semibold text-[#1B5E3A] hover:underline">Lihat Semua</a>
                    </div>
                    <div class="space-y-2 max-h-72 overflow-y-auto custom-scrollbar">
                        <div class="p-2.5 rounded-xl bg-[#EBF5F0]/60 border border-[#DCF2E4] flex items-start gap-2.5">
                            <span class="w-2 h-2 rounded-full bg-[#1B5E3A] mt-1.5 shrink-0"></span>
                            <div class="text-xs">
                                <p class="font-bold text-stone-900">Reservasi #WL24520X Siap Diambil!</p>
                                <p class="text-stone-600 text-[11px] mt-0.5">Croissant Butter di Sunny Bakery dapat diambil sekarang hingga pukul 20.00.</p>
                            </div>
                        </div>
                        <div class="p-2.5 rounded-xl hover:bg-stone-50 transition flex items-start gap-2.5">
                            <span class="w-2 h-2 rounded-full bg-[#E67E22] mt-1.5 shrink-0"></span>
                            <div class="text-xs">
                                <p class="font-bold text-stone-900">Flash Sale Surplus 35% OFF</p>
                                <p class="text-stone-600 text-[11px] mt-0.5">Sweet Moments baru saja menambahkan 6 slice Red Velvet dengan harga Rp 13.000!</p>
                            </div>
                        </div>
                        <div class="p-2.5 rounded-xl hover:bg-stone-50 transition flex items-start gap-2.5">
                            <span class="w-2 h-2 rounded-full bg-stone-300 mt-1.5 shrink-0"></span>
                            <div class="text-xs">
                                <p class="font-bold text-stone-900">Dampak Penyelamatan Makanan</p>
                                <p class="text-stone-600 text-[11px] mt-0.5">Kamu telah menyelamatkan 12 porsi makanan surplus bulan ini. Hebat!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Profil Pengguna ("Hai, Jasmine" dengan Avatar Foto Persis Mockup) -->
            <div class="relative" x-data="{ userMenuOpen: false }">
                <button type="button" 
                        @click="userMenuOpen = !userMenuOpen" 
                        @click.outside="userMenuOpen = false"
                        class="flex items-center gap-2 pl-1 pr-2 py-1 rounded-full hover:bg-stone-100 transition-colors">
                    <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=120&q=80" 
                         alt="Jasmine" 
                         class="w-9 h-9 rounded-full object-cover ring-2 ring-[#1B5E3A]/20">
                    <span class="hidden md:inline-block text-xs sm:text-sm font-bold text-stone-800">Hai, Jasmine</span>
                    <svg class="w-3.5 h-3.5 text-stone-400 transition-transform duration-200" :class="userMenuOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <!-- Dropdown Profil Pengguna -->
                <div x-cloak x-show="userMenuOpen" 
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-100"
                     class="absolute right-0 mt-2 w-52 rounded-2xl bg-white shadow-2xl border border-stone-200/80 py-2 z-50">
                    <div class="px-4 py-2 border-b border-stone-100">
                        <p class="text-xs font-bold text-stone-900">Jasmine Salsabilla</p>
                        <p class="text-[11px] text-stone-500">jasmine@foodrescue.id</p>
                    </div>
                    <a href="{{ url('/?view=profile') }}" class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A]">
                        <svg class="w-4 h-4 text-[#1B5E3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Profil Saya
                    </a>
                    <a href="{{ url('/?view=my-reservations') }}" class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A]">
                        <svg class="w-4 h-4 text-[#1B5E3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Reservasi Saya
                    </a>
                    <a href="{{ url('/?view=notifications') }}" class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A]">
                        <svg class="w-4 h-4 text-[#1B5E3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        Notifikasi
                    </a>
                    <a href="#" @click.prevent="showToast('Fitur Favorit sedang aktif!')" class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A]">
                        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                        Daftar Keinginan (Wishlist)
                    </a>
                    <a href="{{ url('/?view=dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A]">
                        <svg class="w-4 h-4 text-[#E67E22]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Beralih ke Portal Bisnis
                    </a>
                    <div class="border-t border-stone-100 my-1"></div>
                    <a href="{{ url('/?view=landing') }}" class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-red-600 hover:bg-red-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Keluar
                    </a>
                </div>
            </div>

            <!-- Mobile Drawer Toggle -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" 
                    type="button" 
                    class="p-2 rounded-xl text-stone-600 hover:bg-stone-100 lg:hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div x-cloak x-show="mobileMenuOpen" 
         class="lg:hidden border-b border-stone-200 bg-white px-4 pt-3 pb-6 space-y-3">
        <a href="{{ url('/?view=landing') }}" class="block py-2 text-base font-semibold text-stone-700">Beranda</a>
        <a href="{{ url('/?view=explore') }}" class="block py-2 text-base font-bold text-[#1B5E3A]">Jelajahi Makanan Surplus</a>
        <a href="{{ url('/?view=my-reservations') }}" class="block py-2 text-base font-semibold text-stone-700">Reservasi Saya</a>
        <a href="{{ url('/?view=dashboard') }}" class="block py-2 text-base font-semibold text-stone-700">Portal Bisnis</a>
        <a href="{{ url('/?view=landing#cara-kerja') }}" class="block py-2 text-base font-semibold text-stone-700">Cara Kerja</a>
        <a href="{{ url('/?view=landing#tentang-kami') }}" class="block py-2 text-base font-semibold text-stone-700">Tentang Kami</a>
    </div>
</header>
@endsection

@section('content')
<div x-data="{
    // Mock Data Makanan Sesuai Persis dengan Screenshot Lampiran (10 Card Baris 1 & 2 + Tambahan untuk Paginasi 24 Hasil)
    foods: [
        {
            id: 1,
            title: 'Croissant Butter',
            merchant: 'Sunny Bakery',
            merchantVerified: true,
            merchantAvatar: 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=120&q=80',
            rating: 4.8,
            reviewsCount: 120,
            distance: 1.2,
            distanceText: '1.2 km',
            category: 'Roti & Kue',
            categorySpec: 'Roti & Kue',
            discount: 30,
            originalPrice: 15000,
            discountPrice: 10500,
            pickupTime: 'Ambil hari ini 18.00 - 20.00',
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
            description: 'Croissant butter premium yang renyah keemasan di luar dan lembut berlapis di dalam. Cocok untuk sarapan atau kudapan sore.'
        },
        {
            id: 2,
            title: 'Chicken Teriyaki Bowl',
            merchant: 'RiceBowl Kitchen',
            merchantVerified: true,
            merchantAvatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=120&q=80',
            rating: 4.9,
            reviewsCount: 95,
            distance: 0.8,
            distanceText: '0.8 km',
            category: 'Makanan Berat',
            categorySpec: 'Makanan Berat',
            discount: 25,
            originalPrice: 24000,
            discountPrice: 18000,
            pickupTime: 'Ambil hari ini 17.00 - 19.00',
            pickupLocation: 'Jl. Senopati No. 45, Jakarta Selatan',
            stock: 6,
            unit: 'porsi',
            freshness: 'Dimasak hangat hari ini pukul 11.30',
            image: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&q=80',
            gallery: [
                'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&q=80',
                'https://images.unsplash.com/photo-1512058564366-18510be2db19?auto=format&fit=crop&w=600&q=80'
            ],
            isWishlist: false,
            description: 'Nasi hangat dengan potongan ayam panggang empuk berbalur saus teriyaki gurih manis khas Jepang dan sayuran segar.'
        },
        {
            id: 3,
            title: 'Spaghetti Bolognese',
            merchant: 'Pasta La Vista',
            merchantVerified: true,
            merchantAvatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=120&q=80',
            rating: 4.7,
            reviewsCount: 78,
            distance: 1.5,
            distanceText: '1.5 km',
            category: 'Makanan Berat',
            categorySpec: 'Makanan Berat',
            discount: 20,
            originalPrice: 25000,
            discountPrice: 20000,
            pickupTime: 'Ambil hari ini 19.00 - 21.00',
            pickupLocation: 'Jl. Gandaria I No. 8, Jakarta Selatan',
            stock: 8,
            unit: 'porsi',
            freshness: 'Saus dimasak perlahan hari ini pukul 10.00',
            image: 'https://images.unsplash.com/photo-1621996346565-e3d5d6281292?auto=format&fit=crop&w=800&q=80',
            gallery: [
                'https://images.unsplash.com/photo-1621996346565-e3d5d6281292?auto=format&fit=crop&w=800&q=80'
            ],
            isWishlist: false,
            description: 'Spaghetti al dente dengan siraman saus daging sapi cincang melimpah, tomat segar, dan taburan oregano aromatik.'
        },
        {
            id: 4,
            title: 'Red Velvet Slice',
            merchant: 'Sweet Moments',
            merchantVerified: true,
            merchantAvatar: 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=120&q=80',
            rating: 4.9,
            reviewsCount: 142,
            distance: 2.1,
            distanceText: '2.1 km',
            category: 'Dessert',
            categorySpec: 'Dessert',
            discount: 35,
            originalPrice: 20000,
            discountPrice: 13000,
            pickupTime: 'Ambil hari ini 18.00 - 20.00',
            pickupLocation: 'Jl. Panglima Polim No. 22, Jakarta',
            stock: 6,
            unit: 'slice',
            freshness: 'Disimpan dalam showcase dingin hari ini',
            image: 'https://images.unsplash.com/photo-1586788680434-30d324b2d46f?auto=format&fit=crop&w=800&q=80',
            gallery: [
                'https://images.unsplash.com/photo-1586788680434-30d324b2d46f?auto=format&fit=crop&w=800&q=80'
            ],
            isWishlist: false,
            description: 'Kue red velvet lembut berpadu sempurna dengan lapisan cream cheese frosting segar dan topping buah ceri manis.'
        },
        {
            id: 5,
            title: 'Salmon Sushi Set',
            merchant: 'Sushi Daily',
            merchantVerified: true,
            merchantAvatar: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=120&q=80',
            rating: 4.8,
            reviewsCount: 110,
            distance: 1.0,
            distanceText: '1.0 km',
            category: 'Makanan Berat',
            categorySpec: 'Makanan Berat',
            discount: 30,
            originalPrice: 31000,
            discountPrice: 22000,
            pickupTime: 'Ambil hari ini 17.00 - 19.00',
            pickupLocation: 'Grand Indonesia Mall Lt. LG, Jakarta',
            stock: 4,
            unit: 'set',
            freshness: 'Dibuat fresh oleh sushi chef hari ini',
            image: 'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?auto=format&fit=crop&w=800&q=80',
            gallery: [
                'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?auto=format&fit=crop&w=800&q=80'
            ],
            isWishlist: false,
            description: 'Set aneka nigiri dan maki sushi dengan salmon Norwegia segar, nasi sushi pulen asam gurih, dan wasabi otentik.'
        },
        {
            id: 6,
            title: 'Tuna Mayo Sandwich',
            merchant: 'Sandwich Hub',
            merchantVerified: true,
            merchantAvatar: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=120&q=80',
            rating: 4.6,
            reviewsCount: 64,
            distance: 1.4,
            distanceText: '1.4 km',
            category: 'Snack',
            categorySpec: 'Snack',
            discount: 28,
            originalPrice: 12500,
            discountPrice: 9000,
            pickupTime: 'Ambil hari ini 18.00 - 20.00',
            pickupLocation: 'Jl. Tebet Raya No. 19, Jakarta Selatan',
            stock: 7,
            unit: 'porsi',
            freshness: 'Roti gandum dipanggang hari ini',
            image: 'https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=800&q=80',
            gallery: [
                'https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=800&q=80'
            ],
            isWishlist: false,
            description: 'Sandwich roti gandum isi suwiran daging tuna gurih saus mayo, selada segar krispi, dan tomat iris manis.'
        },
        {
            id: 7,
            title: 'Es Kopi Susu Gula Aren',
            merchant: 'Kopi Kita',
            merchantVerified: true,
            merchantAvatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=120&q=80',
            rating: 4.9,
            reviewsCount: 230,
            distance: 0.6,
            distanceText: '0.6 km',
            category: 'Minuman',
            categorySpec: 'Minuman',
            discount: 20,
            originalPrice: 10000,
            discountPrice: 8000,
            pickupTime: 'Ambil hari ini 17.00 - 19.00',
            pickupLocation: 'Jl. Cipete Raya No. 5, Jakarta Selatan',
            stock: 15,
            unit: 'cup',
            freshness: 'Ekstraksi kopi fresh pagi ini',
            image: 'https://images.unsplash.com/photo-1517256064527-09c73fc73e38?auto=format&fit=crop&w=800&q=80',
            gallery: [
                'https://images.unsplash.com/photo-1517256064527-09c73fc73e38?auto=format&fit=crop&w=800&q=80'
            ],
            isWishlist: false,
            description: 'Espresso double shot aromatik dipadukan dengan susu murni creamy dan manis legit gula aren asli nusantara.'
        },
        {
            id: 8,
            title: 'Mix Donut Box (6pcs)',
            merchant: 'Donut Delight',
            merchantVerified: true,
            merchantAvatar: 'https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?auto=format&fit=crop&w=120&q=80',
            rating: 4.8,
            reviewsCount: 185,
            distance: 1.7,
            distanceText: '1.7 km',
            category: 'Roti & Kue',
            categorySpec: 'Roti & Kue',
            discount: 25,
            originalPrice: 20000,
            discountPrice: 15000,
            pickupTime: 'Ambil hari ini 18.00 - 20.00',
            pickupLocation: 'Jl. Fatmawati Raya No. 30, Jakarta',
            stock: 9,
            unit: 'box',
            freshness: 'Donat kentang digoreng empuk hari ini',
            image: 'https://images.unsplash.com/photo-1527515862127-a4fc05baf7a5?auto=format&fit=crop&w=800&q=80',
            gallery: [
                'https://images.unsplash.com/photo-1527515862127-a4fc05baf7a5?auto=format&fit=crop&w=800&q=80'
            ],
            isWishlist: false,
            description: 'Satu box isi 6 donat kentang lembut aneka topping premium: Cokelat Belgia, Glaze Stroberi, Matcha, dan Keju Cheddar.'
        },
        {
            id: 9,
            title: 'Nasi Ayam Sambal Matah',
            merchant: 'Warung Nasi Enak',
            merchantVerified: true,
            merchantAvatar: 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=120&q=80',
            rating: 4.7,
            reviewsCount: 89,
            distance: 1.1,
            distanceText: '1.1 km',
            category: 'Makanan Berat',
            categorySpec: 'Makanan Berat',
            discount: 22,
            originalPrice: 15500,
            discountPrice: 12000,
            pickupTime: 'Ambil hari ini 17.00 - 19.00',
            pickupLocation: 'Jl. Radio Dalam No. 14, Jakarta Selatan',
            stock: 6,
            unit: 'porsi',
            freshness: 'Ayam goreng krispi dengan sambal matah fresh',
            image: 'https://images.unsplash.com/photo-1562967914-608f82629710?auto=format&fit=crop&w=800&q=80',
            gallery: [
                'https://images.unsplash.com/photo-1562967914-608f82629710?auto=format&fit=crop&w=800&q=80'
            ],
            isWishlist: false,
            description: 'Nasi hangat pulen dengan suwiran ayam gurih berbumbu rempah dan sambal matah iris segar khas Bali beraroma serai.'
        },
        {
            id: 10,
            title: 'Choco Chip Cookies',
            merchant: 'Cookie House',
            merchantVerified: true,
            merchantAvatar: 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=120&q=80',
            rating: 4.9,
            reviewsCount: 160,
            distance: 0.9,
            distanceText: '0.9 km',
            category: 'Snack',
            categorySpec: 'Snack',
            discount: 18,
            originalPrice: 8500,
            discountPrice: 7000,
            pickupTime: 'Ambil hari ini 18.00 - 20.00',
            pickupLocation: 'Jl. Panglima Polim IX No. 3, Jakarta',
            stock: 14,
            unit: 'pack',
            freshness: 'Kue kering renyah dipanggang hari ini',
            image: 'https://images.unsplash.com/photo-1499636136210-6f4ee915583e?auto=format&fit=crop&w=800&q=80',
            gallery: [
                'https://images.unsplash.com/photo-1499636136210-6f4ee915583e?auto=format&fit=crop&w=800&q=80'
            ],
            isWishlist: false,
            description: 'Kue kering renyah bertabur cokelat chip melimpah yang lumer di mulut. Dibuat dengan mentega tawar murni.'
        },
        // Makanan Tambahan untuk Mencapai 24 Hasil Sesuai Teks "Menampilkan 24 hasil"
        {
            id: 11,
            title: 'Matcha Latte Dingin',
            merchant: 'Kopi Kita',
            merchantVerified: true,
            merchantAvatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=120&q=80',
            rating: 4.8,
            reviewsCount: 88,
            distance: 0.6,
            distanceText: '0.6 km',
            category: 'Minuman',
            categorySpec: 'Minuman',
            discount: 25,
            originalPrice: 16000,
            discountPrice: 12000,
            pickupTime: 'Ambil hari ini 17.00 - 19.00',
            pickupLocation: 'Jl. Cipete Raya No. 5, Jakarta Selatan',
            stock: 8,
            unit: 'cup',
            freshness: 'Matcha Uji Jepang fresh shake',
            image: 'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?auto=format&fit=crop&w=800&q=80',
            gallery: ['https://images.unsplash.com/photo-1536256263959-770b48d82b0a?auto=format&fit=crop&w=800&q=80'],
            isWishlist: false,
            description: 'Bubuk teh hijau matcha Kyoto otentik dipadukan susu segar dan sedikit pemanis alami madu.'
        },
        {
            id: 12,
            title: 'Chocolate Lava Cake',
            merchant: 'Sweet Moments',
            merchantVerified: true,
            merchantAvatar: 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=120&q=80',
            rating: 4.9,
            reviewsCount: 97,
            distance: 2.1,
            distanceText: '2.1 km',
            category: 'Dessert',
            categorySpec: 'Dessert',
            discount: 30,
            originalPrice: 22000,
            discountPrice: 15400,
            pickupTime: 'Ambil hari ini 18.00 - 20.00',
            pickupLocation: 'Jl. Panglima Polim No. 22, Jakarta',
            stock: 5,
            unit: 'pcs',
            freshness: 'Kue cokelat leleh siap hangatkan',
            image: 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?auto=format&fit=crop&w=800&q=80',
            gallery: ['https://images.unsplash.com/photo-1606313564200-e75d5e30476c?auto=format&fit=crop&w=800&q=80'],
            isWishlist: false,
            description: 'Kue cokelat panggang dengan lelehan cokelat hitam premium di bagian tengah.'
        },
        {
            id: 13,
            title: 'Baguette Garlic Bread',
            merchant: 'Sunny Bakery',
            merchantVerified: true,
            merchantAvatar: 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=120&q=80',
            rating: 4.7,
            reviewsCount: 74,
            distance: 1.2,
            distanceText: '1.2 km',
            category: 'Roti & Kue',
            categorySpec: 'Roti & Kue',
            discount: 30,
            originalPrice: 18000,
            discountPrice: 12600,
            pickupTime: 'Ambil hari ini 18.00 - 20.00',
            pickupLocation: 'Jl. Kemang Raya No. 12, Jakarta',
            stock: 8,
            unit: 'pcs',
            freshness: 'Baguette prancis oles butter bawang putih',
            image: 'https://images.unsplash.com/photo-1573140247632-f8fd74997d5c?auto=format&fit=crop&w=800&q=80',
            gallery: ['https://images.unsplash.com/photo-1573140247632-f8fd74997d5c?auto=format&fit=crop&w=800&q=80'],
            isWishlist: false,
            description: 'Roti baguette garing beroleskan garlic butter dan parsley segar harum menggugah selera.'
        },
        {
            id: 14,
            title: 'Beef Yakiniku Bento',
            merchant: 'RiceBowl Kitchen',
            merchantVerified: true,
            merchantAvatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=120&q=80',
            rating: 4.9,
            reviewsCount: 112,
            distance: 0.8,
            distanceText: '0.8 km',
            category: 'Makanan Berat',
            categorySpec: 'Makanan Berat',
            discount: 25,
            originalPrice: 32000,
            discountPrice: 24000,
            pickupTime: 'Ambil hari ini 17.00 - 19.00',
            pickupLocation: 'Jl. Senopati No. 45, Jakarta Selatan',
            stock: 5,
            unit: 'bento',
            freshness: 'Irisan daging sapi US dimasak siang ini',
            image: 'https://images.unsplash.com/photo-1569058242253-92a9c755a0ec?auto=format&fit=crop&w=800&q=80',
            gallery: ['https://images.unsplash.com/photo-1569058242253-92a9c755a0ec?auto=format&fit=crop&w=800&q=80'],
            isWishlist: false,
            description: 'Daging sapi US slice ditumis dengan saus yakiniku gurih manis, dilengkapi tamagoyaki dan salad wortel.'
        },
        {
            id: 15,
            title: 'Crispy Pisang Goreng Madu',
            merchant: 'Warung Nasi Enak',
            merchantVerified: true,
            merchantAvatar: 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=120&q=80',
            rating: 4.8,
            reviewsCount: 92,
            distance: 1.1,
            distanceText: '1.1 km',
            category: 'Snack',
            categorySpec: 'Snack',
            discount: 20,
            originalPrice: 12000,
            discountPrice: 9600,
            pickupTime: 'Ambil hari ini 17.00 - 19.00',
            pickupLocation: 'Jl. Radio Dalam No. 14, Jakarta Selatan',
            stock: 10,
            unit: 'porsi',
            freshness: 'Pisang raja matang karamelisasi madu',
            image: 'https://images.unsplash.com/photo-1528750997573-59b89d56f4f7?auto=format&fit=crop&w=800&q=80',
            gallery: ['https://images.unsplash.com/photo-1528750997573-59b89d56f4f7?auto=format&fit=crop&w=800&q=80'],
            isWishlist: false,
            description: 'Pisang raja pilihan berbalut adonan madu renyah karamel gelap dengan rasa manis alami.'
        },
        {
            id: 16,
            title: 'Earl Grey Milk Tea',
            merchant: 'Kopi Kita',
            merchantVerified: true,
            merchantAvatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=120&q=80',
            rating: 4.7,
            reviewsCount: 65,
            distance: 0.6,
            distanceText: '0.6 km',
            category: 'Minuman',
            categorySpec: 'Minuman',
            discount: 20,
            originalPrice: 12000,
            discountPrice: 9600,
            pickupTime: 'Ambil hari ini 17.00 - 19.00',
            pickupLocation: 'Jl. Cipete Raya No. 5, Jakarta Selatan',
            stock: 11,
            unit: 'cup',
            freshness: 'Seduhan teh hitam bergamot harum',
            image: 'https://images.unsplash.com/photo-1576092768241-dec231879fc3?auto=format&fit=crop&w=800&q=80',
            gallery: ['https://images.unsplash.com/photo-1576092768241-dec231879fc3?auto=format&fit=crop&w=800&q=80'],
            isWishlist: false,
            description: 'Teh hitam beraroma minyak bergamot berpadu susu lembut dan es batu segar.'
        },
        {
            id: 17,
            title: 'Penne Carbonara Creamy',
            merchant: 'Pasta La Vista',
            merchantVerified: true,
            merchantAvatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=120&q=80',
            rating: 4.8,
            reviewsCount: 94,
            distance: 1.5,
            distanceText: '1.5 km',
            category: 'Makanan Berat',
            categorySpec: 'Makanan Berat',
            discount: 25,
            originalPrice: 28000,
            discountPrice: 21000,
            pickupTime: 'Ambil hari ini 19.00 - 21.00',
            pickupLocation: 'Jl. Gandaria I No. 8, Jakarta Selatan',
            stock: 5,
            unit: 'porsi',
            freshness: 'Saus krim keju dan smoked beef lezat',
            image: 'https://images.unsplash.com/photo-1612874742237-6526221588e3?auto=format&fit=crop&w=800&q=80',
            gallery: ['https://images.unsplash.com/photo-1612874742237-6526221588e3?auto=format&fit=crop&w=800&q=80'],
            isWishlist: false,
            description: 'Pasta penne berlumur krim keju gurih dengan potongan smoked beef renyah dan lada hitam.'
        },
        {
            id: 18,
            title: 'Almond Danish Pastry',
            merchant: 'Sunny Bakery',
            merchantVerified: true,
            merchantAvatar: 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=120&q=80',
            rating: 4.9,
            reviewsCount: 130,
            distance: 1.2,
            distanceText: '1.2 km',
            category: 'Roti & Kue',
            categorySpec: 'Roti & Kue',
            discount: 30,
            originalPrice: 17000,
            discountPrice: 11900,
            pickupTime: 'Ambil hari ini 18.00 - 20.00',
            pickupLocation: 'Jl. Kemang Raya No. 12, Jakarta',
            stock: 7,
            unit: 'pcs',
            freshness: 'Pastry mentega tabur kacang almond sangrai',
            image: 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=800&q=80',
            gallery: ['https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=800&q=80'],
            isWishlist: false,
            description: 'Pastry eropa renyah dengan isian krim vanila lembut dan taburan almond panggang gurih.'
        },
        {
            id: 19,
            title: 'Katsu Curry Rice',
            merchant: 'RiceBowl Kitchen',
            merchantVerified: true,
            merchantAvatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=120&q=80',
            rating: 4.8,
            reviewsCount: 88,
            distance: 0.8,
            distanceText: '0.8 km',
            category: 'Makanan Berat',
            categorySpec: 'Makanan Berat',
            discount: 22,
            originalPrice: 27000,
            discountPrice: 21000,
            pickupTime: 'Ambil hari ini 17.00 - 19.00',
            pickupLocation: 'Jl. Senopati No. 45, Jakarta Selatan',
            stock: 6,
            unit: 'porsi',
            freshness: 'Kari jepang kental dimasak perlahan',
            image: 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?auto=format&fit=crop&w=800&q=80',
            gallery: ['https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?auto=format&fit=crop&w=800&q=80'],
            isWishlist: false,
            description: 'Ayam katsu krispi emas di atas nasi putih, disiram kuah kari jepang kaya rempah wortel kentang.'
        },
        {
            id: 20,
            title: 'Mango Sago Pudding',
            merchant: 'Sweet Moments',
            merchantVerified: true,
            merchantAvatar: 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=120&q=80',
            rating: 4.9,
            reviewsCount: 104,
            distance: 2.1,
            distanceText: '2.1 km',
            category: 'Dessert',
            categorySpec: 'Dessert',
            discount: 30,
            originalPrice: 18000,
            discountPrice: 12600,
            pickupTime: 'Ambil hari ini 18.00 - 20.00',
            pickupLocation: 'Jl. Panglima Polim No. 22, Jakarta',
            stock: 8,
            unit: 'cup',
            freshness: 'Potongan mangga arumanis manis segar',
            image: 'https://images.unsplash.com/photo-1551024709-8f23befc6f87?auto=format&fit=crop&w=800&q=80',
            gallery: ['https://images.unsplash.com/photo-1551024709-8f23befc6f87?auto=format&fit=crop&w=800&q=80'],
            isWishlist: false,
            description: 'Puding mangga segar dengan mutiara sagu kenyal dan siraman susu evaporasi dingin menyegarkan.'
        },
        {
            id: 21,
            title: 'Egg & Cheese Toast',
            merchant: 'Sandwich Hub',
            merchantVerified: true,
            merchantAvatar: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=120&q=80',
            rating: 4.6,
            reviewsCount: 52,
            distance: 1.4,
            distanceText: '1.4 km',
            category: 'Snack',
            categorySpec: 'Snack',
            discount: 25,
            originalPrice: 14000,
            discountPrice: 10500,
            pickupTime: 'Ambil hari ini 18.00 - 20.00',
            pickupLocation: 'Jl. Tebet Raya No. 19, Jakarta Selatan',
            stock: 7,
            unit: 'porsi',
            freshness: 'Telur orak-arik lembut dan keju leleh',
            image: 'https://images.unsplash.com/photo-1525351484163-7529414344d8?auto=format&fit=crop&w=800&q=80',
            gallery: ['https://images.unsplash.com/photo-1525351484163-7529414344d8?auto=format&fit=crop&w=800&q=80'],
            isWishlist: false,
            description: 'Roti brioche bakar berisikan telur creamy fluffy dan lelehan keju mozarella cheddar gurih.'
        },
        {
            id: 22,
            title: 'California Roll (8pcs)',
            merchant: 'Sushi Daily',
            merchantVerified: true,
            merchantAvatar: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=120&q=80',
            rating: 4.8,
            reviewsCount: 96,
            distance: 1.0,
            distanceText: '1.0 km',
            category: 'Makanan Berat',
            categorySpec: 'Makanan Berat',
            discount: 28,
            originalPrice: 25000,
            discountPrice: 18000,
            pickupTime: 'Ambil hari ini 17.00 - 19.00',
            pickupLocation: 'Grand Indonesia Mall Lt. LG, Jakarta',
            stock: 5,
            unit: 'roll',
            freshness: 'Sushi crabstick timun dan tobiko segar',
            image: 'https://images.unsplash.com/photo-1611143669185-af224c5e3252?auto=format&fit=crop&w=800&q=80',
            gallery: ['https://images.unsplash.com/photo-1611143669185-af224c5e3252?auto=format&fit=crop&w=800&q=80'],
            isWishlist: false,
            description: 'Uramaki sushi dengan isian crabstick manis, alpukat, mentimun jepang, dan taburan telur ikan tobiko oranye.'
        },
        {
            id: 23,
            title: 'Ice Lychee Tea',
            merchant: 'Kopi Kita',
            merchantVerified: true,
            merchantAvatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=120&q=80',
            rating: 4.8,
            reviewsCount: 140,
            distance: 0.6,
            distanceText: '0.6 km',
            category: 'Minuman',
            categorySpec: 'Minuman',
            discount: 20,
            originalPrice: 10000,
            discountPrice: 8000,
            pickupTime: 'Ambil hari ini 17.00 - 19.00',
            pickupLocation: 'Jl. Cipete Raya No. 5, Jakarta Selatan',
            stock: 12,
            unit: 'cup',
            freshness: 'Teh melati dingin dengan 2 buah leci asli',
            image: 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=800&q=80',
            gallery: ['https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=800&q=80'],
            isWishlist: false,
            description: 'Es teh beraroma buah leci manis menyegarkan dilengkapi dua buah leci utuh segar.'
        },
        {
            id: 24,
            title: 'Cinnamon Roll Glaze',
            merchant: 'Sunny Bakery',
            merchantVerified: true,
            merchantAvatar: 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=120&q=80',
            rating: 4.9,
            reviewsCount: 115,
            distance: 1.2,
            distanceText: '1.2 km',
            category: 'Roti & Kue',
            categorySpec: 'Roti & Kue',
            discount: 30,
            originalPrice: 16000,
            discountPrice: 11200,
            pickupTime: 'Ambil hari ini 18.00 - 20.00',
            pickupLocation: 'Jl. Kemang Raya No. 12, Jakarta',
            stock: 9,
            unit: 'pcs',
            freshness: 'Kayu manis harum baru selesai panggang',
            image: 'https://images.unsplash.com/photo-1509365465985-25d11c17e812?auto=format&fit=crop&w=800&q=80',
            gallery: ['https://images.unsplash.com/photo-1509365465985-25d11c17e812?auto=format&fit=crop&w=800&q=80'],
            isWishlist: false,
            description: 'Roti gulung kayu manis lembut berlapis gula palem wangi dengan siraman vanilla cream cheese glaze kental.'
        }
    ],

    // State Interaktif Halaman
    currentCity: 'Jakarta, Indonesia',
    searchQuery: '',
    selectedCategory: 'Semua',
    locationFilter: 'Lokasi Saya',
    maxRadius: 20, // default max 20km slider
    priceRange: 'all',
    minRating: 0,
    sortBy: 'terbaru',
    viewMode: 'grid', // 'grid' | 'list'
    currentPage: 1,
    itemsPerPage: 10,
    mobileFilterOpen: false,
    mapModalOpen: false,

    // Modal Detail & Reservasi (Komponen food-detail-modal & reservation-modal)
    selectedFood: null,
    activeImage: null,
    orderQuantity: 1,

    // State Alur Reservasi Konsumen
    reservationModalOpen: false,
    reservationStep: 1,
    resItem: {},
    resQuantity: 1,
    selectedSlot: 1,
    selectedPayment: 'gopay',
    reservationNotes: '',

    openReservation(food) {
        this.resItem = food || this.selectedFood || this.foods[0];
        this.resQuantity = this.orderQuantity || 1;
        this.reservationStep = 1;
        this.selectedSlot = 1;
        this.selectedPayment = 'gopay';
        this.closeDetail();
        this.reservationModalOpen = true;
    },

    closeReservationModal() {
        this.reservationModalOpen = false;
        this.reservationStep = 1;
    },

    finishReservation() {
        this.reservationStep = 4;
        this.showToast('Reservasi berhasil dikonfirmasi! Kode booking telah diterbitkan.', 'success');
    },

    submitReservation() {
        this.openReservation(this.selectedFood);
    },

    // Collapse Sidebar Accordions
    collapseCategory: false,
    collapseLocation: false,
    collapseRadius: false,
    collapsePrice: false,
    collapseRating: false,

    // Helper Formatter Rupiah Indonesia
    formatRupiah(number) {
        if (!number) return 'Rp 0';
        return 'Rp ' + Number(number).toLocaleString('id-ID');
    },

    // Method Filter & Sorting
    get filteredFoods() {
        return this.foods.filter(food => {
            // 1. Search Query
            if (this.searchQuery.trim() !== '') {
                const q = this.searchQuery.toLowerCase();
                const matchTitle = food.title.toLowerCase().includes(q);
                const matchMerchant = food.merchant.toLowerCase().includes(q);
                const matchCat = food.category.toLowerCase().includes(q);
                if (!matchTitle && !matchMerchant && !matchCat) return false;
            }

            // 2. Kategori Filter (Semua vs Spesifik)
            if (this.selectedCategory !== 'Semua' && food.category !== this.selectedCategory) {
                return false;
            }

            // 3. Radius Jarak (km)
            if (food.distance > this.maxRadius) {
                return false;
            }

            // 4. Rentang Harga
            if (this.priceRange === 'under-10k' && food.discountPrice >= 10000) return false;
            if (this.priceRange === '10k-20k' && (food.discountPrice < 10000 || food.discountPrice > 20000)) return false;
            if (this.priceRange === '20k-30k' && (food.discountPrice < 20000 || food.discountPrice > 30000)) return false;
            if (this.priceRange === 'above-30k' && food.discountPrice <= 30000) return false;

            // 5. Rating Bisnis
            if (this.minRating > 0 && food.rating < this.minRating) {
                return false;
            }

            return true;
        }).sort((a, b) => {
            if (this.sortBy === 'terbaru') return b.id - a.id;
            if (this.sortBy === 'harga-terendah') return a.discountPrice - b.discountPrice;
            if (this.sortBy === 'harga-tertinggi') return b.discountPrice - a.discountPrice;
            if (this.sortBy === 'diskon-tertinggi') return b.discount - a.discount;
            if (this.sortBy === 'jarak-terdekat') return a.distance - b.distance;
            if (this.sortBy === 'rating-tertinggi') return b.rating - a.rating;
            return 0;
        });
    },

    // Paginasi Items
    get paginatedFoods() {
        const start = (this.currentPage - 1) * this.itemsPerPage;
        return this.filteredFoods.slice(start, start + this.itemsPerPage);
    },

    get totalPages() {
        return Math.ceil(this.filteredFoods.length / this.itemsPerPage) || 1;
    },

    // Reset Semua Filter
    resetFilters() {
        this.searchQuery = '';
        this.selectedCategory = 'Semua';
        this.locationFilter = 'Lokasi Saya';
        this.maxRadius = 20;
        this.priceRange = 'all';
        this.minRating = 0;
        this.sortBy = 'terbaru';
        this.currentPage = 1;
        this.showToast('Filter berhasil direset.');
    },

    // Buka Modal Detail Makanan
    openDetail(food) {
        this.selectedFood = food;
        this.activeImage = food.image;
        this.orderQuantity = 1;
    },

    closeDetail() {
        this.selectedFood = null;
        this.activeImage = null;
        this.orderQuantity = 1;
    },

    // Toggle Wishlist
    toggleWishlist(food) {
        food.isWishlist = !food.isWishlist;
        if (food.isWishlist) {
            this.showToast(food.title + ' ditambahkan ke daftar favorit!', 'success');
        } else {
            this.showToast(food.title + ' dihapus dari favorit.', 'info');
        }
    },

    // Direct Action Reserve Sekarang
    reserveNow(food) {
        this.showToast('Memproses reservasi untuk ' + food.title + ' (' + this.orderQuantity + ' ' + (food.unit || 'porsi') + ')...', 'success');
        this.closeDetail();
        // Alihkan ke Reservasi Saya dengan simulasi
        setTimeout(() => {
            window.location.href = '{{ url('/?view=my-reservations') }}';
        }, 800);
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
                            <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=200&q=80" 
                                 alt="Salad Bowl" 
                                 class="w-full h-full object-cover">
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
                            <img src="https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=200&q=80" 
                                 alt="Croissant" 
                                 class="w-full h-full object-cover">
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

                    <!-- Sisi Tengah: Input Lokasi Saya -->
                    <div class="w-full md:w-72 flex items-center gap-2.5 px-3.5 py-1.5 border-t md:border-t-0 border-stone-100">
                        <svg class="w-5 h-5 text-stone-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-stone-700 text-sm font-semibold truncate" x-text="locationFilter === 'Lokasi Saya' ? 'Lokasi saya' : currentCity">Lokasi saya</span>
                        <span class="ml-auto text-[10px] font-bold text-[#1B5E3A] bg-[#EBF5F0] px-2 py-0.5 rounded-full shrink-0">GPS Aktif</span>
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
                                  x-text="sortBy === 'terbaru' ? 'Terbaru' : (sortBy === 'harga-terendah' ? 'Harga Terendah' : (sortBy === 'diskon-tertinggi' ? 'Diskon Tertinggi' : (sortBy === 'jarak-terdekat' ? 'Jarak Terdekat' : 'Rating Tertinggi')))">
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
                            <button type="button" @click="sortBy = 'jarak-terdekat'; sortMenuOpen = false" class="w-full text-left px-3.5 py-2 text-xs font-semibold text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A]">Jarak Terdekat</button>
                            <button type="button" @click="sortBy = 'rating-tertinggi'; sortMenuOpen = false" class="w-full text-left px-3.5 py-2 text-xs font-semibold text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A]">Rating Tertinggi</button>
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

                        <div x-show="!collapseCategory" x-collapse class="space-y-2.5 text-xs font-medium text-stone-700">
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
                        </div>
                    </div>

                    <div class="border-t border-stone-100"></div>

                    <!-- 2. Bagian Lokasi (Radio: Lokasi Saya vs Pilih Lokasi Lain) -->
                    <div>
                        <button type="button" 
                                @click="collapseLocation = !collapseLocation"
                                class="w-full flex items-center justify-between text-xs font-extrabold uppercase tracking-wider text-stone-800 mb-3">
                            <span>Lokasi</span>
                            <svg class="w-4 h-4 text-stone-400 transition-transform duration-200" :class="collapseLocation ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="!collapseLocation" x-collapse class="space-y-2.5 text-xs font-medium text-stone-700">
                            <label class="flex items-center gap-2.5 cursor-pointer select-none">
                                <input type="radio" 
                                       name="sidebar_location" 
                                       value="Lokasi Saya" 
                                       x-model="locationFilter"
                                       class="text-[#1B5E3A] focus:ring-[#1B5E3A]/20 border-stone-300">
                                <span :class="locationFilter === 'Lokasi Saya' ? 'font-bold text-stone-900' : ''">Lokasi Saya</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer select-none">
                                <input type="radio" 
                                       name="sidebar_location" 
                                       value="Pilih Lokasi Lain" 
                                       x-model="locationFilter"
                                       class="text-[#1B5E3A] focus:ring-[#1B5E3A]/20 border-stone-300">
                                <span :class="locationFilter === 'Pilih Lokasi Lain' ? 'font-bold text-stone-900' : ''">Pilih Lokasi Lain</span>
                            </label>
                        </div>
                    </div>

                    <div class="border-t border-stone-100"></div>

                    <!-- 3. Bagian Jarak (Green Slider 0 km - 20 km+) -->
                    <div>
                        <div class="flex items-center justify-between text-xs font-extrabold uppercase tracking-wider text-stone-800 mb-2">
                            <span>Jarak</span>
                            <span class="text-[#1B5E3A] font-bold text-xs" x-text="maxRadius >= 20 ? '20 km+' : maxRadius + ' km'">20 km+</span>
                        </div>

                        <!-- Range Slider Hijau -->
                        <div class="pt-2 pb-1">
                            <input type="range" 
                                   min="1" 
                                   max="20" 
                                   step="0.5" 
                                   x-model="maxRadius" 
                                   class="slider-green">
                        </div>

                        <!-- Labels Bawah Slider: 0 km & 20 km+ Sesuai Mockup -->
                        <div class="flex items-center justify-between text-[11px] font-bold text-stone-400 mt-1">
                            <span>0 km</span>
                            <span>20 km+</span>
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

                        <div x-show="!collapsePrice" x-collapse class="space-y-2.5 text-xs font-medium text-stone-700">
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

                    <div class="border-t border-stone-100"></div>

                    <!-- 5. Bagian Rating Bisnis (5 Bintang Oranye + "ke atas") Sesuai Mockup -->
                    <div>
                        <button type="button" 
                                @click="collapseRating = !collapseRating"
                                class="w-full flex items-center justify-between text-xs font-extrabold uppercase tracking-wider text-stone-800 mb-3">
                            <span>Rating Bisnis</span>
                            <svg class="w-4 h-4 text-stone-400 transition-transform duration-200" :class="collapseRating ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="!collapseRating" x-collapse>
                            <button type="button" 
                                    @click="minRating = minRating === 4.5 ? 0 : 4.5"
                                    class="w-full flex items-center justify-between p-2 rounded-xl border transition-all text-xs font-semibold"
                                    :class="minRating === 4.5 ? 'border-[#F59E0B] bg-[#FEF3C7]/40 text-stone-900' : 'border-stone-200/80 hover:bg-stone-50 text-stone-600'">
                                <div class="flex items-center gap-1 text-[#F59E0B]">
                                    <span>★</span>
                                    <span>★</span>
                                    <span>★</span>
                                    <span>★</span>
                                    <span>★</span>
                                </div>
                                <span class="text-stone-500 font-medium">ke atas</span>
                            </button>
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
                                        <img :src="food.image" 
                                             :alt="food.title" 
                                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                             loading="lazy">

                                        <!-- Badge Diskon Oranye (Kiri Atas) Persis Mockup ("30% OFF", "25% OFF", dll.) -->
                                        <div class="absolute top-2.5 left-2.5 px-2.5 py-1 rounded-md bg-[#F59E0B] text-stone-900 font-black text-[11px] shadow-sm tracking-tight">
                                            <span x-text="food.discount + '% OFF'">30% OFF</span>
                                        </div>

                                        <!-- Tombol Ikon Wishlist (Kanan Atas) Persis Mockup -->
                                        <button type="button" 
                                                @click.stop="toggleWishlist(food)"
                                                class="absolute top-2.5 right-2.5 w-8 h-8 rounded-full bg-white/90 backdrop-blur-xs shadow-sm flex items-center justify-center hover:scale-110 active:scale-95 transition-all duration-200"
                                                :title="food.isWishlist ? 'Hapus dari favorit' : 'Tambah ke favorit'">
                                            <svg class="w-4 h-4 transition-colors" 
                                                 :class="food.isWishlist ? 'text-red-500 fill-red-500' : 'text-stone-400 hover:text-red-500'" 
                                                 fill="none" 
                                                 stroke="currentColor" 
                                                 viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Konten Kartu Makanan -->
                                    <div class="p-3 sm:p-3.5 space-y-1.5">
                                        
                                        <!-- Nama Toko / Merchant & Centang Hijau Verified -->
                                        <div class="flex items-center gap-1.5 text-xs text-stone-500 font-medium">
                                            <span class="truncate" x-text="food.merchant">Sunny Bakery</span>
                                            <!-- Centang Hijau Terverifikasi -->
                                            <span class="text-[#1B5E3A] shrink-0" title="Mitra Terverifikasi">
                                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                            </span>
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

                                    <!-- Jarak Lokasi dengan Ikon Pin -->
                                    <div class="flex items-center gap-1.5 text-stone-500">
                                        <svg class="w-3.5 h-3.5 text-stone-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span x-text="food.distanceText || (food.distance + ' km')">1.2 km</span>
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
                                        <img :src="food.image" :alt="food.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        <span class="absolute top-1.5 left-1.5 px-2 py-0.5 rounded bg-[#F59E0B] text-stone-900 font-extrabold text-[10px]">
                                            <span x-text="food.discount + '% OFF'"></span>
                                        </span>
                                    </div>

                                    <!-- Detail Informasi -->
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-1.5 text-xs text-stone-500 font-medium">
                                            <span x-text="food.merchant">Sunny Bakery</span>
                                            <span class="text-[#1B5E3A] shrink-0">✓</span>
                                            <span class="text-stone-300">•</span>
                                            <span class="text-stone-500" x-text="food.distanceText">1.2 km</span>
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
                <div class="mt-10 sm:mt-12 flex items-center justify-center gap-1.5 sm:gap-2">
                    
                    <!-- Tombol Prev (<) -->
                    <button type="button" 
                            @click="if (currentPage > 1) { currentPage--; window.scrollTo({ top: 300, behavior: 'smooth' }); }" 
                            :disabled="currentPage === 1"
                            class="w-9 h-9 rounded-xl border border-stone-200/90 bg-white flex items-center justify-center text-stone-500 hover:text-stone-900 disabled:opacity-40 disabled:cursor-not-allowed shadow-xs transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>

                    <!-- Halaman 1 [Aktif Hijau Tua #1B5E3A Teks Putih Persis Mockup] -->
                    <button type="button" 
                            @click="currentPage = 1; window.scrollTo({ top: 300, behavior: 'smooth' });"
                            class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-bold transition-all shadow-xs"
                            :class="currentPage === 1 
                                ? 'bg-[#1B5E3A] text-white shadow-[#1B5E3A]/25' 
                                : 'border border-stone-200/90 bg-white text-stone-700 hover:bg-stone-50'">
                        1
                    </button>

                    <!-- Halaman 2 -->
                    <button type="button" 
                            @click="currentPage = 2; window.scrollTo({ top: 300, behavior: 'smooth' });"
                            class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-bold transition-all shadow-xs"
                            :class="currentPage === 2 
                                ? 'bg-[#1B5E3A] text-white shadow-[#1B5E3A]/25' 
                                : 'border border-stone-200/90 bg-white text-stone-700 hover:bg-stone-50'">
                        2
                    </button>

                    <!-- Halaman 3 -->
                    <button type="button" 
                            @click="currentPage = 3; window.scrollTo({ top: 300, behavior: 'smooth' });"
                            class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-bold transition-all shadow-xs"
                            :class="currentPage === 3 
                                ? 'bg-[#1B5E3A] text-white shadow-[#1B5E3A]/25' 
                                : 'border border-stone-200/90 bg-white text-stone-700 hover:bg-stone-50'">
                        3
                    </button>

                    <!-- Separator (...) -->
                    <span class="w-7 text-center text-stone-400 font-bold text-xs">...</span>

                    <!-- Halaman 5 -->
                    <button type="button" 
                            @click="currentPage = 5; window.scrollTo({ top: 300, behavior: 'smooth' });"
                            class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-bold transition-all shadow-xs"
                            :class="currentPage === 5 
                                ? 'bg-[#1B5E3A] text-white shadow-[#1B5E3A]/25' 
                                : 'border border-stone-200/90 bg-white text-stone-700 hover:bg-stone-50'">
                        5
                    </button>

                    <!-- Tombol Next (>) -->
                    <button type="button" 
                            @click="if (currentPage < 5) { currentPage++; window.scrollTo({ top: 300, behavior: 'smooth' }); }" 
                            :disabled="currentPage >= 5"
                            class="w-9 h-9 rounded-xl border border-stone-200/90 bg-white flex items-center justify-center text-stone-500 hover:text-stone-900 disabled:opacity-40 disabled:cursor-not-allowed shadow-xs transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>

                </div>

            </main>

        </div>
    </div>


    <!-- ========================================================================= -->
    <!-- 6. TOMBOL MENGAMBANG "PETA" DI POJOK KANAN BAWAH (Persis Sesuai Mockup)   -->
    <!-- ========================================================================= -->
    <div class="fixed bottom-6 right-6 sm:bottom-8 sm:right-8 z-30">
        <button type="button" 
                @click="mapModalOpen = true"
                class="flex items-center gap-2.5 px-5 py-3 rounded-xl bg-[#1B5E3A] hover:bg-[#14472C] text-white text-sm font-extrabold shadow-floating hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-200">
            <!-- Ikon Lipatan Peta Persis Mockup -->
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
            </svg>
            <span>Peta</span>
        </button>
    </div>


    <!-- ========================================================================= -->
    <!-- 7. MODAL PETA SEBARAN TOKO MAKANAN SURPLUS (MAP VIEW POPUP)               -->
    <!-- ========================================================================= -->
    <div x-cloak x-show="mapModalOpen" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-stone-900/60 backdrop-blur-xs transition-opacity" @click="mapModalOpen = false"></div>
        
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-4xl overflow-hidden border border-stone-200">
                <!-- Header Modal Peta -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-stone-100 bg-stone-50">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-[#1B5E3A] text-white flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-stone-900">Peta Sebaran Makanan Surplus</h3>
                            <p class="text-xs text-stone-500">Menampilkan toko terdekat di sekitar Jakarta Selatan & Pusat</p>
                        </div>
                    </div>
                    <button type="button" @click="mapModalOpen = false" class="w-8 h-8 rounded-full bg-white border border-stone-200 text-stone-500 hover:text-stone-900 flex items-center justify-center font-bold">✕</button>
                </div>

                <!-- Kontainer Visualisasi Peta Simulasi -->
                <div class="relative h-[420px] bg-[#E8ECE9] overflow-hidden">
                    <!-- Pola Grid Peta Jalan -->
                    <div class="absolute inset-0 opacity-40 bg-[radial-gradient(#1B5E3A_1px,transparent_1px)] [background-size:16px_16px]"></div>
                    
                    <!-- Garis-garis Rute Simulasi -->
                    <svg class="absolute inset-0 w-full h-full text-stone-300 stroke-current" fill="none" stroke-width="4">
                        <path d="M 50 150 Q 200 80 400 220 T 700 180" />
                        <path d="M 120 380 Q 300 250 550 300 T 800 120" />
                    </svg>

                    <!-- Pin 1: Sunny Bakery -->
                    <div class="absolute top-28 left-40 transform -translate-x-1/2 -translate-y-full cursor-pointer group"
                         @click="openDetail(foods[0]); mapModalOpen = false">
                        <div class="px-2.5 py-1 rounded-xl bg-white shadow-lg border border-stone-200 text-[11px] font-bold text-stone-900 flex items-center gap-1.5 mb-1 whitespace-nowrap">
                            <span class="w-2 h-2 rounded-full bg-[#1B5E3A]"></span>
                            <span>Sunny Bakery (1.2 km)</span>
                        </div>
                        <div class="w-7 h-7 rounded-full bg-[#1B5E3A] text-white flex items-center justify-center mx-auto shadow-md ring-4 ring-[#1B5E3A]/20">
                            🥐
                        </div>
                    </div>

                    <!-- Pin 2: RiceBowl Kitchen -->
                    <div class="absolute top-44 left-1/2 transform -translate-x-1/2 -translate-y-full cursor-pointer group"
                         @click="openDetail(foods[1]); mapModalOpen = false">
                        <div class="px-2.5 py-1 rounded-xl bg-white shadow-lg border border-stone-200 text-[11px] font-bold text-stone-900 flex items-center gap-1.5 mb-1 whitespace-nowrap">
                            <span class="w-2 h-2 rounded-full bg-[#E67E22]"></span>
                            <span>RiceBowl Kitchen (0.8 km)</span>
                        </div>
                        <div class="w-7 h-7 rounded-full bg-[#E67E22] text-white flex items-center justify-center mx-auto shadow-md ring-4 ring-[#E67E22]/20">
                            🍱
                        </div>
                    </div>

                    <!-- Pin 3: Kopi Kita -->
                    <div class="absolute top-72 left-1/3 transform -translate-x-1/2 -translate-y-full cursor-pointer group"
                         @click="openDetail(foods[6]); mapModalOpen = false">
                        <div class="px-2.5 py-1 rounded-xl bg-white shadow-lg border border-stone-200 text-[11px] font-bold text-stone-900 flex items-center gap-1.5 mb-1 whitespace-nowrap">
                            <span class="w-2 h-2 rounded-full bg-[#1B5E3A]"></span>
                            <span>Kopi Kita (0.6 km)</span>
                        </div>
                        <div class="w-7 h-7 rounded-full bg-[#1B5E3A] text-white flex items-center justify-center mx-auto shadow-md ring-4 ring-[#1B5E3A]/20">
                            ☕
                        </div>
                    </div>

                    <!-- Pin 4: Sweet Moments -->
                    <div class="absolute top-36 right-48 transform -translate-x-1/2 -translate-y-full cursor-pointer group"
                         @click="openDetail(foods[3]); mapModalOpen = false">
                        <div class="px-2.5 py-1 rounded-xl bg-white shadow-lg border border-stone-200 text-[11px] font-bold text-stone-900 flex items-center gap-1.5 mb-1 whitespace-nowrap">
                            <span class="w-2 h-2 rounded-full bg-[#F59E0B]"></span>
                            <span>Sweet Moments (2.1 km)</span>
                        </div>
                        <div class="w-7 h-7 rounded-full bg-[#F59E0B] text-stone-900 flex items-center justify-center mx-auto shadow-md ring-4 ring-[#F59E0B]/20">
                            🍰
                        </div>
                    </div>

                    <!-- Lokasi Pengguna (Pin Biru Denyut) -->
                    <div class="absolute bottom-16 right-1/3 transform -translate-x-1/2 -translate-y-1/2">
                        <div class="relative flex items-center justify-center">
                            <span class="animate-ping absolute inline-flex h-8 w-8 rounded-full bg-blue-400 opacity-75"></span>
                            <div class="w-5 h-5 rounded-full bg-blue-600 border-2 border-white shadow-md"></div>
                        </div>
                        <span class="px-2 py-0.5 rounded-md bg-stone-900 text-white text-[10px] font-bold mt-1 inline-block whitespace-nowrap">Posisi Anda</span>
                    </div>

                </div>

                <div class="p-4 bg-white border-t border-stone-100 flex items-center justify-between text-xs text-stone-500">
                    <span>💡 Klik salah satu pin toko untuk membuka detail makanan surplus.</span>
                    <button type="button" @click="mapModalOpen = false" class="px-4 py-2 rounded-xl bg-stone-100 text-stone-700 font-bold hover:bg-stone-200">Tutup Peta</button>
                </div>
            </div>
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
                        <template x-for="cat in ['Semua', 'Makanan Berat', 'Roti & Kue', 'Minuman', 'Snack', 'Dessert']" :key="cat">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="mobile_cat" :value="cat" x-model="selectedCategory" class="text-[#1B5E3A] focus:ring-[#1B5E3A]">
                                <span x-text="cat"></span>
                            </label>
                        </template>
                    </div>
                </div>

                <!-- Jarak di Mobile Drawer -->
                <div>
                    <div class="flex justify-between text-xs font-bold text-stone-700 mb-1">
                        <span>Jarak Maksimal</span>
                        <span class="text-[#1B5E3A]" x-text="maxRadius + ' km'"></span>
                    </div>
                    <input type="range" min="1" max="20" step="1" x-model="maxRadius" class="slider-green">
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
