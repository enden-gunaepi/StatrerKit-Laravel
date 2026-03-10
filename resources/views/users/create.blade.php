@extends('layouts.master')

@section('title', 'Create User')

@section('content')
    <section class="glass-card p-5">
        <h2 class="text-lg font-semibold text-slate-900">Create User</h2>
        <p class="mt-2 text-sm text-slate-500">Gunakan form Add User di halaman Users untuk menambahkan pengguna baru.</p>
        <div class="mt-4">
            <a href="{{ route('users.index') }}" class="mac-btn-primary">Go to Users</a>
        </div>
    </section>
@endsection
