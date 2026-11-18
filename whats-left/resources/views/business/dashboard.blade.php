@extends('layouts.business')

@section('title', "Dashboard Merchant - Sunny Bakery")

@section('content')
<div class="flex h-screen overflow-hidden bg-[#FAF7F2]"
     x-data="{
         // Sidebar active state
         activeNav: 'dashboard',
         mobileSidebarOpen: false,

         // Topbar dropdowns
         profileDropdownOpen: false,
         notificationDropdownOpen: false,
         unreadCount: 3,

         // Date range & chart filter states
         selectedDateRange: '2 - 8 Mei 2025',
         dateDropdownOpen: false,
         chartPeriod: 'Mingguan',
         chartPeriodDropdownOpen: false,
         impactPeriod: 'Minggu ini',
         impactDropdownOpen: false,

         // Active hover on performance chart
         hoveredPoint: null,
         chartData: [
             { day: '2 Mei', porsi: 300, pendapatan: 220000, porsiY: 100, rpY: 130 },
             { day: '3 Mei', porsi: 360, pendapatan: 320000, porsiY: 76, rpY: 96 },
             { day: '4 Mei', porsi: 450, pendapatan: 320000, porsiY: 45, rpY: 96 },
             { day: '5 Mei', porsi: 300, pendapatan: 210000, porsiY: 100, rpY: 135 },
             { day: '6 Mei', porsi: 420, pendapatan: 380000, porsiY: 55, rpY: 72 },
             { day: '7 Mei', porsi: 510, pendapatan: 450000, porsiY: 25, rpY: 45 },
             { day: '8 Mei', porsi: 285, pendapatan: 240000, porsiY: 106, rpY: 122 }
         ],

         // Active Surplus Food list
         surplusFoods: [
             {
                 id: 1,
                 title: 'Roti Cokelat',
                 stock: 18,
                 unit: 'pcs',
                 price: 8000,
                 pickupTime: 'Ambil hari ini 16.00 - 20.00',
                 status: 'Tersedia',
                 reserved: 12,
                 image: 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=300&q=80'
             },
             {
                 id: 2,
                 title: 'Chicken Sandwich',
                 stock: 10,
                 unit: 'pcs',
                 price: 15000,
                 pickupTime: 'Ambil hari ini 18.00 - 21.00',
                 status: 'Tersedia',
                 reserved: 6,
                 image: 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?auto=format&fit=crop&w=300&q=80'
             },
             {
                 id: 3,
                 title: 'Salad Sayur Segar',
                 stock: 6,
                 unit: 'bowl',
                 price: 12000,
                 pickupTime: 'Ambil hari ini 17.00 - 20.00',
                 status: 'Tersedia',
                 reserved: 3,
                 image: 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=300&q=80'
             },
             {
                 id: 4,
                 title: 'Donut Mix',
                 stock: 20,
                 unit: 'pcs',
                 price: 10000,
                 pickupTime: 'Ambil hari ini 16.00 - 20.00',
                 status: 'Tersedia',
                 reserved: 8,
                 image: 'https://images.unsplash.com/photo-1527515637462-cff94eecc1ac?auto=format&fit=crop&w=300&q=80'
             }
         ],

         // Recent Orders
         recentOrders: [
             {
                 id: 'WL-0821',
                 name: 'Dinda Ayu',
                 time: '2 Mei 2025, 19.45',
                 items: 3,
                 total: 45000,
                 status: 'Selesai',
                 avatar: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=150&q=80'
             },
             {
                 id: 'WL-0822',
                 name: 'Budi Santoso',
                 time: '2 Mei 2025, 19.20',
                 items: 2,
                 total: 26000,
                 status: 'Selesai',
                 avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&q=80'
             },
             {
                 id: 'WL-0823',
                 name: 'Citra Lestari',
                 time: '2 Mei 2025, 18.50',
                 items: 4,
                 total: 60000,
                 status: 'Diproses',
                 avatar: 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=150&q=80'
             },
             {
                 id: 'WL-0824',
                 name: 'Fahri Ramadhan',
                 time: '2 Mei 2025, 18.15',
                 items: 1,
                 total: 12000,
                 status: 'Diproses',
                 avatar: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=150&q=80'
             },
             {
                 id: 'WL-0825',
                 name: 'Siti Nurhaliza',
                 time: '2 Mei 2025, 17.40',
                 items: 2,
                 total: 28000,
                 status: 'Selesai',
                 avatar: 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=150&q=80'
             }
         ],

         // Modal Tambah Surplus Makanan
         isAddModalOpen: false,
         newSurplus: {
             title: '',
             category: 'Roti & Kue',
             originalPrice: '',
             discountPrice: '',
             stock: '',
             unit: 'pcs',
             pickupStart: '17:00',
             pickupEnd: '20:30',
             freshness: 'Dipanggang hari ini pukul 06.00 WIB',
             image: 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=300&q=80'
         },

         openAddModal() {
             this.newSurplus = {
                 title: '',
                 category: 'Roti & Kue',
                 originalPrice: '',
                 discountPrice: '',
                 stock: '',
                 unit: 'pcs',
                 pickupStart: '17:00',
                 pickupEnd: '20:30',
                 freshness: 'Dipanggang hari ini pukul 06.00 WIB',
                 image: 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=300&q=80'
             };
             this.isAddModalOpen = true;
         },

         submitSurplus() {
             if (!this.newSurplus.title || !this.newSurplus.discountPrice || !this.newSurplus.stock) {
                 this.$dispatch('show-toast', { msg: 'Harap lengkapi nama makanan, harga surplus, dan jumlah stok!', type: 'error' });
                 return;
             }

             this.surplusFoods.unshift({
                 id: Date.now(),
                 title: this.newSurplus.title,
                 stock: parseInt(this.newSurplus.stock),
                 unit: this.newSurplus.unit,
                 price: parseInt(this.newSurplus.discountPrice),
                 pickupTime: `Ambil hari ini ${this.newSurplus.pickupStart} - ${this.newSurplus.pickupEnd}`,
                 status: 'Tersedia',
                 reserved: 0,
                 image: this.newSurplus.image
             });

             this.isAddModalOpen = false;
             this.$dispatch('show-toast', { msg: `Menu surplus '${this.newSurplus.title}' berhasil ditambahkan ke listing!` });
         },

         // Modal Order Detail
         selectedOrder: null,
         openOrderDetail(order) {
             this.selectedOrder = order;
         },
         updateOrderStatus(order, newStatus) {
             order.status = newStatus;
             this.selectedOrder = null;
             this.$dispatch('show-toast', { msg: `Status pesanan ${order.id} berhasil diubah menjadi ${newStatus}!` });
         },

         formatRupiah(num) {
             return 'Rp ' + Number(num).toLocaleString('id-ID');
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
    <aside :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-stone-200/80 flex flex-col justify-between transition-transform duration-300 ease-in-out lg:static lg:z-auto">
        
        <!-- Top Section: Brand Logo & Navigation Links -->
        <div class="px-5 pt-6 pb-4 flex flex-col h-full overflow-y-auto custom-scrollbar">
            
            <!-- Logo What's Left (Match screenshot visual) -->
            <div class="flex items-center justify-between mb-8 pl-1">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 group">
                    <!-- Sprout / leaf icon mark -->
                    <div class="w-8 h-8 rounded-lg bg-[#1B5E3A] flex items-center justify-center text-white shadow-xs">
                        <svg class="w-4 h-4 text-[#F59E0B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="flex items-center text-2xl font-black tracking-tight leading-none">
                        <span class="text-[#1B5E3A]">What's</span>
                        <span class="text-[#E67E22] ml-1">Left</span>
                        <span class="inline-block text-[#1B5E3A] text-xs font-bold ml-0.5">🌱</span>
                    </div>
                </a>

                <!-- Mobile Close Button -->
                <button type="button" 
                        @click="mobileSidebarOpen = false"
                        class="p-1 rounded-lg text-stone-400 hover:text-stone-700 hover:bg-stone-100 lg:hidden">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Navigation Menu Items -->
            <nav class="space-y-1.5 flex-1">
                
                <!-- 1. Dashboard (Active Highlight) -->
                <button type="button"
                        @click="activeNav = 'dashboard'; mobileSidebarOpen = false"
                        :class="activeNav === 'dashboard' 
                            ? 'bg-[#EBF5F0] text-[#1B5E3A] font-bold shadow-xs' 
                            : 'text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium'"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Dashboard</span>
                </button>

                <!-- 2. Makanan Surplus -->
                <a href="{{ url('/?view=makanan-surplus') }}"
                   class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                    <span>Makanan Surplus</span>
                </a>

                <!-- 3. Produksi Harian -->
                <button type="button"
                        @click="activeNav = 'produksi'; mobileSidebarOpen = false; $dispatch('show-toast', { msg: 'Menu Produksi Harian dipilih' })"
                        :class="activeNav === 'produksi' 
                            ? 'bg-[#EBF5F0] text-[#1B5E3A] font-bold' 
                            : 'text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium'"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    <span>Produksi Harian</span>
                </button>

                <!-- 4. Reservasi (with Badge 5) -->
                <button type="button"
                        @click="activeNav = 'reservasi'; mobileSidebarOpen = false; $dispatch('show-toast', { msg: 'Menu Reservasi (5 reservasi aktif) dipilih' })"
                        :class="activeNav === 'reservasi' 
                            ? 'bg-[#EBF5F0] text-[#1B5E3A] font-bold' 
                            : 'text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium'"
                        class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Reservasi</span>
                    </div>
                    <!-- Red/Orange Notification Badge -->
                    <span class="w-5 h-5 rounded-full bg-[#EA580C] text-white text-[11px] font-bold flex items-center justify-center shadow-xs">
                        5
                    </span>
                </button>

                <!-- 5. Pelanggan -->
                <button type="button"
                        @click="activeNav = 'pelanggan'; mobileSidebarOpen = false; $dispatch('show-toast', { msg: 'Menu Pelanggan dipilih' })"
                        :class="activeNav === 'pelanggan' 
                            ? 'bg-[#EBF5F0] text-[#1B5E3A] font-bold' 
                            : 'text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium'"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span>Pelanggan</span>
                </button>

                <!-- Subtle Section Spacer -->
                <div class="py-2"></div>

                <!-- 6. Analitik -->
                <button type="button"
                        @click="activeNav = 'analitik'; mobileSidebarOpen = false; $dispatch('show-toast', { msg: 'Menu Analitik dipilih' })"
                        :class="activeNav === 'analitik' 
                            ? 'bg-[#EBF5F0] text-[#1B5E3A] font-bold' 
                            : 'text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium'"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span>Analitik</span>
                </button>

                <!-- 7. Promo & Diskon -->
                <button type="button"
                        @click="activeNav = 'promo'; mobileSidebarOpen = false; $dispatch('show-toast', { msg: 'Menu Promo & Diskon dipilih' })"
                        :class="activeNav === 'promo' 
                            ? 'bg-[#EBF5F0] text-[#1B5E3A] font-bold' 
                            : 'text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium'"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <span>Promo & Diskon</span>
                </button>

                <!-- 8. Review -->
                <button type="button"
                        @click="activeNav = 'review'; mobileSidebarOpen = false; $dispatch('show-toast', { msg: 'Menu Review dipilih' })"
                        :class="activeNav === 'review' 
                            ? 'bg-[#EBF5F0] text-[#1B5E3A] font-bold' 
                            : 'text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium'"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    <span>Review</span>
                </button>

                <!-- Subtle Section Spacer -->
                <div class="py-2"></div>

                <!-- 9. Pengaturan Toko -->
                <button type="button"
                        @click="activeNav = 'pengaturan'; mobileSidebarOpen = false; $dispatch('show-toast', { msg: 'Menu Pengaturan Toko dipilih' })"
                        :class="activeNav === 'pengaturan' 
                            ? 'bg-[#EBF5F0] text-[#1B5E3A] font-bold' 
                            : 'text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium'"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Pengaturan Toko</span>
                </button>

                <!-- 10. Akun & Tim -->
                <button type="button"
                        @click="activeNav = 'akun'; mobileSidebarOpen = false; $dispatch('show-toast', { msg: 'Menu Akun & Tim dipilih' })"
                        :class="activeNav === 'akun' 
                            ? 'bg-[#EBF5F0] text-[#1B5E3A] font-bold' 
                            : 'text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium'"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>Akun & Tim</span>
                </button>

            </nav>

            <!-- Bottom Appreciation Card ("Terima kasih! Kamu sudah membantu...") -->
            <div class="mt-6 mb-2 p-4 rounded-2xl bg-[#FDFBF7] border border-stone-200/70 text-center relative overflow-hidden shadow-xs">
                
                <!-- Cute illustration (bag, salad bowl, croissant) -->
                <div class="relative w-24 h-16 mx-auto mb-2 flex items-center justify-center">
                    <!-- Paper Bag with Sprout -->
                    <div class="w-12 h-14 bg-[#DCF2E4] border border-[#1B5E3A]/20 rounded-lg flex flex-col items-center justify-center relative shadow-xs">
                        <div class="w-6 h-3 border-2 border-[#1B5E3A] border-b-0 rounded-t-full -mt-2 mb-1"></div>
                        <span class="text-xs">🌱</span>
                    </div>
                    <!-- Salad bowl overlay -->
                    <div class="w-9 h-7 bg-amber-100 rounded-full border border-amber-300 flex items-center justify-center absolute -bottom-1 -left-2 text-[11px] shadow-xs">
                        🥗
                    </div>
                    <!-- Croissant overlay -->
                    <div class="w-9 h-7 bg-orange-100 rounded-full border border-orange-300 flex items-center justify-center absolute -bottom-1 -right-2 text-[11px] shadow-xs">
                        🥐
                    </div>
                </div>

                <h4 class="text-sm font-extrabold text-stone-900">Terima kasih!</h4>
                <p class="text-xs text-stone-500 mt-0.5">Kamu sudah membantu</p>
                <div class="text-2xl font-black text-[#1B5E3A] my-0.5 tracking-tight">1.248</div>
                <p class="text-[11px] text-stone-500 leading-snug">porsi makanan terselamatkan dari food waste 🎉</p>
            </div>

        </div>
    </aside>

    <!-- ======================================================= -->
    <!-- 2. MAIN CONTENT AREA (TOPBAR + SCROLLABLE DASHBOARD)   -->
    <!-- ======================================================= -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- TOPBAR NAVIGATION -->
        <header class="h-16 bg-white border-b border-stone-200/80 px-4 sm:px-6 lg:px-8 flex items-center justify-between shrink-0">
            
            <!-- Left: Mobile Menu Toggle Button -->
            <div class="flex items-center gap-3">
                <button type="button" 
                        @click="mobileSidebarOpen = true"
                        class="p-2 rounded-xl text-stone-600 hover:bg-stone-100 focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 lg:hidden"
                        title="Buka Menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <!-- Right: Notifications & Profile -->
            <div class="flex items-center gap-4">
                
                <!-- Notification Bell Button -->
                <div class="relative" x-data="{ open: false }">
                    <button type="button" 
                            @click="open = !open"
                            class="relative p-2 rounded-xl text-stone-600 hover:text-stone-900 hover:bg-stone-100 transition focus:outline-none">
                        <!-- Bell Icon -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <!-- Orange Notification Badge (Match screenshot '3') -->
                        <span class="absolute top-1 right-1 w-4 h-4 bg-[#EA580C] text-white text-[10px] font-bold rounded-full flex items-center justify-center shadow-xs">
                            3
                        </span>
                    </button>

                    <!-- Notification Dropdown Menu -->
                    <div x-cloak x-show="open" 
                         @click.outside="open = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-xl border border-stone-200 py-3 z-50">
                        <div class="px-4 pb-2 border-b border-stone-100 flex items-center justify-between">
                            <span class="text-sm font-bold text-stone-900">Notifikasi Toko</span>
                            <span class="text-[11px] font-semibold text-[#1B5E3A] cursor-pointer hover:underline" @click="open = false; $dispatch('show-toast', { msg: 'Semua notifikasi ditandai telah dibaca' })">Tandai Dibaca</span>
                        </div>
                        <div class="divide-y divide-stone-100 max-h-72 overflow-y-auto custom-scrollbar">
                            <div class="p-3.5 hover:bg-stone-50 transition flex items-start gap-3">
                                <span class="w-2 h-2 rounded-full bg-[#1B5E3A] mt-1.5 shrink-0"></span>
                                <div>
                                    <p class="text-xs font-semibold text-stone-800">Reservasi Baru #WL-0821</p>
                                    <p class="text-[11px] text-stone-500 mt-0.5">Dinda Ayu telah memesan 3 item surplus (Rp 45.000).</p>
                                    <span class="text-[10px] text-stone-400 mt-1 block">5 menit yang lalu</span>
                                </div>
                            </div>
                            <div class="p-3.5 hover:bg-stone-50 transition flex items-start gap-3">
                                <span class="w-2 h-2 rounded-full bg-[#EA580C] mt-1.5 shrink-0"></span>
                                <div>
                                    <p class="text-xs font-semibold text-stone-800">Stok Surplus Menipis!</p>
                                    <p class="text-[11px] text-stone-500 mt-0.5">Sisa stok Salad Sayur Segar tinggal 6 bowl tersisa.</p>
                                    <span class="text-[10px] text-stone-400 mt-1 block">30 menit yang lalu</span>
                                </div>
                            </div>
                            <div class="p-3.5 hover:bg-stone-50 transition flex items-start gap-3">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 mt-1.5 shrink-0"></span>
                                <div>
                                    <p class="text-xs font-semibold text-stone-800">Laporan Mingguan Siap Diunduh</p>
                                    <p class="text-[11px] text-stone-500 mt-0.5">Ringkasan performa 2 - 8 Mei 2025 telah terbit.</p>
                                    <span class="text-[10px] text-stone-400 mt-1 block">2 jam yang lalu</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vertical Divider -->
                <div class="w-px h-6 bg-stone-200"></div>

                <!-- Sunny Bakery Merchant Profile Dropdown -->
                <div class="relative" x-data="{ openProfile: false }">
                    <button type="button" 
                            @click="openProfile = !openProfile"
                            class="flex items-center gap-2.5 p-1 rounded-xl hover:bg-stone-100 transition focus:outline-none">
                        <!-- Merchant Avatar -->
                        <div class="w-9 h-9 rounded-full bg-amber-100 border border-amber-200 flex items-center justify-center overflow-hidden shrink-0">
                            <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=100&q=80" 
                                 alt="Sunny Bakery" 
                                 class="w-full h-full object-cover">
                        </div>
                        
                        <!-- Name & Role -->
                        <div class="hidden sm:flex flex-col text-left">
                            <span class="text-xs font-bold text-stone-900 leading-tight">Sunny Bakery</span>
                            <span class="text-[10px] font-semibold text-[#1B5E3A] leading-tight">Business</span>
                        </div>

                        <!-- Chevron Down -->
                        <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Profile Dropdown Content -->
                    <div x-cloak x-show="openProfile" 
                         @click.outside="openProfile = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-stone-200 py-1.5 z-50">
                        <div class="px-4 py-2.5 border-b border-stone-100">
                            <p class="text-xs font-bold text-stone-800">Sunny Bakery Bandung</p>
                            <p class="text-[11px] text-stone-500">manager@sunnybakery.com</p>
                        </div>
                        
                        <a href="{{ url('/') }}" 
                           class="flex items-center gap-2 px-4 py-2 text-xs text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A] transition">
                            <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>Tampilan Konsumen</span>
                        </a>

                        <button type="button" 
                                @click="openProfile = false; $dispatch('show-toast', { msg: 'Membuka pengaturan profil toko...' })"
                                class="w-full text-left flex items-center gap-2 px-4 py-2 text-xs text-stone-700 hover:bg-stone-50 transition">
                            <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>Profil Toko & Jam Operasional</span>
                        </button>

                        <div class="border-t border-stone-100 my-1"></div>

                        <a href="{{ Route::has('login') ? route('login') : url('/?view=login') }}" 
                           class="flex items-center gap-2 px-4 py-2 text-xs text-red-600 hover:bg-red-50 transition font-semibold">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>Keluar (Logout)</span>
                        </a>
                    </div>
                </div>

            </div>

        </header>

        <!-- DASHBOARD SCROLLABLE BODY -->
        <main class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6 custom-scrollbar">
            
            <!-- ======================================================= -->
            <!-- 3. HEADER GREETING, DATE FILTER & CTA BUTTON           -->
            <!-- ======================================================= -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                
                <!-- Greeting -->
                <div>
                    <h1 class="text-2xl sm:text-[26px] font-black text-stone-900 tracking-tight flex items-center gap-2">
                        <span>Good morning, Sunny Bakery!</span>
                        <span class="inline-block transform hover:rotate-12 transition">👋</span>
                    </h1>
                    <p class="text-xs sm:text-sm text-stone-500 mt-1">
                        Kelola makanan surplusmu dan bantu lebih banyak orang hari ini.
                    </p>
                </div>

                <!-- Action Controls: Date Dropdown & + Tambah Surplus Button -->
                <div class="flex items-center gap-3">
                    
                    <!-- Date Filter Dropdown -->
                    <div class="relative">
                        <button type="button" 
                                @click="dateDropdownOpen = !dateDropdownOpen"
                                class="bg-white border border-stone-200/90 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-stone-700 flex items-center gap-2.5 shadow-xs hover:border-stone-300 transition">
                            <svg class="w-4 h-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span x-text="selectedDateRange"></span>
                            <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-cloak x-show="dateDropdownOpen" 
                             @click.outside="dateDropdownOpen = false"
                             class="absolute right-0 mt-1.5 w-48 bg-white rounded-xl shadow-xl border border-stone-200 py-1 z-30">
                            <button type="button" 
                                    @click="selectedDateRange = '2 - 8 Mei 2025'; dateDropdownOpen = false; $dispatch('show-toast', { msg: 'Filter rentang: 2 - 8 Mei 2025' })"
                                    class="w-full text-left px-3.5 py-2 text-xs hover:bg-stone-50 font-medium"
                                    :class="selectedDateRange === '2 - 8 Mei 2025' ? 'text-[#1B5E3A] font-bold bg-[#EBF5F0]/50' : 'text-stone-700'">
                                2 - 8 Mei 2025 (Minggu Ini)
                            </button>
                            <button type="button" 
                                    @click="selectedDateRange = 'Hari Ini (8 Mei 2025)'; dateDropdownOpen = false; $dispatch('show-toast', { msg: 'Filter: Hari Ini' })"
                                    class="w-full text-left px-3.5 py-2 text-xs hover:bg-stone-50 font-medium text-stone-700">
                                Hari Ini (8 Mei 2025)
                            </button>
                            <button type="button" 
                                    @click="selectedDateRange = 'Bulan Ini (Mei 2025)'; dateDropdownOpen = false; $dispatch('show-toast', { msg: 'Filter: Mei 2025' })"
                                    class="w-full text-left px-3.5 py-2 text-xs hover:bg-stone-50 font-medium text-stone-700">
                                Bulan Ini (Mei 2025)
                            </button>
                        </div>
                    </div>

                    <!-- Primary Action Button: + Tambah Surplus Makanan -->
                    <button type="button" 
                            @click="openAddModal()"
                            class="bg-[#1B5E3A] hover:bg-[#14472C] text-white px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold flex items-center gap-2 shadow-sm shadow-[#1B5E3A]/25 transition active:scale-98">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Tambah Surplus Makanan</span>
                    </button>

                </div>

            </div>

            <!-- ======================================================= -->
            <!-- 4. TOP STATS ROW (4 CARDS)                              -->
            <!-- ======================================================= -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
                
                <!-- Stat 1: Total Porsi Terselamatkan -->
                <div class="bg-white rounded-2xl p-5 border border-stone-200/80 shadow-xs flex items-center gap-4 hover:border-emerald-200 transition">
                    <!-- Icon Bag Green -->
                    <div class="w-12 h-12 rounded-xl bg-[#E8F5E9] text-[#1B5E3A] flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <!-- Details -->
                    <div class="flex-1 min-w-0">
                        <span class="text-xs text-stone-500 font-medium block">Total Porsi Terselamatkan</span>
                        <div class="flex items-baseline gap-1 mt-0.5">
                            <span class="text-2xl font-black text-stone-900 tracking-tight">1.248</span>
                            <span class="text-xs text-stone-500 font-normal">porsi</span>
                        </div>
                        <div class="flex items-center gap-1 text-[11px] text-emerald-600 font-semibold mt-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                            </svg>
                            <span>18.5% dari minggu lalu</span>
                        </div>
                    </div>
                </div>

                <!-- Stat 2: Total Pesanan -->
                <div class="bg-white rounded-2xl p-5 border border-stone-200/80 shadow-xs flex items-center gap-4 hover:border-amber-200 transition">
                    <!-- Icon Box Orange -->
                    <div class="w-12 h-12 rounded-xl bg-[#FFF3E0] text-[#F59E0B] flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <!-- Details -->
                    <div class="flex-1 min-w-0">
                        <span class="text-xs text-stone-500 font-medium block">Total Pesanan</span>
                        <div class="flex items-baseline gap-1 mt-0.5">
                            <span class="text-2xl font-black text-stone-900 tracking-tight">86</span>
                            <span class="text-xs text-stone-500 font-normal">pesanan</span>
                        </div>
                        <div class="flex items-center gap-1 text-[11px] text-emerald-600 font-semibold mt-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                            </svg>
                            <span>22.1% dari minggu lalu</span>
                        </div>
                    </div>
                </div>

                <!-- Stat 3: Total Pendapatan -->
                <div class="bg-white rounded-2xl p-5 border border-stone-200/80 shadow-xs flex items-center gap-4 hover:border-orange-200 transition">
                    <!-- Icon Rp Coin -->
                    <div class="w-12 h-12 rounded-xl bg-[#FBE9E7] text-[#E67E22] flex items-center justify-center shrink-0 font-black text-sm">
                        <span class="w-7 h-7 rounded-full border-2 border-[#E67E22] flex items-center justify-center text-xs">Rp</span>
                    </div>
                    <!-- Details -->
                    <div class="flex-1 min-w-0">
                        <span class="text-xs text-stone-500 font-medium block">Total Pendapatan</span>
                        <div class="flex items-baseline gap-1 mt-0.5">
                            <span class="text-2xl font-black text-stone-900 tracking-tight">Rp 3.247.000</span>
                        </div>
                        <div class="flex items-center gap-1 text-[11px] text-emerald-600 font-semibold mt-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                            </svg>
                            <span>19.8% dari minggu lalu</span>
                        </div>
                    </div>
                </div>

                <!-- Stat 4: Pelanggan Baru -->
                <div class="bg-white rounded-2xl p-5 border border-stone-200/80 shadow-xs flex items-center gap-4 hover:border-blue-200 transition">
                    <!-- Icon Users Blue -->
                    <div class="w-12 h-12 rounded-xl bg-[#E3F2FD] text-[#1976D2] flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <!-- Details -->
                    <div class="flex-1 min-w-0">
                        <span class="text-xs text-stone-500 font-medium block">Pelanggan Baru</span>
                        <div class="flex items-baseline gap-1 mt-0.5">
                            <span class="text-2xl font-black text-stone-900 tracking-tight">23</span>
                            <span class="text-xs text-stone-500 font-normal">pelanggan</span>
                        </div>
                        <div class="flex items-center gap-1 text-[11px] text-emerald-600 font-semibold mt-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                            </svg>
                            <span>15.0% dari minggu lalu</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ======================================================= -->
            <!-- 5. MIDDLE ROW: PERFORMANCE CHART & SURPLUS MAKANAN     -->
            <!-- ======================================================= -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                
                <!-- Left: Ringkasan Kinerja (Line Chart SVG) -->
                <div class="lg:col-span-8 bg-white rounded-2xl p-5 sm:p-6 border border-stone-200/80 shadow-xs flex flex-col justify-between">
                    
                    <!-- Chart Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                        <div>
                            <h3 class="text-base font-bold text-stone-900">Ringkasan Kinerja</h3>
                        </div>

                        <!-- Legend & Dropdown -->
                        <div class="flex items-center gap-4 flex-wrap">
                            <!-- Green Legend -->
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#1B5E3A]"></span>
                                <span class="text-xs text-stone-600 font-medium">Porsi Terselamatkan (porsi)</span>
                            </div>
                            <!-- Orange Legend -->
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#E67E22]"></span>
                                <span class="text-xs text-stone-600 font-medium">Pendapatan (Rp)</span>
                            </div>

                            <!-- Mingguan Dropdown -->
                            <div class="relative">
                                <button type="button" 
                                        @click="chartPeriodDropdownOpen = !chartPeriodDropdownOpen"
                                        class="text-xs border border-stone-200 rounded-lg px-2.5 py-1 text-stone-700 bg-white hover:bg-stone-50 flex items-center gap-1 font-semibold">
                                    <span x-text="chartPeriod"></span>
                                    <svg class="w-3 h-3 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div x-cloak x-show="chartPeriodDropdownOpen" 
                                     @click.outside="chartPeriodDropdownOpen = false"
                                     class="absolute right-0 mt-1 w-28 bg-white rounded-lg shadow-lg border border-stone-200 py-1 z-20">
                                    <button type="button" @click="chartPeriod = 'Mingguan'; chartPeriodDropdownOpen = false" class="w-full text-left px-3 py-1.5 text-xs hover:bg-stone-50 font-medium text-stone-700">Mingguan</button>
                                    <button type="button" @click="chartPeriod = 'Bulanan'; chartPeriodDropdownOpen = false" class="w-full text-left px-3 py-1.5 text-xs hover:bg-stone-50 font-medium text-stone-700">Bulanan</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart SVG Area -->
                    <div class="relative w-full h-64 sm:h-72">
                        
                        <!-- Left Y-Axis Scale (Porsi) -->
                        <div class="absolute left-0 top-0 bottom-6 w-8 flex flex-col justify-between text-[11px] text-stone-400 font-medium">
                            <span>600</span>
                            <span>450</span>
                            <span>300</span>
                            <span>150</span>
                            <span>0</span>
                        </div>

                        <!-- Right Y-Axis Scale (Pendapatan) -->
                        <div class="absolute right-0 top-0 bottom-6 w-10 flex flex-col justify-between items-end text-[11px] text-stone-400 font-medium">
                            <span>1M</span>
                            <span>750K</span>
                            <span>500K</span>
                            <span>250K</span>
                            <span>0</span>
                        </div>

                        <!-- Chart SVG Container (matches screenshot exact curve points) -->
                        <div class="ml-10 mr-12 h-full flex flex-col justify-between">
                            <div class="relative w-full h-[calc(100%-24px)]">
                                <svg class="w-full h-full overflow-visible" viewBox="0 0 600 200" preserveAspectRatio="none">
                                    <defs>
                                        <!-- Green Area Gradient -->
                                        <linearGradient id="greenGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                            <stop offset="0%" stop-color="#1B5E3A" stop-opacity="0.12"/>
                                            <stop offset="100%" stop-color="#1B5E3A" stop-opacity="0.0"/>
                                        </linearGradient>
                                        <!-- Orange Area Gradient -->
                                        <linearGradient id="orangeGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                            <stop offset="0%" stop-color="#E67E22" stop-opacity="0.10"/>
                                            <stop offset="100%" stop-color="#E67E22" stop-opacity="0.0"/>
                                        </linearGradient>
                                    </defs>

                                    <!-- Grid Lines -->
                                    <line x1="0" y1="0" x2="600" y2="0" stroke="#F3F4F6" stroke-width="1"/>
                                    <line x1="0" y1="50" x2="600" y2="50" stroke="#F3F4F6" stroke-width="1"/>
                                    <line x1="0" y1="100" x2="600" y2="100" stroke="#F3F4F6" stroke-width="1"/>
                                    <line x1="0" y1="150" x2="600" y2="150" stroke="#F3F4F6" stroke-width="1"/>
                                    <line x1="0" y1="200" x2="600" y2="200" stroke="#E5E7EB" stroke-width="1"/>

                                    <!-- Area Fill Green (Porsi) -->
                                    <path d="M 0,100 C 50,75 50,75 100,75 C 150,75 150,45 200,45 C 250,45 250,100 300,100 C 350,100 350,55 400,55 C 450,55 450,25 500,25 C 550,25 550,110 600,110 L 600,200 L 0,200 Z"
                                          fill="url(#greenGradient)"/>

                                    <!-- Line Curve Green: Porsi (300 -> 360 -> 450 -> 300 -> 420 -> 510 -> 285) -->
                                    <path d="M 0,100 C 50,75 50,75 100,75 C 150,75 150,45 200,45 C 250,45 250,100 300,100 C 350,100 350,55 400,55 C 450,55 450,25 500,25 C 550,25 550,110 600,110"
                                          fill="none" stroke="#2E7D32" stroke-width="2.5" stroke-linecap="round"/>

                                    <!-- Area Fill Orange (Pendapatan) -->
                                    <path d="M 0,130 C 50,96 50,96 100,96 C 150,96 150,96 200,96 C 250,96 250,135 300,135 C 350,135 350,72 400,72 C 450,72 450,45 500,45 C 550,45 550,122 600,122 L 600,200 L 0,200 Z"
                                          fill="url(#orangeGradient)"/>

                                    <!-- Line Curve Orange: Pendapatan (220k -> 320k -> 320k -> 210k -> 380k -> 450k -> 240k) -->
                                    <path d="M 0,130 C 50,96 50,96 100,96 C 150,96 150,96 200,96 C 250,96 250,135 300,135 C 350,135 350,72 400,72 C 450,72 450,45 500,45 C 550,45 550,122 600,122"
                                          fill="none" stroke="#E67E22" stroke-width="2.5" stroke-linecap="round"/>

                                    <!-- Nodes Green -->
                                    <circle cx="0" cy="100" r="4.5" fill="#2E7D32" stroke="#FFFFFF" stroke-width="2"/>
                                    <circle cx="100" cy="75" r="4.5" fill="#2E7D32" stroke="#FFFFFF" stroke-width="2"/>
                                    <circle cx="200" cy="45" r="4.5" fill="#2E7D32" stroke="#FFFFFF" stroke-width="2"/>
                                    <circle cx="300" cy="100" r="4.5" fill="#2E7D32" stroke="#FFFFFF" stroke-width="2"/>
                                    <circle cx="400" cy="55" r="4.5" fill="#2E7D32" stroke="#FFFFFF" stroke-width="2"/>
                                    <circle cx="500" cy="25" r="4.5" fill="#2E7D32" stroke="#FFFFFF" stroke-width="2"/>
                                    <circle cx="600" cy="110" r="4.5" fill="#2E7D32" stroke="#FFFFFF" stroke-width="2"/>

                                    <!-- Nodes Orange -->
                                    <circle cx="0" cy="130" r="4.5" fill="#E67E22" stroke="#FFFFFF" stroke-width="2"/>
                                    <circle cx="100" cy="96" r="4.5" fill="#E67E22" stroke="#FFFFFF" stroke-width="2"/>
                                    <circle cx="200" cy="96" r="4.5" fill="#E67E22" stroke="#FFFFFF" stroke-width="2"/>
                                    <circle cx="300" cy="135" r="4.5" fill="#E67E22" stroke="#FFFFFF" stroke-width="2"/>
                                    <circle cx="400" cy="72" r="4.5" fill="#E67E22" stroke="#FFFFFF" stroke-width="2"/>
                                    <circle cx="500" cy="45" r="4.5" fill="#E67E22" stroke="#FFFFFF" stroke-width="2"/>
                                    <circle cx="600" cy="122" r="4.5" fill="#E67E22" stroke="#FFFFFF" stroke-width="2"/>
                                </svg>

                                <!-- Interactive Hover Pillars -->
                                <div class="absolute inset-0 grid grid-cols-7">
                                    <template x-for="(pt, idx) in chartData" :key="pt.day">
                                        <div class="h-full flex items-center justify-center cursor-pointer group relative"
                                             @mouseenter="hoveredPoint = pt"
                                             @mouseleave="hoveredPoint = null">
                                            <div class="w-full h-full group-hover:bg-stone-50/50 transition"></div>
                                        </div>
                                    </template>
                                </div>

                                <!-- Tooltip Overlay -->
                                <template x-if="hoveredPoint">
                                    <div class="absolute top-2 left-1/2 transform -translate-x-1/2 bg-stone-900 text-white text-[11px] px-3 py-1.5 rounded-xl shadow-xl border border-stone-700 pointer-events-none z-30 flex items-center gap-3">
                                        <span class="font-bold text-stone-200" x-text="hoveredPoint.day"></span>
                                        <span class="text-emerald-400 font-semibold" x-text="hoveredPoint.porsi + ' porsi'"></span>
                                        <span class="text-amber-400 font-semibold" x-text="formatRupiah(hoveredPoint.pendapatan)"></span>
                                    </div>
                                </template>

                            </div>

                            <!-- X-Axis Labels (2 Mei s/d 8 Mei) -->
                            <div class="w-full flex justify-between text-[11px] text-stone-500 font-medium pt-2 border-t border-transparent">
                                <span>2 Mei</span>
                                <span>3 Mei</span>
                                <span>4 Mei</span>
                                <span>5 Mei</span>
                                <span>6 Mei</span>
                                <span>7 Mei</span>
                                <span>8 Mei</span>
                            </div>

                        </div>

                    </div>

                </div>

                <!-- Right: Makanan Surplus Aktif -->
                <div class="lg:col-span-4 bg-white rounded-2xl p-5 sm:p-6 border border-stone-200/80 shadow-xs flex flex-col justify-between">
                    
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-bold text-stone-900">Makanan Surplus Aktif</h3>
                        <button type="button" 
                                @click="$dispatch('show-toast', { msg: 'Membuka seluruh katalog makanan surplus...' })"
                                class="text-xs font-semibold text-[#1B5E3A] hover:underline">
                            Lihat Semua
                        </button>
                    </div>

                    <!-- Items List -->
                    <div class="divide-y divide-stone-100 flex-1 space-y-3">
                        <template x-for="item in surplusFoods" :key="item.id">
                            <div class="pt-3 first:pt-0 flex items-center justify-between gap-3 group">
                                <!-- Food Image & Details -->
                                <div class="flex items-center gap-3 min-w-0">
                                    <img :src="item.image" 
                                         :alt="item.title"
                                         class="w-12 h-12 rounded-xl object-cover border border-stone-100 shrink-0 shadow-xs group-hover:scale-105 transition duration-200">
                                    <div class="min-w-0">
                                        <h4 class="text-xs font-bold text-stone-900 truncate" x-text="item.title"></h4>
                                        <p class="text-[11px] text-stone-500 mt-0.5" 
                                           x-text="'Tersisa ' + item.stock + ' ' + item.unit + ' • ' + formatRupiah(item.price)"></p>
                                        <p class="text-[10px] text-stone-400 mt-0.5 flex items-center gap-1">
                                            <span>⏰</span>
                                            <span x-text="item.pickupTime"></span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Right: Badge Tersedia & Reserved Counter -->
                                <div class="text-right shrink-0">
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-[#EBF5F0] text-[#1B5E3A] border border-[#DCF2E4]">
                                        Tersedia
                                    </span>
                                    <p class="text-[11px] text-stone-400 font-medium mt-1" x-text="item.reserved + ' reserved'"></p>
                                </div>
                            </div>
                        </template>
                    </div>

                </div>

            </div>

            <!-- ======================================================= -->
            <!-- 6. BOTTOM ROW: PENJUALAN (DONUT) | PESANAN | DAMPAK   -->
            <!-- ======================================================= -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-6">
                
                <!-- Col 1: Penjualan (Donut Chart) -->
                <div class="lg:col-span-4 bg-white rounded-2xl p-5 sm:p-6 border border-stone-200/80 shadow-xs flex flex-col justify-between">
                    
                    <h3 class="text-base font-bold text-stone-900 mb-4">Penjualan</h3>

                    <div class="flex flex-col sm:flex-row items-center gap-6 my-auto">
                        
                        <!-- Donut Chart SVG -->
                        <div class="relative w-36 h-36 shrink-0 flex items-center justify-center">
                            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                                <!-- Background track -->
                                <circle cx="50" cy="50" r="38" stroke="#F3F4F6" stroke-width="15" fill="none"/>
                                
                                <!-- Segments:
                                     Circumference = 2 * PI * 38 = 238.76
                                     1. Makanan Utama: 56.9% = 135.8
                                     2. Roti & Kue: 29.3% = 70.0
                                     3. Minuman: 7.6% = 18.1
                                     4. Snack: 6.2% = 14.8
                                -->
                                <!-- 1. Makanan Utama (Green #2E7D32) -->
                                <circle cx="50" cy="50" r="38" 
                                        stroke="#1B5E3A" 
                                        stroke-width="15" 
                                        stroke-dasharray="135.8 238.76" 
                                        stroke-dashoffset="0" 
                                        fill="none"/>

                                <!-- 2. Roti & Kue (Orange #E67E22) -->
                                <circle cx="50" cy="50" r="38" 
                                        stroke="#E67E22" 
                                        stroke-width="15" 
                                        stroke-dasharray="70.0 238.76" 
                                        stroke-dashoffset="-135.8" 
                                        fill="none"/>

                                <!-- 3. Minuman (Amber #F59E0B) -->
                                <circle cx="50" cy="50" r="38" 
                                        stroke="#F59E0B" 
                                        stroke-width="15" 
                                        stroke-dasharray="18.1 238.76" 
                                        stroke-dashoffset="-205.8" 
                                        fill="none"/>

                                <!-- 4. Snack (Pink/Coral #F472B6) -->
                                <circle cx="50" cy="50" r="38" 
                                        stroke="#F472B6" 
                                        stroke-width="15" 
                                        stroke-dasharray="14.8 238.76" 
                                        stroke-dashoffset="-223.9" 
                                        fill="none"/>
                            </svg>

                            <!-- Donut Hole Content -->
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                                <span class="text-[10px] uppercase font-bold text-stone-400 tracking-wider">Total</span>
                                <span class="text-xs font-black text-stone-900 mt-0.5">Rp 3.247.000</span>
                            </div>
                        </div>

                        <!-- Legend List -->
                        <div class="space-y-2.5 flex-1 w-full text-left">
                            
                            <!-- Category 1: Makanan Utama -->
                            <div>
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#1B5E3A]"></span>
                                    <span class="text-xs font-bold text-stone-800">Makanan Utama</span>
                                </div>
                                <span class="text-[11px] text-stone-500 font-medium pl-4 block">Rp 1.850.000 (56.9%)</span>
                            </div>

                            <!-- Category 2: Roti & Kue -->
                            <div>
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#E67E22]"></span>
                                    <span class="text-xs font-bold text-stone-800">Roti & Kue</span>
                                </div>
                                <span class="text-[11px] text-stone-500 font-medium pl-4 block">Rp 950.000 (29.3%)</span>
                            </div>

                            <!-- Category 3: Minuman -->
                            <div>
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#F59E0B]"></span>
                                    <span class="text-xs font-bold text-stone-800">Minuman</span>
                                </div>
                                <span class="text-[11px] text-stone-500 font-medium pl-4 block">Rp 247.000 (7.6%)</span>
                            </div>

                            <!-- Category 4: Snack -->
                            <div>
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#F472B6]"></span>
                                    <span class="text-xs font-bold text-stone-800">Snack</span>
                                </div>
                                <span class="text-[11px] text-stone-500 font-medium pl-4 block">Rp 200.000 (6.2%)</span>
                            </div>

                        </div>

                    </div>

                </div>

                <!-- Col 2: Pesanan Terbaru (List Table) -->
                <div class="lg:col-span-4 bg-white rounded-2xl p-5 sm:p-6 border border-stone-200/80 shadow-xs flex flex-col justify-between">
                    
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-base font-bold text-stone-900">Pesanan Terbaru</h3>
                        <button type="button" 
                                @click="$dispatch('show-toast', { msg: 'Membuka semua daftar pesanan masuk...' })"
                                class="text-xs font-semibold text-[#1B5E3A] hover:underline">
                            Lihat Semua
                        </button>
                    </div>

                    <!-- Orders List (Exact 5 items from screenshot) -->
                    <div class="divide-y divide-stone-100 flex-1">
                        <template x-for="order in recentOrders" :key="order.id">
                            <div class="py-2.5 flex items-center justify-between gap-2.5 cursor-pointer hover:bg-stone-50 px-1.5 rounded-xl transition"
                                 @click="openOrderDetail(order)">
                                
                                <!-- Customer Avatar & Info -->
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <img :src="order.avatar" 
                                         :alt="order.name"
                                         class="w-8 h-8 rounded-full object-cover shrink-0 border border-stone-200">
                                    <div class="min-w-0">
                                        <h4 class="text-xs font-bold text-stone-900 truncate" x-text="order.name"></h4>
                                        <p class="text-[10px] text-stone-400 mt-0.5" x-text="order.time"></p>
                                    </div>
                                </div>

                                <!-- Items & Price -->
                                <div class="text-right shrink-0">
                                    <p class="text-xs text-stone-600 font-semibold" 
                                       x-text="order.items + ' item • ' + formatRupiah(order.total)"></p>
                                </div>

                                <!-- Status Badge -->
                                <div class="shrink-0 text-right">
                                    <span class="inline-block px-2 py-0.5 rounded-md text-[11px] font-semibold"
                                          :class="order.status === 'Selesai' 
                                              ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' 
                                              : 'bg-amber-50 text-amber-700 border border-amber-100'"
                                          x-text="order.status">
                                    </span>
                                </div>

                            </div>
                        </template>
                    </div>

                </div>

                <!-- Col 3: Dampak Positif Kamu (Eco Metric Card + Scenic Landscape) -->
                <div class="lg:col-span-4 bg-white rounded-2xl border border-stone-200/80 shadow-xs flex flex-col justify-between overflow-hidden relative">
                    
                    <div class="p-5 sm:p-6 pb-2">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="text-base font-bold text-stone-900 flex items-center gap-1.5">
                                <span>Dampak Positif Kamu</span>
                                <span>🌱</span>
                            </h3>

                            <!-- Filter Minggu Ini Dropdown -->
                            <div class="relative">
                                <button type="button" 
                                        @click="impactDropdownOpen = !impactDropdownOpen"
                                        class="text-xs text-stone-500 hover:text-stone-800 flex items-center gap-1 font-medium">
                                    <span x-text="impactPeriod"></span>
                                    <svg class="w-3 h-3 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div x-cloak x-show="impactDropdownOpen" 
                                     @click.outside="impactDropdownOpen = false"
                                     class="absolute right-0 mt-1 w-32 bg-white rounded-xl shadow-lg border border-stone-200 py-1 z-20">
                                    <button type="button" @click="impactPeriod = 'Minggu ini'; impactDropdownOpen = false" class="w-full text-left px-3 py-1.5 text-xs hover:bg-stone-50 font-medium text-stone-700">Minggu ini</button>
                                    <button type="button" @click="impactPeriod = 'Bulan ini'; impactDropdownOpen = false" class="w-full text-left px-3 py-1.5 text-xs hover:bg-stone-50 font-medium text-stone-700">Bulan ini</button>
                                    <button type="button" @click="impactPeriod = 'Semua waktu'; impactDropdownOpen = false" class="w-full text-left px-3 py-1.5 text-xs hover:bg-stone-50 font-medium text-stone-700">Semua waktu</button>
                                </div>
                            </div>
                        </div>

                        <!-- 3 Impact Rows -->
                        <div class="space-y-4">
                            
                            <!-- Metric 1: Porsi Makanan Diselamatkan -->
                            <div class="flex items-center justify-between gap-3 group cursor-pointer hover:bg-stone-50/70 p-1.5 rounded-xl transition">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-[#DCF2E4] text-[#1B5E3A] flex items-center justify-center shrink-0">
                                        <!-- Leaf Icon -->
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-extrabold text-stone-900">1.248 porsi makanan</h4>
                                        <p class="text-[11px] text-stone-500">berhasil diselamatkan</p>
                                    </div>
                                </div>
                                <svg class="w-4 h-4 text-stone-400 group-hover:text-stone-700 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>

                            <!-- Metric 2: Emisi Karbon CO2 Dihindari -->
                            <div class="flex items-center justify-between gap-3 group cursor-pointer hover:bg-stone-50/70 p-1.5 rounded-xl transition">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-[#DCF2E4] text-[#1B5E3A] flex items-center justify-center shrink-0">
                                        <!-- Eco Footprint / Cloud Icon -->
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 00-9.78 2.096A4.001 4.001 0 003 15z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-extrabold text-stone-900">312 kg CO₂e</h4>
                                        <p class="text-[11px] text-stone-500">emisi karbon dihindari</p>
                                    </div>
                                </div>
                                <svg class="w-4 h-4 text-stone-400 group-hover:text-stone-700 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>

                            <!-- Metric 3: Penggunaan Air Dihemat -->
                            <div class="flex items-center justify-between gap-3 group cursor-pointer hover:bg-stone-50/70 p-1.5 rounded-xl transition">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-[#E0F2FE] text-[#0284C7] flex items-center justify-center shrink-0">
                                        <!-- Water Drop Icon -->
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-extrabold text-stone-900">18.720 liter air</h4>
                                        <p class="text-[11px] text-stone-500">penggunaan air dihemat</p>
                                    </div>
                                </div>
                                <svg class="w-4 h-4 text-stone-400 group-hover:text-stone-700 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>

                        </div>
                    </div>

                    <!-- Scenic Green Hills & Eco City Landscape Banner at bottom (Exact reflection of screenshot) -->
                    <div class="w-full h-16 mt-4 relative overflow-hidden bg-gradient-to-t from-[#EBF5F0] to-transparent">
                        <svg class="w-full h-full object-cover" viewBox="0 0 400 60" preserveAspectRatio="none">
                            <!-- Background City Skyline silhouette -->
                            <rect x="310" y="25" width="16" height="35" fill="#E2E8F0" rx="1"/>
                            <rect x="330" y="18" width="20" height="42" fill="#CBD5E1" rx="1"/>
                            <rect x="355" y="28" width="14" height="32" fill="#E2E8F0" rx="1"/>
                            <rect x="372" y="22" width="18" height="38" fill="#CBD5E1" rx="1"/>
                            
                            <!-- Sun / Warm glow -->
                            <circle cx="340" cy="15" r="10" fill="#FEF3C7" opacity="0.8"/>

                            <!-- Rolling Hill 1 (Pale green) -->
                            <path d="M 0,40 Q 90,20 200,45 T 400,35 L 400,60 L 0,60 Z" fill="#C8E6C9" opacity="0.6"/>

                            <!-- Rolling Hill 2 (Fresh eco green) -->
                            <path d="M 0,48 Q 120,32 260,52 T 400,42 L 400,60 L 0,60 Z" fill="#A5D6A7"/>

                            <!-- Rolling Hill 3 (Front darker green) -->
                            <path d="M 0,54 Q 150,42 300,56 T 400,48 L 400,60 L 0,60 Z" fill="#81C784"/>

                            <!-- Trees & Bushes -->
                            <circle cx="45" cy="42" r="5" fill="#2E7D32"/>
                            <circle cx="53" cy="45" r="4" fill="#388E3C"/>
                            <circle cx="160" cy="46" r="6" fill="#2E7D32"/>
                            <circle cx="210" cy="48" r="5" fill="#388E3C"/>
                            <circle cx="270" cy="50" r="4" fill="#2E7D32"/>
                        </svg>
                    </div>

                </div>

            </div>

        </main>

    </div>

    <!-- ======================================================= -->
    <!-- 7. MODAL: + TAMBAH SURPLUS MAKANAN                     -->
    <!-- ======================================================= -->
    <div x-cloak x-show="isAddModalOpen" 
         class="fixed inset-0 z-50 overflow-y-auto"
         role="dialog" 
         aria-modal="true">
        
        <!-- Backdrop -->
        <div x-show="isAddModalOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="isAddModalOpen = false"
             class="fixed inset-0 bg-stone-900/60 backdrop-blur-xs transition-opacity"></div>

        <!-- Modal Box -->
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="isAddModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-xl border border-stone-200">
                
                <!-- Modal Header -->
                <div class="p-6 border-b border-stone-100 flex items-center justify-between bg-stone-50/50">
                    <div>
                        <h3 class="text-lg font-black text-stone-900">Tambah Makanan Surplus</h3>
                        <p class="text-xs text-stone-500 mt-0.5">Publikasikan stok makanan berlebih untuk diselamatkan konsumen.</p>
                    </div>
                    <button type="button" 
                            @click="isAddModalOpen = false"
                            class="w-8 h-8 rounded-full bg-white text-stone-400 hover:text-stone-700 flex items-center justify-center border border-stone-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body Form -->
                <form @submit.prevent="submitSurplus()" class="p-6 space-y-4">
                    
                    <!-- Nama Makanan -->
                    <div>
                        <label class="block text-xs font-bold text-stone-700 mb-1">Nama Makanan Surplus *</label>
                        <input type="text" 
                               x-model="newSurplus.title" 
                               required
                               placeholder="Contoh: Croissant Almond Premium"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-xs sm:text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-[#1B5E3A] focus:border-transparent">
                    </div>

                    <!-- Kategori & Satuan -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1">Kategori *</label>
                            <select x-model="newSurplus.category" 
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-xs sm:text-sm text-stone-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]">
                                <option value="Roti & Kue">Roti & Kue</option>
                                <option value="Makanan Utama">Makanan Utama</option>
                                <option value="Minuman">Minuman</option>
                                <option value="Snack">Snack</option>
                                <option value="Dessert">Dessert</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1">Satuan Stok</label>
                            <select x-model="newSurplus.unit" 
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-xs sm:text-sm text-stone-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]">
                                <option value="pcs">Pcs (Satuan)</option>
                                <option value="porsi">Porsi</option>
                                <option value="bowl">Bowl</option>
                                <option value="box">Box / Kotak</option>
                            </select>
                        </div>
                    </div>

                    <!-- Harga Normal vs Harga Surplus & Jumlah Stok -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1">Harga Normal (Rp)</label>
                            <input type="number" 
                                   x-model="newSurplus.originalPrice" 
                                   placeholder="15000"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-xs sm:text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1">Harga Surplus (Rp) *</label>
                            <input type="number" 
                                   x-model="newSurplus.discountPrice" 
                                   required
                                   placeholder="9000"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-xs sm:text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1">Stok Tersedia *</label>
                            <input type="number" 
                                   x-model="newSurplus.stock" 
                                   required
                                   placeholder="15"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-xs sm:text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]">
                        </div>
                    </div>

                    <!-- Slot Jam Pengambilan -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1">Jam Ambil Mulai</label>
                            <input type="time" 
                                   x-model="newSurplus.pickupStart" 
                                   class="w-full px-3.5 py-2 rounded-xl border border-stone-300 text-xs text-stone-800 bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1">Jam Ambil Selesai</label>
                            <input type="time" 
                                   x-model="newSurplus.pickupEnd" 
                                   class="w-full px-3.5 py-2 rounded-xl border border-stone-300 text-xs text-stone-800 bg-white">
                        </div>
                    </div>

                    <!-- Catatan Kesegaran -->
                    <div>
                        <label class="block text-xs font-bold text-stone-700 mb-1">Catatan Kesegaran / Kualitas</label>
                        <input type="text" 
                               x-model="newSurplus.freshness" 
                               placeholder="Contoh: Dipanggang hangat hari ini pukul 07.00 WIB"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-xs sm:text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]">
                    </div>

                    <!-- Buttons -->
                    <div class="pt-4 border-t border-stone-100 flex items-center justify-end gap-3">
                        <button type="button" 
                                @click="isAddModalOpen = false"
                                class="px-5 py-2.5 rounded-xl border border-stone-300 text-stone-600 text-xs font-bold hover:bg-stone-50 transition">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-5 py-2.5 rounded-xl bg-[#1B5E3A] hover:bg-[#14472C] text-white text-xs font-bold shadow-md shadow-[#1B5E3A]/25 transition">
                            Publikasikan Surplus
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <!-- ======================================================= -->
    <!-- 8. MODAL: DETAIL PESANAN TERBARU                       -->
    <!-- ======================================================= -->
    <div x-cloak x-show="selectedOrder !== null" 
         class="fixed inset-0 z-50 overflow-y-auto"
         role="dialog" 
         aria-modal="true">
        
        <div x-show="selectedOrder !== null"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="selectedOrder = null"
             class="fixed inset-0 bg-stone-900/60 backdrop-blur-xs transition-opacity"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="selectedOrder !== null"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-stone-200 p-6 space-y-4">
                
                <template x-if="selectedOrder">
                    <div>
                        <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                            <div>
                                <span class="text-[11px] font-bold text-[#1B5E3A] uppercase tracking-wider" x-text="selectedOrder.id"></span>
                                <h3 class="text-base font-black text-stone-900" x-text="'Pesanan ' + selectedOrder.name"></h3>
                            </div>
                            <button type="button" 
                                    @click="selectedOrder = null"
                                    class="w-7 h-7 rounded-full bg-stone-100 text-stone-500 hover:text-stone-800 flex items-center justify-center">
                                ✕
                            </button>
                        </div>

                        <div class="py-3 space-y-3">
                            <div class="flex items-center gap-3">
                                <img :src="selectedOrder.avatar" :alt="selectedOrder.name" class="w-12 h-12 rounded-full object-cover border border-stone-200">
                                <div>
                                    <div class="font-bold text-sm text-stone-900" x-text="selectedOrder.name"></div>
                                    <div class="text-xs text-stone-500" x-text="selectedOrder.time"></div>
                                </div>
                            </div>

                            <div class="p-3 bg-stone-50 rounded-xl space-y-1.5 text-xs">
                                <div class="flex justify-between text-stone-600">
                                    <span>Total Item:</span>
                                    <span class="font-bold text-stone-800" x-text="selectedOrder.items + ' Porsi Surplus'"></span>
                                </div>
                                <div class="flex justify-between text-stone-600">
                                    <span>Total Pembayaran:</span>
                                    <span class="font-bold text-[#1B5E3A]" x-text="formatRupiah(selectedOrder.total)"></span>
                                </div>
                                <div class="flex justify-between text-stone-600">
                                    <span>Status Saat Ini:</span>
                                    <span class="font-bold" 
                                          :class="selectedOrder.status === 'Selesai' ? 'text-emerald-600' : 'text-amber-600'" 
                                          x-text="selectedOrder.status"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Update Status Actions -->
                        <div class="pt-3 border-t border-stone-100 flex items-center justify-end gap-2">
                            <button type="button" 
                                    @click="updateOrderStatus(selectedOrder, 'Selesai')"
                                    class="w-full py-2.5 rounded-xl bg-[#1B5E3A] hover:bg-[#14472C] text-white text-xs font-bold transition">
                                Verifikasi & Tandai Selesai Diambil
                            </button>
                        </div>
                    </div>
                </template>

            </div>
        </div>
    </div>

</div>
@endsection
