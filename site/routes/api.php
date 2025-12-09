<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProposalServiceController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rotas de Propostas com prefixo correto: /api/admin/...
Route::prefix('admin')->group(function () {
    // CRUD de propostas
    Route::get('proposals', [ProposalServiceController::class, 'index']);
    Route::post('proposals', [ProposalServiceController::class, 'store']);
    Route::get('proposals/{id}', [ProposalServiceController::class, 'show']);
    Route::put('proposals/{id}', [ProposalServiceController::class, 'update']);
    Route::delete('proposals/{id}', [ProposalServiceController::class, 'destroy']);
    
    // Validação CPF/CNPJ
    Route::post('validate-cpf', [ProposalServiceController::class, 'validateCPF']);
    Route::get('cnpj/{cnpj}', [ProposalServiceController::class, 'getCNPJData']);

    // Geração e download de PDF
    Route::post('proposals/{id}/generate-pdf', [ProposalServiceController::class, 'generatePDF']);
    Route::get('proposals/{id}/download-pdf', [ProposalServiceController::class, 'downloadPDF']);
});