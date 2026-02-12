<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - Real-Time Voting System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Chart.js for data visualization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Font Awesome for icons -->
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
            background: linear-gradient(180deg, #1e3c72 0%, #2a5298 100%);
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
            color: #3498db;
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
        }

        .nav-item:hover, .nav-item.active {
            background: rgba(255,255,255,0.1);
            border-left: 4px solid #3498db;
        }

        .nav-icon {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        /* Logout section in sidebar */
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

        .alert-badge {
            background: #e74c3c;
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.9rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }

        /* Dashboard Cards */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: transform 0.3s, box-shadow 0.3s;
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
            font-size: 1rem;
            color: #7f8c8d;
            font-weight: 500;
        }

        .card-value {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .card-change {
            font-size: 0.9rem;
            color: #27ae60;
        }

        .card-change.negative {
            color: #e74c3c;
        }

        .progress-bar {
            height: 8px;
            background: #ecf0f1;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 0.5rem;
        }

        .progress-fill {
            height: 100%;
            background: #3498db;
            border-radius: 4px;
            transition: width 0.5s ease;
        }

        /* Charts Section */
        .charts-section {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            height: 400px;
            display: flex;
            flex-direction: column;
        }

        .chart-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #2c3e50;
        }

        .chart-container canvas {
            flex: 1;
            width: 100% !important;
            height: 100% !important;
        }

        /* Tables */
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
            min-width: 800px;
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

        .status-blocked {
            background: #f8d7da;
            color: #721c24;
        }

        .status-active {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-flagged {
            background: #f8d7da;
            color: #721c24;
            animation: blink 1s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
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

        .btn-view {
            background: #3498db;
            color: white;
        }

        .btn-view:hover {
            background: #2980b9;
        }

        .btn-edit {
            background: #f39c12;
            color: white;
        }

        .btn-edit:hover {
            background: #e67e22;
        }

        .btn-delete {
            background: #e74c3c;
            color: white;
        }

        .btn-delete:hover {
            background: #c0392b;
        }

        .btn-approve {
            background: #27ae60;
            color: white;
        }

        .btn-approve:hover {
            background: #229954;
        }

        .btn-dismiss {
            background: #95a5a6;
            color: white;
        }

        .btn-dismiss:hover {
            background: #7f8c8d;
        }

        /* Alert Panel */
        .alert-panel {
            background: #fff3cd;
            border-left: 4px solid #f39c12;
            padding: 1rem;
            margin-bottom: 2rem;
            border-radius: 0 8px 8px 0;
        }

        .alert-title {
            font-weight: 600;
            color: #856404;
            margin-bottom: 0.5rem;
        }

        .alert-content {
            color: #856404;
        }

        /* Stats Row */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-item {
            text-align: center;
            padding: 1rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #3498db;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #7f8c8d;
            margin-top: 0.5rem;
        }

        /* Tabs */
        .tabs {
            display: flex;
            border-bottom: 2px solid #eaeaea;
            margin-bottom: 1.5rem;
        }

        .tab {
            padding: 1rem 1.5rem;
            cursor: pointer;
            font-weight: 500;
            color: #7f8c8d;
            border-bottom: 2px solid transparent;
            transition: all 0.3s;
            user-select: none;
        }

        .tab.active {
            color: #3498db;
            border-bottom: 2px solid #3498db;
        }

        .tab:hover:not(.active) {
            color: #3498db;
            background: #f8f9fa;
        }

        /* Dashboard Sections */
        .dashboard-section {
            display: none;
        }

        .dashboard-section.active-section {
            display: block;
        }

        /* Search Input */
        .search-input {
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.9rem;
            min-width: 250px;
        }

        /* Logout Confirmation Modal */
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
            max-width: 400px;
            width: 90%;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #2c3e50;
        }

        .modal-content {
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .modal-btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
            font-family: inherit;
            font-size: 1rem;
        }

        .modal-btn.cancel {
            background: #f8f9fa;
            color: #666;
            border: 1px solid #ddd;
        }

        .modal-btn.cancel:hover {
            background: #eaeaea;
        }

        .modal-btn.logout {
            background: #e74c3c;
            color: white;
        }

        .modal-btn.logout:hover {
            background: #c0392b;
        }

        /* Election Management Styles */
        .election-tabs {
            display: flex;
            border-bottom: 2px solid #eaeaea;
            margin-bottom: 2rem;
            overflow-x: auto;
        }

        .election-tab {
            padding: 1rem 1.5rem;
            cursor: pointer;
            font-weight: 500;
            color: #7f8c8d;
            border-bottom: 2px solid transparent;
            transition: all 0.3s;
            white-space: nowrap;
            user-select: none;
        }

        .election-tab.active {
            color: #3498db;
            border-bottom: 2px solid #3498db;
        }

        .election-tab:hover:not(.active) {
            color: #3498db;
            background: #f8f9fa;
        }

        .election-section {
            display: none;
        }

        .election-section.active-election-section {
            display: block;
        }

        /* Form Styles */
        .election-form {
            padding: 1.5rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-input {
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.9rem;
            transition: border-color 0.3s;
        }

        .form-input:focus {
            outline: none;
            border-color: #3498db;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #eaeaea;
        }

        /* Management Grid */
        .manage-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .manage-content {
            padding: 1rem 0;
        }

        .manage-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .manage-item:last-child {
            border-bottom: none;
        }

        /* Security Grid */
        .security-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .security-content {
            padding: 1rem 0;
        }

        .security-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .security-item:last-child {
            border-bottom: none;
        }

        .alert-count {
            background: #e74c3c;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .countdown-timer {
            text-align: center;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
            margin-top: 1rem;
        }

        .countdown-display {
            font-size: 1.5rem;
            font-weight: 700;
            color: #e74c3c;
            font-family: 'Courier New', monospace;
        }

        /* Emergency Buttons */
        .emergency-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem;
            margin-bottom: 0.5rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
            width: 100%;
            justify-content: center;
            font-size: 0.9rem;
        }

        .emergency-btn.pause {
            background: #f39c12;
            color: white;
        }

        .emergency-btn.pause:hover {
            background: #e67e22;
        }

        .emergency-btn.close {
            background: #e74c3c;
            color: white;
        }

        .emergency-btn.close:hover {
            background: #c0392b;
        }

        .emergency-btn.lock {
            background: #9b59b6;
            color: white;
        }

        .emergency-btn.lock:hover {
            background: #8e44ad;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .charts-section {
                grid-template-columns: 1fr;
            }

            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }

            .manage-grid,
            .security-grid {
                grid-template-columns: 1fr;
            }
        }

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

            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .stats-row {
                grid-template-columns: 1fr;
            }

            .logout-section {
                display: none;
            }

            .user-profile .profile-name {
                display: none;
            }

            .charts-section {
                grid-template-columns: 1fr;
            }

            .chart-container {
                height: 300px;
            }

            .election-tabs {
                padding-bottom: 0.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
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
        }

        /* Toggle Slider Styles */
        .toggle-label {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
            cursor: pointer;
        }

        .toggle-label input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            border-radius: 24px;
            transition: 0.4s;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            border-radius: 50%;
            transition: 0.4s;
        }

        input:checked + .toggle-slider {
            background-color: #27ae60;
        }

        input:checked + .toggle-slider:before {
            transform: translateX(26px);
        }
    </style>
</head>
<body>
    <!-- Hidden logout form for Laravel -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Logout Confirmation Modal -->
    <div class="modal-overlay" id="logoutModal">
        <div class="modal">
            <div class="modal-title">Confirm Logout</div>
            <div class="modal-content">
                Are you sure you want to log out? You will need to sign in again to access the admin dashboard.
            </div>
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
                Real-Time <span>Voting</span>
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
            </ul>

            <!-- Logout Button in Sidebar -->
            <div class="logout-section">
                <button class="logout-btn" onclick="openLogoutModal()">
                    <i class="logout-icon fas fa-sign-out-alt"></i>
                    Log Out
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

                        <!-- Profile Dropdown -->
                        <div class="profile-dropdown" id="profileDropdown">
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                <i class="fas fa-user"></i> My Profile
                            </a>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-cog"></i> Account Settings
                            </a>
                            <div class="dropdown-item logout" onclick="openLogoutModal()">
                                <i class="fas fa-sign-out-alt"></i> Log Out
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Overview Section -->
            <div id="overview" class="dashboard-section active-section">
                <!-- Alert Panel -->
                <div class="alert-panel">
                    <div class="alert-title"><i class="fas fa-exclamation-triangle"></i> AI Security Alert</div>
                    <div class="alert-content">Multiple voting attempts detected from IP: 192.168.1.100. Confidence: 85%</div>
                </div>

                <!-- Quick Stats -->
                <div class="stats-row">
                    <div class="stat-item">
                        <div class="stat-value" id="totalVoters">{{ number_format($totalVoters) }}</div>
                        <div class="stat-label">Registered Voters</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="votesCast">{{ number_format($votesCast) }}</div>
                        <div class="stat-label">Votes Cast</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="participationRate">{{ $participationRate }}%</div>
                        <div class="stat-label">Participation Rate</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="activeElections">{{ $activeElections }}</div>
                        <div class="stat-label">Active Elections</div>
                    </div>
                </div>

                <!-- Main Cards -->
                <div class="dashboard-grid">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Voting Progress</div>
                            <i class="fas fa-chart-line text-primary"></i>
                        </div>
                        <div class="card-value">71.2%</div>
                        <div class="card-change">+2.5% from yesterday</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 71.2%"></div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">AI Security Alerts</div>
                            <i class="fas fa-shield-alt text-danger"></i>
                        </div>
                        <div class="card-value">3</div>
                        <div class="card-change negative">+1 new alert</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 30%; background: #e74c3c;"></div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">System Health</div>
                            <i class="fas fa-heartbeat text-success"></i>
                        </div>
                        <div class="card-value">100%</div>
                        <div class="card-change">All systems operational</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 100%; background: #27ae60;"></div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Active Users</div>
                            <i class="fas fa-user-clock text-info"></i>
                        </div>
                        <div class="card-value">47</div>
                        <div class="card-change">+12 in last hour</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 60%"></div>
                        </div>
                    </div>
                </div>

                <!-- Charts -->
                <div class="charts-section">
                    <div class="chart-container">
                        <div class="chart-title">Votes Per Hour (Today)</div>
                        <canvas id="votesChart"></canvas>
                    </div>
                    <div class="chart-container">
                        <div class="chart-title">Election Status Distribution</div>
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Live Results Section -->
            <div id="results" class="dashboard-section">
                <div class="tabs">
                    <div class="tab active" onclick="showElectionTab('president')">President</div>
                    <div class="tab" onclick="showElectionTab('vice-president')">Vice President</div>
                    <div class="tab" onclick="showElectionTab('secretary')">Secretary</div>
                </div>

                <div class="chart-container">
                    <div class="chart-title">Live Presidential Election Results</div>
                    <canvas id="resultsChart"></canvas>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Candidate</th>
                                <th>Votes</th>
                                <th>Percentage</th>
                                <th>Trend</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>John Doe (Party A)</td>
                                <td>450</td>
                                <td>45%</td>
                                <td><span class="card-change"><i class="fas fa-arrow-up"></i> +12 votes/min</span></td>
                            </tr>
                            <tr>
                                <td>Jane Smith (Party B)</td>
                                <td>380</td>
                                <td>38%</td>
                                <td><span class="card-change"><i class="fas fa-arrow-up"></i> +8 votes/min</span></td>
                            </tr>
                            <tr>
                                <td>Bob Johnson (Independent)</td>
                                <td>170</td>
                                <td>17%</td>
                                <td><span class="card-change negative"><i class="fas fa-arrow-down"></i> -2 votes/min</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Voter Management Section -->
            <div id="voters" class="dashboard-section">
                <div class="table-container">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <h3 class="chart-title">Voter Management</h3>
                        <input type="text" class="search-input" id="voterSearch" placeholder="Search voters...">
                    </div>
                    <div id="votersTableContainer">
                        <table id="votersTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Verified</th>
                                    <th>Voted</th>
                                    <th>Last Login</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="votersTableBody">
                                @if(isset($voters) && $voters->count() > 0)
                                    @foreach($voters as $voter)
                                        <tr>
                                            <td>#{{ $voter->id }}</td>
                                            <td>{{ $voter->name }}</td>
                                            <td>{{ $voter->email }}</td>
                                            <td><span class="status-badge status-{{ strtolower($voter->blocked_at ? 'blocked' : ($voter->email_verified_at ? 'verified' : 'pending')) }}">{{ $voter->blocked_at ? 'Blocked' : ($voter->email_verified_at ? 'Verified' : 'Pending') }}</span></td>
                                            <td><span class="status-badge status-{{ $voter->email_verified_at ? 'verified' : 'pending' }}">{{ $voter->email_verified_at ? 'Yes' : 'No' }}</span></td>
                                            <td><span class="status-badge status-active">No</span></td>
                                            <td>{{ $voter->last_login_at ? $voter->last_login_at->format('Y-m-d H:i') : 'Never' }}</td>
                                            <td>
                                                @if(!$voter->email_verified_at)
                                                    <button class="action-btn btn-approve" onclick="approveVoter({{ $voter->id }})">Approve</button>
                                                @endif
                                                <button class="action-btn btn-view" onclick="viewVoter({{ $voter->id }})">View</button>
                                                <button class="action-btn btn-edit" onclick="editVoter({{ $voter->id }})">Edit</button>
                                                @if($voter->blocked_at)
                                                    <button class="action-btn btn-approve" onclick="unblockVoter({{ $voter->id }})">Unblock</button>
                                                @else
                                                    <button class="action-btn btn-delete" onclick="blockVoter({{ $voter->id }})">Block</button>
                                                @endif
                                                <button class="action-btn btn-delete" onclick="deleteVoter({{ $voter->id }})">Delete</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" style="text-align: center; padding: 2rem;">
                                            <i class="fas fa-users"></i> No voters found.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <div id="loadingIndicator" style="text-align: center; padding: 2rem; display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Loading voters...
                        </div>
                        <div id="noDataMessage" style="text-align: center; padding: 2rem; display: none;">
                            <i class="fas fa-users"></i> No voters found.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Candidates Management Section -->
            <div id="candidates" class="dashboard-section">
                <div class="table-container">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <h3 class="chart-title">Candidates Management</h3>
                        <div style="display: flex; gap: 1rem; align-items: center;">
                            <select class="form-input" id="electionFilter" style="width: 200px;">
                                <option value="">All Elections</option>
                                @if(isset($elections))
                                    @foreach($elections as $election)
                                        <option value="{{ $election->id }}">{{ $election->title }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <input type="text" class="search-input" id="candidateSearch" placeholder="Search candidates..." style="width: 250px;">
                            <button class="action-btn btn-primary" onclick="showAddCandidateModal()">+ Add Candidate</button>
                        </div>
                    </div>
                    <div id="candidatesTableContainer">
                        <table id="candidatesTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Photo</th>
                                    <th>Name</th>
                                    <th>Election</th>
                                    <th>Position</th>
                                    <th>Description</th>
                                    <th>Votes</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="candidatesTableBody">
                                @if(isset($candidates) && $candidates->count() > 0)
                                    @foreach($candidates as $candidate)
                                        <tr>
                                            <td>#{{ $candidate->id }}</td>
                                            <td><img src="{{ asset($candidate->photo_url) }}" alt="{{ $candidate->name }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;"></td>
                                            <td>{{ $candidate->name }}</td>
                                            <td>{{ $candidate->election ? $candidate->election->title : 'N/A' }}</td>
                                            <td>{{ $candidate->position ?: 'N/A' }}</td>
                                            <td>{{ $candidate->vote_count }}</td>
                                            <td><span class="status-badge status-active">Active</span></td>
                                            <td>
                                                <button class="action-btn btn-view" onclick="viewCandidate({{ $candidate->id }})">View</button>
                                                <button class="action-btn btn-edit" onclick="editCandidate({{ $candidate->id }})">Edit</button>
                                                <button class="action-btn btn-delete" onclick="deleteCandidate({{ $candidate->id }})">Delete</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" style="text-align: center; padding: 2rem;">
                                            <i class="fas fa-user-tie"></i> No candidates found.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <div id="candidatesLoadingIndicator" style="text-align: center; padding: 2rem; display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Loading candidates...
                        </div>
                        <div id="candidatesNoDataMessage" style="text-align: center; padding: 2rem; display: none;">
                            <i class="fas fa-user-tie"></i> No candidates found.
                        </div>
                    </div>
                </div>
            </div>

            <!-- AI Fraud Detection Section -->
            <div id="ai-alerts" class="dashboard-section">
                <div class="table-container">
                    <h3 class="chart-title">AI Fraud Detection Alerts</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Voter ID</th>
                                <th>Flag Reason</th>
                                <th>AI Confidence</th>
                                <th>IP Address</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>14:30:22</td>
                                <td>#045</td>
                                <td>Multiple vote attempts</td>
                                <td>92%</td>
                                <td>192.168.1.100</td>
                                <td><span class="status-badge status-flagged">Flagged</span></td>
                                <td>
                                    <button class="action-btn btn-view">Review</button>
                                    <button class="action-btn btn-dismiss">Dismiss</button>
                                </td>
                            </tr>
                            <tr>
                                <td>14:15:45</td>
                                <td>#128</td>
                                <td>Unusual voting speed</td>
                                <td>78%</td>
                                <td>192.168.1.205</td>
                                <td><span class="status-badge status-flagged">Flagged</span></td>
                                <td>
                                    <button class="action-btn btn-view">Review</button>
                                    <button class="action-btn btn-approve">Confirm</button>
                                </td>
                            </tr>
                            <tr>
                                <td>13:58:12</td>
                                <td>#067</td>
                                <td>Suspicious IP pattern</td>
                                <td>85%</td>
                                <td>192.168.1.150</td>
                                <td><span class="status-badge status-pending">Under Review</span></td>
                                <td>
                                    <button class="action-btn btn-approve">Approve</button>
                                    <button class="action-btn btn-delete">Invalidate</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Election Management Section -->
            <div id="elections" class="dashboard-section">
                <!-- Election Overview Stats -->
                <div class="stats-row">
                    <div class="stat-item">
                        <div class="stat-value" id="totalElections">5</div>
                        <div class="stat-label">Total Elections</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="activeElectionsCount">2</div>
                        <div class="stat-label">Active Elections</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="totalVotesElection">3,247</div>
                        <div class="stat-label">Total Votes Cast</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="avgParticipation">68.5%</div>
                        <div class="stat-label">Avg Participation</div>
                    </div>
                </div>

                <!-- Election Management Tabs -->
                <div class="election-tabs">
                    <div class="election-tab active" onclick="showElectionSection('overview')">Elections</div>
                    <div class="election-tab" onclick="showElectionSection('create')">Create</div>
                    <div class="election-tab" onclick="showElectionSection('manage')">Manage</div>
                    <div class="election-tab" onclick="showElectionSection('security')">Security</div>
                </div>

                <!-- Election Overview Panel -->
                <div id="election-overview" class="election-section active-election-section">
                    <div class="table-container">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                            <h3 class="chart-title">All Elections</h3>
                            <div>
                                <input type="text" class="search-input" placeholder="Search..." id="electionSearch">
                                <button class="action-btn btn-primary" onclick="showElectionSection('create')">+ New Election</button>
                            </div>
                        </div>
                        <table id="electionsTable">
                            <thead>
                                <tr>
                                    <th>Election Name</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Total Votes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Presidential Election 2024</td>
                                    <td>Single Position</td>
                                    <td><span class="status-badge status-active">Active</span></td>
                                    <td>Dec 1, 2024</td>
                                    <td>Dec 15, 2024</td>
                                    <td>1,247</td>
                                    <td>
                                        <button class="action-btn btn-view" onclick="viewElection(1)">View</button>
                                        <button class="action-btn btn-edit" onclick="editElection(1)">Edit</button>
                                        <button class="action-btn btn-approve" onclick="closeElection(1)">Close</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Board of Directors Election</td>
                                    <td>Multi-Position</td>
                                    <td><span class="status-badge status-active">Active</span></td>
                                    <td>Dec 5, 2024</td>
                                    <td>Dec 20, 2024</td>
                                    <td>892</td>
                                    <td>
                                        <button class="action-btn btn-view" onclick="viewElection(2)">View</button>
                                        <button class="action-btn btn-edit" onclick="editElection(2)">Edit</button>
                                        <button class="action-btn btn-approve" onclick="closeElection(2)">Close</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Student Council Election</td>
                                    <td>Multi-Position</td>
                                    <td><span class="status-badge status-scheduled">Scheduled</span></td>
                                    <td>Jan 15, 2025</td>
                                    <td>Jan 30, 2025</td>
                                    <td>0</td>
                                    <td>
                                        <button class="action-btn btn-view" onclick="viewElection(3)">View</button>
                                        <button class="action-btn btn-edit" onclick="editElection(3)">Edit</button>
                                        <button class="action-btn btn-approve" onclick="activateElection(3)">Activate</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Constitutional Referendum</td>
                                    <td>Referendum</td>
                                    <td><span class="status-badge status-closed">Closed</span></td>
                                    <td>Nov 1, 2024</td>
                                    <td>Nov 10, 2024</td>
                                    <td>2,156</td>
                                    <td>
                                        <button class="action-btn btn-view" onclick="viewElection(4)">View</button>
                                        <button class="action-btn btn-approve" onclick="publishResults(4)">Publish</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Create/Edit Election Section -->
                <div id="election-create" class="election-section">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Create / Edit Election</div>
                        </div>
                        <div class="election-form" id="electionForm">
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Election Title</label>
                                    <input type="text" class="form-input" id="electionTitle" placeholder="Enter election title">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Election Type</label>
                                    <select class="form-input" id="electionType">
                                        <option value="single">Single Position</option>
                                        <option value="multi">Multi-Position</option>
                                        <option value="referendum">Referendum</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Start Date & Time</label>
                                    <input type="datetime-local" class="form-input" id="electionStart">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">End Date & Time</label>
                                    <input type="datetime-local" class="form-input" id="electionEnd">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Description</label>
                                <textarea class="form-input" id="electionDescription" rows="2" placeholder="Brief description"></textarea>
                            </div>
                            <div class="form-actions">
                                <button type="button" class="action-btn btn-secondary" onclick="showElectionSection('overview')">Cancel</button>
                                <button type="button" class="action-btn btn-primary" onclick="saveElection()">Save Election</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Management Section (Combined Candidates, Voters, Results) -->
                <div id="election-manage" class="election-section">
                    <div style="margin-bottom: 2rem;">
                        <label class="form-label">Select Election:</label>
                        <select class="form-input" id="manageElectionSelect" onchange="loadCandidatesForManagement()">
                            <option value="">Choose an election...</option>
                        </select>
                    </div>

                    <div class="manage-grid">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Candidates</div>
                            </div>
                            <div class="manage-content" id="candidatesList">
                                <p style="color: #666; text-align: center;">Select an election to manage candidates</p>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Voters</div>
                            </div>
                            <div class="manage-content">
                                <div class="manage-item">
                                    <span>1,250 Registered Voters</span>
                                    <span class="status-badge status-active">Verified</span>
                                </div>
                                <div class="manage-item">
                                    <span>890 Votes Cast</span>
                                    <span class="status-badge status-active">Active</span>
                                </div>
                                <button class="action-btn btn-secondary" onclick="manageVoters()" style="margin-top: 1rem;">Manage Voters</button>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Results</div>
                            </div>
                            <div class="manage-content">
                                <div class="manage-item">
                                    <span>Presidential Election 2024</span>
                                    <span class="status-badge status-closed">Completed</span>
                                </div>
                                <div class="manage-item">
                                    <span>Winner: John Smith</span>
                                    <span>1,247 votes (71.2%)</span>
                                </div>
                                <button class="action-btn btn-primary" onclick="viewResults()" style="margin-top: 1rem;">View Results</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Section (Combined Monitoring, Audit, Emergency) -->
                <div id="election-security" class="election-section">
                    <div class="security-grid">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">AI Security</div>
                            </div>
                            <div class="security-content">
                                <div class="security-item">
                                    <span>AI Detection</span>
                                    <label class="toggle-label">
                                        <input type="checkbox" id="aiDetectionEnabled" checked>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>
                                <div class="security-item">
                                    <span>Active Alerts</span>
                                    <span class="alert-count">3</span>
                                </div>
                                <button class="action-btn btn-secondary" onclick="viewAlerts()" style="margin-top: 1rem;">View Alerts</button>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Live Monitoring</div>
                            </div>
                            <div class="security-content">
                                <div class="security-item">
                                    <span>Active Voters</span>
                                    <span id="activeVoters">47</span>
                                </div>
                                <div class="security-item">
                                    <span>Votes/Minute</span>
                                    <span id="votesPerMinute">12</span>
                                </div>
                                <div class="countdown-timer" style="margin-top: 1rem;">
                                    <div class="countdown-display" id="electionCountdown">23:45:12</div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Emergency Controls</div>
                            </div>
                            <div class="security-content">
                                <button class="emergency-btn pause" onclick="pauseElection()">
                                    <i class="fas fa-pause"></i> Pause
                                </button>
                                <button class="emergency-btn close" onclick="forceCloseElection()">
                                    <i class="fas fa-times-circle"></i> Close
                                </button>
                                <button class="emergency-btn lock" onclick="lockResults()">
                                    <i class="fas fa-lock"></i> Lock Results
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="audit" class="dashboard-section">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Audit Logs</div>
                    </div>
                    <div class="card-value">Coming Soon</div>
                    <div class="card-change">This section is under development</div>
                </div>
            </div>

            <div id="monitoring" class="dashboard-section">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">System Monitoring</div>
                    </div>
                    <div class="card-value">Coming Soon</div>
                    <div class="card-change">This section is under development</div>
                </div>
            </div>

            <div id="reports" class="dashboard-section">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Reports & Analytics</div>
                    </div>
                    <div class="card-value">Coming Soon</div>
                    <div class="card-change">This section is under development</div>
                </div>
            </div>

            <!-- Completed Elections Section -->
            <div id="completed" class="dashboard-section">
                <div class="table-container">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <h3 class="chart-title">Completed Elections</h3>
                        <input type="text" class="search-input" id="completedSearch" placeholder="Search completed elections...">
                    </div>
                    <div id="completedTableContainer">
                        <table id="completedTable">
                            <thead>
                                <tr>
                                    <th>Election Name</th>
                                    <th>Type</th>
                                    <th>End Date</th>
                                    <th>Total Votes</th>
                                    <th>Winner</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="completedTableBody">
                                <!-- Data will be loaded via AJAX -->
                            </tbody>
                        </table>
                        <div id="completedLoadingIndicator" style="text-align: center; padding: 2rem; display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Loading completed elections...
                        </div>
                        <div id="completedNoDataMessage" style="text-align: center; padding: 2rem; display: none;">
                            <i class="fas fa-check-circle"></i> No completed elections found.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
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

                // Load data for specific sections
                if (sectionId === 'voters') {
                    loadVoters();
                } else if (sectionId === 'candidates') {
                    loadCandidates();
                    loadElectionsForFilter();
                } else if (sectionId === 'elections') {
                    loadElections();
                } else if (sectionId === 'completed') {
                    loadCompletedElections();
                }
            }

            // Add active class to clicked nav item
            event.currentTarget.classList.add('active');

            // Update page title
            const titles = {
                'overview': 'Dashboard Overview',
                'results': 'Live Election Results',
                'voters': 'Voter Management',
                'candidates': 'Candidates Management',
                'elections': 'Election Management',
                'ai-alerts': 'AI Fraud Detection',
                'audit': 'Audit Logs',
                'monitoring': 'System Monitoring',
                'reports': 'Reports & Analytics',
                'completed': 'Completed Elections',
                'settings': 'System Settings'
            };
            document.getElementById('pageTitle').textContent = titles[sectionId] || 'Dashboard';

            // Close profile dropdown when navigating
            document.getElementById('profileDropdown').classList.remove('active');
        }

        // Tab switching for election results
        function showElectionTab(electionType) {
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            event.target.classList.add('active');

            // Update chart title
            const titles = {
                'president': 'Presidential',
                'vice-president': 'Vice Presidential',
                'secretary': 'Secretary'
            };
            document.querySelector('#results .chart-title').textContent =
                `Live ${titles[electionType]} Election Results`;

            // Update chart data based on election type
            updateResultsChart(electionType);
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

        // Logout modal functions
        function openLogoutModal() {
            document.getElementById('logoutModal').classList.add('active');
        }

        function closeLogoutModal() {
            document.getElementById('logoutModal').classList.remove('active');
        }

        // Laravel-compatible logout function
        function performLogout() {
            // Submit the hidden Laravel logout form
            document.getElementById('logout-form').submit();
        }

        // Prevent modal click from closing modal
        document.getElementById('logoutModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeLogoutModal();
            }
        });

        // Initialize charts
        let votesChart, statusChart, resultsChart;

        document.addEventListener('DOMContentLoaded', function() {
            // Votes per hour chart
            const votesCtx = document.getElementById('votesChart').getContext('2d');
            votesChart = new Chart(votesCtx, {
                type: 'line',
                data: {
                    labels: ['8AM', '9AM', '10AM', '11AM', '12PM', '1PM', '2PM'],
                    datasets: [{
                        label: 'Votes',
                        data: [120, 190, 300, 500, 200, 300, 450],
                        borderColor: '#3498db',
                        backgroundColor: 'rgba(52, 152, 219, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        }
                    }
                }
            });

            // Status distribution chart
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            statusChart = new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Verified', 'Pending', 'Blocked'],
                    datasets: [{
                        data: [850, 250, 150],
                        backgroundColor: ['#27ae60', '#f39c12', '#e74c3c'],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        }
                    },
                    cutout: '65%'
                }
            });

            // Results chart
            const resultsCtx = document.getElementById('resultsChart').getContext('2d');
            resultsChart = new Chart(resultsCtx, {
                type: 'bar',
                data: {
                    labels: ['John Doe', 'Jane Smith', 'Bob Johnson'],
                    datasets: [{
                        label: 'Votes',
                        data: [450, 380, 170],
                        backgroundColor: ['#3498db', '#9b59b6', '#2ecc71'],
                        borderWidth: 1,
                        borderColor: '#fff',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Initialize search functionality
            const searchInput = document.querySelector('.search-input');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase();
                    const rows = document.querySelectorAll('#voters tbody tr');

                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(searchTerm) ? '' : 'none';
                    });
                });
            }

            // Initialize action buttons
            document.querySelectorAll('.action-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const action = this.textContent.trim();
                    const row = this.closest('tr');
                    const voterName = row.querySelector('td:nth-child(2)').textContent;

                    // Show notification
                    showNotification(`Action "${action}" performed on ${voterName}`, 'info');
                });
            });
        });

        // Update results chart based on election type
        function updateResultsChart(type) {
            const data = {
                'president': [450, 380, 170],
                'vice-president': [320, 410, 120],
                'secretary': [280, 290, 180]
            };

            const labels = {
                'president': ['John Doe', 'Jane Smith', 'Bob Johnson'],
                'vice-president': ['Alice Brown', 'Charlie Green', 'David White'],
                'secretary': ['Emma Wilson', 'Frank Taylor', 'Grace Lee']
            };

            resultsChart.data.labels = labels[type] || labels.president;
            resultsChart.data.datasets[0].data = data[type] || data.president;
            resultsChart.update();
        }

        // Show notification
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.innerHTML = `
                <div>${message}</div>
                <button onclick="this.parentElement.remove()">&times;</button>
            `;

            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'info' ? '#3498db' : type === 'success' ? '#27ae60' : '#e74c3c'};
                color: white;
                padding: 1rem 1.5rem;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                display: flex;
                align-items: center;
                justify-content: space-between;
                z-index: 3000;
                min-width: 300px;
                animation: slideIn 0.3s ease;
            `;

            notification.querySelector('button').style.cssText = `
                background: none;
                border: none;
                color: white;
                font-size: 1.5rem;
                cursor: pointer;
                margin-left: 1rem;
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }

        // Simulate real-time updates
        setInterval(() => {
            // Update voter count (simulate new registrations)
            const voterCount = document.getElementById('totalVoters');
            if (voterCount) {
                const currentVoters = parseInt(voterCount.textContent.replace(',', ''));
                if (Math.random() > 0.7) {
                    voterCount.textContent = (currentVoters + 1).toLocaleString();
                }
            }

            // Update votes cast
            const votesCast = document.getElementById('votesCast');
            if (votesCast) {
                const currentVotes = parseInt(votesCast.textContent.replace(',', ''));
                if (Math.random() > 0.5) {
                    votesCast.textContent = (currentVotes + Math.floor(Math.random() * 3)).toLocaleString();
                }
            }

            // Update participation rate
            const participationRate = document.getElementById('participationRate');
            if (participationRate && voterCount && votesCast) {
                const voters = parseInt(voterCount.textContent.replace(',', ''));
                const votes = parseInt(votesCast.textContent.replace(',', ''));
                const rate = voters > 0 ? ((votes / voters) * 100).toFixed(1) : 0;
                participationRate.textContent = `${rate}%`;
            }

            // Update AI alert count occasionally
            const alertCount = document.getElementById('aiAlertCount');
            if (alertCount && Math.random() > 0.8) {
                const currentAlerts = parseInt(alertCount.textContent);
                alertCount.textContent = `${Math.max(0, currentAlerts + (Math.random() > 0.5 ? 1 : -1))} AI Alerts`;
            }
        }, 5000);

        // Election Management Functions
        function showElectionSection(sectionId) {
            // Hide all election sections
            document.querySelectorAll('.election-section').forEach(section => {
                section.classList.remove('active-election-section');
            });

            // Remove active class from all election tabs
            document.querySelectorAll('.election-tab').forEach(tab => {
                tab.classList.remove('active');
            });

            // Show selected section
            const selectedSection = document.getElementById('election-' + sectionId);
            if (selectedSection) {
                selectedSection.classList.add('active-election-section');
            }

            // Find and activate the corresponding tab
            const tab = document.querySelector(`.election-tab[onclick*="showElectionSection('${sectionId}')"]`);
            if (tab) {
                tab.classList.add('active');
            }
        }

        // Load elections from database
        async function loadElections() {
            console.log('loadElections function called');
            try {
                const response = await fetch('/admin/elections', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                console.log('Response status:', response.status);
                const data = await response.json();
                console.log('Response data:', data);

                if (data.success) {
                    console.log('Populating table with', data.data.length, 'elections');
                    populateElectionsTable(data.data);
                    loadElectionStats();
                    showNotification(`Loaded ${data.data.length} elections successfully`, 'success');
                } else {
                    console.error('API returned success=false:', data);
                    showNotification('Failed to load elections', 'error');
                }
            } catch (error) {
                console.error('Error loading elections:', error);
                showNotification('Failed to load elections: ' + error.message, 'error');
            }
        }

        // Load election statistics
        async function loadElectionStats() {
            console.log('loadElectionStats function called');
            try {
                const response = await fetch('/admin/elections-stats', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                console.log('Stats response status:', response.status);
                const data = await response.json();
                console.log('Stats response data:', data);

                if (data.success) {
                    console.log('Updating stats with:', data.data);
                    updateElectionStats(data.data);
                } else {
                    console.error('Stats API returned success=false:', data);
                }
            } catch (error) {
                console.error('Error loading election stats:', error);
            }
        }

        // Populate elections table
        function populateElectionsTable(elections) {
            console.log('Populating table with elections:', elections);
            const tableBody = document.querySelector('#electionsTable tbody');
            tableBody.innerHTML = '';

            elections.forEach(election => {
                console.log('Processing election:', election);
                const row = document.createElement('tr');

                // Format dates
                const startDate = new Date(election.start_date).toLocaleDateString();
                const endDate = new Date(election.end_date).toLocaleDateString();

                // Determine status badge class
                const statusClass = `status-${election.status}`;

                // Use actions from API response
                let actionButtons = election.actions || '';

                // Safe access to total_votes with fallback
                const totalVotes = (election.total_votes != null && !isNaN(election.total_votes)) ? Number(election.total_votes).toLocaleString() : '0';

                row.innerHTML = `
                    <td>${election.title}</td>
                    <td>${election.type.charAt(0).toUpperCase() + election.type.slice(1)} Position</td>
                    <td><span class="status-badge ${statusClass}">${election.status.charAt(0).toUpperCase() + election.status.slice(1)}</span></td>
                    <td>${startDate}</td>
                    <td>${endDate}</td>
                    <td>${totalVotes}</td>
                    <td>${actionButtons}</td>
                `;

                tableBody.appendChild(row);
            });
        }

        // Update election statistics
        function updateElectionStats(stats) {
            document.getElementById('totalElections').textContent = stats.total_elections;
            document.getElementById('activeElectionsCount').textContent = stats.active_elections;
            document.getElementById('totalVotesElection').textContent = stats.total_votes.toLocaleString();
            document.getElementById('avgParticipation').textContent = `${stats.avg_participation}%`;
        }

        // Election action functions
        function viewElection(id) {
            // Fetch election data and show in modal
            fetch(`/admin/elections/${id}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const election = data.data;

                    // Format dates
                    const startDate = new Date(election.start_date).toLocaleString();
                    const endDate = new Date(election.end_date).toLocaleString();
                    const createdDate = new Date(election.created_at).toLocaleString();
                    const updatedDate = new Date(election.updated_at).toLocaleString();

                    // Determine status color
                    const statusClass = `status-${election.status}`;

                    // Create and show view modal
                    const modal = document.createElement('div');
                    modal.className = 'modal-overlay';
                    modal.innerHTML = `
                        <div class="modal" style="max-width: 600px;">
                            <div class="modal-title">Election Details</div>
                            <div class="modal-content">
                                <div style="display: grid; gap: 1.5rem;">
                                    <div style="text-align: center; padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                                        <h2 style="margin: 0; color: #2c3e50; font-size: 1.5rem;">${election.title}</h2>
                                        <div style="margin-top: 0.5rem;">
                                            <span class="status-badge ${statusClass}" style="font-size: 0.9rem;">${election.status.charAt(0).toUpperCase() + election.status.slice(1)}</span>
                                        </div>
                                    </div>

                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                        <div>
                                            <strong>Type:</strong><br>
                                            ${election.type.charAt(0).toUpperCase() + election.type.slice(1)} Position
                                        </div>
                                        <div>
                                            <strong>Total Votes:</strong><br>
                                            ${election.total_votes || 0}
                                        </div>
                                        <div>
                                            <strong>Start Date:</strong><br>
                                            ${startDate}
                                        </div>
                                        <div>
                                            <strong>End Date:</strong><br>
                                            ${endDate}
                                        </div>
                                    </div>

                                    <div>
                                        <strong>Description:</strong><br>
                                        <div style="background: #f8f9fa; padding: 1rem; border-radius: 6px; margin-top: 0.5rem;">
                                            ${election.description || 'No description provided'}
                                        </div>
                                    </div>

                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; font-size: 0.9rem; color: #666;">
                                        <div>
                                            <strong>Created:</strong><br>
                                            ${createdDate}
                                        </div>
                                        <div>
                                            <strong>Last Updated:</strong><br>
                                            ${updatedDate}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-actions">
                                <button class="modal-btn cancel" onclick="this.closest('.modal-overlay').remove()">Close</button>
                                <button class="modal-btn logout" onclick="editElection(${election.id}); this.closest('.modal-overlay').remove()">Edit Election</button>
                            </div>
                        </div>
                    `;
                    document.body.appendChild(modal);
                    modal.classList.add('active');
                } else {
                    showNotification('Failed to load election details', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to load election details', 'error');
            });
        }

        function editElection(id) {
            // Fetch election data and populate form
            fetch(`/admin/elections/${id}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const election = data.data;
                        document.getElementById('electionTitle').value = election.title;
                        document.getElementById('electionType').value = election.type;
                        document.getElementById('electionStart').value = new Date(election.start_date).toISOString().slice(0, 16);
                        document.getElementById('electionEnd').value = new Date(election.end_date).toISOString().slice(0, 16);
                        document.getElementById('electionDescription').value = election.description || '';

                        // Store election ID for update
                        document.getElementById('electionForm').setAttribute('data-election-id', id);

                        showElectionSection('create');
                        showNotification(`Editing election: ${election.title}`, 'info');
                    } else {
                        showNotification('Failed to load election data', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Failed to load election data', 'error');
                });
        }

        function closeElection(id) {
            if (confirm('Are you sure you want to close this election?')) {
                fetch(`/admin/elections/${id}/close`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(data.message, 'success');
                        loadElections(); // Reload the elections list
                    } else {
                        showNotification(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Failed to close election', 'error');
                });
            }
        }

        function activateElection(id) {
            if (confirm('Are you sure you want to activate this election?')) {
                fetch(`/admin/elections/${id}/activate`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(data.message, 'success');
                        loadElections(); // Reload the elections list
                    } else {
                        showNotification(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Failed to activate election', 'error');
                });
            }
        }

        function publishResults(id) {
            if (confirm('Are you sure you want to publish the results?')) {
                // This would typically publish results - for now just show notification
                showNotification(`Results for election ${id} have been published`, 'success');
            }
        }

        function deleteElection(id) {
            if (confirm('Are you sure you want to delete this election? This action cannot be undone.')) {
                fetch(`/admin/elections/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(data.message, 'success');
                        loadElections(); // Reload the elections list
                    } else {
                        showNotification(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Failed to delete election', 'error');
                });
            }
        }

        function saveElection() {
            const title = document.getElementById('electionTitle').value;
            const type = document.getElementById('electionType').value;
            const startDate = document.getElementById('electionStart').value;
            const endDate = document.getElementById('electionEnd').value;
            const description = document.getElementById('electionDescription').value;
            const electionId = document.getElementById('electionForm').getAttribute('data-election-id');

            // Frontend validation
            if (!title.trim()) {
                showNotification('Please enter an election title', 'error');
                return;
            }

            if (!startDate) {
                showNotification('Please select a start date and time', 'error');
                return;
            }

            if (!endDate) {
                showNotification('Please select an end date and time', 'error');
                return;
            }

            // Check if end date is after start date
            const startDateTime = new Date(startDate);
            const endDateTime = new Date(endDate);

            if (endDateTime <= startDateTime) {
                showNotification('End date must be after start date', 'error');
                return;
            }

            // Use FormData instead of JSON for better Laravel compatibility
            const formData = new FormData();
            formData.append('title', title);
            formData.append('type', type);
            formData.append('start_date', startDate);
            formData.append('end_date', endDate);
            formData.append('description', description);

            // Add CSRF token
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            const url = electionId ? `/admin/elections/${electionId}` : '/admin/elections';
            const method = 'POST';

            // For updates, add _method to simulate PUT
            if (electionId) {
                formData.append('_method', 'PUT');
            }

            fetch(url, {
                method: method,
                headers: {
                    // Don't set Content-Type for FormData, let browser set it with boundary
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    // If not JSON, it's probably an error page
                    return response.text().then(text => {
                        throw new Error('Server returned HTML instead of JSON: ' + text.substring(0, 200));
                    });
                }
            })
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    // Clear form fields individually since it's not a real form element
                    document.getElementById('electionTitle').value = '';
                    document.getElementById('electionType').value = 'single';
                    document.getElementById('electionStart').value = '';
                    document.getElementById('electionEnd').value = '';
                    document.getElementById('electionDescription').value = '';
                    document.getElementById('electionForm').removeAttribute('data-election-id');
                    showElectionSection('overview');
                    // Reload elections with a small delay to ensure UI updates
                    setTimeout(() => {
                        loadElections();
                    }, 100);
                } else {
                    // Show validation errors if present
                    if (data.errors) {
                        const errorMessages = Object.values(data.errors).flat().join(', ');
                        showNotification('Validation failed: ' + errorMessages, 'error');
                    } else {
                        showNotification(data.message || 'Failed to save election', 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to save election: ' + error.message, 'error');
            });
        }

        // Load elections for management dropdown
        async function loadElectionsForManagement() {
            try {
                const response = await fetch('/admin/elections', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();
                const select = document.getElementById('manageElectionSelect');

                if (data.success) {
                    select.innerHTML = '<option value="">Choose an election...</option>';
                    data.data.forEach(election => {
                        select.innerHTML += `<option value="${election.id}">${election.title}</option>`;
                    });
                }
            } catch (error) {
                console.error('Error loading elections:', error);
            }
        }

        // Load candidates for selected election
        async function loadCandidatesForManagement() {
            const electionId = document.getElementById('manageElectionSelect').value;
            const candidatesList = document.getElementById('candidatesList');

            if (!electionId) {
                candidatesList.innerHTML = '<p style="color: #666; text-align: center;">Select an election to manage candidates</p>';
                return;
            }

            try {
                const response = await fetch(`/admin/candidates?election_id=${electionId}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    let html = '';
                    data.data.forEach(candidate => {
                        html += `
                            <div class="manage-item">
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <img src="${candidate.photo_url ? candidate.photo_url : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(candidate.name) + '&background=3498db&color=fff&size=40'}" alt="${candidate.name}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                                    <div>
                                        <strong>${candidate.name}</strong>
                                        <div style="color: #666; font-size: 0.9rem;">${candidate.description || 'No description'}</div>
                                    </div>
                                </div>
                                <div>
                                    <button class="action-btn btn-edit" onclick="editCandidate(${candidate.id})">Edit</button>
                                    <button class="action-btn btn-delete" onclick="deleteCandidate(${candidate.id})">Delete</button>
                                </div>
                            </div>
                        `;
                    });

                    html += `<button class="action-btn btn-primary" onclick="addCandidate(${electionId})" style="margin-top: 1rem;">Add Candidate</button>`;
                    candidatesList.innerHTML = html;
                } else {
                    candidatesList.innerHTML = '<p style="color: #e74c3c; text-align: center;">Error loading candidates</p>';
                }
            } catch (error) {
                console.error('Error loading candidates:', error);
                candidatesList.innerHTML = '<p style="color: #e74c3c; text-align: center;">Error loading candidates</p>';
            }
        }

        // Load candidates for candidates management section
        async function loadCandidates(searchTerm = '', electionId = '') {
            console.log('loadCandidates called with searchTerm:', searchTerm, 'electionId:', electionId);
            const tableBody = document.getElementById('candidatesTableBody');
            const loadingIndicator = document.getElementById('candidatesLoadingIndicator');
            const noDataMessage = document.getElementById('candidatesNoDataMessage');

            // Show loading indicator
            loadingIndicator.style.display = 'block';
            noDataMessage.style.display = 'none';
            tableBody.innerHTML = '';

            try {
                let url = '/admin/api/candidates?';
                if (searchTerm) url += `search=${encodeURIComponent(searchTerm)}&`;
                if (electionId) url += `election_id=${electionId}&`;
                url = url.slice(0, -1); // Remove trailing &

                console.log('Fetching URL:', url);
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    credentials: 'include'
                });

                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('Response data:', data);

                if (data.success && data.data.length > 0) {
                    console.log('Data length:', data.data.length);
                    data.data.forEach(candidate => {
                        const row = document.createElement('tr');
                        const photoUrl = candidate.photo_url ? candidate.photo_url : `https://ui-avatars.com/api/?name=${encodeURIComponent(candidate.name)}&background=3498db&color=fff&size=40`;

                        row.innerHTML = `
                            <td>#${candidate.id}</td>
                            <td><img src="${photoUrl}" alt="${candidate.name}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;"></td>
                            <td>${candidate.name}</td>
                            <td>${candidate.election ? candidate.election.title : 'N/A'}</td>
                            <td>${candidate.position || 'N/A'}</td>
                            <td>${candidate.description || 'No description'}</td>
                            <td>${candidate.votes || 0}</td>
                            <td><span class="status-badge status-active">Active</span></td>
                            <td>
                                <button class="action-btn btn-view" onclick="viewCandidate(${candidate.id})">View</button>
                                <button class="action-btn btn-edit" onclick="editCandidate(${candidate.id})">Edit</button>
                                <button class="action-btn btn-delete" onclick="deleteCandidate(${candidate.id})">Delete</button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                } else {
                    noDataMessage.style.display = 'block';
                }
            } catch (error) {
                console.error('Error loading candidates:', error);
                showNotification('Failed to load candidates. Please try again.', 'error');
                noDataMessage.style.display = 'block';
            } finally {
                loadingIndicator.style.display = 'none';
            }
        }

        // Load elections for filter dropdown
        async function loadElectionsForFilter() {
            try {
                const response = await fetch('/admin/elections', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();
                const select = document.getElementById('electionFilter');

                if (data.success) {
                    select.innerHTML = '<option value="">All Elections</option>';
                    data.data.forEach(election => {
                        select.innerHTML += `<option value="${election.id}">${election.title}</option>`;
                    });
                }
            } catch (error) {
                console.error('Error loading elections for filter:', error);
            }
        }

        // Search functionality for candidates with debouncing
        let candidateSearchTimeout;
        document.getElementById('candidateSearch').addEventListener('input', function(e) {
            clearTimeout(candidateSearchTimeout);
            const searchTerm = e.target.value.trim();
            const electionId = document.getElementById('electionFilter').value;

            candidateSearchTimeout = setTimeout(() => {
                loadCandidates(searchTerm, electionId);
            }, 300); // 300ms debounce
        });

        // Election filter change
        document.getElementById('electionFilter').addEventListener('change', function(e) {
            const electionId = e.target.value;
            const searchTerm = document.getElementById('candidateSearch').value.trim();
            loadCandidates(searchTerm, electionId);
        });

        // View candidate details
        async function viewCandidate(id) {
            try {
                const response = await fetch(`/admin/candidates/${id}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    const candidate = data.data;
                    const photoUrl = candidate.photo_url ? candidate.photo_url : `https://ui-avatars.com/api/?name=${encodeURIComponent(candidate.name)}&background=3498db&color=fff&size=100`;

                    // Create and show view modal
                    const modal = document.createElement('div');
                    modal.className = 'modal-overlay';
                    modal.innerHTML = `
                        <div class="modal" style="max-width: 500px;">
                            <div class="modal-title">Candidate Details</div>
                            <div class="modal-content">
                                <div style="text-align: center; margin-bottom: 2rem;">
                                    <img src="${photoUrl}" alt="${candidate.name}" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; margin-bottom: 1rem;">
                                    <h3 style="margin: 0; color: #2c3e50;">${candidate.name}</h3>
                                </div>
                                <div style="display: grid; gap: 1rem;">
                                    <div><strong>Election:</strong> ${candidate.election ? candidate.election.title : 'N/A'}</div>
                                    <div><strong>Description:</strong> ${candidate.description || 'No description'}</div>
                                    <div><strong>Votes:</strong> ${candidate.votes || 0}</div>
                                    <div><strong>Status:</strong> <span class="status-badge status-active">Active</span></div>
                                    <div><strong>Created:</strong> ${new Date(candidate.created_at).toLocaleDateString()}</div>
                                </div>
                            </div>
                            <div class="modal-actions">
                                <button class="modal-btn cancel" onclick="this.closest('.modal-overlay').remove()">Close</button>
                                <button class="modal-btn logout" onclick="editCandidate(${candidate.id}); this.closest('.modal-overlay').remove()">Edit</button>
                            </div>
                        </div>
                    `;
                    document.body.appendChild(modal);
                    modal.classList.add('active');
                } else {
                    showNotification('Failed to load candidate details', 'error');
                }
            } catch (error) {
                console.error('Error loading candidate:', error);
                showNotification('Failed to load candidate details', 'error');
            }
        }

        // Show add candidate modal
        function showAddCandidateModal() {
            // Create and show add modal
            const modal = document.createElement('div');
            modal.className = 'modal-overlay';
            modal.innerHTML = `
                <div class="modal" style="max-width: 500px;">
                    <div class="modal-title">Add New Candidate</div>
                    <div class="modal-content">
                        <form id="addCandidateForm" enctype="multipart/form-data">
                            <div style="margin-bottom: 1rem;">
                                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Election *</label>
                                <select name="election_id" id="addElectionId" class="form-input" required style="width: 100%;">
                                    <option value="">Select Election</option>
                                </select>
                            </div>
                            <div style="margin-bottom: 1rem;">
                                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Name *</label>
                                <input type="text" name="name" id="addCandidateName" class="form-input" required style="width: 100%;">
                            </div>
                            <div style="margin-bottom: 1rem;">
                                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Description</label>
                                <textarea name="description" id="addCandidateDescription" class="form-input" rows="3" style="width: 100%;"></textarea>
                            </div>
                            <div style="margin-bottom: 1rem;">
                                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Photo</label>
                                <input type="file" name="photo" id="addCandidatePhoto" accept="image/*" style="width: 100%;">
                            </div>
                        </form>
                    </div>
                    <div class="modal-actions">
                        <button class="modal-btn cancel" onclick="this.closest('.modal-overlay').remove()">Cancel</button>
                        <button class="modal-btn logout" onclick="submitAddCandidateForm()">Add Candidate</button>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
            modal.classList.add('active');

            // Load elections for dropdown
            loadElectionsForAddModal();
        }

        // Load elections for add modal
        async function loadElectionsForAddModal() {
            try {
                const response = await fetch('/admin/elections', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();
                const select = document.getElementById('addElectionId');

                if (data.success) {
                    select.innerHTML = '<option value="">Select Election</option>';
                    data.data.forEach(election => {
                        select.innerHTML += `<option value="${election.id}">${election.title}</option>`;
                    });
                }
            } catch (error) {
                console.error('Error loading elections:', error);
            }
        }

        // Submit add candidate form
        async function submitAddCandidateForm() {
            const form = document.getElementById('addCandidateForm');
            const formData = new FormData(form);

            // Check required fields using form elements directly
            const electionSelect = document.getElementById('addElectionId');
            const nameInput = document.getElementById('addCandidateName');
            const electionId = electionSelect.value;
            const name = nameInput.value.trim();

            if (!electionId || !name) {
                showNotification('Please fill in all required fields (Election and Name)', 'error');
                return;
            }

            // Show loading state
            const submitBtn = document.querySelector('.modal-btn.logout');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Adding...';
            submitBtn.disabled = true;

            // Add CSRF token to form data for multipart requests
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            try {
                const response = await fetch('/admin/candidates', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json'
                    },
                    credentials: 'include',
                    body: formData
                });

                let data;
                const responseText = await response.text();
                console.log('Raw response:', responseText);
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);

                try {
                    data = JSON.parse(responseText);
                } catch (e) {
                    console.error('Failed to parse response as JSON:', e);
                    // If it's not JSON, it might be an HTML error page
                    if (responseText.includes('<html>') || responseText.includes('<!DOCTYPE')) {
                        throw new Error('Server returned HTML instead of JSON. Check server logs for errors.');
                    } else {
                        throw new Error('Invalid response from server: ' + responseText.substring(0, 100));
                    }
                }

                if (response.ok && data.success) {
                    showNotification('Candidate added successfully', 'success');
                    // Update CSRF token if provided
                    if (data.csrf_token) {
                        document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.csrf_token);
                    }
                    document.querySelector('.modal-overlay').remove();
                    loadCandidates(); // Reload the candidates list
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        const errorMessages = Object.values(data.errors).flat().join(', ');
                        showNotification('Validation failed: ' + errorMessages, 'error');
                    } else {
                        showNotification(data.message || 'Failed to add candidate', 'error');
                    }
                }
            } catch (error) {
                console.error('Error adding candidate:', error);
                showNotification('Failed to add candidate: ' + error.message, 'error');
            } finally {
                // Reset button state
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }
        }

        // Add new candidate
        function addCandidate(electionId) {
            const name = prompt('Enter candidate name:');
            if (!name) return;

            const description = prompt('Enter candidate description:');
            if (description === null) return; // Allow empty description

            const formData = new FormData();
            formData.append('election_id', electionId);
            formData.append('name', name);
            formData.append('description', description);

            fetch('/admin/candidates', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Candidate added successfully', 'success');
                    loadCandidatesForManagement();
                } else {
                    showNotification('Failed to add candidate', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to add candidate', 'error');
            });
        }

        // Edit candidate
        async function editCandidate(candidateId) {
            try {
                // First, fetch current candidate data
                const response = await fetch(`/admin/candidates/${candidateId}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    const candidate = data.data;

                    // Create and show edit modal
                    const modal = document.createElement('div');
                    modal.className = 'modal-overlay';
                    modal.innerHTML = `
                        <div class="modal" style="max-width: 500px;">
                            <div class="modal-title">Edit Candidate</div>
                            <div class="modal-content">
                                <form id="editCandidateForm" enctype="multipart/form-data">
                                    <div style="margin-bottom: 1rem;">
                                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Election *</label>
                                        <select name="election_id" id="editElectionId" class="form-input" required style="width: 100%;">
                                            <option value="">Select Election</option>
                                        </select>
                                    </div>
                                    <div style="margin-bottom: 1rem;">
                                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Name *</label>
                                        <input type="text" name="name" id="editCandidateName" class="form-input" required style="width: 100%;" value="${candidate.name}">
                                    </div>
                                    <div style="margin-bottom: 1rem;">
                                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Description</label>
                                        <textarea name="description" id="editCandidateDescription" class="form-input" rows="3" style="width: 100%;">${candidate.description || ''}</textarea>
                                    </div>
                                    <div style="margin-bottom: 1rem;">
                                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Photo</label>
                                        <input type="file" name="photo" id="editCandidatePhoto" accept="image/*" style="width: 100%;">
                                        <small style="color: #666;">Leave empty to keep current photo</small>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-actions">
                                <button class="modal-btn cancel" onclick="this.closest('.modal-overlay').remove()">Cancel</button>
                                <button class="modal-btn logout" onclick="submitEditCandidateForm(${candidateId})">Update Candidate</button>
                            </div>
                        </div>
                    `;
                    document.body.appendChild(modal);
                    modal.classList.add('active');

                    // Load elections for dropdown and set current election
                    await loadElectionsForEditModal(candidate.election_id);
                } else {
                    showNotification('Failed to load candidate data', 'error');
                }
            } catch (error) {
                console.error('Error loading candidate:', error);
                showNotification('Failed to load candidate data', 'error');
            }
        }

        // Load elections for edit modal
        async function loadElectionsForEditModal(currentElectionId) {
            try {
                const response = await fetch('/admin/elections', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();
                const select = document.getElementById('editElectionId');

                if (data.success) {
                    select.innerHTML = '<option value="">Select Election</option>';
                    data.data.forEach(election => {
                        const selected = election.id == currentElectionId ? 'selected' : '';
                        select.innerHTML += `<option value="${election.id}" ${selected}>${election.title}</option>`;
                    });
                }
            } catch (error) {
                console.error('Error loading elections:', error);
            }
        }

        // Submit edit candidate form
        async function submitEditCandidateForm(candidateId) {
            const form = document.getElementById('editCandidateForm');
            const formData = new FormData(form);

            if (!formData.get('election_id') || !formData.get('name')) {
                showNotification('Please fill in all required fields', 'error');
                return;
            }

            formData.append('_method', 'PUT');

            try {
                const response = await fetch(`/admin/candidates/${candidateId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    showNotification('Candidate updated successfully', 'success');
                    document.querySelector('.modal-overlay').remove();
                    loadCandidates(); // Reload the candidates list
                } else {
                    showNotification(data.message || 'Failed to update candidate', 'error');
                }
            } catch (error) {
                console.error('Error updating candidate:', error);
                showNotification('Failed to update candidate', 'error');
            }
        }

        // Delete candidate
        function deleteCandidate(candidateId) {
            if (!confirm('Are you sure you want to delete this candidate?')) return;

            const formData = new FormData();
            formData.append('_method', 'DELETE');

            fetch(`/admin/candidates/${candidateId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Candidate deleted successfully', 'success');
                    // Update CSRF token if provided
                    if (data.csrf_token) {
                        document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.csrf_token);
                    }
                    loadCandidatesForManagement();
                } else {
                    showNotification('Failed to delete candidate', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to delete candidate', 'error');
            });
        }

        function manageVoters() {
            showNotification('Manage voters functionality would open voter management', 'info');
        }

        function viewResults() {
            showNotification('View results functionality would show detailed results', 'info');
        }

        function viewAlerts() {
            showNotification('View alerts functionality would show AI security alerts', 'info');
        }

        function pauseElection() {
            if (confirm('Are you sure you want to pause the election?')) {
                showNotification('Election has been paused', 'warning');
            }
        }

        function forceCloseElection() {
            if (confirm('Are you sure you want to force close the election? This action cannot be undone.')) {
                showNotification('Election has been force closed', 'error');
            }
        }

        function lockResults() {
            if (confirm('Are you sure you want to lock the results?')) {
                showNotification('Election results have been locked', 'success');
            }
        }

        // Voter Management Functions
        async function loadVoters(searchTerm = '') {
            const tableBody = document.getElementById('votersTableBody');
            const loadingIndicator = document.getElementById('loadingIndicator');
            const noDataMessage = document.getElementById('noDataMessage');

            // Show loading indicator
            loadingIndicator.style.display = 'block';
            noDataMessage.style.display = 'none';
            tableBody.innerHTML = '';

            try {
                // Use API route
                const url = searchTerm ? `/admin/api/voters?search=${encodeURIComponent(searchTerm)}` : '/admin/api/voters';
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.success && data.data.length > 0) {
                    data.data.forEach(voter => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>#${voter.id}</td>
                            <td>${voter.name}</td>
                            <td>${voter.email}</td>
                            <td><span class="status-badge status-${voter.status.toLowerCase()}">${voter.status}</span></td>
                            <td><span class="status-badge status-${voter.verified === 'Yes' ? 'verified' : 'pending'}">${voter.verified}</span></td>
                            <td><span class="status-badge status-${voter.voted === 'Yes' ? 'active' : 'blocked'}">${voter.voted}</span></td>
                            <td>${voter.last_login}</td>
                            <td>${voter.actions}</td>
                        `;
                        tableBody.appendChild(row);
                    });
                } else {
                    noDataMessage.style.display = 'block';
                }
            } catch (error) {
                console.error('Error loading voters:', error);
                showNotification('Failed to load voters. Please try again.', 'error');
                noDataMessage.style.display = 'block';
            } finally {
                loadingIndicator.style.display = 'none';
            }
        }

        // Search functionality with debouncing
        let searchTimeout;
        document.getElementById('voterSearch').addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            const searchTerm = e.target.value.trim();

            searchTimeout = setTimeout(() => {
                loadVoters(searchTerm);
            }, 300); // 300ms debounce
        });

        // Load completed elections
        async function loadCompletedElections(searchTerm = '') {
            const tableBody = document.getElementById('completedTableBody');
            const loadingIndicator = document.getElementById('completedLoadingIndicator');
            const noDataMessage = document.getElementById('completedNoDataMessage');

            // Show loading indicator
            loadingIndicator.style.display = 'block';
            noDataMessage.style.display = 'none';
            tableBody.innerHTML = '';

            try {
                let url = '/admin/elections?status=closed';
                if (searchTerm) url += `&search=${encodeURIComponent(searchTerm)}`;

                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.success && data.data.length > 0) {
                    data.data.forEach(election => {
                        const row = document.createElement('tr');

                        // Format dates
                        const endDate = new Date(election.end_date).toLocaleDateString();

                        // Get winner (simplified - in real app, you'd calculate this)
                        const winner = 'Winner TBD'; // Placeholder

                        row.innerHTML = `
                            <td>${election.title}</td>
                            <td>${election.type.charAt(0).toUpperCase() + election.type.slice(1)} Position</td>
                            <td>${endDate}</td>
                            <td>${election.total_votes || 0}</td>
                            <td>${winner}</td>
                            <td>
                                <button class="action-btn btn-view" onclick="viewCompletedElection(${election.id})">View Results</button>
                                <button class="action-btn btn-approve" onclick="publishResults(${election.id})">Publish</button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                } else {
                    noDataMessage.style.display = 'block';
                }
            } catch (error) {
                console.error('Error loading completed elections:', error);
                showNotification('Failed to load completed elections. Please try again.', 'error');
                noDataMessage.style.display = 'block';
            } finally {
                loadingIndicator.style.display = 'none';
            }
        }

        // Search functionality for completed elections
        let completedSearchTimeout;
        document.getElementById('completedSearch').addEventListener('input', function(e) {
            clearTimeout(completedSearchTimeout);
            const searchTerm = e.target.value.trim();

            completedSearchTimeout = setTimeout(() => {
                loadCompletedElections(searchTerm);
            }, 300); // 300ms debounce
        });

        // View completed election results
        function viewCompletedElection(id) {
            // For now, just show notification. Could be expanded to show detailed modal
            showNotification(`Viewing results for completed election ID: ${id}`, 'info');
        }

        // Action functions
        async function approveVoter(id) {
            if (!confirm('Are you sure you want to approve this voter?')) return;

            try {
                const response = await fetch(`/admin/api/voters/${id}/approve`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    showNotification(data.message, 'success');
                    loadVoters(); // Reload the list
                } else {
                    showNotification('Failed to approve voter', 'error');
                }
            } catch (error) {
                console.error('Error approving voter:', error);
                showNotification('Failed to approve voter', 'error');
            }
        }

        async function blockVoter(id) {
            if (!confirm('Are you sure you want to block this voter?')) return;

            try {
                const response = await fetch(`/admin/api/voters/${id}/block`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    showNotification(data.message, 'success');
                    loadVoters(); // Reload the list
                } else {
                    showNotification('Failed to block voter', 'error');
                }
            } catch (error) {
                console.error('Error blocking voter:', error);
                showNotification('Failed to block voter', 'error');
            }
        }

        async function unblockVoter(id) {
            if (!confirm('Are you sure you want to unblock this voter?')) return;

            try {
                const response = await fetch(`/admin/api/voters/${id}/unblock`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    showNotification(data.message, 'success');
                    loadVoters(); // Reload the list
                } else {
                    showNotification('Failed to unblock voter', 'error');
                }
            } catch (error) {
                console.error('Error unblocking voter:', error);
                showNotification('Failed to unblock voter', 'error');
            }
        }

        async function deleteVoter(id) {
            if (!confirm('Are you sure you want to delete this voter? This action cannot be undone.')) return;

            try {
                const response = await fetch(`/admin/api/voters/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    showNotification(data.message, 'success');
                    loadVoters(); // Reload the list
                } else {
                    showNotification('Failed to delete voter', 'error');
                }
            } catch (error) {
                console.error('Error deleting voter:', error);
                showNotification('Failed to delete voter', 'error');
            }
        }

        function viewVoter(id) {
            // Redirect to voter detail page
            window.location.href = `/admin/voter-management/${id}`;
        }

        function editVoter(id) {
            // Redirect to voter edit page
            window.location.href = `/admin/voter-management/${id}/edit`;
        }

        // Add CSS for notification animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            .notification {
                font-family: 'Instrument Sans', sans-serif;
            }

            .toggle-label {
                position: relative;
                display: inline-block;
                width: 50px;
                height: 24px;
                cursor: pointer;
            }

            .toggle-label input {
                opacity: 0;
                width: 0;
                height: 0;
            }

            .toggle-slider {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #ccc;
                border-radius: 24px;
                transition: 0.4s;
            }

            .toggle-slider:before {
                position: absolute;
                content: "";
                height: 18px;
                width: 18px;
                left: 3px;
                bottom: 3px;
                background-color: white;
                border-radius: 50%;
                transition: 0.4s;
            }

            input:checked + .toggle-slider {
                background-color: #27ae60;
            }

            input:checked + .toggle-slider:before {
                transform: translateX(26px);
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
</html>
