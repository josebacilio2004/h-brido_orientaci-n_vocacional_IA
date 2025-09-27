<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// Página principal
Route::get('/', function () {
    return view('welcome');
});

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas del dashboard (requieren autenticación)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/tests', [DashboardController::class, 'tests'])->name('dashboard.tests');
    Route::get('/dashboard/careers', [DashboardController::class, 'careers'])->name('dashboard.careers');
    Route::get('/dashboard/recommendations', [DashboardController::class, 'recommendations'])->name('dashboard.recommendations');
    Route::get('/dashboard/profile', [DashboardController::class, 'profile'])->name('dashboard.profile');
});
