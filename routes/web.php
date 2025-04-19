<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\ProfileController;
use App\Mail\TestMail;
use App\Models\Event;
use App\Models\User;
use App\Notifications\EventAdded;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-mail', function () {
    $user = User::where('id', 1)->first();
    Mail::to('nandha.kumar790@gmail.com')->send(new TestMail($user));
    return "Mail sent";
});

Route::get('/test-notification', function () {
    $user = User::where('id', 1)->first();
    $event = Event::first();
    $user->notify(new EventAdded($event));
    return "Notification sent";
});

Route::middleware('guest')->group(function () {
    Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('google.auth.redirect');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.auth.callback');
    Route::get('/api/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.auth.callback');
});

Route::middleware(['auth', 'verified', 'logger'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard');

    Route::get('/events', [EventController::class, 'index'])->name('event.index');
    Route::get('/events/create', [EventController::class, 'create'])->name('event.create');
    Route::get('/events/{id}', [EventController::class, 'show'])->where('id', '[0-9]+')->name('event.show');
    Route::post('/events', [EventController::class, 'store'])->name('event.store');
    Route::get('/events/{id}/edit', [EventController::class, 'edit'])->where('id', '[0-9]+')->name('event.edit');
    Route::put('/events/{id}', [EventController::class, 'update'])->where('id', '[0-9]+')->name('event.update');
    Route::delete('/events/{id}', [EventController::class, 'delete'])->where('id', '[0-9]+')->name('event.delete');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';