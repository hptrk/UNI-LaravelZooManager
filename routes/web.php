<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// every route will be protected by 'auth' middleware
Route::middleware('auth')->group(function () {
    // Main page
    Route::get('/', [HomeController::class, 'index'])->name('home');

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
