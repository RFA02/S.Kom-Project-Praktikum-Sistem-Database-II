@php
    $view = request()->query('view', 'landing');
@endphp

@if ($view === 'register')
    {!! view('auth.register')->render() !!}
@elseif ($view === 'login')
    {!! view('auth.login')->render() !!}
@elseif ($view === 'dashboard' || $view === 'business' || $view === 'merchant')
    {!! view('business.dashboard')->render() !!}
@elseif ($view === 'makanan-surplus' || $view === 'surplus-listings' || $view === 'surplus' || $view === 'daftar-makanan-surplus')
    {!! view('business.surplus-listings')->render() !!}
@elseif ($view === 'food-management' || $view === 'manajemen-makanan')
    {!! view('business.food-management')->render() !!}
@elseif ($view === 'explore' || $view === 'jelajahi' || $view === 'explore-foods' || $view === 'makanan')
    {!! view('consumer.explore')->render() !!}
@elseif ($view === 'food-detail' || $view === 'food-details' || $view === 'detail-makanan' || $view === 'detail')
    {!! view('consumer.food-details')->render() !!}
@elseif ($view === 'reservation' || $view === 'reservasi' || $view === 'order' || $view === 'checkout' || $view === 'pesan')
    {!! view('consumer.reservation')->render() !!}
@elseif ($view === 'my-reservations' || $view === 'reservations' || $view === 'reservasi-saya' || $view === 'riwayat-reservasi')
    {!! view('consumer.my-reservations')->render() !!}
@else
    {!! view('landing')->render() !!}
@endif
