@extends('layouts.master')
@section('title', 'Users')
@section('content')

    <div class="row justify-content-center">
        <!-- Kolom Kanan - Tabel Pengguna -->
        <div class="col-md-12">
            <h4>Daftar Pengguna</h4>


            <!-- Button to trigger modal -->
            @can('has-permission', 'add-user')
                <button type="button" class="btn btn-success mb-3 btn-sm w-auto" data-bs-toggle="modal"
                    data-bs-target="#addUserModal">
                    Add User
                </button>
            @endcan

            <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <!-- Modal Body -->
                        <div class="modal-body">
                            <form id="addUserForm" method="POST" action="{{ route('users.store') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="userName" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="userName" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="userEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="userEmail" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="userPassword" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="userPassword" name="password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="userPasswordConfirmation" class="form-label">Confirm
                                        Password</label>
                                    <input type="password" class="form-control" id="userPasswordConfirmation"
                                        name="password_confirmation" required>
                                </div>
                                <div class="mb-3">
                                    <label for="userRole" class="form-label">Role</label>
                                    <select class="form-select" id="userRole" name="role_id" required>
                                        <option value="" disabled selected>Select a role</option>
                                        {{-- Loop through roles dynamically --}}
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="isActive" class="form-label">Status</label>
                                    <select class="form-select" id="isActive" name="is_active" required>
                                        <option value="1" selected>Active</option>
                                        <option value="0">Non-Active</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <!-- Modal Footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" form="addUserForm">Add User</button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Form untuk pencarian dan pengurutan -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <!-- Show Entries -->
                <div class="d-flex align-items-center">
                    <label for="entries_per_page" class="me-2 mb-0">Show</label>
                    <form method="GET" class="d-inline">
                        <select name="entries_per_page" id="entries_per_page" class="form-select d-inline w-auto"
                            onchange="this.form.submit()">
                            <option value="10" {{ request('entries_per_page') == 10 ? 'selected' : '' }}>10
                            </option>
                            <option value="25" {{ request('entries_per_page') == 25 ? 'selected' : '' }}>25
                            </option>
                            <option value="50" {{ request('entries_per_page') == 50 ? 'selected' : '' }}>50
                            </option>
                            <option value="100" {{ request('entries_per_page') == 100 ? 'selected' : '' }}>100
                            </option>
                            <option value="250" {{ request('entries_per_page') == 250 ? 'selected' : '' }}>250
                            </option>
                            <option value="1" {{ request('entries_per_page') == 1 ? 'selected' : '' }}>1
                            </option>
                        </select>
                        <span class="ms-2">entries per page</span>
                    </form>
                </div>

                <!-- Role Select -->
                <div class="d-flex align-items-center">
                    <label for="roleSelect" class="form-label me-2 mb-0">Pilih Role</label>
                    <select class="form-select w-auto" id="roleSelect" name="role" onchange="this.form.submit()">
                        <option value="all" {{ request('role') == 'all' ? 'selected' : '' }}>Semua</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }} ({{ $role->users->count() }} Users)
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Search Form -->
                <div class="d-flex align-items-center">
                    <form method="GET" class="d-flex">
                        <!-- Dropdown Pencarian Berdasarkan -->
                        <select name="search_by" class="form-select me-2" onchange="this.form.submit()">
                            <option value="name" {{ request('search_by') == 'name' ? 'selected' : '' }}>Name
                            </option>
                            <option value="email" {{ request('search_by') == 'email' ? 'selected' : '' }}>Email
                            </option>
                            <option value="is_active" {{ request('search_by') == 'is_active' ? 'selected' : '' }}>
                                Status</option>
                        </select>

                        <!-- Field Pencarian -->
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control ms-2"
                            placeholder="Search..." aria-label="Search">

                        <!-- Tombol Search (Submit) dengan Icon -->
                        <button type="submit" class="btn btn-primary ms-2">
                            <i class="bi bi-search"></i> <!-- Bootstrap Icon Search -->
                        </button>
                    </form>
                </div>
            </div>


            <div class="table-responsive">
                <table class="table table-bordered mt-3 table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th> <!-- Added column for row number -->
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                    style="text-decoration: none;">
                                    Nama {!! request('sort_by') == 'name' ? (request('sort_order') == 'asc' ? '🔼' : '🔽') : '' !!}
                                </a>
                            </th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'email', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                    style="text-decoration: none;">
                                    Email {!! request('sort_by') == 'email' ? (request('sort_order') == 'asc' ? '🔼' : '🔽') : '' !!}
                                </a>
                            </th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'role', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                    style="text-decoration: none;">
                                    Role {!! request('sort_by') == 'role' ? (request('sort_order') == 'asc' ? '🔼' : '🔽') : '' !!}
                                </a>
                            </th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'is_active', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                    style="text-decoration: none;">
                                    Status {!! request('sort_by') == 'is_active' ? (request('sort_order') == 'asc' ? '🔼' : '🔽') : '' !!}
                                </a>
                            </th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $index => $user)
                            <tr class="user-row" data-roles="{{ implode(',', $user->roles->pluck('name')->toArray()) }}"
                                data-id="{{ $user->id }}">
                                <td>{{ $index + 1 + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                <!-- Display row number -->
                                <td class="user-name">{{ $user->name }}</td>
                                <td class="user-email">{{ $user->email }}</td>
                                @if ($user->roles->isNotEmpty())
                                    <td class="align-middle user-role" data-role-id="{{ $user->roles->first()->id }}">
                                        @foreach ($user->roles as $role)
                                            {{ ucfirst($role->name) }}
                                        @endforeach
                                    </td>
                                @else
                                    <td class="align-middle user-role">
                                        No roles assigned
                                    </td>
                                @endif

                                <td class="user-status">{{ $user->is_active ? 'Active' : 'Not Active' }}</td>
                                <td>
                                    @can('has-permission', 'edit-user')
                                        @if (auth()->user()->roles->pluck('id')->contains(1) == 1 || $user->roles->pluck('id')->contains(1) != 1)
                                            <button class="btn btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $user->id }}" data-action="edit">
                                                <i class="bi bi-pencil-fill"></i> {{ $user->role_id }}
                                            </button>
                                        @endif
                                    @endcan
                                    <!-- Edit and Delete Button with Icon -->

                                    @if (auth()->user()->id !== $user->id)
                                        <!-- Jika yang login bukan pengguna yang sedang ditampilkan -->
                                        @can('has-permission', 'del-user')
                                            <!-- Jika user yang login memiliki izin untuk menghapus -->
                                            <!-- Cek apakah pengguna yang sedang ditampilkan bukan pengguna dengan role_id 1 -->
                                            @if (!$user->roles->pluck('id')->contains(1))
                                                <button class="btn btn-danger delete-btn" data-user-id="{{ $user->id }}"
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            @endif
                                        @endcan
                                    @endif

                                </td>
                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1"
                                    aria-labelledby="editModalLabel" aria-hidden="true">

                                    <div class="modal-dialog">

                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form class="editUserForm" data-id="{{ $user->id }}">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Nama</label>
                                                        <input type="text" class="form-control name" name="name"
                                                            value="{{ $user->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="email" class="form-label">Email</label>
                                                        <input type="email" class="form-control email" name="email"
                                                            value="{{ $user->email }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="is_active" class="form-label">Status</label>
                                                        <select class="form-control is_active" name="is_active">
                                                            <option value="1"
                                                                {{ $user->is_active ? 'selected' : '' }}>Active
                                                            </option>
                                                            <option value="0"
                                                                {{ !$user->is_active ? 'selected' : '' }}>Not
                                                                Active</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="role_id" class="form-label">Role</label>
                                                        <select name="role_id" class="form-control role_id">
                                                            @foreach ($roles as $role)
                                                                <option value="{{ $role->id }}"
                                                                    {{ $user->roles->contains($role->id) ? 'selected' : '' }}>
                                                                    {{ ucfirst($role->name) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="new_password" class="form-label">New
                                                            Password</label>
                                                        <input type="password" class="form-control new_password"
                                                            name="new_password">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="new_password_confirmation" class="form-label">Confirm
                                                            New Password</label>
                                                        <input type="password"
                                                            class="form-control new_password_confirmation"
                                                            name="new_password_confirmation">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Save
                                                        Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>



                                <!-- Modal -->
                                <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1"
                                    aria-labelledby="deleteModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form id="deleteForm{{ $user->id }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete user
                                                        <strong>{{ $user->name }}</strong>?
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="button"
                                                        class="btn btn-danger confirm-delete">Delete</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p>Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries</p>

            @if ($users->hasPages())
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center mt-3">
                        {{-- Previous Page Link --}}
                        <li class="page-item {{ $users->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link"
                                href="{{ $users->previousPageUrl() }}&entries_per_page={{ request('entries_per_page') }}"
                                aria-label="Previous">
                                &laquo; Previous
                            </a>
                        </li>

                        {{-- Page Links (4 pages consistently) --}}
                        @php
                            $start = max($users->currentPage() - 1, 1); // Start from one page before the current page, but not less than 1
                            $end = min($start + 3, $users->lastPage()); // Show 4 pages, but don't go beyond the last page
                        @endphp

                        {{-- First Page Link if necessary --}}
                        @if ($start > 1)
                            <li class="page-item">
                                <a class="page-link"
                                    href="{{ $users->url(1) }}&entries_per_page={{ request('entries_per_page') }}">1</a>
                            </li>
                            @if ($start > 2)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                        @endif

                        {{-- Loop through pages in the range --}}
                        @for ($page = $start; $page <= $end; $page++)
                            <li class="page-item {{ $users->currentPage() == $page ? 'active' : '' }}">
                                <a class="page-link"
                                    href="{{ $users->url($page) }}&entries_per_page={{ request('entries_per_page') }}">{{ $page }}</a>
                            </li>
                        @endfor

                        {{-- Last Page Link if necessary --}}
                        @if ($end < $users->lastPage())
                            @if ($end < $users->lastPage() - 1)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                            <li class="page-item">
                                <a class="page-link"
                                    href="{{ $users->url($users->lastPage()) }}&entries_per_page={{ request('entries_per_page') }}">{{ $users->lastPage() }}</a>
                            </li>
                        @endif

                        {{-- Next Page Link --}}
                        <li class="page-item {{ $users->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link"
                                href="{{ $users->nextPageUrl() }}&entries_per_page={{ request('entries_per_page') }}"
                                aria-label="Next">
                                Next &raquo;
                            </a>
                        </li>
                    </ul>
                </nav>
            @endif
        </div>
    </div>
@endsection

@section('script')
    <!-- apexcharts -->
    {{-- <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>

    <!-- dashboard init -->
    <script src="{{ URL::asset('build/libs/list.js/list.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/dashboard-analytics.init.js') }}"></script> --}}
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Handle Tab Pengguna dan Role
            const rows = document.querySelectorAll(".user-row");

            // Handle Dropdown Filter Pengguna
            const roleSelect = document.getElementById("roleSelect");

            roleSelect.addEventListener("change", function() {
                const selectedRole = this.value; // Mendapatkan nilai dari dropdown

                rows.forEach(row => {
                    const roles = row.getAttribute("data-roles").split(",");
                    if (selectedRole === "all" || roles.includes(selectedRole)) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });
            });

            // Handle Edit Button Click
            const editButtons = document.querySelectorAll(".btn-edit-role");

            editButtons.forEach(button => {
                button.addEventListener("click", function() {
                    const roleId = this.getAttribute("data-id");
                    const roleName = this.getAttribute("data-name");

                    // Isi modal dengan data yang diambil
                    const editRoleForm = document.getElementById("editRoleForm");
                    const editRoleNameInput = document.getElementById("editRoleName");

                    // Set value nama role di modal
                    editRoleNameInput.value = roleName;

                    // Update action form dengan URL yang sesuai untuk mengedit role
                    editRoleForm.action = `/roles/${roleId}`;
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Ketika tombol Edit ditekan
            $(".btn-warning[data-action='edit']").on("click", function() {
                let row = $(this).closest("tr"); // Ambil baris terkait
                let userId = row.attr("data-id"); // Ambil ID user dari data-id

                // Isi modal dengan data dari tabel
                let modal = $("#editModal" + userId);
                modal.find(".name").val(row.find(".user-name").text().trim()); // Isi input nama
                modal.find(".email").val(row.find(".user-email").text().trim()); // Isi input email
                modal.find(".is_active").val(row.find(".user-status").text().trim() === "Active" ? 1 :
                    0); // Status
                modal.find(".role_id").val(row.find(".user-role").attr("data-role-id")); // Role ID

                // Simpan userId di form modal
                modal.find(".editUserForm").attr("data-id", userId);
            });

            // Submit form untuk update data user
            $(".editUserForm").on("submit", function(e) {
                e.preventDefault(); // Mencegah reload halaman

                let form = $(this);
                let userId = form.attr("data-id"); // Ambil ID user dari atribut data-id
                let formData = form.serialize(); // Ambil semua data form

                $.ajax({
                    url: `/users/${userId}`,
                    type: "PUT",
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            // Tutup modal setelah berhasil
                            $("#editModal" + userId).modal("hide");

                            // Update data di row tabel tanpa reload
                            let row = $("tr[data-id='" + response.user.id + "']");
                            row.find(".user-name").text(response.user.name);
                            row.find(".user-email").text(response.user.email);

                            row.find(".user-role").text(response.user.role)
                                .attr("data-role-id", response.user.role_id);
                            // Perbarui status user
                            row.find(".user-status").text(response.user.is_active == 1 ?
                                "Active" : "Not Active");

                            // Tampilkan toast sukses
                            showToast("User berhasil diperbarui!", "success");
                        }
                    },
                    error: function(xhr) {
                        $("#editModal" + userId).modal("hide");
                        let errors = xhr.responseJSON.errors;
                        let message = "Terjadi kesalahan. Silakan coba lagi.";

                        if (errors) {
                            message = Object.values(errors).map(err => err[0]).join("\n");
                        }

                        showToast(message, "error");
                    }
                });
            });
            $(".confirm-delete").click(function() {
                var userId = $(this).closest('.modal').find('form').attr('id').replace('deleteForm',
                    ''); // Ambil user ID dari ID form

                // Kirim request DELETE via AJAX
                $.ajax({
                    url: '/users/' + userId, // URL penghapusan, sesuaikan dengan route
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}', // Kirimkan token CSRF
                    },
                    success: function(response) {
                        console.log(response); // Menampilkan respons di console

                        // Jika berhasil
                        $('#deleteModal' + userId).modal('hide'); // Menutup modal konfirmasi

                        // Menghapus baris pengguna dari tabel
                        $("tr[data-id='" + userId + "']").remove();

                        // Menampilkan toast berdasarkan status
                        if (response.status === 'success') {
                            toastr.success(response.message); // Menampilkan pesan sukses
                        } else if (response.status === 'error') {
                            toastr.error(response.message); // Menampilkan pesan error
                        }
                    },

                    error: function(xhr, status, error) {
                        // Jika terjadi kesalahan dalam request
                        toastr.error(
                            'An error occurred while trying to delete the user.'
                        ); // Menampilkan toast error
                    }
                });

            });
            // Fungsi untuk menampilkan toast
            function showToast(message, type) {
                let bgColor = type === "success" ? "bg-success" : "bg-danger";
                let toastHtml = `
            <div class="toast align-items-center text-white ${bgColor} border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>`;

                $("#toastContainer").html(toastHtml);

                // Hapus toast otomatis setelah 3 detik
                setTimeout(() => {
                    $(".toast").fadeOut(500, function() {
                        $(this).remove();
                    });
                }, 3000);
            }
        });
    </script>
@endsection
