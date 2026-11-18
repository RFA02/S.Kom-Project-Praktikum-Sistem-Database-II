@extends('layouts.business')

@section('title', 'Daftar Makanan Surplus - Sunny Bakery')

@section('content')
<div class="flex h-screen overflow-hidden bg-[#FAF7F2]"
     x-data="{
         // Sidebar Mobile State
         mobileSidebarOpen: false,

         // Active Sidebar Item ('surplus')
         activeNav: 'surplus',

         // Topbar Dropdowns
         notificationDropdownOpen: false,
         profileDropdownOpen: false,

         // Filter Tab Cepat (Status Filter Tabs)
         activeStatusTab: 'all', // 'all' (Semua), 'Tersedia', 'Dalam Reservasi', 'Selesai', 'Dibatalkan'

         // Search Filter
         searchQuery: '',

         // Filter Kategori
         selectedCategory: 'Semua Kategori',
         categoryDropdownOpen: false,
         categoriesList: [
             'Semua Kategori',
             'Roti & Kue',
             'Makanan Berat',
             'Makanan Sehat',
             'Snack',
             'Dessert',
             'Minuman'
         ],

         // Filter Tanggal
         selectedDate: '',
         dateFilterOpen: false,

         // Sorting
         sortBy: 'terbaru',
         sortDropdownOpen: false,

         // Pagination
         currentPage: 1,
         itemsPerPage: 5,

         // Period Ringkasan Surplus
         summaryPeriod: 'Minggu ini',
         summaryPeriodDropdownOpen: false,

         // Modals State
         isAddModalOpen: false,
         isGuideModalOpen: false,
         isDetailModalOpen: false,
         selectedDetailItem: null,

         // Active Action Menu (Three dots dropdown)
         activeActionMenuId: null,

         // Form New Surplus Listing State
         newListing: {
             title: '',
             category: 'Roti & Kue',
             date: '2 Mei 2025',
             pickupStart: '16.00',
             pickupEnd: '20.00',
             stock: '',
             unit: 'pcs',
             originalPrice: '',
             discountPrice: '',
             freshness: 'Dipanggang hari ini pukul 06.00 WIB',
             image: 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=400&q=80'
         },

         // Hitung persentase diskon otomatis
         get calculatedDiscountPercent() {
             const orig = Number(this.newListing.originalPrice);
             const disc = Number(this.newListing.discountPrice);
             if (orig > 0 && disc > 0 && orig > disc) {
                 return Math.round(((orig - disc) / orig) * 100);
             }
             return 0;
         },

         // Master Data Listing Makanan Surplus (Sesuai Screenshot & Mockup)
         foods: [
             {
                 id: 1,
                 title: 'Croissant Butter',
                 category: 'Roti & Kue',
                 categoryIcon: '🥐',
                 status: 'Tersedia',
                 date: '2 Mei 2025',
                 pickupTime: '16.00 - 20.00',
                 stock: 12,
                 unit: 'pcs',
                 price: 10500,
                 originalPrice: 30000,
                 discountBadge: 'Hemat hingga 65%',
                 reservationsCount: 0,
                 createdAtText: 'Dibuat hari ini, 08.30',
                 image: 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=400&q=80',
                 freshness: 'Dipanggang segar pagi hari pukul 06.00 WIB di dapur Sunny Bakery. Kondisi renyah dan harum mentega premium.',
                 pickupAddress: 'Sunny Bakery, Jl. Raya Jatinangor No. 45, Sumedang'
             },
             {
                 id: 2,
                 title: 'Chicken Teriyaki Bowl',
                 category: 'Makanan Berat',
                 categoryIcon: '🍲',
                 status: 'Tersedia',
                 date: '2 Mei 2025',
                 pickupTime: '17.00 - 20.00',
                 stock: 6,
                 unit: 'porsi',
                 price: 18000,
                 originalPrice: 40000,
                 discountBadge: 'Hemat hingga 55%',
                 reservationsCount: 1,
                 createdAtText: 'Dibuat kemarin, 14.20',
                 image: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=400&q=80',
                 freshness: 'Dimasak hangat pukul 11.30 WIB. Disimpan dalam rice warmer higienis standar resto.',
                 pickupAddress: 'Sunny Bakery & Cafe, Jl. Raya Jatinangor No. 45, Sumedang'
             },
             {
                 id: 3,
                 title: 'Chicken Sandwich',
                 category: 'Makanan Berat',
                 categoryIcon: '🥪',
                 status: 'Dalam Reservasi',
                 date: '2 Mei 2025',
                 pickupTime: '18.00 - 21.00',
                 stock: 8,
                 unit: 'pcs',
                 price: 15000,
                 originalPrice: 30000,
                 discountBadge: 'Hemat hingga 50%',
                 reservationsCount: 1,
                 createdAtText: 'Dibuat kemarin, 11.10',
                 image: 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?auto=format&fit=crop&w=400&q=80',
                 freshness: 'Roti gandum dengan dada ayam panggang, selada romaine, dan tomat segar dibungkus food-grade packaging.',
                 pickupAddress: 'Sunny Bakery, Jl. Raya Jatinangor No. 45, Sumedang'
             },
             {
                 id: 4,
                 title: 'Salad Sayur Segar',
                 category: 'Makanan Sehat',
                 categoryIcon: '🥗',
                 status: 'Tersedia',
                 date: '3 Mei 2025',
                 pickupTime: '17.00 - 19.00',
                 stock: 5,
                 unit: 'porsi',
                 price: 12000,
                 originalPrice: 20000,
                 discountBadge: 'Hemat hingga 40%',
                 reservationsCount: 0,
                 createdAtText: 'Dibuat 3 Mei 2025, 09.15',
                 image: 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=400&q=80',
                 freshness: 'Sayuran organik hidroponik segar dingin dengan dressing wijen sangrai terpisah.',
                 pickupAddress: 'Sunny Bakery, Jl. Raya Jatinangor No. 45, Sumedang'
             },
             {
                 id: 5,
                 title: 'Donut Mix',
                 category: 'Snack',
                 categoryIcon: '🍩',
                 status: 'Selesai',
                 date: '1 Mei 2025',
                 pickupTime: '16.00 - 20.00',
                 stock: 10,
                 unit: 'pcs',
                 price: 10000,
                 originalPrice: 25000,
                 discountBadge: 'Hemat hingga 60%',
                 reservationsCount: 8,
                 createdAtText: 'Selesai 1 Mei 2025',
                 image: 'https://images.unsplash.com/photo-1527515862127-a4fc05baf7a5?auto=format&fit=crop&w=400&q=80',
                 freshness: 'Donat aneka topping (cokelat, stroberi, kacang) lembut dan lezat.',
                 pickupAddress: 'Sunny Bakery, Jl. Raya Jatinangor No. 45, Sumedang'
             },
             {
                 id: 6,
                 title: 'Red Velvet Slice',
                 category: 'Dessert',
                 categoryIcon: '🍰',
                 status: 'Tersedia',
                 date: '3 Mei 2025',
                 pickupTime: '17.30 - 20.30',
                 stock: 7,
                 unit: 'pcs',
                 price: 14000,
                 originalPrice: 28000,
                 discountBadge: 'Hemat hingga 50%',
                 reservationsCount: 0,
                 createdAtText: 'Dibuat hari ini, 09.40',
                 image: 'https://images.unsplash.com/photo-1586788680434-30d324b2d46f?auto=format&fit=crop&w=400&q=80',
                 freshness: 'Kue red velvet lembut dengan cream cheese lezat dalam chiller suhu 4°C.',
                 pickupAddress: 'Sunny Bakery, Jl. Raya Jatinangor No. 45, Sumedang'
             },
             {
                 id: 7,
                 title: 'Cinnamon Roll Glaze',
                 category: 'Roti & Kue',
                 categoryIcon: '🥐',
                 status: 'Tersedia',
                 date: '3 Mei 2025',
                 pickupTime: '16.30 - 20.00',
                 stock: 9,
                 unit: 'pcs',
                 price: 11000,
                 originalPrice: 22000,
                 discountBadge: 'Hemat hingga 50%',
                 reservationsCount: 0,
                 createdAtText: 'Dibuat hari ini, 10.15',
                 image: 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=400&q=80',
                 freshness: 'Gulungan kayu manis wangi dengan lelehan gula vanila lembut.',
                 pickupAddress: 'Sunny Bakery, Jl. Raya Jatinangor No. 45, Sumedang'
             },
             {
                 id: 8,
                 title: 'Es Kopi Susu Aren',
                 category: 'Minuman',
                 categoryIcon: '🥤',
                 status: 'Tersedia',
                 date: '3 Mei 2025',
                 pickupTime: '16.00 - 19.30',
                 stock: 15,
                 unit: 'cup',
                 price: 9000,
                 originalPrice: 18000,
                 discountBadge: 'Hemat hingga 50%',
                 reservationsCount: 0,
                 createdAtText: 'Dibuat hari ini, 11.00',
                 image: 'https://images.unsplash.com/photo-1517256064527-09c73fc73e38?auto=format&fit=crop&w=400&q=80',
                 freshness: 'Kopi susu gula aren botol sealed dingin tanpa es batu cair.',
                 pickupAddress: 'Sunny Bakery, Jl. Raya Jatinangor No. 45, Sumedang'
             },
             {
                 id: 9,
                 title: 'Baguette Garlic Herb',
                 category: 'Roti & Kue',
                 categoryIcon: '🥖',
                 status: 'Tersedia',
                 date: '2 Mei 2025',
                 pickupTime: '18.00 - 21.00',
                 stock: 6,
                 unit: 'pcs',
                 price: 12500,
                 originalPrice: 25000,
                 discountBadge: 'Hemat hingga 50%',
                 reservationsCount: 0,
                 createdAtText: 'Dibuat kemarin, 16.00',
                 image: 'https://images.unsplash.com/photo-1549931319-a545dcf3bc73?auto=format&fit=crop&w=400&q=80',
                 freshness: 'Baguette khas Prancis dengan olesan mentega bawang putih dan daun parsley.',
                 pickupAddress: 'Sunny Bakery, Jl. Raya Jatinangor No. 45, Sumedang'
             },
             {
                 id: 10,
                 title: 'Matcha Latte Bottle',
                 category: 'Minuman',
                 categoryIcon: '🍵',
                 status: 'Dalam Reservasi',
                 date: '2 Mei 2025',
                 pickupTime: '17.30 - 20.30',
                 stock: 4,
                 unit: 'cup',
                 price: 13000,
                 originalPrice: 26000,
                 discountBadge: 'Hemat hingga 50%',
                 reservationsCount: 2,
                 createdAtText: 'Dibuat kemarin, 15.30',
                 image: 'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?auto=format&fit=crop&w=400&q=80',
                 freshness: 'Matcha murni Uji Jepang dipadukan susu oats creamy dalam botol 250ml.',
                 pickupAddress: 'Sunny Bakery, Jl. Raya Jatinangor No. 45, Sumedang'
             },
             {
                 id: 11,
                 title: 'Beef Bulgogi Bento',
                 category: 'Makanan Berat',
                 categoryIcon: '🍱',
                 status: 'Selesai',
                 date: '1 Mei 2025',
                 pickupTime: '18.00 - 20.30',
                 stock: 5,
                 unit: 'porsi',
                 price: 24000,
                 originalPrice: 48000,
                 discountBadge: 'Hemat hingga 50%',
                 reservationsCount: 5,
                 createdAtText: 'Selesai 1 Mei 2025',
                 image: 'https://images.unsplash.com/photo-1563245372-f21724e3856d?auto=format&fit=crop&w=400&q=80',
                 freshness: 'Daging sapi bulgogi marinasi manis gurih dengan kimchi dan nasi jepang.',
                 pickupAddress: 'Sunny Bakery, Jl. Raya Jatinangor No. 45, Sumedang'
             },
             {
                 id: 12,
                 title: 'Apple Cinnamon Pie',
                 category: 'Dessert',
                 categoryIcon: '🥧',
                 status: 'Dibatalkan',
                 date: '30 Apr 2025',
                 pickupTime: '17.00 - 20.00',
                 stock: 3,
                 unit: 'pcs',
                 price: 16000,
                 originalPrice: 32000,
                 discountBadge: 'Hemat hingga 50%',
                 reservationsCount: 0,
                 createdAtText: 'Dibatalkan 30 Apr 2025',
                 image: 'https://images.unsplash.com/photo-1519869325930-281384150729?auto=format&fit=crop&w=400&q=80',
                 freshness: 'Pai apel hangat dengan taburan kayu manis.',
                 pickupAddress: 'Sunny Bakery, Jl. Raya Jatinangor No. 45, Sumedang'
             }
         ],

         // Filtered Foods Computation
         get filteredFoods() {
             return this.foods.filter(item => {
                 // 1. Filter Tab Cepat
                 if (this.activeStatusTab !== 'all' && item.status !== this.activeStatusTab) {
                     return false;
                 }

                 // 2. Filter Search Text
                 if (this.searchQuery.trim() !== '') {
                     const q = this.searchQuery.toLowerCase();
                     const matchTitle = item.title.toLowerCase().includes(q);
                     const matchCategory = item.category.toLowerCase().includes(q);
                     if (!matchTitle && !matchCategory) return false;
                 }

                 // 3. Filter Kategori Dropdown
                 if (this.selectedCategory !== 'Semua Kategori' && item.category !== this.selectedCategory) {
                     return false;
                 }

                 return true;
             }).sort((a, b) => {
                 if (this.sortBy === 'harga-rendah') return a.price - b.price;
                 if (this.sortBy === 'harga-tinggi') return b.price - a.price;
                 if (this.sortBy === 'stok-banyak') return b.stock - a.stock;
                 if (this.sortBy === 'nama-az') return a.title.localeCompare(b.title);
                 return a.id - b.id; // Urutkan: Terbaru (sesuai urutan id)
             });
         },

         // Paginated Items
         get paginatedFoods() {
             const start = (this.currentPage - 1) * this.itemsPerPage;
             return this.filteredFoods.slice(start, start + this.itemsPerPage);
         },

         get totalPages() {
             return Math.ceil(this.filteredFoods.length / this.itemsPerPage) || 1;
         },

         // Hitung jumlah item per status untuk badge tab
         countByStatus(status) {
             if (status === 'all') return this.foods.length;
             return this.foods.filter(f => f.status === status).length;
         },

         // Format mata uang Rupiah standar Indonesia
         formatRupiah(number) {
             return 'Rp ' + Number(number).toLocaleString('id-ID');
         },

         // Buka Modal Detail Item
         openDetail(item) {
             this.selectedDetailItem = item;
             this.isDetailModalOpen = true;
             this.activeActionMenuId = null;
         },

         // Simpan Listing Makanan Surplus Baru
         saveNewListing() {
             if (!this.newListing.title || !this.newListing.originalPrice || !this.newListing.discountPrice || !this.newListing.stock) {
                 this.showToast('Harap lengkapi semua kolom yang wajib diisi!', 'error');
                 return;
             }

             const orig = Number(this.newListing.originalPrice);
             const disc = Number(this.newListing.discountPrice);
             const percent = Math.round(((orig - disc) / orig) * 100);

             const newItem = {
                 id: Date.now(),
                 title: this.newListing.title,
                 category: this.newListing.category,
                 categoryIcon: this.newListing.category === 'Roti & Kue' ? '🥐' : (this.newListing.category === 'Makanan Berat' ? '🍲' : '🥗'),
                 status: 'Tersedia',
                 date: this.newListing.date || 'Hari ini',
                 pickupTime: `${this.newListing.pickupStart} - ${this.newListing.pickupEnd}`,
                 stock: Number(this.newListing.stock),
                 unit: this.newListing.unit,
                 price: disc,
                 originalPrice: orig,
                 discountBadge: `Hemat hingga ${percent}%`,
                 reservationsCount: 0,
                 createdAtText: 'Dibuat baru saja',
                 image: this.newListing.image || 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=400&q=80',
                 freshness: this.newListing.freshness || 'Dibuat hari ini dengan standar higienis Sunny Bakery.',
                 pickupAddress: 'Sunny Bakery, Jl. Raya Jatinangor No. 45, Sumedang'
             };

             this.foods.unshift(newItem);
             this.isAddModalOpen = false;
             this.showToast(`Listing ${newItem.title} berhasil dipublikasikan! 🌱`);

             // Reset form
             this.newListing.title = '';
             this.newListing.originalPrice = '';
             this.newListing.discountPrice = '';
             this.newListing.stock = '';
         },

         // Ubah status item dari menu aksi (...)
         updateItemStatus(item, newStatus) {
             item.status = newStatus;
             this.activeActionMenuId = null;
             this.showToast(`Status ${item.title} diubah menjadi '${newStatus}'.`);
         },

         // Hapus listing
         deleteItem(id) {
             const item = this.foods.find(f => f.id === id);
             const title = item ? item.title : 'Listing';
             this.foods = this.foods.filter(f => f.id !== id);
             this.activeActionMenuId = null;
             this.showToast(`${title} telah dihapus dari daftar surplus.`, 'error');
         }
     }"
     @click="activeActionMenuId = null; categoryDropdownOpen = false; sortDropdownOpen = false; summaryPeriodDropdownOpen = false">

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
           class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-stone-200/80 flex flex-col justify-between transition-transform duration-300 ease-in-out lg:static lg:z-auto shrink-0">
        
        <!-- Top Section: Brand Logo & Navigation Links -->
        <div class="px-5 pt-6 pb-4 flex flex-col h-full overflow-y-auto custom-scrollbar">
            
            <!-- Logo What's Left & Badge 'Business' (Sesuai Rujukan Visual) -->
            <div class="flex items-center justify-between mb-7 pl-1">
                <a href="{{ url('/?view=dashboard') }}" class="inline-flex flex-col group">
                    <div class="flex items-center text-2xl font-black tracking-tight leading-none">
                        <span class="text-[#1B5E3A]">What's</span>
                        <span class="text-[#E67E22] ml-1">Left</span>
                        <!-- Cute spark tag -->
                        <span class="text-[#E67E22] text-sm -mt-2 ml-0.5 font-bold">✨</span>
                    </div>
                    <!-- Pill Badge 'Business' di bawah Logo -->
                    <div class="mt-1.5 self-start">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-[#DCF2E4] text-[#1B5E3A] uppercase tracking-wider">
                            Business
                        </span>
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
            <nav class="space-y-1 flex-1">
                
                <!-- 1. Dashboard -->
                <a href="{{ url('/?view=dashboard') }}"
                   class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <!-- 2. Makanan Surplus (STATUS AKTIF / MENYALA HIJAU) -->
                <a href="{{ url('/?view=makanan-surplus') }}"
                   class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold bg-[#EBF5F0] text-[#1B5E3A] shadow-xs text-left">
                    <svg class="w-5 h-5 shrink-0 text-[#1B5E3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                    <span>Makanan Surplus</span>
                </a>

                <!-- 3. Produksi Harian -->
                <button type="button"
                        @click="showToast('Menu Produksi Harian')"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    <span>Produksi Harian</span>
                </button>

                <!-- 4. Reservasi (Badge Angka 5 Oranye) -->
                <button type="button"
                        @click="showToast('Menu Reservasi (5 Reservasi Baru)')"
                        class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Reservasi</span>
                    </div>
                    <span class="w-5 h-5 rounded-full bg-[#EA580C] text-white text-[11px] font-bold flex items-center justify-center shadow-xs">
                        5
                    </span>
                </button>

                <!-- 5. Pelanggan -->
                <button type="button"
                        @click="showToast('Menu Pelanggan')"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span>Pelanggan</span>
                </button>

                <!-- 6. Analitik -->
                <button type="button"
                        @click="showToast('Menu Analitik')"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span>Analitik</span>
                </button>

                <!-- 7. Promo & Diskon -->
                <button type="button"
                        @click="showToast('Menu Promo & Diskon')"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <span>Promo & Diskon</span>
                </button>

                <!-- 8. Review -->
                <button type="button"
                        @click="showToast('Menu Review')"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    <span>Review</span>
                </button>

                <!-- 9. Pengaturan Toko -->
                <button type="button"
                        @click="showToast('Menu Pengaturan Toko')"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Pengaturan Toko</span>
                </button>

                <!-- 10. Akun & Tim -->
                <button type="button"
                        @click="showToast('Menu Akun & Tim')"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>Akun & Tim</span>
                </button>

            </nav>

            <!-- Bottom Impact Box ("Yuk, kurangi food waste..." Sesuai Screenshot) -->
            <div class="mt-5 mb-1 p-4 rounded-2xl bg-[#FDFBF7] border border-[#DCF2E4] relative overflow-hidden shadow-2xs">
                <p class="text-xs font-bold text-stone-900 leading-snug">
                    Yuk, kurangi food waste dan bantu lebih banyak orang! 🌱
                </p>
                
                <!-- Cute Eco Illustration (Shopping bag, salad, bread) -->
                <div class="my-3 flex items-center justify-center">
                    <div class="w-20 h-16 relative flex items-center justify-center">
                        <!-- Paper Bag Icon -->
                        <div class="w-12 h-14 bg-[#4ADE80]/20 border border-[#22C55E] rounded-lg flex flex-col items-center justify-center relative shadow-xs">
                            <span class="text-lg">🛍️</span>
                            <span class="absolute -top-2 text-xs">🌱</span>
                        </div>
                        <span class="absolute -bottom-1 -left-2 text-base">🥗</span>
                        <span class="absolute -bottom-1 -right-2 text-base">🥐</span>
                    </div>
                </div>

                <!-- Sub-title Dampak Toko -->
                <p class="text-[11px] font-bold text-stone-500 mb-2">Dampak positif tokomu</p>

                <div class="space-y-1.5">
                    <!-- Stat 1: 320 kg Makanan terselamatkan -->
                    <div class="flex items-center gap-2">
                        <div class="w-5 h-5 rounded-md bg-[#DCF2E4] flex items-center justify-center text-[#1B5E3A] text-xs shrink-0">
                            🌿
                        </div>
                        <div>
                            <span class="text-xs font-extrabold text-stone-900">320 kg</span>
                            <span class="text-[10px] text-stone-500 block -mt-0.5">Makanan terselamatkan</span>
                        </div>
                    </div>

                    <!-- Stat 2: 1.248 Porsi disalurkan -->
                    <div class="flex items-center gap-2">
                        <div class="w-5 h-5 rounded-md bg-[#DCF2E4] flex items-center justify-center text-[#1B5E3A] text-xs shrink-0">
                            📦
                        </div>
                        <div>
                            <span class="text-xs font-extrabold text-stone-900">1.248</span>
                            <span class="text-[10px] text-stone-500 block -mt-0.5">Porsi disalurkan</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </aside>

    <!-- ======================================================= -->
    <!-- 2. MAIN CONTENT AREA (TOPBAR + CANVAS LISTING SURPLUS)  -->
    <!-- ======================================================= -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-[#FAF7F2]">
        
        <!-- TOP NAVBAR -->
        <header class="h-16 bg-white border-b border-stone-200/80 px-4 sm:px-6 lg:px-8 flex items-center justify-between shrink-0 z-10">
            
            <!-- Left: Mobile Toggle & Page Context -->
            <div class="flex items-center gap-3">
                <button type="button" 
                        @click="mobileSidebarOpen = true"
                        class="p-2 -ml-2 rounded-xl text-stone-600 hover:text-stone-900 hover:bg-stone-100 lg:hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <!-- Right: Notifikasi & Profil Toko (Sunny Bakery - Owner) -->
            <div class="flex items-center gap-4">
                
                <!-- Notification Bell Button -->
                <div class="relative" x-data="{ open: false }">
                    <button type="button" 
                            @click="open = !open"
                            class="relative p-2 rounded-xl text-stone-600 hover:text-stone-900 hover:bg-stone-100 transition focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <!-- Badge Notifikasi Angka 5 Oranye -->
                        <span class="absolute top-1 right-1 w-4 h-4 bg-[#EA580C] text-white text-[10px] font-bold rounded-full flex items-center justify-center shadow-xs">
                            5
                        </span>
                    </button>

                    <!-- Dropdown Notifikasi -->
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
                            <span class="text-sm font-bold text-stone-900">Notifikasi Masuk</span>
                            <span class="text-[11px] font-semibold text-[#1B5E3A] cursor-pointer hover:underline" @click="open = false; showToast('Semua notifikasi ditandai dibaca')">Tandai Dibaca</span>
                        </div>
                        <div class="divide-y divide-stone-100 max-h-72 overflow-y-auto custom-scrollbar">
                            <div class="p-3.5 hover:bg-stone-50 transition flex items-start gap-3">
                                <span class="w-2 h-2 rounded-full bg-[#1B5E3A] mt-1.5 shrink-0"></span>
                                <div>
                                    <p class="text-xs font-semibold text-stone-800">Reservasi Baru Masuk!</p>
                                    <p class="text-[11px] text-stone-500 mt-0.5">1 porsi Chicken Sandwich telah direservasi oleh Fajar.</p>
                                    <span class="text-[10px] text-stone-400 mt-1 block">5 menit yang lalu</span>
                                </div>
                            </div>
                            <div class="p-3.5 hover:bg-stone-50 transition flex items-start gap-3">
                                <span class="w-2 h-2 rounded-full bg-[#EA580C] mt-1.5 shrink-0"></span>
                                <div>
                                    <p class="text-xs font-semibold text-stone-800">Waktu Pengambilan Segera Tiba</p>
                                    <p class="text-[11px] text-stone-500 mt-0.5">Slot pickup Croissant Butter dimulai pukul 16.00 WIB.</p>
                                    <span class="text-[10px] text-stone-400 mt-1 block">30 menit yang lalu</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="h-6 w-px bg-stone-200"></div>

                <!-- User Profile Dropdown (Sunny Bakery - Owner) -->
                <div class="relative" x-data="{ open: false }">
                    <button type="button" 
                            @click="open = !open"
                            class="flex items-center gap-3 p-1 rounded-xl hover:bg-stone-50 transition focus:outline-none">
                        <!-- Avatar Sunny Bakery -->
                        <img src="https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=120&q=80" 
                             alt="Sunny Bakery" 
                             class="w-9 h-9 rounded-full object-cover ring-2 ring-[#DCF2E4]">
                        <div class="text-left hidden sm:block">
                            <p class="text-xs font-bold text-stone-900 leading-none">Sunny Bakery</p>
                            <p class="text-[11px] text-stone-500 mt-0.5 leading-none">Owner</p>
                        </div>
                        <svg class="w-4 h-4 text-stone-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown Menu Profil -->
                    <div x-cloak x-show="open" 
                         @click.outside="open = false"
                         x-transition
                         class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-stone-200 py-1.5 z-50 text-xs">
                        <a href="{{ url('/?view=dashboard') }}" class="block px-4 py-2 text-stone-700 hover:bg-stone-50">Dashboard</a>
                        <a href="{{ url('/?view=makanan-surplus') }}" class="block px-4 py-2 font-bold text-[#1B5E3A] bg-[#EBF5F0]">Daftar Surplus</a>
                        <div class="my-1 border-t border-stone-100"></div>
                        <a href="{{ url('/') }}" class="block px-4 py-2 text-stone-700 hover:bg-stone-50">Kunjungi Halaman Publik</a>
                        <a href="{{ url('/?view=login') }}" class="block px-4 py-2 text-red-600 hover:bg-red-50">Keluar Toko</a>
                    </div>
                </div>

            </div>

        </header>

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
                                @click="isAddModalOpen = true"
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
                            @click="activeStatusTab = 'Tersedia'; currentPage = 1"
                            :class="activeStatusTab === 'Tersedia' 
                                ? 'text-[#1B5E3A] font-bold' 
                                : 'text-stone-500 hover:text-stone-800 font-medium'"
                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs sm:text-sm transition shrink-0">
                        <span>Tersedia</span>
                        <span :class="activeStatusTab === 'Tersedia' 
                            ? 'bg-[#1B5E3A] text-white' 
                            : 'bg-stone-200 text-stone-600'"
                              class="text-[11px] font-bold px-2 py-0.5 rounded-full"
                              x-text="countByStatus('Tersedia')">
                        </span>
                    </button>

                    <!-- Tab: Dalam Reservasi (2) -->
                    <button type="button"
                            @click="activeStatusTab = 'Dalam Reservasi'; currentPage = 1"
                            :class="activeStatusTab === 'Dalam Reservasi' 
                                ? 'text-[#1B5E3A] font-bold' 
                                : 'text-stone-500 hover:text-stone-800 font-medium'"
                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs sm:text-sm transition shrink-0">
                        <span>Dalam Reservasi</span>
                        <span :class="activeStatusTab === 'Dalam Reservasi' 
                            ? 'bg-[#1B5E3A] text-white' 
                            : 'bg-stone-200 text-stone-600'"
                              class="text-[11px] font-bold px-2 py-0.5 rounded-full"
                              x-text="countByStatus('Dalam Reservasi')">
                        </span>
                    </button>

                    <!-- Tab: Selesai (10) -->
                    <button type="button"
                            @click="activeStatusTab = 'Selesai'; currentPage = 1"
                            :class="activeStatusTab === 'Selesai' 
                                ? 'text-[#1B5E3A] font-bold' 
                                : 'text-stone-500 hover:text-stone-800 font-medium'"
                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs sm:text-sm transition shrink-0">
                        <span>Selesai</span>
                        <span :class="activeStatusTab === 'Selesai' 
                            ? 'bg-[#1B5E3A] text-white' 
                            : 'bg-stone-200 text-stone-600'"
                              class="text-[11px] font-bold px-2 py-0.5 rounded-full"
                              x-text="countByStatus('Selesai')">
                        </span>
                    </button>

                    <!-- Tab: Dibatalkan (2) -->
                    <button type="button"
                            @click="activeStatusTab = 'Dibatalkan'; currentPage = 1"
                            :class="activeStatusTab === 'Dibatalkan' 
                                ? 'text-[#1B5E3A] font-bold' 
                                : 'text-stone-500 hover:text-stone-800 font-medium'"
                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs sm:text-sm transition shrink-0">
                        <span>Dibatalkan</span>
                        <span :class="activeStatusTab === 'Dibatalkan' 
                            ? 'bg-[#1B5E3A] text-white' 
                            : 'bg-stone-200 text-stone-600'"
                              class="text-[11px] font-bold px-2 py-0.5 rounded-full"
                              x-text="countByStatus('Dibatalkan')">
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

                            <!-- 3. Tombol Pilih Tanggal -->
                            <div class="relative shrink-0">
                                <button type="button"
                                        @click="showToast('Filter Tanggal dipilih')"
                                        class="w-full sm:w-auto inline-flex items-center justify-between sm:justify-start gap-2.5 px-3.5 py-2.5 bg-white border border-stone-200 rounded-xl text-xs sm:text-sm font-medium text-stone-700 hover:bg-stone-50 transition shadow-2xs">
                                    <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Pilih Tanggal</span>
                                </button>
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
                                        <div class="relative shrink-0">
                                            <img :src="item.image" 
                                                 :alt="item.title"
                                                 class="w-24 h-24 sm:w-28 sm:h-28 rounded-xl object-cover shadow-2xs border border-stone-100">
                                        </div>

                                        <!-- Informasi Item -->
                                        <div class="flex-1 min-w-0 pt-0.5">
                                            <!-- Status Badge -->
                                            <div>
                                                <span :class="{
                                                    'bg-[#DCF2E4] text-[#1B5E3A]': item.status === 'Tersedia',
                                                    'bg-[#FEF3C7] text-[#D97706]': item.status === 'Dalam Reservasi',
                                                    'bg-stone-100 text-stone-600': item.status === 'Selesai',
                                                    'bg-rose-50 text-rose-600': item.status === 'Dibatalkan'
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
                                                    <span class="font-semibold text-stone-700" x-text="item.stock + ' ' + item.unit"></span>
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
                                            <p class="text-[11px] text-stone-400 mt-0.5" x-text="item.createdAtText"></p>
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
                                                            @click="updateItemStatus(item, 'Tersedia')"
                                                            class="w-full text-left px-3.5 py-2 text-stone-700 hover:bg-stone-50 flex items-center gap-2">
                                                        <span>🟢</span> Tandai Tersedia
                                                    </button>

                                                    <button type="button"
                                                            @click="updateItemStatus(item, 'Selesai')"
                                                            class="w-full text-left px-3.5 py-2 text-stone-700 hover:bg-stone-50 flex items-center gap-2">
                                                        <span>✅</span> Tandai Selesai
                                                    </button>

                                                    <button type="button"
                                                            @click="updateItemStatus(item, 'Dibatalkan')"
                                                            class="w-full text-left px-3.5 py-2 text-amber-700 hover:bg-amber-50 flex items-center gap-2">
                                                        <span>⚠️</span> Batalkan Listing
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
                                        @click="searchQuery = ''; activeStatusTab = 'all'; selectedCategory = 'Semua Kategori'"
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
                                        <div class="text-2xl font-black text-stone-900 leading-none">12</div>
                                        <span class="text-[10px] text-stone-400">listing</span>
                                    </div>
                                </div>

                                <!-- Metrik 2: Total Porsi (176 porsi) -->
                                <div class="p-3.5 rounded-xl border border-stone-100 bg-[#FAF7F2]/60 flex flex-col justify-between">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[11px] font-semibold text-stone-500">Total Porsi</span>
                                        <div class="w-7 h-7 rounded-lg bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center text-xs">
                                            🌱
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <div class="text-2xl font-black text-stone-900 leading-none">176</div>
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
                                        <div class="text-2xl font-black text-stone-900 leading-none">86</div>
                                        <span class="text-[10px] text-stone-400">porsi</span>
                                    </div>
                                </div>

                                <!-- Metrik 4: Potensi Penghematan (Rp 1.860.000) -->
                                <div class="p-3.5 rounded-xl border border-stone-100 bg-[#FAF7F2]/60 flex flex-col justify-between">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[11px] font-semibold text-stone-500">Potensi Penghematan</span>
                                        <div class="w-7 h-7 rounded-lg bg-[#FEF3C7] text-[#D97706] flex items-center justify-center text-xs">
                                            🛍️
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <div class="text-sm font-extrabold text-stone-900 leading-tight">Rp 1.860.000</div>
                                        <span class="text-[10px] text-stone-400">estimasi</span>
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
                                    <p class="leading-relaxed">Tambahkan foto makanan yang menarik dan jelas.</p>
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
                                    @click="showToast('Menghubungkan ke Tim Support What\'s Left WhatsApp...')"
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
                            <h3 class="text-base font-extrabold text-stone-900" id="modal-add-title">
                                Buat Listing Makanan Surplus
                            </h3>
                            <p class="text-xs text-stone-500">Tawarkan makanan layak konsumsi dengan diskon hemat.</p>
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
                    
                    <!-- Row 1: Nama Makanan & Kategori -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-stone-700 mb-1">Nama Makanan <span class="text-red-500">*</span></label>
                            <input type="text"
                                   x-model="newListing.title"
                                   placeholder="Contoh: Croissant Butter"
                                   required
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 bg-white text-stone-800 focus:outline-none focus:border-[#1B5E3A] focus:ring-1 focus:ring-[#1B5E3A]">
                        </div>

                        <div>
                            <label class="block font-bold text-stone-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                            <select x-model="newListing.category"
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 bg-white text-stone-800 focus:outline-none focus:border-[#1B5E3A]">
                                <option value="Roti & Kue">🥐 Roti & Kue</option>
                                <option value="Makanan Berat">🍲 Makanan Berat</option>
                                <option value="Makanan Sehat">🥗 Makanan Sehat</option>
                                <option value="Snack">🍩 Snack</option>
                                <option value="Dessert">🍰 Dessert</option>
                                <option value="Minuman">🥤 Minuman</option>
                            </select>
                        </div>
                    </div>

                    <!-- Row 2: Harga Normal & Harga Diskon (Live Auto Hitung Persentase Diskon) -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 rounded-2xl bg-[#FDFBF7] border border-[#DCF2E4]">
                        <div>
                            <label class="block font-bold text-stone-700 mb-1">Harga Normal (Rp) <span class="text-red-500">*</span></label>
                            <input type="number"
                                   x-model="newListing.originalPrice"
                                   placeholder="30000"
                                   required
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 bg-white text-stone-800 focus:outline-none focus:border-[#1B5E3A]">
                        </div>

                        <div>
                            <label class="block font-bold text-stone-700 mb-1">Harga Diskon (Rp) <span class="text-red-500">*</span></label>
                            <input type="number"
                                   x-model="newListing.discountPrice"
                                   placeholder="10500"
                                   required
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 bg-white text-stone-800 focus:outline-none focus:border-[#1B5E3A]">
                        </div>

                        <div>
                            <label class="block font-bold text-stone-700 mb-1">Estimasi Hemat</label>
                            <div class="h-10 px-3.5 rounded-xl bg-[#DCF2E4] border border-[#22C55E]/30 flex items-center justify-center font-extrabold text-[#1B5E3A]">
                                <span x-show="calculatedDiscountPercent > 0" x-text="'Hemat ' + calculatedDiscountPercent + '%'"></span>
                                <span x-show="calculatedDiscountPercent <= 0" class="text-stone-400 font-normal">Otomatis dihitung</span>
                            </div>
                        </div>
                    </div>

                    <!-- Row 3: Jumlah Stok & Satuan -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-stone-700 mb-1">Jumlah Stok <span class="text-red-500">*</span></label>
                            <input type="number"
                                   x-model="newListing.stock"
                                   placeholder="12"
                                   min="1"
                                   required
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 bg-white text-stone-800 focus:outline-none focus:border-[#1B5E3A]">
                        </div>

                        <div>
                            <label class="block font-bold text-stone-700 mb-1">Satuan</label>
                            <select x-model="newListing.unit"
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 bg-white text-stone-800 focus:outline-none focus:border-[#1B5E3A]">
                                <option value="pcs">pcs (buah)</option>
                                <option value="porsi">porsi</option>
                                <option value="cup">cup</option>
                                <option value="box">box</option>
                                <option value="pack">pack</option>
                            </select>
                        </div>
                    </div>

                    <!-- Row 4: Waktu Pengambilan (Pickup Window) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-stone-700 mb-1">Jam Mulai Pickup</label>
                            <input type="text"
                                   x-model="newListing.pickupStart"
                                   placeholder="16.00"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 bg-white text-stone-800 focus:outline-none focus:border-[#1B5E3A]">
                        </div>

                        <div>
                            <label class="block font-bold text-stone-700 mb-1">Jam Selesai Pickup</label>
                            <input type="text"
                                   x-model="newListing.pickupEnd"
                                   placeholder="20.00"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 bg-white text-stone-800 focus:outline-none focus:border-[#1B5E3A]">
                        </div>
                    </div>

                    <!-- Row 5: Catatan Kesegaran & Foto URL -->
                    <div>
                        <label class="block font-bold text-stone-700 mb-1">Keterangan Kesegaran Makanan</label>
                        <input type="text"
                               x-model="newListing.freshness"
                               placeholder="Contoh: Dipanggang hari ini pukul 06.00 WIB, masih harum dan renyah."
                               class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 bg-white text-stone-800 focus:outline-none focus:border-[#1B5E3A]">
                    </div>

                    <div>
                        <label class="block font-bold text-stone-700 mb-1">URL Foto Makanan</label>
                        <input type="url"
                               x-model="newListing.image"
                               placeholder="https://images.unsplash.com/..."
                               class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 bg-white text-stone-800 focus:outline-none focus:border-[#1B5E3A]">
                    </div>

                    <!-- Modal Actions -->
                    <div class="pt-4 border-t border-stone-100 flex items-center justify-end gap-3">
                        <button type="button"
                                @click="isAddModalOpen = false"
                                class="px-5 py-2.5 rounded-xl font-bold text-stone-600 hover:bg-stone-100 transition">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-6 py-2.5 rounded-xl font-bold text-white bg-[#1B5E3A] hover:bg-[#14472C] transition shadow-md shadow-[#1B5E3A]/25">
                            Publikasikan Listing Surplus
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
                        <img :src="selectedDetailItem.image" 
                             :alt="selectedDetailItem.title"
                             class="w-full sm:w-40 h-40 rounded-2xl object-cover shadow-sm">
                        
                        <div class="flex-1 space-y-2 text-xs sm:text-sm">
                            <div>
                                <span class="text-stone-400 block text-[11px]">Status Listing</span>
                                <span :class="{
                                    'bg-[#DCF2E4] text-[#1B5E3A]': selectedDetailItem.status === 'Tersedia',
                                    'bg-[#FEF3C7] text-[#D97706]': selectedDetailItem.status === 'Dalam Reservasi',
                                    'bg-stone-100 text-stone-600': selectedDetailItem.status === 'Selesai',
                                    'bg-rose-50 text-rose-600': selectedDetailItem.status === 'Dibatalkan'
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
                                <span class="text-stone-400 block text-[11px]">Sisa Stok & Satuan</span>
                                <span class="font-bold text-stone-900" x-text="selectedDetailItem.stock + ' ' + selectedDetailItem.unit"></span>
                            </div>

                            <div>
                                <span class="text-stone-400 block text-[11px]">Waktu Pengambilan</span>
                                <span class="font-semibold text-stone-800" x-text="selectedDetailItem.date + ' (' + selectedDetailItem.pickupTime + ')'"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Keterangan Kesegaran & Alamat -->
                    <div class="p-3.5 rounded-xl bg-[#FAF7F2] border border-stone-200/80 text-xs space-y-2">
                        <div>
                            <span class="font-bold text-stone-800">Catatan Kesegaran:</span>
                            <p class="text-stone-600 mt-0.5" x-text="selectedDetailItem.freshness"></p>
                        </div>
                        <div>
                            <span class="font-bold text-stone-800">Lokasi Pengambilan:</span>
                            <p class="text-stone-600 mt-0.5" x-text="selectedDetailItem.pickupAddress"></p>
                        </div>
                    </div>

                    <!-- Footer Action -->
                    <div class="pt-3 border-t border-stone-100 flex items-center justify-between">
                        <button type="button"
                                @click="showToast('Link listing disalin ke clipboard!'); isDetailModalOpen = false"
                                class="px-4 py-2 rounded-xl text-xs font-bold text-stone-700 bg-stone-100 hover:bg-stone-200 transition">
                            📋 Salin Link
                        </button>

                        <div class="flex items-center gap-2">
                            <button type="button"
                                    @click="updateItemStatus(selectedDetailItem, 'Selesai'); isDetailModalOpen = false"
                                    class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-[#1B5E3A] hover:bg-[#14472C] transition">
                                Tandai Selesai
                            </button>
                        </div>
                    </div>

                </div>
            </template>
        </div>
    </div>

</div>
@endsection
