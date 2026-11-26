@extends('layouts.auth')

@section('title', 'Masuk ke Akun')

@section('nav-action')
    <div class="flex items-center gap-2">
        <span class="text-stone-500 hidden md:inline">Belum punya akun?</span>
        <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-sm font-semibold bg-[#1B5E3A]/10 text-[#1B5E3A] hover:bg-[#1B5E3A] hover:text-white transition duration-200">
            Daftar Sekarang
        </a>
    </div>
@endsection

@section('content')
<div x-data="{
    role: @js(old('role', 'consumer')),
    showPassword: false,
    email: @js(old('email', '')),
    password: '',
    remember: @js((bool) old('remember', false)),
    notification: ''
}" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">

    <!-- LEFT COLUMN: Brand Story & Impact Visual (Mockup Reflection) -->
    <div class="hidden lg:flex lg:col-span-5 flex-col justify-between space-y-8 pr-4">
        <div>
            <!-- Badge Promo / Category -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#EBF5F0] border border-[#DCF2E4] text-[#1B5E3A] text-xs font-bold tracking-wide uppercase mb-4">
                <span class="w-2 h-2 rounded-full bg-[#1B5E3A] animate-ping"></span>
                <span>Gerakan Food Rescue Indonesia</span>
            </div>

            <h1 class="text-3xl xl:text-4xl font-extrabold text-[#1F2937] tracking-tight leading-tight">
                Good Food Deserves a <br>
                <span class="text-[#E67E22] inline-block mt-1">Second Chance.</span>
            </h1>

            <p class="mt-4 text-[#6B7280] text-sm leading-relaxed">
                Sambungkan makanan lezat yang masih layak konsumsi dengan orang yang tepat. Bersama kurangi food waste, mulai dari langkah kecil hari ini! 💚
            </p>
        </div>

        <!-- Food Card Mockup Preview (Reflecting explore foods mockup) -->
        <div class="relative group">
            <div class="absolute -inset-1 bg-gradient-to-r from-[#1B5E3A]/20 to-[#E67E22]/20 rounded-2xl blur-lg opacity-70 group-hover:opacity-100 transition duration-300"></div>
            
            <div class="relative bg-white rounded-2xl p-4 border border-stone-200/80 shadow-md">
                <div class="flex gap-3.5">
                    <!-- Food Thumbnail with Discount Badge -->
                    <div class="relative w-28 h-28 shrink-0 rounded-xl overflow-hidden bg-stone-100">
                        <div class="w-full h-full flex items-center justify-center text-5xl bg-[#EBF5F0]">🥐</div>
                        <span class="absolute top-1.5 left-1.5 bg-[#F59E0B] text-white text-[10px] font-extrabold px-1.5 py-0.5 rounded-md shadow-sm">
                            30% OFF
                        </span>
                    </div>

                    <!-- Card Metadata -->
                    <div class="flex-1 flex flex-col justify-between min-w-0">
                        <div>
                            <div class="flex items-center gap-1.5 text-xs text-[#1B5E3A] font-semibold">
                                <span>What's Left</span>
                                <svg class="w-3.5 h-3.5 text-[#1B5E3A] fill-current" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-stone-300">•</span>
                                <span class="text-stone-500 text-[11px]">1.2 km</span>
                            </div>
                            <h4 class="font-bold text-[#1F2937] text-sm mt-0.5 truncate">Croissant Butter Premium</h4>
                        </div>

                        <div>
                            <div class="flex items-baseline gap-2">
                                <span class="text-base font-extrabold text-[#E67E22]">Rp 10.500</span>
                                <span class="text-xs line-through text-stone-400">Rp 15.000</span>
                            </div>
                            <div class="flex items-center gap-1 text-[11px] text-stone-500 mt-1">
                                <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Ambil: 18.00 - 20.00 WIB</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Callout badge inside mockup -->
                <div class="mt-3 pt-2.5 border-t border-stone-100 flex items-center justify-between text-xs">
                    <span class="inline-flex items-center gap-1 text-emerald-700 font-medium">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Diselamatkan hari ini
                    </span>
                    <span class="text-[11px] font-semibold text-stone-500 bg-stone-100 px-2 py-0.5 rounded">
                        Tersisa 3 porsi
                    </span>
                </div>
            </div>
        </div>

        <!-- Environmental & Social Impact Stats -->
        <div class="grid grid-cols-3 gap-3 bg-white/70 backdrop-blur-sm p-4 rounded-2xl border border-stone-200/70">
            <div class="text-center">
                <span class="block text-xl font-extrabold text-[#1B5E3A]">5.200+</span>
                <span class="text-[11px] text-[#6B7280] font-medium leading-tight">Porsi Diselamatkan</span>
            </div>
            <div class="text-center border-x border-stone-200">
                <span class="block text-xl font-extrabold text-[#E67E22]">180+</span>
                <span class="text-[11px] text-[#6B7280] font-medium leading-tight">Mitra Bisnis</span>
            </div>
            <div class="text-center">
                <span class="block text-xl font-extrabold text-[#1B5E3A]">4.2 Ton</span>
                <span class="text-[11px] text-[#6B7280] font-medium leading-tight">Limbah Dicegah</span>
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN: Authentication Card -->
    <div class="col-span-1 lg:col-span-7 max-w-xl mx-auto w-full">
    
        {{-- Pesan sukses dari Laravel Session --}}
        @if (session('success'))
            <div class="mb-4 p-3.5 bg-emerald-50 border border-emerald-200 text-[#1B5E3A] text-sm font-semibold rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        {{-- Pesan error dari Laravel --}}
        @if ($errors->any())
            <div class="mb-4 p-3.5 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Toast Simulation Notification -->
        <div 
            x-show="notification" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="mb-4 p-3.5 bg-emerald-50 border border-emerald-200 text-[#1B5E3A] text-xs sm:text-sm font-semibold rounded-xl flex items-center justify-between shadow-sm"
            x-cloak
        >
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-[#1B5E3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span x-text="notification"></span>
            </div>
            <button @click="notification = ''" class="text-emerald-700 hover:text-emerald-900">&times;</button>
        </div>

        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-stone-200/90 shadow-card relative">
            
            <!-- Form Header -->
            <div class="mb-6 text-center sm:text-left">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#1F2937] tracking-tight">
                    Selamat Datang Kembali! 👋
                </h2>
                <p class="mt-1.5 text-stone-500 text-sm">
                    Masuk ke akun What's Left Anda dan lanjutkan misi kebaikan.
                </p>
            </div>

            <!-- Role Selector Toggle Tabs -->
            <div class="p-1 bg-stone-100 rounded-2xl flex items-center gap-1 mb-6 border border-stone-200/80">
                <button 
                    type="button"
                    @click="role = 'consumer'"
                    :class="role === 'consumer' ? 'bg-[#1B5E3A] text-white shadow-sm' : 'text-stone-600 hover:text-[#1F2937]'"
                    class="flex-1 py-2.5 px-3 rounded-xl text-xs sm:text-sm font-bold transition-all duration-200 flex items-center justify-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span>Sebagai Konsumen</span>
                </button>
                <button 
                    type="button"
                    @click="role = 'business'"
                    :class="role === 'business' ? 'bg-[#1B5E3A] text-white shadow-sm' : 'text-stone-600 hover:text-[#1F2937]'"
                    class="flex-1 py-2.5 px-3 rounded-xl text-xs sm:text-sm font-bold transition-all duration-200 flex items-center justify-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Sebagai Mitra Bisnis</span>
                </button>
                <button 
                    type="button"
                    @click="role = 'admin'"
                    :class="role === 'admin' ? 'bg-[#1B5E3A] text-white shadow-sm' : 'text-stone-600 hover:text-[#1F2937]'"
                    class="flex-1 py-2.5 px-3 rounded-xl text-xs sm:text-sm font-bold transition-all duration-200 flex items-center justify-center gap-2"
                >
                    <span>Admin</span>
                </button>
            </div>

            <!-- Role Context Alert -->
            <div class="mb-5 px-3.5 py-2.5 rounded-xl border text-xs flex items-center gap-2.5"
                 :class="role === 'consumer' ? 'bg-emerald-50/70 border-emerald-200 text-emerald-800' : (role === 'business' ? 'bg-amber-50/70 border-amber-200 text-amber-800' : 'bg-blue-50 border-blue-200 text-blue-800')">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                <span x-show="role === 'consumer'">Masuk untuk mereservasi makanan surplus dengan harga diskon hingga 70%.</span>
                <span x-show="role === 'business'">Masuk ke portal merchant untuk mengelola stok surplus dan pencatatan produksi.</span>
                <span x-show="role === 'admin'">Masuk sebagai administrator untuk pengujian akses sistem.</span>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login.authenticate') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="role" :value="role">

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">
                        <span x-text="role === 'consumer' ? 'Alamat Email' : (role === 'business' ? 'Email Akun Usaha / Toko' : 'Email Administrator')"></span>
                        <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative rounded-xl shadow-2xs">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            x-model="email"
                            required 
                            autocomplete="email"
                            :placeholder="role === 'consumer' ? 'contoh: nama@domain.com' : (role === 'business' ? 'contoh: resto@sunnybakery.com' : 'admin@whatsleft.id')"
                            class="block w-full pl-10 pr-4 py-3 bg-[#FAF6F0]/60 border border-stone-200 rounded-xl text-sm text-[#1F2937] placeholder-stone-400 focus:outline-none focus:bg-white focus:border-[#1B5E3A] focus:ring-4 focus:ring-[#1B5E3A]/10 transition duration-150"
                        >
                    </div>
                </div>

                <!-- Password Input -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-stone-700">
                            Kata Sandi <span class="text-rose-500">*</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="text-xs font-semibold text-[#1B5E3A] hover:text-[#14472C] hover:underline">
                            Lupa Kata Sandi?
                        </a>
                    </div>
                    <div class="relative rounded-xl shadow-2xs">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input 
                            :type="showPassword ? 'text' : 'password'" 
                            id="password" 
                            name="password" 
                            x-model="password"
                            required 
                            placeholder="Masukkan kata sandi akun"
                            class="block w-full pl-10 pr-11 py-3 bg-[#FAF6F0]/60 border border-stone-200 rounded-xl text-sm text-[#1F2937] placeholder-stone-400 focus:outline-none focus:bg-white focus:border-[#1B5E3A] focus:ring-4 focus:ring-[#1B5E3A]/10 transition duration-150"
                        >
                        <!-- Show/Hide Password Toggle -->
                        <button 
                            type="button" 
                            @click="showPassword = !showPassword" 
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-stone-400 hover:text-stone-600 focus:outline-none"
                            aria-label="Toggle password visibility"
                        >
                            <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            x-model="remember"
                            class="w-4 h-4 rounded text-[#1B5E3A] border-stone-300 focus:ring-[#1B5E3A] accent-[#1B5E3A]"
                        >
                        <span class="text-xs sm:text-sm text-stone-600">Ingat saya!</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full mt-2 py-3.5 px-4 bg-[#1B5E3A] hover:bg-[#14472C] text-white font-bold text-sm rounded-xl shadow-lg shadow-[#1B5E3A]/25 hover:shadow-xl hover:shadow-[#1B5E3A]/30 active:scale-[0.99] transition duration-200 flex items-center justify-center gap-2 group cursor-pointer"
                >
                    <span x-text="role === 'consumer' ? 'Masuk Sebagai Konsumen' : (role === 'business' ? 'Masuk Portal Bisnis' : 'Masuk Sebagai Admin')"></span>
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>

            <!-- Bottom Register Link -->
            <div class="mt-8 pt-6 border-t border-stone-100 text-center text-sm text-stone-600">
                <span>Belum punya akun?</span>
                <a href="{{ route('register') }}" class="font-bold text-[#1B5E3A] hover:text-[#14472C] ml-1 hover:underline">
                    Daftar Sekarang
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
