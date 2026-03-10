@extends('layouts.master-without-nav')

@section('title', 'Sign Up')

@section('content')
    <main class="mx-auto flex min-h-[calc(100vh-2rem)] w-full max-w-6xl items-center px-4 pb-12 pt-6 md:px-8">
        <section class="w-full overflow-hidden rounded-[28px] border border-slate-300 bg-white shadow-[0_20px_60px_rgba(2,6,23,0.18)]">
            <div class="grid min-h-[620px] md:grid-cols-[0.92fr_1.08fr]">
                <aside class="relative overflow-hidden bg-black p-8 text-white md:p-10">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_10%,#2a2a2a_0%,#0b0b0b_45%,#000_100%)]"></div>
                    <div class="absolute -left-10 top-14 h-56 w-56 rounded-full bg-white/10 blur-3xl"></div>
                    <div class="absolute -bottom-16 right-0 h-64 w-64 rounded-full bg-slate-500/20 blur-3xl"></div>

                    <div class="relative z-10 flex h-full flex-col justify-between">
                        <span class="inline-flex w-fit rounded-full border border-white/30 px-3 py-1 text-xs tracking-[0.16em] text-white/80">CREATE</span>

                        <div>
                            <h2 class="max-w-xs text-4xl font-semibold leading-tight">Create your account</h2>
                            <p class="mt-3 max-w-xs text-base text-white/70">Start collaborating with a clean and secure workspace.</p>
                        </div>

                        <p class="text-xs text-white/50">Elegant Black & White UI</p>
                    </div>
                </aside>

                <div class="flex items-center justify-center bg-white px-6 py-10 md:px-10">
                    <div class="w-full max-w-md">
                        <h1 class="text-center text-4xl font-semibold tracking-tight text-black">Sign Up</h1>
                        <p class="mt-2 text-center text-sm text-slate-500">Create your account in seconds</p>

                        <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-4">
                            @csrf
                            <div>
                                <input id="name" name="name" type="text" value="{{ old('name') }}" class="h-11 w-full rounded-lg border border-slate-200 px-3 text-sm text-slate-900 outline-none transition focus:border-black @error('name') border-rose-300 @enderror" placeholder="Full name" required>
                                @error('name')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

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

                            <div>
                                <input id="password_confirmation" name="password_confirmation" type="password" class="h-11 w-full rounded-lg border border-slate-200 px-3 text-sm text-slate-900 outline-none transition focus:border-black" placeholder="Confirm password" required>
                            </div>

                            <label class="flex items-start gap-2 text-xs text-slate-500">
                                <input type="checkbox" class="mt-0.5 rounded border-slate-300" required>
                                <span>I agree to terms and conditions.</span>
                            </label>

                            <button type="submit" class="h-11 w-full rounded-lg bg-black text-sm font-medium text-white transition hover:bg-slate-800">
                                Join now
                            </button>
                        </form>

                        <div class="my-6 h-px bg-slate-200"></div>

                        <div class="space-y-3">
                            <button type="button" class="h-10 w-full rounded-lg border border-slate-200 bg-white text-sm font-medium text-slate-700 transition hover:border-slate-300">Sign up with Google</button>
                            <button type="button" class="h-10 w-full rounded-lg border border-black bg-black text-sm font-medium text-white transition hover:bg-slate-800">Sign up with Apple</button>
                        </div>

                        <p class="mt-6 text-center text-sm text-slate-500">
                            Already have an account?
                            <a href="{{ route('login.form') }}" class="font-medium text-black underline">Sign in</a>
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
