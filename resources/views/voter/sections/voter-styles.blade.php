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
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%); color: #333; overflow-x: hidden; }
        .dashboard-container { display: flex; min-height: 100vh; }
        .sidebar { width: 280px; background: linear-gradient(180deg, var(--cpsu-blue) 0%, #003d00 100%); color: white; padding: 0; display: flex; flex-direction: column; position: sticky; top: 0; height: 100vh; box-shadow: var(--shadow-lg); z-index: 100; }
        .logo { padding: 2rem 1.5rem; font-size: 1.8rem; font-weight: 700; border-bottom: 1px solid rgba(255,255,255,0.15); display: flex; align-items: center; gap: 12px; background: rgba(0,0,0,0.1); }
        .logo-text { display: flex; flex-direction: column; line-height: 1.2; }
        .logo-text .main { font-size: 1.5rem; color: white; }
        .logo-text .sub { font-size: 0.75rem; color: var(--cpsu-gold); font-weight: 500; }
        .nav-menu { list-style: none; flex: 1; padding: 1rem 0; overflow-y: auto; }
        .nav-menu::-webkit-scrollbar { width: 6px; }
        .nav-menu::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); }
        .nav-menu::-webkit-scrollbar-thumb { background: rgba(255,215,0,0.3); border-radius: 3px; }
        .nav-item { padding: 0.9rem 1.5rem; transition: all 0.3s ease; cursor: pointer; display: flex; align-items: center; margin: 0.3rem 0.8rem; border-radius: 12px; font-weight: 500; position: relative; }
        .nav-item:hover { background: rgba(255,215,0,0.15); transform: translateX(5px); }
        .nav-item.active { background: var(--cpsu-gold); color: var(--cpsu-blue); box-shadow: 0 4px 15px rgba(255,215,0,0.3); }
        .nav-item.active::before { content: ''; position: absolute; left: 0; top: 50%; transform: translateY(-50%); width: 4px; height: 60%; background: var(--cpsu-blue); border-radius: 0 4px 4px 0; }
        .nav-icon { margin-right: 12px; width: 20px; text-align: center; font-size: 1.1rem; }
        .logout-section { padding: 1.5rem; border-top: 1px solid rgba(255,255,255,0.15); background: rgba(0,0,0,0.1); }
        .logout-btn { display: flex; align-items: center; justify-content: center; width: 100%; padding: 0.9rem; background: rgba(220,53,69,0.15); color: white; border: 2px solid rgba(220,53,69,0.3); border-radius: 12px; cursor: pointer; font-weight: 600; transition: all 0.3s; text-decoration: none; font-family: inherit; font-size: 1rem; }
        .logout-btn:hover { background: var(--cpsu-red); border-color: var(--cpsu-red); transform: translateY(-2px); box-shadow: 0 5px 15px rgba(220,53,69,0.3); }
        .logout-icon { margin-right: 8px; }
        .main-content { flex: 1; padding: 2.5rem; overflow-y: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem; padding: 1.5rem 2rem; background: white; border-radius: 20px; box-shadow: var(--shadow-sm); }
        .page-title { font-size: 2rem; font-weight: 700; color: var(--cpsu-blue); display: flex; align-items: center; gap: 12px; }
        .page-title::before { content: ''; width: 5px; height: 40px; background: var(--cpsu-gold); border-radius: 3px; }
        .user-info { display: flex; align-items: center; gap: 1.5rem; }
        .user-profile { display: flex; align-items: center; gap: 1rem; padding: 0.7rem 1.2rem; background: var(--cpsu-light); border-radius: 50px; cursor: pointer; position: relative; border: 2px solid transparent; transition: all 0.3s; }
        .user-profile:hover { border-color: var(--cpsu-gold); background: white; }
        .profile-dropdown { position: absolute; top: 110%; right: 0; background: white; border-radius: 15px; box-shadow: var(--shadow-lg); display: none; min-width: 220px; z-index: 1000; overflow: hidden; }
        .profile-dropdown.active { display: block; animation: slideDown 0.3s ease; }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .dropdown-item { padding: 1rem 1.5rem; display: flex; align-items: center; gap: 1rem; cursor: pointer; transition: all 0.3s; text-decoration: none; color: #333; font-weight: 500; }
        .dropdown-item:hover { background: var(--cpsu-light); color: var(--cpsu-blue); }
        .dropdown-item.logout { color: var(--cpsu-red); border-top: 1px solid #eee; }
        .dropdown-item.logout:hover { background: rgba(220,53,69,0.1); }
        .status-badge { padding: 0.4rem 1rem; border-radius: 50px; font-size: 0.85rem; font-weight: 600; display: inline-block; text-transform: uppercase; letter-spacing: 0.3px; }
        .status-verified { background: linear-gradient(135deg, #d4edda, #c3e6cb); color: #155724; }
        .status-pending { background: linear-gradient(135deg, #fff3cd, #ffeaa7); color: #856404; }
        .status-active { background: linear-gradient(135deg, #d4edda, #c3e6cb); color: #155724; }
        .status-closed { background: linear-gradient(135deg, #f8d7da, #f5c6cb); color: #721c24; }
        .dashboard-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-bottom: 2.5rem; }
        .card { background: white; border-radius: 20px; padding: 2rem; box-shadow: var(--shadow-sm); transition: all 0.3s ease; position: relative; overflow: hidden; }
        .card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 5px; background: linear-gradient(90deg, var(--cpsu-blue), var(--cpsu-gold)); }
        .card:hover { transform: translateY(-8px); box-shadow: var(--shadow-lg); }
        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .card-title { font-size: 0.95rem; color: #7f8c8d; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .card-subtitle { font-size: 0.9rem; color: #7f8c8d; margin-bottom: 0.5rem; font-weight: 500; }
        .card-value { font-size: 2.5rem; font-weight: 700; background: linear-gradient(135deg, var(--cpsu-blue), var(--cpsu-purple)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin-bottom: 0.8rem; }
        .countdown-timer { font-size: 1.5rem; font-weight: 700; color: var(--cpsu-red); text-align: center; padding: 1rem; background: var(--cpsu-light); border-radius: 15px; margin: 1rem 0; font-family: 'Courier New', monospace; }
        .countdown-label { font-size: 0.9rem; color: #7f8c8d; text-align: center; margin-bottom: 0.5rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .election-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-bottom: 2.5rem; }
        .election-card { background: white; border-radius: 20px; padding: 2rem; box-shadow: var(--shadow-sm); border: 1px solid #eaeaea; transition: all 0.3s ease; position: relative; overflow: hidden; }
        .election-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, var(--cpsu-blue), var(--cpsu-gold)); }
        .election-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-md); }
        .election-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 2px solid var(--cpsu-light); }
        .election-title { font-size: 1.2rem; font-weight: 600; color: var(--cpsu-blue); }
        .election-time { font-size: 0.9rem; color: #7f8c8d; margin: 0.5rem 0; font-weight: 500; }
        .election-description { color: #666; margin-bottom: 1.5rem; line-height: 1.6; }
        .vote-btn { width: 100%; padding: 0.9rem; background: linear-gradient(135deg, var(--cpsu-green), #229954); color: white; border: none; border-radius: 12px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s; text-transform: uppercase; letter-spacing: 0.5px; }
        .vote-btn:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(39,174,96,0.3); }
        .vote-btn:disabled { background: linear-gradient(135deg, #95a5a6, #7f8c8d); cursor: not-allowed; }
        .vote-btn.voted { background: linear-gradient(135deg, #3498db, #2980b9); }
        .vote-btn.closed { background: linear-gradient(135deg, #e74c3c, #c0392b); }
        .voting-interface { background: white; border-radius: 20px; padding: 2.5rem; box-shadow: var(--shadow-md); max-width: 900px; margin: 0 auto; }
        .candidate-table { width: 100%; border-collapse: collapse; }
        .candidate-table thead { background: var(--cpsu-light); }
        .candidate-table th { text-align: left; padding: 1.2rem 1rem; font-weight: 700; color: var(--cpsu-blue); border-bottom: 3px solid var(--cpsu-gold); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; }
        .candidate-table td { padding: 1.2rem 1rem; border-bottom: 1px solid #f0f0f0; }
        .candidate-table tr { transition: all 0.3s; cursor: pointer; }
        .candidate-table tbody tr:hover { background: var(--cpsu-light); }
        .candidate-table tbody tr.selected { background: linear-gradient(135deg, rgba(255,215,0,0.1), rgba(255,215,0,0.05)); border-left: 4px solid var(--cpsu-gold); }
        .candidate-photo { width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 3px solid var(--cpsu-gold); }
        .candidate-name { font-size: 1.1rem; font-weight: 600; color: var(--cpsu-blue); margin-bottom: 0.3rem; }
        .candidate-description { color: #666; line-height: 1.5; font-size: 0.9rem; }
        .candidate-select { padding: 0.6rem 1.2rem; background: linear-gradient(135deg, var(--cpsu-green), #229954); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s; text-transform: uppercase; font-size: 0.85rem; }
        .candidate-select:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(39,174,96,0.3); }
        .confirmation-code { font-family: 'Courier New', monospace; font-size: 1.2rem; background: var(--cpsu-light); padding: 1.2rem; border-radius: 12px; margin: 1.5rem 0; letter-spacing: 2px; font-weight: 700; color: var(--cpsu-blue); border: 2px dashed var(--cpsu-gold); }
        .profile-section { background: white; border-radius: 20px; padding: 2.5rem; box-shadow: var(--shadow-sm); }
        .profile-header { display: flex; align-items: center; margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 2px solid var(--cpsu-light); }
        .profile-photo { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; margin-right: 2rem; border: 4px solid var(--cpsu-gold); box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .profile-info h3 { font-size: 1.8rem; color: var(--cpsu-blue); margin-bottom: 0.5rem; font-weight: 700; }
        .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-top: 2rem; }
        .info-item { padding: 1.5rem; background: var(--cpsu-light); border-radius: 15px; transition: all 0.3s; border-left: 4px solid var(--cpsu-gold); }
        .info-item:hover { transform: translateY(-3px); box-shadow: var(--shadow-sm); }
        .info-label { font-size: 0.85rem; color: #7f8c8d; margin-bottom: 0.5rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .info-value { font-size: 1.1rem; color: var(--cpsu-blue); font-weight: 600; }
        .table-container { background: white; border-radius: 20px; padding: 2rem; box-shadow: var(--shadow-sm); margin-bottom: 2.5rem; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 600px; }
        th { text-align: left; padding: 1.2rem 1rem; background: var(--cpsu-light); font-weight: 700; color: var(--cpsu-blue); border-bottom: 3px solid var(--cpsu-gold); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; }
        td { padding: 1.2rem 1rem; border-bottom: 1px solid #f0f0f0; }
        tr { transition: all 0.3s; }
        tr:hover { background: var(--cpsu-light); }
        .action-btn { padding: 0.5rem 1.2rem; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 0.85rem; transition: all 0.3s; margin-right: 0.5rem; text-transform: uppercase; letter-spacing: 0.3px; }
        .btn-primary { background: linear-gradient(135deg, #3498db, #2980b9); color: white; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(52,152,219,0.3); }
        .btn-success { background: linear-gradient(135deg, #27ae60, #229954); color: white; }
        .btn-success:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(39,174,96,0.3); }
        .btn-danger { background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; }
        .btn-danger:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(231,76,60,0.3); }
        .btn-secondary { background: linear-gradient(135deg, #95a5a6, #7f8c8d); color: white; }
        .btn-secondary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(149,165,166,0.3); }
        .notification-panel { margin-bottom: 2.5rem; }
        .notification-item { padding: 1.2rem 1.5rem; background: white; border-left: 4px solid #3498db; border-radius: 12px; margin-bottom: 1rem; box-shadow: var(--shadow-sm); display: flex; justify-content: space-between; align-items: center; transition: all 0.3s; }
        .notification-item:hover { transform: translateX(5px); box-shadow: var(--shadow-md); }
        .notification-item.warning { border-left-color: #f39c12; }
        .notification-item.success { border-left-color: #27ae60; }
        .notification-item.danger { border-left-color: #e74c3c; }
        .notification-time { font-size: 0.85rem; color: #95a5a6; font-weight: 500; }
        .help-section { background: white; border-radius: 20px; padding: 2.5rem; box-shadow: var(--shadow-sm); }
        .faq-item { margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 2px solid var(--cpsu-light); }
        .faq-question { font-weight: 600; color: var(--cpsu-blue); margin-bottom: 0.5rem; cursor: pointer; font-size: 1.05rem; transition: all 0.3s; }
        .faq-question:hover { color: var(--cpsu-gold); }
        .faq-answer { color: #666; line-height: 1.8; }
        .security-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-top: 2rem; }
        .security-item { padding: 1.5rem; background: var(--cpsu-light); border-radius: 15px; border-left: 4px solid var(--cpsu-gold); transition: all 0.3s; }
        .security-item:hover { transform: translateY(-3px); box-shadow: var(--shadow-sm); }
        .toggle-switch { position: relative; display: inline-block; width: 60px; height: 30px; }
        .toggle-switch input { opacity: 0; width: 0; height: 0; }
        .toggle-slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 30px; }
        .toggle-slider:before { position: absolute; content: ""; height: 22px; width: 22px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.2); }
        input:checked + .toggle-slider { background: linear-gradient(135deg, #27ae60, #229954); }
        input:checked + .toggle-slider:before { transform: translateX(30px); }
        .dashboard-section { display: none; }
        .dashboard-section.active-section { display: block; }
        .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(5px); display: none; justify-content: center; align-items: center; z-index: 2000; }
        .modal-overlay.active { display: flex; }
        .modal { background: white; border-radius: 25px; padding: 2.5rem; max-width: 550px; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: var(--shadow-lg); animation: modalSlideIn 0.3s ease; }
        @keyframes modalSlideIn { from { opacity: 0; transform: translateY(-30px); } to { opacity: 1; transform: translateY(0); } }
        .modal-title { font-size: 1.8rem; font-weight: 700; margin-bottom: 1.5rem; color: var(--cpsu-blue); }
        .modal-actions { display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem; }
        @media (max-width: 992px) { .sidebar { width: 220px; } .main-content { padding: 2rem; } .page-title { font-size: 1.6rem; } }
        @media (max-width: 768px) { .dashboard-container { flex-direction: column; } .sidebar { width: 100%; position: sticky; top: 0; height: auto; } .nav-menu { display: flex; overflow-x: auto; padding-bottom: 0.5rem; } .nav-item { white-space: nowrap; padding: 1rem; } .logout-section { display: none; } .profile-header { flex-direction: column; text-align: center; } .profile-photo { margin-right: 0; margin-bottom: 1rem; } }
        @media (max-width: 480px) { .header { flex-direction: column; align-items: flex-start; gap: 1rem; } .user-info { width: 100%; justify-content: space-between; } .page-title { font-size: 1.5rem; } .dashboard-grid, .election-grid { grid-template-columns: 1fr; } .main-content { padding: 1.5rem; } }
</style>
