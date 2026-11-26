{{--
    Sidebar Business — dipakai SEMUA halaman bisnis agar identik.
    Props: active = dashboard | food-management | surplus-listings | production-records | reservations | customers | analytics
    Membutuhkan state Alpine `mobileSidebarOpen` di elemen induk (x-data).
--}}
@props(['active' => 'dashboard'])
@php
    $pendingCount = $sidebarPendingCount ?? 0;
    $totalSaved   = $sidebarTotalSaved ?? 0;

    $items = [
        ['key' => 'dashboard',          'label' => 'Dashboard',         'href' => route('business.dashboard'),               'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
        ['key' => 'food-management',    'label' => 'Master Makanan',    'href' => route('business.food-management'),         'icon' => 'M4 6h16M4 10h16M4 14h10M4 18h6'],
        ['key' => 'surplus-listings',   'label' => 'Makanan Surplus',   'href' => route('business.surplus-listings'),        'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
        ['key' => 'production-records', 'label' => 'Produksi Harian',   'href' => route('business.production-records'),      'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
        ['key' => 'reservations',       'label' => 'Reservasi',         'href' => route('business.reservations-management'), 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'badge' => $pendingCount],
        ['key' => 'customers',          'label' => 'Pelanggan',         'href' => route('business.analytics', ['tab' => 'pelanggan']), 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
        ['key' => 'analytics',          'label' => 'Analitik',          'href' => route('business.analytics'),               'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
    ];
@endphp

<!-- Overlay mobile -->
<div x-cloak x-show="mobileSidebarOpen" @click="mobileSidebarOpen = false"
     x-transition:enter="transition-opacity ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-40 bg-stone-900/50 lg:hidden"></div>

<aside :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
       class="fixed inset-y-0 left-0 z-50 w-64 shrink-0 bg-white border-r border-stone-200/80 flex flex-col transition-transform duration-300 ease-in-out lg:static lg:z-auto">

    <div class="px-5 pt-6 pb-4 flex flex-col h-full min-h-0 overflow-y-auto overscroll-contain custom-scrollbar">

        <div class="flex items-start justify-between mb-8 pl-1">
            <x-brand-logo :href="route('business.dashboard')" badge="Business" />
            <button type="button" @click="mobileSidebarOpen = false" aria-label="Tutup menu"
                    class="p-1.5 rounded-lg text-stone-400 hover:text-stone-700 hover:bg-stone-100 lg:hidden">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <nav class="space-y-1.5 flex-1 min-h-0" aria-label="Navigasi Bisnis">
            @foreach($items as $item)
                @php $isActive = $active === $item['key']; @endphp
                <a href="{{ $item['href'] }}" @if($isActive) aria-current="page" @endif
                   class="w-full flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 {{ $isActive ? 'bg-[#EBF5F0] text-[#1B5E3A] font-bold' : 'text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium' }}">
                    <span class="flex items-center gap-3 min-w-0">
                        <svg class="w-5 h-5 shrink-0 {{ $isActive ? 'text-[#1B5E3A]' : 'text-stone-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                        </svg>
                        <span class="truncate">{{ $item['label'] }}</span>
                    </span>
                    @if(!empty($item['badge']))
                        <span class="min-w-5 h-5 px-1.5 rounded-full bg-[#EA580C] text-white text-[11px] font-bold flex items-center justify-center shrink-0">{{ $item['badge'] }}</span>
                    @endif
                </a>
            @endforeach
        </nav>

        <div class="mt-6 p-4 rounded-2xl bg-[#FDFBF7] border border-stone-200/70 text-center">
            <h4 class="text-sm font-extrabold text-stone-900">Terima kasih!</h4>
            <p class="text-xs text-stone-500 mt-0.5">Kamu sudah membantu</p>
            <div class="text-2xl font-black text-[#1B5E3A] my-0.5 tracking-tight">{{ number_format($totalSaved, 0, ',', '.') }}</div>
            <p class="text-[11px] text-stone-500 leading-snug">porsi makanan terselamatkan dari food waste</p>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-red-600 hover:bg-red-50 transition">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Keluar
            </button>
        </form>
    </div>
</aside>
