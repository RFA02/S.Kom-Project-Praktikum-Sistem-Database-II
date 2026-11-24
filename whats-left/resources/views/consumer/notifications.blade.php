@extends('layouts.main')

@section('title', 'Notifikasi - What\'s Left')

@section('header')
<!-- ========================================================================= -->
<!-- NAVBAR KONSUMEN (SESUAI MOCKUP ATTACHMENT)                                -->
<!-- ========================================================================= -->
<header class="sticky top-0 z-40 w-full bg-white/95 backdrop-blur-md border-b border-stone-200/80 transition-all duration-200 shadow-xs"
        x-data="{
            profileDropdown: false,
            locationDropdown: false,
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
                <!-- Small sparkle decoration -->
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
            <a href="{{ url('/?view=explore') }}" class="py-2 text-sm font-semibold text-stone-700 hover:text-[#1B5E3A] transition-colors">
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

        <!-- Right Side: Location Pill, Notifications, & User Profile -->
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
                                @click="selectedCity = city; locationDropdown = false; showToast('Lokasi diubah ke ' + city)"
                                class="w-full text-left px-3.5 py-2 text-xs font-medium text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A] flex items-center justify-between transition">
                            <span x-text="city"></span>
                            <span x-show="selectedCity === city" class="text-[#1B5E3A] font-bold">✓</span>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Active Notification Bell with Orange Badge (Count: 3) -->
            <a href="{{ url('/?view=notifications') }}" 
               class="w-10 h-10 rounded-full flex items-center justify-center text-[#1B5E3A] bg-[#EBF5F0] hover:bg-[#DCF2E4] transition relative"
               title="Notifikasi Anda">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <!-- Reactive Orange Badge from Alpine parent or static fallback -->
                <span x-show="unreadCount > 0"
                      x-text="unreadCount"
                      class="absolute top-1.5 right-1.5 min-w-4 h-4 px-1 rounded-full bg-[#E67E22] text-white text-[10px] font-bold flex items-center justify-center shadow-xs">
                    3
                </span>
            </a>

            <!-- User Profile Avatar & Greeting: "Hai, Jasmine" -->
            <div class="relative" @click.outside="profileDropdown = false">
                <button type="button" 
                        @click="profileDropdown = !profileDropdown"
                        class="flex items-center gap-2.5 p-1 rounded-full hover:bg-stone-100 transition duration-150">
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
                        <p class="text-xs text-stone-500">Masuk sebagai</p>
                        <p class="text-sm font-bold text-stone-900 truncate">Jasmine Salsabilla</p>
                        <span class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#EBF5F0] text-[#1B5E3A]">
                            🌱 Food Rescue Hero
                        </span>
                    </div>
                    <div class="py-1">
                        <a href="{{ url('/?view=my-reservations') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A] transition">
                            <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            Reservasi Saya
                        </a>
                        <a href="{{ url('/?view=notifications') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-[#1B5E3A] bg-[#EBF5F0]/60 transition">
                            <svg class="w-4 h-4 text-[#1B5E3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            Notifikasi
                        </a>
                        <a href="{{ url('/?view=dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A] transition">
                            <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            Portal Merchant / Bisnis
                        </a>
                    </div>
                    <div class="border-t border-stone-100 pt-1">
                        <a href="{{ url('/?view=landing') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-rose-600 hover:bg-rose-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Keluar
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>
</header>
@endsection

@section('content')
<div x-data="{
    // Filter Kategori Aktif
    activeCategory: 'all', // 'all', 'reservasi', 'pengambilan', 'promo', 'sistem'
    
    // Paginasi
    currentPage: 1,
    itemsPerPage: 6,

    // Modal Detail Notifikasi
    detailModalOpen: false,
    selectedNotification: null,

    // Data Notifikasi (12 Data Statis Sesuai Distribusi Counter Mockup)
    notifications: [
        // --- ITEM 1 (Halaman 1) ---
        {
            id: 1,
            category: 'pengambilan',
            title: 'Pesananmu siap diambil!',
            description: 'Croissant Butter dari Sunny Bakery sudah siap diambil.',
            time: '2 menit yang lalu',
            timestamp: '21 Mei 2025, 17.58',
            unread: true,
            iconType: 'bag-check',
            hasAction: true,
            actionLabel: 'Lihat Detail',
            details: {
                bookingCode: 'WL-892110',
                merchant: 'Sunny Bakery',
                merchantAddress: 'Jl. Kemang Raya No. 12, Jakarta Selatan',
                item: 'Croissant Butter',
                quantity: '2 pcs',
                pickupWindow: 'Hari ini, 18.00 - 20.00 WIB',
                total: 21000,
                paymentMethod: 'GoPay',
                status: 'Siap Diambil'
            }
        },
        // --- ITEM 2 (Halaman 1) ---
        {
            id: 2,
            category: 'pengambilan',
            title: 'Jangan lupa ambil pesananmu!',
            description: 'Pesanan Chicken Teriyaki Bowl dapat diambil hingga hari ini, 21 Mei 2025 pukul 19.00.',
            time: '45 menit yang lalu',
            timestamp: '21 Mei 2025, 17.15',
            unread: true,
            iconType: 'clock',
            hasAction: false,
            details: null
        },
        // --- ITEM 3 (Halaman 1) ---
        {
            id: 3,
            category: 'promo',
            title: 'Promo spesial untukmu!',
            description: 'Diskon 20% untuk semua makanan roti & kue. Gunakan kode: BREAD20 Berlaku hingga 25 Mei 2025.',
            time: '3 jam yang lalu',
            timestamp: '21 Mei 2025, 15.00',
            unread: true,
            iconType: 'discount',
            hasAction: false,
            details: null
        },
        // --- ITEM 4 (Halaman 1) ---
        {
            id: 4,
            category: 'reservasi',
            title: 'Reservasi baru berhasil!',
            description: 'Kamu berhasil memesan Mix Donut Box (6pcs) dari Donut Delight.',
            time: '5 jam yang lalu',
            timestamp: '21 Mei 2025, 13.00',
            unread: true,
            iconType: 'bag',
            hasAction: true,
            actionLabel: 'Lihat Detail',
            details: {
                bookingCode: 'WL-541290',
                merchant: 'Donut Delight',
                merchantAddress: 'Jl. Surya Sumantri No. 45, Bandung',
                item: 'Mix Donut Box (6pcs)',
                quantity: '1 box',
                pickupWindow: 'Hari ini, 18.00 - 20.30 WIB',
                total: 35000,
                paymentMethod: 'DANA',
                status: 'Dikonfirmasi'
            }
        },
        // --- ITEM 5 (Halaman 1) ---
        {
            id: 5,
            category: 'sistem',
            title: 'Terima kasih telah menyelamatkan makanan!',
            description: 'Kamu sudah membantu mengurangi food waste. Terus jadi bagian dari perubahan! 🌱💚',
            time: 'Kemarin, 10.30',
            timestamp: '20 Mei 2025, 10.30',
            unread: false,
            iconType: 'sprout',
            hasAction: false,
            details: null
        },
        // --- ITEM 6 (Halaman 1) ---
        {
            id: 6,
            category: 'reservasi',
            title: 'Pesanan telah selesai',
            description: 'Red Velvet Slice dari Sweet Moments telah selesai diambil. Terima kasih!',
            time: '18 Mei 2025, 20.15',
            timestamp: '18 Mei 2025, 20.15',
            unread: false,
            iconType: 'cake',
            hasAction: false,
            details: null
        },
        // --- ITEM 7 (Halaman 2) ---
        {
            id: 7,
            category: 'reservasi',
            title: 'Reservasi Dikonfirmasi Restoran',
            description: 'Pasta La Vista telah menyiapkan reservasi Spaghetti Bolognese untuk diambil tepat waktu.',
            time: '17 Mei 2025, 18.00',
            timestamp: '17 Mei 2025, 18.00',
            unread: false,
            iconType: 'bag',
            hasAction: true,
            actionLabel: 'Lihat Detail',
            details: {
                bookingCode: 'WL-392182',
                merchant: 'Pasta La Vista',
                merchantAddress: 'Jl. Riau No. 88, Bandung',
                item: 'Spaghetti Bolognese',
                quantity: '2 porsi',
                pickupWindow: '17 Mei 2025, 19.00 - 21.00 WIB',
                total: 41000,
                paymentMethod: 'ShopeePay',
                status: 'Selesai'
            }
        },
        // --- ITEM 8 (Halaman 2) ---
        {
            id: 8,
            category: 'reservasi',
            title: 'Pembayaran Reservasi Berhasil',
            description: 'Pembayaran reservasi WL-392182 telah terverifikasi otomatis. Kode booking telah aktif.',
            time: '17 Mei 2025, 17.50',
            timestamp: '17 Mei 2025, 17.50',
            unread: false,
            iconType: 'bag',
            hasAction: false,
            details: null
        },
        // --- ITEM 9 (Halaman 2) ---
        {
            id: 9,
            category: 'promo',
            title: 'Flash Sale Akhir Pekan: Hemat Hingga 40%',
            description: 'Jelajahi pilihan pastry dan makanan hangat favoritmu di area Kemang & Senopati dengan diskon ekstra!',
            time: '16 Mei 2025, 16.00',
            timestamp: '16 Mei 2025, 16.00',
            unread: false,
            iconType: 'discount',
            hasAction: false,
            details: null
        },
        // --- ITEM 10 (Halaman 2) ---
        {
            id: 10,
            category: 'reservasi',
            title: 'Pesanan Telah Selesai',
            description: 'Sushi Set Salmon di Sushi Sato telah diambil. Terima kasih telah mendukung gerakan anti food-waste!',
            time: '14 Mei 2025, 20.45',
            timestamp: '14 Mei 2025, 20.45',
            unread: false,
            iconType: 'cake',
            hasAction: false,
            details: null
        },
        // --- ITEM 11 (Halaman 2) ---
        {
            id: 11,
            category: 'reservasi',
            title: 'Pengingat Pengambilan Makanan',
            description: 'Batas pengambilan Bento Beef Teriyaki di Kedai Oishii tersisa 45 menit lagi.',
            time: '12 Mei 2025, 19.15',
            timestamp: '12 Mei 2025, 19.15',
            unread: false,
            iconType: 'clock',
            hasAction: false,
            details: null
        },
        // --- ITEM 12 (Halaman 2) ---
        {
            id: 12,
            category: 'reservasi',
            title: 'Reservasi Baru Berhasil!',
            description: 'Kamu berhasil mereservasi 2 porsi Nasi Gudeg Spesial dari Warung Bu Tjitro.',
            time: '10 Mei 2025, 17.00',
            timestamp: '10 Mei 2025, 17.00',
            unread: false,
            iconType: 'bag',
            hasAction: true,
            actionLabel: 'Lihat Detail',
            details: {
                bookingCode: 'WL-129088',
                merchant: 'Warung Bu Tjitro',
                merchantAddress: 'Jl. Malioboro No. 23, Yogyakarta',
                item: 'Nasi Gudeg Spesial',
                quantity: '2 porsi',
                pickupWindow: '10 Mei 2025, 18.30 - 20.00 WIB',
                total: 30000,
                paymentMethod: 'BCA Virtual Account',
                status: 'Selesai'
            }
        }
    ],

    // Helper Hitung Counter Kategori
    getCount(cat) {
        if (cat === 'all') return this.notifications.length;
        return this.notifications.filter(n => n.category === cat).length;
    },

    // Hitung Jumlah Belum Dibaca
    get unreadCount() {
        return this.notifications.filter(n => n.unread).length;
    },

    // Filter Notifikasi Berdasarkan Kategori
    get filteredNotifications() {
        if (this.activeCategory === 'all') {
            return this.notifications;
        }
        return this.notifications.filter(n => n.category === this.activeCategory);
    },

    // Total Halaman Berdasarkan Data yang Difilter
    get totalPages() {
        return Math.max(1, Math.ceil(this.filteredNotifications.length / this.itemsPerPage));
    },

    // Data yang Ditampilkan Sesuai Halaman Paginasi Aktif
    get paginatedNotifications() {
        const start = (this.currentPage - 1) * this.itemsPerPage;
        return this.filteredNotifications.slice(start, start + this.itemsPerPage);
    },

    // Pindah Kategori Filter
    setCategory(cat) {
        this.activeCategory = cat;
        this.currentPage = 1;
    },

    // Pindah Halaman
    goToPage(page) {
        if (page >= 1 && page <= this.totalPages) {
            this.currentPage = page;
            window.scrollTo({ top: 120, behavior: 'smooth' });
        }
    },

    // Aksi: Tandai Semua Sebagai Dibaca
    markAllAsRead() {
        this.notifications.forEach(item => { item.unread = false; });
        $dispatch('show-toast', {
            msg: '✓ Semua notifikasi telah ditandai sebagai dibaca.'
        });
    },

    // Aksi: Klik Notifikasi Satuan untuk Menandai Dibaca
    markAsRead(item) {
        item.unread = false;
    },

    // Aksi: Buka Modal Detail
    openDetail(item) {
        this.markAsRead(item);
        if (item.details) {
            this.selectedNotification = item;
            this.detailModalOpen = true;
        } else {
            $dispatch('show-toast', {
                msg: `Notifikasi: ${item.title}`
            });
        }
    },

    // Aksi: Aktifkan Push Notification (Simulasi Tips)
    activatePush() {
        $dispatch('show-toast', {
            msg: '🔔 Push Notification berhasil diaktifkan! Kamu tidak akan ketinggalan info makanan surplus.'
        });
    },

    // Aksi: Bagikan Kode Referral Ajak Teman
    shareReferral() {
        const text = 'Ayo bergabung di What\'s Left dan selamatkan makanan lezat berharga diskon! Gunakan tautan: https://whatsleft.id/ref/JASMINE';
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text);
        }
        $dispatch('show-toast', {
            msg: '🎁 Tautan ajak teman berhasil disalin! Bagikan ke temanmu.'
        });
    },

    formatRupiah(number) {
        if (!number) return 'Rp 0';
        return 'Rp ' + Number(number).toLocaleString('id-ID');
    }
}" 
class="min-h-screen bg-[#FAF6F0] py-6 sm:py-8 lg:py-10">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- ========================================================================= -->
        <!-- HEADER HALAMAN NOTIFIKASI + ILUSTRASI MAKANAN & TAS SURPLUS               -->
        <!-- ========================================================================= -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-2">
            
            <!-- Sisi Kiri: Ikon Lonceng Kuning + Judul Besar & Subteks Persis Mockup -->
            <div class="flex items-center gap-4 sm:gap-5">
                <!-- Ikon Lonceng Lingkaran Kuning / Amber dengan Efek Cahaya -->
                <div class="relative w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-[#FEF3C7] border border-[#FDE68A] flex items-center justify-center shrink-0 shadow-xs">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8 text-[#1B5E3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <!-- Sinar / Sparkle Kecil di sekeliling ikon -->
                    <span class="absolute -top-1 -right-1 text-xs">✨</span>
                </div>

                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-[#1F2937] tracking-tight">
                            Notifikasi
                        </h1>
                        <!-- Sinar Kuning di samping kanan judul -->
                        <div class="flex gap-0.5 text-[#F59E0B]">
                            <svg class="w-5 h-5 animate-pulse" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l1.2 3.8 3.8 1.2-3.8 1.2L12 12l-1.2-3.8-3.8-1.2 3.8-1.2L12 2z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs sm:text-sm text-[#6B7280] font-medium mt-1">
                        Semua informasi penting terkait reservasi dan akun Anda.
                    </p>
                </div>
            </div>

            <!-- Sisi Kanan: Ilustrasi Makanan, Tas Belanja Eco & Lonceng Notifikasi Persis Mockup -->
            <div class="hidden md:flex items-center gap-3 relative pr-2">
                <!-- Visual Ilustrasi Tas Eco Green + Mangkok Salad + Croissant -->
                <div class="relative flex items-end gap-2 bg-gradient-to-r from-transparent via-[#FEF3C7]/40 to-[#EBF5F0]/60 p-2 rounded-3xl">
                    
                    <!-- Mangkok Makanan Sehat / Salad -->
                    <div class="w-16 h-12 rounded-2xl bg-amber-100/90 border border-amber-200/90 flex items-center justify-center shadow-xs overflow-hidden transform -rotate-3 hover:rotate-0 transition">
                        <span class="text-2xl" title="Makanan Segar">🥗</span>
                    </div>

                    <!-- Tas Belanja Kertas Hijau Brand What's Left -->
                    <div class="relative w-18 h-20 rounded-2xl bg-[#1B5E3A] text-white p-2 flex flex-col items-center justify-center shadow-md shadow-[#1B5E3A]/20 transform hover:scale-105 transition">
                        <div class="w-7 h-2.5 rounded-t-full border-2 border-dashed border-white/60 mb-1"></div>
                        <svg class="w-5 h-5 text-[#F59E0B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span class="text-[8px] font-black tracking-tight mt-1">WHAT'S LEFT</span>
                    </div>

                    <!-- Croissant Butter Gurih -->
                    <div class="w-14 h-11 rounded-2xl bg-orange-100/90 border border-orange-200/90 flex items-center justify-center shadow-xs transform rotate-6 hover:rotate-0 transition">
                        <span class="text-2xl" title="Croissant Renyah">🥐</span>
                    </div>

                    <!-- Badge Lonceng Biru Melayang dengan Angka 3 -->
                    <div class="absolute -top-3 -right-2 w-9 h-9 rounded-full bg-white shadow-md border border-stone-200 flex items-center justify-center">
                        <div class="relative text-[#0284C7]">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                            </svg>
                            <span class="absolute -top-1.5 -right-2 w-4 h-4 rounded-full bg-[#E67E22] text-white text-[9px] font-black flex items-center justify-center">
                                3
                            </span>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- ========================================================================= -->
        <!-- LAYOUT UTAMA DUA KOLOM: FEED NOTIFIKASI (KIRI) + PANEL FILTER/TIPS (KANAN)  -->
        <!-- ========================================================================= -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- ===================================================================== -->
            <!-- KOLOM KIRI (FEED NOTIFIKASI & PAGINASI) - 8 KOLOM DESKTOP             -->
            <!-- ===================================================================== -->
            <div class="lg:col-span-8 space-y-5">
                
                <!-- BARIS TAB KATEGORI & AKSI TANDAI SEMUA DIBACA -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    
                    <!-- Tab Kategori Horisontal -->
                    <div class="flex items-center gap-1.5 sm:gap-2 overflow-x-auto no-scrollbar py-1">
                        
                        <!-- Tab: Semua (12) -->
                        <button type="button" 
                                @click="setCategory('all')"
                                :class="activeCategory === 'all' 
                                    ? 'bg-[#1B5E3A] text-white shadow-sm shadow-[#1B5E3A]/20' 
                                    : 'bg-white hover:bg-stone-50 text-stone-600 border border-stone-200/80'"
                                class="inline-flex items-center gap-2 px-3.5 sm:px-4 py-2 rounded-full text-xs sm:text-sm font-bold transition duration-200 shrink-0">
                            <span>Semua</span>
                            <span :class="activeCategory === 'all' ? 'bg-[#14472C] text-white' : 'bg-stone-100 text-stone-600'" 
                                  class="px-2 py-0.5 rounded-full text-[11px] font-black"
                                  x-text="getCount('all')">
                                12
                            </span>
                        </button>

                        <!-- Tab: Reservasi (7) -->
                        <button type="button" 
                                @click="setCategory('reservasi')"
                                :class="activeCategory === 'reservasi' 
                                    ? 'bg-[#1B5E3A] text-white shadow-sm shadow-[#1B5E3A]/20' 
                                    : 'bg-white hover:bg-stone-50 text-stone-600 border border-stone-200/80'"
                                class="inline-flex items-center gap-2 px-3.5 sm:px-4 py-2 rounded-full text-xs sm:text-sm font-bold transition duration-200 shrink-0">
                            <span>Reservasi</span>
                            <span :class="activeCategory === 'reservasi' ? 'bg-[#14472C] text-white' : 'bg-stone-100 text-stone-600'" 
                                  class="px-2 py-0.5 rounded-full text-[11px] font-black"
                                  x-text="getCount('reservasi')">
                                7
                            </span>
                        </button>

                        <!-- Tab: Pengambilan (2) -->
                        <button type="button" 
                                @click="setCategory('pengambilan')"
                                :class="activeCategory === 'pengambilan' 
                                    ? 'bg-[#1B5E3A] text-white shadow-sm shadow-[#1B5E3A]/20' 
                                    : 'bg-white hover:bg-stone-50 text-stone-600 border border-stone-200/80'"
                                class="inline-flex items-center gap-2 px-3.5 sm:px-4 py-2 rounded-full text-xs sm:text-sm font-bold transition duration-200 shrink-0">
                            <span>Pengambilan</span>
                            <span :class="activeCategory === 'pengambilan' ? 'bg-[#14472C] text-white' : 'bg-stone-100 text-stone-600'" 
                                  class="px-2 py-0.5 rounded-full text-[11px] font-black"
                                  x-text="getCount('pengambilan')">
                                2
                            </span>
                        </button>

                        <!-- Tab: Promo (2) -->
                        <button type="button" 
                                @click="setCategory('promo')"
                                :class="activeCategory === 'promo' 
                                    ? 'bg-[#1B5E3A] text-white shadow-sm shadow-[#1B5E3A]/20' 
                                    : 'bg-white hover:bg-stone-50 text-stone-600 border border-stone-200/80'"
                                class="inline-flex items-center gap-2 px-3.5 sm:px-4 py-2 rounded-full text-xs sm:text-sm font-bold transition duration-200 shrink-0">
                            <span>Promo</span>
                            <span :class="activeCategory === 'promo' ? 'bg-[#14472C] text-white' : 'bg-stone-100 text-stone-600'" 
                                  class="px-2 py-0.5 rounded-full text-[11px] font-black"
                                  x-text="getCount('promo')">
                                2
                            </span>
                        </button>

                        <!-- Tab: Sistem (1) -->
                        <button type="button" 
                                @click="setCategory('sistem')"
                                :class="activeCategory === 'sistem' 
                                    ? 'bg-[#1B5E3A] text-white shadow-sm shadow-[#1B5E3A]/20' 
                                    : 'bg-white hover:bg-stone-50 text-stone-600 border border-stone-200/80'"
                                class="inline-flex items-center gap-2 px-3.5 sm:px-4 py-2 rounded-full text-xs sm:text-sm font-bold transition duration-200 shrink-0">
                            <span>Sistem</span>
                            <span :class="activeCategory === 'sistem' ? 'bg-[#14472C] text-white' : 'bg-stone-100 text-stone-600'" 
                                  class="px-2 py-0.5 rounded-full text-[11px] font-black"
                                  x-text="getCount('sistem')">
                                1
                            </span>
                        </button>

                    </div>

                    <!-- Tombol Aksi: Tandai Semua Sebagai Dibaca -->
                    <button type="button" 
                            @click="markAllAsRead()"
                            class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-[#1B5E3A] hover:text-[#14472C] hover:underline transition shrink-0 self-end sm:self-center py-1">
                        <svg class="w-4 h-4 text-[#1B5E3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Tandai semua sebagai dibaca</span>
                    </button>

                </div>

                <!-- CARD KONTAINER DAFTAR NOTIFIKASI -->
                <div class="bg-white rounded-3xl border border-stone-200/90 shadow-sm overflow-hidden">
                    
                    <!-- Kondisi Jika Notifikasi Kosong -->
                    <div x-show="filteredNotifications.length === 0" class="py-16 px-6 text-center">
                        <div class="w-16 h-16 rounded-full bg-stone-100 text-stone-400 mx-auto flex items-center justify-center mb-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-stone-800">Tidak ada notifikasi</h3>
                        <p class="text-xs text-stone-500 mt-1 max-w-sm mx-auto">
                            Belum ada pembaruan pada kategori ini saat ini. Semua informasi terkait akunmu akan muncul di sini.
                        </p>
                    </div>

                    <!-- List Notifikasi Baris demi Baris dengan Divider -->
                    <div class="divide-y divide-stone-100">
                        <template x-for="item in paginatedNotifications" :key="item.id">
                            <div class="p-4 sm:p-5 sm:px-6 transition duration-150 hover:bg-stone-50/70 flex items-start gap-3.5 sm:gap-4 group cursor-pointer"
                                 @click="markAsRead(item)">
                                
                                <!-- 1. Titik Indikator Belum Dibaca (Hijau: Belum Dibaca, Abu-abu: Sudah Dibaca) -->
                                <div class="pt-2.5 shrink-0">
                                    <span :class="item.unread ? 'bg-[#1B5E3A] ring-4 ring-[#EBF5F0]' : 'bg-stone-300'"
                                          class="block w-2.5 h-2.5 rounded-full transition-all"></span>
                                </div>

                                <!-- 2. Ikon Lingkaran Kategori Pastel -->
                                <div class="shrink-0">
                                    <!-- Variasi Ikon Sesuai Kategori & Tipe Mockup -->
                                    <template x-if="item.iconType === 'bag-check'">
                                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center shadow-2xs">
                                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l2 2 4-4"/>
                                            </svg>
                                        </div>
                                    </template>

                                    <template x-if="item.iconType === 'clock'">
                                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-[#FEF3C7] text-[#F59E0B] flex items-center justify-center shadow-2xs">
                                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    </template>

                                    <template x-if="item.iconType === 'discount'">
                                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-[#FFEDD5] text-[#EA580C] flex items-center justify-center shadow-2xs">
                                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                        </div>
                                    </template>

                                    <template x-if="item.iconType === 'bag'">
                                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-[#F3E8FF] text-[#9333EA] flex items-center justify-center shadow-2xs">
                                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                            </svg>
                                        </div>
                                    </template>

                                    <template x-if="item.iconType === 'sprout'">
                                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-[#E0F2FE] text-[#0284C7] flex items-center justify-center shadow-2xs">
                                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                    </template>

                                    <template x-if="item.iconType === 'cake'">
                                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-[#FFE4E6] text-[#E11D48] flex items-center justify-center shadow-2xs">
                                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M3 21h18M3 10h18M3 7l9-4 9 4v11a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
                                            </svg>
                                        </div>
                                    </template>
                                </div>

                                <!-- 3. Konten Teks & Tombol Aksi -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-col sm:flex-row sm:items-baseline justify-between gap-1">
                                        <h4 class="text-sm sm:text-base font-bold text-[#1F2937] leading-snug group-hover:text-[#1B5E3A] transition-colors"
                                            x-text="item.title">
                                        </h4>
                                        <span class="text-xs text-[#6B7280] font-medium shrink-0"
                                              x-text="item.time">
                                        </span>
                                    </div>
                                    
                                    <p class="text-xs sm:text-sm text-[#4B5563] mt-1 leading-relaxed"
                                       x-text="item.description">
                                    </p>

                                    <!-- Tombol Aksi "Lihat Detail" (Hanya pada Item yang Memiliki Detail) -->
                                    <template x-if="item.hasAction">
                                        <div class="mt-2.5">
                                            <button type="button" 
                                                    @click.stop="openDetail(item)"
                                                    class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs font-bold text-[#1B5E3A] bg-[#EBF5F0] hover:bg-[#DCF2E4] transition duration-150">
                                                <span x-text="item.actionLabel || 'Lihat Detail'"></span>
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>

                            </div>
                        </template>
                    </div>

                </div>

                <!-- ================================================================= -->
                <!-- PAGINASI (< 1 2 >) PERSIS MOCKUP                                  -->
                <!-- ================================================================= -->
                <div x-show="totalPages > 1" class="flex items-center justify-center gap-2 pt-3 pb-6">
                    
                    <!-- Tombol Previous (<) -->
                    <button type="button" 
                            @click="goToPage(currentPage - 1)"
                            :disabled="currentPage === 1"
                            :class="currentPage === 1 ? 'opacity-40 cursor-not-allowed text-stone-400 bg-stone-100' : 'text-stone-700 bg-white hover:bg-stone-50 border border-stone-200'"
                            class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>

                    <!-- Angka Halaman 1 -->
                    <button type="button" 
                            @click="goToPage(1)"
                            :class="currentPage === 1 
                                ? 'bg-[#1B5E3A] text-white shadow-sm shadow-[#1B5E3A]/25' 
                                : 'bg-white text-stone-700 hover:bg-stone-50 border border-stone-200'"
                            class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-sm transition">
                        1
                    </button>

                    <!-- Angka Halaman 2 -->
                    <button type="button" 
                            @click="goToPage(2)"
                            :class="currentPage === 2 
                                ? 'bg-[#1B5E3A] text-white shadow-sm shadow-[#1B5E3A]/25' 
                                : 'bg-white text-stone-700 hover:bg-stone-50 border border-stone-200'"
                            class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-sm transition">
                        2
                    </button>

                    <!-- Tombol Next (>) -->
                    <button type="button" 
                            @click="goToPage(currentPage + 1)"
                            :disabled="currentPage === totalPages"
                            :class="currentPage === totalPages ? 'opacity-40 cursor-not-allowed text-stone-400 bg-stone-100' : 'text-stone-700 bg-white hover:bg-stone-50 border border-stone-200'"
                            class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>

                </div>

            </div>


            <!-- ===================================================================== -->
            <!-- KOLOM KANAN (SIDEBAR WIDGETS) - 4 KOLOM DESKTOP                       -->
            <!-- ===================================================================== -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- 1. CARD "FILTER NOTIFIKASI" (VERTIKAL LIST DENGAN COUNTER) -->
                <div class="bg-white rounded-3xl border border-stone-200/90 shadow-sm p-5 sm:p-6">
                    <h3 class="text-base font-bold text-stone-900 mb-4 tracking-tight">
                        Filter Notifikasi
                    </h3>

                    <div class="space-y-1">
                        
                        <!-- Filter Item: Semua -->
                        <button type="button" 
                                @click="setCategory('all')"
                                :class="activeCategory === 'all' 
                                    ? 'bg-[#EBF5F0] text-[#1B5E3A] font-bold' 
                                    : 'text-stone-600 hover:bg-stone-50 font-medium'"
                                class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs sm:text-sm transition duration-150">
                            <div class="flex items-center gap-3">
                                <svg class="w-4 h-4" :class="activeCategory === 'all' ? 'text-[#1B5E3A]' : 'text-stone-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                </svg>
                                <span>Semua</span>
                            </div>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-md" 
                                  :class="activeCategory === 'all' ? 'bg-[#1B5E3A] text-white' : 'text-stone-400'"
                                  x-text="getCount('all')">
                                12
                            </span>
                        </button>

                        <!-- Filter Item: Reservasi -->
                        <button type="button" 
                                @click="setCategory('reservasi')"
                                :class="activeCategory === 'reservasi' 
                                    ? 'bg-[#EBF5F0] text-[#1B5E3A] font-bold' 
                                    : 'text-stone-600 hover:bg-stone-50 font-medium'"
                                class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs sm:text-sm transition duration-150">
                            <div class="flex items-center gap-3">
                                <svg class="w-4 h-4" :class="activeCategory === 'reservasi' ? 'text-[#1B5E3A]' : 'text-stone-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <span>Reservasi</span>
                            </div>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-md" 
                                  :class="activeCategory === 'reservasi' ? 'bg-[#1B5E3A] text-white' : 'text-stone-400'"
                                  x-text="getCount('reservasi')">
                                7
                            </span>
                        </button>

                        <!-- Filter Item: Pengambilan -->
                        <button type="button" 
                                @click="setCategory('pengambilan')"
                                :class="activeCategory === 'pengambilan' 
                                    ? 'bg-[#EBF5F0] text-[#1B5E3A] font-bold' 
                                    : 'text-stone-600 hover:bg-stone-50 font-medium'"
                                class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs sm:text-sm transition duration-150">
                            <div class="flex items-center gap-3">
                                <svg class="w-4 h-4" :class="activeCategory === 'pengambilan' ? 'text-[#1B5E3A]' : 'text-stone-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>Pengambilan</span>
                            </div>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-md" 
                                  :class="activeCategory === 'pengambilan' ? 'bg-[#1B5E3A] text-white' : 'text-stone-400'"
                                  x-text="getCount('pengambilan')">
                                2
                            </span>
                        </button>

                        <!-- Filter Item: Promo -->
                        <button type="button" 
                                @click="setCategory('promo')"
                                :class="activeCategory === 'promo' 
                                    ? 'bg-[#EBF5F0] text-[#1B5E3A] font-bold' 
                                    : 'text-stone-600 hover:bg-stone-50 font-medium'"
                                class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs sm:text-sm transition duration-150">
                            <div class="flex items-center gap-3">
                                <svg class="w-4 h-4" :class="activeCategory === 'promo' ? 'text-[#1B5E3A]' : 'text-stone-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                <span>Promo</span>
                            </div>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-md" 
                                  :class="activeCategory === 'promo' ? 'bg-[#1B5E3A] text-white' : 'text-stone-400'"
                                  x-text="getCount('promo')">
                                2
                            </span>
                        </button>

                        <!-- Filter Item: Sistem -->
                        <button type="button" 
                                @click="setCategory('sistem')"
                                :class="activeCategory === 'sistem' 
                                    ? 'bg-[#EBF5F0] text-[#1B5E3A] font-bold' 
                                    : 'text-stone-600 hover:bg-stone-50 font-medium'"
                                class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs sm:text-sm transition duration-150">
                            <div class="flex items-center gap-3">
                                <svg class="w-4 h-4" :class="activeCategory === 'sistem' ? 'text-[#1B5E3A]' : 'text-stone-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>Sistem</span>
                            </div>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-md" 
                                  :class="activeCategory === 'sistem' ? 'bg-[#1B5E3A] text-white' : 'text-stone-400'"
                                  x-text="getCount('sistem')">
                                1
                            </span>
                        </button>

                    </div>
                </div>

                <!-- 2. CARD "TIPS" (AKTIFKAN PUSH NOTIFICATION + MASKOT ALPUKAT KOKI PERSIS MOCKUP) -->
                <div class="relative bg-[#FFFBEB] rounded-3xl border border-[#FDE68A] p-5 sm:p-6 overflow-hidden shadow-xs">
                    
                    <div class="flex items-start justify-between gap-3">
                        <div class="space-y-2 flex-1">
                            <div class="flex items-center gap-1.5 text-[#D97706] font-black text-sm">
                                <span>💡</span>
                                <h4>Tips</h4>
                            </div>
                            
                            <p class="text-xs text-stone-700 font-medium leading-relaxed">
                                Aktifkan push notification agar kamu tidak ketinggalan informasi penting seputar reservasi dan promo!
                            </p>
                        </div>

                        <!-- Maskot Ilustrasi Alpukat Koki Lucu Melambai Sesuai Mockup -->
                        <div class="w-16 h-20 shrink-0 relative flex items-center justify-center">
                            <!-- SVG Alpukat Koki -->
                            <svg viewBox="0 0 100 120" class="w-full h-full drop-shadow-xs">
                                <!-- Topi Koki Putih -->
                                <path d="M40 32 C35 25, 45 15, 50 18 C55 15, 65 25, 60 32 Z" fill="#FFFFFF" stroke="#E5E7EB" stroke-width="2"/>
                                <ellipse cx="50" cy="22" rx="14" ry="10" fill="#FFFFFF"/>
                                <rect x="38" y="28" width="24" height="6" rx="2" fill="#F3F4F6"/>
                                
                                <!-- Tubuh Alpukat Hijau -->
                                <path d="M50 34 C30 34, 25 50, 22 70 C18 90, 30 110, 50 110 C70 110, 82 90, 78 70 C75 50, 70 34, 50 34 Z" fill="#84CC16" stroke="#4D7C0F" stroke-width="2.5"/>
                                <!-- Daging Alpukat Kuning Kehijauan -->
                                <ellipse cx="50" cy="74" rx="22" ry="28" fill="#ECFCCB"/>
                                <!-- Biji Cokelat -->
                                <circle cx="50" cy="80" r="13" fill="#A16207"/>
                                <!-- Mata & Senyum Lucu -->
                                <circle cx="43" cy="56" r="2.5" fill="#1F2937"/>
                                <circle cx="57" cy="56" r="2.5" fill="#1F2937"/>
                                <path d="M46 62 Q50 66 54 62" stroke="#1F2937" stroke-width="1.8" fill="none" stroke-linecap="round"/>
                                <!-- Rona Pipi Merah -->
                                <circle cx="40" cy="60" r="2" fill="#FCA5A5" opacity="0.8"/>
                                <circle cx="60" cy="60" r="2" fill="#FCA5A5" opacity="0.8"/>
                                
                                <!-- Tangan Kiri Melambai Hello -->
                                <path d="M22 65 Q10 50 14 42" stroke="#4D7C0F" stroke-width="3" stroke-linecap="round" fill="none"/>
                                <!-- Tangan Kanan Terbuka -->
                                <path d="M78 65 Q88 52 86 44" stroke="#4D7C0F" stroke-width="3" stroke-linecap="round" fill="none"/>
                                <!-- Kaki Kecil -->
                                <ellipse cx="40" cy="112" rx="4" ry="2.5" fill="#4D7C0F"/>
                                <ellipse cx="60" cy="112" rx="4" ry="2.5" fill="#4D7C0F"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Tombol "Aktifkan Sekarang" Hijau Solid -->
                    <button type="button" 
                            @click="activatePush()"
                            class="mt-4 w-full py-2.5 px-4 rounded-xl text-xs sm:text-sm font-bold text-white bg-[#1B5E3A] hover:bg-[#14472C] transition duration-200 shadow-sm shadow-[#1B5E3A]/25 text-center">
                        Aktifkan Sekarang
                    </button>

                </div>

                <!-- 3. CARD "AJAK TEMAN, SELAMATKAN MAKANAN!" PERSIS MOCKUP -->
                <div class="relative bg-[#F0FDF4] rounded-3xl border border-emerald-100 p-5 sm:p-6 overflow-hidden shadow-xs">
                    
                    <div class="flex items-start justify-between gap-3">
                        <div class="space-y-1.5 flex-1">
                            <h4 class="text-sm font-bold text-[#1F2937] leading-snug">
                                Ajak Teman,<br>
                                <span class="text-[#1B5E3A]">Selamatkan Makanan!</span>
                            </h4>
                            <p class="text-xs text-stone-600 font-medium">
                                Dapatkan <span class="font-bold text-[#1B5E3A]">Rp 10.000</span> untuk setiap teman yang bergabung.
                            </p>
                        </div>

                        <!-- Ilustrasi Kado Hadiah Hijau dengan Pita Kuning & Hati -->
                        <div class="w-16 h-18 shrink-0 relative flex items-center justify-center">
                            <svg viewBox="0 0 100 100" class="w-full h-full drop-shadow-xs">
                                <!-- Kotak Kado Hijau -->
                                <rect x="25" y="42" width="50" height="42" rx="4" fill="#22C55E"/>
                                <rect x="20" y="34" width="60" height="12" rx="3" fill="#16A34A"/>
                                
                                <!-- Pita Kuning Vertikal -->
                                <rect x="45" y="34" width="10" height="50" fill="#FACC15"/>
                                <!-- Pita Horizontal Tutup -->
                                <rect x="20" y="38" width="60" height="4" fill="#EAB308"/>
                                
                                <!-- Simpul Pita Atas -->
                                <ellipse cx="40" cy="28" rx="8" ry="6" fill="#FACC15"/>
                                <ellipse cx="60" cy="28" rx="8" ry="6" fill="#FACC15"/>
                                <circle cx="50" cy="30" r="4" fill="#EAB308"/>
                                
                                <!-- Hati Kecil Mengambang -->
                                <path d="M72 20 C72 15, 80 15, 82 20 C84 15, 92 15, 92 20 C92 26, 82 30, 82 32 C82 30, 72 26, 72 20 Z" fill="#F43F5E"/>
                                <path d="M22 24 C22 20, 27 20, 28 24 C29 20, 34 20, 34 24 C34 28, 28 31, 28 32 C28 31, 22 28, 22 24 Z" fill="#FB7185"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Tombol "Bagikan Sekarang" Hijau Outline -->
                    <button type="button" 
                            @click="shareReferral()"
                            class="mt-4 w-full py-2.5 px-4 rounded-xl text-xs sm:text-sm font-bold text-[#1B5E3A] border border-[#1B5E3A] bg-white hover:bg-[#1B5E3A] hover:text-white transition duration-200 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                        </svg>
                        <span>Bagikan Sekarang</span>
                    </button>

                </div>

            </div>

        </div>

    </div>

    <!-- ========================================================================= -->
    <!-- MODAL POP-UP DETAIL RESERVASI TERKAIT NOTIFIKASI                           -->
    <!-- ========================================================================= -->
    <div x-cloak 
         x-show="detailModalOpen" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         aria-labelledby="modal-detail-title" 
         role="dialog" 
         aria-modal="true"
         @keydown.escape.window="detailModalOpen = false">
        
        <!-- Backdrop Blur Gelap -->
        <div x-show="detailModalOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="detailModalOpen = false"
             class="fixed inset-0 bg-stone-900/60 backdrop-blur-xs transition-opacity"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div x-show="detailModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all w-full max-w-lg border border-stone-200/90 p-6 sm:p-7">
                
                <template x-if="selectedNotification && selectedNotification.details">
                    <div>
                        <!-- Header Modal -->
                        <div class="flex items-center justify-between pb-4 border-b border-stone-100">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 id="modal-detail-title" class="text-base font-bold text-stone-900" x-text="selectedNotification.title"></h3>
                                    <span class="text-xs text-stone-500" x-text="selectedNotification.time"></span>
                                </div>
                            </div>

                            <button type="button" 
                                    @click="detailModalOpen = false"
                                    class="w-8 h-8 rounded-full bg-stone-100 hover:bg-stone-200 text-stone-500 flex items-center justify-center transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Konten Rincian Reservasi -->
                        <div class="py-5 space-y-4">
                            <!-- Banner Kode Booking -->
                            <div class="bg-[#FAF6F0] rounded-2xl p-4 border border-stone-200/80 flex items-center justify-between">
                                <div>
                                    <span class="text-[11px] font-bold uppercase text-stone-500 tracking-wider">Kode Reservasi</span>
                                    <p class="text-lg font-black text-[#1B5E3A] tracking-wider" x-text="selectedNotification.details.bookingCode"></p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#EBF5F0] text-[#1B5E3A]" x-text="selectedNotification.details.status"></span>
                            </div>

                            <!-- Detail Restoran & Makanan -->
                            <div class="space-y-2.5 text-xs">
                                <div class="flex justify-between py-1 border-b border-stone-100">
                                    <span class="text-stone-500 font-medium">Merchant / Bisnis</span>
                                    <span class="font-bold text-stone-900" x-text="selectedNotification.details.merchant"></span>
                                </div>
                                <div class="flex justify-between py-1 border-b border-stone-100">
                                    <span class="text-stone-500 font-medium">Makanan Surplus</span>
                                    <span class="font-bold text-stone-900" x-text="selectedNotification.details.item + ' (' + selectedNotification.details.quantity + ')'"></span>
                                </div>
                                <div class="flex justify-between py-1 border-b border-stone-100">
                                    <span class="text-stone-500 font-medium">Waktu Pengambilan</span>
                                    <span class="font-bold text-[#E67E22]" x-text="selectedNotification.details.pickupWindow"></span>
                                </div>
                                <div class="flex justify-between py-1 border-b border-stone-100">
                                    <span class="text-stone-500 font-medium">Lokasi</span>
                                    <span class="font-semibold text-stone-700 text-right max-w-xs" x-text="selectedNotification.details.merchantAddress"></span>
                                </div>
                                <div class="flex justify-between py-1 border-b border-stone-100">
                                    <span class="text-stone-500 font-medium">Total Pembayaran</span>
                                    <span class="font-black text-[#1B5E3A] text-sm" x-text="formatRupiah(selectedNotification.details.total)"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Modal -->
                        <div class="pt-2 flex items-center gap-3">
                            <a :href="'{{ url('/?view=my-reservations') }}'" 
                               class="flex-1 py-2.5 rounded-xl text-center text-xs sm:text-sm font-bold text-white bg-[#1B5E3A] hover:bg-[#14472C] transition shadow-sm">
                                Buka Reservasi Saya
                            </a>
                            <button type="button" 
                                    @click="detailModalOpen = false"
                                    class="py-2.5 px-5 rounded-xl text-xs sm:text-sm font-semibold text-stone-600 bg-stone-100 hover:bg-stone-200 transition">
                                Tutup
                            </button>
                        </div>

                    </div>
                </template>

            </div>
        </div>
    </div>

</div>
@endsection
