@extends('layouts.master')
@section('title', 'Permissions')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h2 class="mb-4">Role: {{ $role->name }}</h2>

            <form action="{{ route('roles.update_permissions', $role->id) }}" method="POST" id="permissionsForm">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Menu User -->
                    <div class="col-md-12">
                        <ul class="list-group">
                            @php
                                $permissionsList = [
                                    'user' => [
                                        'view-user' => [
                                            'label' => 'Lihat User',
                                            'description' => 'Memberikan hak untuk melihat data pengguna.',
                                        ],
                                        'add-user' => [
                                            'label' => 'Tambah User',
                                            'description' => 'Memberikan hak untuk menambahkan pengguna baru.',
                                        ],
                                        'edit-user' => [
                                            'label' => 'Edit User',
                                            'description' => 'Memberikan hak untuk mengedit data pengguna.',
                                        ],
                                        'del-user' => [
                                            'label' => 'Hapus User',
                                            'description' => 'Memberikan hak untuk menghapus pengguna.',
                                        ],
                                        'role-by-user' => [
                                            'label' => 'Role by user',
                                            'description' =>
                                                'Memberikan hak untuk melihat user berdasarkan role sesuai user.',
                                        ],
                                    ],
                                    'role' => [
                                        'view-role' => [
                                            'label' => 'Lihat role',
                                            'description' => 'Memberikan hak untuk melihat data role.',
                                        ],
                                        'add-role' => [
                                            'label' => 'Tambah role',
                                            'description' => 'Memberikan hak untuk menambahkan role baru.',
                                        ],
                                        'edit-role' => [
                                            'label' => 'Edit role',
                                            'description' => 'Memberikan hak untuk mengedit data role.',
                                        ],
                                        'del-role' => [
                                            'label' => 'Hapus role',
                                            'description' => 'Memberikan hak untuk menghapus role.',
                                        ],
                                        'update-permissions' => [
                                            'label' => 'Update permissions',
                                            'description' => 'Memberikan hak untuk memperbarui hak akses role.',
                                        ],
                                    ],
                                    'log' => [
                                        'view-log' => [
                                            'label' => 'Lihat log',
                                            'description' => 'Memberikan hak untuk melihat data log.',
                                        ],
                                        'log-by-user' => [
                                            'label' => 'Log by user',
                                            'description' =>
                                                'Memberikan hak untuk melihat log berdasarkan yang user buat.',
                                        ],
                                    ],
                                ];
                            @endphp

                            @foreach ($permissionsList as $category => $permissions)
                                <div class="mb-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="text-primary text-uppercase">{{ ucfirst($category) }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <!-- Centang Semua -->
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox"
                                                    id="checkAll{{ $category }}"
                                                    onclick="toggleAllCheckboxes('{{ $category }}')" />
                                                <label class="form-check-label" for="checkAll{{ $category }}">Centang /
                                                    Uncheck
                                                    Semua</label>
                                            </div>

                                            <div class="row">
                                                @foreach ($permissions as $key => $permission)
                                                    <div class="col-md-4 col-sm-6 mb-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input permission-checkbox"
                                                                type="checkbox" name="permissions[]"
                                                                value="{{ $key }}"
                                                                {{ $role->permissions->contains('name', $key) ? 'checked' : '' }}
                                                                data-category="{{ $category }}"
                                                                id="permission-{{ $key }}"
                                                                onchange="updatePermissions()">

                                                            <label class="form-check-label"
                                                                for="permission-{{ $key }}">
                                                                {{ $permission['label'] }}
                                                                <span data-toggle="tooltip"
                                                                    title="{{ $permission['description'] }}"
                                                                    class="text-info ms-2">
                                                                    <i class="fas fa-info-circle"></i>
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </ul>

                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="row mt-4">
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary btn-lg" style="display:none;">Simpan
                            Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script>
        // Fungsi untuk mencentang atau menghapus centang pada semua checkbox dalam kategori tertentu
        function toggleAllCheckboxes(category) {
            var checkboxes = document.querySelectorAll('.permission-checkbox[data-category="' + category + '"]');
            var checkAll = document.getElementById('checkAll' + category);
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = checkAll.checked;
            });

            updatePermissions(); // Call update function when toggling all checkboxes
            updateCheckAllStatus(category); // Check the status of "Centang Semua"
        }

        // Fungsi untuk memeriksa status dari checkbox "Centang Semua" dan memperbaruinya
        function updateCheckAllStatus(category) {
            var checkboxes = document.querySelectorAll('.permission-checkbox[data-category="' + category + '"]');
            var checkAll = document.getElementById('checkAll' + category);

            // Jika semua checkbox dalam kategori ini sudah dicentang, centang "Centang Semua"
            checkAll.checked = Array.from(checkboxes).every(function(checkbox) {
                return checkbox.checked;
            });
        }

        // Fungsi untuk mengirim perubahan permissions via AJAX
        function updatePermissions() {
            var permissions = [];
            document.querySelectorAll('.permission-checkbox:checked').forEach(function(checkbox) {
                permissions.push(checkbox.value);
            });

            $.ajax({
                url: '{{ route('roles.update_permissions', $role->id) }}',
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    permissions: permissions
                },
                success: function(response) {
                    // Menampilkan toast sukses
                    toastr.success(response.message); // Menampilkan pesan sukses berdasarkan response

                    // Menampilkan toast tambahan tentang permission yang ditambahkan
                    if (response.added_permissions.length > 0) {
                        toastr.info('Permissions yang ditambahkan: ' + response.added_permissions.join(', '));
                    }

                    // Menampilkan toast tambahan tentang permission yang dihapus
                    if (response.removed_permissions.length > 0) {
                        toastr.info('Permissions yang dihapus: ' + response.removed_permissions.join(', '));
                    }
                },
                error: function(xhr, status, error) {
                    // Menampilkan toast error jika ada masalah dalam request
                    toastr.error('An error occurred while updating permissions');
                }
            });
        }

        // Fungsi untuk memeriksa status "Centang Semua" saat halaman pertama dimuat
        $(document).ready(function() {
            // Memeriksa status "Centang Semua" untuk setiap kategori saat halaman dimuat
            var categories = ['user', 'role', 'log']; // Ganti dengan kategori yang sesuai
            categories.forEach(function(category) {
                updateCheckAllStatus(category);
            });

            // Aktifkan tooltip
            $('[data-toggle="tooltip"]').tooltip(); // Menambahkan tooltip untuk elemen yang sesuai
        });
    </script>
@endsection
