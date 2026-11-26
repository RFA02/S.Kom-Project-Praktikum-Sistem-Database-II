@extends('layouts.main')

@section('title', 'Profil Saya - What\'s Left')

@section('content')
<main class="min-h-screen bg-[#FAF6F0] py-10">
    <div class="max-w-3xl mx-auto px-4 sm:px-6">
        <div class="mb-7">
            <p class="text-xs font-bold uppercase tracking-wider text-[#1B5E3A]">Akun Consumer</p>
            <h1 class="text-3xl font-black text-stone-900 mt-1">Profil Saya</h1>
            <p class="text-sm text-stone-600 mt-2">Perbarui informasi akun yang digunakan untuk reservasi.</p>
        </div>

        @if(session('success'))
            <div class="mb-5 p-3 rounded-xl bg-[#DCF2E4] border border-[#BBF7D0] text-sm font-semibold text-[#166534]">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('consumer.profile.update') }}" class="bg-white rounded-2xl border border-stone-200 shadow-sm p-6 sm:p-8 space-y-5">
            @csrf
            @method('PUT')

            <div class="flex items-center gap-4 pb-5 border-b border-stone-100">
                <div class="w-16 h-16 rounded-full bg-[#EBF5F0] text-[#1B5E3A] flex items-center justify-center text-2xl font-black">
                    {{ mb_substr($user->first_name ?? 'U', 0, 1) }}
                </div>
                <div>
                    <p class="font-extrabold text-stone-900">{{ $user->first_name }} {{ $user->last_name }}</p>
                    <p class="text-xs text-stone-500">{{ $user->email }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="first_name" class="block text-xs font-bold text-stone-700 mb-1.5">Nama Depan</label>
                    <input id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required
                           class="w-full px-3.5 py-3 rounded-xl border border-stone-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A]">
                    @error('first_name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="last_name" class="block text-xs font-bold text-stone-700 mb-1.5">Nama Belakang</label>
                    <input id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                           class="w-full px-3.5 py-3 rounded-xl border border-stone-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A]">
                    @error('last_name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-stone-700 mb-1.5">Email (tidak dapat diubah dari halaman ini)</label>
                <input id="email" type="email" value="{{ $user->email }}" readonly
                       class="w-full px-3.5 py-3 rounded-xl border border-stone-200 bg-stone-50 text-sm text-stone-500">
            </div>

            <div>
                <label for="phone" class="block text-xs font-bold text-stone-700 mb-1.5">Nomor Telepon</label>
                <input id="phone" name="phone" type="tel" value="{{ old('phone', $user->phone) }}"
                       class="w-full px-3.5 py-3 rounded-xl border border-stone-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A]">
                @error('phone') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <p class="text-[11px] text-stone-500">Alamat, foto profil, preferensi, dan metode pembayaran belum tersedia pada schema akun saat ini.</p>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('consumer.explore') }}" class="px-5 py-3 rounded-xl border border-stone-200 text-stone-700 text-xs font-bold">Kembali</a>
                <button type="submit" class="px-5 py-3 rounded-xl bg-[#1B5E3A] hover:bg-[#14472C] text-white text-xs font-bold">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</main>
@endsection
