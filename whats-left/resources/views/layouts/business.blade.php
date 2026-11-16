<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#FAF7F2]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard Merchant') - What's Left</title>
    <meta name="description" content="Dashboard Merchant What's Left - Pusat kontrol kelola makanan surplus, pantau pesanan, performa penjualan, dan dampak food rescue.">

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
                            canvas: '#FAF7F2',
                        },
                        charcoal: '#1F2937',
                        muted: '#6B7280',
                    },
                    boxShadow: {
                        'soft': '0 2px 10px -2px rgba(0, 0, 0, 0.04), 0 1px 3px 0 rgba(0, 0, 0, 0.02)',
                        'card': '0 4px 16px -2px rgba(0, 0, 0, 0.04), 0 2px 6px -1px rgba(0, 0, 0, 0.02)',
                        'modal': '0 20px 45px -10px rgba(0, 0, 0, 0.15)',
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js for lightweight reactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Native Vite integration if compiled -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
            background-color: #FAF7F2;
            color: #1F2937;
        }
        /* Custom scrollbar for table/lists */
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #F1F1F1;
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #D1D5DB;
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #9CA3AF;
        }
    </style>
</head>
<body class="min-h-full antialiased selection:bg-[#1B5E3A] selection:text-white bg-[#FAF7F2]"
      x-data="{
          sidebarOpen: false,
          activeMenu: 'dashboard',
          toast: { show: false, message: '', type: 'success' },
          showToast(msg, type = 'success') {
              this.toast.message = msg;
              this.toast.type = type;
              this.toast.show = true;
              setTimeout(() => { this.toast.show = false; }, 4000);
          }
      }"
      x-on:show-toast.window="showToast($event.detail.msg, $event.detail.type || 'success')">

    <!-- Toast Notification System -->
    <div x-cloak x-show="toast.show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 transform translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 transform translate-y-4 sm:translate-y-0 sm:scale-95"
         class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-5 py-3.5 rounded-2xl bg-[#1F2937] text-white shadow-2xl border border-stone-700/60">
        <div class="w-2.5 h-2.5 rounded-full" 
             :class="toast.type === 'error' ? 'bg-red-500' : 'bg-[#10B981] animate-pulse'"></div>
        <span class="text-sm font-medium" x-text="toast.message"></span>
    </div>

    @yield('content')

</body>
</html>
