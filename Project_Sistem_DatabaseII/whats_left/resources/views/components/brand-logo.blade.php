{{--
    Logo resmi What's Left — SATU-SATUNYA sumber logo di seluruh aplikasi.
    Props:
      href    : tujuan link (default: halaman utama)
      size    : 'md' (default) | 'sm'
      tagline : teks kecil di bawah wordmark (opsional)
      badge   : label kecil di bawah wordmark, mis. "Business" (opsional)
      link    : false untuk menampilkan tanpa <a>
--}}
@props([
    'href' => null,
    'size' => 'md',
    'tagline' => null,
    'badge' => null,
    'link' => true,
])
@php
    $href = $href ?? route('home');
    $box  = $size === 'sm' ? 'w-9 h-9' : 'w-10 h-10';
    $icon = $size === 'sm' ? 'w-[18px] h-[18px]' : 'w-5 h-5';
    $text = $size === 'sm' ? 'text-xl' : 'text-2xl';
    $tag  = $link ? 'a' : 'div';
@endphp
<{{ $tag }} @if($link) href="{{ $href }}" aria-label="What's Left - Beranda" @endif {{ $attributes->merge(['class' => 'inline-flex items-center gap-2.5 group']) }}>
    <span class="{{ $box }} shrink-0 rounded-xl bg-[#1B5E3A] flex items-center justify-center shadow-sm shadow-[#1B5E3A]/25 transition-transform group-hover:scale-105">
        <svg class="{{ $icon }} text-[#F59E0B]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19c0-8.5 5-14 14-14 0 9-5.5 14-14 14zm0 0c1.8-4.2 4.6-7.4 8.5-9.5"/>
        </svg>
    </span>
    <span class="flex flex-col min-w-0">
        <span class="flex items-center {{ $text }} font-black tracking-tight leading-none">
            <span class="text-[#1B5E3A]">What's</span><span class="text-[#E67E22] ml-1">Left</span>
        </span>
        @if($badge)
            <span class="mt-1.5 self-start inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-[#EBF5F0] text-[#1B5E3A] tracking-wide">{{ $badge }}</span>
        @elseif($tagline)
            <span class="text-[9px] font-bold tracking-wider uppercase text-[#6B7280] mt-1">{{ $tagline }}</span>
        @endif
    </span>
</{{ $tag }}>
