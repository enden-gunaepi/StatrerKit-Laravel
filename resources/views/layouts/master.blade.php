<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $favicon = get_setting('favicon');
        $appName = get_setting('app_name', config('app.name', 'Larakit12'));
    @endphp

    <title>@yield('title', __('ui.dashboard')) - {{ $appName }}</title>

    @if ($favicon)
        <link rel="shortcut icon" href="{{ asset('storage/' . $favicon) }}" type="image/x-icon">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen">
    @php
        $menus = config('sidebar-data', []);
        $user = auth()->user();
        $currentLocale = app()->getLocale();
        $isAdmin = $user?->roles?->pluck('id')->contains(1);
        $userPermissions = $user?->roles?->flatMap->permissions->pluck('name')->unique() ?? collect();

        $flatMenus = collect();

        foreach ($menus as $group) {
            foreach ($group['menus'] as $menu) {
                $permission = $menu['permission'] ?? null;
                $canAccess = !$permission || $isAdmin || $userPermissions->contains($permission);
                if (!$canAccess || !empty($menu['submenus'])) {
                    continue;
                }

                if (($menu['href'] ?? '#') === '#') {
                    continue;
                }

                $dataKey = $menu['data_key'] ?? 'menu';
                if (!in_array($dataKey, ['dashboards', 'users', 'roles', 'logs', 'settings', 'landing-page'], true)) {
                    continue;
                }

                $flatMenus->push([
                    'title' => $menu['title'],
                    'href' => $menu['href'],
                    'data_key' => $dataKey,
                ]);
            }
        }

        $iconMap = [
            'dashboards' => 'M3 10.5 12 3l9 7.5V21a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-10.5Z',
            'users' => 'M16 19a4 4 0 0 0-8 0M12 13a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z',
            'roles' =>
                'M10 3H5a2 2 0 0 0-2 2v5M14 21h5a2 2 0 0 0 2-2v-5M21 10V5a2 2 0 0 0-2-2h-5M3 14v5a2 2 0 0 0 2 2h5',
            'logs' => 'M7 3h7l5 5v13a1 1 0 0 1-1 1H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Zm7 1v5h5',
            'landing-page' => 'M4 5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v4H4V5Zm0 6h16v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-8Zm3 2h4v2H7v-2Zm0 4h7v2H7v-2Z',
            'settings' =>
                'M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm9 4-.9.3a7.8 7.8 0 0 1-.3 1l.6.8a1 1 0 0 1-.1 1.2l-1.1 1.1a1 1 0 0 1-1.2.1l-.8-.6a7.8 7.8 0 0 1-1 .4l-.3.9a1 1 0 0 1-1 .7h-1.6a1 1 0 0 1-1-.7l-.3-.9a7.8 7.8 0 0 1-1-.4l-.8.6a1 1 0 0 1-1.2-.1l-1.1-1.1a1 1 0 0 1-.1-1.2l.6-.8a7.8 7.8 0 0 1-.4-1L3 12a1 1 0 0 1 0-1.1l.9-.3a7.8 7.8 0 0 1 .4-1l-.6-.8a1 1 0 0 1 .1-1.2l1.1-1.1a1 1 0 0 1 1.2-.1l.8.6a7.8 7.8 0 0 1 1-.4l.3-.9a1 1 0 0 1 1-.7h1.6a1 1 0 0 1 1 .7l.3.9a7.8 7.8 0 0 1 1 .4l.8-.6a1 1 0 0 1 1.2.1l1.1 1.1a1 1 0 0 1 .1 1.2l-.6.8a7.8 7.8 0 0 1 .4 1l.9.3a1 1 0 0 1 0 1.1Z',
        ];
    @endphp

    <div id="page-loader"
        class="pointer-events-none fixed inset-0 z-[100] flex items-center justify-center bg-white/70 opacity-0 backdrop-blur-sm transition-opacity duration-200">
        <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-5 py-3 shadow-sm">
            <span class="h-4 w-4 animate-spin rounded-full border-2 border-slate-300 border-t-slate-800"></span>
            <span class="text-sm font-medium text-slate-700">{{ __('ui.loading') }}</span>
        </div>
    </div>

    <div class="w-full px-2 py-2 md:px-4 md:py-4">
        <div class="app-layout">
            <aside class="sidebar-rail">
                <nav class="sidebar-rail-top">
                    @foreach ($flatMenus as $menu)
                        @php
                            $active = request()->is($menu['href'] . '*');
                            $iconPath =
                                $iconMap[$menu['data_key']] ??
                                'M12 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2Zm0 5a1 1 0 1 1 0 2 1 1 0 0 1 0-2Zm0 5a1 1 0 1 1 0 2 1 1 0 0 1 0-2Z';
                        @endphp
                        <a href="{{ url('/' . ltrim($menu['href'], '/')) }}"
                            class="icon-rail-btn {{ $active ? 'icon-rail-btn-active' : 'icon-rail-btn-idle' }}"
                            title="{{ $menu['title'] }}">
                            <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="{{ $iconPath }}"></path>
                            </svg>
                        </a>
                    @endforeach
                </nav>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="icon-rail-btn icon-rail-btn-idle" title="{{ __('ui.logout') }}">
                        <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <path d="M16 17l5-5-5-5"></path>
                            <path d="M21 12H9"></path>
                        </svg>
                    </button>
                </form>
            </aside>

            <div class="content-shell">
                <header class="app-navbar">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <button id="mobileMenuButton" type="button"
                                class="icon-rail-btn icon-rail-btn-idle lg:hidden" aria-label="Open Menu">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round">
                                    <path d="M4 7h16"></path>
                                    <path d="M4 12h16"></path>
                                    <path d="M4 17h16"></path>
                                </svg>
                            </button>
                            <div>
                                <h1 class="text-xl font-semibold text-slate-900">@yield('title', __('ui.dashboard'))</h1>
                                <p class="text-xs text-slate-400">{{ now()->format('l, d M Y') }}</p>
                            </div>
                        </div>

                        <div class="flex w-full items-center gap-2 sm:w-auto">
                            <div class="relative w-full sm:w-64">
                                <span
                                    class="pointer-events-none absolute inset-y-0 left-3 grid place-items-center text-slate-400">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="m21 21-4.3-4.3"></path>
                                        <circle cx="11" cy="11" r="7"></circle>
                                    </svg>
                                </span>
                                <input class="mac-input pl-9" placeholder="{{ __('ui.search') }}" />
                            </div>
                            <div class="flex items-center gap-1 rounded-xl border border-slate-200 bg-white p-1">
                                <a href="{{ route('lang.switch', 'en') }}"
                                    class="rounded-lg px-2 py-1 text-xs {{ $currentLocale === 'en' ? 'bg-black text-white' : 'text-slate-600' }}">EN</a>
                                <a href="{{ route('lang.switch', 'id') }}"
                                    class="rounded-lg px-2 py-1 text-xs {{ $currentLocale === 'id' ? 'bg-black text-white' : 'text-slate-600' }}">ID</a>
                            </div>
                            <a href="{{ route('users.profile') }}"
                                class="mac-btn whitespace-nowrap">{{ $user->name }}</a>
                        </div>
                    </div>
                </header>

                <main class="app-main">
                    @if (session('success'))
                        <div
                            class="mb-3 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-700">
                            {{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="mb-3 rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-sm text-rose-700">
                            {{ session('error') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="mb-3 rounded-xl border border-rose-200 bg-rose-50 p-3 text-sm text-rose-700">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </main>

                <footer class="app-footer">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <p>{{ $appName }} Dashboard</p>
                        <p>&copy; {{ now()->format('Y') }} {{ __('ui.all_rights_reserved') }}</p>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <div id="mobileNav" class="mobile-nav">
        <div id="mobileNavBackdrop" class="absolute inset-0 bg-slate-900/40"></div>
        <div class="relative h-full w-72 bg-white p-4 shadow-xl">
            <div class="mb-4 flex items-center justify-between">
                <p class="text-base font-semibold text-slate-900">{{ $appName }}</p>
                <button id="mobileNavClose" class="icon-rail-btn icon-rail-btn-idle" type="button"
                    aria-label="Close Menu">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round">
                        <path d="M6 6l12 12"></path>
                        <path d="M18 6l-12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="mb-2 flex items-center gap-1 rounded-xl border border-slate-200 bg-white p-1">
                <a href="{{ route('lang.switch', 'en') }}"
                    class="rounded-lg px-2 py-1 text-xs {{ $currentLocale === 'en' ? 'bg-black text-white' : 'text-slate-600' }}">EN</a>
                <a href="{{ route('lang.switch', 'id') }}"
                    class="rounded-lg px-2 py-1 text-xs {{ $currentLocale === 'id' ? 'bg-black text-white' : 'text-slate-600' }}">ID</a>
            </div>
            <nav class="space-y-1">
                @foreach ($flatMenus as $menu)
                    <a href="{{ url('/' . ltrim($menu['href'], '/')) }}"
                        class="block rounded-xl px-3 py-2 text-sm {{ request()->is($menu['href'] . '*') ? 'bg-black text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                        {{ $menu['title'] }}
                    </a>
                @endforeach
            </nav>
            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button type="submit" class="mac-btn w-full">{{ __('ui.logout') }}</button>
            </form>
        </div>
    </div>

    <script>
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileNav = document.getElementById('mobileNav');
        const mobileNavBackdrop = document.getElementById('mobileNavBackdrop');
        const mobileNavClose = document.getElementById('mobileNavClose');

        function closeMobileNav() {
            if (!mobileNav) return;
            mobileNav.classList.remove('is-open');
        }

        function openMobileNav() {
            if (!mobileNav) return;
            mobileNav.classList.add('is-open');
        }

        mobileMenuButton?.addEventListener('click', openMobileNav);
        mobileNavBackdrop?.addEventListener('click', closeMobileNav);
        mobileNavClose?.addEventListener('click', closeMobileNav);
    </script>

    @stack('scripts')
</body>

</html>
