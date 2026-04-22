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
