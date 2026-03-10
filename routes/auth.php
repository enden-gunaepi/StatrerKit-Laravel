<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\MigrationController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\App;

Route::get('/lang/{locale}', function (string $locale) {
    if (!in_array($locale, ['en', 'id'], true)) {
        $locale = 'en';
    }

    session(['lang' => $locale]);
    App::setLocale($locale);

    return redirect()->back();
})->name('lang.switch');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/pages-term-conditions', function () {
    return view('pages-term-conditions');
});

Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/run-migrations', [MigrationController::class, 'runMigrations']);

Route::get('/clear-cache', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');

    return 'Cache berhasil dibersihkan!';
});
