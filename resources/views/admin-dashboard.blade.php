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
        }

        .nav-item:hover, .nav-item.active {
            background: rgba(255,255,255,0.1);
            border-left: 4px solid #3498db;
        }

        .nav-icon {
            margin-right: 10px;
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
            transition: transform 0.3s;
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
        }

        .chart-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #2c3e50;
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
            50% { opacity: 0.5; }
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
        }

        .tab.active {
            color: #3498db;
            border-bottom: 2px solid #3498db;
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
        }

        .modal-btn.cancel {
            background: #f8f9fa;
            color: #666;
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

        /* Responsive */
        @media (max-width: 1200px) {
            .charts-section {
                grid-template-columns: 1fr;
            }
            
            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
            }
            
            .nav-menu {
                display: flex;
                overflow-x: auto;
            }
            
            .nav-item {
                white-space: nowrap;
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
                    📊 <span class="nav-icon"></span>Dashboard Overview
                </li>
                <li class="nav-item" onclick="showSection('results')">
                    📈 <span class="nav-icon"></span>Live Results
                </li>
                <li class="nav-item" onclick="showSection('voters')">
                    👥 <span class="nav-icon"></span>Voter Management
                </li>
                <li class="nav-item" onclick="showSection('elections')">
                    🗳️ <span class="nav-icon"></span>Election Management
                </li>
                <li class="nav-item" onclick="showSection('ai-alerts')">
                    🤖 <span class="nav-icon"></span>AI Fraud Detection
                </li>
                <li class="nav-item" onclick="showSection('audit')">
                    📋 <span class="nav-icon"></span>Audit Logs
                </li>
                <li class="nav-item" onclick="showSection('monitoring')">
                    📊 <span class="nav-icon"></span>System Monitoring
                </li>
                <li class="nav-item" onclick="showSection('reports')">
                    📄 <span class="nav-icon"></span>Reports & Analytics
                </li>
                <li class="nav-item" onclick="showSection('settings')">
                    ⚙️ <span class="nav-icon"></span>Settings
                </li>
            </ul>
            
            <!-- Logout Button in Sidebar -->
            <div class="logout-section">
                <button class="logout-btn" onclick="openLogoutModal()">
                    <span class="logout-icon">🚪</span>
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
                        <div>👤</div>
                        <div class="profile-name">{{ Auth::user()->name ?? 'Admin User' }}</div>
                        <div>▼</div>
                        
                        <!-- Profile Dropdown -->
                        <div class="profile-dropdown" id="profileDropdown">
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                👤 My Profile
                            </a>
                            <a href="#" class="dropdown-item">
                                ⚙️ Account Settings
                            </a>
                            <div class="dropdown-item logout" onclick="openLogoutModal()">
                                🚪 Log Out
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Overview Section -->
            <div id="overview" class="dashboard-section active-section">
                <!-- Alert Panel -->
                <div class="alert-panel">
                    <div class="alert-title">⚠️ AI Security Alert</div>
                    <div class="alert-content">Multiple voting attempts detected from IP: 192.168.1.100. Confidence: 85%</div>
                </div>

                <!-- Quick Stats -->
                <div class="stats-row">
                    <div class="stat-item">
                        <div class="stat-value" id="totalVoters">1,250</div>
                        <div class="stat-label">Registered Voters</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="votesCast">890</div>
                        <div class="stat-label">Votes Cast</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="participationRate">71.2%</div>
                        <div class="stat-label">Participation Rate</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="activeElections">3</div>
                        <div class="stat-label">Active Elections</div>
                    </div>
                </div>

                <!-- Main Cards -->
                <div class="dashboard-grid">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Voting Progress</div>
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
            <div id="results" class="dashboard-section" style="display: none;">
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
                                <td class="card-change">↑ +12 votes/min</td>
                            </tr>
                            <tr>
                                <td>Jane Smith (Party B)</td>
                                <td>380</td>
                                <td>38%</td>
                                <td class="card-change">↑ +8 votes/min</td>
                            </tr>
                            <tr>
                                <td>Bob Johnson (Independent)</td>
                                <td>170</td>
                                <td>17%</td>
                                <td class="card-change negative">↓ -2 votes/min</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Voter Management Section -->
            <div id="voters" class="dashboard-section" style="display: none;">
                <div class="table-container">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <h3 class="chart-title">Voter Management</h3>
                        <input type="text" placeholder="Search voters..." style="padding: 0.5rem; border: 1px solid #ddd; border-radius: 6px;">
                    </div>
                    <table>
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
                        <tbody>
                            <tr>
                                <td>#001</td>
                                <td>John Smith</td>
                                <td>john@example.com</td>
                                <td><span class="status-badge status-verified">Verified</span></td>
                                <td><span class="status-badge status-active">Yes</span></td>
                                <td><span class="status-badge status-active">Yes</span></td>
                                <td>2024-01-15 14:30</td>
                                <td>
                                    <button class="action-btn btn-view">View</button>
                                    <button class="action-btn btn-edit">Edit</button>
                                </td>
                            </tr>
                            <tr>
                                <td>#002</td>
                                <td>Jane Doe</td>
                                <td>jane@example.com</td>
                                <td><span class="status-badge status-pending">Pending</span></td>
                                <td><span class="status-badge status-pending">No</span></td>
                                <td><span class="status-badge status-blocked">No</span></td>
                                <td>2024-01-14 09:15</td>
                                <td>
                                    <button class="action-btn btn-approve">Approve</button>
                                    <button class="action-btn btn-delete">Reject</button>
                                </td>
                            </tr>
                            <tr>
                                <td>#003</td>
                                <td>Bob Wilson</td>
                                <td>bob@example.com</td>
                                <td><span class="status-badge status-blocked">Blocked</span></td>
                                <td><span class="status-badge status-verified">Yes</span></td>
                                <td><span class="status-badge status-blocked">No</span></td>
                                <td>2024-01-13 16:45</td>
                                <td>
                                    <button class="action-btn btn-edit">Unblock</button>
                                    <button class="action-btn btn-view">Details</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- AI Fraud Detection Section -->
            <div id="ai-alerts" class="dashboard-section" style="display: none;">
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

            <!-- Additional sections would continue here for Election Management, Audit Logs, etc. -->
        </div>
    </div>

    <script>
        // Navigation function
        function showSection(sectionId) {
            // Hide all sections
            document.querySelectorAll('.dashboard-section').forEach(section => {
                section.style.display = 'none';
            });
            
            // Remove active class from all nav items
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Show selected section
            document.getElementById(sectionId).style.display = 'block';
            
            // Add active class to clicked nav item
            event.target.closest('.nav-item').classList.add('active');
            
            // Update page title
            const titles = {
                'overview': 'Dashboard Overview',
                'results': 'Live Election Results',
                'voters': 'Voter Management',
                'elections': 'Election Management',
                'ai-alerts': 'AI Fraud Detection',
                'audit': 'Audit Logs',
                'monitoring': 'System Monitoring',
                'reports': 'Reports & Analytics',
                'settings': 'System Settings'
            };
            document.getElementById('pageTitle').textContent = titles[sectionId];
        }

        // Tab switching for election results
        function showElectionTab(electionType) {
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            event.target.classList.add('active');
            
            // Update chart title
            document.querySelector('#results .chart-title').textContent = 
                `Live ${electionType.charAt(0).toUpperCase() + electionType.slice(1).replace('-', ' ')} Election Results`;
            
            // Here you would update the chart data based on election type
            updateResultsChart(electionType);
        }

        // Toggle profile dropdown
        function toggleProfileDropdown() {
            document.getElementById('profileDropdown').classList.toggle('active');
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

        // Initialize charts
        document.addEventListener('DOMContentLoaded', function() {
            // Votes per hour chart
            const votesCtx = document.getElementById('votesChart').getContext('2d');
            new Chart(votesCtx, {
                type: 'line',
                data: {
                    labels: ['8AM', '9AM', '10AM', '11AM', '12PM', '1PM', '2PM'],
                    datasets: [{
                        label: 'Votes',
                        data: [120, 190, 300, 500, 200, 300, 450],
                        borderColor: '#3498db',
                        backgroundColor: 'rgba(52, 152, 219, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });

            // Status distribution chart
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Verified', 'Pending', 'Blocked'],
                    datasets: [{
                        data: [850, 250, 150],
                        backgroundColor: ['#27ae60', '#f39c12', '#e74c3c']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Results chart
            const resultsCtx = document.getElementById('resultsChart').getContext('2d');
            window.resultsChart = new Chart(resultsCtx, {
                type: 'bar',
                data: {
                    labels: ['John Doe', 'Jane Smith', 'Bob Johnson'],
                    datasets: [{
                        label: 'Votes',
                        data: [450, 380, 170],
                        backgroundColor: ['#3498db', '#9b59b6', '#2ecc71']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });

        // Update results chart based on election type
        function updateResultsChart(type) {
            const data = {
                'president': [450, 380, 170],
                'vice-president': [320, 410, 120],
                'secretary': [280, 290, 180]
            };
            
            window.resultsChart.data.datasets[0].data = data[type] || data.president;
            window.resultsChart.update();
        }

        // Simulate real-time updates
        setInterval(() => {
            // Update voter count (simulate new registrations)
            const voterCount = document.getElementById('totalVoters');
            const currentVoters = parseInt(voterCount.textContent.replace(',', ''));
            if (Math.random() > 0.7) {
                voterCount.textContent = (currentVoters + 1).toLocaleString();
            }

            // Update votes cast
            const votesCast = document.getElementById('votesCast');
            const currentVotes = parseInt(votesCast.textContent.replace(',', ''));
            if (Math.random() > 0.5) {
                votesCast.textContent = (currentVotes + Math.floor(Math.random() * 3)).toLocaleString();
            }
        }, 5000);
    </script>
</body>
</html><!DOCTYPE html>
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
        }

        .nav-item:hover, .nav-item.active {
            background: rgba(255,255,255,0.1);
            border-left: 4px solid #3498db;
        }

        .nav-icon {
            margin-right: 10px;
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
            transition: transform 0.3s;
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
        }

        .chart-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #2c3e50;
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
            50% { opacity: 0.5; }
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
        }

        .tab.active {
            color: #3498db;
            border-bottom: 2px solid #3498db;
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
        }

        .modal-btn.cancel {
            background: #f8f9fa;
            color: #666;
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

        /* Responsive */
        @media (max-width: 1200px) {
            .charts-section {
                grid-template-columns: 1fr;
            }
            
            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
            }
            
            .nav-menu {
                display: flex;
                overflow-x: auto;
            }
            
            .nav-item {
                white-space: nowrap;
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
                    📊 <span class="nav-icon"></span>Dashboard Overview
                </li>
                <li class="nav-item" onclick="showSection('results')">
                    📈 <span class="nav-icon"></span>Live Results
                </li>
                <li class="nav-item" onclick="showSection('voters')">
                    👥 <span class="nav-icon"></span>Voter Management
                </li>
                <li class="nav-item" onclick="showSection('elections')">
                    🗳️ <span class="nav-icon"></span>Election Management
                </li>
                <li class="nav-item" onclick="showSection('ai-alerts')">
                    🤖 <span class="nav-icon"></span>AI Fraud Detection
                </li>
                <li class="nav-item" onclick="showSection('audit')">
                    📋 <span class="nav-icon"></span>Audit Logs
                </li>
                <li class="nav-item" onclick="showSection('monitoring')">
                    📊 <span class="nav-icon"></span>System Monitoring
                </li>
                <li class="nav-item" onclick="showSection('reports')">
                    📄 <span class="nav-icon"></span>Reports & Analytics
                </li>
                <li class="nav-item" onclick="showSection('settings')">
                    ⚙️ <span class="nav-icon"></span>Settings
                </li>
            </ul>
            
            <!-- Logout Button in Sidebar -->
            <div class="logout-section">
                <button class="logout-btn" onclick="openLogoutModal()">
                    <span class="logout-icon">🚪</span>
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
                        <div>👤</div>
                        <div class="profile-name">{{ Auth::user()->name ?? 'Admin User' }}</div>
                        <div>▼</div>
                        
                        <!-- Profile Dropdown -->
                        <div class="profile-dropdown" id="profileDropdown">
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                👤 My Profile
                            </a>
                            <a href="#" class="dropdown-item">
                                ⚙️ Account Settings
                            </a>
                            <div class="dropdown-item logout" onclick="openLogoutModal()">
                                🚪 Log Out
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Overview Section -->
            <div id="overview" class="dashboard-section active-section">
                <!-- Alert Panel -->
                <div class="alert-panel">
                    <div class="alert-title">⚠️ AI Security Alert</div>
                    <div class="alert-content">Multiple voting attempts detected from IP: 192.168.1.100. Confidence: 85%</div>
                </div>

                <!-- Quick Stats -->
                <div class="stats-row">
                    <div class="stat-item">
                        <div class="stat-value" id="totalVoters">1,250</div>
                        <div class="stat-label">Registered Voters</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="votesCast">890</div>
                        <div class="stat-label">Votes Cast</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="participationRate">71.2%</div>
                        <div class="stat-label">Participation Rate</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="activeElections">3</div>
                        <div class="stat-label">Active Elections</div>
                    </div>
                </div>

                <!-- Main Cards -->
                <div class="dashboard-grid">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Voting Progress</div>
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
            <div id="results" class="dashboard-section" style="display: none;">
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
                                <td class="card-change">↑ +12 votes/min</td>
                            </tr>
                            <tr>
                                <td>Jane Smith (Party B)</td>
                                <td>380</td>
                                <td>38%</td>
                                <td class="card-change">↑ +8 votes/min</td>
                            </tr>
                            <tr>
                                <td>Bob Johnson (Independent)</td>
                                <td>170</td>
                                <td>17%</td>
                                <td class="card-change negative">↓ -2 votes/min</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Voter Management Section -->
            <div id="voters" class="dashboard-section" style="display: none;">
                <div class="table-container">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <h3 class="chart-title">Voter Management</h3>
                        <input type="text" placeholder="Search voters..." style="padding: 0.5rem; border: 1px solid #ddd; border-radius: 6px;">
                    </div>
                    <table>
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
                        <tbody>
                            <tr>
                                <td>#001</td>
                                <td>John Smith</td>
                                <td>john@example.com</td>
                                <td><span class="status-badge status-verified">Verified</span></td>
                                <td><span class="status-badge status-active">Yes</span></td>
                                <td><span class="status-badge status-active">Yes</span></td>
                                <td>2024-01-15 14:30</td>
                                <td>
                                    <button class="action-btn btn-view">View</button>
                                    <button class="action-btn btn-edit">Edit</button>
                                </td>
                            </tr>
                            <tr>
                                <td>#002</td>
                                <td>Jane Doe</td>
                                <td>jane@example.com</td>
                                <td><span class="status-badge status-pending">Pending</span></td>
                                <td><span class="status-badge status-pending">No</span></td>
                                <td><span class="status-badge status-blocked">No</span></td>
                                <td>2024-01-14 09:15</td>
                                <td>
                                    <button class="action-btn btn-approve">Approve</button>
                                    <button class="action-btn btn-delete">Reject</button>
                                </td>
                            </tr>
                            <tr>
                                <td>#003</td>
                                <td>Bob Wilson</td>
                                <td>bob@example.com</td>
                                <td><span class="status-badge status-blocked">Blocked</span></td>
                                <td><span class="status-badge status-verified">Yes</span></td>
                                <td><span class="status-badge status-blocked">No</span></td>
                                <td>2024-01-13 16:45</td>
                                <td>
                                    <button class="action-btn btn-edit">Unblock</button>
                                    <button class="action-btn btn-view">Details</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- AI Fraud Detection Section -->
            <div id="ai-alerts" class="dashboard-section" style="display: none;">
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

            <!-- Additional sections would continue here for Election Management, Audit Logs, etc. -->
        </div>
    </div>

    <script>
        // Navigation function
        function showSection(sectionId) {
            // Hide all sections
            document.querySelectorAll('.dashboard-section').forEach(section => {
                section.style.display = 'none';
            });
            
            // Remove active class from all nav items
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Show selected section
            document.getElementById(sectionId).style.display = 'block';
            
            // Add active class to clicked nav item
            event.target.closest('.nav-item').classList.add('active');
            
            // Update page title
            const titles = {
                'overview': 'Dashboard Overview',
                'results': 'Live Election Results',
                'voters': 'Voter Management',
                'elections': 'Election Management',
                'ai-alerts': 'AI Fraud Detection',
                'audit': 'Audit Logs',
                'monitoring': 'System Monitoring',
                'reports': 'Reports & Analytics',
                'settings': 'System Settings'
            };
            document.getElementById('pageTitle').textContent = titles[sectionId];
        }

        // Tab switching for election results
        function showElectionTab(electionType) {
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            event.target.classList.add('active');
            
            // Update chart title
            document.querySelector('#results .chart-title').textContent = 
                `Live ${electionType.charAt(0).toUpperCase() + electionType.slice(1).replace('-', ' ')} Election Results`;
            
            // Here you would update the chart data based on election type
            updateResultsChart(electionType);
        }

        // Toggle profile dropdown
        function toggleProfileDropdown() {
            document.getElementById('profileDropdown').classList.toggle('active');
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

        // Initialize charts
        document.addEventListener('DOMContentLoaded', function() {
            // Votes per hour chart
            const votesCtx = document.getElementById('votesChart').getContext('2d');
            new Chart(votesCtx, {
                type: 'line',
                data: {
                    labels: ['8AM', '9AM', '10AM', '11AM', '12PM', '1PM', '2PM'],
                    datasets: [{
                        label: 'Votes',
                        data: [120, 190, 300, 500, 200, 300, 450],
                        borderColor: '#3498db',
                        backgroundColor: 'rgba(52, 152, 219, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });

            // Status distribution chart
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Verified', 'Pending', 'Blocked'],
                    datasets: [{
                        data: [850, 250, 150],
                        backgroundColor: ['#27ae60', '#f39c12', '#e74c3c']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Results chart
            const resultsCtx = document.getElementById('resultsChart').getContext('2d');
            window.resultsChart = new Chart(resultsCtx, {
                type: 'bar',
                data: {
                    labels: ['John Doe', 'Jane Smith', 'Bob Johnson'],
                    datasets: [{
                        label: 'Votes',
                        data: [450, 380, 170],
                        backgroundColor: ['#3498db', '#9b59b6', '#2ecc71']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });

        // Update results chart based on election type
        function updateResultsChart(type) {
            const data = {
                'president': [450, 380, 170],
                'vice-president': [320, 410, 120],
                'secretary': [280, 290, 180]
            };
            
            window.resultsChart.data.datasets[0].data = data[type] || data.president;
            window.resultsChart.update();
        }

        // Simulate real-time updates
        setInterval(() => {
            // Update voter count (simulate new registrations)
            const voterCount = document.getElementById('totalVoters');
            const currentVoters = parseInt(voterCount.textContent.replace(',', ''));
            if (Math.random() > 0.7) {
                voterCount.textContent = (currentVoters + 1).toLocaleString();
            }

            // Update votes cast
            const votesCast = document.getElementById('votesCast');
            const currentVotes = parseInt(votesCast.textContent.replace(',', ''));
            if (Math.random() > 0.5) {
                votesCast.textContent = (currentVotes + Math.floor(Math.random() * 3)).toLocaleString();
            }
        }, 5000);
    </script>
</body>
</html><!DOCTYPE html>
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
        }

        .nav-item:hover, .nav-item.active {
            background: rgba(255,255,255,0.1);
            border-left: 4px solid #3498db;
        }

        .nav-icon {
            margin-right: 10px;
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
            transition: transform 0.3s;
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
        }

        .chart-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #2c3e50;
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
            50% { opacity: 0.5; }
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
        }

        .tab.active {
            color: #3498db;
            border-bottom: 2px solid #3498db;
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
        }

        .modal-btn.cancel {
            background: #f8f9fa;
            color: #666;
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

        /* Responsive */
        @media (max-width: 1200px) {
            .charts-section {
                grid-template-columns: 1fr;
            }
            
            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
            }
            
            .nav-menu {
                display: flex;
                overflow-x: auto;
            }
            
            .nav-item {
                white-space: nowrap;
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
                    📊 <span class="nav-icon"></span>Dashboard Overview
                </li>
                <li class="nav-item" onclick="showSection('results')">
                    📈 <span class="nav-icon"></span>Live Results
                </li>
                <li class="nav-item" onclick="showSection('voters')">
                    👥 <span class="nav-icon"></span>Voter Management
                </li>
                <li class="nav-item" onclick="showSection('elections')">
                    🗳️ <span class="nav-icon"></span>Election Management
                </li>
                <li class="nav-item" onclick="showSection('ai-alerts')">
                    🤖 <span class="nav-icon"></span>AI Fraud Detection
                </li>
                <li class="nav-item" onclick="showSection('audit')">
                    📋 <span class="nav-icon"></span>Audit Logs
                </li>
                <li class="nav-item" onclick="showSection('monitoring')">
                    📊 <span class="nav-icon"></span>System Monitoring
                </li>
                <li class="nav-item" onclick="showSection('reports')">
                    📄 <span class="nav-icon"></span>Reports & Analytics
                </li>
                <li class="nav-item" onclick="showSection('settings')">
                    ⚙️ <span class="nav-icon"></span>Settings
                </li>
            </ul>
            
            <!-- Logout Button in Sidebar -->
            <div class="logout-section">
                <button class="logout-btn" onclick="openLogoutModal()">
                    <span class="logout-icon">🚪</span>
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
                        <div>👤</div>
                        <div class="profile-name">{{ Auth::user()->name ?? 'Admin User' }}</div>
                        <div>▼</div>
                        
                        <!-- Profile Dropdown -->
                        <div class="profile-dropdown" id="profileDropdown">
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                👤 My Profile
                            </a>
                            <a href="#" class="dropdown-item">
                                ⚙️ Account Settings
                            </a>
                            <div class="dropdown-item logout" onclick="openLogoutModal()">
                                🚪 Log Out
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Overview Section -->
            <div id="overview" class="dashboard-section active-section">
                <!-- Alert Panel -->
                <div class="alert-panel">
                    <div class="alert-title">⚠️ AI Security Alert</div>
                    <div class="alert-content">Multiple voting attempts detected from IP: 192.168.1.100. Confidence: 85%</div>
                </div>

                <!-- Quick Stats -->
                <div class="stats-row">
                    <div class="stat-item">
                        <div class="stat-value" id="totalVoters">1,250</div>
                        <div class="stat-label">Registered Voters</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="votesCast">890</div>
                        <div class="stat-label">Votes Cast</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="participationRate">71.2%</div>
                        <div class="stat-label">Participation Rate</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="activeElections">3</div>
                        <div class="stat-label">Active Elections</div>
                    </div>
                </div>

                <!-- Main Cards -->
                <div class="dashboard-grid">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Voting Progress</div>
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
            <div id="results" class="dashboard-section" style="display: none;">
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
                                <td class="card-change">↑ +12 votes/min</td>
                            </tr>
                            <tr>
                                <td>Jane Smith (Party B)</td>
                                <td>380</td>
                                <td>38%</td>
                                <td class="card-change">↑ +8 votes/min</td>
                            </tr>
                            <tr>
                                <td>Bob Johnson (Independent)</td>
                                <td>170</td>
                                <td>17%</td>
                                <td class="card-change negative">↓ -2 votes/min</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Voter Management Section -->
            <div id="voters" class="dashboard-section" style="display: none;">
                <div class="table-container">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <h3 class="chart-title">Voter Management</h3>
                        <input type="text" placeholder="Search voters..." style="padding: 0.5rem; border: 1px solid #ddd; border-radius: 6px;">
                    </div>
                    <table>
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
                        <tbody>
                            <tr>
                                <td>#001</td>
                                <td>John Smith</td>
                                <td>john@example.com</td>
                                <td><span class="status-badge status-verified">Verified</span></td>
                                <td><span class="status-badge status-active">Yes</span></td>
                                <td><span class="status-badge status-active">Yes</span></td>
                                <td>2024-01-15 14:30</td>
                                <td>
                                    <button class="action-btn btn-view">View</button>
                                    <button class="action-btn btn-edit">Edit</button>
                                </td>
                            </tr>
                            <tr>
                                <td>#002</td>
                                <td>Jane Doe</td>
                                <td>jane@example.com</td>
                                <td><span class="status-badge status-pending">Pending</span></td>
                                <td><span class="status-badge status-pending">No</span></td>
                                <td><span class="status-badge status-blocked">No</span></td>
                                <td>2024-01-14 09:15</td>
                                <td>
                                    <button class="action-btn btn-approve">Approve</button>
                                    <button class="action-btn btn-delete">Reject</button>
                                </td>
                            </tr>
                            <tr>
                                <td>#003</td>
                                <td>Bob Wilson</td>
                                <td>bob@example.com</td>
                                <td><span class="status-badge status-blocked">Blocked</span></td>
                                <td><span class="status-badge status-verified">Yes</span></td>
                                <td><span class="status-badge status-blocked">No</span></td>
                                <td>2024-01-13 16:45</td>
                                <td>
                                    <button class="action-btn btn-edit">Unblock</button>
                                    <button class="action-btn btn-view">Details</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- AI Fraud Detection Section -->
            <div id="ai-alerts" class="dashboard-section" style="display: none;">
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

            <!-- Additional sections would continue here for Election Management, Audit Logs, etc. -->
        </div>
    </div>

    <script>
        // Navigation function
        function showSection(sectionId) {
            // Hide all sections
            document.querySelectorAll('.dashboard-section').forEach(section => {
                section.style.display = 'none';
            });
            
            // Remove active class from all nav items
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Show selected section
            document.getElementById(sectionId).style.display = 'block';
            
            // Add active class to clicked nav item
            event.target.closest('.nav-item').classList.add('active');
            
            // Update page title
            const titles = {
                'overview': 'Dashboard Overview',
                'results': 'Live Election Results',
                'voters': 'Voter Management',
                'elections': 'Election Management',
                'ai-alerts': 'AI Fraud Detection',
                'audit': 'Audit Logs',
                'monitoring': 'System Monitoring',
                'reports': 'Reports & Analytics',
                'settings': 'System Settings'
            };
            document.getElementById('pageTitle').textContent = titles[sectionId];
        }

        // Tab switching for election results
        function showElectionTab(electionType) {
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            event.target.classList.add('active');
            
            // Update chart title
            document.querySelector('#results .chart-title').textContent = 
                `Live ${electionType.charAt(0).toUpperCase() + electionType.slice(1).replace('-', ' ')} Election Results`;
            
            // Here you would update the chart data based on election type
            updateResultsChart(electionType);
        }

        // Toggle profile dropdown
        function toggleProfileDropdown() {
            document.getElementById('profileDropdown').classList.toggle('active');
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

        // Initialize charts
        document.addEventListener('DOMContentLoaded', function() {
            // Votes per hour chart
            const votesCtx = document.getElementById('votesChart').getContext('2d');
            new Chart(votesCtx, {
                type: 'line',
                data: {
                    labels: ['8AM', '9AM', '10AM', '11AM', '12PM', '1PM', '2PM'],
                    datasets: [{
                        label: 'Votes',
                        data: [120, 190, 300, 500, 200, 300, 450],
                        borderColor: '#3498db',
                        backgroundColor: 'rgba(52, 152, 219, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });

            // Status distribution chart
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Verified', 'Pending', 'Blocked'],
                    datasets: [{
                        data: [850, 250, 150],
                        backgroundColor: ['#27ae60', '#f39c12', '#e74c3c']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Results chart
            const resultsCtx = document.getElementById('resultsChart').getContext('2d');
            window.resultsChart = new Chart(resultsCtx, {
                type: 'bar',
                data: {
                    labels: ['John Doe', 'Jane Smith', 'Bob Johnson'],
                    datasets: [{
                        label: 'Votes',
                        data: [450, 380, 170],
                        backgroundColor: ['#3498db', '#9b59b6', '#2ecc71']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });

        // Update results chart based on election type
        function updateResultsChart(type) {
            const data = {
                'president': [450, 380, 170],
                'vice-president': [320, 410, 120],
                'secretary': [280, 290, 180]
            };
            
            window.resultsChart.data.datasets[0].data = data[type] || data.president;
            window.resultsChart.update();
        }

        // Simulate real-time updates
        setInterval(() => {
            // Update voter count (simulate new registrations)
            const voterCount = document.getElementById('totalVoters');
            const currentVoters = parseInt(voterCount.textContent.replace(',', ''));
            if (Math.random() > 0.7) {
                voterCount.textContent = (currentVoters + 1).toLocaleString();
            }

            // Update votes cast
            const votesCast = document.getElementById('votesCast');
            const currentVotes = parseInt(votesCast.textContent.replace(',', ''));
            if (Math.random() > 0.5) {
                votesCast.textContent = (currentVotes + Math.floor(Math.random() * 3)).toLocaleString();
            }
        }, 5000);
    </script>
</body>
</html>