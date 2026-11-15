@extends('layouts.auth')

@section('title', 'Daftar Akun Baru')

@section('nav-action')
    <div class="flex items-center gap-2">
        <span class="text-stone-500 hidden md:inline">Sudah memiliki akun?</span>
        <a href="{{ Route::has('login') ? route('login') : url('/?view=login') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-sm font-semibold bg-[#1B5E3A]/10 text-[#1B5E3A] hover:bg-[#1B5E3A] hover:text-white transition duration-200">
            Masuk ke Akun
        </a>
    </div>
@endsection

@section('content')
<div x-data="{
    accountType: 'consumer', // 'consumer' or 'business'
    showPassword: false,
    showConfirmPassword: false,
    name: '',
    businessName: '',
    businessCategory: 'bakery',
    ownerName: '',
    email: '',
    phone: '',
    city: 'Jakarta Selatan',
    password: '',
    passwordConfirmation: '',
    termsAccepted: false,
    notification: '',
    
    // Password Strength Helper
    get passwordScore() {
        let score = 0;
        if (this.password.length >= 8) score++;
        if (/[A-Z]/.test(this.password)) score++;
        if (/[0-9]/.test(this.password)) score++;
        if (/[^A-Za-z0-9]/.test(this.password)) score++;
        return score;
    },
    
    get passwordsMatch() {
        if (!this.passwordConfirmation) return true;
        return this.password === this.passwordConfirmation;
    },

    handleSubmit() {
        if (!this.termsAccepted) {
            this.notification = 'Mohon setujui Syarat & Ketentuan untuk melanjutkan.';
            setTimeout(() => this.notification = '', 4000);
            return;
        }
        if (this.password !== this.passwordConfirmation) {
            this.notification = 'Konfirmasi kata sandi tidak cocok!';
            setTimeout(() => this.notification = '', 4000);
            return;
        }
        this.notification = 'Pendaftaran simulasi berhasil! Mengarahkan ke halaman masuk...';
        setTimeout(() => {
            this.notification = '';
            window.location.href = '{{ Route::has('login') ? route('login') : url('/?view=login') }}';
        }, 2000);
    }
}" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

    <!-- LEFT COLUMN: How It Works & Environmental Value (Mockup Alignment) -->
    <div class="hidden lg:flex lg:col-span-5 flex-col justify-between space-y-8 pr-4 sticky top-8">
        <div>
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#FEF3C7] border border-[#FDE68A] text-[#E67E22] text-xs font-bold tracking-wide uppercase mb-4">
                <svg class="w-3.5 h-3.5 text-[#E67E22]" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <span>Bergabung Sekarang</span>
            </div>

            <h1 class="text-3xl xl:text-4xl font-extrabold text-[#1F2937] tracking-tight leading-tight">
                Mulai Aksi Nyata, <br>
                <span class="text-[#1B5E3A] inline-block mt-1">Selamatkan Setiap Rasa.</span>
            </h1>

            <p class="mt-4 text-[#6B7280] text-sm leading-relaxed">
                Jadilah bagian dari komunitas cerdas dan ramah lingkungan. Dapatkan hidangan lezat dengan harga terjangkau atau ubah surplus usaha Anda menjadi peluang berharga.
            </p>
        </div>

        <!-- 4-Step How It Works Card (Identical to "Bagaimana Cara Kerjanya" in landing page mockup) -->
        <div class="bg-white rounded-2xl p-5 border border-stone-200/80 shadow-md space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                <h3 class="font-bold text-sm text-[#1F2937] flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-[#1B5E3A] text-white text-xs flex items-center justify-center font-bold">?</span>
                    Bagaimana Cara Kerjanya?
                </h3>
                <span class="text-[11px] font-semibold text-[#1B5E3A] bg-[#EBF5F0] px-2 py-0.5 rounded-full">
                    Mudah & Cepat
                </span>
            </div>

            <div class="space-y-3.5">
                <!-- Step 1 -->
                <div class="flex items-start gap-3 text-xs">
                    <span class="w-5 h-5 rounded-full bg-[#1B5E3A]/10 text-[#1B5E3A] font-bold flex items-center justify-center shrink-0 mt-0.5">1</span>
                    <div>
                        <strong class="text-stone-800 font-semibold block">Bisnis Unggah Surplus</strong>
                        <p class="text-stone-500 text-[11px] mt-0.5">Restoran mengunggah makanan lezat layak konsumsi sebelum tutup.</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="flex items-start gap-3 text-xs">
                    <span class="w-5 h-5 rounded-full bg-[#1B5E3A]/10 text-[#1B5E3A] font-bold flex items-center justify-center shrink-0 mt-0.5">2</span>
                    <div>
                        <strong class="text-stone-800 font-semibold block">Konsumen Reservasi</strong>
                        <p class="text-stone-500 text-[11px] mt-0.5">Pilih menu favorit dan pesan lewat aplikasi dengan harga diskon.</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="flex items-start gap-3 text-xs">
                    <span class="w-5 h-5 rounded-full bg-[#1B5E3A]/10 text-[#1B5E3A] font-bold flex items-center justify-center shrink-0 mt-0.5">3</span>
                    <div>
                        <strong class="text-stone-800 font-semibold block">Ambil di Waktu Ditentukan</strong>
                        <p class="text-stone-500 text-[11px] mt-0.5">Tunjukkan kode booking unik saat pengambilan pesanan langsung.</p>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="flex items-start gap-3 text-xs">
                    <span class="w-5 h-5 rounded-full bg-[#E67E22]/20 text-[#E67E22] font-bold flex items-center justify-center shrink-0 mt-0.5">4</span>
                    <div>
                        <strong class="text-[#E67E22] font-bold block">Bersama Kurangi Food Waste</strong>
                        <p class="text-stone-500 text-[11px] mt-0.5">Setiap porsi berkontribusi langsung menjaga kelestarian bumi 🌍</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- SDG 12 & Eco Pledge Badge -->
        <div class="p-4 rounded-2xl bg-gradient-to-br from-[#1B5E3A]/10 to-[#E67E22]/10 border border-[#1B5E3A]/20 flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-xl bg-[#1B5E3A] text-white flex items-center justify-center font-extrabold text-sm shrink-0 shadow-sm">
                SDG 12
            </div>
            <div class="text-xs">
                <span class="font-bold text-[#1F2937] block">Konsumsi & Produksi Bertanggung Jawab</span>
                <span class="text-stone-600 text-[11px]">Mendukung target pengurangan separuh limbah pangan dunia.</span>
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN: Sign Up Registration Card -->
    <div class="col-span-1 lg:col-span-7 max-w-xl mx-auto w-full">
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

        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-stone-200/90 shadow-card">
            
            <!-- Form Header -->
            <div class="mb-6 text-center sm:text-left">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#1F2937] tracking-tight">
                    Daftar Akun Baru 🌱
                </h2>
                <p class="mt-1 text-stone-500 text-sm">
                    Pilih tipe akun Anda dan mulailah membuat dampak positif.
                </p>
            </div>

            <!-- STEP 1: INTERACTIVE ACCOUNT TYPE SELECTION -->
            <div class="mb-6">
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2.5">
                    Pilih Tipe Akun Anda <span class="text-rose-500">*</span>
                </label>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- Option 1: Konsumen -->
                    <div 
                        @click="accountType = 'consumer'"
                        :class="accountType === 'consumer' 
                            ? 'border-2 border-[#1B5E3A] bg-[#EBF5F0]/50 shadow-sm ring-2 ring-[#1B5E3A]/10' 
                            : 'border border-stone-200 hover:border-stone-300 bg-white'"
                        class="p-4 rounded-2xl cursor-pointer transition-all duration-200 relative flex flex-col justify-between"
                    >
                        <div class="flex items-start justify-between">
                            <div class="w-10 h-10 rounded-xl bg-[#1B5E3A] text-white flex items-center justify-center shadow-xs">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <!-- Radio indicator -->
                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                                 :class="accountType === 'consumer' ? 'border-[#1B5E3A]' : 'border-stone-300'">
                                <div x-show="accountType === 'consumer'" class="w-2.5 h-2.5 rounded-full bg-[#1B5E3A]"></div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <div class="flex items-center gap-1.5">
                                <span class="font-extrabold text-[#1F2937] text-sm sm:text-base">Sebagai Konsumen</span>
                            </div>
                            <span class="inline-block mt-0.5 text-[10px] font-bold text-[#E67E22] bg-[#FEF3C7] px-1.5 py-0.5 rounded">
                                Diskon 30% - 70%
                            </span>
                            <p class="text-stone-500 text-xs mt-1.5 leading-snug">
                                Temukan & nikmati makanan lezat surplus dari berbagai resto terdekat.
                            </p>
                        </div>
                    </div>

                    <!-- Option 2: Mitra Bisnis -->
                    <div 
                        @click="accountType = 'business'"
                        :class="accountType === 'business' 
                            ? 'border-2 border-[#1B5E3A] bg-[#EBF5F0]/50 shadow-sm ring-2 ring-[#1B5E3A]/10' 
                            : 'border border-stone-200 hover:border-stone-300 bg-white'"
                        class="p-4 rounded-2xl cursor-pointer transition-all duration-200 relative flex flex-col justify-between"
                    >
                        <div class="flex items-start justify-between">
                            <div class="w-10 h-10 rounded-xl bg-[#E67E22] text-white flex items-center justify-center shadow-xs">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <!-- Radio indicator -->
                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                                 :class="accountType === 'business' ? 'border-[#1B5E3A]' : 'border-stone-300'">
                                <div x-show="accountType === 'business'" class="w-2.5 h-2.5 rounded-full bg-[#1B5E3A]"></div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <div class="flex items-center gap-1.5">
                                <span class="font-extrabold text-[#1F2937] text-sm sm:text-base">Mitra Bisnis</span>
                            </div>
                            <span class="inline-block mt-0.5 text-[10px] font-bold text-[#1B5E3A] bg-[#EBF5F0] px-1.5 py-0.5 rounded">
                                Merchant Portal
                            </span>
                            <p class="text-stone-500 text-xs mt-1.5 leading-snug">
                                Jual surplus makanan, pantau produksi, dan kurangi kerugian resto/toko Anda.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- REGISTRATION FORM (Pure Frontend State / Simulated Action) -->
            <form @submit.prevent="handleSubmit" method="POST" action="#" class="space-y-4">
                @csrf
                <input type="hidden" name="account_type" :value="accountType">

                <!-- CONDITIONAL FIELDS FOR CONSUMER -->
                <template x-if="accountType === 'consumer'">
                    <div class="space-y-4">
                        <!-- Full Name -->
                        <div>
                            <label for="consumer_name" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">
                                Nama Lengkap <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative rounded-xl shadow-2xs">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <input 
                                    type="text" 
                                    id="consumer_name" 
                                    name="name" 
                                    x-model="name"
                                    required 
                                    placeholder="contoh: Jasmine Salsabilla"
                                    class="block w-full pl-10 pr-4 py-3 bg-[#FAF6F0]/60 border border-stone-200 rounded-xl text-sm text-[#1F2937] placeholder-stone-400 focus:outline-none focus:bg-white focus:border-[#1B5E3A] focus:ring-4 focus:ring-[#1B5E3A]/10 transition duration-150"
                                >
                            </div>
                        </div>

                        <!-- Email Address -->
                        <div>
                            <label for="consumer_email" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">
                                Alamat Email <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative rounded-xl shadow-2xs">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </div>
                                <input 
                                    type="email" 
                                    id="consumer_email" 
                                    name="email" 
                                    x-model="email"
                                    required 
                                    placeholder="contoh: jasmine@example.com"
                                    class="block w-full pl-10 pr-4 py-3 bg-[#FAF6F0]/60 border border-stone-200 rounded-xl text-sm text-[#1F2937] placeholder-stone-400 focus:outline-none focus:bg-white focus:border-[#1B5E3A] focus:ring-4 focus:ring-[#1B5E3A]/10 transition duration-150"
                                >
                            </div>
                        </div>

                        <!-- WhatsApp / Phone Number -->
                        <div>
                            <label for="consumer_phone" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">
                                Nomor WhatsApp / HP <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative rounded-xl shadow-2xs">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <input 
                                    type="tel" 
                                    id="consumer_phone" 
                                    name="phone" 
                                    x-model="phone"
                                    required 
                                    placeholder="contoh: 0812-3456-7890"
                                    class="block w-full pl-10 pr-4 py-3 bg-[#FAF6F0]/60 border border-stone-200 rounded-xl text-sm text-[#1F2937] placeholder-stone-400 focus:outline-none focus:bg-white focus:border-[#1B5E3A] focus:ring-4 focus:ring-[#1B5E3A]/10 transition duration-150"
                                >
                            </div>
                            <p class="text-[11px] text-stone-500 mt-1">Digunakan untuk mengirimkan konfirmasi reservasi dan kode booking.</p>
                        </div>
                    </div>
                </template>

                <!-- CONDITIONAL FIELDS FOR BUSINESS / MERCHANT -->
                <template x-if="accountType === 'business'">
                    <div class="space-y-4">
                        <!-- Business Name -->
                        <div>
                            <label for="business_name" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">
                                Nama Usaha / Toko <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative rounded-xl shadow-2xs">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <input 
                                    type="text" 
                                    id="business_name" 
                                    name="business_name" 
                                    x-model="businessName"
                                    required 
                                    placeholder="contoh: Sunny Bakery & Pastry"
                                    class="block w-full pl-10 pr-4 py-3 bg-[#FAF6F0]/60 border border-stone-200 rounded-xl text-sm text-[#1F2937] placeholder-stone-400 focus:outline-none focus:bg-white focus:border-[#1B5E3A] focus:ring-4 focus:ring-[#1B5E3A]/10 transition duration-150"
                                >
                            </div>
                        </div>

                        <!-- Business Category & City (2 Columns) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label for="business_category" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">
                                    Kategori Usaha <span class="text-rose-500">*</span>
                                </label>
                                <select 
                                    id="business_category" 
                                    name="category" 
                                    x-model="businessCategory"
                                    class="block w-full px-3 py-3 bg-[#FAF6F0]/60 border border-stone-200 rounded-xl text-sm text-[#1F2937] focus:outline-none focus:bg-white focus:border-[#1B5E3A] focus:ring-4 focus:ring-[#1B5E3A]/10 transition"
                                >
                                    <option value="bakery">Roti & Kue (Bakery)</option>
                                    <option value="restaurant">Restoran & Cafe</option>
                                    <option value="catering">Katering & Nasi Kotak</option>
                                    <option value="beverage">Kopi & Minuman</option>
                                    <option value="groceries">Supermarket & Buah</option>
                                    <option value="umkm">Warung & UMKM Makanan</option>
                                </select>
                            </div>

                            <div>
                                <label for="business_city" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">
                                    Kota Operasional <span class="text-rose-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="business_city" 
                                    name="city" 
                                    x-model="city"
                                    required 
                                    placeholder="contoh: Jakarta Selatan"
                                    class="block w-full px-3.5 py-3 bg-[#FAF6F0]/60 border border-stone-200 rounded-xl text-sm text-[#1F2937] focus:outline-none focus:bg-white focus:border-[#1B5E3A] focus:ring-4 focus:ring-[#1B5E3A]/10 transition"
                                >
                            </div>
                        </div>

                        <!-- Business Email & Phone -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label for="business_email" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">
                                    Email Usaha <span class="text-rose-500">*</span>
                                </label>
                                <input 
                                    type="email" 
                                    id="business_email" 
                                    name="email" 
                                    x-model="email"
                                    required 
                                    placeholder="kontak@toko.com"
                                    class="block w-full px-3.5 py-3 bg-[#FAF6F0]/60 border border-stone-200 rounded-xl text-sm text-[#1F2937] focus:outline-none focus:bg-white focus:border-[#1B5E3A] focus:ring-4 focus:ring-[#1B5E3A]/10 transition"
                                >
                            </div>
                            <div>
                                <label for="business_phone" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">
                                    WhatsApp Usaha <span class="text-rose-500">*</span>
                                </label>
                                <input 
                                    type="tel" 
                                    id="business_phone" 
                                    name="phone" 
                                    x-model="phone"
                                    required 
                                    placeholder="0812-xxxx-xxxx"
                                    class="block w-full px-3.5 py-3 bg-[#FAF6F0]/60 border border-stone-200 rounded-xl text-sm text-[#1F2937] focus:outline-none focus:bg-white focus:border-[#1B5E3A] focus:ring-4 focus:ring-[#1B5E3A]/10 transition"
                                >
                            </div>
                        </div>
                    </div>
                </template>

                <!-- COMMON FIELDS: PASSWORD & CONFIRM PASSWORD -->
                <div>
                    <label for="reg_password" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">
                        Kata Sandi <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative rounded-xl shadow-2xs">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input 
                            :type="showPassword ? 'text' : 'password'" 
                            id="reg_password" 
                            name="password" 
                            x-model="password"
                            required 
                            minlength="8"
                            placeholder="Minimal 8 karakter"
                            class="block w-full pl-10 pr-11 py-3 bg-[#FAF6F0]/60 border border-stone-200 rounded-xl text-sm text-[#1F2937] placeholder-stone-400 focus:outline-none focus:bg-white focus:border-[#1B5E3A] focus:ring-4 focus:ring-[#1B5E3A]/10 transition duration-150"
                        >
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

                    <!-- Password Strength Meter Bar -->
                    <div x-show="password.length > 0" class="mt-2" x-cloak>
                        <div class="h-1.5 w-full bg-stone-200 rounded-full overflow-hidden flex gap-1">
                            <div class="h-full transition-all duration-300 rounded-full"
                                 :class="{
                                     'w-1/4 bg-rose-500': passwordScore === 1,
                                     'w-2/4 bg-amber-500': passwordScore === 2,
                                     'w-3/4 bg-blue-500': passwordScore === 3,
                                     'w-full bg-emerald-500': passwordScore >= 4,
                                 }"></div>
                        </div>
                        <div class="flex items-center justify-between text-[11px] text-stone-500 mt-1">
                            <span>Kekuatan kata sandi:</span>
                            <span class="font-bold"
                                  :class="{
                                      'text-rose-600': passwordScore === 1,
                                      'text-amber-600': passwordScore === 2,
                                      'text-blue-600': passwordScore === 3,
                                      'text-emerald-600': passwordScore >= 4,
                                  }"
                                  x-text="['Sangat Lemah', 'Lemah', 'Sedang', 'Kuat', 'Sangat Kuat'][passwordScore] || 'Lemah'"></span>
                        </div>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="reg_password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">
                        Konfirmasi Kata Sandi <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative rounded-xl shadow-2xs">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <input 
                            :type="showConfirmPassword ? 'text' : 'password'" 
                            id="reg_password_confirmation" 
                            name="password_confirmation" 
                            x-model="passwordConfirmation"
                            required 
                            placeholder="Ketik ulang kata sandi"
                            class="block w-full pl-10 pr-11 py-3 bg-[#FAF6F0]/60 border rounded-xl text-sm text-[#1F2937] placeholder-stone-400 focus:outline-none focus:bg-white focus:ring-4 transition duration-150"
                            :class="!passwordsMatch ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/10' : 'border-stone-200 focus:border-[#1B5E3A] focus:ring-[#1B5E3A]/10'"
                        >
                        <button 
                            type="button" 
                            @click="showConfirmPassword = !showConfirmPassword" 
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-stone-400 hover:text-stone-600 focus:outline-none"
                            aria-label="Toggle confirm password visibility"
                        >
                            <svg x-show="!showConfirmPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="showConfirmPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                    <p x-show="!passwordsMatch" class="text-rose-500 text-xs mt-1" x-cloak>Kata sandi tidak sama.</p>
                </div>

                <!-- Terms and Policy Checkbox -->
                <div class="pt-2">
                    <label class="flex items-start gap-2.5 cursor-pointer select-none">
                        <input 
                            type="checkbox" 
                            name="terms" 
                            x-model="termsAccepted"
                            required
                            class="w-4 h-4 rounded text-[#1B5E3A] border-stone-300 focus:ring-[#1B5E3A] accent-[#1B5E3A] mt-0.5"
                        >
                        <span class="text-xs text-stone-600 leading-snug">
                            Saya menyetujui <a href="#" class="font-bold text-[#1B5E3A] hover:underline">Syarat & Ketentuan</a> serta <a href="#" class="font-bold text-[#1B5E3A] hover:underline">Kebijakan Privasi</a> What's Left.
                        </span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full mt-3 py-3.5 px-4 bg-[#1B5E3A] hover:bg-[#14472C] text-white font-bold text-sm rounded-xl shadow-lg shadow-[#1B5E3A]/25 hover:shadow-xl hover:shadow-[#1B5E3A]/30 active:scale-[0.99] transition duration-200 flex items-center justify-center gap-2 group cursor-pointer"
                >
                    <span x-text="accountType === 'consumer' ? 'Daftar Sebagai Konsumen' : 'Daftar Sebagai Mitra Bisnis'"></span>
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>

            <!-- Social Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-stone-200"></div>
                </div>
                <div class="relative flex justify-center text-xs uppercase">
                    <span class="bg-white px-3 text-stone-400 font-medium tracking-wider">Atau daftar dengan</span>
                </div>
            </div>

            <!-- Google OAuth Button -->
            <button 
                type="button" 
                @click="notification = 'Simulasi pendaftaran cepat dengan akun Google!'"
                class="w-full py-3 px-4 bg-white hover:bg-stone-50 border border-stone-200 text-stone-700 text-sm font-semibold rounded-xl flex items-center justify-center gap-3 transition shadow-2xs hover:border-stone-300 active:scale-[0.99]"
            >
                <svg class="w-4 h-4" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.66-5.17 3.66-9.17z"/>
                    <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.27 21.41 7.36 24 12 24z"/>
                    <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.25C.45 8.18 0 9.98 0 12s.45 3.82 1.25 5.42l4.03-3.15z"/>
                    <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.36 0 3.27 2.59 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
                </svg>
                <span>Daftar Cepat dengan Google</span>
            </button>

            <!-- Bottom Login Link -->
            <div class="mt-8 pt-6 border-t border-stone-100 text-center text-sm text-stone-600">
                <span>Sudah memiliki akun What's Left?</span>
                <a href="{{ Route::has('login') ? route('login') : url('/?view=login') }}" class="font-bold text-[#1B5E3A] hover:text-[#14472C] ml-1 hover:underline">
                    Masuk di Sini
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
