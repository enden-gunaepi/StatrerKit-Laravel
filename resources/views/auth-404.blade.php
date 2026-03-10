@extends('layouts.master-without-nav')

@section('title', '404')

@section('content')
    <main class="mx-auto flex min-h-screen max-w-4xl items-center justify-center px-4">
        <section class="w-full rounded-3xl border border-slate-200 bg-white/90 p-10 text-center shadow-sm backdrop-blur">
            <p class="text-sm font-medium uppercase tracking-[0.2em] text-slate-400">Error 404</p>
            <h1 class="mt-3 text-3xl font-semibold text-slate-900">Halaman tidak ditemukan</h1>
            <p class="mx-auto mt-3 max-w-lg text-sm text-slate-500">Halaman yang kamu buka tidak tersedia atau sudah dipindahkan.</p>
            <div class="mt-8">
                <a href="{{ auth()->check() ? route('dashboard') : route('login.form') }}" class="mac-btn-primary">Kembali</a>
            </div>
        </section>
    </main>
@endsection
