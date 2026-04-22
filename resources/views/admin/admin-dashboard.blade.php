<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - Real-Time Voting System</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @include('admin.sections.dashboard-styles')
</head>
<body>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>

    <!-- Logout Modal -->
    <div class="modal-overlay" id="logoutModal">
        <div class="modal">
            <div class="modal-title">Confirm Logout</div>
            <div class="modal-content">Are you sure you want to log out?</div>
            <div class="modal-actions">
                <button class="modal-btn cancel" onclick="closeLogoutModal()">Cancel</button>
                <button class="modal-btn logout" onclick="performLogout()">Log Out</button>
            </div>
        </div>
    </div>

    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <div class="logo-text">
                    <span class="main">Real-Time <span style="color: var(--cpsu-gold);">Voting</span></span>
                    <span class="sub">Admin Dashboard</span>
                </div>
            </div>
            <ul class="nav-menu">
                <li class="nav-item active" onclick="showSection('overview')">
                    <i class="nav-icon fas fa-chart-bar"></i>Dashboard Overview
                </li>
                <li class="nav-item" onclick="showSection('results')">
                    <i class="nav-icon fas fa-poll"></i>Live Results
                </li>
                <li class="nav-item" onclick="showSection('voters')">
                    <i class="nav-icon fas fa-users"></i>Voter Management
                </li>
                <li class="nav-item" onclick="showSection('candidates')">
                    <i class="nav-icon fas fa-user-tie"></i>Candidates
                </li>
                <li class="nav-item" onclick="showSection('elections')">
                    <i class="nav-icon fas fa-vote-yea"></i>Election Management
                </li>
                <li class="nav-item" onclick="showSection('ai-alerts')">
                    <i class="nav-icon fas fa-robot"></i>AI Fraud Detection
                </li>
                <li class="nav-item" onclick="showSection('audit')">
                    <i class="nav-icon fas fa-clipboard-list"></i>Audit Logs
                </li>
                <li class="nav-item" onclick="showSection('monitoring')">
                    <i class="nav-icon fas fa-tachometer-alt"></i>System Monitoring
                </li>
                <li class="nav-item" onclick="showSection('reports')">
                    <i class="nav-icon fas fa-chart-pie"></i>Reports & Analytics
                </li>
                <li class="nav-item" onclick="showSection('completed')">
                    <i class="nav-icon fas fa-check-circle"></i>Completed Elections
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.accounts') }}" style="display:flex; align-items:center; color:inherit; text-decoration:none; width:100%;">
                        <i class="nav-icon fas fa-user-cog"></i>Account Management
                    </a>
                </li>
            </ul>
            <div class="logout-section">
                <button class="logout-btn" onclick="openLogoutModal()">
                    <i class="logout-icon fas fa-sign-out-alt"></i> Log Out
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <div class="page-title" id="pageTitle">Dashboard Overview</div>
                <div class="user-info">
                    <div class="alert-badge" id="aiAlertCount">3 AI Alerts</div>
                    <div class="user-profile" onclick="toggleProfileDropdown()">
                        <i class="fas fa-user-circle fa-lg"></i>
                        <div class="profile-name">{{ Auth::user()->name ?? 'Admin User' }}</div>
                        <i class="fas fa-chevron-down"></i>
                        <div class="profile-dropdown" id="profileDropdown">
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                <i class="fas fa-user"></i> My Profile
                            </a>
                            <div class="dropdown-item logout" onclick="openLogoutModal()">
                                <i class="fas fa-sign-out-alt"></i> Log Out
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('admin.sections.overview')
            @include('admin.sections.results')
            @include('admin.sections.voters')
            @include('admin.sections.candidates')
            @include('admin.sections.ai-alerts')
            @include('admin.sections.elections')
            @include('admin.sections.audit')
            @include('admin.sections.monitoring')
            @include('admin.sections.reports')
            @include('admin.sections.completed')
        </div>
    </div>

    @include('admin.sections.dashboard-scripts')
</body>
</html>
