@extends('layouts.master')

@section('title', 'Profile')

@section('content')
    <section class="grid gap-4 lg:grid-cols-2">
        <article class="glass-card p-5">
            <h2 class="text-lg font-semibold text-slate-900">Profile Information</h2>
            <p class="mt-1 text-sm text-slate-500">Perbarui identitas akun kamu.</p>

            <form action="{{ route('update-profile') }}" method="POST" enctype="multipart/form-data" class="mt-5 space-y-4">
                @csrf

                <div>
                    <label class="mac-label">Current Photo</label>
                    <div class="flex items-center gap-3">
                        @if ($user->profile_picture)
                            <img src="{{ Storage::url($user->profile_picture) }}" alt="Profile" class="h-16 w-16 rounded-2xl object-cover">
                        @else
                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-200 text-lg font-semibold text-slate-500">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                        <input type="file" name="profile_picture" class="mac-input">
                    </div>
                </div>

                <div>
                    <label class="mac-label">Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="mac-input" required>
                </div>

                <div>
                    <label class="mac-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="mac-input" required>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="mac-btn-primary">Update Profile</button>
                </div>
            </form>
        </article>

        <article class="glass-card p-5">
            <h2 class="text-lg font-semibold text-slate-900">Change Password</h2>
            <p class="mt-1 text-sm text-slate-500">Gunakan password kuat dan unik.</p>

            <form action="{{ route('users.updatePassword') }}" method="POST" class="mt-5 space-y-4">
                @csrf
                <div>
                    <label class="mac-label">Current Password</label>
                    <input type="password" name="current_password" class="mac-input" required>
                </div>
                <div>
                    <label class="mac-label">New Password</label>
                    <input type="password" name="new_password" class="mac-input" required>
                </div>
                <div>
                    <label class="mac-label">Confirm New Password</label>
                    <input type="password" name="new_password_confirmation" class="mac-input" required>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="mac-btn-primary">Update Password</button>
                </div>
            </form>
        </article>
    </section>
@endsection
