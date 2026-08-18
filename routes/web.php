<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Static Public Pages
Route::view('/privacy-policy', 'pages.privacy')->name('privacy');
Route::view('/terms-of-service', 'pages.terms')->name('terms');
Route::view('/about', 'pages.about')->name('about');

// Public Shareable Event View (no auth required)
Route::get('/share/{token}', [EventController::class, 'publicShare'])->name('event.public_share');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [EventController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile-avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::patch('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.update_avatar');

    // Data portability: export, import, templates
    Route::get('/profile/export', [ProfileController::class, 'exportEvents'])->name('profile.export');
    Route::post('/profile/import', [ProfileController::class, 'importEvents'])->name('profile.import');
    Route::get('/profile/template/{format}', [ProfileController::class, 'downloadTemplate'])->name('profile.template');

    // Events management
    Route::get('/events/create', [EventController::class, 'create'])->name('event.create');
    Route::post('/events', [EventController::class, 'store'])->name('event.store');
    Route::get('/events/{id}', [EventController::class, 'show'])->name('event.show');
    Route::get('/events/{id}/edit', [EventController::class, 'edit'])->name('event.edit');
    Route::put('/events/{id}', [EventController::class, 'update'])->name('event.update');
    Route::delete('/events/{id}', [EventController::class, 'delete'])->name('event.delete');
    Route::get('/events/{id}/ical', [EventController::class, 'exportIcal'])->name('event.ical');
    Route::post('/events/{id}/milestones/toggle', [EventController::class, 'toggleMilestone'])->name('event.milestone_toggle');

    // Bulk actions
    Route::post('/events/bulk-delete', [EventController::class, 'bulkDelete'])->name('event.bulk_delete');
    Route::post('/events/bulk-category', [EventController::class, 'bulkCategory'])->name('event.bulk_category');
    Route::post('/events/bulk-export', [EventController::class, 'bulkExport'])->name('event.bulk_export');

    // Pexels API Endpoints
    Route::get('/pexels/search', [\App\Http\Controllers\PexelsController::class, 'search'])->name('pexels.search');
    Route::post('/pexels/download', [\App\Http\Controllers\PexelsController::class, 'download'])->name('pexels.download');
    Route::get('/profile/pexels-stats', [\App\Http\Controllers\PexelsController::class, 'rateLimits'])->name('profile.pexels_stats');

    // Private Events PIN Protection Endpoints
    Route::get('/private-events/status', [\App\Http\Controllers\PrivateLockController::class, 'checkStatus'])->name('private.status');
    Route::post('/private-events/verify-pin', [\App\Http\Controllers\PrivateLockController::class, 'verifyPin'])->name('private.verify_pin');
    Route::post('/private-events/set-pin', [\App\Http\Controllers\PrivateLockController::class, 'setPin'])->name('private.set_pin');
});

// Google OAuth
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('google.auth.redirect');
Route::get('/api/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.auth.callback');


require __DIR__.'/auth.php';
