<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Log;


class UserController extends Controller
{


    /**
     * Menampilkan daftar user beserta role dan permission.
     */
    public function index(Request $request)
    {
        $user = auth()->user()->loadMissing(['roles.permissions']);

        // Cek apakah user memiliki role ID 1 (admin) atau permission 'view-user'
        if (Gate::denies('has-permission', 'view-user')) {
            abort(403, 'Akses dilarang');
        }

        // Ambil parameter sorting dan pencarian dari request
        $sortBy = $request->query('sort_by', 'name'); // Default sort by name
        $sortOrder = $request->query('sort_order', 'asc'); // Default ascending
        $search = $request->query('search'); // Parameter search
        $searchBy = $request->query('search_by', 'name'); // Default search by 'name'

        // Query User
        $query = User::with('roles');

        // Batasi query User jika pengguna tidak memiliki role_id 1 (admin) dan memiliki izin 'role-by-user'
        if (Gate::allows('has-permission', 'role-by-user') && !$user->roles->pluck('id')->contains(1)) {
            // Batasi hanya user dengan role yang dimiliki oleh pengguna yang login
            $roleIds = $user->roles->pluck('id');
            $query->whereHas('roles', function ($q) use ($roleIds) {
                $q->whereIn('role_user.role_id', $roleIds); // Filter berdasarkan role_user.role_id
            });
        }

        // Filter pencarian
        if ($search) {
            if ($searchBy === 'name') {
                $query->where('name', 'like', '%' . $search . '%');
            } elseif ($searchBy === 'email') {
                $query->where('email', 'like', '%' . $search . '%');
            } elseif ($searchBy === 'is_active') {
                // Pencarian berdasarkan status (active/not active)
                $query->where('is_active', $search === 'active' ? 1 : 0);
            }
        }

        // Sorting berdasarkan Role (harus pakai join)
        if ($sortBy === 'role') {
            $query->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
                ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
                ->orderBy('roles.name', $sortOrder)
                ->select('users.*'); // Ambil hanya kolom dari users
        } else {
            // Sorting umum selain role
            $query->orderBy($sortBy, $sortOrder);
        }

        // Sorting berdasarkan status aktif
        if ($sortBy === 'is_active') {
            $query->orderBy('is_active', $sortOrder);
        }

        // Ambil nilai 'entries_per_page' dari request, jika tidak ada set default ke 10
        $entriesPerPage = request('entries_per_page', 10);

        // Perbaikan untuk paginate
        $users = $query->distinct('users.id')->paginate($entriesPerPage); // Gunakan distinct berdasarkan user.id

        // Batasi role jika pengguna bukan admin dan memiliki izin 'role-by-user'
        if (Gate::allows('has-permission', 'role-by-user') && !$user->roles->pluck('id')->contains(1)) {
            // Ambil role yang dimiliki oleh pengguna yang login
            $roleIds = $user->roles->pluck('id');
            // Batasi hanya role yang dimiliki oleh pengguna yang login
            $roles = Role::whereIn('id', $roleIds)->withCount('users')->get();
        } else {
            // Jika pengguna adalah admin (role_id = 1), ambil semua role
            $roles = Role::withCount('users')->get();
        }

        return view('pages.users.index', compact('users', 'roles'));
    }





    public function updatePassword(Request $request)
    {
        // Validasi input password
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed', // Menggunakan min:8
        ]);

        // Mendapatkan user yang sedang login
        $user = auth()->user();

        // Mengecek apakah password lama yang dimasukkan sesuai dengan yang tersimpan
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        // Mengubah password menjadi yang baru
        $user->password = Hash::make($request->new_password);

        // Simpan perubahan password
        $user->save();

        // Redirect dengan pesan sukses
        return redirect()->route('users.profile')->with('success', 'Password berhasil diperbarui.');
    }






    /**
     * Menampilkan detail user tertentu beserta role dan permission.
     */
    public function create()
    {
        return view('users.create'); // Jika membuat form manual
    }
    public function store(Request $request)
    {
        if (Gate::denies('has-permission', 'add-user')) {
            abort(403, 'Akses dilarang');
        }
        // Validasi input data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',  // Pastikan ada konfirmasi password jika diperlukan
            'is_active' => 'required|boolean',
            'role_id' => 'required|exists:roles,id', // Validasi agar role_id valid
        ]);

        // Membuat pengguna baru, mengenkripsi password
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_active' => $validated['is_active'],
        ]);

        // Menambahkan role yang dipilih ke user menggunakan pivot table
        $user->roles()->attach($validated['role_id']); // attach() untuk menyimpan relasi many-to-many

        // Menyimpan log pembuatan pengguna
        Log::create([
            'user_id' => auth()->id(), // ID pengguna yang membuat (admin atau pengguna yang berwenang)
            'action' => 'create_user', // Aksi yang dilakukan
            'category' => 'user_management', // Kategori aksi
            'description' => 'User with Name: ' . $user->name . ', Email: ' . $user->email . ' has been created and assigned the role of ID: ' . $validated['role_id'], // Deskripsi perubahan
            'user_agent' => $request->header('User-Agent'), // User-Agent dari permintaan
            'external_id' => null, // Jika ada ID eksternal lain, bisa ditambahkan
        ]);

        // Redirect dengan pesan sukses
        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }
    public function update(Request $request, $id)
    {
        if (Gate::denies('has-permission', 'edit-user')) {
            abort(403, 'Akses dilarang');
        }
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'is_active' => 'required|boolean',
            'role_id' => 'required|exists:roles,id',
            'new_password' => 'nullable|min:6|confirmed',
        ]);

        try {
            $user = User::findOrFail($id);
            $logChanges = []; // Array untuk menyimpan perubahan yang terjadi

            // Cek perubahan nama
            if ($user->name !== $request->name) {
                $logChanges[] = "Nama diubah dari '{$user->name}' ke '{$request->name}'.";
                $user->name = $request->name;
            }

            // Cek perubahan email
            if ($user->email !== $request->email) {
                $logChanges[] = "Email diubah dari '{$user->email}' ke '{$request->email}'.";
                $user->email = $request->email;
            }

            // Cek perubahan status aktif
            if ($user->is_active != $request->is_active) {
                $statusLama = $user->is_active ? 'Aktif' : 'Non-Aktif';
                $statusBaru = $request->is_active ? 'Aktif' : 'Non-Aktif';
                $logChanges[] = "Status diubah dari '{$statusLama}' ke '{$statusBaru}'.";
                $user->is_active = $request->is_active;
            }

            // Cek perubahan role
            $currentRoleId = $user->roles->first()->id ?? null;
            if ($currentRoleId != $request->role_id) {
                $logChanges[] = "Role diubah.";
                $user->roles()->sync([$request->role_id]);
            }

            // Cek perubahan password
            if ($request->new_password) {
                $logChanges[] = "Password diperbarui.";
                $user->password = bcrypt($request->new_password);
            }

            // Simpan perubahan jika ada
            if (!empty($logChanges)) {
                $user->save();

                // Simpan log perubahan ke dalam database
                Log::create([
                    'user_id' => Auth::id(),
                    'action' => 'update_user',
                    'category' => 'user_management',
                    'description' => implode(" | ", $logChanges), // Gabungkan semua perubahan
                    'user_agent' => $request->header('User-Agent'),
                    'external_id' => $user->id,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data pengguna berhasil diperbarui.',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->roles->first()->name ?? 'N/A',
                    'is_active' => $user->is_active,
                    'role_id' => $user->roles->first()->id ?? null,
                    'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                ]
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Cek jika error adalah duplicate entry untuk email
            if ($e->getCode() == 23000) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email sudah terdaftar. Silakan gunakan email lain.',
                ], 400);
            }

            // Simpan log jika terjadi error
            Log::create([
                'user_id' => Auth::id() ?? null,
                'action' => 'update_user_failed',
                'category' => 'error',
                'description' => 'Gagal memperbarui pengguna: ' . $e->getMessage(),
                'user_agent' => $request->header('User-Agent'),
                'external_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan, silakan coba lagi.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }


    public function destroy($id)
    {
        if (Gate::denies('has-permission', 'del-user')) {
            return response()->json(['message' => 'Akses dilarang', 'status' => 'error'], 403);
        }

        try {
            // Temukan user berdasarkan ID
            $user = User::findOrFail($id);

            // Menyimpan log penghapusan user
            Log::create([
                'user_id' => auth()->id(), // ID admin yang menghapus
                'action' => 'delete_user', // Aksi yang dilakukan
                'category' => 'user_management', // Kategori aksi
                'description' => 'User with ID ' . $user->id . ', Name: ' . $user->name . ', Email: ' . $user->email . ' has been deleted.', // Deskripsi tindakan
                'user_agent' => request()->header('User-Agent'), // User-Agent dari permintaan
                'external_id' => null, // Jika diperlukan, bisa ditambahkan ID eksternal lain
            ]);

            // Hapus pengguna
            $user->delete();

            // Jika permintaan AJAX, kirimkan response JSON
            if (request()->ajax()) {
                return response()->json(['message' => 'User berhasil dihapus', 'status' => 'success']);
            }

            // Redirect jika bukan AJAX
            return redirect()->route('users.index')->with('success', 'User berhasil dihapus');
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, kirimkan response error
            if (request()->ajax()) {
                return response()->json(['message' => 'Terjadi kesalahan saat menghapus user: ' . $e->getMessage(), 'status' => 'error']);
            }

            // Redirect jika bukan AJAX
            return redirect()->route('users.index')->with('error', 'Terjadi kesalahan saat menghapus user');
        }
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }



    public function permissions()
    {
        // Mengambil permissions berdasarkan roles, pastikan sudah di-relasikan dengan benar
        return $this->roles->flatMap(function ($role) {
            return $role->permissions;
        })->unique();
    }

    // Show settings page
    public function profile()
    {
        $user = auth()->user()->loadMissing(['roles.permissions']);

        return view('pages.users.profile', compact('user'));
    }

    // Update profile settings
    // Controller UserController.php
    public function updateProfile(Request $request)
    {
        // Ambil data user yang sedang login
        $user = auth()->user()->loadMissing(['roles.permissions']);

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id, // Validasi email berdasarkan ID user yang sedang login
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:20480', // Validasi gambar profil
        ]);

        // Menyimpan log sebelum melakukan update
        Log::create([
            'user_id' => auth()->id(), // ID admin atau pengguna yang mengupdate
            'action' => 'update_profile', // Aksi yang dilakukan
            'category' => 'profile_management', // Kategori aksi
            'description' => 'User with ID ' . $user->id . ' updated their profile. Name: ' . $user->name . ', Email: ' . $user->email, // Deskripsi perubahan
            'user_agent' => $request->header('User-Agent'), // User-Agent dari permintaan
            'external_id' => null, // Jika ada ID eksternal lain, bisa ditambahkan
        ]);

        // Update nama dan email user
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('profile_picture')) {
            // Menyimpan file menggunakan disk 'public' dan folder 'profile_pictures'
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');

            // Jika sebelumnya ada gambar profil, hapus gambar lama
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // Update dengan path file yang baru
            $user->profile_picture = $path;

            // Menyimpan log gambar profil yang diperbarui
            Log::create([
                'user_id' => auth()->id(), // ID pengguna yang mengupdate
                'action' => 'update_profile_picture', // Aksi pembaruan gambar profil
                'category' => 'profile_management', // Kategori aksi
                'description' => 'User with ID ' . $user->id . ' updated their profile picture.', // Deskripsi pembaruan gambar
                'user_agent' => $request->header('User-Agent'), // User-Agent dari permintaan
                'external_id' => null, // ID eksternal jika diperlukan
            ]);
        }

        // Simpan perubahan pada user
        $user->save();

        // Redirect kembali ke halaman settings dengan pesan sukses
        return redirect()->route('users.profile')->with('success', 'Profile updated successfully.');
    }
}
