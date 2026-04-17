<?php

use App\Http\Controllers\Api\DirectorController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ReceiptController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// L'URL publique signée de téléchargement (Sans middleware sanctum mais nécessite signature valide)
Route::get('/receipt/download/{payment}', [ReceiptController::class, 'download'])
     ->name('receipt.download');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    // Espace Directeur (Lecture Seule / Stats)
    Route::middleware('role:director')->group(function () {
        Route::get('/director/dashboard', [DirectorController::class, 'dashboard']);
        Route::get('/director/payments/{payment}/receipt-url', [ReceiptController::class, 'getTemporaryUrl']);
    });

    // Espace Secrétaire (Paiements)
    Route::middleware('role:secretary')->group(function () {
        Route::get('/secretary/payments', [PaymentController::class, 'index']);
        Route::post('/secretary/payments', [PaymentController::class, 'store']);
    });
});
