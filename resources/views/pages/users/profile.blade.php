@extends('layouts.master')

@section('content')
<div class="row justify-content-center">
    <section class="col-md-12" style="background-color: #eee;">
        <div class="container py-5">
            <h2 class="mb-4">Account Settings</h2>

            <div class="row">
                <!-- Update Profile Form -->
                <div class="col-md-12">
                    <form action="{{ route('update-profile') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card p-4">
                            <h4 class="mb-3">Profile Information</h4>

                            <!-- Profile Picture -->
                            <div class="mb-3">
                                <label for="profile_picture" class="form-label">Profile Picture</label>
                                <div>
                                    @if ($user->profile_picture)
                                        <img src="{{ Storage::url($user->profile_picture) }}" alt="Profile Picture"
                                            class="img-thumbnail" width="150">
                                    @else
                                        <img src="{{ asset('images/default-profile.png') }}" alt="Default Profile Picture"
                                            class="img-thumbnail" width="150">
                                    @endif
                                </div>
                                <input type="file" class="form-control mt-2" name="profile_picture" id="profile_picture">
                            </div>

                            <!-- Name -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name', $user->name) }}" required>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email', $user->email) }}" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </div>
                    </form>
                </div>

                <!-- Change Password Form -->
                <div class="col-md-12">
                    <form action="{{ route('users.updatePassword') }}" method="POST" class="mt-4">
                        @csrf
                        <div class="card p-4">
                            <h4 class="mb-3">Change Password</h4>

                            <!-- Old Password -->
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <div class="input-group position-relative">
                                    <input type="password" class="form-control" id="current_password"
                                        name="current_password" required>
                                    <span class="input-group-text" onclick="togglePasswordVisibility('current_password')"
                                        style="cursor: pointer;">
                                        <i class="bi bi-eye"></i>
                                    </span>
                                </div>
                            </div>

                            <!-- New Password -->
                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <div class="input-group position-relative">
                                    <input type="password" class="form-control" id="new_password" name="new_password"
                                        required>
                                    <span class="input-group-text" onclick="togglePasswordVisibility('new_password')"
                                        style="cursor: pointer;">
                                        <i class="bi bi-eye"></i>
                                    </span>
                                </div>
                            </div>

                            <!-- Confirm New Password -->
                            <div class="mb-3">
                                <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                <div class="input-group position-relative">
                                    <input type="password" class="form-control" id="new_password_confirmation"
                                        name="new_password_confirmation" required>
                                    <span class="input-group-text"
                                        onclick="togglePasswordVisibility('new_password_confirmation')"
                                        style="cursor: pointer;">
                                        <i class="bi bi-eye"></i>
                                    </span>
                                </div>
                            </div>

                            <!-- Password Match Message -->
                            <div id="password_match_message" class="mb-3" style="display: none;">
                                <span class="text-danger">Passwords do not match</span>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-warning" id="update-password-btn" disabled>Update
                                Password</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </section>
</div>
@endsection

@section('script')
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script>
        // Function to toggle password visibility
        function togglePasswordVisibility(id) {
            var passwordField = document.getElementById(id);
            var icon = passwordField.nextElementSibling.querySelector('i'); // Select the icon

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Function to check if passwords match
            document.getElementById('new_password').addEventListener('input', checkPasswordsMatch);
            document.getElementById('new_password_confirmation').addEventListener('input', checkPasswordsMatch);

            function checkPasswordsMatch() {
                var newPassword = document.getElementById('new_password').value;
                var confirmPassword = document.getElementById('new_password_confirmation').value;

                var message = document.getElementById('password_match_message');
                var submitButton = document.getElementById('update-password-btn');

                // Check if passwords match
                if (newPassword !== confirmPassword) {
                    message.style.display = 'block'; // Show mismatch message
                    submitButton.disabled = true; // Disable submit button
                } else {
                    message.style.display = 'none'; // Hide mismatch message
                    submitButton.disabled = false; // Enable submit button
                }
            }
        });
    </script>
@endsection
