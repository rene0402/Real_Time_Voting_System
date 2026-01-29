<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Voter - Real-Time Voting System</title>

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

        /* Form Container */
        .form-container {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            max-width: 600px;
            margin: 0 auto;
        }

        .form-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #2c3e50;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #2c3e50;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-input:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.1);
        }

        .form-select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            background: white;
            cursor: pointer;
        }

        .form-select:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.1);
        }

        /* Checkbox Group */
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 6px;
            border: 1px solid #e9ecef;
        }

        .checkbox-input {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .checkbox-label {
            font-weight: 500;
            color: #495057;
            cursor: pointer;
            margin: 0;
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
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

        /* Alert Messages */
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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

            .form-container {
                padding: 1.5rem;
            }

            .form-actions {
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
                <div class="page-title">Edit Voter</div>
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

            <!-- Alert Messages -->
            @if($errors->any())
                <div class="alert alert-error">
                    <ul style="list-style: none; padding: 0;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Edit Voter Form -->
            <div class="form-container">
                <div class="form-title">
                    <i class="fas fa-user-edit"></i>
                    Edit Voter Information
                </div>

                <form method="POST" action="{{ route('admin.voter-management.update', $voter->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="fas fa-user" style="margin-right: 0.5rem;"></i>
                            Full Name *
                        </label>
                        <input type="text" id="name" name="name" class="form-input"
                               value="{{ old('name', $voter->name) }}" required
                               placeholder="Enter voter's full name">
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope" style="margin-right: 0.5rem;"></i>
                            Email Address *
                        </label>
                        <input type="email" id="email" name="email" class="form-input"
                               value="{{ old('email', $voter->email) }}" required
                               placeholder="Enter voter's email address">
                    </div>

                    <div class="form-group">
                        <label for="user_type" class="form-label">
                            <i class="fas fa-user-tag" style="margin-right: 0.5rem;"></i>
                            User Type *
                        </label>
                        <select id="user_type" name="user_type" class="form-select" required>
                            <option value="voter" {{ old('user_type', $voter->user_type) == 'voter' ? 'selected' : '' }}>
                                Voter
                            </option>
                            <option value="admin" {{ old('user_type', $voter->user_type) == 'admin' ? 'selected' : '' }}>
                                Admin
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="hidden" name="email_verified" value="0">
                            <input type="checkbox" id="email_verified" name="email_verified" value="1" class="checkbox-input"
                                   {{ old('email_verified', $voter->email_verified_at ? '1' : '0') == '1' ? 'checked' : '' }}>
                            <label for="email_verified" class="checkbox-label">
                                <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>
                                Email Verified
                            </label>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('admin.voter-management.show', $voter->id) }}" class="btn btn-secondary">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Voter
                        </button>
                        <a href="{{ route('admin.voter-management.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
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

        // Handle email verification checkbox
        document.getElementById('email_verified').addEventListener('change', function() {
            // Add hidden input for email_verified_at timestamp when checked
            const form = this.closest('form');
            const existingInput = form.querySelector('input[name="email_verified_at"]');

            if (this.checked) {
                if (!existingInput) {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'email_verified_at';
                    hiddenInput.value = new Date().toISOString().slice(0, 19).replace('T', ' ');
                    form.appendChild(hiddenInput);
                }
            } else {
                if (existingInput) {
                    existingInput.remove();
                }
            }
        });

        // Initialize checkbox state
        document.addEventListener('DOMContentLoaded', function() {
            const checkbox = document.getElementById('email_verified');
            if (checkbox.checked) {
                const form = checkbox.closest('form');
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'email_verified_at';
                hiddenInput.value = new Date().toISOString().slice(0, 19).replace('T', ' ');
                form.appendChild(hiddenInput);
            }
        });
    </script>
</body>
</html>
