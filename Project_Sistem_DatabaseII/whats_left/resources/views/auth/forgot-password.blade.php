@extends('layouts.auth')

@section('title', 'Lupa Kata Sandi')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-2xl border border-stone-200 shadow-sm p-6 sm:p-8">
    <h1 class="text-2xl font-black text-stone-900">Lupa kata sandi?</h1>
    <p class="text-sm text-stone-600 mt-1">Masukkan email akunmu. Kami akan mengirim tautan untuk membuat kata sandi baru.</p>

    @if (session('success'))
        <div class="mt-4 p-3 rounded-xl bg-[#DCF2E4] border border-[#BBF7D0] text-sm font-semibold text-[#166534]">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="mt-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-sm text-rose-700">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-4">
        @csrf
        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="block w-full px-4 py-3 bg-[#FAF6F0]/60 border border-stone-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A]">
        </div>
        <button type="submit" class="w-full py-3 rounded-xl bg-[#1B5E3A] hover:bg-[#14472C] text-white text-sm font-bold">Kirim Tautan Reset</button>
        <a href="{{ route('login') }}" class="block text-center text-xs font-semibold text-[#1B5E3A] hover:underline">Kembali ke halaman masuk</a>
    </form>
</div>
@endsection
