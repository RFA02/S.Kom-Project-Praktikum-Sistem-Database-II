@extends('layouts.auth')

@section('title', 'Atur Ulang Kata Sandi')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-2xl border border-stone-200 shadow-sm p-6 sm:p-8">
    <h1 class="text-2xl font-black text-stone-900">Atur ulang kata sandi</h1>
    <p class="text-sm text-stone-600 mt-1">Buat kata sandi baru (minimal 8 karakter).</p>

    @if ($errors->any())
        <div class="mt-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-sm text-rose-700">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required
                   class="block w-full px-4 py-3 bg-[#FAF6F0]/60 border border-stone-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A]">
        </div>
        <div>
            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">Kata sandi baru</label>
            <input id="password" type="password" name="password" required minlength="8" autocomplete="new-password"
                   class="block w-full px-4 py-3 bg-[#FAF6F0]/60 border border-stone-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A]">
        </div>
        <div>
            <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">Ulangi kata sandi</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8" autocomplete="new-password"
                   class="block w-full px-4 py-3 bg-[#FAF6F0]/60 border border-stone-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A]">
        </div>
        <button type="submit" class="w-full py-3 rounded-xl bg-[#1B5E3A] hover:bg-[#14472C] text-white text-sm font-bold">Simpan Kata Sandi</button>
    </form>
</div>
@endsection
