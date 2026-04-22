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
    // Organizations API
    Route::get('api/organizations', [\App\Http\Controllers\Admin\OrganizationController::class, 'index'])->name('api.organizations');
    Route::get('api/organizations/{id}', [\App\Http\Controllers\Admin\OrganizationController::class, 'show'])->name('api.organizations.show');
    Route::post('api/organizations', [\App\Http\Controllers\Admin\OrganizationController::class, 'store'])->name('api.organizations.store');
    Route::put('api/organizations/{id}', [\App\Http\Controllers\Admin\OrganizationController::class, 'update'])->name('api.organizations.update');
    Route::delete('api/organizations/{id}', [\App\Http\Controllers\Admin\OrganizationController::class, 'destroy'])->name('api.organizations.destroy');
    Route::get('api/organizations-active', [\App\Http\Controllers\Admin\OrganizationController::class, 'getActiveOrganizations'])->name('api.organizations.active');

    // Positions API
    Route::get('api/elections/{electionId}/positions', [\App\Http\Controllers\Admin\PositionController::class, 'index'])->name('api.positions');
    Route::post('api/elections/{electionId}/positions', [\App\Http\Controllers\Admin\PositionController::class, 'store'])->name('api.positions.store');
    Route::get('api/positions/{id}', [\App\Http\Controllers\Admin\PositionController::class, 'show'])->name('api.positions.show');
    Route::put('api/positions/{id}', [\App\Http\Controllers\Admin\PositionController::class, 'update'])->name('api.positions.update');
    Route::delete('api/positions/{id}', [\App\Http\Controllers\Admin\PositionController::class, 'destroy'])->name('api.positions.destroy');
    Route::patch('api/elections/{electionId}/positions/reorder', [\App\Http\Controllers\Admin\PositionController::class, 'reorder'])->name('api.positions.reorder');
    Route::get('api/elections/{electionId}/ballot', [\App\Http\Controllers\Admin\PositionController::class, 'ballotPreview'])->name('api.positions.ballot');

    // Candidates API
    Route::get('api/candidates', [\App\Http\Controllers\Admin\CandidateController::class, 'apiIndex'])->name('api.candidates');
    Route::get('api/candidates/{candidate}', [\App\Http\Controllers\Admin\CandidateController::class, 'apiShow'])->name('api.candidates.show');
    Route::get('api/candidates/positions/{electionId}', [\App\Http\Controllers\Admin\CandidateController::class, 'getPositions'])->name('api.candidates.positions');
    Route::get('api/candidates/by-position/{electionId}', [\App\Http\Controllers\Admin\CandidateController::class, 'getByPosition'])->name('api.candidates.by-position');

    // Voters API
    Route::get('api/voters', [\App\Http\Controllers\Admin\VoterController::class, 'apiIndex'])->name('api.voters');
    Route::post('api/voters/{id}/approve', [\App\Http\Controllers\Admin\VoterController::class, 'apiApprove'])->name('api.voters.approve');
    Route::post('api/voters/{id}/block', [\App\Http\Controllers\Admin\VoterController::class, 'apiBlock'])->name('api.voters.block');
    Route::post('api/voters/{id}/unblock', [\App\Http\Controllers\Admin\VoterController::class, 'apiUnblock'])->name('api.voters.unblock');
    Route::delete('api/voters/{id}', [\App\Http\Controllers\Admin\VoterController::class, 'apiDestroy'])->name('api.voters.destroy');

    // Real-time monitoring API
    Route::get('api/monitoring', [\App\Http\Controllers\Admin\DashboardController::class, 'getRealTimeMonitoring'])->name('api.monitoring');

    // Reports & Analytics API
    Route::get('api/reports/voter-turnout', [\App\Http\Controllers\Admin\DashboardController::class, 'getVoterTurnoutReport'])->name('api.reports.voter-turnout');
    Route::get('api/reports/election-results', [\App\Http\Controllers\Admin\DashboardController::class, 'getElectionResults'])->name('api.reports.election-results');
    Route::get('api/reports/ai-patterns', [\App\Http\Controllers\Admin\DashboardController::class, 'getAIPatternAnalysis'])->name('api.reports.ai-patterns');
    Route::get('api/reports/time-based', [\App\Http\Controllers\Admin\DashboardController::class, 'getTimeBasedVotingData'])->name('api.reports.time-based');
    Route::get('api/reports/export', [\App\Http\Controllers\Admin\DashboardController::class, 'exportReport'])->name('api.reports.export');
    Route::get('api/reports/export-pdf', [\App\Http\Controllers\Admin\DashboardController::class, 'exportReportPDF'])->name('api.reports.export-pdf');
});

// Admin routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Account Management
    Route::get('/accounts', [App\Http\Controllers\Admin\DashboardController::class, 'accountManagement'])->name('accounts');
    Route::post('/accounts', [App\Http\Controllers\Admin\DashboardController::class, 'storeAccount'])->name('accounts.store');
    Route::delete('/accounts/{id}', [App\Http\Controllers\Admin\DashboardController::class, 'destroyAccount'])->name('accounts.destroy');

    // Election Management Page (New Wizard)
    Route::get('/election-management', function() {
        return view('admin.election-management');
    })->name('election-management');

    Route::resource('voter-management', \App\Http\Controllers\Admin\VoterController::class);
    Route::patch('voter-management/{id}/approve', [\App\Http\Controllers\Admin\VoterController::class, 'approve'])->name('voter-management.approve');
    Route::patch('voter-management/{id}/block', [\App\Http\Controllers\Admin\VoterController::class, 'block'])->name('voter-management.block');
    Route::patch('voter-management/{id}/unblock', [\App\Http\Controllers\Admin\VoterController::class, 'unblock'])->name('voter-management.unblock');

    // Organization Management Routes
    Route::resource('organizations', \App\Http\Controllers\Admin\OrganizationController::class);
    Route::get('organizations-active', [\App\Http\Controllers\Admin\OrganizationController::class, 'getActiveOrganizations'])->name('organizations.active');

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

    // Election Ballot Preview & Settings
    Route::get('elections/{id}/ballot-preview', [\App\Http\Controllers\Admin\ElectionController::class, 'ballotPreview'])->name('elections.ballot-preview');
    Route::patch('elections/{id}/settings', [\App\Http\Controllers\Admin\ElectionController::class, 'updateSettings'])->name('elections.settings');
    Route::patch('elections/{id}/voter-eligibility', [\App\Http\Controllers\Admin\ElectionController::class, 'setVoterEligibility'])->name('elections.voter-eligibility');

    // Position Management Routes
    Route::get('elections/{electionId}/positions', [\App\Http\Controllers\Admin\PositionController::class, 'index'])->name('positions.index');
    Route::post('elections/{electionId}/positions', [\App\Http\Controllers\Admin\PositionController::class, 'store'])->name('positions.store');
    Route::get('positions/{id}', [\App\Http\Controllers\Admin\PositionController::class, 'show'])->name('positions.show');
    Route::put('positions/{id}', [\App\Http\Controllers\Admin\PositionController::class, 'update'])->name('positions.update');
    Route::delete('positions/{id}', [\App\Http\Controllers\Admin\PositionController::class, 'destroy'])->name('positions.destroy');
    Route::patch('elections/{electionId}/positions/reorder', [\App\Http\Controllers\Admin\PositionController::class, 'reorder'])->name('positions.reorder');
    Route::get('elections/{electionId}/ballot', [\App\Http\Controllers\Admin\PositionController::class, 'ballotPreview'])->name('positions.ballot');

    // Candidate Management Routes
    Route::resource('candidates', \App\Http\Controllers\Admin\CandidateController::class)->parameters([
        'candidates' => 'candidate'
    ]);
    Route::get('candidates/positions/{electionId}', [\App\Http\Controllers\Admin\CandidateController::class, 'getPositions'])->name('candidates.positions');
    Route::get('candidates/by-position/{electionId}', [\App\Http\Controllers\Admin\CandidateController::class, 'getByPosition'])->name('candidates.by-position');

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

