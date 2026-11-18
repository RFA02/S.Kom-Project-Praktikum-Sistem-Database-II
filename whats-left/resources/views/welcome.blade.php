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
@else
    {!! view('landing')->render() !!}
@endif
