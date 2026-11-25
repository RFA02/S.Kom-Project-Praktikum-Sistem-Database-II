@extends('layouts.main')

@section('title', "Profil Saya - What's Left")

@section('header')
<!-- ========================================================================= -->
<!-- NAVBAR KONSUMEN (KONSISTEN DENGAN EXPLORE, MY RESERVATIONS, NOTIFIKASI)   -->
<!-- ========================================================================= -->
<header class="sticky top-0 z-40 w-full bg-white/95 backdrop-blur-md border-b border-stone-200/80 transition-all duration-200 shadow-xs"
        x-data="{
            profileDropdown: false,
            locationDropdown: false,
            notificationDropdown: false,
            mobileConsumerMenu: false,
            selectedCity: 'Jakarta Selatan, DKI Jakarta',
            displayNickname: 'Jasmine',
            displayFullName: 'Jasmine Salsabilla',
            displayAvatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=160&q=80',
            cities: [
                'Jakarta Selatan, DKI Jakarta',
                'Jakarta Pusat, DKI Jakarta',
                'Bandung, Jawa Barat',
                'Surabaya, Jawa Timur',
                'Tangerang Selatan, Banten',
                'Yogyakarta, DI Yogyakarta'
            ]
        }"
        @profile-updated.window="
            if ($event.detail.nickname) displayNickname = $event.detail.nickname;
            if ($event.detail.fullName) displayFullName = $event.detail.fullName;
            if ($event.detail.avatar) displayAvatar = $event.detail.avatar;
            if ($event.detail.city) selectedCity = $event.detail.city;
        ">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">

        <!-- Brand Logo -->
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5 group">
            <div class="w-10 h-10 rounded-xl bg-[#1B5E3A] flex items-center justify-center text-white shadow-sm shadow-[#1B5E3A]/25 transition-transform group-hover:scale-105">
                <svg class="w-5 h-5 text-[#F59E0B]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div class="flex flex-col">
                <div class="flex items-center text-2xl font-black tracking-tight leading-none">
                    <span class="text-[#1B5E3A]">What's</span>
                    <span class="text-[#E67E22] ml-1">Left</span>
                    <svg class="w-3.5 h-3.5 text-[#F59E0B] -mt-3 ml-0.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <span class="text-[9px] font-bold tracking-wider uppercase text-[#6B7280] mt-0.5 hidden sm:block">Food Rescue Indonesia</span>
            </div>
        </a>

        <!-- Desktop Navigation Links -->
        <nav class="hidden lg:flex items-center gap-7" aria-label="Navigasi Utama Konsumen">
            <a href="{{ url('/') }}" class="py-2 text-sm font-semibold text-[#1F2937] hover:text-[#1B5E3A] transition-colors">
                Beranda
            </a>
            <a href="{{ url('/?view=explore') }}" class="py-2 text-sm font-semibold text-[#1F2937] hover:text-[#1B5E3A] transition-colors">
                Jelajahi
            </a>
            <a href="{{ url('/?view=landing#cara-kerja') }}" class="py-2 text-sm font-semibold text-[#1F2937] hover:text-[#1B5E3A] transition-colors">
                Cara Kerja
            </a>
            <a href="{{ url('/?view=dashboard') }}" class="py-2 text-sm font-semibold text-[#1F2937] hover:text-[#1B5E3A] transition-colors">
                Untuk Bisnis
            </a>
            <a href="{{ url('/?view=landing#tentang-kami') }}" class="py-2 text-sm font-semibold text-[#1F2937] hover:text-[#1B5E3A] transition-colors">
                Tentang Kami
            </a>
        </nav>

        <!-- Right Side: Location Pill, Notifications Badge, & User Profile ("Hai, Jasmine") -->
        <div class="flex items-center gap-2.5 sm:gap-3.5">

            <!-- Location Selector Pill -->
            <div class="relative" @click.outside="locationDropdown = false">
                <button type="button"
                        @click="locationDropdown = !locationDropdown"
                        aria-label="Pilih lokasi pencarian makanan surplus"
                        class="hidden md:inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold text-stone-700 bg-stone-50 border border-stone-200/90 hover:border-[#1B5E3A] hover:bg-[#EBF5F0]/50 transition duration-200">
                    <svg class="w-4 h-4 text-[#1B5E3A] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="max-w-[9rem] truncate" x-text="selectedCity">Jakarta Selatan, DKI Jakarta</span>
                    <svg class="w-3.5 h-3.5 text-stone-400 transition-transform duration-200" :class="locationDropdown ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
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
                     class="absolute right-0 mt-2 w-60 rounded-2xl bg-white shadow-xl border border-stone-100 py-2 z-50">
                    <div class="px-3.5 py-1.5 text-[11px] font-bold uppercase tracking-wider text-stone-400">Pilih Lokasi Terdekat</div>
                    <template x-for="city in cities" :key="city">
                        <button type="button"
                                @click="selectedCity = city; locationDropdown = false; $dispatch('city-changed', { city }); showToast('Lokasi pencarian diubah ke ' + city)"
                                class="w-full text-left px-3.5 py-2 text-xs font-medium text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A] flex items-center justify-between transition">
                            <span x-text="city"></span>
                            <span x-show="selectedCity === city" class="text-[#1B5E3A] font-bold">✓</span>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Notification Bell with Orange Badge -->
            <div class="relative" @click.outside="notificationDropdown = false">
                <button type="button"
                        @click="notificationDropdown = !notificationDropdown"
                        aria-label="Buka notifikasi (3 belum dibaca)"
                        class="w-10 h-10 rounded-full flex items-center justify-center text-stone-600 hover:text-[#1B5E3A] hover:bg-stone-100 transition relative"
                        title="Notifikasi">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="absolute top-1.5 right-1.5 w-4 h-4 rounded-full bg-[#E67E22] text-white text-[10px] font-bold flex items-center justify-center ring-2 ring-white">
                        3
                    </span>
                </button>

                <!-- Notifications Popover -->
                <div x-cloak x-show="notificationDropdown"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-80 sm:w-96 rounded-2xl bg-white shadow-2xl border border-stone-100 py-3 z-50">
                    <div class="px-4 pb-2 border-b border-stone-100 flex items-center justify-between">
                        <span class="text-sm font-bold text-stone-900">Notifikasi Terbaru</span>
                        <a href="{{ url('/?view=notifications') }}" class="text-[11px] font-semibold text-[#1B5E3A] hover:underline">Lihat Semua</a>
                    </div>
                    <div class="divide-y divide-stone-100 max-h-72 overflow-y-auto custom-scrollbar">
                        <a href="{{ url('/?view=my-reservations') }}" class="px-4 py-3 hover:bg-stone-50 transition flex gap-3 items-start">
                            <span class="w-2 h-2 rounded-full bg-[#E67E22] mt-1.5 shrink-0"></span>
                            <div>
                                <p class="text-xs font-bold text-stone-800">Croissant Butter Siap Diambil!</p>
                                <p class="text-[11px] text-stone-500 mt-0.5">Ambil di Sunny Bakery sebelum pukul 20.00 WIB.</p>
                                <span class="text-[10px] text-stone-400 mt-1 block">10 menit yang lalu</span>
                            </div>
                        </a>
                        <a href="{{ url('/?view=my-reservations') }}" class="px-4 py-3 hover:bg-stone-50 transition flex gap-3 items-start">
                            <span class="w-2 h-2 rounded-full bg-[#1B5E3A] mt-1.5 shrink-0"></span>
                            <div>
                                <p class="text-xs font-bold text-stone-800">Reservasi Baru Dikonfirmasi</p>
                                <p class="text-[11px] text-stone-500 mt-0.5">Chicken Teriyaki Bowl (Kode: WL24521Y) berhasil dipesan.</p>
                                <span class="text-[10px] text-stone-400 mt-1 block">1 jam yang lalu</span>
                            </div>
                        </a>
                        <a href="{{ url('/?view=notifications') }}" class="px-4 py-3 hover:bg-stone-50 transition flex gap-3 items-start">
                            <span class="w-2 h-2 rounded-full bg-stone-300 mt-1.5 shrink-0"></span>
                            <div>
                                <p class="text-xs font-semibold text-stone-700">Dampak Penyelamatanmu Meningkat</p>
                                <p class="text-[11px] text-stone-500 mt-0.5">Kamu telah mencegah 4.250 g food waste bulan ini! 🌱</p>
                                <span class="text-[10px] text-stone-400 mt-1 block">Kemarin</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- User Profile Pill (Active state for Profile page) -->
            <div class="relative" @click.outside="profileDropdown = false">
                <button type="button"
                        @click="profileDropdown = !profileDropdown"
                        aria-label="Menu akun pengguna"
                        class="flex items-center gap-2.5 pl-1 pr-2.5 py-1 rounded-full bg-[#EBF5F0] border border-[#DCF2E4] hover:bg-[#DCF2E4]/70 transition duration-150">
                    <img :src="displayAvatar"
                         :alt="'Foto Profil ' + displayNickname"
                         class="w-9 h-9 rounded-full object-cover ring-2 ring-[#1B5E3A]">
                    <span class="text-sm font-bold text-[#1B5E3A] hidden sm:inline" x-text="'Hai, ' + displayNickname">Hai, Jasmine</span>
                    <svg class="w-4 h-4 text-[#1B5E3A] hidden sm:inline transition-transform duration-200" :class="profileDropdown ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
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
                     class="absolute right-0 mt-2 w-60 rounded-2xl bg-white shadow-xl border border-stone-100 py-2 z-50">
                    <div class="px-4 py-2.5 border-b border-stone-100">
                        <p class="text-[11px] text-stone-400 font-medium">Masuk sebagai Consumer</p>
                        <p class="text-sm font-bold text-stone-900 truncate" x-text="displayFullName">Jasmine Salsabilla</p>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 mt-1.5 rounded-full text-[10px] font-bold bg-[#EBF5F0] text-[#1B5E3A]">
                            🌱 Food Saver Hero
                        </span>
                    </div>
                    <div class="py-1">
                        <a href="{{ url('/?view=profile') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-[#1B5E3A] bg-[#EBF5F0]/70 transition">
                            <svg class="w-4 h-4 text-[#1B5E3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Profil Saya (Aktif)
                        </a>
                        <a href="{{ url('/?view=my-reservations') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A] transition">
                            <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            Reservasi Saya
                        </a>
                        <a href="{{ url('/?view=notifications') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A] transition">
                            <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            Notifikasi
                        </a>
                        <a href="{{ url('/?view=explore') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A] transition">
                            <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            Jelajahi Makanan Surplus
                        </a>
                        <a href="{{ url('/?view=dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A] transition">
                            <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            Beralih ke Portal Bisnis
                        </a>
                    </div>
                    <div class="border-t border-stone-100 pt-1">
                        <a href="{{ url('/?view=login') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Keluar Akun
                        </a>
                    </div>
                </div>
            </div>

            <!-- Mobile Hamburger Menu Toggle -->
            <button type="button"
                    @click="mobileConsumerMenu = !mobileConsumerMenu"
                    aria-label="Buka menu navigasi"
                    class="lg:hidden p-2 rounded-xl text-stone-600 hover:text-[#1B5E3A] hover:bg-stone-100 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path x-show="!mobileConsumerMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    <path x-show="mobileConsumerMenu" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Drawer Menu -->
    <div x-cloak x-show="mobileConsumerMenu"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="lg:hidden border-t border-stone-200 bg-white px-4 pt-3 pb-5 space-y-1.5 shadow-lg">
        <a href="{{ url('/') }}" @click="mobileConsumerMenu = false" class="block px-3 py-2 rounded-xl text-sm font-semibold text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A]">Beranda</a>
        <a href="{{ url('/?view=explore') }}" @click="mobileConsumerMenu = false" class="block px-3 py-2 rounded-xl text-sm font-semibold text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A]">Jelajahi Makanan Surplus</a>
        <a href="{{ url('/?view=my-reservations') }}" @click="mobileConsumerMenu = false" class="block px-3 py-2 rounded-xl text-sm font-semibold text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A]">Reservasi Saya</a>
        <a href="{{ url('/?view=notifications') }}" @click="mobileConsumerMenu = false" class="block px-3 py-2 rounded-xl text-sm font-semibold text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A]">Notifikasi</a>
        <a href="{{ url('/?view=profile') }}" @click="mobileConsumerMenu = false" class="block px-3 py-2 rounded-xl text-sm font-bold text-[#1B5E3A] bg-[#EBF5F0]">Profil Saya</a>
        <a href="{{ url('/?view=dashboard') }}" @click="mobileConsumerMenu = false" class="block px-3 py-2 rounded-xl text-sm font-semibold text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A]">Untuk Bisnis</a>
    </div>
</header>
@endsection

@section('content')
<div x-data="{
    // Tab Aktif: 'personal', 'location', 'security', 'notifications'
    activeTab: 'personal',
    saving: false,
    lastSavedAt: 'Baru saja disinkronkan',

    // =========================================================================
    // 1. DATA PROFIL PRIBADI (Align dengan tabel Users / Consumer)
    // =========================================================================
    savedProfile: {
        fullName: 'Jasmine Salsabilla',
        nickname: 'Jasmine',
        email: 'jasmine.salsabilla@foodrescue.id',
        phone: '+62 812-3456-7890',
        avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=240&q=80',
        memberSince: 'Februari 2025',
        emailVerified: true,
        favoriteCategories: ['Roti & Kue', 'Makanan Berat', 'Minuman']
    },
    formPersonal: {
        fullName: 'Jasmine Salsabilla',
        nickname: 'Jasmine',
        email: 'jasmine.salsabilla@foodrescue.id',
        phone: '+62 812-3456-7890',
        avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=240&q=80',
        favoriteCategories: ['Roti & Kue', 'Makanan Berat', 'Minuman']
    },
    personalErrors: {},
    allCategories: ['Makanan Berat', 'Roti & Kue', 'Minuman', 'Snack', 'Dessert'],
    avatarPresets: [
        'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=240&q=80',
        'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=240&q=80',
        'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=240&q=80',
        'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=240&q=80'
    ],
    avatarPickerOpen: false,

    // =========================================================================
    // 2. DATA ALAMAT & LOKASI (Radius pencarian hingga 20 km sesuai AGENTS.md)
    // =========================================================================
    searchCity: 'Jakarta Selatan, DKI Jakarta',
    searchRadius: 5,
    availableCities: [
        'Jakarta Selatan, DKI Jakarta',
        'Jakarta Pusat, DKI Jakarta',
        'Bandung, Jawa Barat',
        'Surabaya, Jawa Timur',
        'Tangerang Selatan, Banten',
        'Yogyakarta, DI Yogyakarta'
    ],
    addresses: [
        {
            id: 1,
            label: 'Rumah / Kos',
            recipient: 'Jasmine Salsabilla',
            phone: '+62 812-3456-7890',
            area: 'Kemang, Jakarta Selatan',
            detail: 'Jl. Kemang Raya No. 28, RT 04/RW 02, Bangka, Mampang Prapatan, Jakarta Selatan 12730',
            pickupNote: 'Dekat area kafe & bakery Kemang, biasa ambil sore pukul 17.30 - 20.00 WIB.',
            isPrimary: true
        },
        {
            id: 2,
            label: 'Kampus / Aktivitas',
            recipient: 'Jasmine Salsabilla',
            phone: '+62 812-3456-7890',
            area: 'Kebayoran Baru, Jakarta Selatan',
            detail: 'Jl. Senopati No. 18, Selong, Kebayoran Baru, Jakarta Selatan 12110',
            pickupNote: 'Sering ambil makanan surplus sepulang kelas / kerja.',
            isPrimary: false
        }
    ],
    addressFormOpen: false,
    editingAddressId: null,
    addressForm: {
        label: '',
        area: '',
        detail: '',
        pickupNote: '',
        isPrimary: false
    },
    addressErrors: {},

    // =========================================================================
    // 3. DATA KEAMANAN & KATA SANDI
    // =========================================================================
    securityForm: {
        currentPassword: '',
        newPassword: '',
        confirmPassword: ''
    },
    showCurrentPassword: false,
    showNewPassword: false,
    showConfirmPassword: false,
    securityErrors: {},
    lastPasswordUpdated: '12 April 2025',

    // =========================================================================
    // 4. DATA PREFERENSI NOTIFIKASI (Selaras dengan kategori di Notifications)
    // =========================================================================
    notificationPrefs: {
        pushEnabled: true,
        reservationStatus: true,
        pickupReminder: true,
        pickupReminderLeadTime: '60',
        promoAndDiscounts: true,
        systemAndImpact: true,
        channels: {
            inApp: true,
            email: true,
            whatsapp: true
        }
    },

    // =========================================================================
    // 5. RINGKASAN DAMPAK & AKTIVITAS CONSUMER
    // =========================================================================
    impactStats: {
        portionsSaved: 12,
        wastePreventedGrams: 4250,
        moneySaved: 148500,
        activeReservations: 2,
        completedReservations: 10,
        unreadNotifications: 3
    },

    // =========================================================================
    // COMPUTED HELPERS
    // =========================================================================
    get primaryAddress() {
        return this.addresses.find(a => a.isPrimary) || this.addresses[0] || null;
    },

    get nearbyMerchantsEstimate() {
        if (this.searchRadius <= 2) return 6;
        if (this.searchRadius <= 5) return 14;
        if (this.searchRadius <= 10) return 28;
        if (this.searchRadius <= 15) return 42;
        return 56;
    },

    get profileCompleteness() {
        let score = 0;
        if (this.savedProfile.fullName.trim()) score += 20;
        if (this.savedProfile.nickname.trim()) score += 15;
        if (this.savedProfile.email.trim()) score += 20;
        if (this.savedProfile.phone.trim()) score += 20;
        if (this.addresses.length > 0) score += 15;
        if (this.savedProfile.favoriteCategories.length > 0) score += 10;
        return score;
    },

    get passwordStrength() {
        const pwd = this.securityForm.newPassword || '';
        if (!pwd) return { score: 0, label: 'Belum diisi', color: 'bg-stone-200', textColor: 'text-stone-400' };
        let score = 0;
        if (pwd.length >= 8) score++;
        if (/[A-Z]/.test(pwd) && /[a-z]/.test(pwd)) score++;
        if (/[0-9]/.test(pwd)) score++;
        if (/[^A-Za-z0-9]/.test(pwd)) score++;

        if (score <= 1) return { score: 1, label: 'Lemah', color: 'bg-rose-500', textColor: 'text-rose-600' };
        if (score === 2) return { score: 2, label: 'Cukup', color: 'bg-[#F59E0B]', textColor: 'text-amber-600' };
        if (score === 3) return { score: 3, label: 'Kuat', color: 'bg-[#2E7D32]', textColor: 'text-[#2E7D32]' };
        return { score: 4, label: 'Sangat Kuat', color: 'bg-[#1B5E3A]', textColor: 'text-[#1B5E3A]' };
    },

    get activeNotifCount() {
        let count = 0;
        if (this.notificationPrefs.reservationStatus) count++;
        if (this.notificationPrefs.pickupReminder) count++;
        if (this.notificationPrefs.promoAndDiscounts) count++;
        if (this.notificationPrefs.systemAndImpact) count++;
        return count;
    },

    formatNumber(num) {
        return Number(num || 0).toLocaleString('id-ID');
    },

    formatRupiah(num) {
        return 'Rp ' + Number(num || 0).toLocaleString('id-ID');
    },

    // =========================================================================
    // ACTIONS: INFORMASI PRIBADI
    // =========================================================================
    toggleFavoriteCategory(cat) {
        const idx = this.formPersonal.favoriteCategories.indexOf(cat);
        if (idx > -1) {
            this.formPersonal.favoriteCategories.splice(idx, 1);
        } else {
            this.formPersonal.favoriteCategories.push(cat);
        }
    },

    handleAvatarUpload(event) {
        const file = event.target.files[0];
        if (!file) return;
        if (file.size > 2 * 1024 * 1024) {
            showToast('Ukuran foto maksimal 2 MB. Pilih gambar yang lebih kecil.', 'error');
            return;
        }
        const reader = new FileReader();
        reader.onload = (e) => {
            this.formPersonal.avatar = e.target.result;
            showToast('Foto profil berhasil dipilih. Klik Simpan Perubahan untuk menerapkan.');
        };
        reader.readAsDataURL(file);
    },

    selectPresetAvatar(url) {
        this.formPersonal.avatar = url;
        this.avatarPickerOpen = false;
        showToast('Avatar profil diperbarui. Klik Simpan Perubahan untuk menyimpan.');
    },

    resetPersonalForm() {
        this.formPersonal = {
            fullName: this.savedProfile.fullName,
            nickname: this.savedProfile.nickname,
            email: this.savedProfile.email,
            phone: this.savedProfile.phone,
            avatar: this.savedProfile.avatar,
            favoriteCategories: [...this.savedProfile.favoriteCategories]
        };
        this.personalErrors = {};
        showToast('Perubahan pada formulir dibatalkan.');
    },

    savePersonalInfo() {
        this.personalErrors = {};
        if (!this.formPersonal.fullName.trim()) {
            this.personalErrors.fullName = 'Nama lengkap wajib diisi.';
        }
        if (!this.formPersonal.nickname.trim()) {
            this.personalErrors.nickname = 'Nama panggilan wajib diisi.';
        }
        if (!this.formPersonal.email.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.formPersonal.email)) {
            this.personalErrors.email = 'Masukkan alamat email yang valid.';
        }
        if (!this.formPersonal.phone.trim() || this.formPersonal.phone.replace(/\D/g, '').length < 10) {
            this.personalErrors.phone = 'Masukkan nomor telepon / WhatsApp minimal 10 digit.';
        }

        if (Object.keys(this.personalErrors).length > 0) {
            showToast('Periksa kembali kolom yang masih belum valid.', 'error');
            return;
        }

        this.saving = true;
        setTimeout(() => {
            this.savedProfile = {
                ...this.savedProfile,
                fullName: this.formPersonal.fullName.trim(),
                nickname: this.formPersonal.nickname.trim(),
                email: this.formPersonal.email.trim(),
                phone: this.formPersonal.phone.trim(),
                avatar: this.formPersonal.avatar,
                favoriteCategories: [...this.formPersonal.favoriteCategories]
            };
            this.saving = false;
            const now = new Date();
            const hh = String(now.getHours()).padStart(2, '0');
            const mm = String(now.getMinutes()).padStart(2, '0');
            this.lastSavedAt = `Disimpan hari ini pukul ${hh}.${mm} WIB`;

            window.dispatchEvent(new CustomEvent('profile-updated', {
                detail: {
                    nickname: this.savedProfile.nickname,
                    fullName: this.savedProfile.fullName,
                    avatar: this.savedProfile.avatar
                }
            }));
            showToast('Informasi pribadi berhasil disimpan! 🌱');
        }, 450);
    },

    // =========================================================================
    // ACTIONS: ALAMAT & LOKASI
    // =========================================================================
    openNewAddressForm() {
        this.editingAddressId = null;
        this.addressForm = {
            label: '',
            area: '',
            detail: '',
            pickupNote: '',
            isPrimary: this.addresses.length === 0
        };
        this.addressErrors = {};
        this.addressFormOpen = true;
    },

    openEditAddressForm(addr) {
        this.editingAddressId = addr.id;
        this.addressForm = {
            label: addr.label,
            area: addr.area,
            detail: addr.detail,
            pickupNote: addr.pickupNote || '',
            isPrimary: addr.isPrimary
        };
        this.addressErrors = {};
        this.addressFormOpen = true;
    },

    saveAddress() {
        this.addressErrors = {};
        if (!this.addressForm.label.trim()) this.addressErrors.label = 'Label alamat wajib diisi (misal: Rumah, Kos, Kantor).';
        if (!this.addressForm.area.trim()) this.addressErrors.area = 'Kecamatan / area wajib diisi.';
        if (!this.addressForm.detail.trim()) this.addressErrors.detail = 'Alamat lengkap wajib diisi.';

        if (Object.keys(this.addressErrors).length > 0) return;

        if (this.addressForm.isPrimary) {
            this.addresses.forEach(a => a.isPrimary = false);
        }

        if (this.editingAddressId) {
            const idx = this.addresses.findIndex(a => a.id === this.editingAddressId);
            if (idx > -1) {
                this.addresses[idx] = {
                    ...this.addresses[idx],
                    label: this.addressForm.label.trim(),
                    area: this.addressForm.area.trim(),
                    detail: this.addressForm.detail.trim(),
                    pickupNote: this.addressForm.pickupNote.trim(),
                    isPrimary: this.addressForm.isPrimary || this.addresses[idx].isPrimary
                };
            }
            showToast('Alamat pengambilan berhasil diperbarui.');
        } else {
            this.addresses.push({
                id: Date.now(),
                label: this.addressForm.label.trim(),
                recipient: this.savedProfile.fullName,
                phone: this.savedProfile.phone,
                area: this.addressForm.area.trim(),
                detail: this.addressForm.detail.trim(),
                pickupNote: this.addressForm.pickupNote.trim(),
                isPrimary: this.addressForm.isPrimary || this.addresses.length === 0
            });
            showToast('Alamat baru berhasil ditambahkan! 📍');
        }

        this.addressFormOpen = false;
        this.editingAddressId = null;
    },

    setPrimaryAddress(id) {
        this.addresses.forEach(a => a.isPrimary = (a.id === id));
        const active = this.addresses.find(a => a.id === id);
        showToast(`Alamat '${active.label}' dijadikan lokasi utama.`);
    },

    deleteAddress(id) {
        if (this.addresses.length <= 1) {
            showToast('Minimal harus ada 1 alamat utama tersimpan.', 'error');
            return;
        }
        const target = this.addresses.find(a => a.id === id);
        this.addresses = this.addresses.filter(a => a.id !== id);
        if (target && target.isPrimary && this.addresses.length > 0) {
            this.addresses[0].isPrimary = true;
        }
        showToast('Alamat berhasil dihapus dari daftar.');
    },

    saveLocationPreferences() {
        window.dispatchEvent(new CustomEvent('profile-updated', {
            detail: { city: this.searchCity }
        }));
        showToast(`Preferensi lokasi (${this.searchCity}, radius ${this.searchRadius} km) berhasil disimpan! 📍`);
    },

    // =========================================================================
    // ACTIONS: KEAMANAN & KATA SANDI
    // =========================================================================
    updatePassword() {
        this.securityErrors = {};
        if (!this.securityForm.currentPassword) {
            this.securityErrors.currentPassword = 'Masukkan kata sandi Anda saat ini.';
        }
        if (!this.securityForm.newPassword || this.securityForm.newPassword.length < 8) {
            this.securityErrors.newPassword = 'Kata sandi baru minimal harus 8 karakter.';
        }
        if (this.securityForm.newPassword && this.securityForm.newPassword === this.securityForm.currentPassword) {
            this.securityErrors.newPassword = 'Kata sandi baru tidak boleh sama dengan kata sandi saat ini.';
        }
        if (this.securityForm.confirmPassword !== this.securityForm.newPassword) {
            this.securityErrors.confirmPassword = 'Konfirmasi kata sandi tidak cocok.';
        }

        if (Object.keys(this.securityErrors).length > 0) {
            showToast('Periksa kembali formulir kata sandi Anda.', 'error');
            return;
        }

        this.saving = true;
        setTimeout(() => {
            this.saving = false;
            this.securityForm = { currentPassword: '', newPassword: '', confirmPassword: '' };
            this.lastPasswordUpdated = 'Hari ini, baru saja diperbarui';
            showToast('Kata sandi akun berhasil diperbarui dengan aman! 🔒');
        }, 450);
    },

    // =========================================================================
    // ACTIONS: PREFERENSI NOTIFIKASI
    // =========================================================================
    enableAllNotifications() {
        this.notificationPrefs.pushEnabled = true;
        this.notificationPrefs.reservationStatus = true;
        this.notificationPrefs.pickupReminder = true;
        this.notificationPrefs.promoAndDiscounts = true;
        this.notificationPrefs.systemAndImpact = true;
        showToast('Semua kategori notifikasi diaktifkan.');
    },

    saveNotificationPrefs() {
        showToast('Preferensi notifikasi Anda berhasil disimpan! 🔔');
    }
}"
@city-changed.window="searchCity = $event.detail.city"
class="min-h-screen bg-[#FAF6F0] pb-20">

    <!-- ========================================================================= -->
    <!-- 2. PAGE HEADER BANNER (Warm Cream & Visual Style Matching Consumer Pages) -->
    <!-- ========================================================================= -->
    <section class="w-full bg-gradient-to-b from-[#FDFBF7] to-[#FAF6F0] border-b border-stone-200/75 pt-8 pb-9">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">

                <!-- Left: Icon Badge + Title & Subtitle -->
                <div class="flex items-start gap-4">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-[#FEF3C7] border border-[#FDE68A] flex items-center justify-center text-[#1B5E3A] shadow-xs shrink-0 mt-0.5 relative">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-[#1B5E3A] text-white text-[11px] flex items-center justify-center ring-2 ring-[#FAF6F0]" aria-hidden="true">🌱</span>
                    </div>

                    <div>
                        <div class="inline-flex items-center gap-1.5">
                            <h1 class="text-3xl sm:text-4xl font-extrabold text-[#1B5E3A] tracking-tight">
                                Profil Saya <span aria-hidden="true">🌱</span>
                            </h1>
                            <!-- Decorative Ray Sparkles matching My Reservations -->
                            <span class="inline-flex flex-col -mt-4 text-[#E67E22] select-none" aria-hidden="true">
                                <svg class="w-5 h-5 -rotate-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                    <line x1="5" y1="12" x2="2" y2="10" />
                                    <line x1="8" y1="8" x2="6" y2="4" />
                                    <line x1="12" y1="5" x2="13" y2="2" />
                                </svg>
                            </span>
                        </div>
                        <p class="text-sm text-[#6B7280] font-medium mt-1.5 max-w-xl leading-relaxed">
                            Kelola informasi akun, lokasi pencarian makanan surplus terdekat, keamanan, serta preferensi notifikasi penyelamatan makanan Anda.
                        </p>
                    </div>
                </div>

                <!-- Right: Eco Graphic Illustration matching Consumer Pages -->
                <div class="hidden md:flex items-center justify-end relative pr-2">
                    <div class="absolute -top-6 -right-4 w-40 h-40 bg-[#FEF3C7]/60 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="absolute top-2 right-24 w-28 h-28 bg-[#DCF2E4]/60 rounded-full blur-xl pointer-events-none"></div>

                    <div class="relative flex items-end gap-3 select-none">
                        <!-- Food Rescue Badge Mini Card -->
                        <div class="bg-white/95 backdrop-blur-sm rounded-2xl px-4 py-3 border border-stone-200/80 shadow-card flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-[11px] font-bold uppercase tracking-wider text-[#6B7280]">Status Akun</div>
                                <div class="text-sm font-extrabold text-[#1F2937]">Consumer Aktif</div>
                            </div>
                        </div>

                        <!-- Center What's Left Eco Paper Bag -->
                        <div class="w-20 h-24 bg-[#1B5E3A] rounded-2xl shadow-floating p-2.5 flex flex-col justify-between items-center text-white transform hover:scale-105 transition duration-300 relative">
                            <div class="w-8 h-5 border-2 border-dashed border-[#F59E0B] rounded-t-full -mt-5 bg-[#FAF6F0]"></div>
                            <div class="w-8 h-8 rounded-full bg-white/15 flex items-center justify-center my-auto text-base">
                                🌱
                            </div>
                            <span class="text-[8px] font-black tracking-wider uppercase text-emerald-100">What's Left</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 3. MAIN 2-COLUMN LAYOUT (Kiri: Tab & Form Pengaturan | Kanan: Ringkasan)  -->
    <!-- ========================================================================= -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- ===================================================================== -->
            <!-- KOLOM KIRI (8/12): TAB NAVIGASI INTERNAL + FORM & PENGATURAN          -->
            <!-- ===================================================================== -->
            <div class="lg:col-span-8 space-y-6">

                <!-- Tab Navigasi Internal -->
                <div class="bg-white rounded-2xl p-2 border border-stone-200/80 shadow-soft" role="tablist" aria-label="Pengaturan Profil Konsumen">
                    <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar">

                        <!-- Tab 1: Informasi Pribadi -->
                        <button type="button"
                                role="tab"
                                :aria-selected="activeTab === 'personal'"
                                @click="activeTab = 'personal'"
                                class="flex-1 min-w-max inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all duration-200"
                                :class="activeTab === 'personal'
                                    ? 'bg-[#1B5E3A] text-white shadow-sm shadow-[#1B5E3A]/20'
                                    : 'text-[#6B7280] hover:text-[#1F2937] hover:bg-stone-100/80'">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Informasi Pribadi</span>
                        </button>

                        <!-- Tab 2: Alamat & Lokasi -->
                        <button type="button"
                                role="tab"
                                :aria-selected="activeTab === 'location'"
                                @click="activeTab = 'location'"
                                class="flex-1 min-w-max inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all duration-200"
                                :class="activeTab === 'location'
                                    ? 'bg-[#1B5E3A] text-white shadow-sm shadow-[#1B5E3A]/20'
                                    : 'text-[#6B7280] hover:text-[#1F2937] hover:bg-stone-100/80'">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Alamat & Lokasi</span>
                        </button>

                        <!-- Tab 3: Keamanan & Kata Sandi -->
                        <button type="button"
                                role="tab"
                                :aria-selected="activeTab === 'security'"
                                @click="activeTab = 'security'"
                                class="flex-1 min-w-max inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all duration-200"
                                :class="activeTab === 'security'
                                    ? 'bg-[#1B5E3A] text-white shadow-sm shadow-[#1B5E3A]/20'
                                    : 'text-[#6B7280] hover:text-[#1F2937] hover:bg-stone-100/80'">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <span>Keamanan & Kata Sandi</span>
                        </button>

                        <!-- Tab 4: Preferensi Notifikasi -->
                        <button type="button"
                                role="tab"
                                :aria-selected="activeTab === 'notifications'"
                                @click="activeTab = 'notifications'"
                                class="flex-1 min-w-max inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all duration-200"
                                :class="activeTab === 'notifications'
                                    ? 'bg-[#1B5E3A] text-white shadow-sm shadow-[#1B5E3A]/20'
                                    : 'text-[#6B7280] hover:text-[#1F2937] hover:bg-stone-100/80'">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span>Preferensi Notifikasi</span>
                        </button>

                    </div>
                </div>

                <!-- ================================================================= -->
                <!-- PANEL 1: INFORMASI PRIBADI                                        -->
                <!-- ================================================================= -->
                <section x-show="activeTab === 'personal'"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         role="tabpanel"
                         class="bg-white rounded-2xl border border-stone-200/85 shadow-card overflow-hidden">

                    <!-- Card Header -->
                    <div class="px-6 py-5 border-b border-stone-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2 bg-[#FDFBF7]/70">
                        <div>
                            <h2 class="text-lg font-extrabold text-[#1F2937]">Informasi Pribadi</h2>
                            <p class="text-xs text-[#6B7280] mt-0.5">Perbarui data diri yang ditampilkan saat Anda melakukan reservasi makanan surplus.</p>
                        </div>
                        <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-[#1B5E3A] bg-[#EBF5F0] px-3 py-1 rounded-full self-start sm:self-auto">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#1B5E3A]"></span>
                            <span x-text="lastSavedAt"></span>
                        </span>
                    </div>

                    <form @submit.prevent="savePersonalInfo()" class="p-6 space-y-6" novalidate>

                        <!-- Foto Profil Selector -->
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5 p-4 rounded-2xl bg-[#FAF6F0]/80 border border-stone-200/70">
                            <div class="relative shrink-0">
                                <img :src="formPersonal.avatar"
                                     :alt="'Avatar ' + formPersonal.fullName"
                                     class="w-20 h-20 rounded-2xl object-cover ring-4 ring-white shadow-sm">
                                <label for="avatar-file-input"
                                       class="absolute -bottom-2 -right-2 w-8 h-8 rounded-xl bg-[#1B5E3A] hover:bg-[#14472C] text-white flex items-center justify-center shadow-md cursor-pointer transition"
                                       title="Unggah foto profil baru">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </label>
                                <input id="avatar-file-input" type="file" accept="image/png,image/jpeg,image/webp" class="sr-only" @change="handleAvatarUpload($event)">
                            </div>

                            <div class="flex-1 min-w-0 space-y-2">
                                <div>
                                    <h3 class="text-sm font-bold text-[#1F2937]">Foto Profil Akun</h3>
                                    <p class="text-xs text-[#6B7280] mt-0.5">Gunakan foto yang jelas agar mitra mudah mengenali Anda saat pengambilan pesanan. Format JPG/PNG, maks. 2 MB.</p>
                                </div>
                                <div class="flex flex-wrap items-center gap-2 pt-0.5">
                                    <label for="avatar-file-input" class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-[#1B5E3A] bg-white border border-[#1B5E3A]/30 hover:bg-[#EBF5F0] cursor-pointer transition">
                                        Unggah Foto
                                    </label>
                                    <button type="button"
                                            @click="avatarPickerOpen = !avatarPickerOpen"
                                            class="px-3.5 py-1.5 rounded-xl text-xs font-semibold text-stone-600 bg-white border border-stone-200 hover:border-stone-300 hover:bg-stone-50 transition">
                                        Pilih Avatar Preset
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Avatar Preset Picker (Collapsible) -->
                        <div x-cloak x-show="avatarPickerOpen"
                             x-transition
                             class="p-4 rounded-2xl bg-stone-50 border border-stone-200/80 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-stone-700">Pilih Avatar Cepat</span>
                                <button type="button" @click="avatarPickerOpen = false" class="text-xs text-stone-400 hover:text-stone-700 font-semibold">Tutup</button>
                            </div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <template x-for="(preset, idx) in avatarPresets" :key="idx">
                                    <button type="button"
                                            @click="selectPresetAvatar(preset)"
                                            class="relative rounded-xl overflow-hidden w-14 h-14 transition transform hover:scale-105"
                                            :class="formPersonal.avatar === preset ? 'ring-2 ring-[#1B5E3A] ring-offset-2' : 'ring-1 ring-stone-200'">
                                        <img :src="preset" :alt="'Avatar pilihan ' + (idx + 1)" class="w-full h-full object-cover">
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Form Inputs Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                            <!-- Nama Lengkap -->
                            <div class="space-y-1.5">
                                <label for="input-fullname" class="block text-xs font-bold text-[#1F2937]">
                                    Nama Lengkap <span class="text-rose-500">*</span>
                                </label>
                                <input id="input-fullname"
                                       type="text"
                                       x-model="formPersonal.fullName"
                                       placeholder="Masukkan nama lengkap Anda"
                                       class="w-full px-4 py-2.5 rounded-xl border text-sm text-[#1F2937] bg-white placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A] transition"
                                       :class="personalErrors.fullName ? 'border-rose-400 bg-rose-50/40' : 'border-stone-200'">
                                <p x-cloak x-show="personalErrors.fullName" x-text="personalErrors.fullName" class="text-xs font-medium text-rose-600"></p>
                            </div>

                            <!-- Nama Panggilan -->
                            <div class="space-y-1.5">
                                <label for="input-nickname" class="block text-xs font-bold text-[#1F2937]">
                                    Nama Panggilan <span class="text-rose-500">*</span>
                                </label>
                                <input id="input-nickname"
                                       type="text"
                                       x-model="formPersonal.nickname"
                                       placeholder="Contoh: Jasmine"
                                       class="w-full px-4 py-2.5 rounded-xl border text-sm text-[#1F2937] bg-white placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A] transition"
                                       :class="personalErrors.nickname ? 'border-rose-400 bg-rose-50/40' : 'border-stone-200'">
                                <p x-cloak x-show="personalErrors.nickname" x-text="personalErrors.nickname" class="text-xs font-medium text-rose-600"></p>
                                <p x-show="!personalErrors.nickname" class="text-[11px] text-[#6B7280]">Ditampilkan pada sapaan navbar, seperti "Hai, Jasmine".</p>
                            </div>

                            <!-- Email -->
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <label for="input-email" class="block text-xs font-bold text-[#1F2937]">
                                        Alamat Email <span class="text-rose-500">*</span>
                                    </label>
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-[#1B5E3A] bg-[#EBF5F0] px-2 py-0.5 rounded-full">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Terverifikasi
                                    </span>
                                </div>
                                <input id="input-email"
                                       type="email"
                                       x-model="formPersonal.email"
                                       placeholder="nama@email.com"
                                       class="w-full px-4 py-2.5 rounded-xl border text-sm text-[#1F2937] bg-white placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A] transition"
                                       :class="personalErrors.email ? 'border-rose-400 bg-rose-50/40' : 'border-stone-200'">
                                <p x-cloak x-show="personalErrors.email" x-text="personalErrors.email" class="text-xs font-medium text-rose-600"></p>
                            </div>

                            <!-- No. Telepon / WhatsApp -->
                            <div class="space-y-1.5">
                                <label for="input-phone" class="block text-xs font-bold text-[#1F2937]">
                                    No. Telepon / WhatsApp <span class="text-rose-500">*</span>
                                </label>
                                <input id="input-phone"
                                       type="tel"
                                       x-model="formPersonal.phone"
                                       placeholder="+62 812-xxxx-xxxx"
                                       class="w-full px-4 py-2.5 rounded-xl border text-sm text-[#1F2937] bg-white placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A] transition"
                                       :class="personalErrors.phone ? 'border-rose-400 bg-rose-50/40' : 'border-stone-200'">
                                <p x-cloak x-show="personalErrors.phone" x-text="personalErrors.phone" class="text-xs font-medium text-rose-600"></p>
                                <p x-show="!personalErrors.phone" class="text-[11px] text-[#6B7280]">Digunakan untuk pengingat & koordinasi pengambilan dengan mitra.</p>
                            </div>

                        </div>

                        <!-- Preferensi Kategori Makanan Surplus (Sesuai kategori Explore Foods) -->
                        <div class="pt-2 border-t border-stone-100 space-y-2.5">
                            <div>
                                <span class="block text-xs font-bold text-[#1F2937]">Kategori Makanan Surplus Favorit</span>
                                <p class="text-[11px] text-[#6B7280] mt-0.5">Membantu kami menampilkan rekomendasi makanan surplus yang relevan di halaman Jelajahi.</p>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="category in allCategories" :key="category">
                                    <button type="button"
                                            @click="toggleFavoriteCategory(category)"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold border transition duration-150"
                                            :class="formPersonal.favoriteCategories.includes(category)
                                                ? 'bg-[#EBF5F0] text-[#1B5E3A] border-[#1B5E3A]/40 shadow-2xs'
                                                : 'bg-white text-stone-600 border-stone-200 hover:border-stone-300 hover:bg-stone-50'">
                                        <span x-show="formPersonal.favoriteCategories.includes(category)" class="text-[#1B5E3A]">✓</span>
                                        <span x-text="category"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="pt-5 border-t border-stone-100 flex flex-col-reverse sm:flex-row sm:items-center justify-end gap-3">
                            <button type="button"
                                    @click="resetPersonalForm()"
                                    class="px-5 py-2.5 rounded-xl text-sm font-bold text-stone-600 bg-stone-100 hover:bg-stone-200/80 transition">
                                Batal
                            </button>
                            <button type="submit"
                                    :disabled="saving"
                                    class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-[#1B5E3A] hover:bg-[#14472C] shadow-sm shadow-[#1B5E3A]/25 disabled:opacity-60 transition cursor-pointer">
                                <svg x-cloak x-show="saving" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                <span x-text="saving ? 'Menyimpan...' : 'Simpan Perubahan'">Simpan Perubahan</span>
                            </button>
                        </div>

                    </form>
                </section>

                <!-- ================================================================= -->
                <!-- PANEL 2: ALAMAT & LOKASI (Radius hingga 20 km sesuai AGENTS.md)   -->
                <!-- ================================================================= -->
                <section x-cloak x-show="activeTab === 'location'"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         role="tabpanel"
                         class="space-y-6">

                    <!-- Card A: Preferensi Wilayah & Radius Pencarian -->
                    <div class="bg-white rounded-2xl border border-stone-200/85 shadow-card overflow-hidden">
                        <div class="px-6 py-5 border-b border-stone-100 bg-[#FDFBF7]/70">
                            <h2 class="text-lg font-extrabold text-[#1F2937]">Preferensi Lokasi & Radius Pencarian</h2>
                            <p class="text-xs text-[#6B7280] mt-0.5">Atur wilayah dan jarak jangkauan maksimal (hingga 20 km) untuk menemukan makanan surplus terdekat.</p>
                        </div>

                        <div class="p-6 space-y-6">
                            <!-- Wilayah Utama -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 items-start">
                                <div class="space-y-1.5">
                                    <label for="select-city" class="block text-xs font-bold text-[#1F2937]">Kota / Wilayah Utama</label>
                                    <select id="select-city"
                                            x-model="searchCity"
                                            class="w-full px-4 py-2.5 rounded-xl border border-stone-200 text-sm font-medium text-[#1F2937] bg-white focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A] transition">
                                        <template x-for="city in availableCities" :key="city">
                                            <option :value="city" x-text="city"></option>
                                        </template>
                                    </select>
                                    <p class="text-[11px] text-[#6B7280]">Digunakan sebagai lokasi default saat Anda membuka halaman Jelajahi.</p>
                                </div>

                                <!-- Estimasi Mitra Dalam Radius -->
                                <div class="p-4 rounded-2xl bg-[#EBF5F0]/75 border border-[#DCF2E4] flex items-center gap-3.5">
                                    <div class="w-11 h-11 rounded-xl bg-white text-[#1B5E3A] border border-[#DCF2E4] flex items-center justify-center shrink-0 shadow-2xs">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-semibold text-[#1B5E3A]">Jangkauan Pencarian Aktif</div>
                                        <div class="text-base font-extrabold text-[#1F2937] mt-0.5">
                                            ±<span x-text="nearbyMerchantsEstimate"></span> Mitra Surplus
                                        </div>
                                        <div class="text-[11px] text-[#6B7280]">Dalam radius <span class="font-bold text-[#1B5E3A]" x-text="searchRadius + ' km'"></span> dari titik utama</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Slider Radius Pencarian (1 km - 20 km sesuai AGENTS.md) -->
                            <div class="space-y-3 p-4 rounded-2xl bg-[#FAF6F0]/80 border border-stone-200/70">
                                <div class="flex items-center justify-between">
                                    <label for="radius-slider" class="text-xs font-bold text-[#1F2937]">Radius Pencarian Makanan Surplus</label>
                                    <span class="px-3 py-1 rounded-full bg-[#1B5E3A] text-white text-xs font-extrabold" x-text="searchRadius + ' km'">5 km</span>
                                </div>
                                <input id="radius-slider"
                                       type="range"
                                       min="1"
                                       max="20"
                                       step="1"
                                       x-model.number="searchRadius"
                                       class="slider-green">
                                <div class="flex items-center justify-between text-[11px] font-semibold text-[#6B7280]">
                                    <span>1 km (Sangat Dekat)</span>
                                    <span>10 km</span>
                                    <span>20 km (Maksimal)</span>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-2 border-t border-stone-100">
                                <a href="{{ url('/?view=explore') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#1B5E3A] hover:underline">
                                    <span>Lihat makanan surplus di sekitar radius ini</span>
                                    <span aria-hidden="true">→</span>
                                </a>
                                <button type="button"
                                        @click="saveLocationPreferences()"
                                        class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-[#1B5E3A] hover:bg-[#14472C] shadow-sm shadow-[#1B5E3A]/20 transition cursor-pointer">
                                    Simpan Preferensi Lokasi
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Card B: Daftar Alamat Pengambilan Tersimpan -->
                    <div class="bg-white rounded-2xl border border-stone-200/85 shadow-card overflow-hidden">
                        <div class="px-6 py-5 border-b border-stone-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-[#FDFBF7]/70">
                            <div>
                                <h2 class="text-lg font-extrabold text-[#1F2937]">Alamat & Titik Pengambilan</h2>
                                <p class="text-xs text-[#6B7280] mt-0.5">Simpan lokasi aktivitas Anda (rumah, kos, atau kampus) sebagai titik acuan jarak pengambilan.</p>
                            </div>
                            <button type="button"
                                    @click="openNewAddressForm()"
                                    class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-[#1B5E3A] bg-[#EBF5F0] hover:bg-[#DCF2E4] border border-[#DCF2E4] transition self-start sm:self-auto cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                </svg>
                                <span>Tambah Alamat</span>
                            </button>
                        </div>

                        <div class="p-6 space-y-4">

                            <!-- Form Inline Tambah / Edit Alamat -->
                            <div x-cloak x-show="addressFormOpen"
                                 x-transition
                                 class="p-5 rounded-2xl bg-[#FAF6F0] border border-[#FDE68A] space-y-4">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-extrabold text-[#1F2937]" x-text="editingAddressId ? 'Edit Alamat Tersimpan' : 'Tambah Alamat Baru'"></h3>
                                    <button type="button" @click="addressFormOpen = false" class="text-xs font-bold text-stone-500 hover:text-stone-800">Tutup ✕</button>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="space-y-1">
                                        <label for="addr-label" class="block text-xs font-bold text-[#1F2937]">Label Alamat <span class="text-rose-500">*</span></label>
                                        <input id="addr-label" type="text" x-model="addressForm.label" placeholder="Contoh: Rumah, Kos, Kantor"
                                               class="w-full px-3.5 py-2 rounded-xl border border-stone-200 bg-white text-sm focus:outline-none focus:border-[#1B5E3A]">
                                        <p x-cloak x-show="addressErrors.label" x-text="addressErrors.label" class="text-[11px] font-medium text-rose-600"></p>
                                    </div>
                                    <div class="space-y-1">
                                        <label for="addr-area" class="block text-xs font-bold text-[#1F2937]">Kecamatan & Kota <span class="text-rose-500">*</span></label>
                                        <input id="addr-area" type="text" x-model="addressForm.area" placeholder="Contoh: Kemang, Jakarta Selatan"
                                               class="w-full px-3.5 py-2 rounded-xl border border-stone-200 bg-white text-sm focus:outline-none focus:border-[#1B5E3A]">
                                        <p x-cloak x-show="addressErrors.area" x-text="addressErrors.area" class="text-[11px] font-medium text-rose-600"></p>
                                    </div>
                                </div>

                                <div class="space-y-1">
                                    <label for="addr-detail" class="block text-xs font-bold text-[#1F2937]">Detail Alamat Lengkap <span class="text-rose-500">*</span></label>
                                    <textarea id="addr-detail" rows="2" x-model="addressForm.detail" placeholder="Nama jalan, nomor rumah/gedung, RT/RW, kode pos"
                                              class="w-full px-3.5 py-2 rounded-xl border border-stone-200 bg-white text-sm focus:outline-none focus:border-[#1B5E3A]"></textarea>
                                    <p x-cloak x-show="addressErrors.detail" x-text="addressErrors.detail" class="text-[11px] font-medium text-rose-600"></p>
                                </div>

                                <div class="space-y-1">
                                    <label for="addr-note" class="block text-xs font-bold text-[#1F2937]">Catatan Pengambilan (Opsional)</label>
                                    <input id="addr-note" type="text" x-model="addressForm.pickupNote" placeholder="Contoh: Biasa ambil makanan surplus pukul 18.00 - 20.00 WIB"
                                           class="w-full px-3.5 py-2 rounded-xl border border-stone-200 bg-white text-sm focus:outline-none focus:border-[#1B5E3A]">
                                </div>

                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-2">
                                    <label class="inline-flex items-center gap-2 text-xs font-semibold text-stone-700 cursor-pointer">
                                        <input type="checkbox" x-model="addressForm.isPrimary" class="rounded border-stone-300 text-[#1B5E3A] focus:ring-[#1B5E3A]">
                                        <span>Jadikan sebagai lokasi utama</span>
                                    </label>
                                    <div class="flex items-center gap-2">
                                        <button type="button" @click="addressFormOpen = false" class="px-4 py-2 rounded-xl text-xs font-bold text-stone-600 bg-white border border-stone-200 hover:bg-stone-50">
                                            Batal
                                        </button>
                                        <button type="button" @click="saveAddress()" class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-[#1B5E3A] hover:bg-[#14472C] shadow-xs cursor-pointer">
                                            Simpan Alamat
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- List Kartu Alamat -->
                            <div class="space-y-3.5">
                                <template x-for="addr in addresses" :key="addr.id">
                                    <div class="p-4 sm:p-5 rounded-2xl border transition-all duration-200"
                                         :class="addr.isPrimary
                                            ? 'bg-[#F0FDF4]/60 border-[#1B5E3A]/35 shadow-2xs'
                                            : 'bg-white border-stone-200 hover:border-stone-300'">
                                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                                            <div class="space-y-1.5 min-w-0">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <span class="text-sm font-extrabold text-[#1F2937]" x-text="addr.label"></span>
                                                    <span x-show="addr.isPrimary"
                                                          class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#1B5E3A] text-white">
                                                        ✓ Lokasi Utama
                                                    </span>
                                                    <span class="text-xs font-semibold text-[#1B5E3A]" x-text="'• ' + addr.area"></span>
                                                </div>
                                                <p class="text-xs sm:text-sm text-stone-700 leading-relaxed" x-text="addr.detail"></p>
                                                <p x-show="addr.pickupNote" class="text-[11px] text-[#6B7280] flex items-center gap-1.5 pt-0.5">
                                                    <svg class="w-3.5 h-3.5 text-[#E67E22] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span x-text="addr.pickupNote"></span>
                                                </p>
                                            </div>

                                            <!-- Actions -->
                                            <div class="flex items-center gap-2 shrink-0 pt-1 sm:pt-0">
                                                <button type="button"
                                                        x-show="!addr.isPrimary"
                                                        @click="setPrimaryAddress(addr.id)"
                                                        class="px-3 py-1.5 rounded-lg text-[11px] font-bold text-[#1B5E3A] bg-[#EBF5F0] hover:bg-[#DCF2E4] transition cursor-pointer">
                                                    Jadikan Utama
                                                </button>
                                                <button type="button"
                                                        @click="openEditAddressForm(addr)"
                                                        class="px-3 py-1.5 rounded-lg text-[11px] font-bold text-stone-600 bg-stone-100 hover:bg-stone-200/80 transition cursor-pointer">
                                                    Ubah
                                                </button>
                                                <button type="button"
                                                        x-show="addresses.length > 1"
                                                        @click="deleteAddress(addr.id)"
                                                        aria-label="Hapus alamat"
                                                        class="p-1.5 rounded-lg text-stone-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                        </div>
                    </div>
                </section>

                <!-- ================================================================= -->
                <!-- PANEL 3: KEAMANAN & KATA SANDI                                    -->
                <!-- ================================================================= -->
                <section x-cloak x-show="activeTab === 'security'"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         role="tabpanel"
                         class="space-y-6">

                    <!-- Card Ubah Kata Sandi -->
                    <div class="bg-white rounded-2xl border border-stone-200/85 shadow-card overflow-hidden">
                        <div class="px-6 py-5 border-b border-stone-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2 bg-[#FDFBF7]/70">
                            <div>
                                <h2 class="text-lg font-extrabold text-[#1F2937]">Ubah Kata Sandi</h2>
                                <p class="text-xs text-[#6B7280] mt-0.5">Jaga keamanan akun Consumer Anda dengan menggunakan kata sandi yang kuat dan unik.</p>
                            </div>
                            <span class="text-[11px] font-semibold text-[#6B7280]">
                                Terakhir diubah: <strong class="text-[#1F2937]" x-text="lastPasswordUpdated"></strong>
                            </span>
                        </div>

                        <form @submit.prevent="updatePassword()" class="p-6 space-y-5" novalidate>
                            <!-- Kata Sandi Saat Ini -->
                            <div class="space-y-1.5">
                                <label for="input-current-password" class="block text-xs font-bold text-[#1F2937]">
                                    Kata Sandi Saat Ini <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <input id="input-current-password"
                                           :type="showCurrentPassword ? 'text' : 'password'"
                                           x-model="securityForm.currentPassword"
                                           placeholder="Masukkan kata sandi Anda saat ini"
                                           class="w-full pl-4 pr-11 py-2.5 rounded-xl border text-sm text-[#1F2937] bg-white focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A] transition"
                                           :class="securityErrors.currentPassword ? 'border-rose-400 bg-rose-50/40' : 'border-stone-200'">
                                    <button type="button"
                                            @click="showCurrentPassword = !showCurrentPassword"
                                            aria-label="Tampilkan atau sembunyikan kata sandi saat ini"
                                            class="absolute inset-y-0 right-0 px-3.5 text-stone-400 hover:text-stone-700 flex items-center">
                                        <svg x-show="!showCurrentPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <svg x-cloak x-show="showCurrentPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                    </button>
                                </div>
                                <p x-cloak x-show="securityErrors.currentPassword" x-text="securityErrors.currentPassword" class="text-xs font-medium text-rose-600"></p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <!-- Kata Sandi Baru -->
                                <div class="space-y-1.5">
                                    <label for="input-new-password" class="block text-xs font-bold text-[#1F2937]">
                                        Kata Sandi Baru <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input id="input-new-password"
                                               :type="showNewPassword ? 'text' : 'password'"
                                               x-model="securityForm.newPassword"
                                               placeholder="Minimal 8 karakter"
                                               class="w-full pl-4 pr-11 py-2.5 rounded-xl border text-sm text-[#1F2937] bg-white focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A] transition"
                                               :class="securityErrors.newPassword ? 'border-rose-400 bg-rose-50/40' : 'border-stone-200'">
                                        <button type="button"
                                                @click="showNewPassword = !showNewPassword"
                                                aria-label="Tampilkan atau sembunyikan kata sandi baru"
                                                class="absolute inset-y-0 right-0 px-3.5 text-stone-400 hover:text-stone-700 flex items-center">
                                            <svg x-show="!showNewPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <svg x-cloak x-show="showNewPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                        </button>
                                    </div>
                                    <p x-cloak x-show="securityErrors.newPassword" x-text="securityErrors.newPassword" class="text-xs font-medium text-rose-600"></p>
                                </div>

                                <!-- Konfirmasi Kata Sandi Baru -->
                                <div class="space-y-1.5">
                                    <label for="input-confirm-password" class="block text-xs font-bold text-[#1F2937]">
                                        Konfirmasi Kata Sandi Baru <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input id="input-confirm-password"
                                               :type="showConfirmPassword ? 'text' : 'password'"
                                               x-model="securityForm.confirmPassword"
                                               placeholder="Ulangi kata sandi baru"
                                               class="w-full pl-4 pr-11 py-2.5 rounded-xl border text-sm text-[#1F2937] bg-white focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A] transition"
                                               :class="securityErrors.confirmPassword ? 'border-rose-400 bg-rose-50/40' : 'border-stone-200'">
                                        <button type="button"
                                                @click="showConfirmPassword = !showConfirmPassword"
                                                aria-label="Tampilkan atau sembunyikan konfirmasi kata sandi"
                                                class="absolute inset-y-0 right-0 px-3.5 text-stone-400 hover:text-stone-700 flex items-center">
                                            <svg x-show="!showConfirmPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <svg x-cloak x-show="showConfirmPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                        </button>
                                    </div>
                                    <p x-cloak x-show="securityErrors.confirmPassword" x-text="securityErrors.confirmPassword" class="text-xs font-medium text-rose-600"></p>
                                </div>
                            </div>

                            <!-- Indikator Kekuatan Kata Sandi -->
                            <div class="p-4 rounded-2xl bg-[#FAF6F0]/80 border border-stone-200/70 space-y-2.5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-bold text-stone-700">Kekuatan Kata Sandi</span>
                                    <span class="font-extrabold" :class="passwordStrength.textColor" x-text="passwordStrength.label"></span>
                                </div>
                                <div class="grid grid-cols-4 gap-1.5">
                                    <template x-for="bar in [1, 2, 3, 4]" :key="bar">
                                        <div class="h-1.5 rounded-full transition-colors duration-200"
                                             :class="bar <= passwordStrength.score ? passwordStrength.color : 'bg-stone-200'"></div>
                                    </template>
                                </div>
                                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-1.5 pt-1 text-[11px] text-[#6B7280]">
                                    <li class="flex items-center gap-1.5" :class="securityForm.newPassword.length >= 8 ? 'text-[#1B5E3A] font-semibold' : ''">
                                        <span x-text="securityForm.newPassword.length >= 8 ? '✓' : '•'"></span> Minimal 8 karakter
                                    </li>
                                    <li class="flex items-center gap-1.5" :class="/[A-Z]/.test(securityForm.newPassword) && /[a-z]/.test(securityForm.newPassword) ? 'text-[#1B5E3A] font-semibold' : ''">
                                        <span x-text="/[A-Z]/.test(securityForm.newPassword) && /[a-z]/.test(securityForm.newPassword) ? '✓' : '•'"></span> Kombinasi huruf besar & kecil
                                    </li>
                                    <li class="flex items-center gap-1.5" :class="/[0-9]/.test(securityForm.newPassword) ? 'text-[#1B5E3A] font-semibold' : ''">
                                        <span x-text="/[0-9]/.test(securityForm.newPassword) ? '✓' : '•'"></span> Mengandung minimal 1 angka
                                    </li>
                                    <li class="flex items-center gap-1.5" :class="/[^A-Za-z0-9]/.test(securityForm.newPassword) ? 'text-[#1B5E3A] font-semibold' : ''">
                                        <span x-text="/[^A-Za-z0-9]/.test(securityForm.newPassword) ? '✓' : '•'"></span> Simbol khusus (!@#$%) disarankan
                                    </li>
                                </ul>
                            </div>

                            <div class="pt-4 border-t border-stone-100 flex justify-end">
                                <button type="submit"
                                        :disabled="saving"
                                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-[#1B5E3A] hover:bg-[#14472C] shadow-sm shadow-[#1B5E3A]/20 disabled:opacity-60 transition cursor-pointer">
                                    <span x-text="saving ? 'Memperbarui...' : 'Perbarui Kata Sandi'">Perbarui Kata Sandi</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Card Status Keamanan Akun -->
                    <div class="bg-white rounded-2xl border border-stone-200/85 shadow-card p-6 space-y-4">
                        <h3 class="text-base font-extrabold text-[#1F2937]">Status Keamanan Akun</h3>
                        <div class="divide-y divide-stone-100">
                            <div class="py-3 flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-[#1F2937]">Verifikasi Email</p>
                                        <p class="text-xs text-[#6B7280]" x-text="savedProfile.email"></p>
                                    </div>
                                </div>
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-[#DCF2E4] text-[#1B5E3A]">Aktif & Terverifikasi</span>
                            </div>

                            <div class="py-3 flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-[#FEF3C7] text-[#D97706] flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-[#1F2937]">Sesi Perangkat Saat Ini</p>
                                        <p class="text-xs text-[#6B7280]">Browser Web • Jakarta, Indonesia • Aktif sekarang</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-[#1B5E3A]">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Online
                                </span>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ================================================================= -->
                <!-- PANEL 4: PREFERENSI NOTIFIKASI (Selaras dengan /notifications)    -->
                <!-- ================================================================= -->
                <section x-cloak x-show="activeTab === 'notifications'"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         role="tabpanel"
                         class="bg-white rounded-2xl border border-stone-200/85 shadow-card overflow-hidden">

                    <div class="px-6 py-5 border-b border-stone-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2 bg-[#FDFBF7]/70">
                        <div>
                            <h2 class="text-lg font-extrabold text-[#1F2937]">Preferensi Notifikasi</h2>
                            <p class="text-xs text-[#6B7280] mt-0.5">Pilih jenis pemberitahuan yang ingin Anda terima terkait reservasi, pengambilan, promo, dan sistem.</p>
                        </div>
                        <button type="button"
                                @click="enableAllNotifications()"
                                class="text-xs font-bold text-[#1B5E3A] hover:underline self-start sm:self-auto cursor-pointer">
                            Aktifkan Semua (<span x-text="activeNotifCount"></span>/4)
                        </button>
                    </div>

                    <div class="p-6 space-y-6">

                        <!-- Banner Push Notification -->
                        <div class="p-4 rounded-2xl bg-[#FAF6F0] border border-[#FDE68A] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-[#FEF3C7] text-[#D97706] flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-extrabold text-[#1F2937]">Push Notification Langsung</h3>
                                    <p class="text-xs text-[#6B7280] mt-0.5">Dapatkan peringatan seketika saat makanan surplus siap diambil agar tidak melewati batas waktu.</p>
                                </div>
                            </div>

                            <button type="button"
                                    role="switch"
                                    :aria-checked="notificationPrefs.pushEnabled"
                                    aria-label="Aktifkan Push Notification"
                                    @click="notificationPrefs.pushEnabled = !notificationPrefs.pushEnabled; showToast(notificationPrefs.pushEnabled ? 'Push Notification diaktifkan 🔔' : 'Push Notification dinonaktifkan')"
                                    class="relative inline-flex h-7 w-12 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#1B5E3A] focus:ring-offset-2 self-end sm:self-center"
                                    :class="notificationPrefs.pushEnabled ? 'bg-[#1B5E3A]' : 'bg-stone-300'">
                                <span class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"
                                      :class="notificationPrefs.pushEnabled ? 'translate-x-5' : 'translate-x-0'"></span>
                            </button>
                        </div>

                        <!-- Toggle Kategori Notifikasi (4 Kategori Sesuai Halaman Notifikasi & AGENTS.md) -->
                        <div class="divide-y divide-stone-100">

                            <!-- 1. Status Reservasi -->
                            <div class="py-4 flex items-start justify-between gap-4">
                                <div class="flex items-start gap-3.5">
                                    <div class="w-10 h-10 rounded-xl bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center shrink-0 mt-0.5">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="text-sm font-bold text-[#1F2937]">Status Reservasi</h4>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#EBF5F0] text-[#1B5E3A]">Reservasi</span>
                                        </div>
                                        <p class="text-xs text-[#6B7280] mt-0.5 leading-relaxed">Pemberitahuan konfirmasi pesanan baru, kode booking pengambilan, hingga status pesanan selesai atau dibatalkan.</p>
                                    </div>
                                </div>
                                <button type="button"
                                        role="switch"
                                        :aria-checked="notificationPrefs.reservationStatus"
                                        aria-label="Toggle notifikasi status reservasi"
                                        @click="notificationPrefs.reservationStatus = !notificationPrefs.reservationStatus"
                                        class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-[#1B5E3A] focus:ring-offset-2 mt-1"
                                        :class="notificationPrefs.reservationStatus ? 'bg-[#1B5E3A]' : 'bg-stone-300'">
                                    <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-sm transition duration-200"
                                          :class="notificationPrefs.reservationStatus ? 'translate-x-5' : 'translate-x-0'"></span>
                                </button>
                            </div>

                            <!-- 2. Pengingat Waktu Pengambilan -->
                            <div class="py-4 space-y-3">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex items-start gap-3.5">
                                        <div class="w-10 h-10 rounded-xl bg-[#FEF3C7] text-[#D97706] flex items-center justify-center shrink-0 mt-0.5">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h4 class="text-sm font-bold text-[#1F2937]">Pengingat Waktu Pengambilan</h4>
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#FEF3C7] text-[#B45309]">Pengambilan</span>
                                            </div>
                                            <p class="text-xs text-[#6B7280] mt-0.5 leading-relaxed">Ingatkan saya sebelum jam pengambilan makanan surplus di mitra dimulai atau hampir berakhir.</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                            role="switch"
                                            :aria-checked="notificationPrefs.pickupReminder"
                                            aria-label="Toggle pengingat waktu pengambilan"
                                            @click="notificationPrefs.pickupReminder = !notificationPrefs.pickupReminder"
                                            class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-[#1B5E3A] focus:ring-offset-2 mt-1"
                                            :class="notificationPrefs.pickupReminder ? 'bg-[#1B5E3A]' : 'bg-stone-300'">
                                        <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-sm transition duration-200"
                                              :class="notificationPrefs.pickupReminder ? 'translate-x-5' : 'translate-x-0'"></span>
                                    </button>
                                </div>

                                <!-- Opsi Waktu Pengingat -->
                                <div x-show="notificationPrefs.pickupReminder" x-transition class="pl-14 flex flex-wrap items-center gap-2">
                                    <span class="text-[11px] font-semibold text-[#6B7280]">Kirim pengingat:</span>
                                    <template x-for="opt in [{val: '30', text: '30 menit sebelumnya'}, {val: '60', text: '1 jam sebelumnya'}, {val: '120', text: '2 jam sebelumnya'}]" :key="opt.val">
                                        <button type="button"
                                                @click="notificationPrefs.pickupReminderLeadTime = opt.val"
                                                class="px-2.5 py-1 rounded-lg text-[11px] font-bold border transition"
                                                :class="notificationPrefs.pickupReminderLeadTime === opt.val
                                                    ? 'bg-[#EBF5F0] text-[#1B5E3A] border-[#1B5E3A]/40'
                                                    : 'bg-white text-stone-600 border-stone-200 hover:bg-stone-50'"
                                                x-text="opt.text"></button>
                                    </template>
                                </div>
                            </div>

                            <!-- 3. Promo & Penawaran Surplus -->
                            <div class="py-4 flex items-start justify-between gap-4">
                                <div class="flex items-start gap-3.5">
                                    <div class="w-10 h-10 rounded-xl bg-orange-50 text-[#E67E22] flex items-center justify-center shrink-0 mt-0.5">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="text-sm font-bold text-[#1F2937]">Promo & Surplus Baru Terdekat</h4>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-orange-100 text-[#C2410C]">Promo</span>
                                        </div>
                                        <p class="text-xs text-[#6B7280] mt-0.5 leading-relaxed">Info diskon spesial, kode voucher, dan stok makanan surplus baru dari mitra dalam radius pencarian Anda.</p>
                                    </div>
                                </div>
                                <button type="button"
                                        role="switch"
                                        :aria-checked="notificationPrefs.promoAndDiscounts"
                                        aria-label="Toggle notifikasi promo dan surplus baru"
                                        @click="notificationPrefs.promoAndDiscounts = !notificationPrefs.promoAndDiscounts"
                                        class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-[#1B5E3A] focus:ring-offset-2 mt-1"
                                        :class="notificationPrefs.promoAndDiscounts ? 'bg-[#1B5E3A]' : 'bg-stone-300'">
                                    <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-sm transition duration-200"
                                          :class="notificationPrefs.promoAndDiscounts ? 'translate-x-5' : 'translate-x-0'"></span>
                                </button>
                            </div>

                            <!-- 4. Informasi Sistem & Dampak Lingkungan -->
                            <div class="py-4 flex items-start justify-between gap-4">
                                <div class="flex items-start gap-3.5">
                                    <div class="w-10 h-10 rounded-xl bg-stone-100 text-stone-700 flex items-center justify-center shrink-0 mt-0.5">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="text-sm font-bold text-[#1F2937]">Informasi Sistem & Dampak Food Rescue</h4>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-stone-100 text-stone-600">Sistem</span>
                                        </div>
                                        <p class="text-xs text-[#6B7280] mt-0.5 leading-relaxed">Ringkasan porsi makanan yang berhasil Anda selamatkan, info keamanan akun, dan pembaruan layanan What's Left.</p>
                                    </div>
                                </div>
                                <button type="button"
                                        role="switch"
                                        :aria-checked="notificationPrefs.systemAndImpact"
                                        aria-label="Toggle notifikasi informasi sistem"
                                        @click="notificationPrefs.systemAndImpact = !notificationPrefs.systemAndImpact"
                                        class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-[#1B5E3A] focus:ring-offset-2 mt-1"
                                        :class="notificationPrefs.systemAndImpact ? 'bg-[#1B5E3A]' : 'bg-stone-300'">
                                    <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-sm transition duration-200"
                                          :class="notificationPrefs.systemAndImpact ? 'translate-x-5' : 'translate-x-0'"></span>
                                </button>
                            </div>

                        </div>

                        <!-- Saluran Pengiriman Notifikasi -->
                        <div class="p-4 rounded-2xl bg-stone-50 border border-stone-200/70 space-y-2.5">
                            <span class="block text-xs font-bold text-[#1F2937]">Saluran Penerimaan Notifikasi</span>
                            <div class="flex flex-wrap gap-4">
                                <label class="inline-flex items-center gap-2 text-xs font-semibold text-stone-700 cursor-pointer">
                                    <input type="checkbox" x-model="notificationPrefs.channels.inApp" class="rounded border-stone-300 text-[#1B5E3A] focus:ring-[#1B5E3A]">
                                    <span>Notifikasi Aplikasi Web</span>
                                </label>
                                <label class="inline-flex items-center gap-2 text-xs font-semibold text-stone-700 cursor-pointer">
                                    <input type="checkbox" x-model="notificationPrefs.channels.email" class="rounded border-stone-300 text-[#1B5E3A] focus:ring-[#1B5E3A]">
                                    <span>Email</span>
                                </label>
                                <label class="inline-flex items-center gap-2 text-xs font-semibold text-stone-700 cursor-pointer">
                                    <input type="checkbox" x-model="notificationPrefs.channels.whatsapp" class="rounded border-stone-300 text-[#1B5E3A] focus:ring-[#1B5E3A]">
                                    <span>WhatsApp (Pengingat Pengambilan)</span>
                                </label>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-stone-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <a href="{{ url('/?view=notifications') }}" class="text-xs font-bold text-[#1B5E3A] hover:underline">
                                Buka kotak masuk notifikasi →
                            </a>
                            <button type="button"
                                    @click="saveNotificationPrefs()"
                                    class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-[#1B5E3A] hover:bg-[#14472C] shadow-sm shadow-[#1B5E3A]/20 transition cursor-pointer">
                                Simpan Preferensi Notifikasi
                            </button>
                        </div>
                    </div>
                </section>

            </div>

            <!-- ===================================================================== -->
            <!-- KOLOM KANAN (4/12): KARTU RINGKASAN PROFIL + KARTU DAMPAK & AKTIVITAS -->
            <!-- ===================================================================== -->
            <aside class="lg:col-span-4 space-y-6 lg:sticky lg:top-28">

                <!-- CARD 1: KARTU RINGKASAN PROFIL -->
                <div class="bg-white rounded-2xl border border-stone-200/90 shadow-card overflow-hidden">
                    <!-- Decorative Top Eco Gradient Banner -->
                    <div class="h-20 bg-gradient-to-r from-[#1B5E3A] via-[#236D44] to-[#2E7D32] relative overflow-hidden">
                        <div class="absolute -right-6 -top-6 w-28 h-28 rounded-full bg-white/10"></div>
                        <div class="absolute left-8 -bottom-8 w-24 h-24 rounded-full bg-[#F59E0B]/20"></div>
                        <div class="absolute top-3 right-3.5">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-white/15 backdrop-blur-sm text-white border border-white/20">
                                🌱 Consumer
                            </span>
                        </div>
                    </div>

                    <div class="px-5 pb-5">
                        <!-- Avatar + Identity -->
                        <div class="flex items-end gap-3.5 -mt-10 relative z-10">
                            <img :src="savedProfile.avatar"
                                 :alt="'Foto Profil ' + savedProfile.fullName"
                                 class="w-20 h-20 rounded-2xl object-cover ring-4 ring-white shadow-md bg-white shrink-0">
                            <div class="pb-1 min-w-0">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#FEF3C7] text-[#B45309] border border-[#FDE68A]">
                                    ⭐ Food Saver Hero
                                </span>
                            </div>
                        </div>

                        <div class="mt-3">
                            <h3 class="text-lg font-extrabold text-[#1F2937] leading-tight truncate" x-text="savedProfile.fullName">Jasmine Salsabilla</h3>
                            <p class="text-xs text-[#6B7280] mt-0.5 truncate" x-text="savedProfile.email">jasmine.salsabilla@foodrescue.id</p>
                        </div>

                        <!-- Info Metadata Ringkas -->
                        <div class="mt-4 pt-4 border-t border-stone-100 space-y-2.5 text-xs">
                            <div class="flex items-start justify-between gap-3">
                                <span class="text-[#6B7280] flex items-center gap-1.5 shrink-0">
                                    <svg class="w-3.5 h-3.5 text-[#1B5E3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Lokasi Utama
                                </span>
                                <span class="font-bold text-[#1F2937] text-right truncate" x-text="primaryAddress ? primaryAddress.area : searchCity">Kemang, Jakarta Selatan</span>
                            </div>

                            <div class="flex items-center justify-between gap-3">
                                <span class="text-[#6B7280] flex items-center gap-1.5 shrink-0">
                                    <svg class="w-3.5 h-3.5 text-[#E67E22]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                    </svg>
                                    Radius Pencarian
                                </span>
                                <span class="font-bold text-[#1B5E3A]" x-text="'Maks. ' + searchRadius + ' km'">Maks. 5 km</span>
                            </div>

                            <div class="flex items-center justify-between gap-3">
                                <span class="text-[#6B7280] flex items-center gap-1.5 shrink-0">
                                    <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Bergabung Sejak
                                </span>
                                <span class="font-semibold text-[#1F2937]" x-text="savedProfile.memberSince">Februari 2025</span>
                            </div>
                        </div>

                        <!-- Kelengkapan Profil Progress Bar -->
                        <div class="mt-4 pt-4 border-t border-stone-100 space-y-1.5">
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="font-bold text-stone-600">Kelengkapan Profil</span>
                                <span class="font-extrabold text-[#1B5E3A]" x-text="profileCompleteness + '%'">100%</span>
                            </div>
                            <div class="w-full h-2 rounded-full bg-stone-100 overflow-hidden">
                                <div class="h-full rounded-full bg-gradient-to-r from-[#1B5E3A] to-[#2E7D32] transition-all duration-300"
                                     :style="'width: ' + profileCompleteness + '%'"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD 2: KARTU DAMPAK & AKTIVITAS (Selaras dengan Dampakmu Hari Ini 🌱) -->
                <div class="bg-[#F0FDF4] rounded-2xl p-5 border border-[#DCF2E4] shadow-xs space-y-4">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <h3 class="text-sm sm:text-base font-extrabold text-[#1F2937] flex items-center gap-1.5">
                                <span>Dampak & Aktivitasmu</span>
                                <span aria-hidden="true">🌱</span>
                            </h3>
                            <p class="text-xs text-[#6B7280] mt-0.5">
                                Kontribusimu mengurangi food waste bersama What's Left.
                            </p>
                        </div>
                    </div>

                    <!-- Grid Metrik Dampak Penyelamatan Makanan -->
                    <div class="grid grid-cols-2 gap-3">
                        <!-- Porsi Diselamatkan -->
                        <div class="bg-white rounded-xl p-3.5 border border-emerald-100 shadow-2xs space-y-1.5">
                            <div class="w-8 h-8 rounded-lg bg-[#FEF3C7] text-[#D97706] flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-xl font-black text-[#1F2937] leading-none" x-text="formatNumber(impactStats.portionsSaved)">12</div>
                                <div class="text-[11px] text-[#6B7280] font-medium mt-1 leading-tight">Porsi Diselamatkan</div>
                            </div>
                        </div>

                        <!-- Limbah Makanan Dihindari -->
                        <div class="bg-white rounded-xl p-3.5 border border-emerald-100 shadow-2xs space-y-1.5">
                            <div class="w-8 h-8 rounded-lg bg-[#DCF2E4] text-[#1B5E3A] flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-lg font-black text-[#1B5E3A] leading-none" x-text="formatNumber(impactStats.wastePreventedGrams) + ' g'">4.250 g</div>
                                <div class="text-[11px] text-[#6B7280] font-medium mt-1 leading-tight">Limbah Dihindari</div>
                            </div>
                        </div>
                    </div>

                    <!-- Estimasi Penghematan Belanja Surplus -->
                    <div class="bg-white rounded-xl p-3.5 border border-emerald-100 shadow-2xs flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-9 h-9 rounded-lg bg-orange-50 text-[#E67E22] flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <div class="text-[11px] font-medium text-[#6B7280]">Total Penghematan</div>
                                <div class="text-sm font-black text-[#E67E22]" x-text="formatRupiah(impactStats.moneySaved)">Rp 148.500</div>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-[#EBF5F0] text-[#1B5E3A] shrink-0">
                            <span x-text="impactStats.completedReservations"></span> Selesai
                        </span>
                    </div>

                    <!-- Tautan Cepat ke Fitur Consumer Lainnya -->
                    <div class="pt-2 border-t border-[#DCF2E4] space-y-2">
                        <div class="text-[11px] font-bold uppercase tracking-wider text-[#1B5E3A]/80">Tautan Cepat</div>

                        <a href="{{ url('/?view=my-reservations') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-white hover:bg-[#EBF5F0] border border-emerald-100 hover:border-[#1B5E3A]/30 flex items-center justify-between text-xs font-bold text-[#1F2937] transition group">
                            <span class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-[#1B5E3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <span>Reservasi Saya</span>
                            </span>
                            <span class="inline-flex items-center gap-1.5">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#FEF3C7] text-[#B45309]" x-text="impactStats.activeReservations + ' Akan Diambil'">2 Akan Diambil</span>
                                <svg class="w-4 h-4 text-stone-400 group-hover:text-[#1B5E3A] group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        </a>

                        <a href="{{ url('/?view=notifications') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-white hover:bg-[#EBF5F0] border border-emerald-100 hover:border-[#1B5E3A]/30 flex items-center justify-between text-xs font-bold text-[#1F2937] transition group">
                            <span class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-[#E67E22]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <span>Notifikasi</span>
                            </span>
                            <span class="inline-flex items-center gap-1.5">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#E67E22] text-white" x-text="impactStats.unreadNotifications + ' Baru'">3 Baru</span>
                                <svg class="w-4 h-4 text-stone-400 group-hover:text-[#1B5E3A] group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        </a>

                        <a href="{{ url('/?view=explore') }}"
                           class="w-full mt-1 py-2.5 px-4 rounded-xl bg-[#1B5E3A] hover:bg-[#14472C] text-white font-bold text-xs sm:text-sm flex items-center justify-center gap-2 shadow-sm shadow-[#1B5E3A]/20 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span>Jelajahi Makanan Surplus</span>
                        </a>
                    </div>
                </div>

            </aside>

        </div>
    </div>
</div>
@endsection
