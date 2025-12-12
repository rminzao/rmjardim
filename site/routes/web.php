<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\ServiceController;
use Illuminate\Support\Facades\Route;

// Landing Page
Route::get('/', [LandingController::class, 'index'])->name('home');

// Contact Form
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Admin Routes (Protected by Auth)
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Profile (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Clients Management
    Route::prefix('admin/clients')->name('admin.clients.')->group(function () {
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::patch('/{id}', [ClientController::class, 'update'])->name('update');
        Route::delete('/{id}', [ClientController::class, 'destroy'])->name('destroy');
    });
    
    // Content Editor
    Route::prefix('admin/content')->name('admin.content.')->group(function () {
        Route::get('/', [ContentController::class, 'index'])->name('index');
        Route::post('/', [ContentController::class, 'update'])->name('update');
    });
    
    // Gallery Management
    Route::prefix('admin/gallery')->name('admin.gallery.')->group(function () {
        Route::get('/', [GalleryController::class, 'index'])->name('index');
        Route::post('/', [GalleryController::class, 'store'])->name('store');
        Route::patch('/{id}', [GalleryController::class, 'update'])->name('update');
        Route::delete('/{id}', [GalleryController::class, 'destroy'])->name('destroy');
        Route::post('/order', [GalleryController::class, 'updateOrder'])->name('updateOrder');
        Route::post('/settings', [GalleryController::class, 'updateSettings'])->name('updateSettings');
        Route::post('/projects', [GalleryController::class, 'storeProject'])->name('storeProject');
        Route::patch('/projects/{id}', [GalleryController::class, 'updateProject'])->name('updateProject');
        Route::delete('/projects/{id}', [GalleryController::class, 'destroyProject'])->name('destroyProject');
    });
    
    // Services Management
    Route::prefix('admin/services')->name('admin.services.')->group(function () {
        Route::get('/', [ServiceController::class, 'index'])->name('index');
        Route::post('/', [ServiceController::class, 'store'])->name('store');
        Route::patch('/{id}', [ServiceController::class, 'update'])->name('update');
        Route::delete('/{id}', [ServiceController::class, 'destroy'])->name('destroy');
        Route::post('/order', [ServiceController::class, 'updateOrder'])->name('updateOrder');
    });

    // Propostas
    Route::prefix('admin/proposals')->name('admin.proposals.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ProposalServiceController::class, 'indexWeb'])->name('index');
    });

    // WhatsApp Manager API
    Route::prefix('admin/whatsapp')->name('admin.whatsapp.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\WhatsAppManagerController::class, 'index'])->name('index');
        Route::get('/status', [App\Http\Controllers\Admin\WhatsAppManagerController::class, 'status'])->name('status');
        Route::get('/qrcode', [App\Http\Controllers\Admin\WhatsAppManagerController::class, 'qrcode'])->name('qrcode');
        Route::get('/logs', [App\Http\Controllers\Admin\WhatsAppManagerController::class, 'logs'])->name('logs');
        Route::post('/connect', [App\Http\Controllers\Admin\WhatsAppManagerController::class, 'connect'])->name('connect');
        Route::post('/disconnect', [App\Http\Controllers\Admin\WhatsAppManagerController::class, 'disconnect'])->name('disconnect');
        Route::post('/restart', [App\Http\Controllers\Admin\WhatsAppManagerController::class, 'restart'])->name('restart');
    });
});

// WhatsApp Manager
Route::get('/admin/whatsapp', [App\Http\Controllers\Admin\WhatsAppManagerController::class, 'index'])
    ->name('admin.whatsapp.index')
    ->middleware('auth');

// Webhook WhatsApp (sem autenticação)
Route::post('/webhook/whatsapp', [App\Http\Controllers\WebhookController::class, 'handle'])
    ->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);

require __DIR__.'/auth.php';