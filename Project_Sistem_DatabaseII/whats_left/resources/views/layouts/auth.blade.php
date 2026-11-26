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
    <script>
        // Kelas bergaya Tailwind v4 yang dipakai view (shadow-xs, backdrop-blur-xs, scale-98) tidak ada di CDN v3.
        (function (c) {
            c.theme = c.theme || {}; c.theme.extend = c.theme.extend || {};
            c.theme.extend.boxShadow = Object.assign({}, c.theme.extend.boxShadow, { xs: '0 1px 2px 0 rgb(0 0 0 / 0.05)' });
            c.theme.extend.backdropBlur = Object.assign({}, c.theme.extend.backdropBlur, { xs: '2px' });
            c.theme.extend.scale = Object.assign({}, c.theme.extend.scale, { '98': '0.98' });
        })(tailwind.config);
    </script>


    <!-- Alpine.js for smooth client-side interaction (role switcher, password visibility, validation) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.9/dist/cdn.min.js"></script>

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
            <x-brand-logo />

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

</body>
</html>
