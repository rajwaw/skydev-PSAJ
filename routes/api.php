<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PasienController;

Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::apiResource('pasien', PasienController::class);
});