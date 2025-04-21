<?php

use App\Http\Controllers\AnimalController;
use App\Http\Controllers\EnclosureController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// every route will be protected by 'auth' middleware
Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    
    Route::resource('enclosures', EnclosureController::class);

    // archived animals routes - these need to come BEFORE the resource routes
    Route::get('/animals/archived', [AnimalController::class, 'archived'])->name('animals.archived');
    Route::get('/animals/{id}/restore', [AnimalController::class, 'restoreForm'])->name('animals.restore.form');
    Route::patch('/animals/{id}/restore', [AnimalController::class, 'restore'])->name('animals.restore');

    // regular animal routes
    Route::resource('animals', AnimalController::class);
    Route::patch('animals/{animal}/archive', [AnimalController::class, 'archive'])->name('animals.archive');
    Route::post('/enclosures/{enclosure}/archive-all', [AnimalController::class, 'archiveAll'])->name('animals.archive-all');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
