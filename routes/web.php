<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InterventionController;
use Illuminate\Support\Facades\Route;

// Routes publiques
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.submit');

// Routes authentifiées
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Clients
    Route::resource('clients', ClientController::class);

    // Interventions
    Route::resource('interventions', InterventionController::class);
    Route::post('/interventions/{intervention}/notes', [InterventionController::class, 'addNote'])
        ->name('interventions.notes.store');
    Route::delete('/intervention-images/{image}', [InterventionController::class, 'deleteImage'])
        ->name('intervention-images.destroy');
    Route::get('/interventions-export', [InterventionController::class, 'export'])
        ->name('interventions.export');
});

require __DIR__.'/auth.php';
