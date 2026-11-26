<div x-cloak x-show="selectedFood" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-stone-900/60 backdrop-blur-sm" @click="closeDetail()"></div>
    <div class="min-h-full flex items-center justify-center p-4">
        <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden" @click.stop>
            <button type="button" @click="closeDetail()" class="absolute top-4 right-4 z-10 w-9 h-9 rounded-full bg-white/90 border border-stone-200 text-stone-600 hover:text-stone-900">✕</button>

            <div class="grid grid-cols-1 sm:grid-cols-2">
                <div class="relative min-h-64 sm:min-h-full bg-[#EBF5F0] flex items-center justify-center text-8xl text-[#1B5E3A] overflow-hidden">
                    <img x-show="selectedFood && selectedFood.image" x-cloak :src="selectedFood ? selectedFood.image : ''" :alt="selectedFood ? selectedFood.title : ''" class="absolute inset-0 w-full h-full object-cover">
                    <span x-text="selectedFood ? selectedFood.categoryIcon : '🍽️'"></span>
                </div>
                <div class="p-6 sm:p-7 space-y-4">
                    <div>
                        <p class="text-xs font-bold text-[#1B5E3A]" x-text="selectedFood ? selectedFood.merchant : ''"></p>
                        <p class="text-[11px] text-stone-500 mt-1" x-text="selectedFood ? selectedFood.category : ''"></p>
                        <h2 class="text-xl font-black text-stone-900 mt-2" x-text="selectedFood ? selectedFood.title : ''"></h2>
                    </div>

                    <p class="text-sm text-stone-600 leading-relaxed" x-text="selectedFood && selectedFood.description ? selectedFood.description : 'Informasi tambahan makanan belum tersedia.'"></p>

                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl font-black text-[#E67E22]" x-text="selectedFood ? formatRupiah(selectedFood.discountPrice) : ''"></span>
                        <span class="text-sm text-stone-400 line-through" x-text="selectedFood ? formatRupiah(selectedFood.originalPrice) : ''"></span>
                        <span class="text-xs font-extrabold text-[#1B5E3A] bg-[#EBF5F0] px-2 py-1 rounded-lg" x-text="selectedFood ? selectedFood.discount + '% OFF' : ''"></span>
                    </div>

                    <div class="space-y-2 text-xs text-stone-600">
                        <p><strong class="text-stone-800">Jadwal pickup:</strong> <span x-text="selectedFood ? selectedFood.pickupTime : ''"></span></p>
                        <p><strong class="text-stone-800">Alamat mitra:</strong> <span x-text="selectedFood ? selectedFood.pickupLocation : ''"></span></p>
                        <p><strong class="text-stone-800">Porsi tersedia:</strong> <span x-text="selectedFood ? selectedFood.stock : 0"></span></p>
                    </div>

                    <div class="flex items-center justify-between gap-3 pt-2">
                        <div class="flex items-center border border-stone-200 rounded-xl overflow-hidden">
                            <button type="button" @click="orderQuantity = Math.max(1, orderQuantity - 1)" class="w-9 h-10 text-stone-600 hover:bg-stone-50">−</button>
                            <input type="number" x-model.number="orderQuantity" min="1" :max="selectedFood ? selectedFood.stock : 1" class="w-12 h-10 text-center text-sm font-bold border-x border-stone-200 focus:outline-none">
                            <button type="button" @click="orderQuantity = Math.min(selectedFood ? selectedFood.stock : 1, orderQuantity + 1)" class="w-9 h-10 text-stone-600 hover:bg-stone-50">+</button>
                        </div>
                        <button type="button" @click="submitReservation()" :disabled="!selectedFood || selectedFood.stock < 1 || orderQuantity < 1 || orderQuantity > selectedFood.stock"
                                class="flex-1 py-3 rounded-xl bg-[#1B5E3A] hover:bg-[#14472C] disabled:bg-stone-300 text-white text-sm font-extrabold transition">
                            Reservasi Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
