{{-- 
    Komponen Modal Pop-up: Reservasi Makanan (Reservation Consumers) - What's Left
    Sesuai persis dengan rujukan visual & UI/UX screenshot attachment.
    Teknologi: Blade + Tailwind CSS v4 + Alpine.js
--}}
<div x-cloak 
     x-show="reservationModalOpen" 
     class="fixed inset-0 z-50 overflow-y-auto" 
     aria-labelledby="modal-reservation-title" 
     role="dialog" 
     aria-modal="true"
     @keydown.escape.window="closeReservationModal()">

    <!-- 1. Backdrop Blur Overlay Gelap Transparan Sesuai Mockup -->
    <div x-show="reservationModalOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="closeReservationModal()"
         class="fixed inset-0 bg-stone-900/65 backdrop-blur-xs transition-opacity"></div>

    <!-- 2. Kontainer Posisi Tengah Dialog Modal -->
    <div class="flex min-h-full items-center justify-center p-3 sm:p-5 md:p-6 text-center">
        
        <div x-show="reservationModalOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all w-full max-w-6xl border border-stone-200/90 p-5 sm:p-7 md:p-8">

            <!-- ========================================================================= -->
            <!-- HEADER MODAL: JUDUL + STEPPER 4-LANGKAH + TOMBOL CLOSE (X) PERSIS MOCKUP  -->
            <!-- ========================================================================= -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between pb-6 mb-6 border-b border-stone-200/80 gap-4">
                
                <!-- Sisi Kiri: Judul & Subteks dengan Ikon Daun/Resep -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl border border-[#1B5E3A]/30 text-[#1B5E3A] flex items-center justify-center bg-[#EBF5F0]/70 shrink-0 shadow-xs">
                        <!-- Ikon Resep / Daun Surplus -->
                        <svg class="w-5 h-5 text-[#1B5E3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <div>
                        <h2 id="modal-reservation-title" class="text-lg sm:text-xl font-black text-stone-900 tracking-tight leading-tight">
                            Reservasi Makanan
                        </h2>
                        <p class="text-xs text-stone-500 font-medium mt-0.5">
                            Lengkapi detail reservasi dan pembelianmu
                        </p>
                    </div>
                </div>

                <!-- Bagian Tengah: Stepper 4 Langkah Persis Mockup -->
                <div class="flex items-center gap-1.5 sm:gap-2.5 overflow-x-auto no-scrollbar py-1">
                    
                    <!-- Langkah 1: Detail Pesanan -->
                    <div class="flex items-center gap-2 cursor-pointer" @click="reservationStep = 1">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold transition-colors"
                             :class="reservationStep >= 1 ? 'bg-[#1B5E3A] text-white shadow-xs' : 'border border-stone-300 text-stone-400 bg-white'">
                            1
                        </div>
                        <span class="text-xs font-bold whitespace-nowrap"
                              :class="reservationStep === 1 ? 'text-[#1B5E3A]' : (reservationStep > 1 ? 'text-stone-700' : 'text-stone-400')">
                            Detail Pesanan
                        </span>
                    </div>

                    <span class="text-stone-300 text-xs font-bold tracking-widest">---</span>

                    <!-- Langkah 2: Lokasi & Waktu -->
                    <div class="flex items-center gap-2 cursor-pointer" @click="if(reservationStep >= 2) reservationStep = 2">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold transition-colors"
                             :class="reservationStep >= 2 ? 'bg-[#1B5E3A] text-white shadow-xs' : 'border border-stone-300 text-stone-400 bg-white'">
                            2
                        </div>
                        <span class="text-xs font-semibold whitespace-nowrap"
                              :class="reservationStep === 2 ? 'text-[#1B5E3A] font-bold' : (reservationStep > 2 ? 'text-stone-700 font-bold' : 'text-stone-400')">
                            Lokasi & Waktu
                        </span>
                    </div>

                    <span class="text-stone-300 text-xs font-bold tracking-widest">---</span>

                    <!-- Langkah 3: Pembayaran -->
                    <div class="flex items-center gap-2 cursor-pointer" @click="if(reservationStep >= 3) reservationStep = 3">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold transition-colors"
                             :class="reservationStep >= 3 ? 'bg-[#1B5E3A] text-white shadow-xs' : 'border border-stone-300 text-stone-400 bg-white'">
                            3
                        </div>
                        <span class="text-xs font-semibold whitespace-nowrap"
                              :class="reservationStep === 3 ? 'text-[#1B5E3A] font-bold' : (reservationStep > 3 ? 'text-stone-700 font-bold' : 'text-stone-400')">
                            Pembayaran
                        </span>
                    </div>

                    <span class="text-stone-300 text-xs font-bold tracking-widest">---</span>

                    <!-- Langkah 4: Konfirmasi -->
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold transition-colors"
                             :class="reservationStep >= 4 ? 'bg-[#1B5E3A] text-white shadow-xs' : 'border border-stone-300 text-stone-400 bg-white'">
                            4
                        </div>
                        <span class="text-xs font-semibold whitespace-nowrap"
                              :class="reservationStep === 4 ? 'text-[#1B5E3A] font-bold' : 'text-stone-400'">
                            Konfirmasi
                        </span>
                    </div>

                </div>

                <!-- Sisi Kanan: Tombol Tutup (X) Bundar Persis Mockup -->
                <button type="button" 
                        @click="closeReservationModal()"
                        class="w-9 h-9 rounded-full border border-stone-200 bg-white hover:bg-stone-100 text-stone-400 hover:text-stone-800 flex items-center justify-center transition-colors shrink-0 self-end lg:self-center shadow-2xs"
                        aria-label="Tutup modal reservasi">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

            </div>


            <!-- ========================================================================= -->
            <!-- STEP 1: DETAIL PESANAN (TAMPILAN UTAMA SESUAI SCREENSHOT LAMPIRAN)        -->
            <!-- ========================================================================= -->
            <div x-show="reservationStep === 1">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
                    
                    <!-- ================================================================= -->
                    <!-- KOLOM KIRI (7 Kolom): DETAIL ITEM & PENGATURAN RESERVASI          -->
                    <!-- ================================================================= -->
                    <div class="lg:col-span-7 space-y-6">
                        
                        <!-- 1. Box Detail Makanan (Card Putih Halus Sesuai Mockup) -->
                        <div class="border border-stone-200/90 rounded-2xl p-4 sm:p-5 bg-white shadow-2xs">
                            <div class="flex flex-col sm:flex-row gap-4 sm:gap-5 items-start">
                                
                                <!-- Foto Produk (Aspect 4:3) dengan Badge Diskon "30% OFF" -->
                                <div class="relative w-full sm:w-48 aspect-[4/3] rounded-xl overflow-hidden bg-stone-100 shrink-0">
                                    <img :src="resItem.image" 
                                         :alt="resItem.title" 
                                         class="w-full h-full object-cover">
                                    <div class="absolute top-2.5 left-2.5 px-2.5 py-1 rounded-md bg-[#F59E0B] text-stone-900 font-black text-[11px] shadow-sm tracking-tight">
                                        <span x-text="resItem.discount + '% OFF'">30% OFF</span>
                                    </div>
                                </div>

                                <!-- Informasi Produk & Merchant -->
                                <div class="space-y-2 flex-1">
                                    
                                    <!-- Badge Kategori Hijau -->
                                    <div class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md bg-[#EBF5F0] text-[#1B5E3A] text-xs font-bold">
                                        <span class="text-[10px]">🏷️</span>
                                        <span x-text="resItem.category || 'Bakery'">Bakery</span>
                                    </div>

                                    <!-- Nama Makanan Tebal -->
                                    <h3 class="text-xl sm:text-2xl font-black text-stone-900 leading-tight" x-text="resItem.title">
                                        Croissant Butter
                                    </h3>

                                    <!-- Toko, Verified, Rating & Jarak -->
                                    <div class="flex items-center flex-wrap gap-2 text-xs text-stone-600 font-medium">
                                        <div class="w-5 h-5 rounded-full overflow-hidden bg-amber-100 shrink-0 border border-stone-200">
                                            <img :src="resItem.merchantAvatar" :alt="resItem.merchant" class="w-full h-full object-cover">
                                        </div>
                                        <span class="font-bold text-stone-800" x-text="resItem.merchant">Sunny Bakery</span>
                                        <span class="text-[#1B5E3A]" title="Mitra Terverifikasi">
                                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        </span>
                                        <span class="text-stone-300">•</span>
                                        <div class="flex items-center gap-1 text-stone-700">
                                            <span class="text-[#F59E0B]">★</span>
                                            <span class="font-bold text-stone-900" x-text="resItem.rating">4.8</span>
                                            <span class="text-stone-400" x-text="'(' + resItem.reviewsCount + ')'">(120)</span>
                                        </div>
                                        <span class="text-stone-300">•</span>
                                        <span class="text-stone-500 font-medium" x-text="resItem.distanceText || (resItem.distance + ' km')">1.2 km</span>
                                    </div>

                                    <!-- Banner Kesegaran (Hijau Lembut Sesuai Mockup) -->
                                    <div class="rounded-xl bg-[#EBF5F0]/85 border border-[#DCF2E4] px-3 py-2 flex items-center gap-2.5">
                                        <span class="text-[#1B5E3A] text-sm">🌱</span>
                                        <div class="leading-tight text-xs">
                                            <span class="font-bold text-[#1B5E3A]">Makanan masih segar!</span>
                                            <span class="text-stone-600 ml-1" x-text="resItem.freshness">Dibuat hari ini pukul 07.00</span>
                                        </div>
                                    </div>

                                    <!-- Harga Diskon, Normal Coret & Badge Persen -->
                                    <div class="flex items-baseline gap-2 pt-1 flex-wrap">
                                        <span class="text-2xl sm:text-3xl font-black text-[#E67E22]" x-text="formatRupiah(resItem.discountPrice)">
                                            Rp 10.500
                                        </span>
                                        <span class="text-xs sm:text-sm line-through text-stone-400 font-medium" x-text="formatRupiah(resItem.originalPrice)">
                                            Rp 15.000
                                        </span>
                                        <span class="px-2 py-0.5 rounded bg-[#FEF3C7] text-[#D97706] text-xs font-bold" x-text="resItem.discount + '% OFF'">
                                            30% OFF
                                        </span>
                                    </div>

                                    <!-- Deskripsi Singkat -->
                                    <p class="text-xs text-stone-600 leading-relaxed pt-1" x-text="resItem.description">
                                        Croissant butter premium yang lembut di dalam dan renyah di luar. Pas dinikmati bersama kopi atau teh hangat.
                                    </p>

                                </div>

                            </div>

                            <!-- Baris Metadata Spesifikasi (Kategori, Stok, Waktu Ambil, Lokasi Pengambilan) Persis Mockup -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-2 gap-x-6 border-t border-stone-100 mt-4 pt-4 text-xs">
                                <div class="flex items-center justify-between py-1 border-b sm:border-b-0 border-stone-50">
                                    <span class="text-stone-500 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                        Kategori
                                    </span>
                                    <span class="font-bold text-stone-800" x-text="resItem.categorySpec || 'Roti & Kue'">Roti & Kue</span>
                                </div>

                                <div class="flex items-center justify-between py-1 border-b sm:border-b-0 border-stone-50">
                                    <span class="text-stone-500 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                        Stok Tersedia
                                    </span>
                                    <span class="font-bold text-stone-800" x-text="resItem.stock + ' ' + (resItem.unit || 'pcs')">12 pcs</span>
                                </div>

                                <div class="flex items-center justify-between py-1 border-b sm:border-b-0 border-stone-50">
                                    <span class="text-stone-500 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Waktu Ambil
                                    </span>
                                    <span class="font-bold text-stone-800" x-text="resItem.pickupTime || 'Hari ini, 18.00 - 20.00'">Hari ini, 18.00 - 20.00</span>
                                </div>

                                <div class="flex items-center justify-between py-1">
                                    <span class="text-stone-500 flex items-center gap-1.5 shrink-0">
                                        <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                        Lokasi Pengambilan
                                    </span>
                                    <span class="font-bold text-stone-800 text-right truncate max-w-[190px] ml-2" :title="resItem.pickupLocation" x-text="resItem.pickupLocation">
                                        Jl. Kemang Raya No. 12, Jakarta
                                    </span>
                                </div>
                            </div>

                        </div>

                        <!-- 2. Bagian Kontrol Bawah: Pilih Jumlah & Pilih Waktu Ambil (Persis Mockup) -->
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-5 items-start">
                            
                            <!-- Sub-Kolom Kiri (4 Kolom): Pilih Jumlah -->
                            <div class="md:col-span-4 space-y-3">
                                <div>
                                    <h4 class="text-xs font-extrabold text-stone-900">Pilih Jumlah</h4>
                                    <p class="text-[11px] text-stone-500 mt-0.5">
                                        Stok tersedia: <span class="font-bold text-[#1B5E3A]" x-text="resItem.stock + ' ' + (resItem.unit || 'pcs')">12 pcs</span>
                                    </p>
                                </div>

                                <!-- Stepper Counter [-] [1] [+] -->
                                <div class="flex items-center justify-between border border-stone-200/90 rounded-xl p-1.5 bg-stone-50/60 shadow-2xs">
                                    <button type="button" 
                                            @click="if (resQuantity > 1) resQuantity--"
                                            :disabled="resQuantity <= 1"
                                            class="w-8 h-8 rounded-lg bg-white border border-stone-200 text-stone-700 hover:bg-stone-100 disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center font-bold text-sm shadow-xs transition">
                                        −
                                    </button>
                                    <span class="text-sm font-extrabold text-stone-900 px-3" x-text="resQuantity">1</span>
                                    <button type="button" 
                                            @click="if (resQuantity < resItem.stock) resQuantity++"
                                            :disabled="resQuantity >= resItem.stock"
                                            class="w-8 h-8 rounded-lg bg-white border border-stone-200 text-stone-700 hover:bg-stone-100 disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center font-bold text-sm shadow-xs transition">
                                        +
                                    </button>
                                </div>

                                <!-- Box Edukatif: "Kenapa harga lebih hemat?" Persis Mockup -->
                                <div class="rounded-xl bg-[#FFFBEB] border border-[#FDE68A]/70 p-3 flex items-start gap-2.5">
                                    <div class="w-5 h-5 rounded-full bg-[#F59E0B]/20 text-[#D97706] flex items-center justify-center shrink-0 mt-0.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                                    </div>
                                    <div class="text-[11px] leading-relaxed">
                                        <p class="font-bold text-stone-900">Kenapa harga lebih hemat?</p>
                                        <p class="text-stone-600 mt-0.5">
                                            Makanan ini merupakan surplus yang masih layak konsumsi perlu diselamatkan sebelum tutup.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Sub-Kolom Kanan (8 Kolom): Pilih Waktu Ambil (4 Slot Cards Sesuai Mockup) -->
                            <div class="md:col-span-8 space-y-3">
                                <div>
                                    <h4 class="text-xs font-extrabold text-stone-900">Pilih Waktu Ambil</h4>
                                    <p class="text-[11px] text-stone-500 mt-0.5">Pilih waktu sesuai yang tersedia</p>
                                </div>

                                <!-- Grid 4 Slot Cards Horizontal Sesuai Mockup -->
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                                    
                                    <!-- Slot 1: Hari ini 18.00 - 19.00 [Tersedia] (Default Terpilih Hijau) -->
                                    <div @click="selectedSlot = 1"
                                         class="p-2.5 rounded-xl cursor-pointer transition-all duration-200 text-center flex flex-col justify-between"
                                         :class="selectedSlot === 1 
                                            ? 'border-2 border-[#1B5E3A] bg-white shadow-xs' 
                                            : 'border border-stone-200 bg-white hover:border-stone-300'">
                                        <div class="w-4 h-4 rounded-full mx-auto mb-1 flex items-center justify-center"
                                             :class="selectedSlot === 1 ? 'border-2 border-[#1B5E3A]' : 'border border-stone-300'">
                                            <div x-show="selectedSlot === 1" class="w-2 h-2 rounded-full bg-[#1B5E3A]"></div>
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-bold text-stone-800">Hari ini</p>
                                            <p class="text-[11px] font-black text-stone-900 leading-tight">18.00 - 19.00</p>
                                        </div>
                                        <span class="text-[10px] font-bold text-[#1B5E3A] mt-1.5">Tersedia</span>
                                    </div>

                                    <!-- Slot 2: Hari ini 19.00 - 20.00 [Tersedia] -->
                                    <div @click="selectedSlot = 2"
                                         class="p-2.5 rounded-xl cursor-pointer transition-all duration-200 text-center flex flex-col justify-between"
                                         :class="selectedSlot === 2 
                                            ? 'border-2 border-[#1B5E3A] bg-white shadow-xs' 
                                            : 'border border-stone-200 bg-white hover:border-stone-300'">
                                        <div class="w-4 h-4 rounded-full mx-auto mb-1 flex items-center justify-center"
                                             :class="selectedSlot === 2 ? 'border-2 border-[#1B5E3A]' : 'border border-stone-300'">
                                            <div x-show="selectedSlot === 2" class="w-2 h-2 rounded-full bg-[#1B5E3A]"></div>
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-bold text-stone-800">Hari ini</p>
                                            <p class="text-[11px] font-black text-stone-900 leading-tight">19.00 - 20.00</p>
                                        </div>
                                        <span class="text-[10px] font-bold text-[#1B5E3A] mt-1.5">Tersedia</span>
                                    </div>

                                    <!-- Slot 3: Besok 18.00 - 19.00 [Tersedia] -->
                                    <div @click="selectedSlot = 3"
                                         class="p-2.5 rounded-xl cursor-pointer transition-all duration-200 text-center flex flex-col justify-between"
                                         :class="selectedSlot === 3 
                                            ? 'border-2 border-[#1B5E3A] bg-white shadow-xs' 
                                            : 'border border-stone-200 bg-white hover:border-stone-300'">
                                        <div class="w-4 h-4 rounded-full mx-auto mb-1 flex items-center justify-center"
                                             :class="selectedSlot === 3 ? 'border-2 border-[#1B5E3A]' : 'border border-stone-300'">
                                            <div x-show="selectedSlot === 3" class="w-2 h-2 rounded-full bg-[#1B5E3A]"></div>
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-bold text-stone-800">Besok</p>
                                            <p class="text-[11px] font-black text-stone-900 leading-tight">18.00 - 19.00</p>
                                        </div>
                                        <span class="text-[10px] font-bold text-[#1B5E3A] mt-1.5">Tersedia</span>
                                    </div>

                                    <!-- Slot 4: Besok 19.00 - 20.00 [Terbatas Oranye] -->
                                    <div @click="selectedSlot = 4"
                                         class="p-2.5 rounded-xl cursor-pointer transition-all duration-200 text-center flex flex-col justify-between"
                                         :class="selectedSlot === 4 
                                            ? 'border-2 border-[#1B5E3A] bg-white shadow-xs' 
                                            : 'border border-stone-200 bg-white hover:border-stone-300'">
                                        <div class="w-4 h-4 rounded-full mx-auto mb-1 flex items-center justify-center"
                                             :class="selectedSlot === 4 ? 'border-2 border-[#1B5E3A]' : 'border border-stone-300'">
                                            <div x-show="selectedSlot === 4" class="w-2 h-2 rounded-full bg-[#1B5E3A]"></div>
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-bold text-stone-800">Besok</p>
                                            <p class="text-[11px] font-black text-stone-900 leading-tight">19.00 - 20.00</p>
                                        </div>
                                        <span class="text-[10px] font-bold text-[#E67E22] mt-1.5">Terbatas</span>
                                    </div>

                                </div>

                                <!-- Peringatan Ketepatan Waktu Pengambilan Sesuai Mockup -->
                                <div class="rounded-xl bg-[#EBF5F0]/70 border border-[#DCF2E4] p-3 flex items-start gap-2.5 text-xs text-stone-700">
                                    <svg class="w-4 h-4 text-[#1B5E3A] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-[11px] leading-relaxed">
                                        Harap datang tepat waktu. Pesanan akan dibatalkan jika tidak diambil dalam waktu yang dipilih.
                                    </p>
                                </div>
                            </div>

                        </div>

                    </div>


                    <!-- ================================================================= -->
                    <!-- KOLOM KANAN (5 Kolom): RINGKASAN PESANAN & METODE PEMBAYARAN      -->
                    <!-- ================================================================= -->
                    <div class="lg:col-span-5 space-y-4">
                        
                        <!-- Box Ringkasan Pesanan Persis Mockup -->
                        <div class="border border-stone-200/90 rounded-2xl p-4 sm:p-5 bg-white shadow-2xs space-y-4">
                            <h3 class="text-sm font-extrabold text-stone-900">Ringkasan Pesanan</h3>

                            <!-- Baris Item Mini (Thumbnail + Nama + Qty + Harga) -->
                            <div class="flex items-center justify-between gap-3 pb-3 border-b border-stone-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-10 rounded-lg overflow-hidden bg-stone-100 shrink-0">
                                        <img :src="resItem.image" :alt="resItem.title" class="w-full h-full object-cover">
                                    </div>
                                    <div class="text-xs">
                                        <p class="font-bold text-stone-900" x-text="resItem.title">Croissant Butter</p>
                                        <p class="text-[11px] text-stone-500" x-text="resItem.merchant">Sunny Bakery</p>
                                        <p class="text-[10px] font-bold text-stone-400 mt-0.5" x-text="'Qty: ' + resQuantity">Qty: 1</p>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-stone-900" x-text="formatRupiah(resItem.discountPrice * resQuantity)">
                                    Rp 10.500
                                </span>
                            </div>

                            <!-- Rincian Biaya -->
                            <div class="space-y-2 text-xs">
                                <div class="flex justify-between text-stone-600">
                                    <span>Subtotal</span>
                                    <span class="font-semibold text-stone-900" x-text="formatRupiah(resItem.discountPrice * resQuantity)">Rp 10.500</span>
                                </div>

                                <div class="flex justify-between text-stone-600">
                                    <span class="text-[#1B5E3A] font-bold">Estimasi Hemat</span>
                                    <span class="font-bold text-[#1B5E3A]" x-text="'- ' + formatRupiah((resItem.originalPrice - resItem.discountPrice) * resQuantity)">- Rp 4.500</span>
                                </div>

                                <div class="flex justify-between text-stone-600 items-center">
                                    <span class="flex items-center gap-1">
                                        Biaya Layanan
                                        <span class="text-stone-400 cursor-help" title="Biaya pemeliharaan platform food rescue">ⓘ</span>
                                    </span>
                                    <span class="font-semibold text-stone-900">Rp 1.000</span>
                                </div>
                            </div>

                            <!-- Total Pembayaran Persis Mockup -->
                            <div class="border-t border-stone-100 pt-3 flex items-baseline justify-between">
                                <span class="text-xs font-bold text-stone-900">Total Pembayaran</span>
                                <span class="text-xl font-black text-[#E67E22]" x-text="formatRupiah((resItem.discountPrice * resQuantity) + 1000)">
                                    Rp 11.500
                                </span>
                            </div>
                        </div>

                        <!-- Banner Dampak Makanan (Food Waste Saved Message) Persis Mockup -->
                        <div class="rounded-2xl bg-[#EBF5F0]/85 border border-[#DCF2E4] p-3 sm:p-3.5 flex items-start gap-2.5">
                            <span class="text-lg">🌱</span>
                            <div class="text-[11px] leading-relaxed text-stone-700">
                                <span>Dengan pesanan ini, kamu ikut menyelamatkan </span>
                                <span class="font-black text-[#1B5E3A]" x-text="resQuantity + ' porsi makanan'">1 porsi makanan</span>
                                <span> dari food waste. Terima kasih! 💚</span>
                            </div>
                        </div>

                        <!-- Pilihan Metode Pembayaran (GoPay, OVO, DANA, Virtual Account) Persis Mockup -->
                        <div class="border border-stone-200/90 rounded-2xl p-4 bg-white shadow-2xs space-y-3">
                            <h4 class="text-xs font-extrabold text-stone-900">Metode Pembayaran</h4>

                            <div class="space-y-2">
                                
                                <!-- 1. GoPay (Terpilih Hijau) -->
                                <label @click="selectedPayment = 'gopay'"
                                       class="flex items-center justify-between p-3 rounded-xl cursor-pointer transition-all border"
                                       :class="selectedPayment === 'gopay' 
                                          ? 'border-[#1B5E3A] bg-[#EBF5F0]/30 shadow-2xs' 
                                          : 'border-stone-200 hover:bg-stone-50'">
                                    <div class="flex items-center gap-3">
                                        <div class="w-4 h-4 rounded-full flex items-center justify-center"
                                             :class="selectedPayment === 'gopay' ? 'border-2 border-[#1B5E3A]' : 'border border-stone-300'">
                                            <div x-show="selectedPayment === 'gopay'" class="w-2 h-2 rounded-full bg-[#1B5E3A]"></div>
                                        </div>
                                        <span class="text-xs font-bold text-stone-900">GoPay</span>
                                    </div>
                                    <span class="text-xs font-black text-sky-600 tracking-tight">gopay</span>
                                </label>

                                <!-- 2. OVO -->
                                <label @click="selectedPayment = 'ovo'"
                                       class="flex items-center justify-between p-3 rounded-xl cursor-pointer transition-all border"
                                       :class="selectedPayment === 'ovo' 
                                          ? 'border-[#1B5E3A] bg-[#EBF5F0]/30 shadow-2xs' 
                                          : 'border-stone-200 hover:bg-stone-50'">
                                    <div class="flex items-center gap-3">
                                        <div class="w-4 h-4 rounded-full flex items-center justify-center"
                                             :class="selectedPayment === 'ovo' ? 'border-2 border-[#1B5E3A]' : 'border border-stone-300'">
                                            <div x-show="selectedPayment === 'ovo'" class="w-2 h-2 rounded-full bg-[#1B5E3A]"></div>
                                        </div>
                                        <span class="text-xs font-bold text-stone-900">OVO</span>
                                    </div>
                                    <span class="text-xs font-black text-purple-700 tracking-tight">OVO</span>
                                </label>

                                <!-- 3. DANA -->
                                <label @click="selectedPayment = 'dana'"
                                       class="flex items-center justify-between p-3 rounded-xl cursor-pointer transition-all border"
                                       :class="selectedPayment === 'dana' 
                                          ? 'border-[#1B5E3A] bg-[#EBF5F0]/30 shadow-2xs' 
                                          : 'border-stone-200 hover:bg-stone-50'">
                                    <div class="flex items-center gap-3">
                                        <div class="w-4 h-4 rounded-full flex items-center justify-center"
                                             :class="selectedPayment === 'dana' ? 'border-2 border-[#1B5E3A]' : 'border border-stone-300'">
                                            <div x-show="selectedPayment === 'dana'" class="w-2 h-2 rounded-full bg-[#1B5E3A]"></div>
                                        </div>
                                        <span class="text-xs font-bold text-stone-900">DANA</span>
                                    </div>
                                    <span class="text-xs font-black text-blue-500 tracking-tight">DANA</span>
                                </label>

                                <!-- 4. Virtual Account -->
                                <label @click="selectedPayment = 'va'"
                                       class="flex items-center justify-between p-3 rounded-xl cursor-pointer transition-all border"
                                       :class="selectedPayment === 'va' 
                                          ? 'border-[#1B5E3A] bg-[#EBF5F0]/30 shadow-2xs' 
                                          : 'border-stone-200 hover:bg-stone-50'">
                                    <div class="flex items-center gap-3">
                                        <div class="w-4 h-4 rounded-full flex items-center justify-center"
                                             :class="selectedPayment === 'va' ? 'border-2 border-[#1B5E3A]' : 'border border-stone-300'">
                                            <div x-show="selectedPayment === 'va'" class="w-2 h-2 rounded-full bg-[#1B5E3A]"></div>
                                        </div>
                                        <span class="text-xs font-bold text-stone-900">Virtual Account</span>
                                    </div>
                                    <span class="text-stone-500 text-sm">🏦</span>
                                </label>

                            </div>
                        </div>

                        <!-- Tombol Utama: Lanjut ke Pembayaran & Keamanan Persis Mockup -->
                        <div>
                            <button type="button" 
                                    @click="reservationStep = 2"
                                    class="w-full py-3.5 px-4 rounded-xl bg-[#1B5E3A] hover:bg-[#14472C] active:scale-[0.99] text-white font-extrabold text-sm shadow-md shadow-[#1B5E3A]/25 transition-all duration-200 flex items-center justify-center gap-2">
                                <span>Lanjut ke Pembayaran</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>

                            <p class="text-[11px] text-stone-400 text-center flex items-center justify-center gap-1.5 mt-2">
                                <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                <span>Pembayaran aman & terenkripsi</span>
                            </p>
                        </div>

                    </div>

                </div>
            </div>


            <!-- ========================================================================= -->
            <!-- STEP 2: LOKASI & WAKTU AMBIL (PETA & PETUNJUK PENJEMPUTAN)                 -->
            <!-- ========================================================================= -->
            <div x-show="reservationStep === 2" x-cloak>
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                    
                    <div class="lg:col-span-8 space-y-5">
                        <div class="border border-stone-200/90 rounded-2xl p-5 bg-white space-y-4">
                            <h3 class="text-base font-extrabold text-stone-900">Titik Pengambilan Makanan</h3>
                            
                            <!-- Kartu Lokasi Merchant -->
                            <div class="p-4 rounded-xl bg-stone-50 border border-stone-200/80 flex items-start gap-3.5">
                                <div class="w-10 h-10 rounded-xl bg-[#1B5E3A] text-white flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <div class="text-xs space-y-1">
                                    <p class="font-extrabold text-stone-900 text-sm" x-text="resItem.merchant">Sunny Bakery</p>
                                    <p class="text-stone-600" x-text="resItem.pickupLocation">Jl. Kemang Raya No. 12, Jakarta Selatan</p>
                                    <p class="text-[#1B5E3A] font-bold">Jarak: <span x-text="resItem.distanceText || '1.2 km'">1.2 km dari posisimu</span></p>
                                </div>
                            </div>

                            <!-- Ilustrasi Peta Rute -->
                            <div class="relative h-48 rounded-xl bg-[#E8ECE9] overflow-hidden border border-stone-200/80 flex items-center justify-center">
                                <div class="absolute inset-0 opacity-40 bg-[radial-gradient(#1B5E3A_1px,transparent_1px)] [background-size:16px_16px]"></div>
                                <div class="relative z-10 text-center space-y-1">
                                    <span class="text-2xl">🗺️</span>
                                    <p class="text-xs font-bold text-stone-800">Petunjuk Arah Rute Google Maps</p>
                                    <a href="https://maps.google.com" target="_blank" class="inline-block px-3 py-1.5 rounded-lg bg-white border border-stone-300 text-[11px] font-bold text-[#1B5E3A] hover:bg-stone-50 shadow-xs">Buka di Aplikasi Peta ↗</a>
                                </div>
                            </div>

                            <!-- Input Catatan Tambahan -->
                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1.5">Catatan untuk Mitra / Penjual (Opsional)</label>
                                <textarea x-model="reservationNotes" rows="2" placeholder="Contoh: Saya bawa tas belanja sendiri, tolong siapkan di wadah kertas..." class="w-full text-xs rounded-xl border border-stone-200 p-3 focus:border-[#1B5E3A] focus:ring-[#1B5E3A]/20"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-4 space-y-4">
                        <div class="border border-stone-200/90 rounded-2xl p-5 bg-white space-y-3">
                            <h4 class="text-xs font-extrabold text-stone-900">Jadwal Pengambilan Terpilih</h4>
                            <div class="p-3 rounded-xl bg-[#EBF5F0] border border-[#DCF2E4] text-xs">
                                <p class="text-stone-500 text-[11px]">Waktu Pengambilan:</p>
                                <p class="font-extrabold text-[#1B5E3A] text-sm mt-0.5" x-text="selectedSlot === 1 ? 'Hari ini, 18.00 - 19.00' : (selectedSlot === 2 ? 'Hari ini, 19.00 - 20.00' : (selectedSlot === 3 ? 'Besok, 18.00 - 19.00' : 'Besok, 19.00 - 20.00'))">Hari ini, 18.00 - 19.00</p>
                            </div>
                            <div class="text-[11px] text-stone-500 space-y-1">
                                <p>• Tunjukkan kode booking saat sampai di kasir.</p>
                                <p>• Pengambilan tidak dapat diwakilkan tanpa bukti kode.</p>
                            </div>
                        </div>

                        <div class="flex gap-2.5">
                            <button type="button" @click="reservationStep = 1" class="w-1/3 py-3 rounded-xl border border-stone-300 text-stone-700 font-bold text-xs hover:bg-stone-50">Kembali</button>
                            <button type="button" @click="reservationStep = 3" class="w-2/3 py-3 rounded-xl bg-[#1B5E3A] hover:bg-[#14472C] text-white font-bold text-xs shadow-md">Lanjut Pembayaran</button>
                        </div>
                    </div>

                </div>
            </div>


            <!-- ========================================================================= -->
            <!-- STEP 3: PEMBAYARAN (SIMULASI QRIS / VA CODE & COUNTDOWN TIMER)            -->
            <!-- ========================================================================= -->
            <div x-show="reservationStep === 3" x-cloak>
                <div class="max-w-xl mx-auto space-y-5 text-center">
                    
                    <div class="border border-stone-200/90 rounded-2xl p-6 bg-white space-y-4">
                        <!-- Header Pembayaran -->
                        <div class="space-y-1">
                            <span class="px-3 py-1 rounded-full bg-[#FEF3C7] text-[#D97706] text-xs font-bold">Menunggu Pembayaran</span>
                            <h3 class="text-xl font-black text-stone-900 pt-2">Selesaikan Pembayaran</h3>
                            <p class="text-xs text-stone-500">Selesaikan sebelum batas waktu berakhir</p>
                        </div>

                        <!-- Timer Hitung Mundur -->
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-red-50 text-red-600 text-xs font-bold border border-red-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Batas Waktu: 14:59</span>
                        </div>

                        <!-- Nominal Tagihan -->
                        <div class="p-4 rounded-xl bg-stone-50 border border-stone-200">
                            <p class="text-xs text-stone-500">Total Tagihan:</p>
                            <p class="text-2xl font-black text-[#E67E22] mt-0.5" x-text="formatRupiah((resItem.discountPrice * resQuantity) + 1000)">Rp 11.500</p>
                            <p class="text-[11px] text-stone-400 mt-1" x-text="'Metode: ' + selectedPayment.toUpperCase()"></p>
                        </div>

                        <!-- Simulasi QRIS / Nomor Virtual Account -->
                        <div class="p-5 border-2 border-dashed border-stone-200 rounded-2xl space-y-3">
                            <template x-if="selectedPayment === 'gopay' || selectedPayment === 'dana' || selectedPayment === 'ovo'">
                                <div class="space-y-2">
                                    <div class="w-36 h-36 bg-stone-900 text-white rounded-xl mx-auto flex items-center justify-center p-3 shadow-sm">
                                        <!-- Simulasi QR Code -->
                                        <div class="grid grid-cols-4 gap-1 w-full h-full p-1 bg-white rounded-lg">
                                            <div class="bg-black rounded-xs"></div><div class="bg-black rounded-xs"></div><div class="bg-white"></div><div class="bg-black rounded-xs"></div>
                                            <div class="bg-white"></div><div class="bg-black rounded-xs"></div><div class="bg-black rounded-xs"></div><div class="bg-white"></div>
                                            <div class="bg-black rounded-xs"></div><div class="bg-white"></div><div class="bg-black rounded-xs"></div><div class="bg-black rounded-xs"></div>
                                            <div class="bg-black rounded-xs"></div><div class="bg-black rounded-xs"></div><div class="bg-white"></div><div class="bg-black rounded-xs"></div>
                                        </div>
                                    </div>
                                    <p class="text-xs font-bold text-stone-700">Pindai QRIS dengan aplikasi <span x-text="selectedPayment.toUpperCase()"></span></p>
                                </div>
                            </template>

                            <template x-if="selectedPayment === 'va'">
                                <div class="space-y-2 text-left">
                                    <p class="text-xs text-stone-500">Nomor Rekening Virtual Account (BCA):</p>
                                    <div class="flex items-center justify-between p-3 rounded-xl bg-stone-100 font-mono font-bold text-base text-stone-900">
                                        <span>8801 2452 0192 847</span>
                                        <button type="button" @click="showToast('Nomor VA disalin!')" class="text-xs font-sans text-[#1B5E3A] font-bold hover:underline">Salin</button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="flex gap-3 pt-2">
                            <button type="button" @click="reservationStep = 2" class="w-1/3 py-3 rounded-xl border border-stone-300 text-stone-700 font-bold text-xs hover:bg-stone-50">Kembali</button>
                            <button type="button" @click="finishReservation()" class="w-2/3 py-3 rounded-xl bg-[#1B5E3A] hover:bg-[#14472C] text-white font-extrabold text-xs shadow-md">
                                Simulasi Bayar Sekarang
                            </button>
                        </div>
                    </div>

                </div>
            </div>


            <!-- ========================================================================= -->
            <!-- STEP 4: KONFIRMASI (SUKSES DENGAN KODE BOOKING RESMI)                     -->
            <!-- ========================================================================= -->
            <div x-show="reservationStep === 4" x-cloak>
                <div class="max-w-lg mx-auto text-center space-y-6 py-4">
                    
                    <!-- Lencana Centang Sukses Hijau -->
                    <div class="w-16 h-16 rounded-full bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center mx-auto shadow-inner border border-[#DCF2E4]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>

                    <div class="space-y-1">
                        <h3 class="text-2xl font-black text-stone-900">Reservasi Berhasil Dibuat!</h3>
                        <p class="text-xs text-stone-600 max-w-sm mx-auto">
                            Tunjukkan kode booking ini ke kasir <span class="font-bold text-stone-900" x-text="resItem.merchant">Sunny Bakery</span> saat penjemputan makanan.
                        </p>
                    </div>

                    <!-- Kartu Kode Booking WL24520X -->
                    <div class="border border-stone-200 rounded-2xl p-5 bg-[#FAF6F0] space-y-3 shadow-xs">
                        <span class="text-[11px] font-bold text-stone-400 uppercase tracking-widest">KODE BOOKING ANDA</span>
                        <div class="text-3xl font-black tracking-widest text-[#1B5E3A] font-mono select-all">
                            WL24520X
                        </div>
                        <div class="pt-2 border-t border-stone-200/80 grid grid-cols-2 text-xs text-left gap-2">
                            <div>
                                <span class="text-stone-400 block text-[10px]">WAKTU AMBIL:</span>
                                <span class="font-bold text-stone-800">Hari ini, 18.00 - 19.00</span>
                            </div>
                            <div>
                                <span class="text-stone-400 block text-[10px]">TOTAL DIBAYAR:</span>
                                <span class="font-black text-[#E67E22]" x-text="formatRupiah((resItem.discountPrice * resQuantity) + 1000)">Rp 11.500</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi Menuju Halaman Terkait -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        <a href="{{ url('/?view=my-reservations') }}" 
                           class="w-full sm:w-1/2 py-3 rounded-xl bg-[#1B5E3A] hover:bg-[#14472C] text-white font-extrabold text-xs shadow-md transition flex items-center justify-center gap-1.5">
                            <span>Lihat di Reservasi Saya</span>
                            <span>→</span>
                        </a>
                        <button type="button" 
                                @click="closeReservationModal(); window.location.href = '{{ url('/?view=explore') }}'" 
                                class="w-full sm:w-1/2 py-3 rounded-xl border border-stone-300 text-stone-700 font-bold text-xs hover:bg-stone-50 transition">
                            Jelajahi Makanan Lain
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
