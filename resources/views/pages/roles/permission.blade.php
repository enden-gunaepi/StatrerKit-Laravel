@extends('layouts.master')

@section('title', 'Permissions')

@section('content')
    @php
        $permissionsList = [
            'user' => [
                'view-user' => 'Lihat user',
                'add-user' => 'Tambah user',
                'edit-user' => 'Edit user',
                'del-user' => 'Hapus user',
                'role-by-user' => 'Batasi user by role',
            ],
            'role' => [
                'view-role' => 'Lihat role',
                'add-role' => 'Tambah role',
                'edit-role' => 'Edit role',
                'del-role' => 'Hapus role',
                'update-permissions' => 'Update permissions',
            ],
            'log' => [
                'view-log' => 'Lihat log',
                'log-by-user' => 'Batasi log by user',
            ],
            'settings' => [
                'view-settings' => 'Lihat settings',
            ],
        ];
    @endphp

    <section class="glass-card p-5">
        <div class="flex items-start justify-between gap-3">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Permissions for {{ ucfirst($role->name) }}</h2>
                <p class="text-sm text-slate-500">Centang hak akses yang dibutuhkan role ini.</p>
            </div>
            <a href="{{ route('roles.index') }}" class="mac-btn">Back</a>
        </div>

        <div id="permissionStatus" class="mt-4 hidden rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-700"></div>

        <form id="permissionsForm" class="mt-4 space-y-4" data-no-loader="true">
            @csrf
            @method('PUT')

            @foreach ($permissionsList as $category => $permissions)
                <article class="rounded-2xl border border-slate-200 bg-white p-4">
                    <div class="mb-3 flex items-center justify-between">
                        <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-600">{{ $category }}</h3>
                        <label class="inline-flex items-center gap-2 text-xs text-slate-500">
                            <input type="checkbox" class="rounded border-slate-300" data-check-all="{{ $category }}">
                            Toggle all
                        </label>
                    </div>

                    <div class="grid gap-2 md:grid-cols-2">
                        @foreach ($permissions as $key => $label)
                            <label class="flex items-center gap-3 rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-700">
                                <input
                                    type="checkbox"
                                    name="permissions[]"
                                    value="{{ $key }}"
                                    data-category="{{ $category }}"
                                    class="rounded border-slate-300"
                                    {{ $role->permissions->contains('name', $key) ? 'checked' : '' }}>
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </article>
            @endforeach
        </form>
    </section>
@endsection

@push('scripts')
    <script>
        const permissionForm = document.getElementById('permissionsForm');
        const permissionStatus = document.getElementById('permissionStatus');

        function setStatus(message, isError = false) {
            permissionStatus.textContent = message;
            permissionStatus.classList.remove('hidden', 'border-emerald-200', 'bg-emerald-50', 'text-emerald-700', 'border-rose-200', 'bg-rose-50', 'text-rose-700');
            permissionStatus.classList.add(isError ? 'border-rose-200' : 'border-emerald-200');
            permissionStatus.classList.add(isError ? 'bg-rose-50' : 'bg-emerald-50');
            permissionStatus.classList.add(isError ? 'text-rose-700' : 'text-emerald-700');
        }

        async function savePermissions() {
            const checkedPermissions = Array.from(permissionForm.querySelectorAll('input[name="permissions[]"]:checked')).map((input) => input.value);
            window.pageLoader.show();

            try {
                const response = await fetch('{{ route('roles.update_permissions', $role->id) }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ permissions: checkedPermissions }),
                });

                const data = await response.json();
                if (!response.ok || data.status !== 'success') {
                    throw new Error(data.message || 'Gagal menyimpan permissions.');
                }

                const changes = [];
                if (data.added_permissions?.length) {
                    changes.push('Added: ' + data.added_permissions.join(', '));
                }
                if (data.removed_permissions?.length) {
                    changes.push('Removed: ' + data.removed_permissions.join(', '));
                }

                setStatus(changes.length ? changes.join(' | ') : data.message);
            } catch (error) {
                setStatus(error.message, true);
            } finally {
                document.getElementById('page-loader')?.classList.add('opacity-0', 'pointer-events-none');
            }
        }

        permissionForm.querySelectorAll('input[name="permissions[]"]').forEach((checkbox) => {
            checkbox.addEventListener('change', savePermissions);
        });

        document.querySelectorAll('[data-check-all]').forEach((toggle) => {
            toggle.addEventListener('change', () => {
                const category = toggle.dataset.checkAll;
                permissionForm.querySelectorAll(`input[name="permissions[]"][data-category="${category}"]`).forEach((checkbox) => {
                    checkbox.checked = toggle.checked;
                });
                savePermissions();
            });
        });
    </script>
@endpush
