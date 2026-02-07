<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    // Redirect admin users to admin dashboard
    return redirect('/admin/dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/voter-dashboard', function () {
    return view('voter.voter-dashboard');
})->middleware(['auth', 'verified'])->name('voter-dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.admin-dashboard');
    })->name('dashboard');

    Route::resource('voter-management', \App\Http\Controllers\Admin\VoterController::class);
    Route::patch('voter-management/{id}/approve', [\App\Http\Controllers\Admin\VoterController::class, 'approve'])->name('voter-management.approve');
    Route::patch('voter-management/{id}/block', [\App\Http\Controllers\Admin\VoterController::class, 'block'])->name('voter-management.block');
    Route::patch('voter-management/{id}/unblock', [\App\Http\Controllers\Admin\VoterController::class, 'unblock'])->name('voter-management.unblock');

    // API routes for AJAX functionality
    Route::get('api/voters', [\App\Http\Controllers\Admin\VoterController::class, 'apiIndex'])->name('api.voters');
    Route::post('api/voters/{id}/approve', [\App\Http\Controllers\Admin\VoterController::class, 'apiApprove'])->name('api.voters.approve');
    Route::post('api/voters/{id}/block', [\App\Http\Controllers\Admin\VoterController::class, 'apiBlock'])->name('api.voters.block');
    Route::post('api/voters/{id}/unblock', [\App\Http\Controllers\Admin\VoterController::class, 'apiUnblock'])->name('api.voters.unblock');
    Route::delete('api/voters/{id}', [\App\Http\Controllers\Admin\VoterController::class, 'apiDestroy'])->name('api.voters.destroy');
});

require __DIR__.'/auth.php';