@extends('layouts.business')

@section('title', 'Dashboard Admin - What\'s Left')

@section('content')
<div class="flex h-screen overflow-hidden bg-[#FAF7F2]" x-data="{ mobileSidebarOpen: false }">
<x-admin.sidebar :active="$filter === 'pending' ? 'pending' : 'dashboard'" />
<div class="flex-1 flex flex-col min-w-0 overflow-hidden">
<header class="h-16 bg-white border-b border-stone-200/80 px-4 sm:px-6 lg:px-8 flex items-center justify-between shrink-0">
    <button type="button" @click="mobileSidebarOpen = true" aria-label="Buka menu" class="p-2 rounded-xl text-stone-600 hover:bg-stone-100 lg:hidden">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
    </button>
    <div class="ml-auto text-right leading-tight">
        <p class="text-[13px] font-bold text-[#1F2937]">{{ auth()->user()->first_name }}</p>
        <p class="text-[11px] text-[#6B7280] font-medium">Admin</p>
    </div>
</header>
<main class="flex-1 overflow-y-auto custom-scrollbar p-4 sm:p-6 lg:p-7">
    <div class="max-w-6xl">
        <h1 class="text-3xl font-black text-stone-900">Dashboard Admin</h1>
        <p class="text-sm text-stone-600 mt-1">Ringkasan platform dan verifikasi mitra bisnis.</p>

        @if(session('success'))
            <div class="mt-5 p-3 rounded-xl bg-[#DCF2E4] border border-[#BBF7D0] text-sm font-semibold text-[#166534]">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="mt-5 p-3 rounded-xl bg-rose-50 border border-rose-200 text-sm text-rose-700">{{ $errors->first() }}</div>
        @endif

        <div class="mt-6 grid grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach([
                ['Consumer', $stats['users']],
                ['Mitra Bisnis', $stats['businesses']],
                ['Menunggu Verifikasi', $stats['pendingBusinesses']],
                ['Listing Aktif', $stats['activeListings']],
                ['Total Reservasi', $stats['reservations']],
                ['Porsi Terselamatkan', $stats['completedPortions']],
            ] as [$label, $value])
                <div class="bg-white rounded-2xl border border-stone-200 p-5">
                    <p class="text-xs font-bold uppercase tracking-wider text-stone-500">{{ $label }}</p>
                    <p class="text-3xl font-black text-[#1B5E3A] mt-1">{{ number_format($value, 0, ',', '.') }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-10 flex items-center justify-between flex-wrap gap-3">
            <h2 class="text-xl font-extrabold text-stone-900">Mitra Bisnis</h2>
            <div class="flex flex-wrap items-center gap-2 text-xs font-bold">
                <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center gap-1.5">
                    @if($filter)
                        <input type="hidden" name="status" value="{{ $filter }}">
                    @endif
                    <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Cari nama bisnis, pemilik, kota..."
                           class="px-3 py-1.5 rounded-lg bg-white border border-stone-200 text-xs font-medium text-stone-700 placeholder:text-stone-400 focus:outline-none focus:border-[#1B5E3A] w-56">
                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-stone-800 text-white text-xs font-bold hover:bg-stone-900">Cari</button>
                    @if(!empty($search))
                        <a href="{{ route('admin.dashboard', $filter ? ['status' => $filter] : []) }}" class="px-2 py-1.5 text-stone-500 hover:text-stone-800">Reset</a>
                    @endif
                </form>
                <a href="{{ route('admin.dashboard', !empty($search) ? ['q' => $search] : []) }}" class="px-3 py-1.5 rounded-lg {{ $filter === null ? 'bg-[#1B5E3A] text-white' : 'bg-white border border-stone-200 text-stone-600' }}">Semua</a>
                @foreach(['pending' => 'Menunggu', 'verified' => 'Terverifikasi', 'rejected' => 'Ditolak'] as $key => $label)
                    <a href="{{ route('admin.dashboard', array_filter(['status' => $key, 'q' => $search ?? null])) }}" class="px-3 py-1.5 rounded-lg {{ $filter === $key ? 'bg-[#1B5E3A] text-white' : 'bg-white border border-stone-200 text-stone-600' }}">{{ $label }}</a>
                @endforeach
            </div>
        </div>

        <div class="mt-4 bg-white rounded-2xl border border-stone-200 overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-stone-50 text-left text-xs uppercase tracking-wider text-stone-500">
                    <tr>
                        <th class="px-4 py-3">Bisnis</th>
                        <th class="px-4 py-3">Pemilik & Kontak</th>
                        <th class="px-4 py-3">Lokasi</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($businesses as $business)
                        <tr>
                            <td class="px-4 py-3 font-bold text-stone-900">{{ $business->business_name }}<div class="text-xs font-normal text-stone-500">{{ $business->business_type }}</div></td>
                            <td class="px-4 py-3">
                                {{ trim(($business->user?->first_name ?? '') . ' ' . ($business->user?->last_name ?? '')) ?: '-' }}
                                <div class="text-xs text-stone-500">{{ $business->user?->email }}</div>
                                @if($business->phone || $business->user?->phone)
                                    <div class="text-xs text-stone-500">{{ $business->phone ?: $business->user?->phone }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div>{{ collect([$business->city, $business->province])->filter()->implode(', ') ?: '-' }}</div>
                                @if($business->street)
                                    <div class="text-xs text-stone-500">{{ $business->street }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $badge = [
                                        'verified' => ['Terverifikasi', 'bg-[#DCF2E4] text-[#166534]'],
                                        'rejected' => ['Ditolak', 'bg-rose-100 text-rose-700'],
                                    ][$business->verification_status] ?? ['Menunggu', 'bg-amber-100 text-amber-700'];
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $badge[1] }}">{{ $badge[0] }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <form method="POST" action="{{ route('admin.businesses.verification', $business->id) }}" class="flex gap-2">
                                    @csrf
                                    @method('PUT')
                                    @if($business->verification_status !== 'verified')
                                        <button name="verification_status" value="verified" class="px-3 py-1.5 rounded-lg bg-[#1B5E3A] text-white text-xs font-bold">Verifikasi</button>
                                    @endif
                                    @if($business->verification_status !== 'rejected')
                                        <button name="verification_status" value="rejected" class="px-3 py-1.5 rounded-lg border border-rose-200 text-rose-600 text-xs font-bold">Tolak</button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-10 text-center text-stone-500">Belum ada mitra bisnis.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($businesses->hasPages())
            <div class="mt-4">
                {{ $businesses->links() }}
            </div>
        @endif
    </div>
</main>
</div>
</div>
@endsection
