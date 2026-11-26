@extends('layouts.main')

@section('title', $food['title'] . ' - What\'s Left')

@section('content')
<div x-data="{
    quantity: 1,
    saving: false,
    error: '',
    formatRupiah(value) { return 'Rp ' + Number(value || 0).toLocaleString('id-ID'); },
    async reserve() {
        @guest
            window.location.href = @js(route('login'));
            return;
        @else
            @if(auth()->user()->role !== 'customer')
                this.error = 'Reservasi hanya dapat dibuat menggunakan akun Consumer.';
                return;
            @endif
        @endguest
        this.error = '';
        this.saving = true;
        try {
            const response = await fetch(@js(route('consumer.reservations.store')), {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify({ surplus_listing_id: @js($food['id']), portion_quantity: Number(this.quantity) })
            });
            const result = await response.json();
            if (!response.ok) {
                this.error = result.message || 'Reservasi gagal dibuat.';
                return;
            }
            window.location.href = @js(route('consumer.my-reservations'));
        } catch (error) {
            this.error = 'Tidak dapat terhubung ke server. Silakan coba kembali.';
        } finally {
            this.saving = false;
        }
    }
}" class="min-h-screen bg-[#FAF6F0] py-10">
    <main class="max-w-5xl mx-auto px-4 sm:px-6">
        <a href="{{ route('consumer.explore') }}" class="text-xs font-bold text-[#1B5E3A] hover:underline">← Kembali ke Jelajahi</a>

        <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-8 bg-white rounded-3xl border border-stone-200 shadow-sm p-5 sm:p-8">
            @if(!empty($food['image']))
                <img src="{{ $food['image'] }}" alt="{{ $food['title'] }}" class="w-full min-h-72 max-h-96 rounded-2xl object-cover">
            @else
                <div class="min-h-72 rounded-2xl bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center text-8xl">{{ $food['categoryIcon'] }}</div>
            @endif
            <div class="space-y-5">
                <div>
                    <p class="text-xs font-bold text-[#1B5E3A]">{{ $food['merchant'] }} • {{ $food['category'] }}</p>
                    <h1 class="text-3xl font-black text-stone-900 mt-2">{{ $food['title'] }}</h1>
                    <p class="text-sm text-stone-600 mt-3">{{ $food['description'] ?: 'Informasi tambahan makanan belum tersedia.' }}</p>
                </div>

                <div class="flex items-baseline gap-3">
                    <span class="text-2xl font-black text-[#E67E22]">Rp {{ number_format($food['discountPrice'], 0, ',', '.') }}</span>
                    <span class="text-sm text-stone-400 line-through">Rp {{ number_format($food['originalPrice'], 0, ',', '.') }}</span>
                    <span class="px-2 py-1 rounded-lg bg-[#FEF3C7] text-[#92400E] text-xs font-extrabold">{{ number_format($food['discount'], 0) }}% OFF</span>
                </div>

                <div class="space-y-3 text-sm">
                    <div><strong class="text-stone-900">Jadwal pickup</strong><p class="text-stone-600 mt-1">{{ $food['pickupTime'] }}</p></div>
                    <div><strong class="text-stone-900">Alamat mitra</strong><p class="text-stone-600 mt-1">{{ $food['pickupLocation'] }}</p></div>
                    <div><strong class="text-stone-900">Porsi tersedia</strong><p class="text-stone-600 mt-1">{{ $food['stock'] }}</p></div>
                </div>

                <div>
                    <label for="quantity" class="block text-xs font-bold text-stone-700 mb-1.5">Jumlah porsi</label>
                    <input id="quantity" type="number" x-model.number="quantity" min="1" max="{{ $food['stock'] }}"
                           class="w-28 px-3 py-2.5 rounded-xl border border-stone-200 text-sm font-bold focus:outline-none focus:border-[#1B5E3A]">
                </div>

                <div x-show="error" x-cloak class="p-3 rounded-xl bg-red-50 border border-red-200 text-xs font-semibold text-red-600" x-text="error"></div>

                <button type="button" @click="reserve()" :disabled="saving || quantity < 1 || quantity > {{ $food['stock'] }}"
                        class="w-full py-3.5 rounded-xl bg-[#1B5E3A] hover:bg-[#14472C] disabled:bg-stone-300 text-white text-sm font-extrabold">
                    <span x-show="!saving">Buat Reservasi • <span x-text="formatRupiah({{ $food['discountPrice'] }} * quantity)"></span></span>
                    <span x-show="saving">Menyimpan...</span>
                </button>
            </div>
        </div>
    </main>
</div>
@endsection
