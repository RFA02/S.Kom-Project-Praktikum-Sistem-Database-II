@extends('layouts.main')

@section('title', "Detail Makanan: Croissant Butter - What's Left")

@section('content')
<div x-data="{
    // Mock Data Makanan Sesuai Screenshot Attachment
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
            title: 'Sushi Set Salmon',
            merchant: 'Sushi Sato',
            merchantAvatar: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=120&q=80',
            merchantVerified: true,
            rating: 4.8,
            reviewsCount: 88,
            distance: '2.5 km',
            category: 'Japanese',
            categorySpec: 'Makanan Berat',
            discount: 35,
            originalPrice: 42000,
            discountPrice: 27300,
            pickupTime: 'Hari ini, 19.00 - 21.00',
            pickupLocation: 'Jl. Senopati No. 41, Jakarta Selatan',
            stock: 5,
            unit: 'porsi',
            freshness: 'Disiapkan chef hari ini pukul 14.00 WIB',
            image: 'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?auto=format&fit=crop&w=800&q=80',
            gallery: [
                'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?auto=format&fit=crop&w=800&q=80',
                'https://images.unsplash.com/photo-1611143669185-af224c5e3252?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1553621042-f6e147245754?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1563245372-f21724e3856d?auto=format&fit=crop&w=600&q=80'
            ],
            isWishlist: false,
            description: 'Kombinasi salmon nigiri dan maki roll segar siap konsumsi.'
        },
        {
            id: 3,
            title: 'Chocolate Chip Cookies',
            merchant: 'Sweet Tooth Bakery',
            merchantAvatar: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=120&q=80',
            merchantVerified: true,
            rating: 4.9,
            reviewsCount: 210,
            distance: '1.8 km',
            category: 'Bakery',
            categorySpec: 'Snack & Kue',
            discount: 25,
            originalPrice: 20000,
            discountPrice: 15000,
            pickupTime: 'Hari ini, 18.00 - 20.00',
            pickupLocation: 'Jl. Panglima Polim No. 15, Jakarta',
            stock: 8,
            unit: 'box',
            freshness: 'Dipanggang hangat pukul 09.00 WIB',
            image: 'https://images.unsplash.com/photo-1499636136210-6f4ee915583e?auto=format&fit=crop&w=800&q=80',
            gallery: [
                'https://images.unsplash.com/photo-1499636136210-6f4ee915583e?auto=format&fit=crop&w=800&q=80',
                'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1586788680434-30d324b2d46f?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1549931319-a545dcf3bc73?auto=format&fit=crop&w=600&q=80'
            ],
            isWishlist: false,
            description: 'Cookies renyah dengan lelehan cokelat belgia premium.'
        }
    ],

    // State Modal Pop-up
    selectedFood: null,
    activeImage: null,
    orderQuantity: 1,

    // Live Countdown Timer (02:45:30 -> 9930 detik)
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
        this.timerSeconds = 9930;
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

        this.selectedFood.stock = Math.max(0, this.selectedFood.stock - this.orderQuantity);
        setTimeout(() => this.closeDetail(), 1400);
    },

    init() {
        // Otomatis membuka modal produk Croissant Butter saat halaman dimuat
        this.$nextTick(() => {
            this.openDetail(this.foods[0]);
        });
    }
}" 
x-init="init()"
x-on:show-toast.window="showToast($event.detail.msg)">

    <!-- BACKGROUND PAGE: Halaman Eksplorasi Makanan Surplus (Persis Latar Belakang Screenshot) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Header Eksplorasi -->
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-stone-900 tracking-tight">Jelajahi Makanan Surplus</h1>
            <p class="text-sm text-stone-500 mt-1">Temukan makanan lezat dengan harga diskon dari sekitarmu</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Sidebar Filter Mockup -->
            <div class="hidden lg:block lg:col-span-1 space-y-6 bg-white p-6 rounded-2xl border border-stone-200/80 shadow-xs h-fit">
                <div>
                    <h3 class="text-sm font-bold text-stone-900 uppercase tracking-wider mb-3">Filter</h3>
                    <div class="space-y-2 text-xs text-stone-600">
                        <label class="flex items-center gap-2"><input type="checkbox" checked class="rounded text-[#1B5E3A]"> Semua Kategori</label>
                        <label class="flex items-center gap-2"><input type="checkbox" class="rounded text-[#1B5E3A]"> Makanan Berat</label>
                        <label class="flex items-center gap-2"><input type="checkbox" class="rounded text-[#1B5E3A]"> Roti & Kue</label>
                        <label class="flex items-center gap-2"><input type="checkbox" class="rounded text-[#1B5E3A]"> Minuman</label>
                        <label class="flex items-center gap-2"><input type="checkbox" class="rounded text-[#1B5E3A]"> Dessert</label>
                    </div>
                </div>

                <div class="pt-4 border-t border-stone-100">
                    <h4 class="text-xs font-bold text-stone-900 mb-2">Lokasi</h4>
                    <div class="space-y-1.5 text-xs text-stone-600">
                        <label class="flex items-center gap-2"><input type="radio" name="lokasi" checked class="text-[#1B5E3A]"> Lokasi Saya</label>
                        <label class="flex items-center gap-2"><input type="radio" name="lokasi" class="text-[#1B5E3A]"> Pilih Lokasi Lain</label>
                    </div>
                </div>

                <div class="pt-4 border-t border-stone-100">
                    <h4 class="text-xs font-bold text-stone-900 mb-2">Rentang Harga</h4>
                    <div class="space-y-1.5 text-xs text-stone-600">
                        <label class="flex items-center gap-2"><input type="radio" name="harga" checked class="text-[#1B5E3A]"> Semua Harga</label>
                        <label class="flex items-center gap-2"><input type="radio" name="harga" class="text-[#1B5E3A]"> Di bawah Rp 10.000</label>
                        <label class="flex items-center gap-2"><input type="radio" name="harga" class="text-[#1B5E3A]"> Rp 10.000 - Rp 20.000</label>
                        <label class="flex items-center gap-2"><input type="radio" name="harga" class="text-[#1B5E3A]"> Di atas Rp 30.000</label>
                    </div>
                </div>
            </div>

            <!-- Grid Kartu Makanan Eksplorasi -->
            <div class="lg:col-span-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <template x-for="food in foods" :key="food.id">
                        <div class="bg-white rounded-2xl overflow-hidden border border-stone-200 shadow-xs hover:shadow-lg transition flex flex-col justify-between group cursor-pointer"
                             @click="openDetail(food)">
                            <div class="relative aspect-[4/3] overflow-hidden bg-stone-100">
                                <img :src="food.image" :alt="food.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <div class="absolute top-3 left-3 px-2.5 py-1 rounded-lg bg-[#F59E0B] text-white text-xs font-black shadow-md">
                                    <span x-text="food.discount + '% OFF'"></span>
                                </div>
                                <div class="absolute bottom-2.5 left-2.5 px-2 py-0.5 rounded-md bg-black/60 backdrop-blur-xs text-white text-[10px] font-semibold">
                                    <span x-text="'Sisa ' + food.stock + ' porsi'"></span>
                                </div>
                            </div>
                            <div class="p-4 space-y-2">
                                <div class="text-xs text-stone-500 font-medium" x-text="food.merchant"></div>
                                <h3 class="text-base font-bold text-stone-900 group-hover:text-[#1B5E3A] transition" x-text="food.title"></h3>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-base font-black text-[#E67E22]" x-text="formatRupiah(food.discountPrice)"></span>
                                    <span class="text-xs line-through text-stone-400 font-medium" x-text="formatRupiah(food.originalPrice)"></span>
                                </div>
                                <div class="pt-2 border-t border-stone-100 text-[11px] text-stone-500 flex items-center justify-between">
                                    <span x-text="food.pickupTime"></span>
                                    <span class="font-bold text-[#1B5E3A]" x-text="food.distance"></span>
                                </div>
                                <button type="button" class="w-full mt-2 py-2 rounded-xl bg-[#EBF5F0] text-[#1B5E3A] text-xs font-bold hover:bg-[#1B5E3A] hover:text-white transition">
                                    Lihat Detail & Pesan
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

        </div>

    </div>

    <!-- MODAL DETAIL MAKANAN POP-UP -->
    @include('components.consumer.food-detail-modal')

</div>
@endsection
