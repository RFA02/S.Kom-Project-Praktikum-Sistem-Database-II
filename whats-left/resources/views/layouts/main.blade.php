<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'What\'s Left - Good Food Deserves a Second Chance')</title>
    <meta name="description" content="Platform penyelamatan makanan surplus (Food Rescue & Food Waste Prevention). Dapatkan makanan lezat berkualitas dengan harga diskon dan bersama kurangi food waste!">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN Fallback with Brand Theme (Ensures instant rendering without build step) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            green: '#1B5E3A',
                            'green-hover': '#14472C',
                            'green-light': '#EBF5F0',
                            'green-badge': '#DCF2E4',
                            orange: '#E67E22',
                            amber: '#F59E0B',
                            'amber-light': '#FEF3C7',
                            cream: '#FAF6F0',
                            'cream-light': '#FDFBF7',
                        },
                        charcoal: '#1F2937',
                        muted: '#6B7280',
                    },
                    boxShadow: {
                        'soft': '0 4px 20px -2px rgba(27, 94, 58, 0.08), 0 2px 6px -1px rgba(0, 0, 0, 0.04)',
                        'card': '0 10px 30px -5px rgba(0, 0, 0, 0.05), 0 4px 10px -2px rgba(0, 0, 0, 0.02)',
                        'floating': '0 20px 40px -10px rgba(27, 94, 58, 0.15)',
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js for lightweight, reactive frontend state -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Native Vite integration if assets are compiled -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
            background-color: #FAF6F0;
            color: #1F2937;
        }
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        /* Custom Green Range Slider matching Explore Foods mockup */
        .slider-green {
            -webkit-appearance: none;
            appearance: none;
            width: 100%;
            height: 6px;
            background: #DCF2E4;
            border-radius: 9999px;
            outline: none;
        }
        .slider-green::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #1B5E3A;
            cursor: pointer;
            border: 2.5px solid #FFFFFF;
            box-shadow: 0 2px 6px rgba(27, 94, 58, 0.35);
            transition: transform 0.15s ease;
        }
        .slider-green::-webkit-slider-thumb:hover {
            transform: scale(1.15);
        }
        .slider-green::-moz-range-thumb {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #1B5E3A;
            cursor: pointer;
            border: 2.5px solid #FFFFFF;
            box-shadow: 0 2px 6px rgba(27, 94, 58, 0.35);
        }
        .slider-green::-moz-range-progress {
            background-color: #1B5E3A;
            border-radius: 9999px;
            height: 6px;
        }
    </style>
</head>
<body class="min-h-full flex flex-col antialiased selection:bg-[#1B5E3A] selection:text-white bg-[#FAF6F0]"
      x-data="{
          mobileMenuOpen: false,
          activeNav: 'beranda',
          toast: { show: false, message: '', type: 'success' },
          showToast(msg, type = 'success') {
              this.toast.message = msg;
              this.toast.type = type;
              this.toast.show = true;
              setTimeout(() => { this.toast.show = false; }, 3500);
          },
          quickViewItem: null,
          openQuickView(item) {
              this.quickViewItem = item;
          },
          closeQuickView() {
              this.quickViewItem = null;
          }
      }">

    <!-- Toast Notification Overlay -->
    <div x-cloak x-show="toast.show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 transform translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 transform translate-y-4 sm:translate-y-0 sm:scale-95"
         class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-5 py-3.5 rounded-2xl bg-[#1F2937] text-white shadow-2xl border border-stone-700/50">
        <div class="w-2.5 h-2.5 rounded-full bg-[#10B981] animate-pulse"></div>
        <span class="text-sm font-medium" x-text="toast.message"></span>
    </div>

    <!-- 1. HEADER / NAVBAR -->
    @hasSection('header')
        @yield('header')
    @else
    <header class="sticky top-0 z-40 w-full bg-white/90 backdrop-blur-md border-b border-stone-200/70 transition-all duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            
            <!-- Brand Logo -->
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5 group">
                <div class="w-10 h-10 rounded-xl bg-[#1B5E3A] flex items-center justify-center text-white shadow-sm shadow-[#1B5E3A]/25 transition-transform group-hover:scale-105">
                    <!-- Sprout / Leaf Icon -->
                    <svg class="w-5 h-5 text-[#F59E0B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    <div class="flex items-center text-2xl font-black tracking-tight leading-none">
                        <span class="text-[#1B5E3A]">What's</span>
                        <span class="text-[#E67E22] ml-1">Left</span>
                    </div>
                    <span class="text-[9px] font-bold tracking-wider uppercase text-[#6B7280] mt-0.5">Food Rescue Indonesia</span>
                </div>
            </a>

            <!-- Desktop Navigation Links -->
            <nav class="hidden lg:flex items-center gap-8">
                <!-- Beranda (Active indicator matching mockup) -->
                <a href="{{ url('/') }}" 
                   @click="activeNav = 'beranda'"
                   class="relative py-2 text-sm font-bold text-[#1B5E3A] transition-colors">
                    Beranda
                    <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-[#1B5E3A] rounded-full"></span>
                </a>
                
                <a href="{{ url('/?view=explore') }}" 
                   @click="activeNav = 'jelajahi'"
                   class="py-2 text-sm font-semibold text-[#1F2937] hover:text-[#1B5E3A] transition-colors">
                    Jelajahi
                </a>

                <a href="#cara-kerja" 
                   @click="activeNav = 'cara-kerja'"
                   class="py-2 text-sm font-semibold text-[#1F2937] hover:text-[#1B5E3A] transition-colors">
                    Cara Kerja
                </a>

                <a href="#untuk-bisnis" 
                   @click="activeNav = 'bisnis'"
                   class="py-2 text-sm font-semibold text-[#1F2937] hover:text-[#1B5E3A] transition-colors">
                    Untuk Bisnis
                </a>

                <a href="#tentang-kami" 
                   @click="activeNav = 'tentang'"
                   class="py-2 text-sm font-semibold text-[#1F2937] hover:text-[#1B5E3A] transition-colors">
                    Tentang Kami
                </a>
            </nav>

            <!-- Right Actions (Search Icon, Cart Icon, Masuk, Daftar) -->
            <div class="hidden sm:flex items-center gap-3">
                <!-- Search Icon Button -->
                <button type="button" 
                        @click="document.getElementById('hero-search-input')?.focus()"
                        class="w-10 h-10 rounded-full flex items-center justify-center text-stone-600 hover:text-[#1B5E3A] hover:bg-stone-100 transition-colors"
                        title="Cari Makanan">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>

                <!-- Shopping Bag / Cart Icon -->
                <button type="button" 
                        @click="showToast('Keranjang reservasi saat ini kosong. Pilih makanan surplus untuk memulai!')"
                        class="w-10 h-10 rounded-full flex items-center justify-center text-stone-600 hover:text-[#1B5E3A] hover:bg-stone-100 transition-colors relative"
                        title="Keranjang">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-[#E67E22]"></span>
                </button>

                <!-- Button Masuk (Outline/Ghost button) -->
                <a href="{{ Route::has('login') ? route('login') : url('/?view=login') }}" 
                   class="px-5 py-2 rounded-xl text-sm font-bold text-stone-700 hover:text-[#1B5E3A] border border-stone-300 hover:border-[#1B5E3A] bg-white transition duration-200">
                    Masuk
                </a>

                <!-- Button Daftar (Primary Green button) -->
                <a href="{{ Route::has('register') ? route('register') : url('/?view=register') }}" 
                   class="px-5 py-2 rounded-xl text-sm font-bold text-white bg-[#1B5E3A] hover:bg-[#14472C] shadow-sm shadow-[#1B5E3A]/25 transition duration-200">
                    Daftar
                </a>
            </div>

            <!-- Mobile Menu Toggle Button -->
            <div class="flex items-center gap-2 sm:hidden">
                <a href="{{ Route::has('login') ? route('login') : url('/?view=login') }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold text-[#1B5E3A] bg-[#EBF5F0]">
                    Masuk
                </a>
                <button @click="mobileMenuOpen = !mobileMenuOpen" 
                        type="button" 
                        class="p-2 rounded-lg text-stone-600 hover:bg-stone-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                        <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer Navigation -->
        <div x-cloak x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="sm:hidden border-b border-stone-200 bg-white px-4 pt-3 pb-6 space-y-3">
            <a href="{{ url('/') }}" @click="mobileMenuOpen = false" class="block py-2 text-base font-bold text-[#1B5E3A]">Beranda</a>
            <a href="{{ url('/?view=explore') }}" @click="mobileMenuOpen = false" class="block py-2 text-base font-semibold text-stone-700">Jelajahi</a>
            <a href="#cara-kerja" @click="mobileMenuOpen = false" class="block py-2 text-base font-semibold text-stone-700">Cara Kerja</a>
            <a href="#untuk-bisnis" @click="mobileMenuOpen = false" class="block py-2 text-base font-semibold text-stone-700">Untuk Bisnis</a>
            <a href="#tentang-kami" @click="mobileMenuOpen = false" class="block py-2 text-base font-semibold text-stone-700">Tentang Kami</a>
            
            <div class="pt-4 border-t border-stone-100 flex flex-col gap-2">
                <a href="{{ Route::has('login') ? route('login') : url('/?view=login') }}" class="w-full text-center py-2.5 rounded-xl border border-stone-300 font-bold text-stone-700 text-sm">Masuk ke Akun</a>
                <a href="{{ Route::has('register') ? route('register') : url('/?view=register') }}" class="w-full text-center py-2.5 rounded-xl bg-[#1B5E3A] text-white font-bold text-sm">Daftar Sekarang</a>
            </div>
        </div>
    </header>
    @endif

    <!-- MAIN CONTENT -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer id="tentang-kami" class="bg-white border-t border-stone-200/80 pt-16 pb-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">
                
                <!-- Col 1: Brand Info & Mission -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-[#1B5E3A] flex items-center justify-center text-white shadow-sm">
                            <svg class="w-5 h-5 text-[#F59E0B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <span class="text-2xl font-black text-[#1B5E3A]">What's <span class="text-[#E67E22]">Left</span></span>
                    </div>
                    <p class="text-sm text-stone-600 leading-relaxed max-w-sm">
                        Platform penyelamatan makanan surplus nomor satu di Indonesia. Kami menjembatani bisnis kuliner dan konsumen untuk menikmati makanan berkualitas dengan harga terjangkau sekaligus mencegah food waste.
                    </p>
                    <div class="flex items-center gap-3 pt-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#EBF5F0] text-[#1B5E3A] text-xs font-bold border border-[#DCF2E4]">
                            🌱 Mendukung SDG 12 & 13
                        </span>
                        <span class="text-xs text-stone-500">Zero Food Waste Initiative</span>
                    </div>
                </div>

                <!-- Col 2: Konsumen -->
                <div>
                    <h3 class="text-sm font-bold text-stone-900 uppercase tracking-wider mb-4">Konsumen</h3>
                    <ul class="space-y-2.5 text-sm text-stone-600">
                        <li><a href="#makanan-populer" class="hover:text-[#1B5E3A] transition">Jelajahi Makanan</a></li>
                        <li><a href="{{ url('/?view=my-reservations') }}" class="hover:text-[#1B5E3A] transition font-semibold text-[#1B5E3A]">Reservasi Saya</a></li>
                        <li><a href="#cara-kerja" class="hover:text-[#1B5E3A] transition">Cara Reservasi</a></li>
                        <li><a href="{{ Route::has('register') ? route('register') : url('/?view=register') }}" class="hover:text-[#1B5E3A] transition">Daftar Akun Baru</a></li>
                    </ul>
                </div>

                <!-- Col 3: Untuk Bisnis -->
                <div id="untuk-bisnis">
                    <h3 class="text-sm font-bold text-stone-900 uppercase tracking-wider mb-4">Untuk Bisnis</h3>
                    <ul class="space-y-2.5 text-sm text-stone-600">
                        <li><a href="{{ Route::has('register') ? route('register') : url('/?view=register') }}" class="hover:text-[#1B5E3A] transition">Gabung Jadi Mitra Resto</a></li>
                        <li><a href="{{ url('/?view=dashboard') }}" class="hover:text-[#1B5E3A] transition font-semibold text-[#1B5E3A]">Merchant Portal (Dashboard)</a></li>
                        <li><a href="#cara-kerja" class="hover:text-[#1B5E3A] transition">Pencegahan Surplus</a></li>
                        <li><a href="#cara-kerja" class="hover:text-[#1B5E3A] transition">Rekomendasi Produksi</a></li>
                    </ul>
                </div>

                <!-- Col 4: Informasi Proyek -->
                <div>
                    <h3 class="text-sm font-bold text-stone-900 uppercase tracking-wider mb-4">Tentang Proyek</h3>
                    <p class="text-xs text-stone-500 leading-relaxed mb-3">
                        Praktikum Sistem Database II (S.Kom Project)
                    </p>
                    <div class="space-y-1.5 text-xs text-stone-600">
                        <div class="font-medium text-stone-800">Tim Pengembang:</div>
                        <div>• Jasmine Salsabilla (140810250017)</div>
                        <div>• Azahwa Ezarmei Narita (140810250044)</div>
                        <div>• M. Razaqa Fasya Aditya (140810250074)</div>
                    </div>
                </div>

            </div>

            <!-- Bottom Sub-footer -->
            <div class="pt-8 mt-12 border-t border-stone-200/70 flex flex-col sm:flex-row items-center justify-between text-xs text-stone-500 gap-4">
                <p>© {{ date('Y') }} What's Left. Hak Cipta Dilindungi. Good Food Great Impact.</p>
                <div class="flex items-center gap-6">
                    <a href="#" class="hover:underline">Kebijakan Privasi</a>
                    <a href="#" class="hover:underline">Syarat & Ketentuan</a>
                    <a href="#" class="hover:underline">Bantuan</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
