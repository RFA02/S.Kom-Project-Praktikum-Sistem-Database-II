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

    <!-- Tailwind CSS CDN Fallback with Brand Theme -->
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
    <script>
        // Kelas bergaya Tailwind v4 yang dipakai view (shadow-xs, backdrop-blur-xs, scale-98) tidak ada di CDN v3.
        (function (c) {
            c.theme = c.theme || {}; c.theme.extend = c.theme.extend || {};
            c.theme.extend.boxShadow = Object.assign({}, c.theme.extend.boxShadow, { xs: '0 1px 2px 0 rgb(0 0 0 / 0.05)' });
            c.theme.extend.backdropBlur = Object.assign({}, c.theme.extend.backdropBlur, { xs: '2px' });
            c.theme.extend.scale = Object.assign({}, c.theme.extend.scale, { '98': '0.98' });
        })(tailwind.config);
    </script>


    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.9/dist/cdn.min.js"></script>

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
    </style>
</head>

<body class="min-h-full flex flex-col antialiased selection:bg-[#1B5E3A] selection:text-white bg-[#FAF6F0]"
      @show-toast.window="showToast($event.detail.msg || 'Pembaruan berhasil.', $event.detail.type || 'success')"
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

        <div class="w-2.5 h-2.5 rounded-full animate-pulse"
             :class="toast.type === 'error' ? 'bg-red-400' : (toast.type === 'info' ? 'bg-blue-400' : 'bg-[#10B981]')"></div>
        <span class="text-sm font-medium" x-text="toast.message"></span>
    </div>

    <!-- 1. HEADER / NAVBAR -->
    <header class="sticky top-0 z-40 w-full bg-white/95 backdrop-blur-md border-b border-stone-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <x-brand-logo />

            <nav class="hidden lg:flex items-center gap-8">
                <a href="{{ route('home') }}" class="text-sm font-semibold text-stone-700 hover:text-[#1B5E3A]">Beranda</a>
                <a href="{{ route('consumer.explore') }}" class="text-sm font-semibold text-stone-700 hover:text-[#1B5E3A]">Jelajahi</a>
                <a href="{{ route('home') }}#cara-kerja" class="text-sm font-semibold text-stone-700 hover:text-[#1B5E3A]">Cara Kerja</a>
                <a href="{{ route('register') }}" class="text-sm font-semibold text-stone-700 hover:text-[#1B5E3A]">Untuk Bisnis</a>
            </nav>

            <div class="flex items-center gap-3">
                @auth
                    @if(auth()->user()->role === 'customer')
                        @php $unreadCount = $consumerUnreadCount ?? 0; @endphp
                        <a href="{{ route('consumer.notifications') }}"
                           aria-label="Notifikasi{{ $unreadCount ? ' ('.$unreadCount.' belum dibaca)' : '' }}"
                           class="relative p-2 rounded-xl text-stone-500 hover:bg-stone-100 hover:text-[#1B5E3A] transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0"/>
                            </svg>
                            @if($unreadCount > 0)
                                <span class="absolute top-1 right-1 min-w-[16px] h-4 px-1 rounded-full bg-[#EA580C] text-white text-[10px] font-bold flex items-center justify-center">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                            @endif
                        </a>
                    @endif
                    <div class="relative" x-data="{ profileOpen: false }">
                        <button type="button" @click="profileOpen = !profileOpen" @click.outside="profileOpen = false"
                                class="flex items-center gap-2 rounded-full px-2 py-1 hover:bg-stone-100">
                            <span class="w-9 h-9 rounded-full bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center font-extrabold">
                                {{ mb_substr(auth()->user()->first_name ?? 'U', 0, 1) }}
                            </span>
                            <span class="hidden sm:inline text-xs font-bold text-stone-800">{{ auth()->user()->first_name }}</span>
                            <span class="text-stone-400">⌄</span>
                        </button>
                        <div x-cloak x-show="profileOpen" class="absolute right-0 mt-2 w-52 rounded-2xl bg-white shadow-xl border border-stone-100 py-2">
                            <div class="px-4 py-2 border-b border-stone-100">
                                <p class="text-xs font-bold text-stone-900">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</p>
                                <p class="text-[11px] text-stone-500">{{ auth()->user()->email }}</p>
                            </div>
                            @if(auth()->user()->role === 'customer')
                                <a href="{{ route('consumer.my-reservations') }}" class="block px-4 py-2 text-xs text-stone-700 hover:bg-stone-50">Reservasi Saya</a>
                                <a href="{{ route('consumer.notifications') }}" class="flex items-center justify-between px-4 py-2 text-xs text-stone-700 hover:bg-stone-50">
                                    <span>Notifikasi</span>
                                    @if(($consumerUnreadCount ?? 0) > 0)
                                        <span class="px-1.5 py-0.5 rounded-full bg-[#EA580C] text-white text-[10px] font-bold">{{ $consumerUnreadCount > 9 ? '9+' : $consumerUnreadCount }}</span>
                                    @endif
                                </a>
                                <a href="{{ route('consumer.profile') }}" class="block px-4 py-2 text-xs text-stone-700 hover:bg-stone-50">Profil Saya</a>
                            @elseif(auth()->user()->role === 'business')
                                <a href="{{ route('business.dashboard') }}" class="block px-4 py-2 text-xs text-stone-700 hover:bg-stone-50">Dashboard Bisnis</a>
                            @elseif(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-xs text-stone-700 hover:bg-stone-50">Dashboard Admin</a>
                            @endif
                            <div class="border-t border-stone-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-xs font-semibold text-red-600 hover:bg-red-50">Keluar</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl text-sm font-bold text-[#1B5E3A] hover:bg-[#EBF5F0]">Masuk</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl text-sm font-bold bg-[#1B5E3A] text-white hover:bg-[#14472C]">Daftar</a>
                @endauth

                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="p-2 rounded-xl text-stone-600 hover:bg-stone-100 lg:hidden" aria-label="Buka menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                    </svg>
                </button>
            </div>
        </div>

        <div x-cloak x-show="mobileMenuOpen" class="lg:hidden border-t border-stone-200 bg-white px-4 pt-3 pb-5 space-y-2">
            <a href="{{ route('home') }}" class="block py-2 text-sm font-semibold text-stone-700">Beranda</a>
            <a href="{{ route('consumer.explore') }}" class="block py-2 text-sm font-semibold text-stone-700">Jelajahi</a>
            <a href="{{ route('home') }}#cara-kerja" class="block py-2 text-sm font-semibold text-stone-700">Cara Kerja</a>
            @auth
                @if(auth()->user()->role === 'customer')
                    <a href="{{ route('consumer.my-reservations') }}" class="block py-2 text-sm font-semibold text-stone-700">Reservasi Saya</a>
                    <a href="{{ route('consumer.notifications') }}" class="block py-2 text-sm font-semibold text-stone-700">Notifikasi</a>
                    <a href="{{ route('consumer.profile') }}" class="block py-2 text-sm font-semibold text-stone-700">Profil Saya</a>
                @elseif(auth()->user()->role === 'business')
                    <a href="{{ route('business.dashboard') }}" class="block py-2 text-sm font-semibold text-stone-700">Dashboard Bisnis</a>
                @elseif(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="block py-2 text-sm font-semibold text-stone-700">Dashboard Admin</a>
                @endif
            @else
                <a href="{{ route('login') }}" class="block py-2 text-sm font-semibold text-stone-700">Masuk</a>
                <a href="{{ route('register') }}" class="block py-2 text-sm font-semibold text-stone-700">Daftar</a>
            @endauth
        </div>
    </header>

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

                    <x-brand-logo />

                    <p class="text-sm text-stone-600 leading-relaxed max-w-sm">
                        Platform penyelamatan makanan surplus nomor satu di Indonesia. Kami menjembatani bisnis kuliner dan konsumen untuk menikmati makanan berkualitas dengan harga terjangkau sekaligus mencegah food waste.
                    </p>

                    <div class="flex items-center gap-3 pt-2">

                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#EBF5F0] text-[#1B5E3A] text-xs font-bold border border-[#DCF2E4]">
                            🌱 Mendukung SDG 12 & 13
                        </span>

                        <span class="text-xs text-stone-500">
                            Zero Food Waste Initiative
                        </span>

                    </div>

                </div>

                <!-- Col 2: Konsumen -->
                <div>

                    <h3 class="text-sm font-bold text-stone-900 uppercase tracking-wider mb-4">
                        Konsumen
                    </h3>

                    <ul class="space-y-2.5 text-sm text-stone-600">

                        <li>
                            <a href="{{ route('consumer.explore') }}" class="hover:text-[#1B5E3A] transition">
                                Jelajahi Makanan
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('home') }}#makanan-populer" class="hover:text-[#1B5E3A] transition">
                                Promo & Diskon Hari Ini
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('home') }}#cara-kerja" class="hover:text-[#1B5E3A] transition">
                                Cara Reservasi
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('register') }}" class="hover:text-[#1B5E3A] transition">
                                Daftar Akun Baru
                            </a>
                        </li>

                    </ul>

                </div>

                <!-- Col 3: Untuk Bisnis -->
                <div id="untuk-bisnis">

                    <h3 class="text-sm font-bold text-stone-900 uppercase tracking-wider mb-4">
                        Untuk Bisnis
                    </h3>

                    <ul class="space-y-2.5 text-sm text-stone-600">

                        <li>
                            <a href="{{ route('register') }}" class="hover:text-[#1B5E3A] transition">
                                Gabung Jadi Mitra Resto
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('login') }}" class="hover:text-[#1B5E3A] transition">
                                Merchant Portal
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('home') }}#cara-kerja" class="hover:text-[#1B5E3A] transition">
                                Pencegahan Surplus
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('home') }}#cara-kerja" class="hover:text-[#1B5E3A] transition">
                                Rekomendasi Produksi
                            </a>
                        </li>

                    </ul>

                </div>

                <!-- Col 4: Informasi Proyek -->
                <div>

                    <h3 class="text-sm font-bold text-stone-900 uppercase tracking-wider mb-4">
                        Tentang Proyek
                    </h3>

                    <p class="text-xs text-stone-500 leading-relaxed mb-3">
                        Praktikum Sistem Database II (S.Kom Project)
                    </p>

                    <div class="space-y-1.5 text-xs text-stone-600">

                        <div class="font-medium text-stone-800">
                            Tim Pengembang:
                        </div>

                        <div>• Jasmine Salsabilla (140810250017)</div>
                        <div>• Azahwa Ezarmei Narita (140810250044)</div>
                        <div>• M. Razaqa Fasya Aditya (140810250074)</div>

                    </div>

                </div>

            </div>

            <!-- Bottom Sub-footer -->
            <div class="pt-8 mt-12 border-t border-stone-200/70 flex flex-col sm:flex-row items-center justify-between text-xs text-stone-500 gap-4">

                <p>
                    © {{ date('Y') }} What's Left. Hak Cipta Dilindungi. Good Food Great Impact.
                </p>

                <div class="flex items-center gap-6">

                    <a href="#" class="hover:underline">
                        Kebijakan Privasi
                    </a>

                    <a href="#" class="hover:underline">
                        Syarat & Ketentuan
                    </a>

                    <a href="#" class="hover:underline">
                        Bantuan
                    </a>

                </div>

            </div>

        </div>

    </footer>

</body>
</html>
