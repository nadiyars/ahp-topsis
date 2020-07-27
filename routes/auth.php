<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.show_login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('admin/dashboard', function () {
        return view('backend.dashboard.index');
    })->name('admin.dashboard');

    Route::get('logout', [LoginController::class, 'logout'])->name('admin.logout');
});

Route::get('admin', [LoginController::class, 'showLoginForm'])->name('admin.show_login');
Route::post('login', [LoginController::class, 'login'])->name('admin.login');
