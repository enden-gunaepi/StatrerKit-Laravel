@extends('layouts.master')

@section('title', 'Users')

@section('content')
    <section class="glass-card p-5">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">User Management</h2>
                <p class="text-sm text-slate-500">Kelola akun, role, dan status user.</p>
            </div>
            @can('has-permission', 'add-user')
                <button id="openAddUser" type="button" class="mac-btn-primary">Add User</button>
            @endcan
        </div>

        @can('has-permission', 'add-user')
            <form id="addUserPanel" method="POST" action="{{ route('users.store') }}" class="mt-4 hidden space-y-3 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                @csrf
                <div class="grid gap-3 md:grid-cols-2">
                    <div>
                        <label class="mac-label">Name</label>
                        <input name="name" class="mac-input" required>
                    </div>
                    <div>
                        <label class="mac-label">Email</label>
                        <input name="email" type="email" class="mac-input" required>
                    </div>
                    <div>
                        <label class="mac-label">Password</label>
                        <input name="password" type="password" class="mac-input" required>
                    </div>
                    <div>
                        <label class="mac-label">Confirm Password</label>
                        <input name="password_confirmation" type="password" class="mac-input" required>
                    </div>
                    <div>
                        <label class="mac-label">Role</label>
                        <select name="role_id" class="mac-input" required>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mac-label">Status</label>
                        <select name="is_active" class="mac-input" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" id="closeAddUser" class="mac-btn">Cancel</button>
                    <button type="submit" class="mac-btn-primary">Save User</button>
                </div>
            </form>
        @endcan

        <form method="GET" class="mt-4 grid gap-3 rounded-2xl border border-slate-200 bg-white p-4 md:grid-cols-5">
            <div>
                <label class="mac-label">Entries</label>
                <select name="entries_per_page" class="mac-input">
                    @foreach ([10, 25, 50, 100, 250] as $entry)
                        <option value="{{ $entry }}" {{ request('entries_per_page', 10) == $entry ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mac-label">Search By</label>
                <select name="search_by" class="mac-input">
                    <option value="name" {{ request('search_by', 'name') === 'name' ? 'selected' : '' }}>Name</option>
                    <option value="email" {{ request('search_by') === 'email' ? 'selected' : '' }}>Email</option>
                    <option value="is_active" {{ request('search_by') === 'is_active' ? 'selected' : '' }}>Status</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="mac-label">Keyword</label>
                <input name="search" value="{{ request('search') }}" class="mac-input" placeholder="Cari user...">
            </div>
            <div class="flex items-end gap-2">
                <button class="mac-btn-primary w-full" type="submit">Apply</button>
                <a href="{{ route('users.index') }}" class="mac-btn">Reset</a>
            </div>
        </form>

        <div class="mac-table-wrap mt-4">
            <table class="mac-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><a href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']) }}" class="hover:text-slate-900">Name</a></th>
                        <th><a href="{{ request()->fullUrlWithQuery(['sort_by' => 'email', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']) }}" class="hover:text-slate-900">Email</a></th>
                        <th><a href="{{ request()->fullUrlWithQuery(['sort_by' => 'role', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']) }}" class="hover:text-slate-900">Role</a></th>
                        <th><a href="{{ request()->fullUrlWithQuery(['sort_by' => 'is_active', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']) }}" class="hover:text-slate-900">Status</a></th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $user)
                        <tr data-user-row="{{ $user->id }}">
                            <td>{{ $index + 1 + ($users->currentPage() - 1) * $users->perPage() }}</td>
                            <td data-field="name">{{ $user->name }}</td>
                            <td data-field="email">{{ $user->email }}</td>
                            <td data-field="role" data-role-id="{{ $user->roles->first()->id ?? '' }}">{{ ucfirst($user->roles->first()->name ?? '-') }}</td>
                            <td data-field="status">
                                <span class="status-pill {{ $user->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="flex justify-end gap-2">
                                    @can('has-permission', 'edit-user')
                                        @if (auth()->user()->roles->pluck('id')->contains(1) || !$user->roles->pluck('id')->contains(1))
                                            <button type="button" class="mac-btn edit-user-btn"
                                                data-id="{{ $user->id }}"
                                                data-name="{{ $user->name }}"
                                                data-email="{{ $user->email }}"
                                                data-active="{{ $user->is_active }}"
                                                data-role-id="{{ $user->roles->first()->id ?? '' }}">
                                                Edit
                                            </button>
                                        @endif
                                    @endcan

                                    @if (auth()->id() !== $user->id)
                                        @can('has-permission', 'del-user')
                                            @if (!$user->roles->pluck('id')->contains(1))
                                                <form method="POST" action="{{ route('users.destroy', $user->id) }}" onsubmit="return confirm('Hapus user ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="mac-btn text-rose-700">Delete</button>
                                                </form>
                                            @endif
                                        @endcan
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-500">Tidak ada data user.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex flex-wrap items-center justify-between gap-3 text-sm text-slate-500">
            <p>
                Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} entries
            </p>
            <div>
                {{ $users->appends(request()->query())->links() }}
            </div>
        </div>
    </section>

    <div id="editUserModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 p-4">
        <div class="w-full max-w-xl rounded-2xl border border-slate-200 bg-white p-5 shadow-xl">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900">Edit User</h3>
                <button type="button" id="closeEditModal" class="mac-btn" data-no-loader="true">Close</button>
            </div>

            <form id="editUserForm" class="space-y-3" data-no-loader="true">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="user_id" id="edit_user_id">

                <div>
                    <label class="mac-label">Name</label>
                    <input name="name" id="edit_name" class="mac-input" required>
                </div>
                <div>
                    <label class="mac-label">Email</label>
                    <input name="email" id="edit_email" type="email" class="mac-input" required>
                </div>
                <div class="grid gap-3 md:grid-cols-2">
                    <div>
                        <label class="mac-label">Status</label>
                        <select name="is_active" id="edit_is_active" class="mac-input">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label class="mac-label">Role</label>
                        <select name="role_id" id="edit_role_id" class="mac-input">
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="mac-label">New Password (optional)</label>
                    <input name="new_password" type="password" class="mac-input">
                </div>
                <div>
                    <label class="mac-label">Confirm Password</label>
                    <input name="new_password_confirmation" type="password" class="mac-input">
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" class="mac-btn" id="cancelEditModal" data-no-loader="true">Cancel</button>
                    <button type="submit" class="mac-btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const addUserPanel = document.getElementById('addUserPanel');
        const openAddUser = document.getElementById('openAddUser');
        const closeAddUser = document.getElementById('closeAddUser');

        if (openAddUser && addUserPanel) {
            openAddUser.addEventListener('click', () => addUserPanel.classList.remove('hidden'));
        }

        if (closeAddUser && addUserPanel) {
            closeAddUser.addEventListener('click', () => addUserPanel.classList.add('hidden'));
        }

        const editModal = document.getElementById('editUserModal');
        const closeEditModal = document.getElementById('closeEditModal');
        const cancelEditModal = document.getElementById('cancelEditModal');
        const editUserForm = document.getElementById('editUserForm');

        function hideEditModal() {
            editModal.classList.add('hidden');
            editModal.classList.remove('flex');
        }

        function showEditModal() {
            editModal.classList.remove('hidden');
            editModal.classList.add('flex');
        }

        document.querySelectorAll('.edit-user-btn').forEach((button) => {
            button.addEventListener('click', () => {
                document.getElementById('edit_user_id').value = button.dataset.id;
                document.getElementById('edit_name').value = button.dataset.name;
                document.getElementById('edit_email').value = button.dataset.email;
                document.getElementById('edit_is_active').value = button.dataset.active;
                document.getElementById('edit_role_id').value = button.dataset.roleId;
                showEditModal();
            });
        });

        [closeEditModal, cancelEditModal].forEach((button) => {
            if (!button) return;
            button.addEventListener('click', hideEditModal);
        });

        if (editUserForm) {
            editUserForm.addEventListener('submit', async (event) => {
                event.preventDefault();

                const userId = document.getElementById('edit_user_id').value;
                const formData = new FormData(editUserForm);

                window.pageLoader.show();

                try {
                    const response = await fetch(`/users/${userId}`, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: formData,
                    });

                    const data = await response.json();

                    if (!response.ok || !data.success) {
                        throw new Error(data.message || 'Gagal memperbarui user.');
                    }

                    window.location.reload();
                } catch (error) {
                    alert(error.message);
                    hideEditModal();
                    document.getElementById('page-loader')?.classList.add('opacity-0', 'pointer-events-none');
                }
            });
        }
    </script>
@endpush
