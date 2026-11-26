{{-- Topbar Business — dipakai SEMUA halaman bisnis. Butuh state Alpine `mobileSidebarOpen` dari induk. --}}
@php
    $biz = $topbarBusiness ?? null;
    $unread = $topbarUnreadCount ?? 0;
@endphp
<header class="h-16 bg-white border-b border-stone-200/80 px-4 sm:px-6 lg:px-8 flex items-center justify-between shrink-0 z-30">
    <div class="flex items-center gap-3">
        <button type="button" @click="mobileSidebarOpen = true" aria-label="Buka menu navigasi"
                class="p-2 rounded-xl text-stone-600 hover:bg-stone-100 hover:text-stone-900 transition lg:hidden">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </div>

    <div class="flex items-center gap-2 sm:gap-3">
    <a href="{{ route('business.notifications') }}" aria-label="Notifikasi{{ $unread ? ' ('.$unread.' belum dibaca)' : '' }}"
       class="relative p-2 rounded-xl text-stone-500 hover:bg-stone-100 hover:text-stone-900 transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0"/></svg>
        @if($unread > 0)
            <span class="absolute top-1 right-1 min-w-[16px] h-4 px-1 rounded-full bg-[#EA580C] text-white text-[10px] font-bold flex items-center justify-center">{{ $unread > 9 ? '9+' : $unread }}</span>
        @endif
    </a>

    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
        <button type="button" @click="open = !open" aria-label="Menu akun"
                class="flex items-center gap-2.5 p-1 pr-2 rounded-xl hover:bg-stone-50 transition">
            <span class="w-9 h-9 rounded-full bg-[#EBF5F0] border border-[#DCF2E4] flex items-center justify-center shrink-0 text-sm font-extrabold text-[#1B5E3A]">
                {{ mb_strtoupper(mb_substr($biz->business_name ?? 'B', 0, 1)) }}
            </span>
            <span class="hidden sm:flex flex-col text-left leading-tight">
                <span class="text-[13px] font-bold text-[#1F2937]">{{ $biz->business_name ?? 'Bisnis' }}</span>
                <span class="text-[11px] text-[#6B7280] font-medium">Business</span>
            </span>
            <svg class="w-4 h-4 text-stone-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>

        <div x-cloak x-show="open"
             x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
             class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-stone-200/80 py-1.5 z-50">
            <div class="px-4 py-2.5 border-b border-stone-100">
                <p class="text-xs font-bold text-stone-800">{{ $biz->business_name ?? '' }}</p>
                <p class="text-[11px] text-stone-500">{{ auth()->user()->email ?? '' }}</p>
            </div>
            <a href="{{ route('business.dashboard') }}" class="block px-4 py-2 text-xs font-semibold text-stone-700 hover:bg-stone-50 hover:text-[#1B5E3A]">Dashboard Bisnis</a>
            <a href="{{ route('home') }}" class="block px-4 py-2 text-xs font-semibold text-stone-700 hover:bg-stone-50 hover:text-[#1B5E3A]">Lihat Halaman Konsumen</a>
            <div class="my-1 border-t border-stone-100"></div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full text-left px-4 py-2 text-xs font-semibold text-red-600 hover:bg-red-50">Keluar</button>
            </form>
        </div>
    </div>
    </div>
</header>

@if($biz && $biz->verification_status !== 'verified')
    @php $rejected = $biz->verification_status === 'rejected'; @endphp
    <div role="status" class="shrink-0 px-4 sm:px-6 lg:px-8 py-2.5 text-xs font-semibold flex items-center gap-2 border-b {{ $rejected ? 'bg-red-50 text-red-700 border-red-100' : 'bg-[#FEF3C7] text-amber-800 border-amber-100' }}">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
        @if($rejected)
            Verifikasi bisnis ditolak. Hubungi admin What's Left untuk meninjau ulang data bisnismu.
        @else
            Bisnismu sedang menunggu verifikasi admin. @if(config('whatsleft.require_verified_business')) Kamu belum dapat membuat listing surplus sampai terverifikasi. @endif
        @endif
    </div>
@endif
