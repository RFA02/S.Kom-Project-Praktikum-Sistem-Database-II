@extends('layouts.main')

@section('title', "Reservasi Makanan: Croissant Butter - What's Left")

@section('content')
<div x-data="{
    // State Modal Reservasi Terbuka Secara Default
    reservationModalOpen: true,
    reservationStep: 1,

    // Data Item Makanan Terpilih Sesuai Screenshot Lampiran
    resItem: {
        id: 1,
        title: 'Croissant Butter',
        merchant: 'Sunny Bakery',
        merchantVerified: true,
        merchantAvatar: 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=120&q=80',
        rating: 4.8,
        reviewsCount: 120,
        distance: 1.2,
        distanceText: '1.2 km',
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
        description: 'Croissant butter premium yang lembut di dalam dan renyah di luar. Pas dinikmati bersama kopi atau teh hangat.'
    },

    // Form Controls
    resQuantity: 1,
    selectedSlot: 1, // 1: Hari ini 18.00-19.00 | 2: Hari ini 19.00-20.00 | 3: Besok 18.00-19.00 | 4: Besok 19.00-20.00
    selectedPayment: 'gopay', // 'gopay' | 'ovo' | 'dana' | 'va'
    reservationNotes: '',

    // Helper Rupiah
    formatRupiah(number) {
        if (!number) return 'Rp 0';
        return 'Rp ' + Number(number).toLocaleString('id-ID');
    },

    closeReservationModal() {
        window.location.href = '{{ url('/?view=explore') }}';
    },

    finishReservation() {
        this.reservationStep = 4;
        this.showToast('Reservasi berhasil dikonfirmasi! Kode booking telah diterbitkan.', 'success');
    }
}"
class="relative min-h-screen bg-[#FAF6F0]">

    <!-- Background Simulasi Halaman Jelajahi Makanan (Memberikan Efek Modal Overlay Seperti Screenshot) -->
    <div class="filter blur-[3px] opacity-40 pointer-events-none select-none max-w-7xl mx-auto px-4 py-8">
        <div class="h-10 w-64 bg-stone-300 rounded-xl mb-4"></div>
        <div class="h-6 w-96 bg-stone-200 rounded-lg mb-8"></div>
        <div class="grid grid-cols-5 gap-4">
            <template x-for="i in 10" :key="i">
                <div class="h-64 bg-stone-200/80 rounded-2xl"></div>
            </template>
        </div>
    </div>

    <!-- Panggil Komponen Modal Reservasi -->
    @include('components.consumer.reservation-modal')

</div>
@endsection
