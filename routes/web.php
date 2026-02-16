<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/contact/submit', [App\Http\Controllers\ContactController::class, 'submit'])->name('contact.submit');

Route::get('/dashboard', function () {
    // Redirect admin users to admin dashboard
    return redirect('/admin/dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/voter-dashboard', [App\Http\Controllers\VoterController::class, 'dashboard'])->name('voter-dashboard');
    Route::post('/voter/vote/{election}', [App\Http\Controllers\VoterController::class, 'vote'])->name('voter.vote');
    Route::get('/voter/active-elections', [App\Http\Controllers\VoterController::class, 'getActiveElections'])->name('voter.active-elections');
    Route::get('/voter/candidates/{election}', [App\Http\Controllers\VoterController::class, 'getCandidates'])->name('voter.candidates');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// API routes for AJAX functionality (outside auth for testing)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('api/candidates', [\App\Http\Controllers\Admin\CandidateController::class, 'apiIndex'])->name('api.candidates');
    Route::get('api/candidates/{candidate}', [\App\Http\Controllers\Admin\CandidateController::class, 'apiShow'])->name('api.candidates.show');
    Route::get('api/voters', [\App\Http\Controllers\Admin\VoterController::class, 'apiIndex'])->name('api.voters');
    Route::post('api/voters/{id}/approve', [\App\Http\Controllers\Admin\VoterController::class, 'apiApprove'])->name('api.voters.approve');
    Route::post('api/voters/{id}/block', [\App\Http\Controllers\Admin\VoterController::class, 'apiBlock'])->name('api.voters.block');
    Route::post('api/voters/{id}/unblock', [\App\Http\Controllers\Admin\VoterController::class, 'apiUnblock'])->name('api.voters.unblock');
    Route::delete('api/voters/{id}', [\App\Http\Controllers\Admin\VoterController::class, 'apiDestroy'])->name('api.voters.destroy');
});

// Admin routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('voter-management', \App\Http\Controllers\Admin\VoterController::class);
    Route::patch('voter-management/{id}/approve', [\App\Http\Controllers\Admin\VoterController::class, 'approve'])->name('voter-management.approve');
    Route::patch('voter-management/{id}/block', [\App\Http\Controllers\Admin\VoterController::class, 'block'])->name('voter-management.block');
    Route::patch('voter-management/{id}/unblock', [\App\Http\Controllers\Admin\VoterController::class, 'unblock'])->name('voter-management.unblock');

    // Election Management Routes
    Route::resource('elections', \App\Http\Controllers\Admin\ElectionController::class);
    Route::patch('elections/{id}/activate', [\App\Http\Controllers\Admin\ElectionController::class, 'activate'])->name('elections.activate');
    Route::patch('elections/{id}/close', [\App\Http\Controllers\Admin\ElectionController::class, 'close'])->name('elections.close');
    Route::patch('elections/{id}/pause', [\App\Http\Controllers\Admin\ElectionController::class, 'pause'])->name('elections.pause');
    Route::patch('elections/{id}/resume', [\App\Http\Controllers\Admin\ElectionController::class, 'resume'])->name('elections.resume');
    Route::patch('elections/{id}/force-close', [\App\Http\Controllers\Admin\ElectionController::class, 'forceClose'])->name('elections.force-close');
    Route::patch('elections/{id}/lock-results', [\App\Http\Controllers\Admin\ElectionController::class, 'lockResults'])->name('elections.lock-results');
    Route::get('elections-stats', [\App\Http\Controllers\Admin\ElectionController::class, 'stats'])->name('elections.stats');
    Route::get('dashboard-stats', [\App\Http\Controllers\Admin\ElectionController::class, 'dashboardStats'])->name('dashboard.stats');

    // Candidate Management Routes
    Route::resource('candidates', \App\Http\Controllers\Admin\CandidateController::class)->parameters([
        'candidates' => 'candidate'
    ]);

    // Debug routes for development (remove in production)
    Route::prefix('debug')->name('debug.')->group(function () {
        Route::get('elections', [\App\Http\Controllers\Admin\ElectionController::class, 'index'])->name('elections');
        Route::get('elections/{id}', [\App\Http\Controllers\Admin\ElectionController::class, 'show'])->name('elections.show');
        Route::post('elections', [\App\Http\Controllers\Admin\ElectionController::class, 'store'])->name('elections.store');
        Route::put('elections/{id}', [\App\Http\Controllers\Admin\ElectionController::class, 'update'])->name('elections.update');
        Route::patch('elections/{id}/activate', [\App\Http\Controllers\Admin\ElectionController::class, 'activate'])->name('elections.activate');
        Route::patch('elections/{id}/close', [\App\Http\Controllers\Admin\ElectionController::class, 'close'])->name('elections.close');
        Route::delete('elections/{id}', [\App\Http\Controllers\Admin\ElectionController::class, 'destroy'])->name('elections.destroy');
        Route::get('elections-stats', [\App\Http\Controllers\Admin\ElectionController::class, 'stats'])->name('elections.stats');
    });
});

require __DIR__.'/auth.php';