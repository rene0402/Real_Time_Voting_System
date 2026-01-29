<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Voter Details - Real-Time Voting System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

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

        /* Detail Container */
        .detail-container {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            max-width: 800px;
            margin: 0 auto;
        }

        .detail-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #eaeaea;
        }

        .voter-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3498db, #2980b9);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            font-weight: 600;
        }

        .voter-info h1 {
            font-size: 1.8rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .voter-email {
            color: #666;
            font-size: 1.1rem;
        }

        /* Detail Grid */
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .detail-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
        }

        .detail-section h3 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e9ecef;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 500;
            color: #495057;
        }

        .detail-value {
            font-weight: 600;
            color: #2c3e50;
        }

        /* Status Badges */
        .status-badge {
            padding: 0.4rem 0.8rem;
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

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #eaeaea;
        }

        .btn {
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            font-size: 1rem;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-family: inherit;
        }

        .btn-primary {
            background: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #f8f9fa;
            color: #666;
            border: 1px solid #ddd;
        }

        .btn-secondary:hover {
            background: #eaeaea;
        }

        .btn-danger {
            background: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }

        /* Back Button */
        .back-btn {
            position: absolute;
            top: 2rem;
            left: 2rem;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            text-decoration: none;
            color: #666;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            transition: all 0.3s;
        }

        .back-btn:hover {
            background: #f8f9fa;
            transform: translateY(-2px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .main-content {
                padding: 1.5rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }

            .detail-header {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .back-btn {
                position: static;
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Hidden logout form for Laravel -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                Real-Time <span>Voting</span>
            </div>
            <ul class="nav-menu">
                <li class="nav-item" onclick="window.location.href='{{ route('dashboard') }}'">
                    <i class="nav-icon fas fa-chart-bar"></i>Dashboard Overview
                </li>
                <li class="nav-item" onclick="window.location.href='{{ route('dashboard') }}#results'">
                    <i class="nav-icon fas fa-poll"></i>Live Results
                </li>
                <li class="nav-item active">
                    <i class="nav-icon fas fa-users"></i>Voter Management
                </li>
                <li class="nav-item" onclick="window.location.href='{{ route('dashboard') }}#elections'">
                    <i class="nav-icon fas fa-vote-yea"></i>Election Management
                </li>
                <li class="nav-item" onclick="window.location.href='{{ route('dashboard') }}#ai-alerts'">
                    <i class="nav-icon fas fa-robot"></i>AI Fraud Detection
                </li>
                <li class="nav-item" onclick="window.location.href='{{ route('dashboard') }}#audit'">
                    <i class="nav-icon fas fa-clipboard-list"></i>Audit Logs
                </li>
                <li class="nav-item" onclick="window.location.href='{{ route('dashboard') }}#monitoring'">
                    <i class="nav-icon fas fa-tachometer-alt"></i>System Monitoring
                </li>
                <li class="nav-item" onclick="window.location.href='{{ route('dashboard') }}#reports'">
                    <i class="nav-icon fas fa-chart-pie"></i>Reports & Analytics
                </li>
                <li class="nav-item" onclick="window.location.href='{{ route('dashboard') }}#settings'">
                    <i class="nav-icon fas fa-cog"></i>Settings
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
            <a href="{{ route('admin.voter-management.index') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back to Voters
            </a>

            <div class="header">
                <div class="page-title">Voter Details</div>
                <div class="user-info">
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

            <!-- Voter Detail Container -->
            <div class="detail-container">
                <div class="detail-header">
                    <div class="voter-avatar">
                        {{ strtoupper(substr($voter->name, 0, 1)) }}
                    </div>
                    <div class="voter-info">
                        <h1>{{ $voter->name }}</h1>
                        <div class="voter-email">{{ $voter->email }}</div>
                    </div>
                </div>

                <div class="detail-grid">
                    <!-- Account Information -->
                    <div class="detail-section">
                        <h3>
                            <i class="fas fa-user-circle"></i>
                            Account Information
                        </h3>
                        <div class="detail-item">
                            <span class="detail-label">User ID</span>
                            <span class="detail-value">#{{ $voter->id }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">User Type</span>
                            <span class="detail-value">{{ ucfirst($voter->user_type) }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Account Status</span>
                            @if($voter->blocked_at)
                                <span class="status-badge status-blocked">Blocked</span>
                            @else
                                <span class="status-badge status-active">Active</span>
                            @endif
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Email Verified</span>
                            @if($voter->email_verified_at)
                                <span class="status-badge status-verified">Yes</span>
                            @else
                                <span class="status-badge status-pending">No</span>
                            @endif
                        </div>
                    </div>

                    <!-- Activity Information -->
                    <div class="detail-section">
                        <h3>
                            <i class="fas fa-clock"></i>
                            Activity Information
                        </h3>
                        <div class="detail-item">
                            <span class="detail-label">Account Created</span>
                            <span class="detail-value">{{ $voter->created_at->format('M d, Y H:i') }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Last Updated</span>
                            <span class="detail-value">{{ $voter->updated_at->format('M d, Y H:i') }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Last Login</span>
                            <span class="detail-value">
                                {{ $voter->last_login_at ? $voter->last_login_at->format('M d, Y H:i') : 'Never' }}
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Email Verified At</span>
                            <span class="detail-value">
                                {{ $voter->email_verified_at ? $voter->email_verified_at->format('M d, Y H:i') : 'Not verified' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ route('admin.voter-management.edit', $voter->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit Voter
                    </a>
                    <a href="{{ route('admin.voter-management.index') }}" class="btn btn-secondary">
                        <i class="fas fa-list"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Navigation function
        function showSection(sectionId) {
            // Redirect to dashboard with section hash
            window.location.href = '{{ route("dashboard") }}#' + sectionId;
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
            // Use the hidden form to logout
            document.getElementById('logout-form').submit();
        }
    </script>
</body>
</html>
