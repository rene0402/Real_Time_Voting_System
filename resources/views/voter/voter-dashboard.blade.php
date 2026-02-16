<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Voter Dashboard - Secure Voting System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --cpsu-blue: #004d00;
            --cpsu-gold: #FFD700;
            --cpsu-green: #28a745;
            --cpsu-red: #dc3545;
            --cpsu-purple: #6f42c1;
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

        /* Sidebar - Redesigned */
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

        /* Logout section in sidebar */
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

        /* Main Content - Redesigned */
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

        /* Status Badges - Redesigned */
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

        .status-restricted {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
        }

        .status-voted {
            background: linear-gradient(135deg, #d1ecf1, #bee5eb);
            color: #0c5460;
        }

        .status-not-voted {
            background: linear-gradient(135deg, #e2e3e5, #d6d8db);
            color: #383d41;
        }

        .status-active {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
        }

        .status-closed {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
        }

        /* Dashboard Cards - Redesigned */
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

        .card-subtitle {
            font-size: 0.9rem;
            color: #7f8c8d;
            margin-bottom: 0.5rem;
            font-weight: 500;
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

        /* Countdown Timer */
        .countdown-timer {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--cpsu-red);
            text-align: center;
            padding: 1rem;
            background: var(--cpsu-light);
            border-radius: 15px;
            margin: 1rem 0;
            font-family: 'Courier New', monospace;
        }

        .countdown-label {
            font-size: 0.9rem;
            color: #7f8c8d;
            text-align: center;
            margin-bottom: 0.5rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Election Cards */
        .election-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2.5rem;
        }

        .election-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid #eaeaea;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .election-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--cpsu-blue), var(--cpsu-gold));
        }

        .election-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .election-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--cpsu-light);
        }

        .election-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--cpsu-blue);
        }

        .election-time {
            font-size: 0.9rem;
            color: #7f8c8d;
            margin: 0.5rem 0;
            font-weight: 500;
        }

        .election-description {
            color: #666;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .vote-btn {
            width: 100%;
            padding: 0.9rem;
            background: linear-gradient(135deg, var(--cpsu-green), #229954);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .vote-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);
        }

        .vote-btn:disabled {
            background: linear-gradient(135deg, #95a5a6, #7f8c8d);
            cursor: not-allowed;
        }

        .vote-btn.voted {
            background: linear-gradient(135deg, #3498db, #2980b9);
        }

        .vote-btn.closed {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
        }

        /* Voting Interface */
        .voting-interface {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: var(--shadow-md);
            max-width: 900px;
            margin: 0 auto;
        }

        .candidate-list {
            margin: 2rem 0;
        }

        .candidate-table {
            width: 100%;
            border-collapse: collapse;
        }

        .candidate-table thead {
            background: var(--cpsu-light);
        }

        .candidate-table th {
            text-align: left;
            padding: 1.2rem 1rem;
            font-weight: 700;
            color: var(--cpsu-blue);
            border-bottom: 3px solid var(--cpsu-gold);
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .candidate-table td {
            padding: 1.2rem 1rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .candidate-table tr {
            transition: all 0.3s;
            cursor: pointer;
        }

        .candidate-table tbody tr:hover {
            background: var(--cpsu-light);
            transform: scale(1.01);
        }

        .candidate-table tbody tr.selected {
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.1), rgba(255, 215, 0, 0.05));
            border-left: 4px solid var(--cpsu-gold);
        }

        .candidate-photo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--cpsu-gold);
        }

        .candidate-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--cpsu-blue);
            margin-bottom: 0.3rem;
        }

        .candidate-description {
            color: #666;
            line-height: 1.5;
            font-size: 0.9rem;
        }

        .candidate-select {
            padding: 0.6rem 1.2rem;
            background: linear-gradient(135deg, var(--cpsu-green), #229954);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.3px;
        }

        .candidate-select:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);
        }

        /* Vote Confirmation */
        .confirmation-card {
            text-align: center;
            padding: 3rem 2rem;
            background: white;
            border-radius: 20px;
            box-shadow: var(--shadow-md);
            max-width: 600px;
            margin: 2rem auto;
        }

        .confirmation-icon {
            font-size: 4rem;
            color: var(--cpsu-green);
            margin-bottom: 1.5rem;
        }

        .confirmation-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--cpsu-blue);
            margin-bottom: 1rem;
        }

        .confirmation-code {
            font-family: 'Courier New', monospace;
            font-size: 1.2rem;
            background: var(--cpsu-light);
            padding: 1.2rem;
            border-radius: 12px;
            margin: 1.5rem 0;
            letter-spacing: 2px;
            font-weight: 700;
            color: var(--cpsu-blue);
            border: 2px dashed var(--cpsu-gold);
        }

        /* Profile Section */
        .profile-section {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: var(--shadow-sm);
        }

        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 2px solid var(--cpsu-light);
        }

        .profile-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 2rem;
            border: 4px solid var(--cpsu-gold);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .profile-info h3 {
            font-size: 1.8rem;
            color: var(--cpsu-blue);
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .info-item {
            padding: 1.5rem;
            background: var(--cpsu-light);
            border-radius: 15px;
            transition: all 0.3s;
            border-left: 4px solid var(--cpsu-gold);
        }

        .info-item:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-sm);
        }

        .info-label {
            font-size: 0.85rem;
            color: #7f8c8d;
            margin-bottom: 0.5rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 1.1rem;
            color: var(--cpsu-blue);
            font-weight: 600;
        }

        /* Table Container */
        .table-container {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--shadow-sm);
            margin-bottom: 2.5rem;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
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

        /* Action Buttons - Redesigned */
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

        .btn-primary {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, #27ae60, #229954);
            color: white;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);
        }

        .btn-danger {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #95a5a6, #7f8c8d);
            color: white;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(149, 165, 166, 0.3);
        }

        /* Notifications */
        .notification-panel {
            margin-bottom: 2.5rem;
        }

        .notification-item {
            padding: 1.2rem 1.5rem;
            background: white;
            border-left: 4px solid #3498db;
            border-radius: 12px;
            margin-bottom: 1rem;
            box-shadow: var(--shadow-sm);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s;
        }

        .notification-item:hover {
            transform: translateX(5px);
            box-shadow: var(--shadow-md);
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
            font-weight: 500;
        }

        /* Help Section */
        .help-section {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: var(--shadow-sm);
        }

        .faq-item {
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid var(--cpsu-light);
        }

        .faq-question {
            font-weight: 600;
            color: var(--cpsu-blue);
            margin-bottom: 0.5rem;
            cursor: pointer;
            font-size: 1.05rem;
            transition: all 0.3s;
        }

        .faq-question:hover {
            color: var(--cpsu-gold);
        }

        .faq-answer {
            color: #666;
            line-height: 1.8;
        }

        /* Security Settings */
        .security-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .security-item {
            padding: 1.5rem;
            background: var(--cpsu-light);
            border-radius: 15px;
            border: 1px solid #eaeaea;
            transition: all 0.3s;
            border-left: 4px solid var(--cpsu-gold);
        }

        .security-item:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-sm);
        }

        /* Toggle Switch */
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 30px;
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
            border-radius: 30px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        input:checked + .toggle-slider {
            background: linear-gradient(135deg, #27ae60, #229954);
        }

        input:checked + .toggle-slider:before {
            transform: translateX(30px);
        }

        /* Dashboard Sections */
        .dashboard-section {
            display: none;
        }

        .dashboard-section.active-section {
            display: block;
        }

        /* Modal - Redesigned */
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

        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                width: 220px;
            }

            .main-content {
                padding: 2rem;
            }

            .page-title {
                font-size: 1.6rem;
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
                padding: 1rem;
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

            .main-content {
                padding: 1.5rem;
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
            <div class="selected-candidate" id="selectedCandidateName" style="font-weight: 600; margin: 1rem 0; padding: 1.2rem; background: var(--cpsu-light); border-radius: 12px; color: var(--cpsu-blue);"></div>
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
                <div style="font-size: 4rem; color: var(--cpsu-green); margin-bottom: 1.5rem;">
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
                <div class="logo-text">
                    <span class="main">Secure<span style="color: var(--cpsu-gold);">Vote</span></span>
                    <span class="sub">Voter Dashboard</span>
                </div>
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
                            <i class="fas fa-user-check" style="font-size: 2rem; color: var(--cpsu-green);"></i>
                        </div>
                        <div class="card-value" id="verificationStatus">Verified</div>
                        <div class="card-subtitle">AI Verification Score: 98%</div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Active Elections</div>
                            <i class="fas fa-vote-yea" style="font-size: 2rem; color: #3498db;"></i>
                        </div>
                        <div class="card-value" id="activeElectionsCount">2</div>
                        <div class="card-subtitle">Available to vote in</div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Voting Status</div>
                            <i class="fas fa-check-circle" style="font-size: 2rem; color: var(--cpsu-green);"></i>
                        </div>
                        <div class="card-value" id="votingStatus">Voted</div>
                        <div class="card-subtitle">In 1 of 2 elections</div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Next Election</div>
                            <i class="fas fa-clock" style="font-size: 2rem; color: var(--cpsu-red);"></i>
                        </div>
                        <div class="countdown-label">Time Remaining:</div>
                        <div class="countdown-timer" id="electionCountdown">23:45:12</div>
                    </div>
                </div>

                <!-- Active Elections Panel -->
                <div class="card" style="margin-top: 2rem;">
                    <div class="card-header">
                        <div class="card-title" style="font-size: 1.2rem; text-transform: none;">Your Active Elections</div>
                    </div>
                    <div class="election-grid" id="activeElectionsList">
                        @forelse($electionsWithStatus ?? [] as $election)
                            <div class="election-card">
                                <div class="election-header">
                                    <div class="election-title">{{ $election->title }}</div>
                                    <span class="status-badge status-{{ $election->status == 'active' ? 'active' : 'closed' }}">
                                        {{ ucfirst($election->status) }}
                                    </span>
                                </div>
                                <div class="election-time">
                                    <i class="fas fa-clock"></i>
                                    @if($election->status == 'active')
                                        Ends: {{ $election->end_date->format('M d, Y H:i') }}
                                    @else
                                        {{ $election->time_remaining }}
                                    @endif
                                </div>
                                <div class="election-description">
                                    {{ $election->description ?? 'Vote for your preferred candidates in this election.' }}
                                </div>
                                @if($election->has_voted)
                                    <button class="vote-btn voted" disabled>
                                        <i class="fas fa-check"></i> Already Voted
                                    </button>
                                @elseif($election->status == 'active')
                                    <button class="vote-btn" onclick="startVoting({{ $election->id }}, '{{ $election->title }}')">
                                        Vote Now
                                    </button>
                                @else
                                    <button class="vote-btn closed" disabled>
                                        Voting Closed
                                    </button>
                                @endif
                            </div>
                        @empty
                            <div class="election-card">
                                <div class="election-description" style="text-align: center; padding: 2rem;">
                                    <i class="fas fa-info-circle" style="font-size: 2rem; color: #6c757d; margin-bottom: 1rem;"></i>
                                    <p>No active elections available at the moment.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Profile & Verification Section -->
            <div id="profile" class="dashboard-section">
                <div class="profile-section">
                    <div class="profile-header">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Voter') }}&background=004d00&color=FFD700&size=120"
                             alt="Profile" class="profile-photo">
                        <div class="profile-info">
                            <h3>{{ Auth::user()->name ?? 'Voter User' }}</h3>
                            <div class="status-badge status-verified">Verified Voter</div>
                            <p style="color: #666; margin-top: 0.8rem;">Registered Voter ID: V-{{ str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT) }}</p>
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
                        <div class="card-title" style="font-size: 1.2rem; text-transform: none;">Elections Available for Voting</div>
                        <div class="card-subtitle">Select an election to cast your vote</div>
                    </div>
                    <div class="election-grid">
                        @forelse($electionsWithStatus ?? [] as $election)
                            <div class="election-card">
                                <div class="election-header">
                                    <div class="election-title">{{ $election->title }}</div>
                                    <span class="status-badge status-{{ $election->status == 'active' ? 'active' : 'closed' }}">
                                        {{ ucfirst($election->status) }}
                                    </span>
                                </div>
                                <div class="election-time">
                                    <i class="fas fa-clock"></i>
                                    @if($election->status == 'active')
                                        Ends: {{ $election->end_date->format('M d, Y H:i') }}
                                    @else
                                        {{ $election->time_remaining }}
                                    @endif
                                </div>
                                <div class="election-description">
                                    {{ $election->description ?? 'Vote for your preferred candidates in this election.' }}
                                </div>
                                @if($election->has_voted)
                                    <button class="vote-btn voted" disabled>
                                        <i class="fas fa-check"></i> Already Voted
                                    </button>
                                @elseif($election->status == 'active')
                                    <button class="vote-btn" onclick="startVoting({{ $election->id }}, '{{ $election->title }}')">
                                        Vote Now
                                    </button>
                                @else
                                    <button class="vote-btn closed" disabled>
                                        Voting Closed
                                    </button>
                                @endif
                            </div>
                        @empty
                            <div class="election-card">
                                <div class="election-description" style="text-align: center; padding: 2rem;">
                                    <i class="fas fa-info-circle" style="font-size: 2rem; color: #6c757d; margin-bottom: 1rem;"></i>
                                    <p>No active elections available at the moment.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Voting Interface -->
            <div id="voting" class="dashboard-section">
                <div class="voting-interface">
                    <h2 style="color: var(--cpsu-blue); margin-bottom: 1rem; font-weight: 700;" id="votingElectionTitle">Presidential Election 2024</h2>
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
                    <h3 style="color: var(--cpsu-blue); margin-bottom: 1.5rem; font-weight: 700;">Voting History</h3>
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
                                <td><code style="background: var(--cpsu-light); padding: 0.3rem 0.6rem; border-radius: 6px; font-weight: 600;">VREF-{{ strtoupper(Str::random(8)) }}</code></td>
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
                                <td><code style="background: var(--cpsu-light); padding: 0.3rem 0.6rem; border-radius: 6px; font-weight: 600;">VREF-{{ strtoupper(Str::random(8)) }}</code></td>
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
                            <i class="fas fa-chart-bar" style="font-size: 2rem; color: #3498db;"></i>
                        </div>
                        <div class="card-value" id="totalVotesCast">1,247</div>
                        <div class="card-subtitle">Across all active elections</div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Turnout Percentage</div>
                            <i class="fas fa-percentage" style="font-size: 2rem; color: var(--cpsu-green);"></i>
                        </div>
                        <div class="card-value" id="turnoutPercentage">62.3%</div>
                        <div class="card-subtitle">Of registered voters</div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Live Progress</div>
                            <i class="fas fa-tachometer-alt" style="font-size: 2rem; color: var(--cpsu-red);"></i>
                        </div>
                        <div class="card-value" id="votingProgress">45%</div>
                        <div style="margin-top: 1rem; height: 10px; background: #ecf0f1; border-radius: 10px; overflow: hidden;">
                            <div style="width: 45%; height: 100%; background: linear-gradient(90deg, var(--cpsu-blue), var(--cpsu-purple)); border-radius: 10px; box-shadow: 0 0 10px rgba(111, 66, 193, 0.3);"></div>
                        </div>
                    </div>
                </div>

                <div class="card" style="margin-top: 2rem;">
                    <div class="card-header">
                        <div class="card-title" style="font-size: 1.2rem; text-transform: none;">Live Election Updates</div>
                    </div>
                    <div class="table-container" style="padding: 0; box-shadow: none;">
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
                        <div class="card-title" style="font-size: 1.2rem; text-transform: none;">Notifications & Alerts</div>
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
                    <h3 style="color: var(--cpsu-blue); margin-bottom: 2rem; font-weight: 700;">Help & Support Center</h3>

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

                    <div style="margin-top: 3rem; padding-top: 2rem; border-top: 2px solid var(--cpsu-light);">
                        <h4 style="color: var(--cpsu-blue); margin-bottom: 1.5rem; font-weight: 700;">Contact Support</h4>
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
                    <h3 style="color: var(--cpsu-blue); margin-bottom: 2rem; font-weight: 700;">Account Security Settings</h3>

                    <div class="security-grid">
                        <div class="security-item">
                            <h4 style="color: var(--cpsu-blue); margin-bottom: 1rem; font-weight: 600;">Two-Factor Authentication</h4>
                            <p style="color: #666; margin-bottom: 1rem;">Add an extra layer of security to your account</p>
                            <label class="toggle-switch">
                                <input type="checkbox" id="twoFactorToggle" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="security-item">
                            <h4 style="color: var(--cpsu-blue); margin-bottom: 1rem; font-weight: 600;">Change Password</h4>
                            <p style="color: #666; margin-bottom: 1rem;">Update your account password</p>
                            <button class="action-btn btn-primary" onclick="showPasswordModal()">
                                <i class="fas fa-key"></i> Change Password
                            </button>
                        </div>

                        <div class="security-item">
                            <h4 style="color: var(--cpsu-blue); margin-bottom: 1rem; font-weight: 600;">Login History</h4>
                            <p style="color: #666; margin-bottom: 1rem;">View recent account activity</p>
                            <button class="action-btn btn-secondary" onclick="showLoginHistory()">
                                <i class="fas fa-history"></i> View History
                            </button>
                        </div>

                        <div class="security-item">
                            <h4 style="color: var(--cpsu-blue); margin-bottom: 1rem; font-weight: 600;">Logout All Sessions</h4>
                            <p style="color: #666; margin-bottom: 1rem;">Sign out from all devices</p>
                            <button class="action-btn btn-danger" onclick="logoutAllSessions()">
                                <i class="fas fa-sign-out-alt"></i> Logout Everywhere
                            </button>
                        </div>
                    </div>

                    <div class="table-container" style="margin-top: 2rem;">
                        <h4 style="color: var(--cpsu-blue); margin-bottom: 1.5rem; font-weight: 700;">Recent Login Activity</h4>
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
        function startVoting(electionId, electionTitle) {
            currentElection = electionId;
            document.getElementById('votingElectionTitle').textContent = electionTitle || 'Election Voting';

            // Load candidates based on election ID
            loadCandidates(electionId);

            // Show voting section
            showSection('voting');
            document.getElementById('votingNavItem').style.display = 'block';
        }

        // Load candidates
        async function loadCandidates(electionId) {
            const candidateList = document.getElementById('candidateList');

            try {
                const response = await fetch(`/voter/candidates/${electionId}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    let html = `
                    <table class="candidate-table">
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                data.data.forEach(candidate => {
                    html += `
                        <tr onclick="selectCandidate(${candidate.id})" id="candidate-${candidate.id}">
                            <td>
                                <img src="${candidate.photo_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(candidate.name) + '&background=004d00&color=FFD700&size=60'}" alt="${candidate.name}" class="candidate-photo">
                            </td>
                            <td>
                                <div class="candidate-name">${candidate.name}</div>
                            </td>
                            <td>
                                <div class="candidate-description">${candidate.description || 'No description available.'}</div>
                            </td>
                            <td>
                                <button class="candidate-select" onclick="selectCandidate(${candidate.id}); event.stopPropagation();">
                                    Select
                                </button>
                            </td>
                        </tr>
                    `;
                });

                html += `
                        </tbody>
                    </table>
                `;

                    candidateList.innerHTML = html;
                    selectedCandidate = null;
                    document.getElementById('reviewVoteBtn').style.display = 'none';

                    // Reset any previously selected state
                    document.querySelectorAll('.candidate-table tr').forEach(row => {
                        row.classList.remove('selected');
                    });
                    document.querySelectorAll('.candidate-select').forEach(btn => {
                        btn.classList.remove('selected');
                        btn.textContent = 'Select';
                    });
                } else {
                    candidateList.innerHTML = '<p style="text-align: center; color: var(--cpsu-red);">' + (data.error || 'Error loading candidates. Please try again.') + '</p>';
                }

                // Update table responsiveness
                const table = candidateList.querySelector('.candidate-table');
                if (table) {
                    table.style.width = '100%';
                }

            } catch (error) {
                console.error('Error loading candidates:', error);
                candidateList.innerHTML = '<p style="text-align: center; color: var(--cpsu-red);">Error loading candidates. Please try again.</p>';
            }
        }

        // Select candidate
        function selectCandidate(candidateId) {
            // Remove selected class from all candidates
            document.querySelectorAll('.candidate-table tbody tr').forEach(row => {
                row.classList.remove('selected');
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
        async function submitVote() {
            if (!selectedCandidate) {
                alert('Please select a candidate first.');
                return;
            }

            try {
                const response = await fetch(`/voter/vote/${currentElection}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        choices: [selectedCandidate.id] // Send array of candidate IDs
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    // Close confirmation modal
                    closeVoteConfirmation();

                    // Update reference code in success modal
                    document.getElementById('voteReferenceCode').textContent = data.reference_code;

                    // Show success modal
                    document.getElementById('voteSuccessModal').classList.add('active');

                    // Update voting status
                    document.getElementById('votingStatus').textContent = 'Voted';

                    // Update active elections count
                    const count = parseInt(document.getElementById('activeElectionsCount').textContent);
                    document.getElementById('activeElectionsCount').textContent = count - 1;

                    // Refresh the page after a delay to show updated status
                    setTimeout(() => {
                        location.reload();
                    }, 3000);

                } else {
                    alert(data.error || 'Error submitting vote. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Network error. Please check your connection and try again.');
            }
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

        // Initialize the dashboard
        document.addEventListener('DOMContentLoaded', function() {
            updateCountdown();

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
