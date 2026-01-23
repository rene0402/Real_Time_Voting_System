<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    // For Laravel Breeze/Blade, use the dashboard view
    // return view('dashboard');
    
    // For the admin dashboard HTML we created, return it directly
    return view('admin.admin-dashboard'); // admin-dashboard.blade.php is in resources/views/admin/
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/voter-dashboard', function () {
    return view('voter.voter-dashboard');
})->middleware(['auth', 'verified'])->name('voter-dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';