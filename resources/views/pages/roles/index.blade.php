@extends('layouts.master')
@section('title', 'Roles')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            @php
                $canViewRoles =
                    auth()->user()->roles->pluck('id')->contains(1) ||
                    auth()->user()->roles->flatMap->permissions->pluck('name')->contains('view-role');
            @endphp

            @if ($canViewRoles)
                <h4>Daftar Role</h4>

                @can('has-permission', 'add-role')
                    <button class="btn btn-success mb-3 btn-sm w-auto" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                        + Tambah Role
                    </button>
                @endcan

                <table class="table table-bordered mt-3 table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr id="role-{{ $role->id }}">
                                <td class="role-no">{{ $loop->iteration }}</td>
                                <td>{{ ucfirst($role->name) }} <span
                                        class="badge bg-primary float-end">{{ $role->users->count() }} Users</span></td>
                                <td class="action-buttons">
                                    @if ($role->id !== 1)
                                        @can('has-permission', 'edit-role')
                                            @if (!auth()->user()->roles->pluck('id')->contains($role->id))
                                                <button class="btn btn-warning btn-edit-role" data-id="{{ $role->id }}"
                                                    data-name="{{ $role->name }}" data-bs-toggle="modal"
                                                    data-bs-target="#editRoleModal">Edit</button>
                                            @endif
                                        @endcan

                                        @if ($role->id !== 2)
                                            @can('has-permission', 'del-role')
                                                <button class="btn btn-danger btn-delete-role"
                                                    data-id="{{ $role->id }}">Hapus</button>
                                            @endcan
                                        @endif

                                        @can('has-permission', 'update-permissions')
                                            @if (!auth()->user()->roles->pluck('id')->contains($role->id))
                                                <a href="{{ route('roles.permission', ['role' => $role->id]) }}"
                                                    class="btn btn-primary">Permissions</a>
                                            @endif
                                        @endcan
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Modal Tambah Role -->
                <div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addRoleModalLabel">Tambah Role</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="addRoleForm" action="{{ route('roles.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="roleName" class="form-label">Nama Role</label>
                                        <input type="text" class="form-control" id="roleName" name="name" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan Role</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Edit Role -->
                <div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editRoleModalLabel">Edit Role</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="editRoleForm" method="PUT">
                                    @csrf
                                    <input type="hidden" name="_method" value="PUT">
                                    <div class="mb-3">
                                        <label for="editRoleName" class="form-label">Nama Role</label>
                                        <input type="text" class="form-control" id="editRoleName" name="name"
                                            required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Saat tombol Edit ditekan
            $(".btn-edit-role").click(function() {
                let roleId = $(this).data("id");
                let roleName = $(this).data("name");

                // Isi nilai input di modal edit
                $("#editRoleName").val(roleName);
                $("#editRoleForm").attr("action", "{{ url('roles') }}/" + roleId);
            });

            // Saat form Edit Role dikirim
            $("#editRoleForm").submit(function(e) {
                e.preventDefault(); // Mencegah reload halaman

                let form = $(this);
                let actionUrl = form.attr("action");
                let formData = form.serialize();

                $.ajax({
                    url: actionUrl,
                    type: "PUT",
                    data: formData,
                    success: function(response) {
                        // Mengecek apakah statusnya "error"
                        if (response.status === "error") {
                            toastr.error(response.message ||
                                "Terjadi kesalahan saat mengedit role.");
                            return; // Menghentikan eksekusi lebih lanjut jika status adalah error
                        }

                        // Tutup modal setelah sukses edit
                        $("#editRoleModal").modal("hide");

                        // Jika berhasil, update nama role di tabel
                        let updatedRow = $("tr#role-" + response.data
                            .id); // Menemukan baris berdasarkan ID
                        updatedRow.find("td").eq(1).text(response.data
                            .name); // Memperbarui kolom nama role

                        // Tampilkan Toast dengan pesan sukses
                        toastr.success(response.message || "Role berhasil diedit.");
                    },
                    error: function(xhr) {
                        // Menangani error jika terjadi
                        let errorResponse = xhr.responseJSON;
                        if (errorResponse && errorResponse.message) {
                            // Menampilkan pesan error yang lebih umum dari backend
                            toastr.error(errorResponse.message ||
                                "Terjadi kesalahan. Silakan coba lagi.");
                        } else {
                            toastr.error("Terjadi kesalahan saat menghubungi server.");
                        }
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {

            $("#addRoleForm").submit(function(e) {
                e.preventDefault();

                let form = $(this);
                let actionUrl = form.attr("action");
                let formData = form.serialize();

                $.ajax({
                    url: actionUrl,
                    type: "POST",
                    data: formData,
                    headers: {
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        if (response.status !== "success") {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message || 'Gagal menyimpan role.'
                            });
                            return;
                        }

                        // Tutup modal
                        $("#addRoleModal").modal("hide");

                        // Tampilkan notifikasi sukses
                        toastr.success(response.message || 'Role berhasil ditambahkan.');

                        // Tambahkan baris baru ke tabel
                        let newRow = `
                        <tr id="role-${response.data.id}">
                            <td class="role-no"></td>
                            <td>${response.data.name} <span class="badge bg-primary float-end">0 Users</span></td>
                            <td class="action-buttons">
                                <button class="btn btn-warning btn-edit-role"
                                    data-id="${response.data.id}"
                                    data-name="${response.data.name}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editRoleModal">
                                    Edit
                                </button>
                                <button class="btn btn-danger btn-delete-role" data-id="${response.data.id}">
                                    Hapus
                                </button>
                                <a href="/roles/permission?role=${response.data.id}" class="btn btn-primary">
                                    Permissions
                                </a>
                            </td>
                        </tr>
                    `;

                        $("table tbody").prepend(newRow);

                        // Update nomor urut
                        updateRowNumbers();

                        // Tambahkan kembali event listener delete dan edit (untuk baris baru)
                        bindRoleActionEvents();
                    },
                    error: function(xhr) {
                        let errorResponse = xhr.responseJSON;

                        if (errorResponse && errorResponse.errors) {
                            let errorMessages = errorResponse.errors.name || [];
                            errorMessages.forEach(function(message) {
                                toastr.error(message);
                            });
                        } else if (errorResponse && errorResponse.message) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorResponse.message
                            });
                        } else {
                            toastr.error("Terjadi kesalahan. Silakan coba lagi.");
                        }
                    }
                });
            });

            function updateRowNumbers() {
                $("table tbody tr").each(function(index) {
                    $(this).find(".role-no").text(index + 1);
                });
            }

            function bindRoleActionEvents() {
                // Tambahkan kembali fungsi .btn-edit-role & .btn-delete-role jika dibutuhkan
                // Karena event tidak otomatis aktif di elemen baru
            }
            // Ketika tombol Hapus diklik
            $(".btn-delete-role").click(function(e) {
                e.preventDefault();

                let roleId = $(this).data("id");
                let row = $("#role-" + roleId);

                Swal.fire({
                    title: 'Yakin ingin hapus?',
                    text: "Role akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/roles/" + roleId,
                            type: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}",
                            },
                            success: function(response) {
                                if (response.status !== "success") {
                                    toastr.error(response.message ||
                                        "Gagal menghapus role.");
                                    return;
                                }
                                row.remove();
                                toastr.success(response.message ||
                                    "Role berhasil dihapus.");
                            },
                            error: function(xhr) {
                                let response = xhr.responseJSON;
                                toastr.error(response?.message || "Terjadi kesalahan.");
                            }
                        });
                    }
                });
            });

        });
    </script>


@endsection
