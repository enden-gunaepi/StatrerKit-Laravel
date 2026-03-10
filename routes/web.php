<?php

use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\LandingPageController;



Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');


    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::post('/users/{id}/permissions/add', [UserController::class, 'addPermission'])->name('users.addPermission');
    Route::post('/users/{id}/permissions/remove', [UserController::class, 'removePermission'])->name('users.removePermission');
    Route::get('/users-profile', [UserController::class, 'profile'])->name('users.profile');
    Route::post('/update-profile', [UserController::class, 'updateProfile'])->name('update-profile');
    // Route untuk menampilkan form create
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::get('/account-settings', [UserController::class, 'profile'])->name('users.profile');
    Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    // Route untuk update password
    Route::post('/update-password', [UserController::class, 'updatePassword'])->name('users.updatePassword');
    // Route untuk menyimpan data
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    // Tambahkan route khusus jika diperlukan (untuk detail, update, dan delete)
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');

    // Roles
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/permission', [RoleController::class, 'permission'])->name('roles.permission');
    Route::get('/roles/{id}/permissions', [RoleController::class, 'editPermissions'])->name('roles.edit_permissions');
    Route::put('/roles/{id}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.update_permissions');
    // Tambahkan route untuk menyimpan role baru
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');

    // Logs
    Route::resource('logs', LogController::class);

    // Settings
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/upload-logo/{key}', [SettingController::class, 'uploadLogo'])->name('settings.upload-logo');

    // Landing Page Builder
    Route::get('landing-page/settings', [LandingPageController::class, 'settings'])->name('landing-page.settings');
    Route::post('landing-page/settings', [LandingPageController::class, 'update'])->name('landing-page.settings.update');
});


Route::get('/layouts-horizontal', function () {
    return view('layouts-horizontal');
});

Route::get('/layouts-detached', function () {
    return view('layouts-detached');
});

Route::get('/layouts-two-column', function () {
    return view('layouts-two-column');
});

Route::get('/layouts-vertical-hovered', function () {
    return view('layouts-vertical-hovered');
});
