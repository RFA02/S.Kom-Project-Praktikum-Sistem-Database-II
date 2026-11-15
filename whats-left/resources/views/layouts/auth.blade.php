<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#FAF6F0]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Masuk atau Daftar') - What's Left</title>

    <!-- Google Fonts: Plus Jakarta Sans / Instrument Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js for smooth client-side interaction (role switcher, password visibility, validation) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Native Vite integration if built -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
            background-color: #FAF6F0;
        }
        .organic-blob {
            position: absolute;
            filter: blur(50px);
            opacity: 0.45;
            z-index: 0;
            pointer-events: none;
        }
    </style>
</head>
<body class="min-h-full flex flex-col text-[#1F2937] antialiased selection:bg-[#1B5E3A] selection:text-white relative overflow-x-hidden">

    <!-- Ambient Eco Background Blobs -->
    <div class="organic-blob w-96 h-96 rounded-full bg-[#1B5E3A]/15 top-[-100px] left-[-100px]"></div>
    <div class="organic-blob w-96 h-96 rounded-full bg-[#F59E0B]/20 bottom-[-50px] right-[-50px]"></div>
    <div class="organic-blob w-80 h-80 rounded-full bg-[#2E7D32]/10 top-1/2 right-10"></div>

    <!-- Top Minimal Navigation -->
    <header class="relative z-10 w-full px-6 py-4 sm:px-8 border-b border-stone-200/60 bg-white/70 backdrop-blur-md">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5 group transition-transform active:scale-95">
                <div class="w-10 h-10 rounded-xl bg-[#1B5E3A] flex items-center justify-center text-white shadow-sm shadow-[#1B5E3A]/20 transition-transform group-hover:scale-105">
                    <!-- Leaf / Food Sparkle Icon -->
                    <svg class="w-5 h-5 text-[#F59E0B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    <div class="flex items-center text-2xl font-extrabold tracking-tight leading-none">
                        <span class="text-[#1B5E3A]">What's</span>
                        <span class="text-[#E67E22] ml-1">Left</span>
                        <span class="text-[#E67E22] ml-0.5 text-xs animate-pulse">✨</span>
                    </div>
                    <span class="text-[9.5px] font-semibold tracking-wider uppercase text-[#6B7280] mt-0.5">Good Food Great Impact</span>
                </div>
            </a>

            <!-- Quick Action / Switch Link -->
            <div class="flex items-center gap-3 text-sm">
                @yield('nav-action')
                <a href="{{ url('/') }}" class="hidden sm:inline-flex items-center gap-1.5 text-stone-500 hover:text-[#1B5E3A] font-medium transition-colors px-3 py-1.5 rounded-lg hover:bg-stone-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Kembali ke Beranda</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="relative z-10 flex-1 flex items-center justify-center py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-6xl mx-auto">
            @yield('content')
        </div>
    </main>

    <!-- Simple Footer -->
    <footer class="relative z-10 py-6 text-center text-xs text-stone-500 border-t border-stone-200/60 bg-white/40">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2">
            <p>&copy; {{ date('Y') }} <strong>What's Left</strong> &bull; Proyek Praktikum Sistem Database II.</p>
            <div class="flex items-center gap-4 text-stone-500">
                <a href="#" class="hover:text-[#1B5E3A] transition">Syarat & Ketentuan</a>
                <span>&bull;</span>
                <a href="#" class="hover:text-[#1B5E3A] transition">Kebijakan Privasi</a>
                <span>&bull;</span>
                <a href="#" class="hover:text-[#1B5E3A] transition">Bantuan</a>
            </div>
        </div>
    </footer>

    <!-- Floating Quick Switcher Pill (Interactive Preview for Reviewers) -->
    <div class="fixed bottom-5 right-5 z-50">
        <div class="bg-white/95 backdrop-blur-md px-3.5 py-2 rounded-2xl shadow-xl border border-stone-200/90 flex items-center gap-2 text-xs font-semibold">
            <span class="text-stone-400 text-[11px] uppercase tracking-wider hidden sm:inline">Halaman:</span>
            <a href="{{ Route::has('login') ? route('login') : url('/?view=login') }}" 
               class="px-2.5 py-1 rounded-lg transition {{ request()->query('view', 'login') === 'login' ? 'bg-[#1B5E3A] text-white shadow-xs' : 'text-stone-600 hover:text-[#1B5E3A] hover:bg-stone-100' }}">
                🔑 Masuk
            </a>
            <a href="{{ Route::has('register') ? route('register') : url('/?view=register') }}" 
               class="px-2.5 py-1 rounded-lg transition {{ request()->query('view') === 'register' ? 'bg-[#1B5E3A] text-white shadow-xs' : 'text-stone-600 hover:text-[#1B5E3A] hover:bg-stone-100' }}">
                📝 Daftar
            </a>
        </div>
    </div>

</body>
</html>
