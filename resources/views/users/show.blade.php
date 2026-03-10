@extends('layouts.master')

@section('title', 'User Detail')

@section('content')
    <section class="glass-card p-5">
        <h2 class="text-lg font-semibold text-slate-900">Detail Pengguna</h2>

        <div class="mt-4 grid gap-4 md:grid-cols-2">
            <div>
                <p class="mac-label">Name</p>
                <p class="text-sm text-slate-700">{{ $user->name }}</p>
            </div>
            <div>
                <p class="mac-label">Email</p>
                <p class="text-sm text-slate-700">{{ $user->email }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="mac-label">Roles</p>
                <div class="flex flex-wrap gap-2">
                    @foreach ($user->roles as $role)
                        <span class="status-pill bg-slate-100 text-slate-700">{{ ucfirst($role->name) }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
