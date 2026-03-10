@extends('layouts.master-without-nav')

@section('title', 'Layouts Hovered')

@section('content')
    <main class="mx-auto flex min-h-screen max-w-3xl items-center justify-center px-4 py-12">
        <section class="w-full rounded-3xl border border-slate-200 bg-white/90 p-8 text-center shadow-sm">
            <h1 class="text-2xl font-semibold text-slate-900">Layouts Hovered</h1>
            <p class="mt-3 text-sm text-slate-500">Demo layout ini sudah menggunakan basis Tailwind baru.</p>
            <a href="{{ route('dashboard') }}" class="mac-btn-primary mt-6 inline-flex">Back to Dashboard</a>
        </section>
    </main>
@endsection
