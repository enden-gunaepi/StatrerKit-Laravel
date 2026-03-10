@extends('layouts.master-without-nav')

@section('title', 'Sign In')

@section('content')
    <main class="mx-auto flex min-h-[calc(100vh-2rem)] w-full max-w-6xl items-center px-4 pb-12 pt-6 md:px-8">
        <section class="w-full overflow-hidden rounded-[28px] border border-slate-300 bg-white shadow-[0_20px_60px_rgba(2,6,23,0.18)]">
            <div class="grid min-h-[620px] md:grid-cols-[0.92fr_1.08fr]">
                <aside class="relative overflow-hidden bg-black p-8 text-white md:p-10">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,#2a2a2a_0%,#0a0a0a_42%,#000_100%)]"></div>
                    <div class="absolute -left-10 top-10 h-56 w-56 rounded-full bg-white/10 blur-3xl"></div>
                    <div class="absolute -bottom-16 right-0 h-64 w-64 rounded-full bg-slate-500/20 blur-3xl"></div>

                    <div class="relative z-10 flex h-full flex-col justify-between">
                        <span class="inline-flex w-fit rounded-full border border-white/30 px-3 py-1 text-xs tracking-[0.16em] text-white/80">ACCOUNT</span>

                        <div>
                            <h2 class="max-w-xs text-4xl font-semibold leading-tight">Welcome back</h2>
                            <p class="mt-3 max-w-xs text-base text-white/70">Sign in to continue your workspace securely.</p>
                        </div>

                        <p class="text-xs text-white/50">Modern Auth Experience</p>
                    </div>
                </aside>

                <div class="flex items-center justify-center bg-white px-6 py-10 md:px-10">
                    <div class="w-full max-w-md">
                        <h1 class="text-center text-4xl font-semibold tracking-tight text-black">Sign In</h1>
                        <p class="mt-2 text-center text-sm text-slate-500">Use your account credentials</p>

                        <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-4">
                            @csrf
                            <div>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" class="h-11 w-full rounded-lg border border-slate-200 px-3 text-sm text-slate-900 outline-none transition focus:border-black @error('email') border-rose-300 @enderror" placeholder="Email address" required>
                                @error('email')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <input id="password" name="password" type="password" class="h-11 w-full rounded-lg border border-slate-200 px-3 text-sm text-slate-900 outline-none transition focus:border-black @error('password') border-rose-300 @enderror" placeholder="Password" required>
                                @error('password')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="h-11 w-full rounded-lg bg-black text-sm font-medium text-white transition hover:bg-slate-800">
                                Sign in
                            </button>
                        </form>

                        <div class="my-6 h-px bg-slate-200"></div>

                        <div class="space-y-3">
                            <button type="button" class="h-10 w-full rounded-lg border border-slate-200 bg-white text-sm font-medium text-slate-700 transition hover:border-slate-300">Continue with Google</button>
                            <button type="button" class="h-10 w-full rounded-lg border border-black bg-black text-sm font-medium text-white transition hover:bg-slate-800">Continue with Apple</button>
                        </div>

                        <p class="mt-6 text-center text-sm text-slate-500">
                            Don't have an account?
                            <a href="{{ route('register.form') }}" class="font-medium text-black underline">Sign up</a>
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
