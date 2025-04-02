<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('logout');
    }

    // Menampilkan form login
    public function showLoginForm()
    {
        if (Auth::check()) {
            // Jika sudah login, redirect ke dashboard
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }
    // Menangani login request
    public function login(Request $request)
    {
        // Mengambil data kredensial (email dan password)
        $credentials = $request->only('email', 'password');
        $userAgent = $request->header('User-Agent'); // Mendapatkan informasi user-agent
        $ipAddress = $request->ip(); // Mendapatkan alamat IP pengguna

        // Validasi kredensial login
        if (Auth::attempt($credentials)) {
            // Setelah login berhasil, ambil user yang terautentikasi
            $user = Auth::user();

            // Cek apakah user aktif
            if ($user->is_active != 1) {
                // Logout jika user tidak aktif
                Auth::logout();

                // Catat log bahwa login gagal karena user tidak aktif
                Log::create([
                    'user_id' => $user->id,
                    'action' => 'login_failed',
                    'description' => 'User is not active, login failed.',
                    'user_agent' => $userAgent,
                    'ip_address' => $ipAddress,
                    'external_id' => null,
                    'category' => 'Authentication',
                ]);

                // Redirect kembali dengan pesan error melalui session
                return redirect()->back()->with('error', 'User is not active, please contact administrator.');
            }

            // Catat log bahwa login berhasil
            Log::create([
                'user_id' => $user->id,
                'action' => 'login_success',
                'description' => 'User logged in successfully.',
                'user_agent' => $userAgent,
                'ip_address' => $ipAddress,
                'external_id' => null,
                'category' => 'Authentication',
            ]);
            return redirect()->intended('/dashboard')->with('success', 'Selamat datang, ' . $user->name . '!');
        }

        // Jika kredensial login salah, catat log error
        Log::create([
            'user_id' => null, // Tidak ada user_id karena login gagal
            'action' => 'login_failed',
            'description' => 'Invalid credentials. Email: ' . $request->input('email'),
            'user_agent' => $userAgent,
            'ip_address' => $ipAddress,
            'external_id' => null,
            'category' => 'Error',
        ]);

        // Kembali ke form login dengan pesan error melalui session
        return redirect()->back()->with('error', 'Invalid credentials.');
    }

    // Menampilkan form register
    public function showRegistrationForm()
    {
        // Cek apakah pengguna sudah login
        if (Auth::check()) {
            // Jika sudah login, redirect ke dashboard
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        
        \Log::info('Request Data:', $request->all());
        // Validasi input dari form registrasi
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            // 'password_confirmation' => 'required|string|confirmed|min:8',
        ]);

        // Jika validasi gagal, catat log dan kembalikan dengan error
        if ($validator->fails()) {
            \Log::error('Validation Failed:', $validator->errors()->toArray());
            Log::create([
                'user_id' => null, // Tidak ada user_id karena belum ada user yang terdaftar
                'action' => 'registration_failed',
                'category' => 'registration',
                'description' => 'Validation failed for email: ' . $request->email,
                'user_agent' => $request->header('User-Agent'),
                'external_id' => null,
            ]);

            // Jika ini adalah AJAX request, kirim error response JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 400);
            }

            // Jika bukan AJAX request, kembalikan dengan error biasa
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Registrasi gagal. Periksa kembali input Anda.');
        }

        // Buat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash password sebelum disimpan
            // 'password_confirmation' => Hash::make($request->password_confirmation),
        ]);

        // Catat log bahwa user baru telah terdaftar
        Log::create([
            'user_id' => $user->id,
            'action' => 'user_registered',
            'category' => 'registration',
            'description' => 'New user registered with email: ' . $request->email,
            'user_agent' => $request->header('User-Agent'),
            'external_id' => null,
        ]);

        // Cek apakah ini adalah user pertama yang didaftarkan
        if (User::count() == 1) {
            // Jika user pertama, aktifkan dan beri role 'admin'
            $user->is_active = 1;
            $user->save();

            // Dapatkan role 'admin'
            $adminRole = Role::where('id', 1)->first();
            if ($adminRole) {
                $user->roles()->attach($adminRole);
            }

            Log::create([
                'user_id' => $user->id,
                'action' => 'first_user_registered',
                'category' => 'registration',
                'description' => 'First user registered and given admin role.',
                'user_agent' => $request->header('User-Agent'),
                'external_id' => null,
            ]);
        } else {
            // Dapatkan role 'user'
            $userRole = Role::where('id', 2)->first();
            if ($userRole) {
                $user->roles()->attach($userRole);
            }

            Log::create([
                'user_id' => $user->id,
                'action' => 'user_role_assigned',
                'category' => 'registration',
                'description' => 'User assigned with default role user.',
                'user_agent' => $request->header('User-Agent'),
                'external_id' => null,
            ]);
        }

        // Cek apakah user aktif
        if ($user->is_active == 1) {
            Auth::login($user);

            Log::create([
                'user_id' => $user->id,
                'action' => 'login_success',
                'category' => 'authentication',
                'description' => 'User logged in successfully after registration.',
                'user_agent' => $request->header('User-Agent'),
                'external_id' => null,
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Registrasi berhasil! Anda sekarang masuk.',
                    'redirect_url' => url('/dashboard'),
                ]);
            }

            return redirect('/dashboard')->with('success', 'Registrasi berhasil! Anda sekarang masuk.');
        } else {
            Auth::logout();

            Log::create([
                'user_id' => $user->id,
                'action' => 'login_failed',
                'category' => 'authentication',
                'description' => 'User account not activated, login failed.',
                'user_agent' => $request->header('User-Agent'),
                'external_id' => null,
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun Anda belum diaktifkan. Harap menunggu persetujuan admin.',
                ]);
            }

            return redirect()->route('login')->with('error', 'Akun Anda belum diaktifkan. Harap menunggu persetujuan admin.');
        }
    }


    // Menangani logout
    public function logout()
    {
        \Log::info('Logout function triggered');
        
        // Proses logout user
        Auth::logout();

        // Menghancurkan session pengguna
        session()->flush();
        session()->invalidate();
        session()->regenerateToken();

        // Redirect ke halaman login atau halaman utama setelah logout dengan pesan sukses
        return redirect()->route('login')->with('success', 'Anda berhasil logout.');
    }

}
