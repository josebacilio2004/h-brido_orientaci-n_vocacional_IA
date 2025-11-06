<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MLPredictionController;

Route::middleware('auth:sanctum')->group(function () {
    // ... existing routes ...

    Route::post('/predictions/generate', [MLPredictionController::class, 'predict']);
    Route::get('/predictions/latest', [MLPredictionController::class, 'latest']);
});
