<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $appName = get_setting('app_name', config('app.name', 'Larakit12'));
        $favicon = get_setting('favicon');
    @endphp

    <title>{{ $appName }}</title>

    @if ($favicon)
        <link rel="shortcut icon" href="{{ asset('storage/' . $favicon) }}" type="image/x-icon">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#060606] text-white antialiased">
    <header class="sticky top-0 z-40 border-b border-white/10 bg-black/85 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 md:px-8">
            <a href="#" class="text-lg font-semibold tracking-wide">{{ $appName }}</a>
            <nav class="hidden items-center gap-5 text-sm text-white/80 md:flex">
                @foreach ($sections as $section)
                    <a href="#{{ $section['key'] }}" class="transition hover:text-white">{{ $section['label'] }}</a>
                @endforeach
            </nav>
            <div class="flex items-center gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-full border border-white/20 px-4 py-2 text-xs font-medium text-white/90 hover:border-white/40">Dashboard</a>
                @else
                    <a href="{{ route('login.form') }}" class="rounded-full border border-white/20 px-4 py-2 text-xs font-medium text-white/90 hover:border-white/40">Login</a>
                @endauth
                <a href="#contact" class="rounded-full bg-[#ff7a18] px-4 py-2 text-xs font-semibold text-black">Contact</a>
            </div>
        </div>
    </header>

    <main>
        @foreach ($sections as $section)
            @if ($section['key'] === 'hero')
                <section id="hero" class="relative overflow-hidden">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_25%_20%,rgba(255,122,24,0.14),rgba(0,0,0,0)_40%)]"></div>
                    <div class="mx-auto grid max-w-7xl gap-8 px-4 py-20 md:grid-cols-2 md:px-8 md:py-28">
                        <div class="relative z-10">
                            <p class="inline-flex rounded-full border border-white/20 px-3 py-1 text-xs uppercase tracking-[0.2em] text-white/70">{{ $content['hero']['badge'] }}</p>
                            <h1 class="mt-4 text-4xl font-semibold leading-tight md:text-6xl">{{ $content['hero']['title'] }}</h1>
                            <p class="mt-5 max-w-xl text-base text-white/70 md:text-lg">{{ $content['hero']['subtitle'] }}</p>
                            <div class="mt-8 flex flex-wrap gap-3">
                                <a href="{{ $content['hero']['primary_link'] }}" class="rounded-full bg-[#ff7a18] px-6 py-3 text-sm font-semibold text-black">{{ $content['hero']['primary_text'] }}</a>
                                <a href="{{ $content['hero']['secondary_link'] }}" class="rounded-full border border-white/20 px-6 py-3 text-sm font-medium text-white">{{ $content['hero']['secondary_text'] }}</a>
                            </div>
                        </div>
                        <div class="relative z-10 rounded-3xl border border-white/10 bg-gradient-to-br from-white/10 to-transparent p-6">
                            <div class="h-full min-h-[280px] rounded-2xl border border-white/10 bg-black/40"></div>
                        </div>
                    </div>
                </section>
            @endif

            @if ($section['key'] === 'trusted_by')
                <section id="trusted_by" class="border-y border-white/10 bg-black/40">
                    <div class="mx-auto max-w-7xl px-4 py-10 md:px-8">
                        <p class="mb-4 text-sm uppercase tracking-[0.16em] text-white/50">{{ $content['trusted_by']['title'] }}</p>
                        <div class="grid gap-3 sm:grid-cols-2 md:grid-cols-5">
                            @foreach ($content['trusted_by']['logos'] as $logo)
                                <div class="rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-center text-sm font-semibold text-white/80">{{ $logo }}</div>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif

            @if ($section['key'] === 'about')
                <section id="about" class="mx-auto max-w-7xl px-4 py-16 md:px-8">
                    <div class="grid gap-6 md:grid-cols-[1.3fr,1fr]">
                        <div>
                            <p class="text-xs uppercase tracking-[0.16em] text-[#ff7a18]">About</p>
                            <h2 class="mt-2 text-3xl font-semibold md:text-4xl">{{ $content['about']['title'] }}</h2>
                            <p class="mt-4 text-white/70">{{ $content['about']['text'] }}</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-6 text-white/90">{{ $content['about']['highlight'] }}</div>
                    </div>
                </section>
            @endif

            @if ($section['key'] === 'services')
                <section id="services" class="mx-auto max-w-7xl px-4 py-16 md:px-8">
                    <h2 class="text-3xl font-semibold md:text-4xl">{{ $content['services']['title'] }}</h2>
                    <div class="mt-6 grid gap-4 md:grid-cols-3">
                        @foreach ($content['services']['items'] as $item)
                            <article class="rounded-2xl border border-white/10 bg-white/5 p-5">
                                <h3 class="text-lg font-semibold">{{ $item['title'] }}</h3>
                                <p class="mt-2 text-sm text-white/70">{{ $item['description'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($section['key'] === 'features')
                <section id="features" class="mx-auto max-w-7xl px-4 py-16 md:px-8">
                    <h2 class="text-3xl font-semibold md:text-4xl">{{ $content['features']['title'] }}</h2>
                    <div class="mt-6 grid gap-4 md:grid-cols-3">
                        @foreach ($content['features']['items'] as $item)
                            <article class="rounded-2xl border border-white/10 bg-black p-5">
                                <h3 class="text-lg font-semibold">{{ $item['title'] }}</h3>
                                <p class="mt-2 text-sm text-white/70">{{ $item['description'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($section['key'] === 'portfolio')
                <section id="portfolio" class="mx-auto max-w-7xl px-4 py-16 md:px-8">
                    <h2 class="text-3xl font-semibold md:text-4xl">{{ $content['portfolio']['title'] }}</h2>
                    <div class="mt-6 grid gap-4 md:grid-cols-3">
                        @foreach ($content['portfolio']['items'] as $item)
                            <article class="rounded-2xl border border-white/10 bg-white/5 p-5">
                                <h3 class="text-lg font-semibold">{{ $item['title'] }}</h3>
                                <p class="mt-2 text-sm text-white/70">{{ $item['description'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($section['key'] === 'testimonials')
                <section id="testimonials" class="mx-auto max-w-7xl px-4 py-16 md:px-8">
                    <h2 class="text-3xl font-semibold md:text-4xl">{{ $content['testimonials']['title'] }}</h2>
                    <div class="mt-6 grid gap-4 md:grid-cols-2">
                        @foreach ($content['testimonials']['items'] as $item)
                            <article class="rounded-2xl border border-white/10 bg-white/5 p-5">
                                <p class="text-sm text-white/80">"{{ $item['quote'] }}"</p>
                                <p class="mt-3 text-sm font-semibold">{{ $item['name'] }}</p>
                                <p class="text-xs text-white/60">{{ $item['role'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($section['key'] === 'pricing')
                <section id="pricing" class="mx-auto max-w-7xl px-4 py-16 md:px-8">
                    <h2 class="text-3xl font-semibold md:text-4xl">{{ $content['pricing']['title'] }}</h2>
                    <div class="mt-6 grid gap-4 md:grid-cols-3">
                        @foreach ($content['pricing']['items'] as $item)
                            <article class="rounded-2xl border border-white/10 bg-white/5 p-5">
                                <p class="text-sm text-white/60">{{ $item['plan'] }}</p>
                                <p class="mt-2 text-3xl font-semibold">{{ $item['price'] }}</p>
                                <ul class="mt-4 space-y-2 text-sm text-white/70">
                                    @foreach ($item['features'] as $feature)
                                        <li>- {{ $feature }}</li>
                                    @endforeach
                                </ul>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($section['key'] === 'faq')
                <section id="faq" class="mx-auto max-w-7xl px-4 py-16 md:px-8">
                    <h2 class="text-3xl font-semibold md:text-4xl">{{ $content['faq']['title'] }}</h2>
                    <div class="mt-6 space-y-3">
                        @foreach ($content['faq']['items'] as $item)
                            <article class="rounded-xl border border-white/10 bg-white/5 p-4">
                                <p class="font-semibold">{{ $item['question'] }}</p>
                                <p class="mt-2 text-sm text-white/70">{{ $item['answer'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($section['key'] === 'cta')
                <section id="cta" class="mx-auto max-w-7xl px-4 py-16 md:px-8">
                    <div class="rounded-3xl border border-[#ff7a18]/30 bg-[#ff7a18]/10 p-8 text-center">
                        <h2 class="text-3xl font-semibold md:text-4xl">{{ $content['cta']['title'] }}</h2>
                        <p class="mx-auto mt-3 max-w-2xl text-white/80">{{ $content['cta']['text'] }}</p>
                        <a href="{{ $content['cta']['button_link'] }}" class="mt-6 inline-flex rounded-full bg-[#ff7a18] px-6 py-3 text-sm font-semibold text-black">{{ $content['cta']['button_text'] }}</a>
                    </div>
                </section>
            @endif

            @if ($section['key'] === 'contact')
                <section id="contact" class="mx-auto max-w-7xl px-4 py-16 md:px-8">
                    <h2 class="text-3xl font-semibold md:text-4xl">{{ $content['contact']['title'] }}</h2>
                    <div class="mt-6 grid gap-3 md:grid-cols-3">
                        <div class="rounded-xl border border-white/10 bg-white/5 p-4">{{ $content['contact']['email'] }}</div>
                        <div class="rounded-xl border border-white/10 bg-white/5 p-4">{{ $content['contact']['phone'] }}</div>
                        <div class="rounded-xl border border-white/10 bg-white/5 p-4">{{ $content['contact']['address'] }}</div>
                    </div>
                </section>
            @endif

            @if ($section['key'] === 'footer')
                <footer id="footer" class="border-t border-white/10 bg-black/80">
                    <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-8 md:px-8">
                        <p class="text-sm text-white/60">{{ $content['footer']['text'] }}</p>
                        <div class="flex items-center gap-4 text-sm text-white/70">
                            @foreach ($content['footer']['links'] as $link)
                                <a href="{{ $link['url'] }}" class="hover:text-white">{{ $link['label'] }}</a>
                            @endforeach
                        </div>
                    </div>
                </footer>
            @endif
        @endforeach
    </main>
</body>

</html>
