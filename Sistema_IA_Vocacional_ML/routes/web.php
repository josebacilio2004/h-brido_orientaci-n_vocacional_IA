<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ClusteringController;
use App\Http\Controllers\ReportController;

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
    // Dashboard general (para todos los usuarios autenticados)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/tests', [DashboardController::class, 'tests'])->name('dashboard.tests');
    Route::get('/dashboard/careers', [DashboardController::class, 'careers'])->name('dashboard.careers');
    Route::get('/dashboard/recommendations', [DashboardController::class, 'recommendations'])->name('dashboard.recommendations');
    Route::get('/dashboard/profile', [DashboardController::class, 'profile'])->name('dashboard.profile');

    // ✅ RUTAS PARA ADMIN - SIN MIDDLEWARE (la verificación será en el controlador)
    Route::prefix('counselor')->name('counselor.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'counselorDashboard'])->name('dashboard');
        Route::get('/students', [DashboardController::class, 'counselorStudents'])->name('students');
        Route::get('/students/{id}/prediction', [DashboardController::class, 'studentPrediction'])->name('student.prediction');
    });

    Route::prefix('psychologist')->name('psychologist.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'psychologistDashboard'])->name('dashboard');
        Route::get('/analytics', [DashboardController::class, 'psychologistAnalytics'])->name('analytics');
    });

    // Rutas de tests (para todos los usuarios autenticados)
    Route::prefix('tests')->name('tests.')->group(function () {
        Route::get('/', [TestController::class, 'index'])->name('index');

        // Test de Intereses
        Route::prefix('interest')->name('interest.')->group(function () {
            Route::get('/{id}', [TestController::class, 'showInterestTest'])->name('show');
            Route::get('/{id}/question/{question}', [TestController::class, 'interestQuestion'])->name('question');
            Route::post('/{id}/question/{question}', [TestController::class, 'saveInterestAnswer'])->name('save-answer');
            Route::get('/{id}/result', [TestController::class, 'interestResult'])->name('result');
        });

        // Test de Habilidades
        Route::prefix('skill')->name('skill.')->group(function () {
            Route::get('/{id}', [TestController::class, 'showSkillTest'])->name('show');
            Route::get('/{id}/question/{question}', [TestController::class, 'skillQuestion'])->name('question');
            Route::post('/{id}/question/{question}', [TestController::class, 'saveSkillAnswer'])->name('save-answer');
            Route::get('/{id}/result', [TestController::class, 'skillResult'])->name('result');
        });

        // Test de Personalidad
        Route::prefix('personality')->name('personality.')->group(function () {
            Route::get('/{id}', [TestController::class, 'showPersonalityTest'])->name('show');
            Route::get('/{id}/question/{question}', [TestController::class, 'personalityQuestion'])->name('question');
            Route::post('/{id}/question/{question}', [TestController::class, 'savePersonalityAnswer'])->name('save-answer');
            Route::get('/{id}/result', [TestController::class, 'personalityResult'])->name('result');
        });

        // Rutas de predicción con IA (sin {id})
        Route::get('/grades/form', [TestController::class, 'gradesForm'])->name('grades');
        Route::post('/grades/submit', [TestController::class, 'submitGrades'])->name('grades.submit');
        Route::get('/ai/result', [TestController::class, 'aiResult'])->name('ai-result');

        // Rutas específicas del test RIASEC (con {id})
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

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/download/students-csv', [ReportController::class, 'downloadStudentsCSV'])->name('download.students-csv');
        Route::get('/download/grades-csv', [ReportController::class, 'downloadGradesCSV'])->name('download.grades-csv');
        Route::get('/download/predictions-csv', [ReportController::class, 'downloadPredictionsCSV'])->name('download.predictions-csv');
        Route::get('/download/student/{studentId}/pdf', [ReportController::class, 'downloadStudentReportPDF'])->name('download.student-pdf');
    });
});
