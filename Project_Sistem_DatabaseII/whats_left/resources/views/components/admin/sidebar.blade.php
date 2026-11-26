{{-- Sidebar Admin — gaya sama dengan sidebar Business. Butuh state Alpine `mobileSidebarOpen`. --}}
@props(['active' => 'dashboard'])
@php
    $pending = $adminPendingBusinesses ?? 0;
    $items = [
        ['key' => 'dashboard', 'label' => 'Ringkasan', 'href' => route('admin.dashboard'), 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'badge' => 0],
        ['key' => 'pending', 'label' => 'Verifikasi Mitra', 'href' => route('admin.dashboard', ['status' => 'pending']), 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'badge' => $pending],
    ];
@endphp

<div x-cloak x-show="mobileSidebarOpen" @click="mobileSidebarOpen = false" class="fixed inset-0 z-40 bg-stone-900/50 lg:hidden"></div>

<aside :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
       class="fixed inset-y-0 left-0 z-50 w-64 shrink-0 bg-white border-r border-stone-200/80 flex flex-col transition-transform duration-300 ease-in-out lg:static lg:z-auto">
    <div class="px-5 pt-6 pb-4 flex flex-col h-full min-h-0 overflow-y-auto custom-scrollbar">
        <div class="flex items-start justify-between mb-8 pl-1">
            <x-brand-logo :href="route('admin.dashboard')" badge="Admin" />
            <button type="button" @click="mobileSidebarOpen = false" aria-label="Tutup menu" class="p-1.5 rounded-lg text-stone-400 hover:text-stone-700 hover:bg-stone-100 lg:hidden">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <nav class="space-y-1.5 flex-1 min-h-0" aria-label="Navigasi Admin">
            @foreach($items as $item)
                @php $isActive = $active === $item['key']; @endphp
                <a href="{{ $item['href'] }}" @if($isActive) aria-current="page" @endif
                   class="w-full flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 {{ $isActive ? 'bg-[#EBF5F0] text-[#1B5E3A] font-bold' : 'text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium' }}">
                    <span class="flex items-center gap-3 min-w-0">
                        <svg class="w-5 h-5 shrink-0 {{ $isActive ? 'text-[#1B5E3A]' : 'text-stone-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/></svg>
                        <span class="truncate">{{ $item['label'] }}</span>
                    </span>
                    @if($item['badge'] > 0)
                        <span class="min-w-5 h-5 px-1.5 rounded-full bg-[#EA580C] text-white text-[11px] font-bold flex items-center justify-center shrink-0">{{ $item['badge'] }}</span>
                    @endif
                </a>
            @endforeach
        </nav>

        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-red-600 hover:bg-red-50 transition">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Keluar
            </button>
        </form>
    </div>
</aside>
