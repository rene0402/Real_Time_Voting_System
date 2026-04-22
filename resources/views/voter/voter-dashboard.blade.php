<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Voter Dashboard - Secure Voting System</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @include('voter.sections.voter-styles')
</head>
<body>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>

    <!-- Vote Confirmation Modal -->
    <div class="modal-overlay" id="voteConfirmationModal">
        <div class="modal">
            <div class="modal-title">Confirm Your Vote</div>
            <p>You are about to vote for:</p>
            <div id="selectedCandidateName" style="font-weight: 600; margin: 1rem 0; padding: 1.2rem; background: var(--cpsu-light); border-radius: 12px; color: var(--cpsu-blue);"></div>
            <p style="color: #666; margin-bottom: 1rem;">Once submitted, your vote cannot be changed.</p>
            <div class="modal-actions">
                <button class="action-btn btn-secondary" onclick="closeVoteConfirmation()">Cancel</button>
                <button class="action-btn btn-success" onclick="submitVote()">Confirm & Submit Vote</button>
            </div>
        </div>
    </div>

    <!-- Vote Success Modal -->
    <div class="modal-overlay" id="voteSuccessModal">
        <div class="modal">
            <div style="text-align: center;">
                <div style="font-size: 4rem; color: var(--cpsu-green); margin-bottom: 1.5rem;"><i class="fas fa-check-circle"></i></div>
                <div class="modal-title">Vote Successfully Recorded!</div>
                <p style="color: #666; margin: 1rem 0;">Your vote has been securely recorded and encrypted.</p>
                <div class="confirmation-code" id="voteReferenceCode">VREF-XXXXXXXX</div>
                <p style="font-size: 0.9rem; color: #95a5a6;">Save this reference code for your records</p>
                <div class="modal-actions" style="justify-content: center; margin-top: 2rem;">
                    <button class="action-btn btn-primary" onclick="closeVoteSuccess()">Return to Dashboard</button>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <div class="logo-text">
                    <span class="main">Secure<span style="color: var(--cpsu-gold);">Vote</span></span>
                    <span class="sub">Voter Dashboard</span>
                </div>
            </div>
            <ul class="nav-menu">
                <li class="nav-item active" onclick="showSection('dashboard')"><i class="nav-icon fas fa-home"></i>Dashboard</li>
                <li class="nav-item" onclick="showSection('profile')"><i class="nav-icon fas fa-user"></i>Profile & Verification</li>
                <li class="nav-item" onclick="showSection('elections')"><i class="nav-icon fas fa-vote-yea"></i>Active Elections</li>
                <li class="nav-item" onclick="showSection('voting')" id="votingNavItem" style="display: none;"><i class="nav-icon fas fa-check-circle"></i>Vote Now</li>
                <li class="nav-item" onclick="showSection('history')"><i class="nav-icon fas fa-history"></i>Voting History</li>
                <li class="nav-item" onclick="showSection('status')"><i class="nav-icon fas fa-chart-line"></i>Election Status</li>
                <li class="nav-item" onclick="showSection('notifications')"><i class="nav-icon fas fa-bell"></i>Notifications</li>
                <li class="nav-item" onclick="showSection('help')"><i class="nav-icon fas fa-question-circle"></i>Help & Support</li>
                <li class="nav-item" onclick="showSection('security')"><i class="nav-icon fas fa-shield-alt"></i>Security Settings</li>
            </ul>
            <div class="logout-section">
                <button class="logout-btn" onclick="performLogout()">
                    <i class="logout-icon fas fa-sign-out-alt"></i> Log Out
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <div class="page-title" id="pageTitle">Voter Dashboard</div>
                <div class="user-info">
                    <span class="status-badge status-verified">Verified Voter</span>
                    <div class="user-profile" onclick="toggleProfileDropdown()">
                        <i class="fas fa-user-circle fa-lg"></i>
                        <div class="profile-name">{{ Auth::user()->name ?? 'Voter' }}</div>
                        <i class="fas fa-chevron-down"></i>
                        <div class="profile-dropdown" id="profileDropdown">
                            <a href="#" class="dropdown-item" onclick="showSection('profile')"><i class="fas fa-user"></i> My Profile</a>
                            <a href="#" class="dropdown-item" onclick="showSection('security')"><i class="fas fa-cog"></i> Security Settings</a>
                            <div class="dropdown-item logout" onclick="performLogout()"><i class="fas fa-sign-out-alt"></i> Log Out</div>
                        </div>
                    </div>
                </div>
            </div>

            @include('voter.sections.dashboard')
            @include('voter.sections.profile')
            @include('voter.sections.elections')
            @include('voter.sections.voting')
            @include('voter.sections.history')
            @include('voter.sections.status')
            @include('voter.sections.notifications-help-security')
        </div>
    </div>

    @include('voter.sections.voter-scripts')
</body>
</html>
