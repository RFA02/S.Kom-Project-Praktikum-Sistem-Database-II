@extends('layouts.business')

@section('title', 'Manajemen Makanan - Sunny Bakery')

@section('content')
<div class="flex h-screen overflow-hidden bg-[#FAF7F2]"
     x-data="{
         // Sidebar Mobile State
         mobileSidebarOpen: false,

         // Active Sidebar Item
         activeNav: 'makanan-surplus',

         // Topbar Dropdowns
         notificationDropdownOpen: false,
         profileDropdownOpen: false,

         // View Mode: 'list' (tabel seperti screenshot) atau 'grid' (kartu)
         viewMode: 'list',

         // Filter Tab Cepat (Status Filter Tabs)
         activeStatusTab: 'all', // 'all', 'Tersedia', 'Habis', 'Dijadwalkan', 'Draft'

         // Search Filter
         searchQuery: '',
         categorySearchQuery: '',

         // Filter Kategori (Checkboxes)
         selectedCategories: ['Semua Kategori'],
         categoriesList: [
             { id: 'semua', name: 'Semua Kategori', icon: '✨' },
             { id: 'roti', name: 'Roti & Kue', icon: '🥐' },
             { id: 'berat', name: 'Makanan Berat', icon: '🍲' },
             { id: 'sehat', name: 'Makanan Sehat', icon: '🥗' },
             { id: 'snack', name: 'Snack', icon: '🍩' },
             { id: 'dessert', name: 'Dessert', icon: '🍰' },
             { id: 'minuman', name: 'Minuman', icon: '🥤' }
         ],

         // Filter Status Checklist
         selectedStatuses: ['Tersedia'],
         statusesList: ['Tersedia', 'Habis', 'Dijadwalkan', 'Draft'],

         // Filter Rentang Harga
         minPrice: '',
         maxPrice: '',

         // Sorting
         sortBy: 'terbaru', // 'terbaru', 'harga-rendah', 'harga-tinggi', 'stok-banyak', 'nama-az'

         // Pagination
         currentPage: 1,
         itemsPerPage: 8,

         // Modals
         isAddModalOpen: false,
         isEditModalOpen: false,
         editingFood: null,
         activeActionMenuId: null,

         // New Food Form Model
         newFood: {
             title: '',
             category: 'Roti & Kue',
             stock: 10,
             unit: 'pcs',
             originalPrice: 15000,
             discountPrice: 10000,
             status: 'Tersedia',
             pickupTime: '18.00 - 20.30',
             image: 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=400&q=80'
         },

         // Master Data Makanan (Total 18: 12 Tersedia, 4 Habis, 2 Dijadwalkan, 0 Draft)
         foods: [
             {
                 id: 1,
                 title: 'Croissant Butter',
                 category: 'Roti & Kue',
                 stock: 12,
                 unit: 'pcs',
                 originalPrice: 15000,
                 price: 10500,
                 status: 'Tersedia',
                 pickupTime: 'Ambil hari ini 18.00 - 20.00',
                 image: 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=400&q=80',
                 createdAt: '2026-11-18 07:00:00'
             },
             {
                 id: 2,
                 title: 'Chicken Teriyaki Bowl',
                 category: 'Makanan Berat',
                 stock: 10,
                 unit: 'porsi',
                 originalPrice: 28000,
                 price: 18000,
                 status: 'Tersedia',
                 pickupTime: 'Ambil hari ini 17.30 - 20.00',
                 image: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=400&q=80',
                 createdAt: '2026-11-18 07:30:00'
             },
             {
                 id: 3,
                 title: 'Chicken Sandwich',
                 category: 'Makanan Berat',
                 stock: 8,
                 unit: 'pcs',
                 originalPrice: 22000,
                 price: 15000,
                 status: 'Tersedia',
                 pickupTime: 'Ambil hari ini 18.00 - 21.00',
                 image: 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?auto=format&fit=crop&w=400&q=80',
                 createdAt: '2026-11-18 08:00:00'
             },
             {
                 id: 4,
                 title: 'Donut Mix',
                 category: 'Snack',
                 stock: 20,
                 unit: 'pcs',
                 originalPrice: 15000,
                 price: 10000,
                 status: 'Tersedia',
                 pickupTime: 'Ambil hari ini 18.30 - 21.30',
                 image: 'https://images.unsplash.com/photo-1527515862127-a4fc05baf7a5?auto=format&fit=crop&w=400&q=80',
                 createdAt: '2026-11-18 08:15:00'
             },
             {
                 id: 5,
                 title: 'Salad Sayur Segar',
                 category: 'Makanan Sehat',
                 stock: 6,
                 unit: 'porsi',
                 originalPrice: 18000,
                 price: 12000,
                 status: 'Tersedia',
                 pickupTime: 'Ambil hari ini 17.00 - 20.00',
                 image: 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=400&q=80',
                 createdAt: '2026-11-18 08:30:00'
             },
             {
                 id: 6,
                 title: 'Red Velvet Slice',
                 category: 'Dessert',
                 stock: 0,
                 unit: 'pcs',
                 originalPrice: 20000,
                 price: 13000,
                 status: 'Habis',
                 pickupTime: 'Ambil hari ini 18.00 - 20.30',
                 image: 'https://images.unsplash.com/photo-1586788680434-30d324b2d46f?auto=format&fit=crop&w=400&q=80',
                 createdAt: '2026-11-18 08:45:00'
             },
             {
                 id: 7,
                 title: 'Es Kopi Susu Gula Aren',
                 category: 'Minuman',
                 stock: 15,
                 unit: 'cup',
                 originalPrice: 15000,
                 price: 8000,
                 status: 'Tersedia',
                 pickupTime: 'Ambil hari ini 16.00 - 20.00',
                 image: 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?auto=format&fit=crop&w=400&q=80',
                 createdAt: '2026-11-18 09:00:00'
             },
             {
                 id: 8,
                 title: 'Roti Cokelat',
                 category: 'Roti & Kue',
                 stock: 18,
                 unit: 'pcs',
                 originalPrice: 12000,
                 price: 8000,
                 status: 'Dijadwalkan',
                 pickupTime: 'Ambil hari ini 19.00 - 21.30',
                 image: 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=400&q=80',
                 createdAt: '2026-11-18 09:15:00'
             },
             {
                 id: 9,
                 title: 'Sourdough Artisan Loaf',
                 category: 'Roti & Kue',
                 stock: 7,
                 unit: 'pcs',
                 originalPrice: 35000,
                 price: 22000,
                 status: 'Tersedia',
                 pickupTime: 'Ambil hari ini 18.00 - 21.00',
                 image: 'https://images.unsplash.com/photo-1589367920969-ab8e050bbb04?auto=format&fit=crop&w=400&q=80',
                 createdAt: '2026-11-18 09:20:00'
             },
             {
                 id: 10,
                 title: 'Spaghetti Bolognese',
                 category: 'Makanan Berat',
                 stock: 5,
                 unit: 'porsi',
                 originalPrice: 27000,
                 price: 20500,
                 status: 'Tersedia',
                 pickupTime: 'Ambil hari ini 19.00 - 21.00',
                 image: 'https://images.unsplash.com/photo-1621996346565-e3d5d6281691?auto=format&fit=crop&w=400&q=80',
                 createdAt: '2026-11-18 09:25:00'
             },
             {
                 id: 11,
                 title: 'Green Detox Cold Pressed',
                 category: 'Minuman',
                 stock: 9,
                 unit: 'cup',
                 originalPrice: 22000,
                 price: 14000,
                 status: 'Tersedia',
                 pickupTime: 'Ambil hari ini 17.00 - 20.00',
                 image: 'https://images.unsplash.com/photo-1613478223719-2ab802602423?auto=format&fit=crop&w=400&q=80',
                 createdAt: '2026-11-18 09:30:00'
             },
             {
                 id: 12,
                 title: 'Cheese Danish Pastry',
                 category: 'Roti & Kue',
                 stock: 0,
                 unit: 'pcs',
                 originalPrice: 18000,
                 price: 12500,
                 status: 'Habis',
                 pickupTime: 'Ambil hari ini 18.00 - 20.30',
                 image: 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=400&q=80',
                 createdAt: '2026-11-18 09:35:00'
             },
             {
                 id: 13,
                 title: 'Fruit Granola Bowl',
                 category: 'Makanan Sehat',
                 stock: 4,
                 unit: 'porsi',
                 originalPrice: 25000,
                 price: 16000,
                 status: 'Tersedia',
                 pickupTime: 'Ambil hari ini 16.30 - 19.30',
                 image: 'https://images.unsplash.com/photo-1511690656952-34342bb7c2f2?auto=format&fit=crop&w=400&q=80',
                 createdAt: '2026-11-18 09:40:00'
             },
             {
                 id: 14,
                 title: 'Cinnamon Roll Glaze',
                 category: 'Snack',
                 stock: 11,
                 unit: 'pcs',
                 originalPrice: 14000,
                 price: 9500,
                 status: 'Tersedia',
                 pickupTime: 'Ambil hari ini 18.30 - 21.00',
                 image: 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=400&q=80',
                 createdAt: '2026-11-18 09:45:00'
             },
             {
                 id: 15,
                 title: 'Tiramisu Cup Classic',
                 category: 'Dessert',
                 stock: 0,
                 unit: 'cup',
                 originalPrice: 24000,
                 price: 16000,
                 status: 'Habis',
                 pickupTime: 'Ambil hari ini 17.00 - 20.00',
                 image: 'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?auto=format&fit=crop&w=400&q=80',
                 createdAt: '2026-11-18 09:50:00'
             },
             {
                 id: 16,
                 title: 'Matcha Milk Tea',
                 category: 'Minuman',
                 stock: 14,
                 unit: 'cup',
                 originalPrice: 18000,
                 price: 11000,
                 status: 'Tersedia',
                 pickupTime: 'Ambil hari ini 17.00 - 20.30',
                 image: 'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?auto=format&fit=crop&w=400&q=80',
                 createdAt: '2026-11-18 09:55:00'
             },
             {
                 id: 17,
                 title: 'Avocado Caesar Wrap',
                 category: 'Makanan Sehat',
                 stock: 10,
                 unit: 'pcs',
                 originalPrice: 25000,
                 price: 17000,
                 status: 'Dijadwalkan',
                 pickupTime: 'Ambil hari ini 19.00 - 22.00',
                 image: 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=400&q=80',
                 createdAt: '2026-11-18 10:00:00'
             },
             {
                 id: 18,
                 title: 'Chocolate Mousse Pie',
                 category: 'Dessert',
                 stock: 0,
                 unit: 'pcs',
                 originalPrice: 22000,
                 price: 14500,
                 status: 'Habis',
                 pickupTime: 'Ambil hari ini 18.00 - 21.00',
                 image: 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?auto=format&fit=crop&w=400&q=80',
                 createdAt: '2026-11-18 10:05:00'
             }
         ],

         // Filter Logic Computations
         get filteredFoods() {
             return this.foods.filter(item => {
                 // 1. Filter Tab Cepat
                 if (this.activeStatusTab !== 'all' && item.status !== this.activeStatusTab) {
                     return false;
                 }

                 // 2. Search Text
                 if (this.searchQuery.trim() !== '') {
                     const q = this.searchQuery.toLowerCase();
                     const matchTitle = item.title.toLowerCase().includes(q);
                     const matchCategory = item.category.toLowerCase().includes(q);
                     if (!matchTitle && !matchCategory) return false;
                 }

                 // 3. Category Checklist
                 if (!this.selectedCategories.includes('Semua Kategori')) {
                     if (!this.selectedCategories.includes(item.category)) {
                         return false;
                     }
                 }

                 // 4. Status Checklist (dari sidebar filter jika tab all aktif)
                 if (this.activeStatusTab === 'all' && this.selectedStatuses.length > 0) {
                     if (!this.selectedStatuses.includes(item.status)) {
                         return false;
                     }
                 }

                 // 5. Rentang Harga
                 if (this.minPrice !== '' && item.price < Number(this.minPrice)) {
                     return false;
                 }
                 if (this.maxPrice !== '' && item.price > Number(this.maxPrice)) {
                     return false;
                 }

                 return true;
             }).sort((a, b) => {
                 if (this.sortBy === 'harga-rendah') return a.price - b.price;
                 if (this.sortBy === 'harga-tinggi') return b.price - a.price;
                 if (this.sortBy === 'stok-banyak') return b.stock - a.stock;
                 if (this.sortBy === 'nama-az') return a.title.localeCompare(b.title);
                 return b.id - a.id; // 'terbaru' (default)
             });
         },

         // Paginated Foods
         get paginatedFoods() {
             const start = (this.currentPage - 1) * this.itemsPerPage;
             return this.filteredFoods.slice(start, start + this.itemsPerPage);
         },

         get totalPages() {
             return Math.ceil(this.filteredFoods.length / this.itemsPerPage) || 1;
         },

         // Helper Counts for Tabs
         countByStatus(status) {
             if (status === 'all') return this.foods.length;
             return this.foods.filter(f => f.status === status).length;
         },

         // Format Currency Helper
         formatRupiah(number) {
             return 'Rp ' + Number(number).toLocaleString('id-ID');
         },

         // Toggle Category in Filter Checklist
         toggleCategory(catName) {
             if (catName === 'Semua Kategori') {
                 this.selectedCategories = ['Semua Kategori'];
             } else {
                 this.selectedCategories = this.selectedCategories.filter(c => c !== 'Semua Kategori');
                 if (this.selectedCategories.includes(catName)) {
                     this.selectedCategories = this.selectedCategories.filter(c => c !== catName);
                     if (this.selectedCategories.length === 0) {
                         this.selectedCategories = ['Semua Kategori'];
                     }
                 } else {
                     this.selectedCategories.push(catName);
                 }
             }
             this.currentPage = 1;
         },

         // Toggle Status in Filter Checklist
         toggleStatus(statusName) {
             if (this.selectedStatuses.includes(statusName)) {
                 this.selectedStatuses = this.selectedStatuses.filter(s => s !== statusName);
             } else {
                 this.selectedStatuses.push(statusName);
             }
             this.currentPage = 1;
         },

         // Reset All Filters
         resetFilters() {
             this.activeStatusTab = 'all';
             this.searchQuery = '';
             this.categorySearchQuery = '';
             this.selectedCategories = ['Semua Kategori'];
             this.selectedStatuses = ['Tersedia', 'Habis', 'Dijadwalkan', 'Draft'];
             this.minPrice = '';
             this.maxPrice = '';
             this.sortBy = 'terbaru';
             this.currentPage = 1;
             this.showToast('Filter berhasil direset.');
         },

         // Export Data to CSV
         exportData() {
             let csvContent = 'data:text/csv;charset=utf-8,ID,Nama Makanan,Kategori,Stok,Satuan,Harga,Status,Waktu Pengambilan\n';
             this.foods.forEach(f => {
                 csvContent += `${f.id},\"${f.title}\",\"${f.category}\",${f.stock},${f.unit},${f.price},\"${f.status}\",\"${f.pickupTime}\"\n`;
             });
             const encodedUri = encodeURI(csvContent);
             const link = document.createElement('a');
             link.setAttribute('href', encodedUri);
             link.setAttribute('download', 'daftar_makanan_surplus_sunny_bakery.csv');
             document.body.appendChild(link);
             link.click();
             document.body.removeChild(link);
             this.showToast('Data makanan berhasil diekspor ke CSV! 📄');
         },

         // Add New Surplus Food
         submitNewFood() {
             if (!this.newFood.title.trim()) {
                 this.showToast('Harap masukkan nama makanan.', 'error');
                 return;
             }
             const newId = this.foods.length > 0 ? Math.max(...this.foods.map(f => f.id)) + 1 : 1;
             this.foods.unshift({
                 id: newId,
                 title: this.newFood.title,
                 category: this.newFood.category,
                 stock: Number(this.newFood.stock),
                 unit: this.newFood.unit,
                 originalPrice: Number(this.newFood.originalPrice),
                 price: Number(this.newFood.discountPrice),
                 status: this.newFood.status,
                 pickupTime: this.newFood.pickupTime || 'Ambil hari ini 18.00 - 20.30',
                 image: this.newFood.image || 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=400&q=80',
                 createdAt: new Date().toISOString()
             });
             this.isAddModalOpen = false;
             this.newFood.title = '';
             this.showToast('Makanan surplus baru berhasil ditambahkan! 🌱');
         },

         // Open Edit Modal
         openEditModal(item) {
             this.editingFood = JSON.parse(JSON.stringify(item));
             this.activeActionMenuId = null;
             this.isEditModalOpen = true;
         },

         // Save Edited Food
         saveEditedFood() {
             if (!this.editingFood) return;
             const index = this.foods.findIndex(f => f.id === this.editingFood.id);
             if (index !== -1) {
                 this.foods[index] = { ...this.editingFood };
                 this.isEditModalOpen = false;
                 this.editingFood = null;
                 this.showToast('Data makanan berhasil diperbarui! ✨');
             }
         },

         // Delete Food
         deleteFood(id) {
             this.foods = this.foods.filter(f => f.id !== id);
             this.activeActionMenuId = null;
             this.showToast('Makanan surplus telah dihapus.', 'error');
         },

         // Quick Status Toggle
         toggleItemStatus(item, newStatus) {
             item.status = newStatus;
             this.activeActionMenuId = null;
             this.showToast(`Status ${item.title} diubah menjadi ${newStatus}.`);
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
            
            <!-- Logo What's Left & Business Badge (Match Screenshot) -->
            <div class="flex items-center justify-between mb-8 pl-1">
                <a href="{{ url('/') }}" class="inline-flex flex-col group">
                    <div class="flex items-center text-2xl font-black tracking-tight leading-none">
                        <span class="text-[#1B5E3A]">What's</span>
                        <span class="text-[#E67E22] ml-1">Left</span>
                        <!-- Cute spark / quotes badge like screenshot -->
                        <span class="text-[#E67E22] text-sm -mt-2 ml-0.5 font-bold">✨</span>
                    </div>
                    <!-- Business Pill Badge Underneath Logo -->
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
            <nav class="space-y-1.5 flex-1">
                
                <!-- 1. Dashboard -->
                <a href="{{ url('/?view=dashboard') }}"
                   class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <!-- 2. Makanan Surplus (ACTIVE STATE Menyala Hijau Sesuai Gambar) -->
                <a href="{{ url('/?view=makanan-surplus') }}"
                   class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left bg-[#EBF5F0] text-[#1B5E3A] font-bold shadow-xs">
                    <!-- Cutlery / Surplus Food Icon -->
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

                <!-- 4. Reservasi (with Badge 5 Sesuai Gambar) -->
                <button type="button"
                        @click="showToast('Menu Reservasi (5 reservasi aktif)')"
                        class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Reservasi</span>
                    </div>
                    <!-- Orange Notification Badge (Match screenshot '5') -->
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

                <!-- Spacer -->
                <div class="py-1"></div>

                <!-- 6. Analitik -->
                <button type="button"
                        @click="showToast('Menu Analitik Penjualan')"
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
                        @click="showToast('Menu Ulasan Pelanggan')"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    <span>Review</span>
                </button>

                <!-- Spacer -->
                <div class="py-1"></div>

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

            <!-- Bottom Motivation Box ("Buat lebih banyak food impact!" Match Screenshot) -->
            <div class="mt-6 mb-2 p-4 rounded-2xl bg-[#F0F8F3] border border-[#DCF2E4] relative overflow-hidden">
                <h4 class="text-xs font-bold text-stone-900 leading-snug">Buat lebih banyak food impact!</h4>
                
                <!-- Cute Earth Mascot with Green Leaves & Sprout -->
                <div class="my-3 flex items-center justify-center">
                    <div class="w-16 h-16 rounded-full bg-[#DCF2E4] flex items-center justify-center relative shadow-xs">
                        <!-- Globe Base -->
                        <span class="text-2xl">🌍</span>
                        <!-- Cute Leaves Overlays -->
                        <span class="absolute -top-1 -right-1 text-sm">🌱</span>
                        <span class="absolute -bottom-1 -left-1 text-xs">💧</span>
                    </div>
                </div>

                <p class="text-[11px] text-stone-500 leading-relaxed text-center">
                    Terus bagikan makanan berkualitas yang masih layak konsumsi dan bantu kurangi food waste. 💚
                </p>
            </div>

        </div>
    </aside>

    <!-- ======================================================= -->
    <!-- 2. MAIN CONTENT AREA (TOPBAR + FOOD MANAGEMENT CANVAS) -->
    <!-- ======================================================= -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-[#FAF7F2]">
        
        <!-- TOPBAR NAVBAR -->
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

            <!-- Right: Notifications & Merchant Profile (Sunny Bakery - Owner) -->
            <div class="flex items-center gap-4">
                
                <!-- Notification Bell Button -->
                <div class="relative" x-data="{ open: false }">
                    <button type="button" 
                            @click="open = !open"
                            class="relative p-2 rounded-xl text-stone-600 hover:text-stone-900 hover:bg-stone-100 transition focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <!-- Orange Notification Badge (Match screenshot '5') -->
                        <span class="absolute top-1 right-1 w-4 h-4 bg-[#EA580C] text-white text-[10px] font-bold rounded-full flex items-center justify-center shadow-xs">
                            5
                        </span>
                    </button>

                    <!-- Notification Dropdown -->
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
                            <span class="text-[11px] font-semibold text-[#1B5E3A] cursor-pointer hover:underline" @click="open = false; showToast('Semua notifikasi ditandai dibaca')">Tandai Dibaca</span>
                        </div>
                        <div class="divide-y divide-stone-100 max-h-72 overflow-y-auto custom-scrollbar">
                            <div class="p-3.5 hover:bg-stone-50 transition flex items-start gap-3">
                                <span class="w-2 h-2 rounded-full bg-[#1B5E3A] mt-1.5 shrink-0"></span>
                                <div>
                                    <p class="text-xs font-semibold text-stone-800">Reservasi Baru #WL-2452</p>
                                    <p class="text-[11px] text-stone-500 mt-0.5">Jasmine memesan 2x Croissant Butter.</p>
                                    <span class="text-[10px] text-stone-400 mt-1 block">10 menit yang lalu</span>
                                </div>
                            </div>
                            <div class="p-3.5 hover:bg-stone-50 transition flex items-start gap-3">
                                <span class="w-2 h-2 rounded-full bg-[#EA580C] mt-1.5 shrink-0"></span>
                                <div>
                                    <p class="text-xs font-semibold text-stone-800">Stok Segera Habis!</p>
                                    <p class="text-[11px] text-stone-500 mt-0.5">Sisa stok Salad Sayur Segar tinggal 6 porsi.</p>
                                    <span class="text-[10px] text-stone-400 mt-1 block">45 menit yang lalu</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vertical Divider -->
                <div class="w-px h-6 bg-stone-200"></div>

                <!-- Sunny Bakery Merchant Profile Dropdown (Avatar + Sunny Bakery + Owner) -->
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
                        
                        <!-- Name & Owner Role -->
                        <div class="hidden sm:flex flex-col text-left">
                            <span class="text-xs font-bold text-stone-900 leading-tight">Sunny Bakery</span>
                            <span class="text-[10px] font-medium text-stone-500 leading-tight">Owner</span>
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
                            <p class="text-[11px] text-stone-500">owner@sunnybakery.com</p>
                        </div>
                        
                        <a href="{{ url('/') }}" 
                           class="flex items-center gap-2 px-4 py-2 text-xs text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A] transition">
                            <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>Tampilan Konsumen</span>
                        </a>

                        <button type="button" 
                                @click="openProfile = false; showToast('Membuka pengaturan toko...')"
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

        <!-- MAIN SCROLLABLE CONTENT BODY -->
        <main class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6 custom-scrollbar">
            
            <!-- ======================================================= -->
            <!-- 3. PAGE HEADER & TOP ACTION BUTTONS                     -->
            <!-- ======================================================= -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                
                <!-- Title & Description -->
                <div>
                    <h1 class="text-2xl sm:text-[26px] font-black text-stone-900 tracking-tight flex items-center gap-2">
                        <span>Manajemen Makanan</span>
                        <span class="text-xl">🌱</span>
                    </h1>
                    <p class="text-xs sm:text-sm text-stone-500 mt-0.5">
                        Kelola semua makanan surplus yang kamu tawarkan.
                    </p>
                </div>

                <!-- Action Buttons: + Tambah Makanan & Export (Match Screenshot) -->
                <div class="flex items-center gap-3">
                    
                    <!-- Button + Tambah Makanan (Solid Green) -->
                    <button type="button" 
                            @click="isAddModalOpen = true"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#1B5E3A] hover:bg-[#14472C] text-white text-sm font-semibold shadow-xs hover:shadow-md transition duration-150 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Tambah Makanan</span>
                    </button>

                    <!-- Button Export (White with border) -->
                    <button type="button" 
                            @click="exportData()"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white hover:bg-stone-50 text-stone-700 border border-stone-200 text-sm font-semibold shadow-2xs hover:border-stone-300 transition duration-150 cursor-pointer">
                        <svg class="w-4 h-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        <span>Export</span>
                    </button>

                </div>

            </div>

            <!-- ======================================================= -->
            <!-- 4. QUICK STATUS TABS (Semua Makanan 18, Tersedia 12, dll)-->
            <!-- ======================================================= -->
            <div class="border-b border-stone-200/90 -mb-2">
                <div class="flex items-center gap-6 overflow-x-auto no-scrollbar text-sm font-medium">
                    
                    <!-- Tab: Semua Makanan (18) -->
                    <button type="button" 
                            @click="activeStatusTab = 'all'; currentPage = 1"
                            :class="activeStatusTab === 'all' 
                                ? 'text-[#1B5E3A] border-b-2 border-[#1B5E3A] font-bold pb-3.5' 
                                : 'text-stone-500 hover:text-stone-800 pb-3.5 border-b-2 border-transparent'"
                            class="inline-flex items-center gap-2 whitespace-nowrap transition-colors duration-150">
                        <span>Semua Makanan</span>
                        <span :class="activeStatusTab === 'all' ? 'bg-[#1B5E3A] text-white' : 'bg-stone-100 text-stone-500'" 
                              class="px-2 py-0.5 rounded-full text-xs font-bold transition">
                            18
                        </span>
                    </button>

                    <!-- Tab: Tersedia (12) -->
                    <button type="button" 
                            @click="activeStatusTab = 'Tersedia'; currentPage = 1"
                            :class="activeStatusTab === 'Tersedia' 
                                ? 'text-[#1B5E3A] border-b-2 border-[#1B5E3A] font-bold pb-3.5' 
                                : 'text-stone-500 hover:text-stone-800 pb-3.5 border-b-2 border-transparent'"
                            class="inline-flex items-center gap-2 whitespace-nowrap transition-colors duration-150">
                        <span>Tersedia</span>
                        <span :class="activeStatusTab === 'Tersedia' ? 'bg-[#1B5E3A] text-white' : 'bg-stone-100 text-stone-500'" 
                              class="px-2 py-0.5 rounded-full text-xs font-bold transition">
                            12
                        </span>
                    </button>

                    <!-- Tab: Habis (4) -->
                    <button type="button" 
                            @click="activeStatusTab = 'Habis'; currentPage = 1"
                            :class="activeStatusTab === 'Habis' 
                                ? 'text-[#1B5E3A] border-b-2 border-[#1B5E3A] font-bold pb-3.5' 
                                : 'text-stone-500 hover:text-stone-800 pb-3.5 border-b-2 border-transparent'"
                            class="inline-flex items-center gap-2 whitespace-nowrap transition-colors duration-150">
                        <span>Habis</span>
                        <span :class="activeStatusTab === 'Habis' ? 'bg-[#1B5E3A] text-white' : 'bg-stone-100 text-stone-500'" 
                              class="px-2 py-0.5 rounded-full text-xs font-bold transition">
                            4
                        </span>
                    </button>

                    <!-- Tab: Dijadwalkan (2) -->
                    <button type="button" 
                            @click="activeStatusTab = 'Dijadwalkan'; currentPage = 1"
                            :class="activeStatusTab === 'Dijadwalkan' 
                                ? 'text-[#1B5E3A] border-b-2 border-[#1B5E3A] font-bold pb-3.5' 
                                : 'text-stone-500 hover:text-stone-800 pb-3.5 border-b-2 border-transparent'"
                            class="inline-flex items-center gap-2 whitespace-nowrap transition-colors duration-150">
                        <span>Dijadwalkan</span>
                        <span :class="activeStatusTab === 'Dijadwalkan' ? 'bg-[#1B5E3A] text-white' : 'bg-stone-100 text-stone-500'" 
                              class="px-2 py-0.5 rounded-full text-xs font-bold transition">
                            2
                        </span>
                    </button>

                    <!-- Tab: Draft (0) -->
                    <button type="button" 
                            @click="activeStatusTab = 'Draft'; currentPage = 1"
                            :class="activeStatusTab === 'Draft' 
                                ? 'text-[#1B5E3A] border-b-2 border-[#1B5E3A] font-bold pb-3.5' 
                                : 'text-stone-500 hover:text-stone-800 pb-3.5 border-b-2 border-transparent'"
                            class="inline-flex items-center gap-2 whitespace-nowrap transition-colors duration-150">
                        <span>Draft</span>
                        <span :class="activeStatusTab === 'Draft' ? 'bg-[#1B5E3A] text-white' : 'bg-stone-100 text-stone-500'" 
                              class="px-2 py-0.5 rounded-full text-xs font-bold transition">
                            0
                        </span>
                    </button>

                </div>
            </div>

            <!-- ======================================================= -->
            <!-- 5. DUAL COLUMN LAYOUT: MAIN TABLE (LEFT) + FILTER (RIGHT)-->
            <!-- ======================================================= -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- =================================================== -->
                <!-- LEFT / CENTER COLUMN: TABLE & CONTROLS (8 or 9 COLS)-->
                <!-- =================================================== -->
                <div class="lg:col-span-8 xl:col-span-9 space-y-4">
                    
                    <!-- CONTROL BAR (Search + Sort + View Toggle) -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-transparent">
                        
                        <!-- Search Box ("Cari makanan...") -->
                        <div class="relative w-full sm:w-80">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </span>
                            <input type="text" 
                                   x-model="searchQuery" 
                                   placeholder="Cari makanan..." 
                                   class="w-full pl-10 pr-4 py-2 bg-white border border-stone-200 rounded-xl text-xs sm:text-sm text-stone-800 placeholder-stone-400 focus:outline-none focus:border-[#1B5E3A] focus:ring-1 focus:ring-[#1B5E3A] shadow-2xs transition">
                            <button type="button" 
                                    x-show="searchQuery.length > 0" 
                                    @click="searchQuery = ''"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-stone-400 hover:text-stone-600">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Right Controls: Sort Dropdown & Grid/List View Toggle -->
                        <div class="flex items-center gap-2.5 self-end sm:self-auto">
                            
                            <!-- Sort Dropdown (Urutkan: Terbaru) -->
                            <div class="relative" x-data="{ sortOpen: false }">
                                <button type="button" 
                                        @click="sortOpen = !sortOpen"
                                        class="flex items-center gap-2 px-3.5 py-2 bg-white border border-stone-200 rounded-xl text-xs sm:text-sm font-medium text-stone-700 hover:bg-stone-50 transition shadow-2xs">
                                    <span class="text-stone-400 text-xs">Urutkan:</span>
                                    <span class="font-semibold text-stone-800" 
                                          x-text="sortBy === 'terbaru' ? 'Terbaru' : (sortBy === 'harga-rendah' ? 'Harga: Rendah' : (sortBy === 'harga-tinggi' ? 'Harga: Tinggi' : (sortBy === 'stok-banyak' ? 'Stok Terbanyak' : 'Nama: A-Z')))"></span>
                                    <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                <div x-cloak x-show="sortOpen" 
                                     @click.outside="sortOpen = false"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     class="absolute right-0 mt-1.5 w-48 bg-white border border-stone-200 rounded-xl shadow-lg py-1 z-30">
                                    <button type="button" @click="sortBy = 'terbaru'; sortOpen = false" class="w-full text-left px-3.5 py-2 text-xs text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A] font-medium">Terbaru</button>
                                    <button type="button" @click="sortBy = 'harga-rendah'; sortOpen = false" class="w-full text-left px-3.5 py-2 text-xs text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A] font-medium">Harga: Rendah ke Tinggi</button>
                                    <button type="button" @click="sortBy = 'harga-tinggi'; sortOpen = false" class="w-full text-left px-3.5 py-2 text-xs text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A] font-medium">Harga: Tinggi ke Rendah</button>
                                    <button type="button" @click="sortBy = 'stok-banyak'; sortOpen = false" class="w-full text-left px-3.5 py-2 text-xs text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A] font-medium">Stok Terbanyak</button>
                                    <button type="button" @click="sortBy = 'nama-az'; sortOpen = false" class="w-full text-left px-3.5 py-2 text-xs text-stone-700 hover:bg-[#EBF5F0] hover:text-[#1B5E3A] font-medium">Nama: A - Z</button>
                                </div>
                            </div>

                            <!-- View Mode Toggle: Grid (4 squares) vs List (Lines) -->
                            <div class="flex items-center bg-white border border-stone-200 rounded-xl p-0.5 shadow-2xs">
                                <button type="button" 
                                        @click="viewMode = 'grid'"
                                        :class="viewMode === 'grid' ? 'bg-[#EBF5F0] text-[#1B5E3A]' : 'text-stone-400 hover:text-stone-700'"
                                        title="Tampilan Grid"
                                        class="p-1.5 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                    </svg>
                                </button>
                                <button type="button" 
                                        @click="viewMode = 'list'"
                                        :class="viewMode === 'list' ? 'bg-[#EBF5F0] text-[#1B5E3A]' : 'text-stone-400 hover:text-stone-700'"
                                        title="Tampilan List / Tabel"
                                        class="p-1.5 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                    </svg>
                                </button>
                            </div>

                        </div>

                    </div>

                    <!-- VIEW MODE 1: LIST VIEW (TABLE EXACT MATCH SCREENSHOT) -->
                    <div x-show="viewMode === 'list'" 
                         class="bg-white rounded-2xl border border-stone-200/90 shadow-2xs overflow-hidden">
                        
                        <div class="overflow-x-auto custom-scrollbar">
                            <table class="w-full text-left border-collapse">
                                
                                <!-- Table Head (Match Screenshot Headers) -->
                                <thead>
                                    <tr class="border-b border-stone-100 bg-[#FCFBF8] text-[12px] font-bold text-stone-500 uppercase tracking-wide">
                                        <th class="py-3.5 pl-6 pr-4">Makanan</th>
                                        <th class="py-3.5 px-4">Kategori</th>
                                        <th class="py-3.5 px-4">Stok</th>
                                        <th class="py-3.5 px-4">Harga</th>
                                        <th class="py-3.5 px-4">Status</th>
                                        <th class="py-3.5 pl-4 pr-6 text-center">Aksi</th>
                                    </tr>
                                </thead>

                                <!-- Table Body Rows -->
                                <tbody class="divide-y divide-stone-100 text-sm">
                                    <template x-for="item in paginatedFoods" :key="item.id">
                                        <tr class="hover:bg-[#FAF7F2]/60 transition-colors duration-100 group">
                                            
                                            <!-- 1. Makanan (Thumbnail + Title + Subtitle) -->
                                            <td class="py-3.5 pl-6 pr-4">
                                                <div class="flex items-center gap-3.5">
                                                    <!-- Photo Thumbnail -->
                                                    <div class="w-12 h-12 rounded-xl bg-stone-100 overflow-hidden shrink-0 border border-stone-200/60 shadow-2xs">
                                                        <img :src="item.image" 
                                                             :alt="item.title" 
                                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                                                    </div>
                                                    
                                                    <!-- Title & Category Subtext -->
                                                    <div class="min-w-0">
                                                        <div class="font-bold text-stone-900 truncate leading-snug" x-text="item.title"></div>
                                                        <div class="text-[11px] text-stone-400 mt-0.5 truncate" x-text="item.category"></div>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- 2. Kategori (Color Badge) -->
                                            <td class="py-3.5 px-4 whitespace-nowrap">
                                                <!-- Roti & Kue (Soft Amber) -->
                                                <span x-show="item.category === 'Roti & Kue'" 
                                                      class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-[#FEF3C7] text-[#D97706]">
                                                    Roti & Kue
                                                </span>

                                                <!-- Makanan Berat (Soft Blue) -->
                                                <span x-show="item.category === 'Makanan Berat'" 
                                                      class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-[#EFF6FF] text-[#2563EB]">
                                                    Makanan Berat
                                                </span>

                                                <!-- Snack (Soft Purple) -->
                                                <span x-show="item.category === 'Snack'" 
                                                      class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-[#F3E8FF] text-[#9333EA]">
                                                    Snack
                                                </span>

                                                <!-- Makanan Sehat (Soft Mint / Emerald) -->
                                                <span x-show="item.category === 'Makanan Sehat'" 
                                                      class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-[#ECFDF5] text-[#059669]">
                                                    Makanan Sehat
                                                </span>

                                                <!-- Dessert (Soft Rose / Pink) -->
                                                <span x-show="item.category === 'Dessert'" 
                                                      class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-[#FFE4E6] text-[#E11D48]">
                                                    Dessert
                                                </span>

                                                <!-- Minuman (Soft Peach / Orange) -->
                                                <span x-show="item.category === 'Minuman'" 
                                                      class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-[#FFEDD5] text-[#EA580C]">
                                                    Minuman
                                                </span>
                                            </td>

                                            <!-- 3. Stok -->
                                            <td class="py-3.5 px-4 whitespace-nowrap text-stone-700 text-xs sm:text-sm font-medium">
                                                <span x-text="item.stock + ' ' + item.unit"></span>
                                            </td>

                                            <!-- 4. Harga -->
                                            <td class="py-3.5 px-4 whitespace-nowrap text-stone-800 text-xs sm:text-sm font-semibold">
                                                <span x-text="formatRupiah(item.price)"></span>
                                            </td>

                                            <!-- 5. Status Badge -->
                                            <td class="py-3.5 px-4 whitespace-nowrap">
                                                <!-- Status: Tersedia (Light Green Pill) -->
                                                <span x-show="item.status === 'Tersedia'" 
                                                      class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#DCFCE7] text-[#16A34A]">
                                                    Tersedia
                                                </span>

                                                <!-- Status: Habis (Light Gray Pill) -->
                                                <span x-show="item.status === 'Habis'" 
                                                      class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#F3F4F6] text-[#6B7280]">
                                                    Habis
                                                </span>

                                                <!-- Status: Dijadwalkan (Light Orange/Peach Pill) -->
                                                <span x-show="item.status === 'Dijadwalkan'" 
                                                      class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#FFEDD5] text-[#D97706]">
                                                    Dijadwalkan
                                                </span>

                                                <!-- Status: Draft (Light Stone Pill) -->
                                                <span x-show="item.status === 'Draft'" 
                                                      class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-stone-100 text-stone-600">
                                                    Draft
                                                </span>
                                            </td>

                                            <!-- 6. Aksi (Pencil Edit Icon + 3 Vertical Dots Menu) -->
                                            <td class="py-3.5 pl-4 pr-6 text-center whitespace-nowrap relative">
                                                <div class="inline-flex items-center justify-center gap-1.5">
                                                    
                                                    <!-- Pencil Edit Icon -->
                                                    <button type="button" 
                                                            @click="openEditModal(item)"
                                                            title="Edit Makanan"
                                                            class="p-1.5 rounded-lg text-stone-500 hover:text-[#1B5E3A] hover:bg-stone-100 transition">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                        </svg>
                                                    </button>

                                                    <!-- Three Vertical Dots Menu Button -->
                                                    <div class="relative">
                                                        <button type="button" 
                                                                @click="activeActionMenuId = activeActionMenuId === item.id ? null : item.id"
                                                                title="Menu Opsi"
                                                                class="p-1.5 rounded-lg text-stone-500 hover:text-stone-900 hover:bg-stone-100 transition">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                                            </svg>
                                                        </button>

                                                        <!-- Dropdown Menu -->
                                                        <div x-cloak x-show="activeActionMenuId === item.id" 
                                                             @click.outside="activeActionMenuId = null"
                                                             x-transition:enter="transition ease-out duration-100"
                                                             x-transition:enter-start="opacity-0 scale-95"
                                                             x-transition:enter-end="opacity-100 scale-100"
                                                             class="absolute right-0 mt-1 w-44 bg-white border border-stone-200 rounded-xl shadow-xl py-1.5 z-40 text-left">
                                                            
                                                            <button type="button" 
                                                                    @click="openEditModal(item)"
                                                                    class="w-full px-3.5 py-1.5 text-xs text-stone-700 hover:bg-stone-50 flex items-center gap-2">
                                                                <span>✏️</span> Edit Data
                                                            </button>

                                                            <button type="button" 
                                                                    @click="toggleItemStatus(item, item.status === 'Tersedia' ? 'Habis' : 'Tersedia')"
                                                                    class="w-full px-3.5 py-1.5 text-xs text-stone-700 hover:bg-stone-50 flex items-center gap-2">
                                                                <span>🔄</span> Tandai <span x-text="item.status === 'Tersedia' ? 'Habis' : 'Tersedia'"></span>
                                                            </button>

                                                            <button type="button" 
                                                                    @click="toggleItemStatus(item, 'Dijadwalkan')"
                                                                    class="w-full px-3.5 py-1.5 text-xs text-stone-700 hover:bg-stone-50 flex items-center gap-2">
                                                                <span>⏰</span> Jadwalkan Jam Rilis
                                                            </button>

                                                            <div class="border-t border-stone-100 my-1"></div>

                                                            <button type="button" 
                                                                    @click="deleteFood(item.id)"
                                                                    class="w-full px-3.5 py-1.5 text-xs text-red-600 hover:bg-red-50 flex items-center gap-2 font-medium">
                                                                <span>🗑️</span> Hapus Makanan
                                                            </button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </td>

                                        </tr>
                                    </template>

                                    <!-- Empty State if no foods match filters -->
                                    <tr x-show="paginatedFoods.length === 0">
                                        <td colspan="6" class="py-12 text-center text-stone-500">
                                            <div class="max-w-xs mx-auto text-center space-y-2">
                                                <span class="text-3xl block">🔍</span>
                                                <p class="text-sm font-bold text-stone-800">Tidak ada makanan yang cocok</p>
                                                <p class="text-xs text-stone-500">Coba ubah kata kunci pencarian atau reset filter di sebelah kanan.</p>
                                                <button type="button" 
                                                        @click="resetFilters()"
                                                        class="mt-2 inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#EBF5F0] text-[#1B5E3A] text-xs font-semibold rounded-lg hover:bg-[#1B5E3A] hover:text-white transition">
                                                    Reset Semua Filter
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>

                            </table>
                        </div>

                    </div>

                    <!-- VIEW MODE 2: GRID VIEW (RESPONSIVE CARDS) -->
                    <div x-show="viewMode === 'grid'" 
                         class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                        <template x-for="item in paginatedFoods" :key="item.id">
                            <div class="bg-white rounded-2xl border border-stone-200/90 overflow-hidden shadow-2xs hover:shadow-md transition-all flex flex-col justify-between">
                                <div>
                                    <!-- Card Image & Status Overlays -->
                                    <div class="relative h-44 bg-stone-100 overflow-hidden">
                                        <img :src="item.image" :alt="item.title" class="w-full h-full object-cover">
                                        <div class="absolute top-3 left-3">
                                            <span x-show="item.status === 'Tersedia'" class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#DCFCE7] text-[#16A34A] shadow-xs">Tersedia</span>
                                            <span x-show="item.status === 'Habis'" class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#F3F4F6] text-[#6B7280] shadow-xs">Habis</span>
                                            <span x-show="item.status === 'Dijadwalkan'" class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#FFEDD5] text-[#D97706] shadow-xs">Dijadwalkan</span>
                                        </div>
                                        <div class="absolute top-3 right-3">
                                            <button type="button" @click="openEditModal(item)" class="w-8 h-8 rounded-full bg-white/90 backdrop-blur-xs flex items-center justify-center text-stone-700 hover:text-[#1B5E3A] shadow-xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Card Content -->
                                    <div class="p-4 space-y-2">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-semibold px-2 py-0.5 rounded-md"
                                                  :class="{
                                                      'bg-[#FEF3C7] text-[#D97706]': item.category === 'Roti & Kue',
                                                      'bg-[#EFF6FF] text-[#2563EB]': item.category === 'Makanan Berat',
                                                      'bg-[#F3E8FF] text-[#9333EA]': item.category === 'Snack',
                                                      'bg-[#ECFDF5] text-[#059669]': item.category === 'Makanan Sehat',
                                                      'bg-[#FFE4E6] text-[#E11D48]': item.category === 'Dessert',
                                                      'bg-[#FFEDD5] text-[#EA580C]': item.category === 'Minuman'
                                                  }"
                                                  x-text="item.category"></span>
                                            <span class="text-xs text-stone-500 font-medium" x-text="'Stok: ' + item.stock + ' ' + item.unit"></span>
                                        </div>
                                        <h3 class="text-sm font-bold text-stone-900 line-clamp-1" x-text="item.title"></h3>
                                        <div class="flex items-baseline gap-2 pt-1">
                                            <span class="text-base font-extrabold text-[#1B5E3A]" x-text="formatRupiah(item.price)"></span>
                                            <span class="text-xs text-stone-400 line-through" x-text="formatRupiah(item.originalPrice)"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card Footer -->
                                <div class="px-4 py-3 bg-stone-50 border-t border-stone-100 flex items-center justify-between text-xs text-stone-500">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span x-text="item.pickupTime"></span>
                                    </span>
                                    <button type="button" @click="deleteFood(item.id)" class="text-red-500 hover:text-red-700">Hapus</button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- PAGINATION (Match Screenshot: < 1 2 3 >) -->
                    <div class="pt-2 flex items-center justify-center">
                        <nav class="inline-flex items-center gap-1.5" aria-label="Pagination">
                            
                            <!-- Previous Page Arrow Button -->
                            <button type="button" 
                                    @click="if (currentPage > 1) currentPage--"
                                    :disabled="currentPage === 1"
                                    :class="currentPage === 1 ? 'opacity-40 cursor-not-allowed' : 'hover:bg-stone-100 text-stone-600'"
                                    class="w-8 h-8 rounded-lg bg-white border border-stone-200 flex items-center justify-center transition shadow-2xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>

                            <!-- Page 1 (Active by Default / Highlight Green) -->
                            <button type="button" 
                                    @click="currentPage = 1"
                                    :class="currentPage === 1 ? 'bg-[#1B5E3A] text-white font-bold shadow-2xs' : 'bg-white border border-stone-200 text-stone-700 hover:bg-stone-50 font-medium'"
                                    class="w-8 h-8 rounded-lg flex items-center justify-center text-xs transition">
                                1
                            </button>

                            <!-- Page 2 -->
                            <button type="button" 
                                    @click="currentPage = 2"
                                    :class="currentPage === 2 ? 'bg-[#1B5E3A] text-white font-bold shadow-2xs' : 'bg-white border border-stone-200 text-stone-700 hover:bg-stone-50 font-medium'"
                                    class="w-8 h-8 rounded-lg flex items-center justify-center text-xs transition">
                                2
                            </button>

                            <!-- Page 3 -->
                            <button type="button" 
                                    @click="currentPage = 3"
                                    :class="currentPage === 3 ? 'bg-[#1B5E3A] text-white font-bold shadow-2xs' : 'bg-white border border-stone-200 text-stone-700 hover:bg-stone-50 font-medium'"
                                    class="w-8 h-8 rounded-lg flex items-center justify-center text-xs transition">
                                3
                            </button>

                            <!-- Next Page Arrow Button -->
                            <button type="button" 
                                    @click="if (currentPage < totalPages) currentPage++"
                                    :disabled="currentPage >= totalPages"
                                    :class="currentPage >= totalPages ? 'opacity-40 cursor-not-allowed' : 'hover:bg-stone-100 text-stone-600'"
                                    class="w-8 h-8 rounded-lg bg-white border border-stone-200 flex items-center justify-center transition shadow-2xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>

                        </nav>
                    </div>

                </div>

                <!-- =================================================== -->
                <!-- RIGHT COLUMN: FILTER SIDEBAR PANEL (3 or 4 COLS)    -->
                <!-- =================================================== -->
                <div class="lg:col-span-4 xl:col-span-3 space-y-5">
                    
                    <!-- FILTER CARD CONTAINER (Match Screenshot) -->
                    <div class="bg-white rounded-2xl border border-stone-200/90 p-5 space-y-6 shadow-2xs">
                        
                        <!-- Header Filter & Reset Link -->
                        <div class="flex items-center justify-between pb-1 border-b border-stone-100">
                            <h3 class="text-base font-bold text-stone-900">Filter</h3>
                            <button type="button" 
                                    @click="resetFilters()"
                                    class="text-xs font-semibold text-[#1B5E3A] hover:underline cursor-pointer">
                                Reset
                            </button>
                        </div>

                        <!-- Search Kategori Input -->
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-stone-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </span>
                            <input type="text" 
                                   x-model="categorySearchQuery"
                                   placeholder="Cari kategori..." 
                                   class="w-full pl-8 pr-3 py-1.5 bg-[#FAF7F2] border border-stone-200 rounded-xl text-xs text-stone-800 placeholder-stone-400 focus:outline-none focus:border-[#1B5E3A] focus:bg-white transition">
                        </div>

                        <!-- Section: Kategori (Checklist with Custom Checkbox & Icons) -->
                        <div class="space-y-3">
                            <label class="block text-xs font-bold text-stone-900 tracking-wide">
                                Kategori
                            </label>

                            <div class="space-y-2.5">
                                <template x-for="cat in categoriesList" :key="cat.id">
                                    <div x-show="categorySearchQuery === '' || cat.name.toLowerCase().includes(categorySearchQuery.toLowerCase())"
                                         @click="toggleCategory(cat.name)"
                                         class="flex items-center gap-2.5 cursor-pointer group select-none">
                                        
                                        <!-- Custom Checkbox (Dark Green when selected) -->
                                        <div class="w-4 h-4 rounded border flex items-center justify-center transition-colors"
                                             :class="selectedCategories.includes(cat.name) 
                                                 ? 'bg-[#1B5E3A] border-[#1B5E3A] text-white' 
                                                 : 'border-stone-300 bg-white group-hover:border-stone-400'">
                                            <svg x-show="selectedCategories.includes(cat.name)" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>

                                        <!-- Category Icon + Label -->
                                        <div class="flex items-center gap-2 text-xs font-medium text-stone-700 group-hover:text-stone-900">
                                            <span class="text-sm" x-text="cat.icon"></span>
                                            <span x-text="cat.name"></span>
                                        </div>

                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Section: Status (Checklist Tersedia, Habis, Dijadwalkan, Draft) -->
                        <div class="space-y-3 pt-2 border-t border-stone-100">
                            <label class="block text-xs font-bold text-stone-900 tracking-wide">
                                Status
                            </label>

                            <div class="space-y-2.5">
                                <template x-for="st in statusesList" :key="st">
                                    <div @click="toggleStatus(st)" 
                                         class="flex items-center gap-2.5 cursor-pointer group select-none">
                                        
                                        <!-- Checkbox -->
                                        <div class="w-4 h-4 rounded border flex items-center justify-center transition-colors"
                                             :class="selectedStatuses.includes(st) 
                                                 ? 'bg-[#1B5E3A] border-[#1B5E3A] text-white' 
                                                 : 'border-stone-300 bg-white group-hover:border-stone-400'">
                                            <svg x-show="selectedStatuses.includes(st)" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>

                                        <!-- Label -->
                                        <span class="text-xs font-medium text-stone-700 group-hover:text-stone-900" x-text="st"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Section: Rentang Harga (Min Rp - Maks Rp) -->
                        <div class="space-y-3 pt-2 border-t border-stone-100">
                            <label class="block text-xs font-bold text-stone-900 tracking-wide">
                                Rentang Harga
                            </label>

                            <div class="grid grid-cols-2 gap-2.5">
                                <!-- Min Price -->
                                <div>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-[10px] font-bold text-stone-400">Min</span>
                                        <input type="number" 
                                               x-model="minPrice" 
                                               @input="currentPage = 1"
                                               placeholder="Rp" 
                                               class="w-full pl-8 pr-2 py-1.5 bg-[#FAF7F2] border border-stone-200 rounded-xl text-xs text-stone-800 placeholder-stone-400 focus:outline-none focus:border-[#1B5E3A] focus:bg-white transition">
                                    </div>
                                </div>

                                <!-- Max Price -->
                                <div>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-[10px] font-bold text-stone-400">Maks</span>
                                        <input type="number" 
                                               x-model="maxPrice" 
                                               @input="currentPage = 1"
                                               placeholder="Rp" 
                                               class="w-full pl-9 pr-2 py-1.5 bg-[#FAF7F2] border border-stone-200 rounded-xl text-xs text-stone-800 placeholder-stone-400 focus:outline-none focus:border-[#1B5E3A] focus:bg-white transition">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- TIPS INFORMATIF CARD (Match Screenshot Amber Box with Bag & Croissant) -->
                    <div class="rounded-2xl bg-[#FFFBEB] border border-[#FDE68A] p-4.5 space-y-3 relative overflow-hidden shadow-2xs">
                        
                        <!-- Header: Lightbulb Icon + Tips -->
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-amber-100 flex items-center justify-center text-amber-700 text-xs">
                                💡
                            </span>
                            <span class="text-xs font-bold text-amber-900">Tips</span>
                        </div>

                        <!-- Description text -->
                        <p class="text-[11px] text-amber-900/80 leading-relaxed font-normal">
                            Pastikan informasi makanan selalu update agar pelanggan mendapatkan pengalaman terbaik.
                        </p>

                        <!-- Cute Eco Paper Bag + Food Illustration (Match Screenshot) -->
                        <div class="pt-2 flex items-center justify-center gap-3">
                            <div class="w-12 h-14 bg-[#DCF2E4] border border-[#1B5E3A]/25 rounded-xl flex flex-col items-center justify-center relative shadow-xs">
                                <div class="w-6 h-3 border-2 border-[#1B5E3A] border-b-0 rounded-t-full -mt-3 mb-1"></div>
                                <span class="text-sm">🌱</span>
                            </div>
                            <div class="flex flex-col gap-1 text-xl">
                                <span>🥗</span>
                                <span>🥐</span>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </main>

    </div>

    <!-- ======================================================= -->
    <!-- 6. MODAL: TAMBAH MAKANAN SURPLUS (+ TAMBAH MAKANAN)     -->
    <!-- ======================================================= -->
    <div x-cloak x-show="isAddModalOpen" 
         class="fixed inset-0 z-50 overflow-y-auto"
         role="dialog" 
         aria-modal="true">
        
        <!-- Backdrop -->
        <div x-show="isAddModalOpen"
             x-transition:enter="transition-opacity ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="isAddModalOpen = false"
             class="fixed inset-0 bg-stone-900/50 backdrop-blur-xs"></div>

        <!-- Modal Box -->
        <div class="min-h-full flex items-center justify-center p-4">
            <div x-show="isAddModalOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-stone-200/80 space-y-5">
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center text-base">
                            🥐
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-stone-900 tracking-tight">Tambah Makanan Surplus</h3>
                            <p class="text-xs text-stone-500">Daftarkan makanan surplus untuk ditawarkan ke konsumen.</p>
                        </div>
                    </div>
                    <button type="button" 
                            @click="isAddModalOpen = false"
                            class="p-1 rounded-xl text-stone-400 hover:text-stone-700 hover:bg-stone-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Modal Form -->
                <form @submit.prevent="submitNewFood()" class="space-y-4">
                    
                    <!-- Nama Makanan -->
                    <div>
                        <label class="block text-xs font-bold text-stone-800 mb-1">Nama Makanan *</label>
                        <input type="text" 
                               x-model="newFood.title" 
                               required
                               placeholder="Contoh: Danish Pastry Mix, Bento Teriyaki" 
                               class="w-full px-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-xs sm:text-sm text-stone-800 focus:outline-none focus:border-[#1B5E3A] focus:bg-white transition">
                    </div>

                    <!-- Kategori & Status -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-stone-800 mb-1">Kategori *</label>
                            <select x-model="newFood.category" 
                                    class="w-full px-3 py-2 bg-stone-50 border border-stone-200 rounded-xl text-xs sm:text-sm text-stone-800 focus:outline-none focus:border-[#1B5E3A] focus:bg-white transition">
                                <option value="Roti & Kue">Roti & Kue</option>
                                <option value="Makanan Berat">Makanan Berat</option>
                                <option value="Makanan Sehat">Makanan Sehat</option>
                                <option value="Snack">Snack</option>
                                <option value="Dessert">Dessert</option>
                                <option value="Minuman">Minuman</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-800 mb-1">Status Awal</label>
                            <select x-model="newFood.status" 
                                    class="w-full px-3 py-2 bg-stone-50 border border-stone-200 rounded-xl text-xs sm:text-sm text-stone-800 focus:outline-none focus:border-[#1B5E3A] focus:bg-white transition">
                                <option value="Tersedia">Tersedia</option>
                                <option value="Dijadwalkan">Dijadwalkan</option>
                                <option value="Habis">Habis</option>
                                <option value="Draft">Draft</option>
                            </select>
                        </div>
                    </div>

                    <!-- Stok & Satuan -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-stone-800 mb-1">Jumlah Stok *</label>
                            <input type="number" 
                                   x-model="newFood.stock" 
                                   min="0"
                                   required 
                                   class="w-full px-3 py-2 bg-stone-50 border border-stone-200 rounded-xl text-xs sm:text-sm text-stone-800 focus:outline-none focus:border-[#1B5E3A] focus:bg-white transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-800 mb-1">Satuan</label>
                            <select x-model="newFood.unit" 
                                    class="w-full px-3 py-2 bg-stone-50 border border-stone-200 rounded-xl text-xs sm:text-sm text-stone-800 focus:outline-none focus:border-[#1B5E3A] focus:bg-white transition">
                                <option value="pcs">pcs</option>
                                <option value="porsi">porsi</option>
                                <option value="bowl">bowl</option>
                                <option value="cup">cup</option>
                                <option value="slice">slice</option>
                                <option value="box">box</option>
                            </select>
                        </div>
                    </div>

                    <!-- Harga Normal vs Harga Surplus (Diskon) -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-stone-800 mb-1">Harga Normal (Rp)</label>
                            <input type="number" 
                                   x-model="newFood.originalPrice" 
                                   placeholder="15000" 
                                   class="w-full px-3 py-2 bg-stone-50 border border-stone-200 rounded-xl text-xs sm:text-sm text-stone-800 focus:outline-none focus:border-[#1B5E3A] focus:bg-white transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-800 mb-1">Harga Surplus Diskon (Rp) *</label>
                            <input type="number" 
                                   x-model="newFood.discountPrice" 
                                   required
                                   placeholder="10000" 
                                   class="w-full px-3 py-2 bg-stone-50 border border-stone-200 rounded-xl text-xs sm:text-sm text-stone-800 font-bold text-[#1B5E3A] focus:outline-none focus:border-[#1B5E3A] focus:bg-white transition">
                        </div>
                    </div>

                    <!-- Jam Pengambilan -->
                    <div>
                        <label class="block text-xs font-bold text-stone-800 mb-1">Jadwal Pengambilan Konsumen</label>
                        <input type="text" 
                               x-model="newFood.pickupTime" 
                               placeholder="Ambil hari ini 18.00 - 20.30" 
                               class="w-full px-3.5 py-2 bg-stone-50 border border-stone-200 rounded-xl text-xs sm:text-sm text-stone-800 focus:outline-none focus:border-[#1B5E3A] focus:bg-white transition">
                    </div>

                    <!-- URL Foto Makanan -->
                    <div>
                        <label class="block text-xs font-bold text-stone-800 mb-1">Foto Makanan (URL)</label>
                        <input type="url" 
                               x-model="newFood.image" 
                               placeholder="https://..." 
                               class="w-full px-3.5 py-2 bg-stone-50 border border-stone-200 rounded-xl text-xs sm:text-sm text-stone-800 focus:outline-none focus:border-[#1B5E3A] focus:bg-white transition">
                    </div>

                    <!-- Submit & Cancel Buttons -->
                    <div class="pt-3 flex items-center justify-end gap-3 border-t border-stone-100">
                        <button type="button" 
                                @click="isAddModalOpen = false"
                                class="px-4 py-2.5 rounded-xl border border-stone-200 text-stone-600 hover:bg-stone-50 text-xs sm:text-sm font-semibold transition">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-5 py-2.5 rounded-xl bg-[#1B5E3A] hover:bg-[#14472C] text-white text-xs sm:text-sm font-bold shadow-xs hover:shadow-md transition">
                            Simpan Makanan
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>

    <!-- ======================================================= -->
    <!-- 7. MODAL: EDIT MAKANAN SURPLUS                           -->
    <!-- ======================================================= -->
    <div x-cloak x-show="isEditModalOpen" 
         class="fixed inset-0 z-50 overflow-y-auto"
         role="dialog" 
         aria-modal="true">
        
        <!-- Backdrop -->
        <div x-show="isEditModalOpen"
             @click="isEditModalOpen = false"
             class="fixed inset-0 bg-stone-900/50 backdrop-blur-xs"></div>

        <!-- Modal Box -->
        <div class="min-h-full flex items-center justify-center p-4">
            <div x-show="isEditModalOpen"
                 class="relative bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-stone-200/80 space-y-5"
                 x-if="editingFood !== null">
                
                <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center text-base">
                            ✏️
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-stone-900 tracking-tight">Edit Makanan Surplus</h3>
                            <p class="text-xs text-stone-500">Perbarui informasi stok, harga, dan status ketersediaan.</p>
                        </div>
                    </div>
                    <button type="button" 
                            @click="isEditModalOpen = false"
                            class="p-1 rounded-xl text-stone-400 hover:text-stone-700 hover:bg-stone-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <template x-if="editingFood">
                    <form @submit.prevent="saveEditedFood()" class="space-y-4">
                        
                        <div>
                            <label class="block text-xs font-bold text-stone-800 mb-1">Nama Makanan</label>
                            <input type="text" 
                                   x-model="editingFood.title" 
                                   required 
                                   class="w-full px-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-xs sm:text-sm text-stone-800 focus:outline-none focus:border-[#1B5E3A] focus:bg-white transition">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-stone-800 mb-1">Kategori</label>
                                <select x-model="editingFood.category" 
                                        class="w-full px-3 py-2 bg-stone-50 border border-stone-200 rounded-xl text-xs sm:text-sm text-stone-800 focus:outline-none focus:border-[#1B5E3A] focus:bg-white transition">
                                    <option value="Roti & Kue">Roti & Kue</option>
                                    <option value="Makanan Berat">Makanan Berat</option>
                                    <option value="Makanan Sehat">Makanan Sehat</option>
                                    <option value="Snack">Snack</option>
                                    <option value="Dessert">Dessert</option>
                                    <option value="Minuman">Minuman</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-stone-800 mb-1">Status Ketersediaan</label>
                                <select x-model="editingFood.status" 
                                        class="w-full px-3 py-2 bg-stone-50 border border-stone-200 rounded-xl text-xs sm:text-sm text-stone-800 focus:outline-none focus:border-[#1B5E3A] focus:bg-white transition">
                                    <option value="Tersedia">Tersedia</option>
                                    <option value="Habis">Habis</option>
                                    <option value="Dijadwalkan">Dijadwalkan</option>
                                    <option value="Draft">Draft</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-stone-800 mb-1">Stok Tersedia</label>
                                <input type="number" 
                                       x-model="editingFood.stock" 
                                       min="0"
                                       required 
                                       class="w-full px-3 py-2 bg-stone-50 border border-stone-200 rounded-xl text-xs sm:text-sm text-stone-800 focus:outline-none focus:border-[#1B5E3A] focus:bg-white transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-stone-800 mb-1">Harga Diskon (Rp)</label>
                                <input type="number" 
                                       x-model="editingFood.price" 
                                       required 
                                       class="w-full px-3 py-2 bg-stone-50 border border-stone-200 rounded-xl text-xs sm:text-sm text-stone-800 font-bold text-[#1B5E3A] focus:outline-none focus:border-[#1B5E3A] focus:bg-white transition">
                            </div>
                        </div>

                        <div class="pt-3 flex items-center justify-end gap-3 border-t border-stone-100">
                            <button type="button" 
                                    @click="isEditModalOpen = false"
                                    class="px-4 py-2.5 rounded-xl border border-stone-200 text-stone-600 hover:bg-stone-50 text-xs sm:text-sm font-semibold transition">
                                Batal
                            </button>
                            <button type="submit" 
                                    class="px-5 py-2.5 rounded-xl bg-[#1B5E3A] hover:bg-[#14472C] text-white text-xs sm:text-sm font-bold shadow-xs hover:shadow-md transition">
                                Perbarui Data
                            </button>
                        </div>

                    </form>
                </template>

            </div>
        </div>

    </div>

</div>
@endsection
