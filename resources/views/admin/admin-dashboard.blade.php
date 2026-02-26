<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - Real-Time Voting System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Chart.js for data visualization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --cpsu-blue: #004d00;
            --cpsu-gold: #FFD700;
            --cpsu-green: #28a745;
            --cpsu-red: #dc3545;
            --cpsu-purple: #6f42c1;
            --cpsu-orange: #f39c12;
            --cpsu-light: #f8f9fa;
            --cpsu-dark: #2c3e50;
            --shadow-sm: 0 2px 10px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 4px 20px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
            color: #333;
            overflow-x: hidden;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar - CPSU Themed */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, var(--cpsu-blue) 0%, #003d00 100%);
            color: white;
            padding: 0;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            box-shadow: var(--shadow-lg);
            z-index: 100;
        }

        .logo {
            padding: 2rem 1.5rem;
            font-size: 1.8rem;
            font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,0.15);
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(0, 0, 0, 0.1);
        }

        .logo-text {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .logo-text .main {
            font-size: 1.5rem;
            color: white;
        }

        .logo-text .sub {
            font-size: 0.75rem;
            color: var(--cpsu-gold);
            font-weight: 500;
        }

        .nav-menu {
            list-style: none;
            flex: 1;
            padding: 1rem 0;
            overflow-y: auto;
        }

        .nav-menu::-webkit-scrollbar {
            width: 6px;
        }

        .nav-menu::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .nav-menu::-webkit-scrollbar-thumb {
            background: rgba(255, 215, 0, 0.3);
            border-radius: 3px;
        }

        .nav-item {
            padding: 0.9rem 1.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            align-items: center;
            margin: 0.3rem 0.8rem;
            border-radius: 12px;
            font-weight: 500;
            position: relative;
        }

        .nav-item:hover {
            background: rgba(255, 215, 0, 0.15);
            transform: translateX(5px);
        }

        .nav-item.active {
            background: var(--cpsu-gold);
            color: var(--cpsu-blue);
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 60%;
            background: var(--cpsu-blue);
            border-radius: 0 4px 4px 0;
        }

        .nav-icon {
            margin-right: 12px;
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        /* Logout section */
        .logout-section {
            padding: 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.15);
            background: rgba(0, 0, 0, 0.1);
        }

        .logout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 0.9rem;
            background: rgba(220, 53, 69, 0.15);
            color: white;
            border: 2px solid rgba(220, 53, 69, 0.3);
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            font-family: inherit;
            font-size: 1rem;
        }

        .logout-btn:hover {
            background: var(--cpsu-red);
            border-color: var(--cpsu-red);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }

        .logout-icon {
            margin-right: 8px;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 2.5rem;
            overflow-y: auto;
            background: transparent;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            padding: 1.5rem 2rem;
            background: white;
            border-radius: 20px;
            box-shadow: var(--shadow-sm);
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--cpsu-blue);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-title::before {
            content: '';
            width: 5px;
            height: 40px;
            background: var(--cpsu-gold);
            border-radius: 3px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .alert-badge {
            background: linear-gradient(135deg, var(--cpsu-red), #c0392b);
            color: white;
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            animation: pulse 2s infinite;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
        }

        @keyframes pulse {
            0% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.8; transform: scale(1.02); }
            100% { opacity: 1; transform: scale(1); }
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.7rem 1.2rem;
            background: var(--cpsu-light);
            border-radius: 50px;
            cursor: pointer;
            position: relative;
            border: 2px solid transparent;
            transition: all 0.3s;
        }

        .user-profile:hover {
            border-color: var(--cpsu-gold);
            background: white;
        }

        .profile-dropdown {
            position: absolute;
            top: 110%;
            right: 0;
            background: white;
            border-radius: 15px;
            box-shadow: var(--shadow-lg);
            display: none;
            min-width: 220px;
            z-index: 1000;
            overflow: hidden;
        }

        .profile-dropdown.active {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-item {
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }

        .dropdown-item:hover {
            background: var(--cpsu-light);
            color: var(--cpsu-blue);
        }

        .dropdown-item.logout {
            color: var(--cpsu-red);
            border-top: 1px solid #eee;
        }

        .dropdown-item.logout:hover {
            background: rgba(220, 53, 69, 0.1);
        }

        /* Dashboard Cards */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 2.5rem;
        }

        .card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--cpsu-blue), var(--cpsu-gold));
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .card-title {
            font-size: 0.95rem;
            color: #7f8c8d;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--cpsu-blue);
            margin-bottom: 0.8rem;
            background: linear-gradient(135deg, var(--cpsu-blue), var(--cpsu-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .card-change {
            font-size: 0.9rem;
            color: var(--cpsu-green);
            font-weight: 500;
        }

        .card-change.negative {
            color: var(--cpsu-red);
        }

        .progress-bar {
            height: 10px;
            background: #ecf0f1;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 1rem;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--cpsu-blue), var(--cpsu-gold));
            border-radius: 10px;
            transition: width 0.5s ease;
            box-shadow: 0 0 10px rgba(255, 215, 0, 0.3);
        }

        /* Stats Row */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2.5rem;
        }

        .stat-item {
            text-align: center;
            padding: 1.5rem;
            background: white;
            border-radius: 15px;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s;
            border-top: 4px solid var(--cpsu-gold);
        }

        .stat-item:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--cpsu-blue), var(--cpsu-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #7f8c8d;
            margin-top: 0.5rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Charts Section */
        .charts-section {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            margin-bottom: 2.5rem;
        }

        .chart-container {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--shadow-sm);
            height: 400px;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        .chart-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--cpsu-blue), var(--cpsu-gold));
        }

        .chart-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--cpsu-blue);
        }

        .chart-container canvas {
            flex: 1;
            width: 100% !important;
            height: 100% !important;
        }

        /* Tables */
        .table-container {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--shadow-sm);
            margin-bottom: 2.5rem;
            overflow-x: auto;
            position: relative;
        }

        .table-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--cpsu-blue), var(--cpsu-gold));
            border-radius: 20px 20px 0 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        th {
            text-align: left;
            padding: 1.2rem 1rem;
            background: var(--cpsu-light);
            font-weight: 700;
            color: var(--cpsu-blue);
            border-bottom: 3px solid var(--cpsu-gold);
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        td {
            padding: 1.2rem 1rem;
            border-bottom: 1px solid #f0f0f0;
        }

        tr {
            transition: all 0.3s;
        }

        tr:hover {
            background: var(--cpsu-light);
            transform: scale(1.01);
        }

        /* Status Badges */
        .status-badge {
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .status-verified {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
        }

        .status-pending {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            color: #856404;
        }

        .status-blocked {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
        }

        .status-active {
            background: linear-gradient(135deg, #d1ecf1, #bee5eb);
            color: #0c5460;
        }

        .status-flagged {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
            animation: blink 1s infinite;
        }

        .status-closed {
            background: linear-gradient(135deg, #e2e3e5, #d6d8db);
            color: #383d41;
        }

        .status-scheduled {
            background: linear-gradient(135deg, #d1ecf1, #bee5eb);
            color: #0c5460;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        /* Action Buttons */
        .action-btn {
            padding: 0.5rem 1.2rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.3s;
            margin-right: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .btn-view {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
        }

        .btn-view:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
        }

        .btn-edit {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: white;
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(243, 156, 18, 0.3);
        }

        .btn-delete {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }

        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
        }

        .btn-approve {
            background: linear-gradient(135deg, #27ae60, #229954);
            color: white;
        }

        .btn-approve:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);
        }

        .btn-dismiss {
            background: linear-gradient(135deg, #95a5a6, #7f8c8d);
            color: white;
        }

        .btn-dismiss:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(149, 165, 166, 0.3);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--cpsu-blue), #003d00);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 77, 0, 0.3);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #95a5a6, #7f8c8d);
            color: white;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(149, 165, 166, 0.3);
        }

        /* Alert Panel */
        .alert-panel {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            border-left: 5px solid var(--cpsu-orange);
            padding: 1.5rem;
            margin-bottom: 2.5rem;
            border-radius: 15px;
            box-shadow: var(--shadow-sm);
        }

        .alert-title {
            font-weight: 700;
            color: #856404;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }

        .alert-content {
            color: #856404;
            font-weight: 500;
        }

        /* Tabs */
        .tabs {
            display: flex;
            border-bottom: 3px solid var(--cpsu-light);
            margin-bottom: 2rem;
            background: white;
            border-radius: 15px 15px 0 0;
            padding: 0 1rem;
            box-shadow: var(--shadow-sm);
        }

        .tab {
            padding: 1.2rem 1.8rem;
            cursor: pointer;
            font-weight: 600;
            color: #7f8c8d;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
            user-select: none;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }

        .tab.active {
            color: var(--cpsu-blue);
            border-bottom: 3px solid var(--cpsu-gold);
            background: linear-gradient(to bottom, rgba(255, 215, 0, 0.1), transparent);
        }

        .tab:hover:not(.active) {
            color: var(--cpsu-blue);
            background: var(--cpsu-light);
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
            padding: 0.9rem 1.2rem;
            border: 2px solid #ddd;
            border-radius: 50px;
            font-size: 0.9rem;
            min-width: 250px;
            transition: all 0.3s;
            font-weight: 500;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--cpsu-gold);
            box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.1);
        }

        /* Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
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
            border-radius: 25px;
            padding: 2.5rem;
            max-width: 550px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: var(--shadow-lg);
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--cpsu-blue);
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
            margin-top: 2rem;
        }

        .modal-btn {
            padding: 0.9rem 1.8rem;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            font-family: inherit;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .modal-btn.cancel {
            background: var(--cpsu-light);
            color: #666;
            border: 2px solid #ddd;
        }

        .modal-btn.cancel:hover {
            background: #eaeaea;
            transform: translateY(-2px);
        }

        .modal-btn.logout {
            background: linear-gradient(135deg, var(--cpsu-red), #c0392b);
            color: white;
        }

        .modal-btn.logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
        }

        /* Election Management Styles */
        .election-tabs {
            display: flex;
            border-bottom: 3px solid var(--cpsu-light);
            margin-bottom: 2rem;
            overflow-x: auto;
            background: white;
            border-radius: 15px 15px 0 0;
            padding: 0 1rem;
            box-shadow: var(--shadow-sm);
        }

        .election-tab {
            padding: 1.2rem 1.8rem;
            cursor: pointer;
            font-weight: 600;
            color: #7f8c8d;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
            white-space: nowrap;
            user-select: none;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }

        .election-tab.active {
            color: var(--cpsu-blue);
            border-bottom: 3px solid var(--cpsu-gold);
            background: linear-gradient(to bottom, rgba(255, 215, 0, 0.1), transparent);
        }

        .election-tab:hover:not(.active) {
            color: var(--cpsu-blue);
            background: var(--cpsu-light);
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
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            font-weight: 700;
            color: var(--cpsu-blue);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-input {
            padding: 0.9rem 1.2rem;
            border: 2px solid #ddd;
            border-radius: 12px;
            font-size: 0.9rem;
            transition: all 0.3s;
            font-weight: 500;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--cpsu-gold);
            box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.1);
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid var(--cpsu-light);
        }

        /* Management Grid */
        .manage-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .manage-content {
            padding: 1rem 0;
        }

        .manage-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .manage-item:hover {
            background: var(--cpsu-light);
            transform: translateX(5px);
        }

        .manage-item:last-child {
            border-bottom: none;
        }

        /* Security Grid */
        .security-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .security-content {
            padding: 1rem 0;
        }

        .security-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .security-item:hover {
            background: var(--cpsu-light);
        }

        .security-item:last-child {
            border-bottom: none;
        }

        .alert-count {
            background: linear-gradient(135deg, var(--cpsu-red), #c0392b);
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 700;
            box-shadow: 0 4px 10px rgba(231, 76, 60, 0.3);
        }

        .countdown-timer {
            text-align: center;
            padding: 1.5rem;
            background: var(--cpsu-light);
            border-radius: 15px;
            margin-top: 1rem;
            border: 2px dashed var(--cpsu-gold);
        }

        .countdown-display {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--cpsu-red), #c0392b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-family: 'Courier New', monospace;
        }

        /* Emergency Buttons */
        .emergency-btn {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            justify-content: center;
            padding: 1rem;
            margin-bottom: 0.8rem;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            width: 100%;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .emergency-btn.pause {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: white;
        }

        .emergency-btn.pause:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(243, 156, 18, 0.3);
        }

        .emergency-btn.close {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }

        .emergency-btn.close:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
        }

        .emergency-btn.lock {
            background: linear-gradient(135deg, #9b59b6, #8e44ad);
            color: white;
        }

        .emergency-btn.lock:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(155, 89, 182, 0.3);
        }

        /* Toggle Slider Styles */
        .toggle-label {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 30px;
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
            border-radius: 30px;
            transition: 0.4s;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            border-radius: 50%;
            transition: 0.4s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        input:checked + .toggle-slider {
            background: linear-gradient(135deg, #27ae60, #229954);
        }

        input:checked + .toggle-slider:before {
            transform: translateX(30px);
        }

        /* Audit Logs Styles */
            .filters-section {
                background: white;
                border-radius: 15px;
                padding: 1.5rem;
                margin-bottom: 1.5rem;
                box-shadow: var(--shadow-sm);
            }

            .filters-row {
                display: flex;
                gap: 1rem;
                flex-wrap: wrap;
                align-items: flex-end;
            }

            .filter-group {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }

            .filter-label {
                font-weight: 600;
                color: var(--cpsu-blue);
                font-size: 0.85rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .filter-input {
                padding: 0.7rem 1rem;
                border: 2px solid #ddd;
                border-radius: 10px;
                font-size: 0.9rem;
                min-width: 150px;
                transition: all 0.3s;
                font-weight: 500;
            }

            .filter-input:focus {
                outline: none;
                border-color: var(--cpsu-gold);
                box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.1);
            }

            .filter-actions {
                margin-left: auto;
            }

            .btn-export {
                background: linear-gradient(135deg, var(--cpsu-green), #229954);
                color: white;
                border: none;
                padding: 0.7rem 1.5rem;
                border-radius: 10px;
                cursor: pointer;
                font-weight: 600;
                transition: all 0.3s;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .btn-export:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);
            }

            .action-type-badge {
                padding: 0.4rem 0.8rem;
                border-radius: 50px;
                font-size: 0.75rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.3px;
            }

            .badge-admin_action {
                background: linear-gradient(135deg, #3498db, #2980b9);
                color: white;
            }

            .badge-voter_activity {
                background: linear-gradient(135deg, #9b59b6, #8e44ad);
                color: white;
            }

            .badge-vote_submission {
                background: linear-gradient(135deg, #27ae60, #229954);
                color: white;
            }

            .badge-system_change {
                background: linear-gradient(135deg, #f39c12, #e67e22);
                color: white;
            }

            .category-badge {
                padding: 0.3rem 0.6rem;
                border-radius: 6px;
                font-size: 0.75rem;
                font-weight: 600;
                background: var(--cpsu-light);
                color: #666;
            }

            .empty-state {
                text-align: center;
                padding: 3rem;
                color: #999;
            }

            .empty-state i {
                font-size: 3rem;
                margin-bottom: 1rem;
                color: #ddd;
            }

            .empty-state h3 {
                margin: 0 0 0.5rem 0;
                color: #666;
            }

            .empty-state p {
                margin: 0;
                font-size: 0.9rem;
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
                width: 220px;
            }

            .main-content {
                padding: 2rem;
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
                padding: 0.9rem 1.2rem;
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
        /* Status Dot for Monitoring */
        .status-dot {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 8px;
            animation: pulse 2s infinite;
            vertical-align: middle;
        }

        .status-dot.online {
            background: linear-gradient(135deg, #27ae60, #229954);
            box-shadow: 0 0 10px rgba(39, 174, 96, 0.5);
        }

        .status-dot.offline {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            box-shadow: 0 0 10px rgba(231, 76, 60, 0.5);
            animation: blink 1s infinite;
        }

        /* Report Tab Styles */
        .report-tab {
            display: none;
        }

        .report-tab.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .anomaly-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .anomaly-high {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }

        .anomaly-medium {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: white;
        }

        .anomaly-low {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
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
                            <i class="fas fa-chart-line" style="font-size: 2rem; color: var(--cpsu-blue);"></i>
                        </div>
                        <div class="card-value">{{ $votingProgress }}%</div>
                        <div class="card-change">{{ $votesCast }} votes cast</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $votingProgress }}%"></div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">System Health</div>
                            <i class="fas fa-heartbeat" style="font-size: 2rem; color: var(--cpsu-green);"></i>
                        </div>
                        <div class="card-value">{{ $systemHealth['percentage'] ?? 100 }}%</div>
                        <div class="card-change">{{ $systemHealth['label'] ?? 'Operational' }}</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $systemHealth['percentage'] ?? 100 }}%; background: linear-gradient(90deg, var(--cpsu-green), #229954);"></div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Active Users</div>
                            <i class="fas fa-user-clock" style="font-size: 2rem; color: var(--cpsu-purple);"></i>
                        </div>
                        <div class="card-value">{{ $activeUsersCount }}</div>
                        <div class="card-change">Currently active</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ min(100, $activeUsersCount * 2) }}%; background: linear-gradient(90deg, var(--cpsu-purple), #8e44ad);"></div>
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
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
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
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
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
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
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
                <!-- Audit Logs Stats -->
                <div class="stats-row">
                    <div class="stat-item">
                        <div class="stat-value" id="totalAuditLogs">{{ \App\Models\AuditLog::count() }}</div>
                        <div class="stat-label">Total Logs</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ \App\Models\AuditLog::where('action_type', 'admin_action')->count() }}</div>
                        <div class="stat-label">Admin Actions</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ \App\Models\AuditLog::where('action_type', 'voter_activity')->count() }}</div>
                        <div class="stat-label">Voter Activity</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ \App\Models\AuditLog::where('action_type', 'vote_submission')->count() }}</div>
                        <div class="stat-label">Vote Submissions</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ \App\Models\AuditLog::whereDate('created_at', \Carbon\Carbon::today())->count() }}</div>
                        <div class="stat-label">Today's Logs</div>
                    </div>
                </div>

                <!-- Audit Logs Tabs -->
                <div class="tabs">
                    <div class="tab active" onclick="showAuditTab('all')">All Logs</div>
                    <div class="tab" onclick="showAuditTab('admin_action')">Admin Actions</div>
                    <div class="tab" onclick="showAuditTab('voter_activity')">Voter Activity</div>
                    <div class="tab" onclick="showAuditTab('vote_submission')">Vote Timestamps</div>
                    <div class="tab" onclick="showAuditTab('system_change')">System Changes</div>
                </div>

                <!-- Audit Logs Filters -->
                <div class="filters-section">
                    <div class="filters-row">
                        <div class="filter-group">
                            <label class="filter-label">Search</label>
                            <input type="text" class="filter-input" id="auditSearch" placeholder="Search logs..." oninput="filterAuditLogs()">
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Date From</label>
                            <input type="date" class="filter-input" id="auditDateFrom" onchange="filterAuditLogs()">
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Date To</label>
                            <input type="date" class="filter-input" id="auditDateTo" onchange="filterAuditLogs()">
                        </div>
                        <div class="filter-actions">
                            <button class="btn btn-export" onclick="exportAuditLogs()">
                                <i class="fas fa-download"></i> Export CSV
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Audit Logs Table -->
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Date/Time</th>
                                <th>User</th>
                                <th>Action Type</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>IP Address</th>
                            </tr>
                        </thead>
                        <tbody id="auditLogsTableBody">
                            @php
                            $auditLogs = \App\Models\AuditLog::with('user')->orderBy('created_at', 'desc')->limit(100)->get();
                            @endphp
                            @forelse($auditLogs as $log)
                                <tr>
                                    <td>
                                        <div>{{ $log->created_at->format('M d, Y') }}</div>
                                        <div style="color: #999; font-size: 0.85rem;">{{ $log->created_at->format('H:i:s') }}</div>
                                    </td>
                                    <td>
                                        @if($log->user)
                                            <div>{{ $log->user->name }}</div>
                                            <div style="color: #999; font-size: 0.75rem;">{{ $log->user->email }}</div>
                                        @else
                                            <span style="color: #999;">System</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="action-type-badge badge-{{ $log->action_type }}">
                                            {{ $log->action_type_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="category-badge">{{ $log->category_label }}</span>
                                    </td>
                                    <td>
                                        <div style="max-width: 300px;">{{ $log->description }}</div>
                                    </td>
                                    <td>
                                        <span style="font-family: monospace; font-size: 0.85rem;">
                                            {{ $log->ip_address ?? 'N/A' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <i class="fas fa-clipboard-list"></i>
                                            <h3>No Audit Logs Found</h3>
                                            <p>No logs recorded yet. Start using the system to generate audit logs.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="monitoring" class="dashboard-section">
                <!-- Real-Time System Monitoring -->
                <div class="stats-row">
                    <!-- Server Status -->
                    <div class="stat-item">
                        <div class="stat-value">
                            <span id="serverStatusIndicator" class="status-dot {{ $monitoringData['server_status']['color'] === 'success' ? 'online' : 'offline' }}"></span>
                            <span id="serverStatusText">{{ $monitoringData['server_status']['label'] }}</span>
                        </div>
                        <div class="stat-label">Server Status</div>
                    </div>

                    <!-- Active Users -->
                    <div class="stat-item">
                        <div class="stat-value" id="activeUsersCount">{{ $monitoringData['active_users']['count'] }}</div>
                        <div class="stat-label">Active Users (15 min)</div>
                    </div>

                    <!-- Votes Per Minute -->
                    <div class="stat-item">
                        <div class="stat-value" id="votesPerMinuteCount">{{ $monitoringData['votes_per_minute'] }}</div>
                        <div class="stat-label">Votes Per Minute</div>
                    </div>

                    <!-- Votes Today -->
                    <div class="stat-item">
                        <div class="stat-value" id="votesTodayCount">{{ number_format($monitoringData['votes_today']) }}</div>
                        <div class="stat-label">Votes Today</div>
                    </div>
                </div>

                <!-- Second Row -->
                <div class="dashboard-grid">
                    <!-- Peak Voting Times -->
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Peak Voting Times</div>
                            <i class="fas fa-clock" style="font-size: 1.5rem; color: var(--cpsu-blue);"></i>
                        </div>
                        <div class="card-value" id="peakTime">{{ $monitoringData['peak_voting_times']['peak_label'] }}</div>
                        <div class="card-change">{{ $monitoringData['peak_voting_times']['peak_count'] }} votes at peak hour</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $monitoringData['peak_voting_times']['peak_count'] > 0 ? min(100, ($monitoringData['peak_voting_times']['peak_count'] / max($monitoringData['votes_today'], 1)) * 100) : 0 }}%"></div>
                        </div>
                    </div>

                    <!-- Average Votes Per Hour -->
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Average Votes/Hour</div>
                            <i class="fas fa-chart-line" style="font-size: 1.5rem; color: var(--cpsu-purple);"></i>
                        </div>
                        <div class="card-value" id="avgVotesPerHour">{{ $monitoringData['avg_votes_per_hour'] }}</div>
                        <div class="card-change">Last 24 hours</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ min(100, $monitoringData['avg_votes_per_hour'] * 10) }}%; background: linear-gradient(90deg, var(--cpsu-purple), #8e44ad);"></div>
                        </div>
                    </div>

                    <!-- System Uptime -->
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">System Uptime</div>
                            <i class="fas fa-server" style="font-size: 1.5rem; color: var(--cpsu-green);"></i>
                        </div>
                        <div class="card-value">{{ $monitoringData['system_uptime'] }}</div>
                        <div class="card-change">Since last restart</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 100%; background: linear-gradient(90deg, var(--cpsu-green), #229954);"></div>
                        </div>
                    </div>

                    <!-- Memory Usage -->
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Memory Usage</div>
                            <i class="fas fa-microchip" style="font-size: 1.5rem; color: var(--cpsu-orange);"></i>
                        </div>
                        <div class="card-value">{{ $monitoringData['memory_usage']['used'] }} / {{ $monitoringData['memory_usage']['total'] }}</div>
                        <div class="card-change">{{ $monitoringData['memory_usage']['percentage'] }}% used</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $monitoringData['memory_usage']['percentage'] }}%; background: linear-gradient(90deg, var(--cpsu-orange), #e67e22);"></div>
                        </div>
                    </div>
                </div>

                <!-- Peak Hours Chart -->
                <div class="chart-container" style="margin-top: 2rem;">
                    <div class="chart-title">Voting Activity by Hour (Last 7 Days)</div>
                    <canvas id="monitoringChart"></canvas>
                </div>

                <!-- Auto-refresh indicator -->
                <div style="text-align: center; margin-top: 1.5rem; color: #666;">
                    <i class="fas fa-sync fa-spin" style="margin-right: 0.5rem;"></i>
                    Auto-refreshing every 5 seconds
                </div>
            </div>

            <!-- Reports & Analytics Section -->
            <div id="reports" class="dashboard-section">
                <!-- Report Filters -->
                <div class="filters-section">
                    <div class="filters-row">
                        <div class="filter-group">
                            <label class="filter-label">Report Type</label>
                            <select class="filter-input" id="reportType" onchange="loadReportData()">
                                <option value="voter-turnout">Voter Turnout</option>
                                <option value="election-results">Election Results</option>
                                <option value="ai-patterns">AI Voting Patterns</option>
                                <option value="time-based">Time-Based Analysis</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Election</label>
                            <select class="filter-input" id="reportElectionFilter" onchange="loadReportData()">
                                <option value="">All Elections</option>
                                @if(isset($elections))
                                    @foreach($elections as $election)
                                        <option value="{{ $election->id }}">{{ $election->title }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Date Range</label>
                            <select class="filter-input" id="reportDateRange" onchange="loadReportData()">
                                <option value="7">Last 7 Days</option>
                                <option value="14">Last 14 Days</option>
                                <option value="30">Last 30 Days</option>
                                <option value="90">Last 90 Days</option>
                                <option value="all">All Time</option>
                            </select>
                        </div>
                        <div class="filter-actions">
                            <button class="btn-export" onclick="exportToCSV()">
                                <i class="fas fa-file-csv"></i> Export CSV
                            </button>
                            <button class="btn-export" style="background: linear-gradient(135deg, #e74c3c, #c0392b);" onclick="exportToPDF()">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Report Tabs -->
                <div class="tabs">
                    <div class="tab active" onclick="showReportTab('turnout')">Voter Turnout</div>
                    <div class="tab" onclick="showReportTab('results')">Election Results</div>
                    <div class="tab" onclick="showReportTab('ai-patterns')">AI Patterns</div>
                    <div class="tab" onclick="showReportTab('time-based')">Time Analysis</div>
                </div>

                <!-- Voter Turnout Report -->
                <div id="report-turnout" class="report-tab active">
                    <!-- Summary Stats -->
                    <div class="stats-row">
                        <div class="stat-item">
                            <div class="stat-value" id="totalRegisteredVoters">-</div>
                            <div class="stat-label">Total Registered</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="totalVotesCast">-</div>
                            <div class="stat-label">Total Votes Cast</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="overallTurnout">-</div>
                            <div class="stat-label">Overall Turnout %</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="avgTurnout">-</div>
                            <div class="stat-label">Avg per Election</div>
                        </div>
                    </div>

                    <!-- Turnout Chart -->
                    <div class="charts-section">
                        <div class="chart-container" style="height: 350px;">
                            <div class="chart-title">Voter Turnout by Election</div>
                            <canvas id="turnoutChart"></canvas>
                        </div>
                        <div class="chart-container" style="height: 350px;">
                            <div class="chart-title">Turnout Distribution</div>
                            <canvas id="turnoutPieChart"></canvas>
                        </div>
                    </div>

                    <!-- Turnout Table -->
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Election</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Votes Cast</th>
                                    <th>Turnout Rate</th>
                                    <th>Date Range</th>
                                </tr>
                            </thead>
                            <tbody id="turnoutTableBody">
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 2rem;">
                                        <i class="fas fa-spinner fa-spin"></i> Loading turnout data...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Election Results Report -->
                <div id="report-results" class="report-tab">
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Election</th>
                                    <th>Candidate</th>
                                    <th>Votes</th>
                                    <th>Percentage</th>
                                    <th>Status</th>
                                    <th>Winner</th>
                                </tr>
                            </thead>
                            <tbody id="resultsTableBody">
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 2rem;">
                                        <i class="fas fa-spinner fa-spin"></i> Loading results data...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Results Chart -->
                    <div class="chart-container" style="margin-top: 2rem; height: 400px;">
                        <div class="chart-title">Election Results Comparison</div>
                        <canvas id="resultsComparisonChart"></canvas>
                    </div>
                </div>

                <!-- AI Patterns Report -->
                <div id="report-ai-patterns" class="report-tab">
                    <!-- AI Stats -->
                    <div class="stats-row">
                        <div class="stat-item">
                            <div class="stat-value" id="aiTotalVotes">-</div>
                            <div class="stat-label">Total Votes Analyzed</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="aiUniqueVoters">-</div>
                            <div class="stat-label">Unique Voters</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="aiPeakHour">-</div>
                            <div class="stat-label">Peak Voting Hour</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="aiAvgPerHour">-</div>
                            <div class="stat-label">Avg Votes/Hour</div>
                        </div>
                    </div>

                    <!-- AI Pattern Chart -->
                    <div class="charts-section">
                        <div class="chart-container" style="height: 350px;">
                            <div class="chart-title">Voting Activity by Hour</div>
                            <canvas id="aiHourlyChart"></canvas>
                        </div>
                        <div class="chart-container" style="height: 350px;">
                            <div class="chart-title">Daily Voting Trends</div>
                            <canvas id="aiDailyChart"></canvas>
                        </div>
                    </div>

                    <!-- Anomalies Section -->
                    <div class="table-container">
                        <h3 class="chart-title" style="margin-bottom: 1.5rem;">Detected Anomalies</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Time</th>
                                    <th>Count</threshold>
                                    <th>Threshold</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="anomaliesTableBody">
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 2rem;">
                                        <i class="fas fa-spinner fa-spin"></i> Loading anomaly data...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Time-Based Analysis Report -->
                <div id="report-time-based" class="report-tab">
                    <!-- Time Stats -->
                    <div class="stats-row">
                        <div class="stat-item">
                            <div class="stat-value" id="timeTotalVotes">-</div>
                            <div class="stat-label">Total Votes</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="timeDaysAnalyzed">-</div>
                            <div class="stat-label">Days Analyzed</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="timeChangePercent">-</div>
                            <div class="stat-label">vs Previous Period</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="timeAvgDaily">-</div>
                            <div class="stat-label">Avg Daily Votes</div>
                        </div>
                    </div>

                    <!-- Cumulative Votes Chart -->
                    <div class="chart-container" style="height: 400px; margin-bottom: 2rem;">
                        <div class="chart-title">Cumulative Votes Over Time</div>
                        <canvas id="cumulativeChart"></canvas>
                    </div>

                    <!-- Hourly Heatmap -->
                    <div class="chart-container" style="height: 400px;">
                        <div class="chart-title">Voting Activity Heatmap (Hourly)</div>
                        <canvas id="heatmapChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Completed Elections Section -->
            <div id="completed" class="dashboard-section">
                <div class="table-container">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
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
        // All the existing JavaScript from the original file goes here
        // [KEEPING ALL EXISTING JAVASCRIPT FUNCTIONS UNCHANGED]

        // Navigation function
        function showSection(sectionId) {
            document.querySelectorAll('.dashboard-section').forEach(section => {
                section.classList.remove('active-section');
            });

            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
            });

            const selectedSection = document.getElementById(sectionId);
            if (selectedSection) {
                selectedSection.classList.add('active-section');

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

            event.currentTarget.classList.add('active');

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

            document.getElementById('profileDropdown').classList.remove('active');
        }

        function showElectionTab(electionType) {
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            event.target.classList.add('active');

            const titles = {
                'president': 'Presidential',
                'vice-president': 'Vice Presidential',
                'secretary': 'Secretary'
            };
            document.querySelector('#results .chart-title').textContent =
                `Live ${titles[electionType]} Election Results`;

            updateResultsChart(electionType);
        }

        function toggleProfileDropdown() {
            document.getElementById('profileDropdown').classList.toggle('active');
            event.stopPropagation();
        }

        document.addEventListener('click', function(event) {
            const profileDropdown = document.getElementById('profileDropdown');
            const userProfile = document.querySelector('.user-profile');

            if (!userProfile.contains(event.target)) {
                profileDropdown.classList.remove('active');
            }
        });

        function openLogoutModal() {
            document.getElementById('logoutModal').classList.add('active');
        }

        function closeLogoutModal() {
            document.getElementById('logoutModal').classList.remove('active');
        }

        function performLogout() {
            document.getElementById('logout-form').submit();
        }

        document.getElementById('logoutModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeLogoutModal();
            }
        });

        // Initialize charts
        let votesChart, statusChart, resultsChart;

        document.addEventListener('DOMContentLoaded', function() {
            const votesCtx = document.getElementById('votesChart').getContext('2d');
            votesChart = new Chart(votesCtx, {
                type: 'line',
                data: {
                    labels: ['8AM', '9AM', '10AM', '11AM', '12PM', '1PM', '2PM'],
                    datasets: [{
                        label: 'Votes',
                        data: [120, 190, 300, 500, 200, 300, 450],
                        borderColor: '#004d00',
                        backgroundColor: 'rgba(0, 77, 0, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3
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

            const statusCtx = document.getElementById('statusChart').getContext('2d');
            statusChart = new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Verified', 'Pending', 'Blocked'],
                    datasets: [{
                        data: [850, 250, 150],
                        backgroundColor: ['#28a745', '#f39c12', '#dc3545'],
                        borderWidth: 3,
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
                                usePointStyle: true,
                                font: {
                                    family: 'Poppins',
                                    weight: 600
                                }
                            }
                        }
                    },
                    cutout: '65%'
                }
            });

            const resultsCtx = document.getElementById('resultsChart').getContext('2d');
            resultsChart = new Chart(resultsCtx, {
                type: 'bar',
                data: {
                    labels: ['John Doe', 'Jane Smith', 'Bob Johnson'],
                    datasets: [{
                        label: 'Votes',
                        data: [450, 380, 170],
                        backgroundColor: ['#004d00', '#6f42c1', '#28a745'],
                        borderWidth: 2,
                        borderColor: '#fff',
                        borderRadius: 8
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

            document.querySelectorAll('.action-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const action = this.textContent.trim();
                    const row = this.closest('tr');
                    const voterName = row.querySelector('td:nth-child(2)').textContent;

                    showNotification(`Action "${action}" performed on ${voterName}`, 'info');
                });
            });
        });

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

        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.innerHTML = `
                <div>${message}</div>
                <button onclick="this.parentElement.remove()">&times;</button>
            `;

            const bgColor = type === 'info' ? '#3498db' : type === 'success' ? '#27ae60' : type === 'error' ? '#e74c3c' : '#f39c12';

            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${bgColor};
                color: white;
                padding: 1.2rem 1.8rem;
                border-radius: 15px;
                box-shadow: 0 8px 20px rgba(0,0,0,0.2);
                display: flex;
                align-items: center;
                justify-content: space-between;
                z-index: 3000;
                min-width: 350px;
                animation: slideIn 0.3s ease;
                font-weight: 500;
                font-family: 'Poppins', sans-serif;
            `;

            notification.querySelector('button').style.cssText = `
                background: none;
                border: none;
                color: white;
                font-size: 1.8rem;
                cursor: pointer;
                margin-left: 1.5rem;
                font-weight: 300;
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }

        // Live counter updates removed - dashboard now shows static values from server-side rendering

        // [CONTINUING WITH ALL OTHER EXISTING JAVASCRIPT FUNCTIONS FROM THE ORIGINAL FILE]
        // Election Management Functions, Load functions, etc. all remain exactly the same

        function showElectionSection(sectionId) {
            document.querySelectorAll('.election-section').forEach(section => {
                section.classList.remove('active-election-section');
            });

            document.querySelectorAll('.election-tab').forEach(tab => {
                tab.classList.remove('active');
            });

            const selectedSection = document.getElementById('election-' + sectionId);
            if (selectedSection) {
                selectedSection.classList.add('active-election-section');
            }

            const tab = document.querySelector(`.election-tab[onclick*="showElectionSection('${sectionId}')"]`);
            if (tab) {
                tab.classList.add('active');
            }
        }

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

        function populateElectionsTable(elections) {
            console.log('Populating table with elections:', elections);
            const tableBody = document.querySelector('#electionsTable tbody');
            tableBody.innerHTML = '';

            elections.forEach(election => {
                console.log('Processing election:', election);
                const row = document.createElement('tr');

                const startDate = new Date(election.start_date).toLocaleDateString();
                const endDate = new Date(election.end_date).toLocaleDateString();

                const statusClass = `status-${election.status}`;

                let actionButtons = election.actions || '';

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

        function updateElectionStats(stats) {
            document.getElementById('totalElections').textContent = stats.total_elections;
            document.getElementById('activeElectionsCount').textContent = stats.active_elections;
            document.getElementById('totalVotesElection').textContent = stats.total_votes.toLocaleString();
            document.getElementById('avgParticipation').textContent = `${stats.avg_participation}%`;
        }

        function viewElection(id) {
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

                    const startDate = new Date(election.start_date).toLocaleString();
                    const endDate = new Date(election.end_date).toLocaleString();
                    const createdDate = new Date(election.created_at).toLocaleString();
                    const updatedDate = new Date(election.updated_at).toLocaleString();

                    const statusClass = `status-${election.status}`;

                    const modal = document.createElement('div');
                    modal.className = 'modal-overlay';
                    modal.innerHTML = `
                        <div class="modal" style="max-width: 600px;">
                            <div class="modal-title">Election Details</div>
                            <div class="modal-content">
                                <div style="display: grid; gap: 1.5rem;">
                                    <div style="text-align: center; padding: 1.5rem; background: var(--cpsu-light); border-radius: 15px;">
                                        <h2 style="margin: 0; color: var(--cpsu-blue); font-size: 1.8rem;">${election.title}</h2>
                                        <div style="margin-top: 0.8rem;">
                                            <span class="status-badge ${statusClass}" style="font-size: 1rem;">${election.status.charAt(0).toUpperCase() + election.status.slice(1)}</span>
                                        </div>
                                    </div>

                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
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
                                        <div style="background: var(--cpsu-light); padding: 1.2rem; border-radius: 12px; margin-top: 0.8rem;">
                                            ${election.description || 'No description provided'}
                                        </div>
                                    </div>

                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; font-size: 0.9rem; color: #666;">
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
                        loadElections();
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
                        loadElections();
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
                        loadElections();
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

            const startDateTime = new Date(startDate);
            const endDateTime = new Date(endDate);

            if (endDateTime <= startDateTime) {
                showNotification('End date must be after start date', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('title', title);
            formData.append('type', type);
            formData.append('start_date', startDate);
            formData.append('end_date', endDate);
            formData.append('description', description);

            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            const url = electionId ? `/admin/elections/${electionId}` : '/admin/elections';
            const method = 'POST';

            if (electionId) {
                formData.append('_method', 'PUT');
            }

            fetch(url, {
                method: method,
                headers: {
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    return response.text().then(text => {
                        throw new Error('Server returned HTML instead of JSON: ' + text.substring(0, 200));
                    });
                }
            })
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    document.getElementById('electionTitle').value = '';
                    document.getElementById('electionType').value = 'single';
                    document.getElementById('electionStart').value = '';
                    document.getElementById('electionEnd').value = '';
                    document.getElementById('electionDescription').value = '';
                    document.getElementById('electionForm').removeAttribute('data-election-id');
                    showElectionSection('overview');
                    setTimeout(() => {
                        loadElections();
                    }, 100);
                } else {
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
                                    <img src="${candidate.photo_url ? candidate.photo_url : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(candidate.name) + '&background=004d00&color=FFD700&size=40'}" alt="${candidate.name}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
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

        async function loadCandidates(searchTerm = '', electionId = '') {
            console.log('loadCandidates called with searchTerm:', searchTerm, 'electionId:', electionId);
            const tableBody = document.getElementById('candidatesTableBody');
            const loadingIndicator = document.getElementById('candidatesLoadingIndicator');
            const noDataMessage = document.getElementById('candidatesNoDataMessage');

            loadingIndicator.style.display = 'block';
            noDataMessage.style.display = 'none';
            tableBody.innerHTML = '';

            try {
                let url = '/admin/api/candidates?';
                if (searchTerm) url += `search=${encodeURIComponent(searchTerm)}&`;
                if (electionId) url += `election_id=${electionId}&`;
                url = url.slice(0, -1);

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
                        const photoUrl = candidate.photo_url ? candidate.photo_url : `https://ui-avatars.com/api/?name=${encodeURIComponent(candidate.name)}&background=004d00&color=FFD700&size=40`;

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

        let candidateSearchTimeout;
        document.getElementById('candidateSearch').addEventListener('input', function(e) {
            clearTimeout(candidateSearchTimeout);
            const searchTerm = e.target.value.trim();
            const electionId = document.getElementById('electionFilter').value;

            candidateSearchTimeout = setTimeout(() => {
                loadCandidates(searchTerm, electionId);
            }, 300);
        });

        document.getElementById('electionFilter').addEventListener('change', function(e) {
            const electionId = e.target.value;
            const searchTerm = document.getElementById('candidateSearch').value.trim();
            loadCandidates(searchTerm, electionId);
        });

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
                    const photoUrl = candidate.photo_url ? candidate.photo_url : `https://ui-avatars.com/api/?name=${encodeURIComponent(candidate.name)}&background=004d00&color=FFD700&size=100`;

                    const modal = document.createElement('div');
                    modal.className = 'modal-overlay';
                    modal.innerHTML = `
                        <div class="modal" style="max-width: 500px;">
                            <div class="modal-title">Candidate Details</div>
                            <div class="modal-content">
                                <div style="text-align: center; margin-bottom: 2rem;">
                                    <img src="${photoUrl}" alt="${candidate.name}" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; margin-bottom: 1rem; border: 4px solid var(--cpsu-gold);">
                                    <h3 style="margin: 0; color: var(--cpsu-blue);">${candidate.name}</h3>
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

        function showAddCandidateModal() {
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

            loadElectionsForAddModal();
        }

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

        async function submitAddCandidateForm() {
            const form = document.getElementById('addCandidateForm');
            const formData = new FormData(form);

            const electionSelect = document.getElementById('addElectionId');
            const nameInput = document.getElementById('addCandidateName');
            const electionId = electionSelect.value;
            const name = nameInput.value.trim();

            if (!electionId || !name) {
                showNotification('Please fill in all required fields (Election and Name)', 'error');
                return;
            }

            const submitBtn = document.querySelector('.modal-btn.logout');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Adding...';
            submitBtn.disabled = true;

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
                    if (responseText.includes('<html>') || responseText.includes('<!DOCTYPE')) {
                        throw new Error('Server returned HTML instead of JSON. Check server logs for errors.');
                    } else {
                        throw new Error('Invalid response from server: ' + responseText.substring(0, 100));
                    }
                }

                if (response.ok && data.success) {
                    showNotification('Candidate added successfully', 'success');
                    if (data.csrf_token) {
                        document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.csrf_token);
                    }
                    document.querySelector('.modal-overlay').remove();
                    loadCandidates();
                } else {
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
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }
        }

        function addCandidate(electionId) {
            const name = prompt('Enter candidate name:');
            if (!name) return;

            const description = prompt('Enter candidate description:');
            if (description === null) return;

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

        async function editCandidate(candidateId) {
            try {
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
        // Audit Logs Functions
        function showAuditTab(tabType) {
            // Update tab active state
            document.querySelectorAll('.tabs .tab').forEach(tab => {
                tab.classList.remove('active');
            });
            event.target.classList.add('active');

            // For now, we'll just show a notification
            const tabNames = {
                'all': 'All Logs',
                'admin_action': 'Admin Actions',
                'voter_activity': 'Voter Activity',
                'vote_submission': 'Vote Timestamps',
                'system_change': 'System Changes'
            };

            showNotification(`Showing ${tabNames[tabType] || 'All Logs'}`, 'info');
        }

        function filterAuditLogs() {
            const searchTerm = document.getElementById('auditSearch').value.toLowerCase();
            const dateFrom = document.getElementById('auditDateFrom').value;
            const dateTo = document.getElementById('auditDateTo').value;

            const rows = document.querySelectorAll('#auditLogsTableBody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const rowDateCell = row.querySelector('td:first-child');
                const rowDate = rowDateCell ? rowDateCell.textContent : '';

                let showRow = true;

                if (searchTerm && !text.includes(searchTerm)) {
                    showRow = false;
                }

                if (dateFrom || dateTo) {
                    const rowDateObj = new Date(rowDate);
                    if (dateFrom && rowDateObj < new Date(dateFrom)) {
                        showRow = false;
                    }
                    if (dateTo && rowDateObj > new Date(dateTo)) {
                        showRow = false;
                    }
                }

                row.style.display = showRow ? '' : 'none';
            });

            const visibleCount = document.querySelectorAll('#auditLogsTableBody tr:not([style*="display: none"])').length;
            showNotification(`Found ${visibleCount} matching logs`, 'info');
        }

        function exportAuditLogs() {
            const rows = Array.from(document.querySelectorAll('#auditLogsTableBody tr')).filter(row => row.style.display !== 'none');

            if (rows.length === 0) {
                showNotification('No logs to export', 'error');
                return;
            }

            let csvContent = 'Date/Time,User,Action Type,Category,Description,IP Address\n';

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                if (cells.length >= 6) {
                    const dateTime = cells[0].textContent.trim();
                    const user = cells[1].textContent.trim().replace(/\n/g, ' ');
                    const actionType = cells[2].textContent.trim();
                    const category = cells[3].textContent.trim();
                    const description = cells[4].textContent.trim().replace(/"/g, '""');
                    const ipAddress = cells[5].textContent.trim();

                    csvContent += `"${dateTime}","${user}","${actionType}","${category}","${description}","${ipAddress}"\n`;
                }
            });

            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);

            const timestamp = new Date().toISOString().slice(0, 19).replace(/[:-]/g, '');
            link.setAttribute('href', url);
            link.setAttribute('download', `audit_logs_${timestamp}.csv`);
            link.style.visibility = 'hidden';

            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            showNotification(`Exported ${rows.length} audit logs to CSV`, 'success');
        }

        document.head.appendChild(style);

        // System Monitoring JavaScript
        let monitoringChart;

        // Initialize monitoring chart
        function initMonitoringChart(hourlyData) {
            const ctx = document.getElementById('monitoringChart');
            if (!ctx) return;

            const hours = [];
            const labels = [];
            for (let i = 0; i < 24; i++) {
                hours.push(hourlyData[i] || 0);
                labels.push(i + ':00');
            }

            monitoringChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Votes',
                        data: hours,
                        backgroundColor: 'rgba(0, 77, 0, 0.6)',
                        borderColor: '#004d00',
                        borderWidth: 2,
                        borderRadius: 5
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
                            },
                            title: {
                                display: true,
                                text: 'Number of Votes'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: 'Hour of Day'
                            }
                        }
                    }
                }
            });
        }

        // Fetch real-time monitoring data
        async function fetchMonitoringData() {
            try {
                const response = await fetch('/admin/api/monitoring', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    updateMonitoringUI(data.data);
                }
            } catch (error) {
                console.error('Error fetching monitoring data:', error);
            }
        }

        // Update monitoring UI with new data
        function updateMonitoringUI(data) {
            // Update server status
            const statusIndicator = document.getElementById('serverStatusIndicator');
            const statusText = document.getElementById('serverStatusText');
            if (statusIndicator && statusText) {
                statusIndicator.className = 'status-dot ' + (data.server_status.status === 'online' ? 'online' : 'offline');
                statusText.textContent = data.server_status.label;
            }

            // Update active users
            const activeUsers = document.getElementById('activeUsersCount');
            if (activeUsers) {
                activeUsers.textContent = data.active_users.count;
            }

            // Update votes per minute
            const votesPerMinute = document.getElementById('votesPerMinuteCount');
            if (votesPerMinute) {
                votesPerMinute.textContent = data.votes_per_minute;
            }

            // Update votes today
            const votesToday = document.getElementById('votesTodayCount');
            if (votesToday) {
                votesToday.textContent = data.votes_today.toLocaleString();
            }

            // Update peak time
            const peakTime = document.getElementById('peakTime');
            if (peakTime) {
                peakTime.textContent = data.peak_voting_times.peak_label;
            }

            // Update average votes per hour
            const avgVotes = document.getElementById('avgVotesPerHour');
            if (avgVotes) {
                avgVotes.textContent = data.avg_votes_per_hour;
            }

            // Update chart if it exists
            if (monitoringChart && data.peak_voting_times.hourly) {
                monitoringChart.data.datasets[0].data = data.peak_voting_times.hourly;
                monitoringChart.update();
            }
        }

        // Initialize monitoring on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize chart with initial data from server-side rendering
            const initialHourlyData = @json($monitoringData['peak_voting_times']['hourly'] ?? array_fill(0, 24, 0));
            initMonitoringChart(initialHourlyData);

            // Start polling for real-time updates (every 5 seconds)
            setInterval(fetchMonitoringData, 5000);

            // Load initial report data when reports section is shown
            loadReportData();
        });

        // ==================== REPORTS & ANALYTICS FUNCTIONS ====================

        // Chart instances for Reports
        let turnoutChart, turnoutPieChart, resultsComparisonChart, aiHourlyChart, aiDailyChart, cumulativeChart, heatmapChart;

        // Current report tab
        let currentReportTab = 'turnout';

        // Show report tab
        function showReportTab(tabName) {
            // Update tab active state
            document.querySelectorAll('#reports .tabs .tab').forEach(tab => {
                tab.classList.remove('active');
            });
            event.target.classList.add('active');

            // Hide all report tabs
            document.querySelectorAll('.report-tab').forEach(tab => {
                tab.classList.remove('active');
            });

            // Show selected tab
            const tabMap = {
                'turnout': 'report-turnout',
                'results': 'report-results',
                'ai-patterns': 'report-ai-patterns',
                'time-based': 'report-time-based'
            };

            const tabId = tabMap[tabName];
            if (tabId) {
                document.getElementById(tabId).classList.add('active');
                currentReportTab = tabName;
                loadReportData();
            }
        }

        // Load report data based on current filters
        async function loadReportData() {
            const reportType = document.getElementById('reportType') ? document.getElementById('reportType').value : 'voter-turnout';
            const electionId = document.getElementById('reportElectionFilter') ? document.getElementById('reportElectionFilter').value : '';
            const dateRange = document.getElementById('reportDateRange') ? document.getElementById('reportDateRange').value : '7';

            switch(currentReportTab) {
                case 'turnout':
                    await loadVoterTurnoutReport(electionId, dateRange);
                    break;
                case 'results':
                    await loadElectionResultsReport(electionId);
                    break;
                case 'ai-patterns':
                    await loadAIPatternsReport(dateRange);
                    break;
                case 'time-based':
                    await loadTimeBasedReport(electionId, dateRange);
                    break;
            }
        }

        // Load Voter Turnout Report
        async function loadVoterTurnoutReport(electionId, days) {
            try {
                let url = `/admin/api/reports/voter-turnout?days=${days}`;
                if (electionId) {
                    url += `&election_id=${electionId}`;
                }

                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const result = await response.json();

                if (result.success) {
                    // Update summary stats
                    const summary = result.summary;
                    document.getElementById('totalRegisteredVoters').textContent = summary.total_voters.toLocaleString();
                    document.getElementById('totalVotesCast').textContent = summary.total_votes.toLocaleString();
                    document.getElementById('overallTurnout').textContent = summary.overall_turnout + '%';

                    // Calculate average turnout
                    const avgTurnout = result.data.length > 0
                        ? (result.data.reduce((sum, e) => sum + e.turnout_rate, 0) / result.data.length).toFixed(1)
                        : 0;
                    document.getElementById('avgTurnout').textContent = avgTurnout + '%';

                    // Update table
                    const tableBody = document.getElementById('turnoutTableBody');
                    if (result.data.length > 0) {
                        tableBody.innerHTML = result.data.map(election => `
                            <tr>
                                <td>${election.title}</td>
                                <td>${election.type}</td>
                                <td><span class="status-badge status-${election.status}">${election.status}</span></td>
                                <td>${election.votes_cast.toLocaleString()}</td>
                                <td>${election.turnout_rate}%</td>
                                <td>${new Date(election.start_date).toLocaleDateString()} - ${new Date(election.end_date).toLocaleDateString()}</td>
                            </tr>
                        `).join('');
                    } else {
                        tableBody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 2rem;">No data available</td></tr>';
                    }

                    // Update charts
                    updateTurnoutCharts(result.data);
                }
            } catch (error) {
                console.error('Error loading voter turnout report:', error);
                showNotification('Failed to load voter turnout data', 'error');
            }
        }

        // Update turnout charts
        function updateTurnoutCharts(data) {
            // Bar chart - Turnout by Election
            const ctx1 = document.getElementById('turnoutChart');
            if (ctx1) {
                if (turnoutChart) turnoutChart.destroy();

                turnoutChart = new Chart(ctx1, {
                    type: 'bar',
                    data: {
                        labels: data.map(e => e.title.substring(0, 20)),
                        datasets: [{
                            label: 'Turnout %',
                            data: data.map(e => e.turnout_rate),
                            backgroundColor: 'rgba(0, 77, 0, 0.7)',
                            borderColor: '#004d00',
                            borderWidth: 2,
                            borderRadius: 5
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
                                max: 100,
                                title: {
                                    display: true,
                                    text: 'Turnout %'
                                }
                            }
                        }
                    }
                });
            }

            // Pie chart - Turnout distribution
            const ctx2 = document.getElementById('turnoutPieChart');
            if (ctx2) {
                if (turnoutPieChart) turnoutPieChart.destroy();

                const highTurnout = data.filter(e => e.turnout_rate >= 70).length;
                const medTurnout = data.filter(e => e.turnout_rate >= 40 && e.turnout_rate < 70).length;
                const lowTurnout = data.filter(e => e.turnout_rate < 40).length;

                turnoutPieChart = new Chart(ctx2, {
                    type: 'doughnut',
                    data: {
                        labels: ['High (70%+)', 'Medium (40-70%)', 'Low (<40%)'],
                        datasets: [{
                            data: [highTurnout, medTurnout, lowTurnout],
                            backgroundColor: ['#28a745', '#f39c12', '#dc3545'],
                            borderWidth: 3,
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
                        cutout: '60%'
                    }
                });
            }
        }

        // Load Election Results Report
        async function loadElectionResultsReport(electionId) {
            try {
                let url = '/admin/api/reports/election-results';
                if (electionId) {
                    url += `?election_id=${electionId}`;
                }

                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const result = await response.json();

                if (result.success) {
                    // Update table
                    const tableBody = document.getElementById('resultsTableBody');
                    if (result.data.length > 0) {
                        let html = '';
                        result.data.forEach(election => {
                            election.candidates.forEach(candidate => {
                                const isWinner = candidate.name === election.winner;
                                html += `
                                    <tr>
                                        <td>${election.title}</td>
                                        <td>${candidate.name} ${isWinner ? '<span class="status-badge status-active" style="margin-left: 5px;">Winner</span>' : ''}</td>
                                        <td>${candidate.votes.toLocaleString()}</td>
                                        <td>${candidate.percentage}%</td>
                                        <td><span class="status-badge status-${election.status}">${election.status}</span></td>
                                        <td>${election.winner}</td>
                                    </tr>
                                `;
                            });
                        });
                        tableBody.innerHTML = html;
                    } else {
                        tableBody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 2rem;">No data available</td></tr>';
                    }

                    // Update chart
                    updateResultsChart(result.data);
                }
            } catch (error) {
                console.error('Error loading election results report:', error);
                showNotification('Failed to load election results data', 'error');
            }
        }

        // Update results comparison chart
        function updateResultsChart(data) {
            const ctx = document.getElementById('resultsComparisonChart');
            if (!ctx) return;

            if (resultsComparisonChart) resultsComparisonChart.destroy();

            // Get all candidates from all elections
            const allCandidates = [];
            data.forEach(election => {
                election.candidates.forEach(candidate => {
                    allCandidates.push({
                        name: `${candidate.name} (${election.title.substring(0, 15)})`,
                        votes: candidate.votes
                    });
                });
            });

            // Sort by votes and take top 10
            const topCandidates = allCandidates
                .sort((a, b) => b.votes - a.votes)
                .slice(0, 10);

            resultsComparisonChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: topCandidates.map(c => c.name),
                    datasets: [{
                        label: 'Votes',
                        data: topCandidates.map(c => c.votes),
                        backgroundColor: [
                            'rgba(0, 77, 0, 0.7)',
                            'rgba(111, 66, 193, 0.7)',
                            'rgba(40, 167, 69, 0.7)',
                            'rgba(243, 156, 18, 0.7)',
                            'rgba(52, 152, 219, 0.7)',
                            'rgba(231, 76, 60, 0.7)',
                            'rgba(155, 89, 182, 0.7)',
                            'rgba(26, 188, 156, 0.7)',
                            'rgba(241, 196, 15, 0.7)',
                            'rgba(46, 204, 113, 0.7)'
                        ],
                        borderWidth: 1,
                        borderColor: '#fff',
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Votes'
                            }
                        }
                    }
                }
            });
        }

        // Load AI Patterns Report
        async function loadAIPatternsReport(days) {
            try {
                const url = `/admin/api/reports/ai-patterns?days=${days}`;

                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const result = await response.json();

                if (result.success) {
                    const data = result.data;

                    // Update stats
                    document.getElementById('aiTotalVotes').textContent = data.total_votes.toLocaleString();
                    document.getElementById('aiUniqueVoters').textContent = data.unique_voters.toLocaleString();
                    document.getElementById('aiPeakHour').textContent = data.peak_hour_label;
                    document.getElementById('aiAvgPerHour').textContent = data.avg_votes_per_hour;

                    // Update anomalies table
                    const anomaliesBody = document.getElementById('anomaliesTableBody');
                    if (data.anomalies && data.anomalies.length > 0) {
                        anomaliesBody.innerHTML = data.anomalies.map(anomaly => `
                            <tr>
                                <td><span class="anomaly-badge anomaly-medium">${anomaly.type}</span></td>
                                <td>${anomaly.hour}:00</td>
                                <td>${anomaly.count}</td>
                                <td>${anomaly.threshold}</td>
                                <td>${anomaly.description}</td>
                                <td>
                                    <button class="action-btn btn-view">View</button>
                                    <button class="action-btn btn-dismiss">Dismiss</button>
                                </td>
                            </tr>
                        `).join('');
                    } else {
                        anomaliesBody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 2rem;">No anomalies detected</td></tr>';
                    }

                    // Update charts
                    updateAICharts(data);
                }
            } catch (error) {
                console.error('Error loading AI patterns report:', error);
                showNotification('Failed to load AI patterns data', 'error');
            }
        }

        // Update AI charts
        function updateAICharts(data) {
            // Hourly activity chart
            const ctx1 = document.getElementById('aiHourlyChart');
            if (ctx1) {
                if (aiHourlyChart) aiHourlyChart.destroy();

                const labels = [];
                for (let i = 0; i < 24; i++) {
                    labels.push(i + ':00');
                }

                aiHourlyChart = new Chart(ctx1, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Votes',
                            data: data.votes_by_hour,
                            borderColor: '#004d00',
                            backgroundColor: 'rgba(0, 77, 0, 0.1)',
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
                                title: {
                                    display: true,
                                    text: 'Number of Votes'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Hour of Day'
                                }
                            }
                        }
                    }
                });
            }

            // Daily trends chart
            const ctx2 = document.getElementById('aiDailyChart');
            if (ctx2) {
                if (aiDailyChart) aiDailyChart.destroy();

                const labels = data.votes_by_day.map(d => d.date);
                const values = data.votes_by_day.map(d => d.count);

                aiDailyChart = new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Daily Votes',
                            data: values,
                            backgroundColor: 'rgba(111, 66, 193, 0.7)',
                            borderColor: '#6f42c1',
                            borderWidth: 2,
                            borderRadius: 5
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
                                title: {
                                    display: true,
                                    text: 'Number of Votes'
                                }
                            }
                        }
                    }
                });
            }
        }

        // Load Time-Based Report
        async function loadTimeBasedReport(electionId, days) {
            try {
                let url = `/admin/api/reports/time-based?days=${days}`;
                if (electionId) {
                    url += `&election_id=${electionId}`;
                }

                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const result = await response.json();

                if (result.success) {
                    const data = result.data;

                    // Update stats
                    document.getElementById('timeTotalVotes').textContent = data.total_votes.toLocaleString();
                    document.getElementById('timeDaysAnalyzed').textContent = data.days_analyzed;

                    const changeText = data.comparison.change >= 0
                        ? `+${data.comparison.change}%`
                        : `${data.comparison.change}%`;
                    const changeElement = document.getElementById('timeChangePercent');
                    changeElement.textContent = changeText;
                    changeElement.style.color = data.comparison.change >= 0 ? '#28a745' : '#dc3545';

                    const avgDaily = data.days_analyzed > 0
                        ? Math.round(data.total_votes / data.days_analyzed)
                        : 0;
                    document.getElementById('timeAvgDaily').textContent = avgDaily.toLocaleString();

                    // Update charts
                    updateTimeCharts(data);
                }
            } catch (error) {
                console.error('Error loading time-based report:', error);
                showNotification('Failed to load time-based data', 'error');
            }
        }

        // Update time-based charts
        function updateTimeCharts(data) {
            // Cumulative votes chart
            const ctx1 = document.getElementById('cumulativeChart');
            if (ctx1) {
                if (cumulativeChart) cumulativeChart.destroy();

                const labels = data.cumulative.map(d => d.date);
                const values = data.cumulative.map(d => d.count);

                cumulativeChart = new Chart(ctx1, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Cumulative Votes',
                            data: values,
                            borderColor: '#004d00',
                            backgroundColor: 'rgba(0, 77, 0, 0.2)',
                            fill: true,
                            tension: 0.3,
                            borderWidth: 3
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
                                title: {
                                    display: true,
                                    text: 'Total Votes'
                                }
                            }
                        }
                    }
                });
            }

            // Heatmap chart (hourly activity)
            const ctx2 = document.getElementById('heatmapChart');
            if (ctx2) {
                if (heatmapChart) heatmapChart.destroy();

                // Prepare heatmap data
                const hourlyLabels = [];
                for (let i = 0; i < 24; i++) {
                    hourlyLabels.push(i + ':00');
                }

                // Use the daily_hourly data for heatmap
                const allHourlyData = Object.values(data.daily_hourly || {});
                const avgHourly = [];
                for (let h = 0; h < 24; h++) {
                    let sum = 0, count = 0;
                    allHourlyData.forEach(day => {
                        if (day[h] !== undefined) {
                            sum += day[h];
                            count++;
                        }
                    });
                    avgHourly.push(count > 0 ? Math.round(sum / count) : 0);
                }

                heatmapChart = new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: hourlyLabels,
                        datasets: [{
                            label: 'Avg Votes per Hour',
                            data: avgHourly,
                            backgroundColor: avgHourly.map(v => {
                                const max = Math.max(...avgHourly);
                                const intensity = max > 0 ? v / max : 0;
                                return `rgba(0, 77, 0, ${0.2 + intensity * 0.8})`;
                            }),
                            borderColor: '#004d00',
                            borderWidth: 1,
                            borderRadius: 3
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
                                title: {
                                    display: true,
                                    text: 'Average Votes'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Hour of Day'
                                }
                            }
                        }
                    }
                });
            }
        }

        // Export to CSV
        function exportToCSV() {
            const reportType = document.getElementById('reportType') ? document.getElementById('reportType').value : 'voter-turnout';
            const electionId = document.getElementById('reportElectionFilter') ? document.getElementById('reportElectionFilter').value : '';
            const dateRange = document.getElementById('reportDateRange') ? document.getElementById('reportDateRange').value : '7';

            let url = `/admin/api/reports/export?type=${reportType}&days=${dateRange}`;
            if (electionId) {
                url += `&election_id=${electionId}`;
            }

            // Create a link and trigger download
            const link = document.createElement('a');
            link.href = url;
            link.download = `${reportType}_report_${new Date().toISOString().split('T')[0]}.csv`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            showNotification('CSV export started', 'success');
        }

        // Export to PDF
        function exportToPDF() {
            const reportType = document.getElementById('reportType') ? document.getElementById('reportType').value : 'voter-turnout';
            const electionId = document.getElementById('reportElectionFilter') ? document.getElementById('reportElectionFilter').value : '';
            const dateRange = document.getElementById('reportDateRange') ? document.getElementById('reportDateRange').value : '7';

            let url = `/admin/api/reports/export-pdf?type=${reportType}&days=${dateRange}`;
            if (electionId) {
                url += `&election_id=${electionId}`;
            }

            // Open in new tab for printing/saving
            window.open(url, '_blank');

            showNotification('PDF report opened in new tab. Use browser print to save as PDF.', 'info');
        }
    </script>
</body>
</html>
</html>
