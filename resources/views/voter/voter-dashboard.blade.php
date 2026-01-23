<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Voter Dashboard - Secure Voting System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Instrument Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f7fa;
            color: #333;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, #1a3a5f 0%, #2a5298 100%);
            color: white;
            padding: 1.5rem 0;
            display: flex;
            flex-direction: column;
            position: relative;
            z-index: 100;
        }

        .logo {
            padding: 0 1.5rem 2rem;
            font-size: 1.5rem;
            font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 1rem;
        }

        .logo span {
            color: #4CAF50;
        }

        .nav-menu {
            list-style: none;
            flex: 1;
        }

        .nav-item {
            padding: 0.75rem 1.5rem;
            transition: all 0.3s;
            cursor: pointer;
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .nav-item:hover, .nav-item.active {
            background: rgba(255,255,255,0.1);
            border-left: 4px solid #4CAF50;
        }

        .nav-icon {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        /* Logout section */
        .logout-section {
            padding: 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: auto;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 0.75rem;
            background: rgba(231, 76, 60, 0.2);
            color: white;
            border: 1px solid rgba(231, 76, 60, 0.4);
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
            text-decoration: none;
            font-family: inherit;
            font-size: 1rem;
        }

        .logout-btn:hover {
            background: rgba(231, 76, 60, 0.3);
            border-color: #e74c3c;
            transform: translateY(-2px);
        }

        .logout-icon {
            margin-right: 8px;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #eaeaea;
        }

        .page-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: #2c3e50;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            background: #f8f9fa;
            border-radius: 8px;
            cursor: pointer;
            position: relative;
            border: 1px solid #eaeaea;
        }

        .profile-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            display: none;
            min-width: 200px;
            z-index: 1000;
            margin-top: 0.5rem;
            border: 1px solid #eaeaea;
        }

        .profile-dropdown.active {
            display: block;
        }

        .dropdown-item {
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            transition: background 0.3s;
            text-decoration: none;
            color: #333;
        }

        .dropdown-item:hover {
            background: #f8f9fa;
        }

        .dropdown-item.logout {
            color: #e74c3c;
            border-top: 1px solid #eee;
        }

        /* Status Badges */
        .status-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-block;
        }

        .status-verified {
            background: #d4edda;
            color: #155724;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-restricted {
            background: #f8d7da;
            color: #721c24;
        }

        .status-voted {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-not-voted {
            background: #f8f9fa;
            color: #6c757d;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-closed {
            background: #f8d7da;
            color: #721c24;
        }

        /* Cards */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: transform 0.3s, box-shadow 0.3s;
            border-left: 4px solid #4CAF50;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .card-title {
            font-size: 1.1rem;
            color: #2c3e50;
            font-weight: 600;
        }

        .card-subtitle {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }

        .card-value {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        /* Countdown Timer */
        .countdown-timer {
            font-size: 1.5rem;
            font-weight: 700;
            color: #e74c3c;
            text-align: center;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
            margin: 1rem 0;
        }

        .countdown-label {
            font-size: 0.9rem;
            color: #6c757d;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        /* Election Cards */
        .election-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .election-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border: 1px solid #eaeaea;
        }

        .election-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f8f9fa;
        }

        .election-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2c3e50;
        }

        .election-time {
            font-size: 0.9rem;
            color: #6c757d;
            margin: 0.5rem 0;
        }

        .election-description {
            color: #666;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .vote-btn {
            width: 100%;
            padding: 0.75rem;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .vote-btn:hover:not(:disabled) {
            background: #45a049;
            transform: translateY(-2px);
        }

        .vote-btn:disabled {
            background: #95a5a6;
            cursor: not-allowed;
        }

        .vote-btn.voted {
            background: #3498db;
        }

        .vote-btn.closed {
            background: #e74c3c;
        }

        /* Voting Interface */
        .voting-interface {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 0 auto;
        }

        .candidate-list {
            margin: 2rem 0;
        }

        .candidate-card {
            display: flex;
            align-items: center;
            padding: 1rem;
            border: 2px solid #eaeaea;
            border-radius: 8px;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s;
        }

        .candidate-card:hover {
            border-color: #3498db;
            background: #f8f9fa;
        }

        .candidate-card.selected {
            border-color: #4CAF50;
            background: #f0f9f0;
        }

        .candidate-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 1rem;
            border: 3px solid #eaeaea;
        }

        .candidate-info {
            flex: 1;
        }

        .candidate-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .candidate-description {
            color: #666;
            line-height: 1.5;
        }

        .candidate-select {
            padding: 0.5rem 1rem;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
        }

        /* Vote Confirmation */
        .confirmation-card {
            text-align: center;
            padding: 3rem 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 2rem auto;
        }

        .confirmation-icon {
            font-size: 4rem;
            color: #4CAF50;
            margin-bottom: 1.5rem;
        }

        .confirmation-title {
            font-size: 2rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        .confirmation-code {
            font-family: monospace;
            font-size: 1.2rem;
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin: 1.5rem 0;
            letter-spacing: 2px;
        }

        /* Profile Section */
        .profile-section {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f8f9fa;
        }

        .profile-photo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 2rem;
            border: 4px solid #eaeaea;
        }

        .profile-info h3 {
            font-size: 1.5rem;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .info-item {
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .info-label {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }

        .info-value {
            font-size: 1.1rem;
            color: #2c3e50;
            font-weight: 500;
        }

        /* Table Container */
        .table-container {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        th {
            text-align: left;
            padding: 1rem;
            background: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
            border-bottom: 2px solid #eaeaea;
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid #eaeaea;
        }

        tr:hover {
            background: #f8f9fa;
        }

        /* Action Buttons */
        .action-btn {
            padding: 0.4rem 0.8rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s;
            margin-right: 0.5rem;
        }

        .btn-primary {
            background: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background: #2980b9;
        }

        .btn-success {
            background: #27ae60;
            color: white;
        }

        .btn-success:hover {
            background: #229954;
        }

        .btn-danger {
            background: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background: #c0392b;
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
        }

        /* Notifications */
        .notification-panel {
            margin-bottom: 2rem;
        }

        .notification-item {
            padding: 1rem;
            background: white;
            border-left: 4px solid #3498db;
            border-radius: 8px;
            margin-bottom: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-item.warning {
            border-left-color: #f39c12;
        }

        .notification-item.success {
            border-left-color: #27ae60;
        }

        .notification-item.danger {
            border-left-color: #e74c3c;
        }

        .notification-time {
            font-size: 0.85rem;
            color: #95a5a6;
        }

        /* Help Section */
        .help-section {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .faq-item {
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #eaeaea;
        }

        .faq-question {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
            cursor: pointer;
        }

        .faq-answer {
            color: #666;
            line-height: 1.6;
        }

        /* Security Settings */
        .security-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .security-item {
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #eaeaea;
        }

        /* Toggle Switch */
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .toggle-slider {
            background-color: #4CAF50;
        }

        input:checked + .toggle-slider:before {
            transform: translateX(26px);
        }

        /* Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 2000;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #2c3e50;
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                width: 200px;
            }

            .main-content {
                padding: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                position: sticky;
                top: 0;
                height: auto;
            }

            .nav-menu {
                display: flex;
                overflow-x: auto;
                padding-bottom: 0.5rem;
            }

            .nav-item {
                white-space: nowrap;
                padding: 0.75rem 1rem;
            }

            .logout-section {
                display: none;
            }

            .profile-header {
                flex-direction: column;
                text-align: center;
            }

            .profile-photo {
                margin-right: 0;
                margin-bottom: 1rem;
            }
        }

        @media (max-width: 480px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .user-info {
                width: 100%;
                justify-content: space-between;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .dashboard-grid, .election-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Hidden logout form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Voting Confirmation Modal -->
    <div class="modal-overlay" id="voteConfirmationModal">
        <div class="modal">
            <div class="modal-title">Confirm Your Vote</div>
            <p>You are about to vote for:</p>
            <div class="selected-candidate" id="selectedCandidateName" style="font-weight: 600; margin: 1rem 0; padding: 1rem; background: #f8f9fa; border-radius: 8px;"></div>
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
                <div style="font-size: 4rem; color: #4CAF50; margin-bottom: 1rem;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="modal-title">Vote Successfully Recorded!</div>
                <p style="color: #666; margin: 1rem 0;">Your vote has been securely recorded and encrypted.</p>
                <div class="confirmation-code" id="voteReferenceCode">VREF-{{ strtoupper(Str::random(8)) }}</div>
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
                Secure<span>Vote</span>
            </div>
            <ul class="nav-menu">
                <li class="nav-item active" onclick="showSection('dashboard')">
                    <i class="nav-icon fas fa-home"></i>Dashboard
                </li>
                <li class="nav-item" onclick="showSection('profile')">
                    <i class="nav-icon fas fa-user"></i>Profile & Verification
                </li>
                <li class="nav-item" onclick="showSection('elections')">
                    <i class="nav-icon fas fa-vote-yea"></i>Active Elections
                </li>
                <li class="nav-item" onclick="showSection('voting')" id="votingNavItem" style="display: none;">
                    <i class="nav-icon fas fa-check-circle"></i>Vote Now
                </li>
                <li class="nav-item" onclick="showSection('history')">
                    <i class="nav-icon fas fa-history"></i>Voting History
                </li>
                <li class="nav-item" onclick="showSection('status')">
                    <i class="nav-icon fas fa-chart-line"></i>Election Status
                </li>
                <li class="nav-item" onclick="showSection('notifications')">
                    <i class="nav-icon fas fa-bell"></i>Notifications
                </li>
                <li class="nav-item" onclick="showSection('help')">
                    <i class="nav-icon fas fa-question-circle"></i>Help & Support
                </li>
                <li class="nav-item" onclick="showSection('security')">
                    <i class="nav-icon fas fa-shield-alt"></i>Security Settings
                </li>
            </ul>

            <div class="logout-section">
                <button class="logout-btn" onclick="performLogout()">
                    <i class="logout-icon fas fa-sign-out-alt"></i>
                    Log Out
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <div class="page-title" id="pageTitle">Voter Dashboard</div>
                <div class="user-info">
                    <div class="verification-badge" id="verificationBadge">
                        <span class="status-badge status-verified">Verified Voter</span>
                    </div>
                    <div class="user-profile" onclick="toggleProfileDropdown()">
                        <i class="fas fa-user-circle fa-lg"></i>
                        <div class="profile-name">{{ Auth::user()->name ?? 'Voter User' }}</div>
                        <i class="fas fa-chevron-down"></i>

                        <div class="profile-dropdown" id="profileDropdown">
                            <a href="#" class="dropdown-item" onclick="showSection('profile')">
                                <i class="fas fa-user"></i> My Profile
                            </a>
                            <a href="#" class="dropdown-item" onclick="showSection('security')">
                                <i class="fas fa-cog"></i> Security Settings
                            </a>
                            <div class="dropdown-item logout" onclick="performLogout()">
                                <i class="fas fa-sign-out-alt"></i> Log Out
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Section -->
            <div id="dashboard" class="dashboard-section active-section">
                <!-- Notifications -->
                <div class="notification-panel">
                    <div class="notification-item success">
                        <div>
                            <strong>Welcome back!</strong> Your identity has been verified via AI facial recognition.
                        </div>
                        <div class="notification-time">Just now</div>
                    </div>
                </div>

                <!-- Dashboard Overview -->
                <div class="dashboard-grid">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Verification Status</div>
                            <i class="fas fa-user-check" style="color: #27ae60;"></i>
                        </div>
                        <div class="card-value" id="verificationStatus">Verified</div>
                        <div class="card-subtitle">AI Verification Score: 98%</div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Active Elections</div>
                            <i class="fas fa-vote-yea" style="color: #3498db;"></i>
                        </div>
                        <div class="card-value" id="activeElectionsCount">2</div>
                        <div class="card-subtitle">Available to vote in</div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Voting Status</div>
                            <i class="fas fa-check-circle" style="color: #27ae60;"></i>
                        </div>
                        <div class="card-value" id="votingStatus">Voted</div>
                        <div class="card-subtitle">In 1 of 2 elections</div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Next Election</div>
                            <i class="fas fa-clock" style="color: #e74c3c;"></i>
                        </div>
                        <div class="countdown-label">Time Remaining:</div>
                        <div class="countdown-timer" id="electionCountdown">23:45:12</div>
                    </div>
                </div>

                <!-- Active Elections Panel -->
                <div class="card" style="margin-top: 2rem;">
                    <div class="card-header">
                        <div class="card-title">Your Active Elections</div>
                    </div>
                    <div class="election-grid" id="activeElectionsList">
                        <!-- Election cards will be dynamically loaded -->
                    </div>
                </div>
            </div>

            <!-- Profile & Verification Section -->
            <div id="profile" class="dashboard-section">
                <div class="profile-section">
                    <div class="profile-header">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Voter') }}&background=4CAF50&color=fff&size=100"
                             alt="Profile" class="profile-photo">
                        <div class="profile-info">
                            <h3>{{ Auth::user()->name ?? 'Voter User' }}</h3>
                            <div class="status-badge status-verified">Verified Voter</div>
                            <p style="color: #666; margin-top: 0.5rem;">Registered Voter ID: V-{{ str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>

                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Email Address</div>
                            <div class="info-value">{{ Auth::user()->email ?? 'voter@example.com' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Identity Verification</div>
                            <div class="info-value">
                                <span class="status-badge status-verified">Verified</span>
                                <small style="display: block; color: #666; margin-top: 0.25rem;">AI Facial Recognition</small>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Registration Date</div>
                            <div class="info-value">{{ date('F d, Y') }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Last Login</div>
                            <div class="info-value">{{ date('Y-m-d H:i') }} from {{ request()->ip() }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">AI Behavior Score</div>
                            <div class="info-value">95% Consistency</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Registered Device</div>
                            <div class="info-value">This Device (Verified)</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Elections Section -->
            <div id="elections" class="dashboard-section">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Elections Available for Voting</div>
                        <div class="card-subtitle">Select an election to cast your vote</div>
                    </div>
                    <div class="election-grid">
                        <!-- Presidential Election -->
                        <div class="election-card">
                            <div class="election-header">
                                <div class="election-title">Presidential Election 2024</div>
                                <span class="status-badge status-active">Active</span>
                            </div>
                            <div class="election-time">
                                <i class="fas fa-clock"></i> Ends: Dec 15, 2024 23:59
                            </div>
                            <div class="election-description">
                                Vote for the next President of the organization. Candidates have presented their platforms for digital transformation and member welfare.
                            </div>
                            <button class="vote-btn" onclick="startVoting('president')">Vote Now</button>
                        </div>

                        <!-- Board Election -->
                        <div class="election-card">
                            <div class="election-header">
                                <div class="election-title">Board of Directors Election</div>
                                <span class="status-badge status-active">Active</span>
                            </div>
                            <div class="election-time">
                                <i class="fas fa-clock"></i> Ends: Dec 20, 2024 23:59
                            </div>
                            <div class="election-description">
                                Elect 5 members to the Board of Directors. Each voter can select up to 5 candidates.
                            </div>
                            <button class="vote-btn voted" disabled>
                                <i class="fas fa-check"></i> Already Voted
                            </button>
                        </div>

                        <!-- Referendum -->
                        <div class="election-card">
                            <div class="election-header">
                                <div class="election-title">Constitutional Amendment</div>
                                <span class="status-badge status-closed">Closed</span>
                            </div>
                            <div class="election-time">
                                <i class="fas fa-clock"></i> Ended: Dec 10, 2024
                            </div>
                            <div class="election-description">
                                Vote on proposed amendments to the organization's constitution regarding membership criteria.
                            </div>
                            <button class="vote-btn closed" disabled>Voting Closed</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Voting Interface -->
            <div id="voting" class="dashboard-section">
                <div class="voting-interface">
                    <h2 style="color: #2c3e50; margin-bottom: 1rem;" id="votingElectionTitle">Presidential Election 2024</h2>
                    <p style="color: #666; margin-bottom: 2rem;">Select your preferred candidate. You can review your selection before submitting.</p>

                    <div class="candidate-list" id="candidateList">
                        <!-- Candidates will be dynamically loaded -->
                    </div>

                    <div style="text-align: center; margin-top: 2rem;">
                        <button class="action-btn btn-secondary" onclick="showSection('elections')">Back to Elections</button>
                        <button class="action-btn btn-primary" id="reviewVoteBtn" onclick="reviewVote()" style="display: none;">
                            <i class="fas fa-eye"></i> Review Vote
                        </button>
                    </div>
                </div>
            </div>

            <!-- Voting History -->
            <div id="history" class="dashboard-section">
                <div class="table-container">
                    <h3 class="card-title">Voting History</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Election</th>
                                <th>Date & Time</th>
                                <th>Status</th>
                                <th>Reference Code</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Board of Directors Election</td>
                                <td>Dec 12, 2024 14:30</td>
                                <td><span class="status-badge status-verified">Recorded</span></td>
                                <td><code>VREF-{{ strtoupper(Str::random(8)) }}</code></td>
                                <td>
                                    <button class="action-btn btn-primary" onclick="viewReceipt('{{ strtoupper(Str::random(8)) }}')">
                                        <i class="fas fa-receipt"></i> Receipt
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>Annual Budget Approval</td>
                                <td>Nov 30, 2024 10:15</td>
                                <td><span class="status-badge status-verified">Recorded</span></td>
                                <td><code>VREF-{{ strtoupper(Str::random(8)) }}</code></td>
                                <td>
                                    <button class="action-btn btn-primary" onclick="viewReceipt('{{ strtoupper(Str::random(8)) }}')">
                                        <i class="fas fa-receipt"></i> Receipt
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Election Status -->
            <div id="status" class="dashboard-section">
                <div class="dashboard-grid">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Total Votes Cast</div>
                            <i class="fas fa-chart-bar" style="color: #3498db;"></i>
                        </div>
                        <div class="card-value" id="totalVotesCast">1,247</div>
                        <div class="card-subtitle">Across all active elections</div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Turnout Percentage</div>
                            <i class="fas fa-percentage" style="color: #27ae60;"></i>
                        </div>
                        <div class="card-value" id="turnoutPercentage">62.3%</div>
                        <div class="card-subtitle">Of registered voters</div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Live Progress</div>
                            <i class="fas fa-tachometer-alt" style="color: #e74c3c;"></i>
                        </div>
                        <div class="card-value" id="votingProgress">45%</div>
                        <div class="progress-bar" style="margin-top: 1rem;">
                            <div class="progress-fill" style="width: 45%; height: 8px; background: #4CAF50; border-radius: 4px;"></div>
                        </div>
                    </div>
                </div>

                <div class="card" style="margin-top: 2rem;">
                    <div class="card-header">
                        <div class="card-title">Live Election Updates</div>
                    </div>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Election</th>
                                    <th>Status</th>
                                    <th>Votes Cast</th>
                                    <th>Turnout</th>
                                    <th>Time Remaining</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Presidential Election 2024</td>
                                    <td><span class="status-badge status-active">Active</span></td>
                                    <td>892</td>
                                    <td>44.6%</td>
                                    <td>2 days, 5 hours</td>
                                </tr>
                                <tr>
                                    <td>Board of Directors Election</td>
                                    <td><span class="status-badge status-active">Active</span></td>
                                    <td>1,247</td>
                                    <td>62.3%</td>
                                    <td>7 days, 5 hours</td>
                                </tr>
                                <tr>
                                    <td>Constitutional Amendment</td>
                                    <td><span class="status-badge status-closed">Closed</span></td>
                                    <td>1,845</td>
                                    <td>92.2%</td>
                                    <td>Results pending</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Notifications -->
            <div id="notifications" class="dashboard-section">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Notifications & Alerts</div>
                    </div>
                    <div class="notification-panel">
                        <div class="notification-item success">
                            <div>
                                <strong>Identity Verified</strong><br>
                                Your facial recognition verification was successful.
                            </div>
                            <div class="notification-time">2 hours ago</div>
                        </div>
                        <div class="notification-item">
                            <div>
                                <strong>Election Reminder</strong><br>
                                Presidential Election ends in 2 days. Cast your vote soon!
                            </div>
                            <div class="notification-time">1 day ago</div>
                        </div>
                        <div class="notification-item warning">
                            <div>
                                <strong>Security Alert</strong><br>
                                New login detected from different device. Was this you?
                            </div>
                            <div class="notification-time">2 days ago</div>
                        </div>
                        <div class="notification-item">
                            <div>
                                <strong>System Update</strong><br>
                                Voting system maintenance scheduled for Sunday 2 AM - 4 AM.
                            </div>
                            <div class="notification-time">3 days ago</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Help & Support -->
            <div id="help" class="dashboard-section">
                <div class="help-section">
                    <h3 style="color: #2c3e50; margin-bottom: 2rem;">Help & Support Center</h3>

                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <i class="fas fa-chevron-right" style="margin-right: 0.5rem;"></i>
                            How do I cast my vote?
                        </div>
                        <div class="faq-answer" style="display: none;">
                            <p>To cast your vote:</p>
                            <ol style="margin-left: 1.5rem; margin-top: 0.5rem;">
                                <li>Go to the "Active Elections" section</li>
                                <li>Click "Vote Now" on the election you want to participate in</li>
                                <li>Select your preferred candidate(s)</li>
                                <li>Review your selection and confirm</li>
                                <li>Save your vote receipt for reference</li>
                            </ol>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <i class="fas fa-chevron-right" style="margin-right: 0.5rem;"></i>
                            Can I change my vote after submitting?
                        </div>
                        <div class="faq-answer" style="display: none;">
                            No, for security and integrity reasons, votes cannot be changed once submitted. Please review your selection carefully before confirming.
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <i class="fas fa-chevron-right" style="margin-right: 0.5rem;"></i>
                            How is my vote kept secret?
                        </div>
                        <div class="faq-answer" style="display: none;">
                            We use end-to-end encryption to ensure vote secrecy. Your identity is separated from your vote choice, and only anonymized, aggregated results are visible.
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <i class="fas fa-chevron-right" style="margin-right: 0.5rem;"></i>
                            What if I encounter technical issues?
                        </div>
                        <div class="faq-answer" style="display: none;">
                            Contact support immediately at support@securevote.example.com or call +1-800-VOTE-NOW. Include your voter ID and reference code if available.
                        </div>
                    </div>

                    <div style="margin-top: 3rem; padding-top: 2rem; border-top: 2px solid #f8f9fa;">
                        <h4 style="color: #2c3e50; margin-bottom: 1rem;">Contact Support</h4>
                        <div style="display: flex; gap: 2rem; flex-wrap: wrap;">
                            <div>
                                <p><strong>Email:</strong><br>support@securevote.example.com</p>
                            </div>
                            <div>
                                <p><strong>Phone:</strong><br>+1-800-VOTE-NOW (868-3669)</p>
                            </div>
                            <div>
                                <p><strong>Hours:</strong><br>24/7 during election periods</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Settings -->
            <div id="security" class="dashboard-section">
                <div class="profile-section">
                    <h3 style="color: #2c3e50; margin-bottom: 2rem;">Account Security Settings</h3>

                    <div class="security-grid">
                        <div class="security-item">
                            <h4 style="color: #2c3e50; margin-bottom: 1rem;">Two-Factor Authentication</h4>
                            <p style="color: #666; margin-bottom: 1rem;">Add an extra layer of security to your account</p>
                            <label class="toggle-switch">
                                <input type="checkbox" id="twoFactorToggle" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="security-item">
                            <h4 style="color: #2c3e50; margin-bottom: 1rem;">Change Password</h4>
                            <p style="color: #666; margin-bottom: 1rem;">Update your account password</p>
                            <button class="action-btn btn-primary" onclick="showPasswordModal()">
                                <i class="fas fa-key"></i> Change Password
                            </button>
                        </div>

                        <div class="security-item">
                            <h4 style="color: #2c3e50; margin-bottom: 1rem;">Login History</h4>
                            <p style="color: #666; margin-bottom: 1rem;">View recent account activity</p>
                            <button class="action-btn btn-secondary" onclick="showLoginHistory()">
                                <i class="fas fa-history"></i> View History
                            </button>
                        </div>

                        <div class="security-item">
                            <h4 style="color: #2c3e50; margin-bottom: 1rem;">Logout All Sessions</h4>
                            <p style="color: #666; margin-bottom: 1rem;">Sign out from all devices</p>
                            <button class="action-btn btn-danger" onclick="logoutAllSessions()">
                                <i class="fas fa-sign-out-alt"></i> Logout Everywhere
                            </button>
                        </div>
                    </div>

                    <div class="table-container" style="margin-top: 2rem;">
                        <h4 style="color: #2c3e50; margin-bottom: 1rem;">Recent Login Activity</h4>
                        <table>
                            <thead>
                                <tr>
                                    <th>Date & Time</th>
                                    <th>Device</th>
                                    <th>Location</th>
                                    <th>IP Address</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ date('Y-m-d H:i') }}</td>
                                    <td>Chrome on Windows</td>
                                    <td>New York, USA</td>
                                    <td>{{ request()->ip() }}</td>
                                    <td><span class="status-badge status-active">Current</span></td>
                                </tr>
                                <tr>
                                    <td>{{ date('Y-m-d', strtotime('-1 day')) }} 14:30</td>
                                    <td>Safari on iPhone</td>
                                    <td>New York, USA</td>
                                    <td>192.168.1.100</td>
                                    <td><span class="status-badge status-verified">Success</span></td>
                                </tr>
                                <tr>
                                    <td>{{ date('Y-m-d', strtotime('-2 days')) }} 09:15</td>
                                    <td>Firefox on Windows</td>
                                    <td>Boston, USA</td>
                                    <td>203.0.113.1</td>
                                    <td><span class="status-badge status-verified">Success</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Global variables
        let selectedCandidate = null;
        let currentElection = null;

        // Navigation function
        function showSection(sectionId) {
            // Hide all sections
            document.querySelectorAll('.dashboard-section').forEach(section => {
                section.classList.remove('active-section');
            });

            // Remove active class from all nav items
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
            });

            // Show selected section
            const selectedSection = document.getElementById(sectionId);
            if (selectedSection) {
                selectedSection.classList.add('active-section');
            }

            // Add active class to clicked nav item
            event.currentTarget.classList.add('active');

            // Update page title
            const titles = {
                'dashboard': 'Voter Dashboard',
                'profile': 'Profile & Verification',
                'elections': 'Active Elections',
                'voting': 'Vote Now',
                'history': 'Voting History',
                'status': 'Election Status',
                'notifications': 'Notifications',
                'help': 'Help & Support',
                'security': 'Security Settings'
            };
            document.getElementById('pageTitle').textContent = titles[sectionId] || 'Voter Dashboard';

            // Close profile dropdown
            document.getElementById('profileDropdown').classList.remove('active');
        }

        // Toggle profile dropdown
        function toggleProfileDropdown() {
            document.getElementById('profileDropdown').classList.toggle('active');
            event.stopPropagation();
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const profileDropdown = document.getElementById('profileDropdown');
            const userProfile = document.querySelector('.user-profile');

            if (!userProfile.contains(event.target)) {
                profileDropdown.classList.remove('active');
            }
        });

        // Logout function
        function performLogout() {
            document.getElementById('logout-form').submit();
        }

        // Start voting process
        function startVoting(electionType) {
            currentElection = electionType;
            const electionTitle = electionType === 'president'
                ? 'Presidential Election 2024'
                : 'Board of Directors Election';

            document.getElementById('votingElectionTitle').textContent = electionTitle;

            // Load candidates based on election type
            loadCandidates(electionType);

            // Show voting section
            showSection('voting');
            document.getElementById('votingNavItem').style.display = 'block';
        }

        // Load candidates
        function loadCandidates(electionType) {
            const candidateList = document.getElementById('candidateList');
            let candidates = [];

            if (electionType === 'president') {
                candidates = [
                    {
                        id: 1,
                        name: 'John Smith',
                        party: 'Progressive Alliance',
                        description: 'Focus on digital transformation and member welfare. 10 years experience in leadership.',
                        photo: 'https://ui-avatars.com/api/?name=John+Smith&background=3498db&color=fff&size=80'
                    },
                    {
                        id: 2,
                        name: 'Jane Doe',
                        party: 'Unity Coalition',
                        description: 'Advocate for transparency and financial reform. Former finance committee chair.',
                        photo: 'https://ui-avatars.com/api/?name=Jane+Doe&background=9b59b6&color=fff&size=80'
                    },
                    {
                        id: 3,
                        name: 'Robert Johnson',
                        party: 'Independent',
                        description: 'Running on platform of innovation and youth engagement. Tech entrepreneur.',
                        photo: 'https://ui-avatars.com/api/?name=Robert+Johnson&background=2ecc71&color=fff&size=80'
                    }
                ];
            }

            let html = '';
            candidates.forEach(candidate => {
                html += `
                    <div class="candidate-card" onclick="selectCandidate(${candidate.id})" id="candidate-${candidate.id}">
                        <img src="${candidate.photo}" alt="${candidate.name}" class="candidate-photo">
                        <div class="candidate-info">
                            <div class="candidate-name">${candidate.name}</div>
                            <div style="color: #666; margin-bottom: 0.5rem;">${candidate.party}</div>
                            <div class="candidate-description">${candidate.description}</div>
                        </div>
                        <button class="candidate-select" onclick="selectCandidate(${candidate.id}); event.stopPropagation();">
                            Select
                        </button>
                    </div>
                `;
            });

            candidateList.innerHTML = html;
            selectedCandidate = null;
            document.getElementById('reviewVoteBtn').style.display = 'none';
        }

        // Select candidate
        function selectCandidate(candidateId) {
            // Remove selected class from all candidates
            document.querySelectorAll('.candidate-card').forEach(card => {
                card.classList.remove('selected');
            });

            // Add selected class to chosen candidate
            const selectedCard = document.getElementById(`candidate-${candidateId}`);
            selectedCard.classList.add('selected');

            // Get candidate name
            const candidateName = selectedCard.querySelector('.candidate-name').textContent;
            selectedCandidate = { id: candidateId, name: candidateName };

            // Show review button
            document.getElementById('reviewVoteBtn').style.display = 'inline-block';
        }

        // Review vote before submission
        function reviewVote() {
            if (!selectedCandidate) {
                alert('Please select a candidate first.');
                return;
            }

            document.getElementById('selectedCandidateName').textContent = selectedCandidate.name;
            document.getElementById('voteConfirmationModal').classList.add('active');
        }

        // Close vote confirmation
        function closeVoteConfirmation() {
            document.getElementById('voteConfirmationModal').classList.remove('active');
        }

        // Submit vote
        function submitVote() {
            // Simulate vote submission
            console.log('Vote submitted for:', selectedCandidate);

            // Close confirmation modal
            closeVoteConfirmation();

            // Show success modal
            document.getElementById('voteSuccessModal').classList.add('active');

            // Update voting status
            document.getElementById('votingStatus').textContent = 'Voted';

            // Update active elections count
            const count = parseInt(document.getElementById('activeElectionsCount').textContent);
            document.getElementById('activeElectionsCount').textContent = count - 1;
        }

        // Close vote success modal
        function closeVoteSuccess() {
            document.getElementById('voteSuccessModal').classList.remove('active');
            showSection('dashboard');
        }

        // View vote receipt
        function viewReceipt(refCode) {
            alert(`Vote Receipt\nReference Code: ${refCode}\nDate: ${new Date().toLocaleDateString()}\nStatus: Successfully Recorded`);
        }

        // Toggle FAQ answers
        function toggleFAQ(element) {
            const answer = element.parentElement.querySelector('.faq-answer');
            const icon = element.querySelector('i');

            if (answer.style.display === 'none') {
                answer.style.display = 'block';
                icon.classList.remove('fa-chevron-right');
                icon.classList.add('fa-chevron-down');
            } else {
                answer.style.display = 'none';
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-right');
            }
        }

        // Password change modal
        function showPasswordModal() {
            alert('Password change functionality would open here. In a real implementation, this would show a secure password change form.');
        }

        // Show login history
        function showLoginHistory() {
            alert('Detailed login history would be displayed here.');
        }

        // Logout all sessions
        function logoutAllSessions() {
            if (confirm('Are you sure you want to logout from all devices? You will need to login again.')) {
                // In real implementation, this would call an API endpoint
                alert('All other sessions have been logged out.');
            }
        }

        // Countdown timer
        function updateCountdown() {
            const targetDate = new Date();
            targetDate.setDate(targetDate.getDate() + 2); // 2 days from now
            targetDate.setHours(23, 59, 59, 0);

            function update() {
                const now = new Date().getTime();
                const distance = targetDate - now;

                if (distance < 0) {
                    document.getElementById('electionCountdown').textContent = "00:00:00";
                    return;
                }

                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById('electionCountdown').textContent =
                    `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }

            update();
            setInterval(update, 1000);
        }

        // Initialize active elections list
        function initActiveElections() {
            const elections = [
                { title: 'Presidential Election', votes: 892, total: 2000, time: '2 days left' },
                { title: 'Board Election', votes: 1247, total: 2000, time: '7 days left' }
            ];

            let html = '';
            elections.forEach(election => {
                const percentage = Math.round((election.votes / election.total) * 100);
                html += `
                    <div class="election-card">
                        <div class="election-header">
                            <div class="election-title">${election.title}</div>
                            <span class="status-badge status-active">Active</span>
                        </div>
                        <div class="election-time">
                            <i class="fas fa-clock"></i> ${election.time}
                        </div>
                        <div style="margin: 1rem 0;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span>Progress: ${percentage}%</span>
                                <span>${election.votes}/${election.total} votes</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: ${percentage}%; height: 8px; background: #4CAF50; border-radius: 4px;"></div>
                            </div>
                        </div>
                    </div>
                `;
            });

            document.getElementById('activeElectionsList').innerHTML = html;
        }

        // Initialize the dashboard
        document.addEventListener('DOMContentLoaded', function() {
            updateCountdown();
            initActiveElections();

            // Simulate real-time updates
            setInterval(() => {
                // Update total votes
                const votesElement = document.getElementById('totalVotesCast');
                if (votesElement) {
                    const currentVotes = parseInt(votesElement.textContent.replace(',', ''));
                    votesElement.textContent = (currentVotes + Math.floor(Math.random() * 3)).toLocaleString();
                }

                // Update turnout percentage
                const turnoutElement = document.getElementById('turnoutPercentage');
                if (turnoutElement) {
                    const currentTurnout = parseFloat(turnoutElement.textContent);
                    turnoutElement.textContent = (currentTurnout + 0.1).toFixed(1) + '%';
                }
            }, 10000); // Update every 10 seconds
        });
    </script>
</body>
</html>
