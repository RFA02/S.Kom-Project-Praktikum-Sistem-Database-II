<div x-cloak x-show="reservationModalOpen" class="fixed inset-0 z-[60] overflow-y-auto" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-stone-900/60 backdrop-blur-sm" @click="closeReservationModal()"></div>
    <div class="min-h-full flex items-center justify-center p-4">
        <div class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl p-6 sm:p-7" @click.stop>
            <button type="button" @click="closeReservationModal()" class="absolute top-4 right-4 w-9 h-9 rounded-full bg-stone-100 text-stone-500 hover:text-stone-900">✕</button>

            <template x-if="reservationStep === 1">
                <div class="space-y-5">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-[#1B5E3A]">Konfirmasi Reservasi</p>
                        <h2 class="text-xl font-black text-stone-900 mt-1">Pastikan pesananmu</h2>
                        <p class="text-xs text-stone-500 mt-1">Reservasi akan disimpan ke database dan mengurangi stok listing.</p>
                    </div>

                    <div class="flex gap-3 p-3 rounded-2xl bg-[#FAF7F2] border border-stone-200">
                        <div class="relative w-16 h-16 rounded-xl bg-[#EBF5F0] flex items-center justify-center text-3xl overflow-hidden shrink-0">
                            <img x-show="resItem.image" x-cloak :src="resItem.image" :alt="resItem.title" class="absolute inset-0 w-full h-full object-cover">
                            <span x-text="resItem.categoryIcon"></span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="font-extrabold text-sm text-stone-900" x-text="resItem.title"></p>
                            <p class="text-xs text-stone-500" x-text="resItem.merchant"></p>
                            <p class="text-xs text-stone-500 mt-1" x-text="resItem.pickupTime"></p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-stone-800">Jumlah porsi</p>
                            <p class="text-[11px] text-stone-500" x-text="resItem.stock + ' porsi tersedia'"></p>
                        </div>
                        <div class="flex items-center border border-stone-200 rounded-xl overflow-hidden">
                            <button type="button" @click="resQuantity = Math.max(1, Number(resQuantity) - 1)" class="w-9 h-9 text-stone-600 hover:bg-stone-50">−</button>
                            <input type="number" x-model.number="resQuantity" min="1" :max="resItem.stock" class="w-12 h-9 text-center text-sm font-bold border-x border-stone-200 focus:outline-none">
                            <button type="button" @click="resQuantity = Math.min(resItem.stock, Number(resQuantity) + 1)" class="w-9 h-9 text-stone-600 hover:bg-stone-50">+</button>
                        </div>
                    </div>

                    <div class="border-t border-dashed border-stone-200 pt-4 flex items-center justify-between">
                        <span class="text-sm font-bold text-stone-700">Total reservasi</span>
                        <span class="text-xl font-black text-[#1B5E3A]" x-text="formatRupiah(resItem.discountPrice * Number(resQuantity))"></span>
                    </div>

                    <div x-show="reservationError" x-cloak class="p-3 rounded-xl bg-red-50 border border-red-200 text-xs font-semibold text-red-600" x-text="reservationError"></div>

                    <button type="button" @click="finishReservation()" :disabled="reservationLoading || Number(resQuantity) < 1 || Number(resQuantity) > Number(resItem.stock)"
                            class="w-full py-3.5 rounded-xl bg-[#1B5E3A] hover:bg-[#14472C] disabled:bg-stone-300 text-white text-sm font-extrabold transition">
                        <span x-show="!reservationLoading">Konfirmasi Reservasi</span>
                        <span x-show="reservationLoading">Menyimpan...</span>
                    </button>
                </div>
            </template>

            <template x-if="reservationStep === 2">
                <div class="text-center py-5 space-y-4">
                    <div class="w-16 h-16 rounded-full bg-[#DCF2E4] text-[#1B5E3A] flex items-center justify-center text-3xl mx-auto">✓</div>
                    <div>
                        <h2 class="text-xl font-black text-stone-900">Reservasi berhasil dibuat</h2>
                        <p class="text-sm text-stone-500 mt-2">Simpan ID reservasi ini dan tunjukkan kepada mitra saat pengambilan.</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-[#FAF7F2] border border-dashed border-[#1B5E3A]/30">
                        <p class="text-[11px] font-bold text-stone-500">ID RESERVASI</p>
                        <p class="text-xl font-black tracking-widest text-[#1B5E3A] mt-1"
                           x-text="createdReservation ? 'RES-' + String(createdReservation.id).padStart(6, '0') : '—'"></p>
                    </div>
                    <p class="text-xs text-stone-500">Jadwal dan alamat pickup dapat dilihat kembali pada halaman Reservasi Saya.</p>
                    <a href="{{ route('consumer.my-reservations') }}" class="block w-full py-3 rounded-xl bg-[#1B5E3A] hover:bg-[#14472C] text-white text-sm font-extrabold">Lihat Reservasi Saya</a>
                    <button type="button" @click="closeReservationModal()" class="text-xs font-bold text-stone-500 hover:text-stone-800">Tutup</button>
                </div>
            </template>
        </div>
    </div>
</div>
