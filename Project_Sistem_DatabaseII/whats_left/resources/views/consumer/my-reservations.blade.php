@extends('layouts.main')

@section('title', 'Reservasi Saya - What\'s Left')

@section('content')
<div x-data="{
    activeTab: 'Semua',
    tabs: ['Semua', 'Menunggu Konfirmasi', 'Akan Diambil', 'Selesai', 'Dibatalkan', 'Tidak Hadir'],
    detailModalOpen: false,
    selectedReservation: null,
    activeReservations: @js($activeReservations),
    historyReservations: @js($historyReservations),
    cancellingId: null,

    get filteredActive() {
        return this.activeReservations.filter(item => this.activeTab === 'Semua' || item.statusKey === this.activeTab);
    },

    get filteredHistory() {
        return this.historyReservations.filter(item => this.activeTab === 'Semua' || item.statusKey === this.activeTab);
    },

    get totalVisibleCount() {
        return this.filteredActive.length + this.filteredHistory.length;
    },

    formatRupiah(value) {
        return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
    },

    openDetail(item) {
        this.selectedReservation = item;
        this.detailModalOpen = true;
    },

    closeDetail() {
        this.detailModalOpen = false;
        this.selectedReservation = null;
    },

    statusClass(status) {
        const classes = {
            'Menunggu Konfirmasi': 'bg-amber-100 text-amber-800',
            'Akan Diambil': 'bg-emerald-100 text-emerald-800',
            'Selesai': 'bg-stone-100 text-stone-700',
            'Dibatalkan': 'bg-red-100 text-red-700',
            'Tidak Hadir': 'bg-purple-100 text-purple-700'
        };
        return classes[status] || 'bg-stone-100 text-stone-700';
    },

    async copyBookingCode(code) {
        try {
            await navigator.clipboard.writeText(code);
            this.$dispatch('show-toast', { msg: 'ID reservasi berhasil disalin.', type: 'success' });
        } catch (error) {
            this.$dispatch('show-toast', { msg: 'ID reservasi: ' + code, type: 'info' });
        }
    },

    async cancelReservation(id) {
        if (this.cancellingId) return;
        if (!window.confirm('Batalkan reservasi ini? Stok akan dikembalikan jika pembatalan masih diizinkan.')) return;

        this.cancellingId = id;

        try {
            const response = await fetch(`/consumer/reservations/${id}/cancel`, {
                method: 'PUT',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify({ reservation_status: 'cancelled' })
            });
            const result = await response.json();

            if (!response.ok) {
                this.$dispatch('show-toast', { msg: result.message || 'Reservasi gagal dibatalkan.', type: 'error' });
                return;
            }

            this.$dispatch('show-toast', { msg: result.message || 'Reservasi berhasil dibatalkan.', type: 'success' });
            window.location.reload();
        } catch (error) {
            this.$dispatch('show-toast', { msg: 'Tidak dapat terhubung ke server. Silakan coba kembali.', type: 'error' });
        } finally {
            this.cancellingId = null;
        }
    }
}" class="min-h-screen bg-[#FAF6F0] pb-16">
    <section class="bg-[#FAF6F0] border-b border-stone-200/70 py-9">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-[#1B5E3A]">Akun Consumer</p>
                <h1 class="text-3xl sm:text-4xl font-black text-stone-900 mt-1">Reservasi Saya</h1>
                <p class="text-sm text-stone-600 mt-2">Pantau status pesanan dan informasi pengambilan makanan surplus.</p>
            </div>
            <a href="{{ route('consumer.explore') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-[#1B5E3A] hover:bg-[#14472C] text-white text-sm font-bold">
                Jelajahi Makanan
            </a>
        </div>
    </section>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-7">
            <section class="lg:col-span-8 space-y-5">
                <div class="flex gap-2 overflow-x-auto pb-2">
                    <template x-for="tab in tabs" :key="tab">
                        <button type="button" @click="activeTab = tab"
                                :class="activeTab === tab ? 'bg-[#1B5E3A] text-white border-[#1B5E3A]' : 'bg-white text-stone-600 border-stone-200 hover:border-[#1B5E3A]'"
                                class="shrink-0 px-4 py-2.5 rounded-xl border text-xs font-bold transition" x-text="tab"></button>
                    </template>
                </div>

                <div x-show="filteredActive.length > 0" class="space-y-3">
                    <h2 class="text-sm font-extrabold text-stone-900">Reservasi Aktif <span class="text-stone-400 font-semibold" x-text="'(' + filteredActive.length + ')'"></span></h2>
                    <template x-for="item in filteredActive" :key="item.id">
                        <article class="bg-white rounded-2xl border border-stone-200 shadow-sm p-4 sm:p-5 flex flex-col sm:flex-row gap-4">
                            <div class="relative w-20 h-20 rounded-xl bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center text-4xl shrink-0 overflow-hidden">
                                <img x-show="item.image" x-cloak :src="item.image" :alt="item.title" loading="lazy" class="absolute inset-0 w-full h-full object-cover">
                                <span x-text="item.categoryIcon"></span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold" :class="statusClass(item.status)" x-text="item.status"></span>
                                    <span class="text-[11px] text-stone-400" x-text="item.bookingCode"></span>
                                </div>
                                <h3 class="font-extrabold text-stone-900" x-text="item.title"></h3>
                                <p class="text-xs text-stone-500 mt-0.5" x-text="item.merchant"></p>
                                <p class="text-xs text-stone-600 mt-2" x-text="item.dateLabel + ' • ' + item.pickupTime"></p>
                                <p class="text-xs font-bold text-[#1B5E3A] mt-2" x-text="formatRupiah(item.discountPrice * item.qty) + ' • ' + item.qty + ' porsi'"></p>
                            </div>
                            <div class="flex sm:flex-col gap-2 sm:justify-center">
                                <button type="button" @click="openDetail(item)" class="flex-1 px-4 py-2.5 rounded-xl border border-stone-200 text-stone-700 text-xs font-bold hover:bg-stone-50">Detail</button>
                                <button type="button" x-show="item.statusCode === 'pending'" @click="cancelReservation(item.id)" class="flex-1 px-4 py-2.5 rounded-xl bg-red-50 text-red-600 text-xs font-bold hover:bg-red-100">Batalkan</button>
                            </div>
                        </article>
                    </template>
                </div>

                <div x-show="filteredHistory.length > 0" class="space-y-3">
                    <h2 class="text-sm font-extrabold text-stone-900">Riwayat Reservasi <span class="text-stone-400 font-semibold" x-text="'(' + filteredHistory.length + ')'"></span></h2>
                    <template x-for="item in filteredHistory" :key="item.id">
                        <article class="bg-white rounded-2xl border border-stone-200 shadow-sm p-4 sm:p-5 flex flex-col sm:flex-row gap-4">
                            <div class="relative w-20 h-20 rounded-xl bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center text-4xl shrink-0 overflow-hidden">
                                <img x-show="item.image" x-cloak :src="item.image" :alt="item.title" loading="lazy" class="absolute inset-0 w-full h-full object-cover">
                                <span x-text="item.categoryIcon"></span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold" :class="statusClass(item.status)" x-text="item.status"></span>
                                    <span class="text-[11px] text-stone-400" x-text="item.bookingCode"></span>
                                </div>
                                <h3 class="font-extrabold text-stone-900" x-text="item.title"></h3>
                                <p class="text-xs text-stone-500 mt-0.5" x-text="item.merchant"></p>
                                <p class="text-xs text-stone-600 mt-2" x-text="item.dateLabel + ' • ' + item.pickupTime"></p>
                                <p class="text-xs font-bold text-[#1B5E3A] mt-2" x-text="formatRupiah(item.discountPrice * item.qty) + ' • ' + item.qty + ' porsi'"></p>
                            </div>
                            <div class="flex sm:flex-col justify-center">
                                <button type="button" @click="openDetail(item)" class="px-4 py-2.5 rounded-xl border border-stone-200 text-stone-700 text-xs font-bold hover:bg-stone-50">Detail</button>
                            </div>
                        </article>
                    </template>
                </div>

                <div x-show="totalVisibleCount === 0" class="bg-white rounded-2xl border border-stone-200 p-10 text-center">
                    <div class="text-5xl mb-3">📋</div>
                    <h3 class="font-extrabold text-stone-900">Belum ada reservasi pada kategori ini</h3>
                    <p class="text-sm text-stone-500 mt-2">Pilih kategori lain atau jelajahi makanan surplus yang tersedia.</p>
                    <a href="{{ route('consumer.explore') }}" class="inline-flex mt-5 px-5 py-2.5 rounded-xl bg-[#1B5E3A] text-white text-xs font-bold">Jelajahi Makanan</a>
                </div>
            </section>

            <aside class="lg:col-span-4 space-y-4">
                <div class="bg-white rounded-2xl border border-stone-200 shadow-sm p-5">
                    <h2 class="text-sm font-extrabold text-stone-900">Ringkasan Aktivitas</h2>
                    <div class="mt-4 space-y-3">
                        <div class="flex justify-between text-xs">
                            <span class="text-stone-500">Reservasi aktif</span>
                            <strong class="text-stone-900">{{ $activeReservations->count() }}</strong>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-stone-500">Total reservasi</span>
                            <strong class="text-stone-900">{{ $activeReservations->count() + $historyReservations->count() }}</strong>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-stone-500">Porsi diselamatkan</span>
                            <strong class="text-[#1B5E3A]">{{ number_format($savedPortions, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>

                <div class="bg-[#EBF5F0] rounded-2xl border border-[#DCF2E4] p-5">
                    <h2 class="text-sm font-extrabold text-[#1B5E3A]">Tips Pengambilan</h2>
                    <ul class="mt-3 space-y-2 text-xs text-stone-700">
                        <li>• Periksa tanggal dan rentang waktu pickup pada detail reservasi.</li>
                        <li>• Tunjukkan ID reservasi kepada mitra saat tiba.</li>
                        <li>• Pembatalan hanya tersedia saat status masih menunggu konfirmasi.</li>
                    </ul>
                </div>
            </aside>
        </div>
    </main>

    <div x-cloak x-show="detailModalOpen" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-stone-900/60 backdrop-blur-sm" @click="closeDetail()"></div>
        <div class="min-h-full flex items-center justify-center p-4">
            <div class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl p-6 space-y-5" @click.stop>
                <button type="button" @click="closeDetail()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-stone-100 text-stone-500">✕</button>
                <template x-if="selectedReservation">
                    <div class="space-y-5">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-[#1B5E3A]">Detail Reservasi</p>
                            <h2 class="text-xl font-black text-stone-900 mt-1" x-text="selectedReservation.title"></h2>
                            <p class="text-xs text-stone-500 mt-1" x-text="selectedReservation.merchant"></p>
                        </div>

                        <div class="p-4 rounded-2xl bg-[#FAF7F2] border border-dashed border-[#1B5E3A]/30 text-center">
                            <p class="text-[10px] font-bold text-stone-500 uppercase">ID Reservasi</p>
                            <div class="flex justify-center items-center gap-2 mt-1">
                                <strong class="text-xl font-black tracking-widest text-[#1B5E3A]" x-text="selectedReservation.bookingCode"></strong>
                                <button type="button" @click="copyBookingCode(selectedReservation.bookingCode)" class="text-xs text-stone-500 hover:text-[#1B5E3A]">Salin</button>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="relative w-16 h-16 rounded-xl bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center text-3xl overflow-hidden shrink-0">
                                <img x-show="selectedReservation.image" x-cloak :src="selectedReservation.image" :alt="selectedReservation.title" class="absolute inset-0 w-full h-full object-cover">
                                <span x-text="selectedReservation.categoryIcon"></span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <span class="inline-flex px-2 py-1 rounded-full text-[10px] font-bold" :class="statusClass(selectedReservation.status)" x-text="selectedReservation.status"></span>
                                <p class="text-xs text-stone-500 mt-2" x-text="'Dibuat ' + selectedReservation.createdAt"></p>
                            </div>
                        </div>

                        <div class="space-y-3 text-xs">
                            <div>
                                <strong class="text-stone-900">Waktu pickup</strong>
                                <p class="text-stone-600 mt-1" x-text="selectedReservation.dateLabel + ' • ' + selectedReservation.pickupTime"></p>
                            </div>
                            <div>
                                <strong class="text-stone-900">Alamat mitra</strong>
                                <p class="text-stone-600 mt-1" x-text="selectedReservation.pickupAddress"></p>
                            </div>
                            <div>
                                <strong class="text-stone-900">Kontak mitra</strong>
                                <p class="text-stone-600 mt-1" x-text="selectedReservation.merchantPhone || 'Nomor kontak belum tersedia'"></p>
                            </div>
                        </div>

                        <div class="p-4 rounded-2xl bg-stone-50 border border-stone-200 space-y-2 text-xs">
                            <div class="flex justify-between"><span class="text-stone-500">Harga per porsi</span><strong x-text="formatRupiah(selectedReservation.discountPrice)"></strong></div>
                            <div class="flex justify-between"><span class="text-stone-500">Jumlah porsi</span><strong x-text="selectedReservation.qty"></strong></div>
                            <div class="border-t border-stone-200 pt-2 flex justify-between text-sm"><strong>Total reservasi</strong><strong class="text-[#E67E22]" x-text="formatRupiah(selectedReservation.discountPrice * selectedReservation.qty)"></strong></div>
                        </div>

                        <div class="flex gap-2">
                            <button type="button" x-show="selectedReservation.statusCode === 'pending'"
                                    @click="cancelReservation(selectedReservation.id)"
                                    class="flex-1 py-3 rounded-xl bg-red-50 text-red-600 text-xs font-bold hover:bg-red-100">Batalkan Reservasi</button>
                            <button type="button" @click="closeDetail()" class="flex-1 py-3 rounded-xl bg-[#1B5E3A] text-white text-xs font-bold hover:bg-[#14472C]">Tutup</button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
@endsection
