<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use App\Models\Log;

use Illuminate\Support\Facades\Gate;


class RoleController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user()->loadMissing(['roles.permissions']);

        // Mengambil semua permissions dari roles yang dimiliki user
        $userPermissions = $user->roles->flatMap->permissions->pluck('name')->unique();
        // Cek apakah user memiliki role ID 1 atau permission 'view-user'
        if (!$user->roles->pluck('id')->contains(1) && !$userPermissions->contains('view-role')) {
            abort(403, 'Akses dilarang');
        }

        $roles = Role::withCount('users')->get();

        return view('pages.roles.index', compact('roles'));
    }
    public function permission(Request $request)
    {

        // Ambil ID role dari request
        $roleId = $request->query('role');

        // Cari role berdasarkan ID
        $role = Role::with('permissions')->findOrFail($roleId);


        // Ambil semua permission untuk ditampilkan dalam checkbox
        $allPermissions = Permission::all();

        // Ambil semua role untuk tampilan tab filter (opsional)
        $roles = Role::all();
        $user = auth()->user()->loadMissing(['roles.permissions']);

        // Mengambil semua permissions dari roles yang dimiliki user
        $userPermissions = $user->roles->flatMap->permissions->pluck('name')->unique();

        // Cek apakah user memiliki role ID 1 atau permission 'view-user'
        if (!$user->roles->pluck('id')->contains(1) && !$userPermissions->contains('update-permissions')) {
            abort(403, 'Akses dilarang');
        }
        // Mengirimkan semua data yang dibutuhkan, termasuk $allPermissions
        return view('pages.roles.permission', compact('roles', 'role', 'allPermissions'));
    }


    public function editPermissions($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $allPermissions = Permission::all();

        return view('pages.roles.index', compact('role', 'allPermissions'));
    }

    public function updatePermissions(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        // Ambil daftar permission yang dicentang
        $permissionNames = $request->input('permissions', []);

        // Konversi nama permission ke ID
        $permissions = collect($permissionNames)->map(function ($permissionName) {
            return Permission::firstOrCreate(['name' => $permissionName])->id;
        })->toArray(); // Convert to array

        // Cek apakah ada perubahan dalam daftar permissions
        $currentPermissions = $role->permissions->pluck('id')->toArray();

        // Menyimpan permissions yang baru ditambahkan
        $addedPermissions = array_diff($permissions, $currentPermissions);

        // Menyimpan permissions yang dihapus
        $removedPermissions = array_diff($currentPermissions, $permissions);

        // Variabel untuk menyimpan nama permission yang ditambahkan/dihapus
        $addedPermissionNames = [];
        $removedPermissionNames = [];

        // Ambil nama permission yang ditambahkan
        foreach ($addedPermissions as $permissionId) {
            $addedPermissionNames[] = Permission::find($permissionId)->name;
        }

        // Ambil nama permission yang dihapus
        foreach ($removedPermissions as $permissionId) {
            $removedPermissionNames[] = Permission::find($permissionId)->name;
        }

        // Jika permissions yang dikirimkan berbeda dengan yang ada di role
        if ($permissions != $currentPermissions) {
            // Jika tidak ada permission yang dipilih, hapus semua yang ada di role ini
            $role->permissions()->sync($permissions);

            // Menambahkan log untuk aksi update permissions role
            Log::create([
                'user_id' => auth()->id() ?? null,  // Menambahkan user_id jika ada
                'action' => 'permissions_updated',
                'category' => 'role',
                'description' => 'Permissions untuk role ' . $role->name . ' berhasil diperbarui. Added: ' . implode(', ', $addedPermissionNames) . '. Removed: ' . implode(', ', $removedPermissionNames),
                'user_agent' => request()->header('User-Agent'),
                'external_id' => null,
            ]);

            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Permissions berhasil diperbarui.',
                'added_permissions' => $addedPermissionNames,
                'removed_permissions' => $removedPermissionNames,
            ]);
        }

        // Jika tidak ada perubahan, beri pesan error
        return response()->json([
            'status' => 'error',
            'message' => 'Tidak ada perubahan pada permissions.',
            'added_permissions' => [],
            'removed_permissions' => [],
        ]);
    }



    public function store(Request $request)
    {
        if (!Gate::allows('has-permission', 'add-role')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses ditolak / Unauthorized.',
            ], 403);
        }

        $request->validate([
            'name' => 'required|max:255|unique:roles,name',
        ]);

        $existingRole = Role::where('name', $request->name)->first();

        if ($existingRole) {
            Log::create([
                'user_id' => auth()->id(),
                'action' => 'role_creation_failed',
                'category' => 'role',
                'description' => 'Role dengan nama ' . $request->name . ' sudah ada.',
                'user_agent' => $request->header('User-Agent'),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Role dengan nama tersebut sudah ada.',
            ], 400);
        }

        $role = Role::create([
            'name' => $request->name,
        ]);

        Log::create([
            'user_id' => auth()->id(),
            'action' => 'role_created',
            'category' => 'role',
            'description' => 'Role baru berhasil dibuat: ' . $role->name,
            'user_agent' => $request->header('User-Agent'),
        ]);

        // Jika request via AJAX (seperti dari form JS), kembalikan JSON
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Role berhasil ditambahkan.',
                'data' => $role,
            ]);
        }

        // Jika bukan AJAX (misal dari form biasa), redirect
        return redirect()->route('roles.index')->with('success', 'Role berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        // Memeriksa apakah user memiliki permission untuk 'edit-role'
        if (!Gate::allows('has-permission', 'edit-role')) {
            // Jika tidak memiliki permission, kembalikan response error
            return response()->json([
                'status' => 'error',
                'message' => 'Akses ditolak / Unauthorized.',
            ], 403); // 403 Forbidden
        }

        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
        ]);

        // Jika tidak ada validasi error, lanjutkan ke proses update
        $role = Role::findOrFail($id);
        $role->name = $request->name;
        $role->save();

        // Menambahkan log untuk aksi role yang berhasil diperbarui
        Log::create([
            'user_id' => auth()->id() ?? null,
            'action' => 'role_updated',
            'category' => 'role',
            'description' => 'Role dengan ID ' . $role->id . ' berhasil diperbarui. Nama baru: ' . $role->name,
            'user_agent' => $request->header('User-Agent'),
            'external_id' => null,
        ]);

        // Response success
        return response()->json([
            'status' => 'success',
            'message' => 'Role berhasil diperbarui!',
            'data' => $role
        ]);
    }






    public function destroy($id)
    {
        if (!Gate::allows('has-permission', 'del-role')) {
            // Jika tidak memiliki permission, kembalikan response error
            return response()->json([
                'status' => 'error',
                'message' => 'Akses ditolak / Unauthorized.',
            ], 403); // 403 Forbidden
        }
        $role = Role::findOrFail($id);

        // Cek apakah role digunakan oleh user
        if (User::whereHas('roles', function ($query) use ($role) {
            $query->where('name', $role->name);
        })->exists()) {
            Log::create([
                'user_id' => auth()->id() ?? null,
                'action' => 'role_deletion_failed',
                'category' => 'role',
                'description' => 'Role dengan nama ' . $role->name . ' gagal dihapus karena masih digunakan.',
                'user_agent' => request()->header('User-Agent'),
                'external_id' => null,
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Role tidak dapat dihapus karena masih digunakan.'
            ], 400); // 400 Bad Request karena role sedang digunakan
        }

        // Hapus role
        $role->delete();

        // Menambahkan log untuk aksi role yang berhasil dihapus
        Log::create([
            'user_id' => auth()->id() ?? null,
            'action' => 'role_deleted',
            'category' => 'role',
            'description' => 'Role dengan nama ' . $role->name . ' (ID: ' . $role->id . ') berhasil dihapus.',
            'user_agent' => request()->header('User-Agent'),
            'external_id' => null,
        ]);


        return response()->json([
            'status' => 'success',
            'message' => 'Role berhasil dihapus.'
        ]);
    }
}
