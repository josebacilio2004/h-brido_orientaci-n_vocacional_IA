<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ClusteringController;

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

    Route::prefix('tests')->name('tests.')->group(function () {
        Route::get('/', [TestController::class, 'index'])->name('index');

        // Rutas de predicción con IA (sin {id})
        Route::get('/grades/form', [TestController::class, 'gradesForm'])->name('grades');
        Route::post('/grades/submit', [TestController::class, 'submitGrades'])->name('grades.submit');
        Route::get('/ai/result', [TestController::class, 'aiResult'])->name('ai-result');

        // Rutas específicas del test (con {id})
        Route::get('/{id}/start', [TestController::class, 'start'])->name('start');
        Route::get('/{id}/restart', [TestController::class, 'restart'])->name('restart');
        Route::get('/{id}/question/{question}', [TestController::class, 'question'])->name('question');
        Route::post('/{id}/question/{question}', [TestController::class, 'saveAnswer'])->name('save-answer');
        Route::get('/{id}/process', [TestController::class, 'process'])->name('process');
        Route::get('/{id}/result', [TestController::class, 'result'])->name('result');
        Route::post('/{id}/finalize-last', [TestController::class, 'finalizeFromLastQuestion'])
            ->name('tests.finalize-last');
        // Ruta genérica al final
        Route::get('/{id}', [TestController::class, 'show'])->name('show');
    });

    Route::prefix('clustering')->name('clustering.')->group(function () {
        Route::get('/dashboard', [ClusteringController::class, 'dashboard'])->name('dashboard');
        Route::get('/data', [ClusteringController::class, 'getClusteringData'])->name('data');
    });
});
