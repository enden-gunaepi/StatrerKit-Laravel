@extends('layouts.master-without-nav')

@section('title', 'Welcome')

@section('content')
    <main class="mx-auto flex min-h-screen max-w-6xl items-center px-4 py-12 md:px-8">
        <section class="grid w-full gap-6 md:grid-cols-[1.2fr,1fr]">
            <div class="rounded-3xl border border-slate-200 bg-white/80 p-8 shadow-sm backdrop-blur md:p-12">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Laravel 12 + Tailwind</p>
                <h1 class="mt-4 text-3xl font-semibold leading-tight text-slate-900 md:text-5xl">Minimal, tenang, dan fokus untuk manajemen user.</h1>
                <p class="mt-4 max-w-xl text-sm text-slate-600 md:text-base">
                    Starter panel ini dirancang dengan pola antarmuka ringan ala desktop modern. Semua alur utama sudah siap:
                    autentikasi, role, permission, logs, dan pengaturan sistem.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    @guest
                        <a href="{{ route('login.form') }}" class="mac-btn-primary">Masuk</a>
                        <a href="{{ route('register.form') }}" class="mac-btn">Daftar</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="mac-btn-primary">Ke Dashboard</a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="mac-btn">Logout</button>
                        </form>
                    @endguest
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-gradient-to-b from-slate-900 to-slate-700 p-8 text-white shadow-sm md:p-10">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-300">Base Kit</p>
                <ul class="mt-6 space-y-4 text-sm text-slate-200">
                    <li class="rounded-xl bg-white/10 p-3">Role dan permission berbasis Gate.</li>
                    <li class="rounded-xl bg-white/10 p-3">Audit log aktivitas pengguna.</li>
                    <li class="rounded-xl bg-white/10 p-3">Setting aplikasi dan identitas brand.</li>
                </ul>
                <p class="mt-6 text-xs text-slate-300">{{ now()->format('Y') }} {{ get_setting('app_name', config('app.name')) }}</p>
            </div>
        </section>
    </main>
@endsection
