<?php

use App\Http\Controllers\Api\TesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdditionalController;

Route::middleware(['block.browser', 'auth.basic', 'role:api'])->group(function () {
    Route::get('/tes', [TesController::class, 'index']);
    Route::apiResource('additional', AdditionalController::class);
});
