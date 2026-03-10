<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $favicon = get_setting('favicon');
        $appName = get_setting('app_name', config('app.name', 'Larakit12'));
        $currentLocale = app()->getLocale();
    @endphp

    <title>@yield('title', 'Authentication') - {{ $appName }}</title>

    @if ($favicon)
        <link rel="shortcut icon" href="{{ asset('storage/' . $favicon) }}" type="image/x-icon">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[radial-gradient(circle_at_top,_#f8fafc,_#e2e8f0)]">
    <div id="page-loader" class="pointer-events-none fixed inset-0 z-[100] flex items-center justify-center bg-white/70 opacity-0 backdrop-blur-sm transition-opacity duration-200">
        <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-5 py-3 shadow-sm">
            <span class="h-4 w-4 animate-spin rounded-full border-2 border-slate-300 border-t-slate-800"></span>
            <span class="text-sm font-medium text-slate-700">{{ __('ui.loading') }}</span>
        </div>
    </div>

    <div class="mx-auto flex w-full max-w-6xl justify-end gap-1 px-4 pt-4 md:px-8">
        <a href="{{ route('lang.switch', 'en') }}" class="rounded-lg px-2 py-1 text-xs {{ $currentLocale === 'en' ? 'bg-black text-white' : 'bg-white text-slate-600 border border-slate-200' }}">EN</a>
        <a href="{{ route('lang.switch', 'id') }}" class="rounded-lg px-2 py-1 text-xs {{ $currentLocale === 'id' ? 'bg-black text-white' : 'bg-white text-slate-600 border border-slate-200' }}">ID</a>
    </div>

    @if (session('success'))
        <div class="mx-auto mt-4 max-w-3xl rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="mx-auto mt-4 max-w-3xl rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">{{ session('error') }}</div>
    @endif

    @yield('content')

    @stack('scripts')
</body>

</html>
