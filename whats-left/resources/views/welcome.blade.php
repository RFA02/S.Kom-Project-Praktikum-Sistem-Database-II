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
@elseif ($view === 'production-records' || $view === 'produksi-harian' || $view === 'catatan-produksi' || $view === 'produksi')
    {!! view('business.production-records')->render() !!}
@elseif ($view === 'reservations-management' || $view === 'manajemen-reservasi' || $view === 'business-reservations' || $view === 'reservasi-bisnis')
    {!! view('business.reservations-management')->render() !!}
@elseif ($view === 'analytics' || $view === 'analitik' || $view === 'business-analytics')
    {!! view('business.analytics')->render() !!}
@elseif ($view === 'explore' || $view === 'jelajahi' || $view === 'explore-foods' || $view === 'makanan')
    {!! view('consumer.explore')->render() !!}
@elseif ($view === 'food-detail' || $view === 'food-details' || $view === 'detail-makanan' || $view === 'detail')
    {!! view('consumer.food-details')->render() !!}
@elseif ($view === 'reservation' || $view === 'reservasi' || $view === 'order' || $view === 'checkout' || $view === 'pesan')
    {!! view('consumer.reservation')->render() !!}
@elseif ($view === 'my-reservations' || $view === 'reservations' || $view === 'reservasi-saya' || $view === 'riwayat-reservasi')
    {!! view('consumer.my-reservations')->render() !!}
@elseif ($view === 'notifications' || $view === 'notifikasi')
    {!! view('consumer.notifications')->render() !!}
@elseif ($view === 'profile' || $view === 'profil' || $view === 'profil-saya' || $view === 'consumer-profile')
    {!! view('consumer.profile')->render() !!}
@else
    {!! view('landing')->render() !!}
@endif
