@extends('layouts.master')

@section('title', 'Roles')

@section('content')
    @php
        $canViewRoles = auth()->user()->roles->pluck('id')->contains(1)
            || auth()->user()->roles->flatMap->permissions->pluck('name')->contains('view-role');
    @endphp

    @if ($canViewRoles)
        <section class="glass-card p-5">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Roles</h2>
                    <p class="text-sm text-slate-500">Kelola daftar role untuk akses sistem.</p>
                </div>
            </div>

            @can('has-permission', 'add-role')
                <form method="POST" action="{{ route('roles.store') }}" class="mt-4 grid gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4 md:grid-cols-[1fr,auto]">
                    @csrf
                    <div>
                        <label class="mac-label">Role Name</label>
                        <input type="text" name="name" class="mac-input" placeholder="Contoh: manager" required>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="mac-btn-primary">Add Role</button>
                    </div>
                </form>
            @endcan

            <div class="mac-table-wrap mt-4">
                <table class="mac-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Users</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr id="role-row-{{ $role->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td class="font-medium text-slate-800" data-role-name>{{ ucfirst($role->name) }}</td>
                                <td>{{ $role->users->count() }}</td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        @if ($role->id !== 1)
                                            @can('has-permission', 'edit-role')
                                                @if (!auth()->user()->roles->pluck('id')->contains($role->id))
                                                    <button class="mac-btn edit-role-btn" type="button" data-id="{{ $role->id }}" data-name="{{ $role->name }}">
                                                        Edit
                                                    </button>
                                                @endif
                                            @endcan

                                            @if ($role->id !== 2)
                                                @can('has-permission', 'del-role')
                                                    <button class="mac-btn text-rose-700 delete-role-btn" type="button" data-id="{{ $role->id }}">
                                                        Delete
                                                    </button>
                                                @endcan
                                            @endif

                                            @can('has-permission', 'update-permissions')
                                                @if (!auth()->user()->roles->pluck('id')->contains($role->id))
                                                    <a href="{{ route('roles.permission', ['role' => $role->id]) }}" class="mac-btn-primary">Permissions</a>
                                                @endif
                                            @endcan
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <div id="editRoleModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 p-4">
            <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-5 shadow-xl">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900">Edit Role</h3>
                    <button type="button" id="closeRoleModal" class="mac-btn" data-no-loader="true">Close</button>
                </div>

                <form id="editRoleForm" class="space-y-4" data-no-loader="true">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" id="editRoleId">
                    <div>
                        <label class="mac-label">Role Name</label>
                        <input id="editRoleName" name="name" class="mac-input" required>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" id="cancelRoleModal" class="mac-btn" data-no-loader="true">Cancel</button>
                        <button type="submit" class="mac-btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        const roleModal = document.getElementById('editRoleModal');
        const editRoleForm = document.getElementById('editRoleForm');

        function hideRoleModal() {
            roleModal.classList.add('hidden');
            roleModal.classList.remove('flex');
        }

        function showRoleModal() {
            roleModal.classList.remove('hidden');
            roleModal.classList.add('flex');
        }

        document.querySelectorAll('.edit-role-btn').forEach((button) => {
            button.addEventListener('click', () => {
                document.getElementById('editRoleId').value = button.dataset.id;
                document.getElementById('editRoleName').value = button.dataset.name;
                showRoleModal();
            });
        });

        ['closeRoleModal', 'cancelRoleModal'].forEach((id) => {
            const element = document.getElementById(id);
            if (element) {
                element.addEventListener('click', hideRoleModal);
            }
        });

        if (editRoleForm) {
            editRoleForm.addEventListener('submit', async (event) => {
                event.preventDefault();
                const roleId = document.getElementById('editRoleId').value;
                const formData = new FormData(editRoleForm);
                window.pageLoader.show();

                try {
                    const response = await fetch(`/roles/${roleId}`, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: formData,
                    });

                    const data = await response.json();
                    if (!response.ok || data.status !== 'success') {
                        throw new Error(data.message || 'Gagal memperbarui role.');
                    }

                    window.location.reload();
                } catch (error) {
                    alert(error.message);
                    hideRoleModal();
                    document.getElementById('page-loader')?.classList.add('opacity-0', 'pointer-events-none');
                }
            });
        }

        document.querySelectorAll('.delete-role-btn').forEach((button) => {
            button.addEventListener('click', async () => {
                if (!confirm('Hapus role ini?')) {
                    return;
                }

                window.pageLoader.show();

                try {
                    const response = await fetch(`/roles/${button.dataset.id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                    });

                    const data = await response.json();
                    if (!response.ok || data.status !== 'success') {
                        throw new Error(data.message || 'Gagal menghapus role.');
                    }

                    window.location.reload();
                } catch (error) {
                    alert(error.message);
                    document.getElementById('page-loader')?.classList.add('opacity-0', 'pointer-events-none');
                }
            });
        });
    </script>
@endpush
