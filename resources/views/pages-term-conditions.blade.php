@extends('layouts.master-without-nav')

@section('title', 'Terms')

@section('content')
    <main class="mx-auto flex min-h-screen max-w-3xl items-center justify-center px-4 py-12">
        <section class="w-full rounded-3xl border border-slate-200 bg-white/90 p-8 shadow-sm backdrop-blur md:p-10">
            <h1 class="text-2xl font-semibold text-slate-900">Terms and Conditions</h1>
            <p class="mt-4 text-sm leading-relaxed text-slate-600">
                Halaman ini dapat kamu isi sesuai kebutuhan kebijakan penggunaan aplikasi. Tampilan sudah disesuaikan
                dengan gaya minimalis agar konsisten dengan seluruh sistem.
            </p>
            <div class="mt-8 flex gap-2">
                <a href="{{ route('register.form') }}" class="mac-btn-primary">Back to Register</a>
                <a href="{{ url('/') }}" class="mac-btn">Home</a>
            </div>
        </section>
    </main>
@endsection
