@extends('layouts.main')

@section('title', 'Notifikasi - What\'s Left')

@section('content')
<main class="min-h-screen bg-[#FAF6F0] py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6">
        <h1 class="text-3xl font-black text-stone-900">Notifikasi</h1>
        <p class="text-sm text-stone-600 mt-2">Informasi pembaruan akun dan reservasi.</p>

        @if($notifications->isEmpty())
        <div class="mt-8 bg-white rounded-2xl border border-stone-200 p-10 text-center">
            <div class="text-5xl mb-3">🔔</div>
            <h2 class="font-extrabold text-stone-900">Belum ada notifikasi</h2>
            <p class="text-sm text-stone-500 mt-2 max-w-md mx-auto">Pembaruan status reservasi kamu akan muncul di sini.</p>
            <a href="{{ route('consumer.my-reservations') }}" class="inline-flex mt-5 px-5 py-2.5 rounded-xl bg-[#1B5E3A] text-white text-xs font-bold">Lihat Reservasi</a>
        </div>
        @else
        <ul class="mt-8 space-y-3">
            @foreach($notifications as $item)
            <li class="bg-white rounded-2xl border {{ $item['unread'] ? 'border-[#1B5E3A]/40' : 'border-stone-200' }} p-4 flex gap-3">
                <div class="text-2xl">🔔</div>
                <div class="flex-1">
                    <p class="text-sm font-extrabold text-stone-900">{{ $item['title'] }}</p>
                    <p class="text-sm text-stone-600 mt-0.5">{{ $item['body'] }}</p>
                    <p class="text-[11px] text-stone-400 mt-1">{{ $item['time'] }}</p>
                </div>
            </li>
            @endforeach
        </ul>
        @endif
    </div>
</main>
@endsection
