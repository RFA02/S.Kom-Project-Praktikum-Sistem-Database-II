@extends('layouts.business')

@section('title', 'Notifikasi - What\'s Left')

@section('content')
<div class="flex h-screen overflow-hidden bg-[#FAF7F2]" x-data="{ mobileSidebarOpen: false }">

    <x-business.sidebar active="notifications" />

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <x-business.topbar />

        <main class="flex-1 overflow-y-auto custom-scrollbar p-4 sm:p-6 lg:p-7">
            <div class="max-w-3xl">
                <div class="flex items-center gap-2 text-xs text-stone-500 mb-2">
                    <span>Business</span><span>/</span>
                    <span class="text-[#1B5E3A] font-semibold">Notifikasi</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-stone-900">Notifikasi</h1>
                <p class="text-sm text-stone-500 mt-1">Reservasi baru dan pembatalan dari pelanggan.</p>

                @if($notifications->isEmpty())
                    <div class="mt-8 bg-white rounded-2xl border border-stone-200 p-10 text-center">
                        <div class="w-14 h-14 mx-auto rounded-2xl bg-[#EBF5F0] flex items-center justify-center text-[#1B5E3A]">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0"/></svg>
                        </div>
                        <h2 class="mt-3 font-extrabold text-stone-900">Belum ada notifikasi</h2>
                        <p class="text-sm text-stone-500 mt-1">Reservasi baru akan muncul di sini.</p>
                        <a href="{{ route('business.reservations-management') }}" class="inline-flex mt-5 px-5 py-2.5 rounded-xl bg-[#1B5E3A] text-white text-xs font-bold hover:bg-[#14472C]">Lihat Reservasi</a>
                    </div>
                @else
                    <ul class="mt-6 space-y-3">
                        @foreach($notifications as $item)
                            <li class="bg-white rounded-2xl border {{ $item['unread'] ? 'border-[#1B5E3A]/40' : 'border-stone-200' }} p-4 flex gap-3">
                                <span class="mt-1.5 w-2.5 h-2.5 rounded-full shrink-0 {{ $item['unread'] ? 'bg-[#EA580C]' : 'bg-stone-200' }}"></span>
                                <div class="flex-1 min-w-0">
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
    </div>
</div>
@endsection
