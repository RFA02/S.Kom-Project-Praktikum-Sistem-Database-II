@extends('layouts.main')

@section('title', 'My Reservations - What\'s Left')

@section('header')
<!-- Logged-in Consumer Header/Navbar matching attachment mockup -->
<header class="sticky top-0 z-40 w-full bg-white/95 backdrop-blur-md border-b border-stone-200/80 transition-all duration-200 shadow-xs"
        x-data="{
            profileDropdown: false,
            locationDropdown: false,
            notificationDropdown: false,
            selectedCity: 'Jakarta, Indonesia',
            cities: ['Jakarta, Indonesia', 'Bandung, Indonesia', 'Surabaya, Indonesia', 'Tangerang Selatan, Indonesia']
        }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
        
        <!-- Brand Logo -->
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5 group">
            <div class="w-10 h-10 rounded-xl bg-[#1B5E3A] flex items-center justify-center text-white shadow-sm shadow-[#1B5E3A]/25 transition-transform group-hover:scale-105">
                <!-- Sprout / Leaf Icon -->
                <svg class="w-5 h-5 text-[#F59E0B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div class="flex items-center">
                <span class="text-2xl font-black tracking-tight text-[#1B5E3A]">What's</span>
                <span class="text-2xl font-black tracking-tight text-[#E67E22] ml-1">Left</span>
                <!-- Small sparkle decoration icon -->
                <svg class="w-3.5 h-3.5 text-[#F59E0B] -mt-3 ml-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            </div>
        </a>

        <!-- Desktop Navigation Links -->
        <nav class="hidden lg:flex items-center gap-7">
            <a href="{{ url('/') }}" class="py-2 text-sm font-semibold text-stone-700 hover:text-[#1B5E3A] transition-colors">
                Beranda
            </a>
            <a href="{{ url('/?view=landing#makanan-populer') }}" class="py-2 text-sm font-semibold text-stone-700 hover:text-[#1B5E3A] transition-colors">
                Jelajahi
            </a>
            <a href="{{ url('/?view=landing#cara-kerja') }}" class="py-2 text-sm font-semibold text-stone-700 hover:text-[#1B5E3A] transition-colors">
                Cara Kerja
            </a>
            <a href="{{ url('/?view=dashboard') }}" class="py-2 text-sm font-semibold text-stone-700 hover:text-[#1B5E3A] transition-colors">
                Untuk Bisnis
            </a>
            <a href="{{ url('/?view=landing#tentang-kami') }}" class="py-2 text-sm font-semibold text-stone-700 hover:text-[#1B5E3A] transition-colors">
                Tentang Kami
            </a>
        </nav>

        <!-- Right Side: Location Pill, Notifications, & User Profile (Exact Mockup Match) -->
        <div class="flex items-center gap-3 sm:gap-4">
            
            <!-- Location Selector Pill -->
            <div class="relative" @click.outside="locationDropdown = false">
                <button type="button" 
                        @click="locationDropdown = !locationDropdown"
                        class="hidden sm:inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold text-stone-700 bg-stone-50 border border-stone-200/90 hover:border-[#1B5E3A] hover:bg-[#EBF5F0]/50 transition duration-200">
                    <svg class="w-4 h-4 text-[#1B5E3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span x-text="selectedCity">Jakarta, Indonesia</span>
                    <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Location Dropdown Menu -->
                <div x-cloak x-show="locationDropdown" 
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-56 rounded-2xl bg-white shadow-xl border border-stone-100 py-2 z-50">
                    <div class="px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider text-stone-400">Pilih Lokasi Terdekat</div>
                    <template x-for="city in cities" :key="city">
                        <button type="button" 
                                @click="selectedCity = city; locationDropdown = false; showToast('Lokasi berhasil diubah ke ' + city)"
                                class="w-full text-left px-3.5 py-2 text-xs font-medium text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A] flex items-center justify-between transition">
                            <span x-text="city"></span>
                            <span x-show="selectedCity === city" class="text-[#1B5E3A] font-bold">✓</span>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Notification Bell with Orange Badge (Count: 3) -->
            <div class="relative" @click.outside="notificationDropdown = false">
                <button type="button" 
                        @click="notificationDropdown = !notificationDropdown"
                        class="w-10 h-10 rounded-full flex items-center justify-center text-stone-600 hover:text-[#1B5E3A] hover:bg-stone-100 transition relative"
                        title="Notifikasi">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <!-- Orange Notification Badge -->
                    <span class="absolute top-1.5 right-1.5 w-4 h-4 rounded-full bg-[#E67E22] text-white text-[10px] font-bold flex items-center justify-center shadow-xs">
                        3
                    </span>
                </button>

                <!-- Notifications Dropdown -->
                <div x-cloak x-show="notificationDropdown" 
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-80 sm:w-88 rounded-2xl bg-white shadow-2xl border border-stone-100 py-3 z-50">
                    <div class="px-4 pb-2 border-b border-stone-100 flex items-center justify-between">
                        <span class="text-sm font-bold text-stone-900">Notifikasi Reservasi</span>
                        <span class="text-[11px] font-semibold text-[#1B5E3A] cursor-pointer hover:underline" @click="showToast('Semua notifikasi ditandai dibaca')">Tandai Dibaca</span>
                    </div>
                    <div class="divide-y divide-stone-100 max-h-72 overflow-y-auto custom-scrollbar">
                        <div class="px-4 py-3 hover:bg-stone-50 transition cursor-pointer flex gap-3 items-start">
                            <span class="w-2 h-2 rounded-full bg-[#E67E22] mt-1.5 shrink-0"></span>
                            <div>
                                <p class="text-xs font-bold text-stone-800">Croissant Butter Siap Diambil!</p>
                                <p class="text-[11px] text-stone-500 mt-0.5">Ambil di Sunny Bakery sebelum pukul 20.00 WIB.</p>
                                <span class="text-[10px] text-stone-400 mt-1 block">10 menit yang lalu</span>
                            </div>
                        </div>
                        <div class="px-4 py-3 hover:bg-stone-50 transition cursor-pointer flex gap-3 items-start">
                            <span class="w-2 h-2 rounded-full bg-[#1B5E3A] mt-1.5 shrink-0"></span>
                            <div>
                                <p class="text-xs font-bold text-stone-800">Reservasi Baru Dikonfirmasi</p>
                                <p class="text-[11px] text-stone-500 mt-0.5">Chicken Teriyaki Bowl (Kode: WL24521Y) berhasil dipesan.</p>
                                <span class="text-[10px] text-stone-400 mt-1 block">1 jam yang lalu</span>
                            </div>
                        </div>
                        <div class="px-4 py-3 hover:bg-stone-50 transition cursor-pointer flex gap-3 items-start">
                            <span class="w-2 h-2 rounded-full bg-stone-300 mt-1.5 shrink-0"></span>
                            <div>
                                <p class="text-xs font-semibold text-stone-700">Dampak Penyelamatanmu Meningkat</p>
                                <p class="text-[11px] text-stone-500 mt-0.5">Kamu telah mencegah 4.250g food waste minggu ini! 🌱</p>
                                <span class="text-[10px] text-stone-400 mt-1 block">Kemarin</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Profile Avatar & Greeting: "Hai, Jasmine" -->
            <div class="relative" @click.outside="profileDropdown = false">
                <button type="button" 
                        @click="profileDropdown = !profileDropdown"
                        class="flex items-center gap-2.5 p-1 rounded-full hover:bg-stone-100 transition duration-150">
                    <!-- User Avatar -->
                    <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=120&q=80" 
                         alt="Foto Profil Jasmine" 
                         class="w-9 h-9 rounded-full object-cover ring-2 ring-[#1B5E3A]/20">
                    <span class="text-sm font-bold text-stone-800 hidden sm:inline">Hai, Jasmine</span>
                    <svg class="w-4 h-4 text-stone-500 hidden sm:inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Profile Dropdown Menu -->
                <div x-cloak x-show="profileDropdown" 
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-56 rounded-2xl bg-white shadow-xl border border-stone-100 py-2 z-50">
                    <div class="px-4 py-2 border-b border-stone-100">
                        <p class="text-xs text-stone-400">Masuk sebagai</p>
                        <p class="text-sm font-bold text-stone-900 truncate">Jasmine Salsabilla</p>
                        <span class="inline-block px-2 py-0.5 mt-1 rounded-full text-[10px] font-bold bg-[#EBF5F0] text-[#1B5E3A]">
                            Food Saver Hero 🌱
                        </span>
                    </div>
                    <a href="{{ url('/?view=my-reservations') }}" class="block px-4 py-2 text-xs font-semibold text-[#1B5E3A] bg-[#EBF5F0]/60 flex items-center gap-2">
                        <span>📋</span> Reservasi Saya (Aktif)
                    </a>
                    <a href="{{ url('/') }}" class="block px-4 py-2 text-xs font-medium text-stone-700 hover:bg-stone-50 flex items-center gap-2">
                        <span>🔍</span> Jelajahi Makanan
                    </a>
                    <a href="{{ url('/?view=dashboard') }}" class="block px-4 py-2 text-xs font-medium text-stone-700 hover:bg-stone-50 flex items-center gap-2">
                        <span>🏪</span> Beralih ke Portal Bisnis
                    </a>
                    <div class="border-t border-stone-100 my-1"></div>
                    <a href="{{ url('/?view=login') }}" class="block px-4 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 flex items-center gap-2">
                        <span>🚪</span> Keluar Akun
                    </a>
                </div>
            </div>

        </div>
    </div>
</header>
@endsection

@section('content')
<div x-data="{
    // Filter Tab State: 'Semua', 'Akan Diambil', 'Sudah Diambil', 'Selesai', 'Dibatalkan'
    activeTab: 'Semua',
    tabs: ['Semua', 'Akan Diambil', 'Sudah Diambil', 'Selesai', 'Dibatalkan'],

    // Detail Modal State
    detailModalOpen: false,
    selectedReservation: null,

    // Help & CS Modal State
    csModalOpen: false,
    helpCenterModalOpen: false,

    // Active Reservations Data (Matching Attachment Screenshot)
    activeReservations: [
        {
            id: 1,
            title: 'Croissant Butter',
            merchant: 'Sunny Bakery',
            merchantVerified: true,
            merchantPhone: '+62 812-3456-7890',
            status: 'Akan Diambil',
            statusKey: 'Akan Diambil',
            dateLabel: 'Hari ini, 20 Mei 2025',
            pickupTime: '18.00 - 20.00',
            pickupAddress: 'Jl. Kemang Raya No. 12, Jakarta',
            itemsCount: 1,
            qty: 1,
            unit: 'pcs',
            originalPrice: 15000,
            discountPrice: 10500,
            serviceFee: 1000,
            bookingCode: 'WL24520X',
            paymentMethod: 'Bayar saat ambil (Tunai / QRIS)',
            paymentStatus: 'Menunggu Pengambilan',
            freshness: 'Dipanggang hangat hari ini pukul 07.00 WIB',
            notes: 'Tunjukkan kode reservasi kepada kasir toko saat mengambil.',
            image: 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=400&q=80',
            gramsSaved: 150
        },
        {
            id: 2,
            title: 'Chicken Teriyaki Bowl',
            merchant: 'RiceBowl Kitchen',
            merchantVerified: true,
            merchantPhone: '+62 813-9876-5432',
            status: 'Akan Diambil',
            statusKey: 'Akan Diambil',
            dateLabel: 'Besok, 21 Mei 2025',
            pickupTime: '17.00 - 19.00',
            pickupAddress: 'Jl. Senopati No. 45, Jakarta',
            itemsCount: 1,
            qty: 1,
            unit: 'porsi',
            originalPrice: 28000,
            discountPrice: 18000,
            serviceFee: 1000,
            bookingCode: 'WL24521Y',
            paymentMethod: 'GoPay (Sudah Dibayar)',
            paymentStatus: 'Lunas',
            freshness: 'Dimasak segar hari ini dengan kemasan food grade',
            notes: 'Ambil di counter pick-up online dengan menyebutkan kode WL24521Y.',
            image: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=400&q=80',
            gramsSaved: 380
        }
    ],

    // History Reservations Data (Matching Attachment Screenshot)
    historyReservations: [
        {
            id: 3,
            title: 'Salmon Sushi Set',
            merchant: 'Sushi Daily',
            merchantVerified: true,
            status: 'Selesai',
            statusKey: 'Selesai',
            statusSubtext: 'Selesai diambil',
            dateLabel: '18 Mei 2025',
            pickupTime: '17.00 - 19.00',
            pickupAddress: 'Grand Indonesia Mall Lt. LG, Jakarta Pusat',
            itemsCount: 1,
            qty: 1,
            unit: 'set',
            originalPrice: 38000,
            discountPrice: 22000,
            bookingCode: 'WL24518S',
            completedAt: '18 Mei 2025 • 18.15 WIB',
            image: 'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?auto=format&fit=crop&w=300&q=80',
            gramsSaved: 320
        },
        {
            id: 4,
            title: 'Red Velvet Slice',
            merchant: 'Sweet Moments',
            merchantVerified: true,
            status: 'Dibatalkan',
            statusKey: 'Dibatalkan',
            statusSubtext: 'Dibatalkan',
            dateLabel: '17 Mei 2025',
            pickupTime: '18.00 - 20.00',
            pickupAddress: 'Jl. Gandaria I No. 8, Jakarta Selatan',
            itemsCount: 1,
            qty: 1,
            unit: 'slice',
            originalPrice: 20000,
            discountPrice: 13000,
            bookingCode: 'WL24517R',
            cancelReason: 'Waktu pickup terlewat oleh pengguna (dibatalkan otomatis oleh sistem)',
            image: 'https://images.unsplash.com/photo-1586788680434-30d324b2d46f?auto=format&fit=crop&w=300&q=80',
            gramsSaved: 0
        },
        {
            id: 5,
            title: 'Es Kopi Susu Gula Aren',
            merchant: 'Kopi Kita',
            merchantVerified: true,
            status: 'Selesai',
            statusKey: 'Selesai',
            statusSubtext: 'Selesai diambil',
            dateLabel: '15 Mei 2025',
            pickupTime: '17.00 - 19.00',
            pickupAddress: 'Jl. Tebet Raya No. 34, Jakarta Selatan',
            itemsCount: 1,
            qty: 1,
            unit: 'cup',
            originalPrice: 18000,
            discountPrice: 8000,
            bookingCode: 'WL24515K',
            completedAt: '15 Mei 2025 • 17.40 WIB',
            image: 'https://images.unsplash.com/photo-1517256064527-09c73fc73e38?auto=format&fit=crop&w=300&q=80',
            gramsSaved: 250
        }
    ],

    // Filtering logic
    get filteredActive() {
        if (this.activeTab === 'Semua' || this.activeTab === 'Akan Diambil') {
            return this.activeReservations;
        }
        return [];
    },

    get filteredHistory() {
        if (this.activeTab === 'Semua') {
            return this.historyReservations;
        }
        return this.historyReservations.filter(item => item.statusKey === this.activeTab);
    },

    get totalVisibleCount() {
        return this.filteredActive.length + this.filteredHistory.length;
    },

    openDetail(item) {
        this.selectedReservation = item;
        this.detailModalOpen = true;
    },

    closeDetail() {
        this.detailModalOpen = false;
        this.selectedReservation = null;
    },

    copyBookingCode(code) {
        navigator.clipboard.writeText(code);
        $dispatch('show-toast', { msg: `Kode reservasi ${code} berhasil disalin! 📋` });
    },

    formatRupiah(num) {
        return 'Rp ' + (num || 0).toLocaleString('id-ID');
    }
}" class="min-h-screen bg-[#FAF6F0] pb-24">

    <!-- 2. PAGE HEADER BANNER (Exact Mockup Match) -->
    <section class="w-full bg-[#FAF6F0] border-b border-stone-200/70 pt-8 pb-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                
                <!-- Left Title & Subtitle with Icon -->
                <div class="flex items-start gap-4">
                    <!-- Icon container matching mockup (Shopping bag with sprout inside warm rounded badge) -->
                    <div class="w-14 h-14 rounded-2xl bg-[#FEF3C7] border border-[#FDE68A] flex items-center justify-center text-[#D97706] shadow-xs shrink-0 mt-0.5">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v3m-1.5-1.5l3-1.5" />
                        </svg>
                    </div>

                    <div>
                        <!-- Title with 3 cute orange ray sparkles -->
                        <div class="inline-flex items-center gap-1.5 relative">
                            <h1 class="text-3xl sm:text-4xl font-extrabold text-[#1B5E3A] tracking-tight">
                                My Reservations
                            </h1>
                            <!-- Decorative Ray Sparkles (3 orange strokes) -->
                            <span class="inline-flex flex-col -mt-4 text-[#E67E22] font-black text-xs select-none">
                                <svg class="w-5 h-5 -rotate-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                    <line x1="5" y1="12" x2="2" y2="10" />
                                    <line x1="8" y1="8" x2="6" y2="4" />
                                    <line x1="12" y1="5" x2="13" y2="2" />
                                </svg>
                            </span>
                        </div>
                        <p class="text-sm text-stone-500 font-medium mt-1">
                            Lihat dan kelola semua reservasi makanan Anda.
                        </p>
                    </div>
                </div>

                <!-- Right Side Graphic Illustration (Bag + Croissant + Salad bowl matching mockup) -->
                <div class="hidden md:flex items-center justify-end relative pr-4">
                    <!-- Soft organic warm background blobs -->
                    <div class="absolute -top-6 -right-6 w-44 h-44 bg-[#FEF3C7]/60 rounded-full blur-2xl -z-10"></div>
                    <div class="absolute top-2 right-20 w-32 h-32 bg-[#DCF2E4]/60 rounded-full blur-xl -z-10"></div>

                    <div class="flex items-end gap-3 select-none">
                        <!-- Salad Bowl Graphic Container -->
                        <div class="w-16 h-16 rounded-2xl bg-white shadow-card p-2 border border-stone-200/80 flex items-center justify-center transform -rotate-6 hover:rotate-0 transition">
                            <img src="https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=140&q=80" 
                                 alt="Salad Bowl" 
                                 class="w-full h-full object-cover rounded-xl">
                        </div>

                        <!-- Center What's Left Eco Paper Bag -->
                        <div class="w-24 h-28 bg-[#1B5E3A] rounded-2xl shadow-floating p-3 flex flex-col justify-between items-center text-white transform hover:scale-105 transition duration-300 relative">
                            <!-- Bag handles -->
                            <div class="w-10 h-6 border-2 border-dashed border-[#F59E0B] rounded-t-full -mt-6 bg-[#FAF6F0]"></div>
                            <!-- Sprout Emblem -->
                            <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center my-auto">
                                <span class="text-lg">🌱</span>
                            </div>
                            <span class="text-[9px] font-black tracking-wider uppercase text-emerald-100">What's Left</span>
                        </div>

                        <!-- Golden Croissant Graphic Container -->
                        <div class="w-16 h-16 rounded-2xl bg-white shadow-card p-2 border border-stone-200/80 flex items-center justify-center transform rotate-6 hover:rotate-0 transition">
                            <img src="https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=140&q=80" 
                                 alt="Golden Croissant" 
                                 class="w-full h-full object-cover rounded-xl">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 3. STATUS FILTER TABS (Exact Mockup Match) -->
    <div class="w-full bg-[#FAF6F0] border-b border-stone-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-2 sm:gap-6 overflow-x-auto no-scrollbar py-1">
                <template x-for="tab in tabs" :key="tab">
                    <button type="button" 
                            @click="activeTab = tab"
                            class="relative py-3.5 px-3 text-sm font-bold whitespace-nowrap transition-colors duration-200"
                            :class="activeTab === tab ? 'text-[#1B5E3A]' : 'text-stone-500 hover:text-stone-900'">
                        <span x-text="tab"></span>
                        
                        <!-- Count badge helper for quick overview -->
                        <span x-show="tab === 'Semua'" class="ml-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold"
                              :class="activeTab === tab ? 'bg-[#1B5E3A] text-white' : 'bg-stone-200 text-stone-600'">
                            5
                        </span>
                        <span x-show="tab === 'Akan Diambil'" class="ml-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold"
                              :class="activeTab === tab ? 'bg-[#E67E22] text-white' : 'bg-amber-100 text-[#D97706]'">
                            2
                        </span>
                        
                        <!-- Active Indicator underline bar (Matching Mockup) -->
                        <span x-show="activeTab === tab" 
                              x-transition
                              class="absolute bottom-0 left-0 right-0 h-0.5 bg-[#1B5E3A] rounded-full"></span>
                    </button>
                </template>
            </div>
        </div>
    </div>

    <!-- 4. MAIN LAYOUT: TWO COLUMNS (Grid) -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- LEFT COLUMN: DAFTAR RESERVASI (lg:col-span-8) -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- BAGIAN 1: RESERVASI AKTIF -->
                <div x-show="filteredActive.length > 0" class="space-y-4">
                    <!-- Section Title Header -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-stone-900 font-bold text-base sm:text-lg">
                            <!-- Clock Icon in green -->
                            <svg class="w-5 h-5 text-[#1B5E3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Reservasi Aktif</span>
                        </div>
                        <span class="text-xs font-semibold text-[#1B5E3A] bg-[#DCF2E4] px-2.5 py-0.5 rounded-full">
                            <span x-text="filteredActive.length"></span> Pesanan Menunggu Diambil
                        </span>
                    </div>

                    <!-- Active Reservation Cards Loop -->
                    <div class="space-y-4">
                        <template x-for="item in filteredActive" :key="item.id">
                            <div class="bg-white rounded-2xl p-4 sm:p-5 border border-stone-200/90 shadow-card hover:shadow-lg hover:border-[#1B5E3A]/40 transition duration-200 relative group">
                                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                    
                                    <!-- Left Section: Image + Details -->
                                    <div class="flex items-start gap-4 w-full sm:w-auto">
                                        <!-- Food Thumbnail Image -->
                                        <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl overflow-hidden shrink-0 border border-stone-100 shadow-xs relative">
                                            <img :src="item.image" 
                                                 :alt="item.title" 
                                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                        </div>

                                        <!-- Meta Content -->
                                        <div class="space-y-1.5 flex-1 min-w-0">
                                            <!-- Status Badge "Akan Diambil" (Warm Orange) -->
                                            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[#FEF3C7] text-[#D97706] text-xs font-bold border border-[#FDE68A]">
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#D97706] animate-pulse"></span>
                                                <span x-text="item.status">Akan Diambil</span>
                                            </div>

                                            <!-- Merchant Name with Verified Badge -->
                                            <div class="flex items-center gap-1.5 text-xs font-semibold text-stone-600">
                                                <span x-text="item.merchant"></span>
                                                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                            </div>

                                            <!-- Food Item Title -->
                                            <h3 class="text-base sm:text-lg font-bold text-stone-900 truncate" x-text="item.title"></h3>

                                            <!-- Pickup Schedule & Location with SVG Icons -->
                                            <div class="flex flex-wrap items-center gap-y-1 gap-x-3 text-xs text-stone-500 pt-0.5">
                                                <!-- Calendar Icon & Date -->
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <span class="font-medium text-stone-700" x-text="item.dateLabel"></span>
                                                </div>

                                                <!-- Clock Icon & Pickup Window -->
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span class="font-bold text-[#1B5E3A]" x-text="item.pickupTime"></span>
                                                </div>

                                                <!-- Pin Icon & Location -->
                                                <div class="flex items-center gap-1 truncate max-w-[240px]">
                                                    <svg class="w-3.5 h-3.5 text-stone-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    </svg>
                                                    <span class="truncate" x-text="item.pickupAddress"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Section: Price, Code, and Action Button -->
                                    <div class="flex sm:flex-col items-center sm:items-end justify-between sm:justify-center w-full sm:w-auto pt-3 sm:pt-0 border-t sm:border-t-0 border-stone-100 gap-2 shrink-0">
                                        <!-- Item Count & Qty -->
                                        <span class="text-xs text-stone-400 font-medium">
                                            <span x-text="item.itemsCount"></span> Item • Qty: <span x-text="item.qty"></span>
                                        </span>

                                        <!-- Price (Rp 10.500 / Rp 18.000) -->
                                        <div class="text-lg sm:text-xl font-black text-[#E67E22]" x-text="formatRupiah(item.discountPrice)">
                                        </div>

                                        <!-- Booking Code -->
                                        <div class="text-xs font-mono font-semibold text-stone-500">
                                            Kode: <span class="text-stone-800 font-bold" x-text="item.bookingCode"></span>
                                        </div>

                                        <!-- Action Button: "Lihat Detail" -->
                                        <button type="button" 
                                                @click="openDetail(item)"
                                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs sm:text-sm font-bold text-[#1B5E3A] border border-[#1B5E3A] hover:bg-[#1B5E3A] hover:text-white transition duration-200 mt-1 shadow-xs">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Lihat Detail
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- BAGIAN 2: RIWAYAT RESERVASI (Matching Mockup Screenshot) -->
                <div x-show="filteredHistory.length > 0" class="space-y-4">
                    <!-- Section Title Header -->
                    <div class="flex items-center gap-2 text-stone-900 font-bold text-base sm:text-lg pt-2">
                        <!-- History Refresh Icon in green -->
                        <svg class="w-5 h-5 text-[#1B5E3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>Riwayat Reservasi</span>
                    </div>

                    <!-- History List Card Container -->
                    <div class="bg-white rounded-2xl border border-stone-200/90 shadow-card divide-y divide-stone-100 overflow-hidden">
                        <template x-for="item in filteredHistory" :key="item.id">
                            <div @click="openDetail(item)"
                                 class="p-4 sm:p-5 hover:bg-stone-50/80 transition duration-150 cursor-pointer flex items-center justify-between gap-4 group">
                                
                                <!-- Left Content -->
                                <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                                    <!-- Image Thumbnail -->
                                    <div class="w-16 h-16 sm:w-18 sm:h-18 rounded-xl overflow-hidden shrink-0 border border-stone-100 shadow-xs">
                                        <img :src="item.image" 
                                             :alt="item.title" 
                                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                    </div>

                                    <!-- Texts -->
                                    <div class="space-y-1 min-w-0">
                                        <!-- Status Badge -->
                                        <div class="inline-block">
                                            <span x-show="item.status === 'Selesai'" 
                                                  class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#DCFCE7] text-[#166534] border border-[#BBF7D0]">
                                                Selesai
                                            </span>
                                            <span x-show="item.status === 'Dibatalkan'" 
                                                  class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#FEE2E2] text-[#DC2626] border border-[#FECACA]">
                                                Dibatalkan
                                            </span>
                                        </div>

                                        <!-- Merchant Name + Verified -->
                                        <div class="flex items-center gap-1 text-xs font-semibold text-stone-500">
                                            <span x-text="item.merchant"></span>
                                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        </div>

                                        <!-- Title -->
                                        <h4 class="text-sm sm:text-base font-bold text-stone-900 group-hover:text-[#1B5E3A] transition truncate" x-text="item.title"></h4>

                                        <!-- Date & Time Subtext -->
                                        <p class="text-xs text-stone-400">
                                            <span x-text="item.dateLabel"></span> • <span x-text="item.pickupTime"></span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Right Content: Price & Status Subtext & Arrow Chevron -->
                                <div class="flex items-center gap-3 sm:gap-4 shrink-0">
                                    <div class="text-right">
                                        <div class="text-sm sm:text-base font-black text-stone-900" x-text="formatRupiah(item.discountPrice)"></div>
                                        <div class="text-xs font-medium" 
                                             :class="item.status === 'Selesai' ? 'text-emerald-600' : 'text-red-500'" 
                                             x-text="item.statusSubtext"></div>
                                    </div>
                                    <svg class="w-5 h-5 text-stone-300 group-hover:text-[#1B5E3A] group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>

                            </div>
                        </template>
                    </div>
                </div>

                <!-- EMPTY STATE (If tab has no reservations) -->
                <div x-show="totalVisibleCount === 0" 
                     class="bg-white rounded-2xl p-12 text-center border border-stone-200/90 shadow-card space-y-4">
                    <div class="w-16 h-16 rounded-full bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center mx-auto text-2xl">
                        🍃
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-stone-900">Belum Ada Reservasi</h4>
                        <p class="text-xs text-stone-500 mt-1 max-w-sm mx-auto">
                            Tidak ditemukan pesanan pada kategori <span class="font-bold text-stone-700" x-text="activeTab"></span>. Ayo temukan makanan lezat dengan harga hemat!
                        </p>
                    </div>
                    <button type="button" 
                            @click="activeTab = 'Semua'"
                            class="px-5 py-2.5 rounded-xl bg-[#1B5E3A] text-white text-xs font-bold hover:bg-[#14472C] transition shadow-sm">
                        Tampilkan Semua Reservasi
                    </button>
                </div>

            </div>

            <!-- RIGHT COLUMN: SIDEBAR PENDUKUNG (lg:col-span-4) (Exact Mockup Match) -->
            <aside class="lg:col-span-4 space-y-6">
                
                <!-- CARD 1: PENTING DIPERHATIKAN! (Warm Amber Tint) -->
                <div class="bg-[#FEF9ED] rounded-2xl p-5 border border-[#FDE68A] shadow-xs space-y-4">
                    <!-- Header with Icon -->
                    <div class="flex items-center gap-2 text-stone-900 font-extrabold text-sm sm:text-base">
                        <div class="w-7 h-7 rounded-lg bg-[#FEF3C7] text-[#D97706] flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <span>Penting Diperhatikan!</span>
                    </div>

                    <!-- 3 Important Bullet Points Matching Mockup -->
                    <div class="space-y-3.5 text-xs">
                        <!-- Point 1 -->
                        <div class="flex items-start gap-2.5">
                            <div class="w-5 h-5 rounded-md bg-[#FEF3C7] text-[#D97706] flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-stone-900 leading-tight">Ambil pesananmu sesuai waktu yang dipilih.</p>
                                <p class="text-stone-600 mt-0.5 leading-relaxed">Pesanan yang tidak diambil tepat waktu dapat dibatalkan otomatis.</p>
                            </div>
                        </div>

                        <!-- Point 2 -->
                        <div class="flex items-start gap-2.5">
                            <div class="w-5 h-5 rounded-md bg-[#FEF3C7] text-[#D97706] flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-stone-900 leading-tight">Tunjukkan kode reservasi ke merchant saat pengambilan.</p>
                                <p class="text-stone-600 mt-0.5 leading-relaxed">Kode dapat dilihat di detail reservasi.</p>
                            </div>
                        </div>

                        <!-- Point 3 -->
                        <div class="flex items-start gap-2.5">
                            <div class="w-5 h-5 rounded-md bg-[#FEF3C7] text-[#D97706] flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-stone-900 leading-tight">Makanan tetap terbaik jika segera diambil dan dikonsumsi.</p>
                                <p class="text-stone-600 mt-0.5 leading-relaxed">Terima kasih sudah membantu kurangi food waste! 💚</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD 2: DAMPAKMU HARI INI 🌱 (Soft Green Mint Tint) -->
                <div class="bg-[#F0FDF4] rounded-2xl p-5 border border-[#DCF2E4] shadow-xs space-y-4">
                    <div>
                        <div class="flex items-center gap-1.5 text-stone-900 font-extrabold text-sm sm:text-base">
                            <span>Dampakmu Hari Ini</span>
                            <span class="text-emerald-600">🌱</span>
                        </div>
                        <p class="text-xs text-stone-500 mt-0.5">
                            Terima kasih sudah menjadi bagian dari perubahan!
                        </p>
                    </div>

                    <!-- 2 Side-by-Side Metric Boxes Matching Mockup -->
                    <div class="grid grid-cols-2 gap-3">
                        <!-- Box 1: Pesanan Diselamatkan -->
                        <div class="bg-white rounded-xl p-3 border border-emerald-100 flex items-center gap-2.5 shadow-2xs">
                            <div class="w-8 h-8 rounded-lg bg-amber-100/70 text-amber-700 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <div class="text-lg font-black text-stone-900 leading-none">3</div>
                                <div class="text-[10px] text-stone-500 font-medium leading-tight mt-1">Pesanan Diselamatkan</div>
                            </div>
                        </div>

                        <!-- Box 2: Food Waste Dihindari -->
                        <div class="bg-white rounded-xl p-3 border border-emerald-100 flex items-center gap-2.5 shadow-2xs">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <div class="text-base font-black text-[#1B5E3A] leading-none">4.250 g</div>
                                <div class="text-[10px] text-stone-500 font-medium leading-tight mt-1">Food Waste Dihindari</div>
                            </div>
                        </div>
                    </div>

                    <!-- Link to Extended Impact -->
                    <button type="button" 
                            @click="showToast('Fitur Laporan Dampak Lingkungan lengkap akan segera hadir!')"
                            class="w-full text-center text-xs font-bold text-[#1B5E3A] hover:underline flex items-center justify-center gap-1 pt-1 transition">
                        <span>Lihat dampak lebih lengkap</span>
                        <span>→</span>
                    </button>
                </div>

                <!-- CARD 3: BUTUH BANTUAN? (White Container) -->
                <div class="bg-white rounded-2xl p-5 border border-stone-200/90 shadow-card space-y-4">
                    <div>
                        <h4 class="text-sm sm:text-base font-extrabold text-stone-900">Butuh Bantuan?</h4>
                        <p class="text-xs text-stone-500 mt-0.5">Kami siap membantu!</p>
                    </div>

                    <!-- Button "Chat dengan CS" with Chat Bubble Icon -->
                    <button type="button" 
                            @click="csModalOpen = true"
                            class="w-full py-2.5 px-4 rounded-xl border border-stone-300 hover:border-[#1B5E3A] text-[#1B5E3A] hover:bg-[#EBF5F0]/70 font-bold text-xs sm:text-sm flex items-center justify-center gap-2 transition duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <span>Chat dengan CS</span>
                    </button>

                    <!-- Link "Pusat Bantuan" with Right Chevron -->
                    <div @click="helpCenterModalOpen = true"
                         class="pt-3 border-t border-stone-100 flex items-center justify-between text-xs font-semibold text-stone-700 hover:text-[#1B5E3A] cursor-pointer transition">
                        <div class="flex items-center gap-2">
                            <div class="w-5 h-5 rounded-full bg-stone-100 text-stone-600 flex items-center justify-center text-[10px] font-bold">
                                ?
                            </div>
                            <span>Pusat Bantuan</span>
                        </div>
                        <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>

            </aside>

        </div>
    </main>

    <!-- 5. INTERACTIVE MODAL: DETAIL RESERVASI LENGKAP -->
    <div x-cloak x-show="detailModalOpen" 
         class="fixed inset-0 z-50 overflow-y-auto"
         role="dialog" 
         aria-modal="true">
        
        <!-- Backdrop Blur -->
        <div x-show="detailModalOpen" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-stone-900/60 backdrop-blur-xs transition-opacity"
             @click="closeDetail()"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="detailModalOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-stone-200"
                 @click.outside="closeDetail()">
                
                <template x-if="selectedReservation">
                    <div class="p-6 space-y-6">
                        
                        <!-- Top Header: Title + Close Button -->
                        <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                            <div>
                                <h3 class="text-lg font-black text-stone-900">Detail Reservasi Makanan</h3>
                                <p class="text-xs text-stone-500" x-text="'Dibuat untuk pengambilan di ' + selectedReservation.merchant"></p>
                            </div>
                            <button type="button" 
                                    @click="closeDetail()"
                                    class="w-8 h-8 rounded-full bg-stone-100 text-stone-500 hover:bg-stone-200 flex items-center justify-center transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Highlight Kode Booking Box (Barcode / QR Simulator) -->
                        <div class="bg-[#FAF6F0] rounded-2xl p-4 border border-[#FDE68A] text-center space-y-2">
                            <span class="text-[11px] font-bold text-stone-500 uppercase tracking-wider">Kode Reservasi Pengambilan</span>
                            
                            <div class="flex items-center justify-center gap-2">
                                <span class="text-2xl sm:text-3xl font-black font-mono tracking-widest text-[#1B5E3A]" 
                                      x-text="selectedReservation.bookingCode"></span>
                                <button type="button" 
                                        @click="copyBookingCode(selectedReservation.bookingCode)"
                                        class="p-1.5 rounded-lg bg-white border border-stone-200 text-stone-600 hover:text-[#1B5E3A] hover:bg-emerald-50 transition" 
                                        title="Salin Kode">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Mock Barcode graphic lines -->
                            <div class="flex justify-center items-center gap-1 pt-1 opacity-75">
                                <div class="w-1 h-8 bg-stone-800"></div>
                                <div class="w-2 h-8 bg-stone-800"></div>
                                <div class="w-0.5 h-8 bg-stone-800"></div>
                                <div class="w-1.5 h-8 bg-stone-800"></div>
                                <div class="w-0.5 h-8 bg-stone-800"></div>
                                <div class="w-2 h-8 bg-stone-800"></div>
                                <div class="w-1 h-8 bg-stone-800"></div>
                                <div class="w-0.5 h-8 bg-stone-800"></div>
                                <div class="w-2.5 h-8 bg-stone-800"></div>
                                <div class="w-1 h-8 bg-stone-800"></div>
                                <div class="w-0.5 h-8 bg-stone-800"></div>
                                <div class="w-2 h-8 bg-stone-800"></div>
                            </div>
                            <p class="text-[10px] text-stone-400">Tunjukkan barcode atau kode ini kepada kasir merchant saat tiba di lokasi</p>
                        </div>

                        <!-- Item Detail Card -->
                        <div class="flex items-center gap-3 p-3 bg-white rounded-xl border border-stone-100">
                            <img :src="selectedReservation.image" :alt="selectedReservation.title" class="w-16 h-16 rounded-xl object-cover shrink-0">
                            <div class="min-w-0 flex-1">
                                <h4 class="font-bold text-stone-900 text-sm truncate" x-text="selectedReservation.title"></h4>
                                <p class="text-xs text-stone-500" x-text="selectedReservation.merchant"></p>
                                <div class="text-xs font-black text-[#E67E22] mt-0.5" x-text="formatRupiah(selectedReservation.discountPrice)"></div>
                            </div>
                            <div class="text-right text-xs">
                                <span class="px-2 py-0.5 rounded-full font-bold"
                                      :class="{
                                          'bg-[#FEF3C7] text-[#D97706]': selectedReservation.status === 'Akan Diambil',
                                          'bg-[#DCFCE7] text-[#166534]': selectedReservation.status === 'Selesai',
                                          'bg-[#FEE2E2] text-[#DC2626]': selectedReservation.status === 'Dibatalkan'
                                      }"
                                      x-text="selectedReservation.status"></span>
                            </div>
                        </div>

                        <!-- Pickup Location & Time Details -->
                        <div class="space-y-2.5 text-xs">
                            <div class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-[#1B5E3A] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <span class="font-bold text-stone-900">Jadwal Pengambilan:</span>
                                    <p class="text-stone-600" x-text="selectedReservation.dateLabel + ' • Pukul ' + selectedReservation.pickupTime"></p>
                                </div>
                            </div>

                            <div class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-[#1B5E3A] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                <div>
                                    <span class="font-bold text-stone-900">Alamat Merchant:</span>
                                    <p class="text-stone-600" x-text="selectedReservation.pickupAddress"></p>
                                </div>
                            </div>

                            <!-- Food waste rescue impact -->
                            <div class="flex items-center gap-2 p-2.5 rounded-xl bg-[#EBF5F0] text-[#1B5E3A] font-semibold text-xs">
                                <span>🌱</span>
                                <span>Kontribusi Penyelamatan: Menghindari ~<span x-text="selectedReservation.gramsSaved || 200"></span> gram potensi food waste!</span>
                            </div>
                        </div>

                        <!-- Price Breakdown -->
                        <div class="p-3 bg-stone-50 rounded-xl space-y-1.5 text-xs border border-stone-200/60">
                            <div class="flex justify-between text-stone-600">
                                <span>Harga Makanan Surplus</span>
                                <span class="font-semibold text-stone-800" x-text="formatRupiah(selectedReservation.discountPrice)"></span>
                            </div>
                            <div class="flex justify-between text-stone-600">
                                <span>Biaya Layanan Aplikasi</span>
                                <span class="font-semibold text-stone-800">Rp 1.000</span>
                            </div>
                            <div class="border-t border-stone-200 my-1 pt-1 flex justify-between font-bold text-stone-900 text-sm">
                                <span>Total Pembayaran</span>
                                <span class="text-[#E67E22]" x-text="formatRupiah(selectedReservation.discountPrice + 1000)"></span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="grid grid-cols-2 gap-3 pt-2">
                            <button type="button" 
                                    @click="showToast('Membuka rute ke ' + selectedReservation.pickupAddress)"
                                    class="py-2.5 px-4 rounded-xl border border-stone-300 text-stone-700 font-bold text-xs hover:bg-stone-50 transition flex items-center justify-center gap-1.5">
                                <span>🗺️ Buka Peta</span>
                            </button>
                            <button type="button" 
                                    @click="closeDetail()"
                                    class="py-2.5 px-4 rounded-xl bg-[#1B5E3A] text-white font-bold text-xs hover:bg-[#14472C] transition shadow-xs">
                                Tutup
                            </button>
                        </div>

                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- 6. MODAL: CHAT DENGAN CS -->
    <div x-cloak x-show="csModalOpen" 
         class="fixed inset-0 z-50 overflow-y-auto"
         role="dialog" 
         aria-modal="true">
        <div class="fixed inset-0 bg-stone-900/60 backdrop-blur-xs transition-opacity" @click="csModalOpen = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-3xl bg-white p-6 shadow-2xl transition-all sm:w-full sm:max-w-md border border-stone-200 space-y-4"
                 @click.outside="csModalOpen = false">
                
                <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center font-bold">
                            CS
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-stone-900">What's Left Support Care</h4>
                            <span class="text-[10px] text-emerald-600 font-semibold flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Online 24/7
                            </span>
                        </div>
                    </div>
                    <button type="button" @click="csModalOpen = false" class="text-stone-400 hover:text-stone-700">✕</button>
                </div>

                <div class="bg-stone-50 rounded-2xl p-4 text-xs space-y-2 text-stone-600">
                    <p class="font-bold text-stone-900">Halo, Jasmine! 👋</p>
                    <p>Ada kendala dengan pengambilan makanan surplus Anda? Tim customer support kami siap membantu pengecekan status ke pihak merchant.</p>
                </div>

                <div class="space-y-2">
                    <button type="button" 
                            @click="showToast('Menghubungkan ke WhatsApp CS Resmi What\'s Left...'); csModalOpen = false"
                            class="w-full py-2.5 px-4 rounded-xl bg-[#25D366] hover:bg-[#20ba5a] text-white font-bold text-xs flex items-center justify-center gap-2 transition shadow-xs">
                        <span>💬 Hubungi via WhatsApp</span>
                    </button>
                    <button type="button" 
                            @click="showToast('Live chat agent akan merespon dalam 1 menit'); csModalOpen = false"
                            class="w-full py-2.5 px-4 rounded-xl border border-[#1B5E3A] text-[#1B5E3A] font-bold text-xs hover:bg-[#EBF5F0] transition">
                        <span>Mulai Live Chat di Aplikasi</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 7. MODAL: PUSAT BANTUAN -->
    <div x-cloak x-show="helpCenterModalOpen" 
         class="fixed inset-0 z-50 overflow-y-auto"
         role="dialog" 
         aria-modal="true">
        <div class="fixed inset-0 bg-stone-900/60 backdrop-blur-xs transition-opacity" @click="helpCenterModalOpen = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-3xl bg-white p-6 shadow-2xl transition-all sm:w-full sm:max-w-lg border border-stone-200 space-y-4"
                 @click.outside="helpCenterModalOpen = false">
                
                <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                    <h4 class="text-base font-bold text-stone-900">Pusat Bantuan & Tanya Jawab</h4>
                    <button type="button" @click="helpCenterModalOpen = false" class="text-stone-400 hover:text-stone-700">✕</button>
                </div>

                <div class="space-y-3 text-xs text-stone-700">
                    <div class="p-3 bg-stone-50 rounded-xl border border-stone-100">
                        <p class="font-bold text-stone-900">Bagaimana cara mengambil pesanan?</p>
                        <p class="text-stone-600 mt-1">Kunjungi merchant sesuai jadwal waktu pickup yang tertera, lalu tunjukkan Kode Reservasi unik (misal: WL24520X) ke kasir.</p>
                    </div>

                    <div class="p-3 bg-stone-50 rounded-xl border border-stone-100">
                        <p class="font-bold text-stone-900">Apa yang terjadi jika saya terlambat mengambil?</p>
                        <p class="text-stone-600 mt-1">Merchant memiliki batas toleransi waktu 30 menit. Jika lewat jam operasional toko, pesanan otomatis berstatus Dibatalkan.</p>
                    </div>

                    <div class="p-3 bg-stone-50 rounded-xl border border-stone-100">
                        <p class="font-bold text-stone-900">Apakah makanan surplus terjamin aman dan higienis?</p>
                        <p class="text-stone-600 mt-1">Pasti! Semua merchant mitra What's Left wajib menandatangani standar higienitas. Makanan surplus adalah makanan layak konsumsi berlebih pada hari produksi yang sama.</p>
                    </div>
                </div>

                <button type="button" 
                        @click="helpCenterModalOpen = false"
                        class="w-full py-2.5 rounded-xl bg-[#1B5E3A] text-white font-bold text-xs hover:bg-[#14472C] transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
