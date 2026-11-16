@php
    $view = request()->query('view', 'landing');
@endphp

@if ($view === 'register')
    {!! view('auth.register')->render() !!}
@elseif ($view === 'login')
    {!! view('auth.login')->render() !!}
@elseif ($view === 'dashboard' || $view === 'business' || $view === 'merchant')
    {!! view('business.dashboard')->render() !!}
@else
    {!! view('landing')->render() !!}
@endif
