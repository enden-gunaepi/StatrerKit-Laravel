<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\MigrationController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/pages-term-conditions', function () {
    return view('pages-term-conditions');
});

Route::post('/register', [AuthController::class, 'register']);
// Menampilkan form login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
// Proses login
Route::post('/login', [AuthController::class, 'login'])->name('login');
// Menampilkan form register
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register.form');
// Proses register
Route::post('/register', [AuthController::class, 'register'])->name('register');
// Proses logout

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');;

Route::get('/run-migrations', [MigrationController::class, 'runMigrations']);

Route::get('/clear-cache', function () {
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');

    return 'Cache berhasil dibersihkan!';
});