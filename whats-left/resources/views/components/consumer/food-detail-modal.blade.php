{{-- 
    Komponen Modal Pop-up: Detail Makanan (Food Details) - What's Left
    Sesuai spesifikasi visual & UI/UX mockup Consumer Food Rescue.
    Teknologi: Blade + Tailwind CSS v4 + Alpine.js
--}}
<div x-cloak 
     x-show="selectedFood !== null" 
     class="fixed inset-0 z-50 overflow-y-auto" 
     aria-labelledby="modal-food-title" 
     role="dialog" 
     aria-modal="true"
     @keydown.escape.window="closeDetail()">

    <!-- 1. Backdrop Blur Overlay (Menggelapkan & memburamkan halaman eksplorasi di baliknya) -->
    <div x-show="selectedFood !== null"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="closeDetail()"
         class="fixed inset-0 bg-stone-900/60 backdrop-blur-md transition-opacity"></div>

    <!-- 2. Kontainer Posisi Tengah Dialog -->
    <div class="flex min-h-full items-center justify-center p-3 sm:p-4 md:p-6 text-center">
        
        <div x-show="selectedFood !== null"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all w-full max-w-5xl lg:max-w-6xl border border-stone-200/80 p-5 sm:p-7 md:p-8">

            <template x-if="selectedFood">
                <div>
                    <!-- Tombol Tutup (X) di Pojok Kanan Atas -->
                    <button type="button" 
                            @click="closeDetail()"
                            class="absolute top-5 right-5 z-30 w-10 h-10 rounded-full bg-stone-100/90 hover:bg-stone-200 text-stone-500 hover:text-stone-900 flex items-center justify-center transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20"
                            aria-label="Tutup detail makanan">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>

                    <!-- GRID UTAMA (Desktop 12-Kolom / Split Layout) -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
                        
                        <!-- ======================================================== -->
                        <!-- KOLOM KIRI: Visual & Galeri Foto Produk (5 Kolom Desktop) -->
                        <!-- ======================================================== -->
                        <div class="lg:col-span-5 flex flex-col space-y-3">
                            
                            <!-- Foto Produk Utama Besar -->
                            <div class="relative w-full aspect-[4/3] sm:aspect-square rounded-2xl overflow-hidden bg-stone-100 shadow-sm border border-stone-200/60 group">
                                <img :src="activeImage || selectedFood.image" 
                                     :alt="selectedFood.title" 
                                     class="w-full h-full object-cover transition-all duration-500 group-hover:scale-105">
                                
                                <!-- Badge Diskon Kuning/Amber (Top-Left) -->
                                <div class="absolute top-3.5 left-3.5 px-3 py-1.5 rounded-full bg-[#F59E0B] text-stone-900 font-extrabold text-xs shadow-md tracking-wider flex items-center gap-1">
                                    <span x-text="selectedFood.discount + '% OFF'">30% OFF</span>
                                </div>

                                <!-- Tombol Ikon Wishlist (Top-Right) -->
                                <button type="button" 
                                        @click="toggleWishlist(selectedFood)"
                                        class="absolute top-3.5 right-3.5 w-10 h-10 rounded-full bg-white/95 backdrop-blur-xs shadow-md flex items-center justify-center hover:scale-110 active:scale-95 transition-all duration-200 group/btn"
                                        :title="selectedFood.isWishlist ? 'Hapus dari favorit' : 'Tambah ke favorit'"
                                        aria-label="Favorit">
                                    <svg class="w-5 h-5 transition-colors" 
                                         :class="selectedFood.isWishlist ? 'text-red-500 fill-red-500' : 'text-stone-400 group-hover/btn:text-red-500'" 
                                         fill="none" 
                                         stroke="currentColor" 
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Galeri Thumbnail Foto Kecil (4 Kotak di Bawah Foto Utama) -->
                            <div class="grid grid-cols-4 gap-2.5">
                                <template x-for="(thumb, index) in (selectedFood.gallery || [selectedFood.image, selectedFood.image, selectedFood.image, selectedFood.image]).slice(0, 4)" :key="index">
                                    <div @click="activeImage = thumb"
                                         class="relative aspect-square rounded-xl overflow-hidden cursor-pointer border-2 transition-all duration-200"
                                         :class="(activeImage === thumb || (!activeImage && index === 0)) ? 'border-[#1B5E3A] ring-2 ring-[#1B5E3A]/20' : 'border-transparent hover:opacity-80'">
                                        <img :src="thumb" 
                                             :alt="'Foto ' + (index + 1)" 
                                             class="w-full h-full object-cover">
                                        
                                        <!-- Indikator Overlay +2 pada Thumbnail Ke-4 (Persis Mockup) -->
                                        <template x-if="index === 3">
                                            <div class="absolute inset-0 bg-black/55 backdrop-blur-[1px] flex items-center justify-center text-white font-extrabold text-sm sm:text-base">
                                                <span>+2</span>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>

                        </div>


                        <!-- ======================================================== -->
                        <!-- KOLOM TENGAH: Detail Produk & Informasi (4 Kolom Desktop) -->
                        <!-- ======================================================== -->
                        <div class="lg:col-span-4 flex flex-col space-y-4">
                            
                            <!-- Header Produk: Badge Kategori & Nama Produk -->
                            <div>
                                <!-- Badge Kategori Hijau Lembut -->
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-[#EBF5F0] text-[#1B5E3A] text-xs font-bold tracking-wide">
                                    <!-- Ikon Toko/Kategori -->
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                    <span x-text="selectedFood.category || 'Bakery'">Bakery</span>
                                </div>

                                <!-- Nama Produk Tebal -->
                                <h1 id="modal-food-title" 
                                    class="text-2xl sm:text-3xl font-extrabold text-stone-900 tracking-tight leading-tight mt-2" 
                                    x-text="selectedFood.title">
                                    Croissant Butter
                                </h1>

                                <!-- Info Toko / Merchant (Avatar, Verified, Rating, Jarak) -->
                                <div class="flex items-center flex-wrap gap-2 text-xs sm:text-sm text-stone-600 font-medium mt-2">
                                    <!-- Avatar Toko -->
                                    <div class="w-6 h-6 rounded-full overflow-hidden bg-amber-100 border border-stone-200 shrink-0">
                                        <img :src="selectedFood.merchantAvatar || 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=120&q=80'" 
                                             :alt="selectedFood.merchant" 
                                             class="w-full h-full object-cover">
                                    </div>
                                    
                                    <!-- Nama Toko -->
                                    <span class="font-bold text-stone-900" x-text="selectedFood.merchant || 'Sunny Bakery'">Sunny Bakery</span>
                                    
                                    <!-- Ikon Centang Hijau Terverifikasi -->
                                    <span class="inline-flex items-center text-[#1B5E3A]" title="Mitra Terverifikasi">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </span>

                                    <!-- Bintang Rating -->
                                    <div class="flex items-center gap-1 text-stone-700 ml-1">
                                        <svg class="w-4 h-4 text-[#F59E0B] fill-current" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        <span class="font-bold text-stone-900" x-text="selectedFood.rating || '4.8'">4.8</span>
                                        <span class="text-stone-400 font-normal" x-text="'(' + (selectedFood.reviewsCount || '120') + ')'">(120)</span>
                                    </div>

                                    <span class="text-stone-300">•</span>

                                    <!-- Jarak Lokasi -->
                                    <span class="text-stone-500 font-medium" x-text="selectedFood.distance || '1.2 km'">1.2 km</span>
                                </div>
                            </div>

                            <!-- Banner Informasi Kesegaran (Hijau Lembut Sesuai Mockup) -->
                            <div class="rounded-xl bg-[#EBF5F0]/90 border border-[#DCF2E4] p-3 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-[#1B5E3A]/10 text-[#1B5E3A] flex items-center justify-center shrink-0">
                                    <!-- Ikon Daun / Kesegaran -->
                                    <svg class="w-4 h-4 text-[#1B5E3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <div class="leading-tight">
                                    <p class="text-xs font-bold text-[#1B5E3A]">Makanan masih segar!</p>
                                    <p class="text-[11px] text-stone-600 mt-0.5" x-text="selectedFood.freshness || 'Dibuat hari ini pukul 07.00'">Dibuat hari ini pukul 07.00</p>
                                </div>
                            </div>

                            <!-- Bagian Harga & Penghematan -->
                            <div>
                                <div class="flex items-baseline gap-2.5 flex-wrap">
                                    <!-- Harga Diskon Besar -->
                                    <span class="text-2xl sm:text-3xl font-extrabold text-[#E67E22]" 
                                          x-text="formatRupiah(selectedFood.discountPrice)">
                                        Rp 10.500
                                    </span>
                                    <!-- Harga Normal Coret -->
                                    <span class="text-sm line-through text-stone-400 font-medium" 
                                          x-text="formatRupiah(selectedFood.originalPrice)">
                                        Rp 15.000
                                    </span>
                                    <!-- Badge Persentase -->
                                    <span class="px-2 py-0.5 rounded bg-[#FEF3C7] text-[#D97706] text-xs font-bold" 
                                          x-text="selectedFood.discount + '% OFF'">
                                        30% OFF
                                    </span>
                                </div>
                                <p class="text-xs font-bold text-[#1B5E3A] mt-1" 
                                   x-text="'Hemat ' + formatRupiah(selectedFood.originalPrice - selectedFood.discountPrice)">
                                    Hemat Rp 4.500
                                </p>
                            </div>

                            <!-- Deskripsi Singkat Produk -->
                            <p class="text-xs sm:text-sm text-stone-600 leading-relaxed" 
                               x-text="selectedFood.description || 'Croissant butter premium yang lembut di dalam dan renyah di luar. Pas dinikmati bersama kopi atau teh hangat.'">
                                Croissant butter premium yang lembut di dalam dan renyah di luar. Pas dinikmati bersama kopi atau teh hangat.
                            </p>

                            <!-- Grid Spesifikasi Informasi Detail (4 Baris Berikon) -->
                            <div class="space-y-2 py-1 text-xs">
                                
                                <!-- 1. Kategori -->
                                <div class="flex items-center justify-between py-1 border-b border-stone-100">
                                    <div class="flex items-center gap-2 text-stone-500">
                                        <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                        <span>Kategori</span>
                                    </div>
                                    <span class="font-bold text-stone-800" x-text="selectedFood.categorySpec || selectedFood.category || 'Roti & Kue'">Roti & Kue</span>
                                </div>

                                <!-- 2. Stok Tersedia -->
                                <div class="flex items-center justify-between py-1 border-b border-stone-100">
                                    <div class="flex items-center gap-2 text-stone-500">
                                        <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                        <span>Stok Tersedia</span>
                                    </div>
                                    <span class="font-bold text-stone-800" x-text="selectedFood.stock + ' ' + (selectedFood.unit || 'pcs')">12 pcs</span>
                                </div>

                                <!-- 3. Waktu Ambil -->
                                <div class="flex items-center justify-between py-1 border-b border-stone-100">
                                    <div class="flex items-center gap-2 text-stone-500">
                                        <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>Waktu Ambil</span>
                                    </div>
                                    <span class="font-bold text-stone-800" x-text="selectedFood.pickupTime || 'Hari ini, 18.00 - 20.00'">Hari ini, 18.00 - 20.00</span>
                                </div>

                                <!-- 4. Lokasi Pengambilan -->
                                <div class="flex items-start justify-between py-1">
                                    <div class="flex items-center gap-2 text-stone-500 shrink-0">
                                        <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span>Lokasi Pengambilan</span>
                                    </div>
                                    <span class="font-semibold text-stone-800 text-right ml-4 max-w-[210px] truncate" 
                                          :title="selectedFood.pickupLocation || 'Jl. Kemang Raya No. 12, Jakarta'"
                                          x-text="selectedFood.pickupLocation || 'Jl. Kemang Raya No. 12, Jakarta'">
                                        Jl. Kemang Raya No. 12, Jakarta
                                    </span>
                                </div>

                            </div>

                            <!-- Kotak Informasi Edukatif ("Kenapa harga lebih hemat?") -->
                            <div class="rounded-xl bg-[#FFFBEB] border border-[#FDE68A]/70 p-3 flex items-start gap-2.5">
                                <div class="w-5 h-5 rounded-full bg-[#F59E0B]/20 text-[#D97706] flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                </div>
                                <div class="text-[11px] leading-relaxed">
                                    <p class="font-bold text-stone-900">Kenapa harga lebih hemat?</p>
                                    <p class="text-stone-600 mt-0.5">
                                        Karena ini makanan surplus yang masih layak konsumsi dan perlu diselamatkan sebelum tutup.
                                    </p>
                                </div>
                            </div>

                        </div>


                        <!-- ======================================================== -->
                        <!-- KOLOM KANAN: Aksi Pemesanan / Checkout Card (3 Kolom)     -->
                        <!-- ======================================================== -->
                        <div class="lg:col-span-3 flex flex-col">
                            
                            <div class="border border-stone-200/90 rounded-2xl p-4 sm:p-5 bg-white shadow-xs flex flex-col justify-between space-y-4">
                                
                                <div>
                                    <!-- Judul Bagian Card -->
                                    <h3 class="text-sm font-bold text-stone-900 tracking-tight">Pesan Sekarang</h3>

                                    <!-- Kontrol Pilih Jumlah -->
                                    <div class="mt-3">
                                        <label class="block text-xs font-medium text-stone-500 mb-1.5">Pilih jumlah</label>
                                        
                                        <div class="flex items-center justify-between border border-stone-200 rounded-xl p-1 bg-stone-50/60">
                                            <!-- Tombol Minus (-) -->
                                            <button type="button" 
                                                    @click="if (orderQuantity > 1) orderQuantity--"
                                                    :disabled="orderQuantity <= 1"
                                                    class="w-8 h-8 rounded-lg flex items-center justify-center text-stone-600 hover:bg-white hover:text-stone-900 hover:shadow-xs active:scale-95 disabled:opacity-30 disabled:cursor-not-allowed transition duration-150 font-bold"
                                                    aria-label="Kurang satu porsi">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/>
                                                </svg>
                                            </button>

                                            <!-- Tampilan Angka Jumlah -->
                                            <span class="text-sm font-extrabold text-stone-900 w-8 text-center select-none" 
                                                  x-text="orderQuantity">1</span>

                                            <!-- Tombol Plus (+) -->
                                            <button type="button" 
                                                    @click="if (orderQuantity < (selectedFood.stock || 99)) orderQuantity++"
                                                    :disabled="orderQuantity >= (selectedFood.stock || 1)"
                                                    class="w-8 h-8 rounded-lg flex items-center justify-center text-stone-600 hover:bg-white hover:text-stone-900 hover:shadow-xs active:scale-95 disabled:opacity-30 disabled:cursor-not-allowed transition duration-150 font-bold"
                                                    aria-label="Tambah satu porsi">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Ringkasan Perhitungan Subtotal & Hemat -->
                                    <div class="space-y-1.5 mt-4 pt-3 border-t border-stone-100 text-xs">
                                        <div class="flex items-center justify-between">
                                            <span class="text-stone-500">Subtotal</span>
                                            <span class="font-bold text-stone-900 text-sm" 
                                                  x-text="formatRupiah(selectedFood.discountPrice * orderQuantity)">
                                                Rp 10.500
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-stone-500">Estimasi Hemat</span>
                                            <span class="font-bold text-[#1B5E3A]" 
                                                  x-text="formatRupiah((selectedFood.originalPrice - selectedFood.discountPrice) * orderQuantity)">
                                                Rp 4.500
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Kotak Countdown Timer Interaktif Sesuai Mockup -->
                                    <div class="mt-4 rounded-xl bg-[#F0FDF4] border border-[#DCF2E4] p-3 text-center">
                                        <div class="flex items-center justify-center gap-1.5 text-[11px] font-semibold text-stone-600">
                                            <svg class="w-3.5 h-3.5 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span>Sisa waktu untuk dipesan</span>
                                        </div>

                                        <!-- Jam Digital Countdown -->
                                        <div class="text-xl font-black text-stone-800 tracking-wider font-mono my-1" 
                                             x-text="modalTimerFormatted">
                                            02 : 45 : 30
                                        </div>

                                        <p class="text-[10px] text-stone-500 font-medium">Sebelum slot waktunya habis</p>
                                    </div>
                                </div>

                                <!-- Tombol Aksi Utama: Reserve Sekarang -->
                                <div>
                                    <button type="button" 
                                            @click="submitReservation()"
                                            :disabled="selectedFood.stock <= 0"
                                            class="w-full py-3.5 px-4 rounded-xl bg-[#1B5E3A] hover:bg-[#14472C] active:scale-[0.98] text-white font-bold text-sm shadow-md shadow-[#1B5E3A]/20 flex items-center justify-center gap-2 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <!-- Ikon Tas Belanja -->
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                        </svg>
                                        <span>Reserve Sekarang</span>
                                    </button>

                                    <!-- Catatan Pembayaran di Bawah Tombol -->
                                    <p class="text-[11px] text-stone-500 text-center mt-2.5 flex items-center justify-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        <span>Pembayaran dilakukan saat pengambilan</span>
                                    </p>
                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- ======================================================== -->
                    <!-- BAGIAN BAWAH: 4 PILAR INFORMASI & VALUE PROPOSITION      -->
                    <!-- ======================================================== -->
                    <div class="border-t border-stone-200/80 mt-6 pt-5">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
                            
                            <!-- Pilar 1: Selamatkan Makanan -->
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center shrink-0 border border-[#DCF2E4]">
                                    <!-- Ikon Daun / Sprout -->
                                    <svg class="w-4 h-4 text-[#1B5E3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <div class="leading-tight">
                                    <h4 class="text-xs font-bold text-stone-900">Selamatkan Makanan</h4>
                                    <p class="text-[11px] text-stone-500 mt-0.5">Bantu kurangi food waste</p>
                                </div>
                            </div>

                            <!-- Pilar 2: Harga Hemat -->
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0 border border-sky-100">
                                    <!-- Ikon Tag Harga -->
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                                <div class="leading-tight">
                                    <h4 class="text-xs font-bold text-stone-900">Harga Hemat</h4>
                                    <p class="text-[11px] text-stone-500 mt-0.5">Dapatkan harga miring</p>
                                </div>
                            </div>

                            <!-- Pilar 3: Aman & Terpercaya -->
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100">
                                    <!-- Ikon Tameng Centang -->
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <div class="leading-tight">
                                    <h4 class="text-xs font-bold text-stone-900">Aman & Terpercaya</h4>
                                    <p class="text-[11px] text-stone-500 mt-0.5">Makanan layak konsumsi</p>
                                </div>
                            </div>

                            <!-- Pilar 4: Berdampak Nyata -->
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center shrink-0 border border-rose-100">
                                    <!-- Ikon Hati -->
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="leading-tight">
                                    <h4 class="text-xs font-bold text-stone-900">Berdampak Nyata</h4>
                                    <p class="text-[11px] text-stone-500 mt-0.5">Bersama jaga bumi kita</p>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </template>

        </div>
    </div>
</div>
