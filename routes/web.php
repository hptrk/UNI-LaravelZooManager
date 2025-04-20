<?php

use App\Http\Controllers\AnimalController;
use App\Http\Controllers\EnclosureController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// every route will be protected by 'auth' middleware
Route::middleware('auth')->group(function () {
    // Main page
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::resource('enclosures', EnclosureController::class);

    Route::resource('animals', AnimalController::class);
    Route::patch('animals/{animal}/archive', [AnimalController::class, 'archive'])->name('animals.archive');

    // Dashboard for temporary breeze template
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile?
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
