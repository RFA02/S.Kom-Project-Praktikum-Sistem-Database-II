@extends('layouts.business')

@section('title', 'Manajemen Makanan - ' . $business->business_name)

@section('content')
<div class="flex h-screen overflow-hidden bg-[#FAF7F2]"
     x-data="{
    mobileSidebarOpen: false,
    activeNav: 'master-makanan',
    profileDropdownOpen: false,
    viewMode: 'list',
    activeStatusTab: 'all',
    searchQuery: '',
    categorySearchQuery: '',
    newImage: null,
    editImage: null,
    selectedCategories: ['Semua Kategori'],
    categoriesList: [
        { id: 'semua', name: 'Semua Kategori', icon: '✨' },
        { id: 'roti', name: 'Roti & Kue', icon: '🥐' },
        { id: 'berat', name: 'Makanan Berat', icon: '🍱' },
        { id: 'snack', name: 'Snack', icon: '🍪' },
        { id: 'dessert', name: 'Dessert', icon: '🍰' },
        { id: 'minuman', name: 'Minuman', icon: '🥤' },
        { id: 'lainnya', name: 'Lainnya', icon: '🍽️' }
    ],
    selectedStatuses: ['Ada Listing Aktif', 'Stok Habis', 'Belum Ada Listing'],
    statusesList: ['Ada Listing Aktif', 'Stok Habis', 'Belum Ada Listing'],
    minPrice: '',
    maxPrice: '',
    sortBy: 'terbaru',
    currentPage: 1,
    itemsPerPage: 8,
    isAddModalOpen: false,
    isEditModalOpen: false,
    editingFood: null,
    activeActionMenuId: null,
    formError: '',
    newFood: {
        title: '',
        category: 'Bread & Pastry',
        description: '',
        originalPrice: ''
    },
    foods: @js($foods),

    get filteredFoods() {
        return this.foods.filter(item => {
            if (this.activeStatusTab !== 'all' && item.status !== this.activeStatusTab) return false;

            if (this.searchQuery.trim() !== '') {
                const query = this.searchQuery.toLowerCase().trim();
                if (!item.title.toLowerCase().includes(query) &&
                    !item.category.toLowerCase().includes(query) &&
                    !(item.description || '').toLowerCase().includes(query)) return false;
            }

            if (!this.selectedCategories.includes('Semua Kategori') &&
                !this.selectedCategories.includes(item.category)) return false;

            if (this.activeStatusTab === 'all' &&
                this.selectedStatuses.length > 0 &&
                !this.selectedStatuses.includes(item.status)) return false;

            if (this.minPrice !== '' && item.price < Number(this.minPrice)) return false;
            if (this.maxPrice !== '' && item.price > Number(this.maxPrice)) return false;

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
        return this.foods.filter(food => food.status === status).length;
    },

    formatRupiah(value) {
        return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
    },

    categoryLabel(code) {
        const labels = {
            'Main Course': 'Makanan Berat',
            'Bread & Pastry': 'Roti & Kue',
            'Beverage': 'Minuman',
            'Snack': 'Snack',
            'Dessert': 'Dessert',
            'Other': 'Lainnya'
        };
        return labels[code] || 'Lainnya';
    },

    toggleCategory(name) {
        if (name === 'Semua Kategori') {
            this.selectedCategories = ['Semua Kategori'];
        } else {
            this.selectedCategories = this.selectedCategories.filter(item => item !== 'Semua Kategori');
            if (this.selectedCategories.includes(name)) {
                this.selectedCategories = this.selectedCategories.filter(item => item !== name);
                if (this.selectedCategories.length === 0) this.selectedCategories = ['Semua Kategori'];
            } else {
                this.selectedCategories.push(name);
            }
        }
        this.currentPage = 1;
    },

    toggleStatus(name) {
        if (this.selectedStatuses.includes(name)) {
            this.selectedStatuses = this.selectedStatuses.filter(item => item !== name);
        } else {
            this.selectedStatuses.push(name);
        }
        this.currentPage = 1;
    },

    resetFilters() {
        this.activeStatusTab = 'all';
        this.searchQuery = '';
        this.categorySearchQuery = '';
        this.selectedCategories = ['Semua Kategori'];
        this.selectedStatuses = [...this.statusesList];
        this.minPrice = '';
        this.maxPrice = '';
        this.sortBy = 'terbaru';
        this.currentPage = 1;
    },

    exportData() {
        const headers = ['ID', 'Nama Makanan', 'Kategori', 'Deskripsi', 'Harga Normal', 'Stok Listing Aktif', 'Jumlah Listing'];
        const rows = this.foods.map(food => [
            food.id, food.title, food.category, food.description || '',
            food.price, food.stock, food.listingCount
        ]);
        const csv = [headers, ...rows].map(row =>
            row.map(value => String.fromCharCode(34) + String(value ?? '').replaceAll(String.fromCharCode(34), String.fromCharCode(34, 34)) + String.fromCharCode(34)).join(',')
        ).join('\n');
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = 'daftar-makanan-whats-left.csv';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
    },

    showToast(message, type = 'success') {
        this.$dispatch('show-toast', { msg: message, type });
    },

    async submitNewFood() {
        this.formError = '';
        if (!this.newFood.title.trim()) {
            this.formError = 'Nama makanan wajib diisi.';
            return;
        }
        if (this.newFood.originalPrice === '' || Number(this.newFood.originalPrice) < 0) {
            this.formError = 'Harga normal harus berupa angka non-negatif.';
            return;
        }

        try {
            const response = await fetch('/business/food-management', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify({
                    food_name: this.newFood.title,
                    category: this.newFood.category,
                    description: this.newFood.description,
                    normal_price: Number(this.newFood.originalPrice)
                })
            });
            const result = await response.json();

            if (!response.ok) {
                const firstError = result.errors ? Object.values(result.errors).flat()[0] : null;
                this.formError = firstError || result.message || 'Makanan gagal ditambahkan.';
                return;
            }

            if (this.newImage) {
                const imgError = await this.uploadImage(result.data.id, this.newImage);
                if (imgError) { window.alert('Makanan tersimpan, tetapi foto gagal diunggah: ' + imgError); }
            }

            this.isAddModalOpen = false;
            this.newImage = null;
            this.newFood = { title: '', category: 'Bread & Pastry', description: '', originalPrice: '' };
            this.showToast(result.message || 'Makanan berhasil ditambahkan.');
            window.location.reload();
        } catch (error) {
            this.formError = 'Tidak dapat terhubung ke server. Silakan coba kembali.';
        }
    },

    async uploadImage(foodId, file) {
        const formData = new FormData();
        formData.append('image', file);
        try {
            const response = await fetch('/business/food-management/' + foodId + '/image', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: formData
            });
            const result = await response.json();
            if (!response.ok) {
                return (result.errors ? Object.values(result.errors).flat()[0] : null) || result.message || 'Foto gagal diunggah.';
            }
            return null;
        } catch (error) {
            return 'Tidak dapat terhubung ke server.';
        }
    },

    openEditModal(item) {
        this.editingFood = {
            id: item.id,
            title: item.title,
            category: item.categoryCode,
            description: item.description || '',
            originalPrice: item.originalPrice
        };
        this.editImage = null;
        this.formError = '';
        this.activeActionMenuId = null;
        this.isEditModalOpen = true;
    },

    async saveEditedFood() {
        if (!this.editingFood) return;
        this.formError = '';

        try {
            const response = await fetch(`/business/food-management/${this.editingFood.id}`, {
                method: 'PUT',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify({
                    food_name: this.editingFood.title,
                    category: this.editingFood.category,
                    description: this.editingFood.description,
                    normal_price: Number(this.editingFood.originalPrice)
                })
            });
            const result = await response.json();

            if (!response.ok) {
                const firstError = result.errors ? Object.values(result.errors).flat()[0] : null;
                this.formError = firstError || result.message || 'Data makanan gagal diperbarui.';
                return;
            }

            if (this.editImage) {
                const imgError = await this.uploadImage(this.editingFood.id, this.editImage);
                if (imgError) { window.alert('Data tersimpan, tetapi foto gagal diunggah: ' + imgError); }
            }

            this.isEditModalOpen = false;
            this.editingFood = null;
            this.showToast(result.message || 'Data makanan berhasil diperbarui.');
            window.location.reload();
        } catch (error) {
            this.formError = 'Tidak dapat terhubung ke server. Silakan coba kembali.';
        }
    },

    async deleteFood(id) {
        if (!window.confirm('Hapus makanan ini? Jika sudah punya riwayat (produksi atau listing), makanan akan DIARSIPKAN, bukan dihapus permanen, dan bisa dipulihkan.')) return;

        try {
            const response = await fetch(`/business/food-management/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                }
            });
            const result = await response.json();

            if (!response.ok) {
                this.showToast(result.message || 'Makanan gagal dihapus.', 'error');
                return;
            }

            this.showToast(result.message || 'Makanan berhasil dihapus.');
            window.location.reload();
        } catch (error) {
            this.showToast('Tidak dapat terhubung ke server. Silakan coba kembali.', 'error');
        }
    }
}">

    <!-- ======================================================= -->
    <!-- 1. SIDEBAR KIRI (DESKTOP DOCKED & MOBILE DRAWER)        -->
    <!-- ======================================================= -->

    <x-business.sidebar active="food-management" />

    <!-- ======================================================= -->
    <!-- 2. MAIN CONTENT AREA (TOPBAR + FOOD MANAGEMENT CANVAS) -->
    <!-- ======================================================= -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-[#FAF7F2]">
        
        <x-business.topbar />

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
                        Kelola master makanan bisnis untuk catatan produksi dan listing surplus.
                    </p>
                </div>

                <!-- Action Buttons: + Tambah Makanan & Export (Match Screenshot) -->
                <div class="flex items-center gap-3">
                    
                    <!-- Button + Tambah Makanan (Solid Green) -->
                    <button type="button" 
                            @click="newImage = null; formError = ''; isAddModalOpen = true"
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
                            <span x-text="countByStatus('all')"></span>
                        </span>
                    </button>

                    <!-- Tab: Ada Listing Aktif -->
                    <button type="button" 
                            @click="activeStatusTab = 'Ada Listing Aktif'; currentPage = 1"
                            :class="activeStatusTab === 'Ada Listing Aktif' 
                                ? 'text-[#1B5E3A] border-b-2 border-[#1B5E3A] font-bold pb-3.5' 
                                : 'text-stone-500 hover:text-stone-800 pb-3.5 border-b-2 border-transparent'"
                            class="inline-flex items-center gap-2 whitespace-nowrap transition-colors duration-150">
                        <span>Ada Listing Aktif</span>
                        <span :class="activeStatusTab === 'Ada Listing Aktif' ? 'bg-[#1B5E3A] text-white' : 'bg-stone-100 text-stone-500'" 
                              class="px-2 py-0.5 rounded-full text-xs font-bold transition">
                            <span x-text="countByStatus('Ada Listing Aktif')"></span>
                        </span>
                    </button>

                    <!-- Tab: Stok Habis -->
                    <button type="button" 
                            @click="activeStatusTab = 'Stok Habis'; currentPage = 1"
                            :class="activeStatusTab === 'Stok Habis' 
                                ? 'text-[#1B5E3A] border-b-2 border-[#1B5E3A] font-bold pb-3.5' 
                                : 'text-stone-500 hover:text-stone-800 pb-3.5 border-b-2 border-transparent'"
                            class="inline-flex items-center gap-2 whitespace-nowrap transition-colors duration-150">
                        <span>Stok Habis</span>
                        <span :class="activeStatusTab === 'Stok Habis' ? 'bg-[#1B5E3A] text-white' : 'bg-stone-100 text-stone-500'" 
                              class="px-2 py-0.5 rounded-full text-xs font-bold transition">
                            <span x-text="countByStatus('Stok Habis')"></span>
                        </span>
                    </button>

                    <!-- Tab: Belum Ada Listing -->
                    <button type="button" 
                            @click="activeStatusTab = 'Belum Ada Listing'; currentPage = 1"
                            :class="activeStatusTab === 'Belum Ada Listing' 
                                ? 'text-[#1B5E3A] border-b-2 border-[#1B5E3A] font-bold pb-3.5' 
                                : 'text-stone-500 hover:text-stone-800 pb-3.5 border-b-2 border-transparent'"
                            class="inline-flex items-center gap-2 whitespace-nowrap transition-colors duration-150">
                        <span>Belum Ada Listing</span>
                        <span :class="activeStatusTab === 'Belum Ada Listing' ? 'bg-[#1B5E3A] text-white' : 'bg-stone-100 text-stone-500'" 
                              class="px-2 py-0.5 rounded-full text-xs font-bold transition">
                            <span x-text="countByStatus('Belum Ada Listing')"></span>
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
                                   @input="currentPage = 1"
                                   placeholder="Cari makanan..." 
                                   class="w-full pl-10 pr-4 py-2 bg-white border border-stone-200 rounded-xl text-xs sm:text-sm text-stone-800 placeholder-stone-400 focus:outline-none focus:border-[#1B5E3A] focus:ring-1 focus:ring-[#1B5E3A] shadow-2xs transition">
                            <button type="button" 
                                    x-show="searchQuery.length > 0" 
                                    @click="searchQuery = ''; currentPage = 1"
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
                                        <th class="py-3.5 px-4">Stok Listing Aktif</th>
                                        <th class="py-3.5 px-4">Harga Normal</th>
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
                                                    <div class="relative w-12 h-12 rounded-xl bg-stone-100 overflow-hidden shrink-0 border border-stone-200/60 shadow-2xs">
                                                        <img x-show="item.image" x-cloak :src="item.image" :alt="item.title" loading="lazy" class="absolute inset-0 w-full h-full object-cover">
                                                        <div class="w-full h-full flex items-center justify-center text-2xl bg-[#EBF5F0] text-[#1B5E3A]" x-text="item.emoji"></div>
                                                    </div>
                                                    
                                                    <!-- Title & Category Subtext -->
                                                    <div class="min-w-0">
                                                        <div class="font-bold text-stone-900 truncate leading-snug" x-text="item.title"></div>
                                                        <div class="text-[11px] text-stone-400 mt-0.5 truncate" x-text="item.description || item.category"></div>
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

                                                <span x-show="item.category === 'Lainnya'"
                                                      class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-stone-100 text-stone-600">
                                                    Lainnya
                                                </span>
                                            </td>

                                            <!-- 3. Stok -->
                                            <td class="py-3.5 px-4 whitespace-nowrap text-stone-700 text-xs sm:text-sm font-medium">
                                                <span x-text="item.stock + ' porsi tersedia'"></span>
                                            </td>

                                            <!-- 4. Harga -->
                                            <td class="py-3.5 px-4 whitespace-nowrap text-stone-800 text-xs sm:text-sm font-semibold">
                                                <span x-text="formatRupiah(item.price)"></span>
                                            </td>

                                            <!-- 5. Status Badge -->
                                            <td class="py-3.5 px-4 whitespace-nowrap">
                                                <!-- Status: Tersedia (Light Green Pill) -->
                                                <span x-show="item.status === 'Ada Listing Aktif'" 
                                                      class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#DCFCE7] text-[#16A34A]">
                                                    Ada Listing Aktif
                                                </span>

                                                <!-- Status: Habis (Light Gray Pill) -->
                                                <span x-show="item.status === 'Stok Habis'" 
                                                      class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#F3F4F6] text-[#6B7280]">
                                                    Stok Habis
                                                </span>

                                                <!-- Status: Dijadwalkan (Light Orange/Peach Pill) -->
                                                <span x-show="item.status === 'Belum Ada Listing'" 
                                                      class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#FFEDD5] text-[#D97706]">
                                                    Belum Ada Listing
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
                                                                    @click="window.location.href = '{{ route('business.surplus-listings') }}'"
                                                                    class="w-full px-3.5 py-1.5 text-xs text-stone-700 hover:bg-stone-50 flex items-center gap-2">
                                                                <span>📦</span> Kelola Listing Surplus
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
                                        <img x-show="item.image" x-cloak :src="item.image" :alt="item.title" loading="lazy" class="absolute inset-0 w-full h-full object-cover">
                                        <div class="w-full h-full flex items-center justify-center text-5xl bg-[#EBF5F0] text-[#1B5E3A]" x-text="item.emoji"></div>
                                        <div class="absolute top-3 left-3">
                                            <span x-show="item.status === 'Ada Listing Aktif'" class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#DCFCE7] text-[#16A34A] shadow-xs">Ada Listing Aktif</span>
                                            <span x-show="item.status === 'Stok Habis'" class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#F3F4F6] text-[#6B7280] shadow-xs">Stok Habis</span>
                                            <span x-show="item.status === 'Belum Ada Listing'" class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#FFEDD5] text-[#D97706] shadow-xs">Belum Ada Listing</span>
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
                                                      'bg-stone-100 text-stone-600': item.category === 'Lainnya',
                                                      'bg-[#FFE4E6] text-[#E11D48]': item.category === 'Dessert',
                                                      'bg-[#FFEDD5] text-[#EA580C]': item.category === 'Minuman'
                                                  }"
                                                  x-text="item.category"></span>
                                            <span class="text-xs text-stone-500 font-medium" x-text="'Stok: ' + item.stock + ' porsi tersedia'"></span>
                                        </div>
                                        <h3 class="text-sm font-bold text-stone-900 line-clamp-1" x-text="item.title"></h3>
                                        <div class="flex items-baseline gap-2 pt-1">
                                            <span class="text-base font-extrabold text-[#1B5E3A]" x-text="formatRupiah(item.price)"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card Footer -->
                                <div class="px-4 py-3 bg-stone-50 border-t border-stone-100 flex items-center justify-between text-xs text-stone-500">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span x-text="item.listingCount + ' listing terkait'"></span>
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

                            <template x-for="page in totalPages" :key="page">
                                <button type="button"
                                        @click="currentPage = page"
                                        :class="currentPage === page ? 'bg-[#1B5E3A] text-white font-bold shadow-2xs' : 'bg-white border border-stone-200 text-stone-700 hover:bg-stone-50 font-medium'"
                                        class="w-8 h-8 rounded-lg flex items-center justify-center text-xs transition"
                                        x-text="page"></button>
                            </template>

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


            @if(session('success') || session('error'))
                <div class="rounded-xl px-4 py-3 text-sm font-semibold {{ session('error') ? 'bg-rose-50 border border-rose-200 text-rose-700' : 'bg-[#DCF2E4] border border-[#BBF7D0] text-[#166534]' }}">
                    {{ session('error') ?? session('success') }}
                </div>
            @endif

            @if(isset($archivedFoods) && $archivedFoods->isNotEmpty())
                <section class="bg-white rounded-2xl border border-stone-200 p-5">
                    <h2 class="text-sm font-extrabold text-stone-900">Makanan Diarsipkan ({{ $archivedFoods->count() }})</h2>
                    <p class="text-xs text-stone-500 mt-1">Tidak tampil di daftar dan pilihan listing/produksi, tetapi riwayat reservasi dan analitik tetap tersimpan.</p>
                    <ul class="mt-4 divide-y divide-stone-100">
                        @foreach($archivedFoods as $archived)
                            <li class="py-3 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span class="text-xl">{{ $archived['emoji'] }}</span>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-stone-900 truncate">{{ $archived['title'] }}</p>
                                        <p class="text-[11px] text-stone-500">{{ $archived['category'] }}@if($archived['archivedAt']) · diarsipkan {{ $archived['archivedAt'] }}@endif</p>
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('business.food-management.restore', $archived['id']) }}">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 rounded-lg border border-[#1B5E3A] text-[#1B5E3A] text-xs font-bold hover:bg-[#EBF5F0]">Pulihkan</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif
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
                            <h3 class="text-lg font-black text-stone-900 tracking-tight">Tambah Master Makanan</h3>
                            <p class="text-xs text-stone-500">Tambahkan makanan ke master data bisnis.</p>
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

                    <!-- Kategori -->
                    <div>
                        <label class="block text-xs font-bold text-stone-800 mb-1">Kategori *</label>
                        <select x-model="newFood.category" required
                                class="w-full px-3 py-2 bg-stone-50 border border-stone-200 rounded-xl text-xs sm:text-sm text-stone-800 focus:outline-none focus:border-[#1B5E3A] focus:bg-white transition">
                            <option value="Main Course">Makanan Berat</option>
                            <option value="Bread & Pastry">Roti & Kue</option>
                            <option value="Snack">Snack</option>
                            <option value="Beverage">Minuman</option>
                            <option value="Dessert">Dessert</option>
                            <option value="Other">Lainnya</option>
                        </select>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-xs font-bold text-stone-800 mb-1">Deskripsi</label>
                        <textarea x-model="newFood.description" rows="3"
                                  placeholder="Deskripsi singkat makanan..."
                                  class="w-full px-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-xs sm:text-sm text-stone-800 focus:outline-none focus:border-[#1B5E3A] focus:bg-white transition"></textarea>
                    </div>

                    <!-- Harga Normal -->
                    <div>
                        <label class="block text-xs font-bold text-stone-800 mb-1">Harga Normal (Rp) *</label>
                        <input type="number" min="0" step="1" required
                               x-model="newFood.originalPrice"
                               placeholder="15000"
                               class="w-full px-3 py-2 bg-stone-50 border border-stone-200 rounded-xl text-xs sm:text-sm text-stone-800 focus:outline-none focus:border-[#1B5E3A] focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-stone-800 mb-1">Foto Makanan (opsional, JPG/PNG/WEBP maks 2 MB)</label>
                        <input type="file" accept="image/png,image/jpeg,image/webp" @change="newImage = $event.target.files[0] || null"
                               class="block w-full text-xs text-stone-600 file:mr-3 file:px-3 file:py-2 file:rounded-lg file:border-0 file:bg-[#EBF5F0] file:text-[#1B5E3A] file:font-bold">
                    </div>

                    <div x-show="formError" x-cloak class="px-3.5 py-2.5 rounded-xl bg-red-50 border border-red-200 text-xs font-semibold text-red-600" x-text="formError"></div>

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
                 class="relative bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-stone-200/80 space-y-5">
                
                <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center text-base">
                            ✏️
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-stone-900 tracking-tight">Edit Master Makanan</h3>
                            <p class="text-xs text-stone-500">Perbarui nama, kategori, deskripsi, dan harga normal.</p>
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

                        <div>
                            <label class="block text-xs font-bold text-stone-800 mb-1">Kategori</label>
                            <select x-model="editingFood.category" required
                                    class="w-full px-3 py-2 bg-stone-50 border border-stone-200 rounded-xl text-xs sm:text-sm text-stone-800 focus:outline-none focus:border-[#1B5E3A] focus:bg-white transition">
                                <option value="Main Course">Makanan Berat</option>
                                <option value="Bread & Pastry">Roti & Kue</option>
                                <option value="Snack">Snack</option>
                                <option value="Beverage">Minuman</option>
                                <option value="Dessert">Dessert</option>
                                <option value="Other">Lainnya</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-800 mb-1">Deskripsi</label>
                            <textarea x-model="editingFood.description" rows="3"
                                      class="w-full px-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-xs sm:text-sm text-stone-800 focus:outline-none focus:border-[#1B5E3A] focus:bg-white transition"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-800 mb-1">Harga Normal (Rp)</label>
                            <input type="number" min="0" step="1" required
                                   x-model="editingFood.originalPrice"
                                   class="w-full px-3 py-2 bg-stone-50 border border-stone-200 rounded-xl text-xs sm:text-sm text-stone-800 focus:outline-none focus:border-[#1B5E3A] focus:bg-white transition">
                        </div>

                        <div>
                        <label class="block text-xs font-bold text-stone-800 mb-1">Foto Makanan (opsional, JPG/PNG/WEBP maks 2 MB)</label>
                        <input type="file" accept="image/png,image/jpeg,image/webp" @change="editImage = $event.target.files[0] || null"
                               class="block w-full text-xs text-stone-600 file:mr-3 file:px-3 file:py-2 file:rounded-lg file:border-0 file:bg-[#EBF5F0] file:text-[#1B5E3A] file:font-bold">
                    </div>

                    <div x-show="formError" x-cloak class="px-3.5 py-2.5 rounded-xl bg-red-50 border border-red-200 text-xs font-semibold text-red-600" x-text="formError"></div>

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
