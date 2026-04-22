<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Account Management - Real-Time Voting System</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @include('admin.sections.dashboard-styles')
</head>
<body>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>

    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <div class="logo-text">
                    <span class="main">Real-Time <span style="color: var(--cpsu-gold);">Voting</span></span>
                    <span class="sub">Account Management</span>
                </div>
            </div>
            <ul class="nav-menu">
                <li class="nav-item active" onclick="showSection('create-account')">
                    <i class="nav-icon fas fa-user-plus"></i>Create Account
                </li>
                <li class="nav-item" onclick="showSection('all-accounts')">
                    <i class="nav-icon fas fa-users"></i>All Accounts
                </li>
                <li class="nav-item" onclick="showSection('admins')">
                    <i class="nav-icon fas fa-user-shield"></i>Admin Accounts
                </li>
                <li class="nav-item" onclick="showSection('voters')">
                    <i class="nav-icon fas fa-user-check"></i>Voter Accounts
                </li>
            </ul>
            <div style="padding: 1rem 1.5rem; border-top: 1px solid rgba(255,255,255,0.15);">
                <a href="{{ route('admin.dashboard') }}" class="logout-btn" style="text-decoration: none; margin-bottom: 0.8rem; display: flex; align-items: center; justify-content: center; background: rgba(255,215,0,0.15); border-color: rgba(255,215,0,0.3);">
                    <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Back to Dashboard
                </a>
            </div>
            <div class="logout-section">
                <button class="logout-btn" onclick="document.getElementById('logout-form').submit()">
                    <i class="logout-icon fas fa-sign-out-alt"></i> Log Out
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <div class="page-title" id="pageTitle">Create Account</div>
                <div class="user-info">
                    <div class="user-profile" style="cursor: default;">
                        <i class="fas fa-user-circle fa-lg"></i>
                        <div class="profile-name">{{ Auth::user()->name }}</div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div style="background: linear-gradient(135deg,#d4edda,#c3e6cb); border-left: 5px solid #28a745; padding: 1.2rem 1.5rem; border-radius: 12px; margin-bottom: 1.5rem; color: #155724; font-weight: 600;">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="background: linear-gradient(135deg,#f8d7da,#f5c6cb); border-left: 5px solid #dc3545; padding: 1.2rem 1.5rem; border-radius: 12px; margin-bottom: 1.5rem; color: #721c24; font-weight: 600;">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            <!-- Create Account Section -->
            <div id="create-account" class="dashboard-section active-section">
                <div style="background: white; border-radius: 20px; padding: 2.5rem; box-shadow: 0 2px 10px rgba(0,0,0,0.08); max-width: 600px;">
                    <h3 style="color: var(--cpsu-blue); margin-bottom: 2rem; font-weight: 700; font-size: 1.3rem;">
                        <i class="fas fa-user-plus" style="margin-right: 0.5rem;"></i>Create New Account
                    </h3>
                    <form method="POST" action="{{ route('admin.accounts.store') }}">
                        @csrf
                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; font-weight: 600; color: var(--cpsu-blue); margin-bottom: 0.5rem; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Full Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                style="width: 100%; padding: 0.9rem 1.2rem; border: 2px solid #ddd; border-radius: 12px; font-size: 0.95rem; font-family: inherit; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='var(--cpsu-gold)'" onblur="this.style.borderColor='#ddd'"
                                placeholder="Enter full name">
                            @error('name') <p style="color: #dc3545; font-size: 0.85rem; margin-top: 0.4rem;">{{ $message }}</p> @enderror
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; font-weight: 600; color: var(--cpsu-blue); margin-bottom: 0.5rem; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                style="width: 100%; padding: 0.9rem 1.2rem; border: 2px solid #ddd; border-radius: 12px; font-size: 0.95rem; font-family: inherit; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='var(--cpsu-gold)'" onblur="this.style.borderColor='#ddd'"
                                placeholder="Enter email address">
                            @error('email') <p style="color: #dc3545; font-size: 0.85rem; margin-top: 0.4rem;">{{ $message }}</p> @enderror
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; font-weight: 600; color: var(--cpsu-blue); margin-bottom: 0.5rem; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Account Type</label>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                <label style="display: flex; align-items: center; gap: 0.8rem; padding: 1rem; border: 2px solid #ddd; border-radius: 12px; cursor: pointer; transition: all 0.3s;" id="voterLabel">
                                    <input type="radio" name="user_type" value="voter" {{ old('user_type', 'voter') === 'voter' ? 'checked' : '' }} onchange="highlightType()">
                                    <div>
                                        <i class="fas fa-user-check" style="color: var(--cpsu-blue); font-size: 1.2rem;"></i>
                                        <span style="font-weight: 600; margin-left: 0.4rem;">Voter</span>
                                    </div>
                                </label>
                                <label style="display: flex; align-items: center; gap: 0.8rem; padding: 1rem; border: 2px solid #ddd; border-radius: 12px; cursor: pointer; transition: all 0.3s;" id="adminLabel">
                                    <input type="radio" name="user_type" value="admin" {{ old('user_type') === 'admin' ? 'checked' : '' }} onchange="highlightType()">
                                    <div>
                                        <i class="fas fa-user-shield" style="color: var(--cpsu-blue); font-size: 1.2rem;"></i>
                                        <span style="font-weight: 600; margin-left: 0.4rem;">Admin</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; font-weight: 600; color: var(--cpsu-blue); margin-bottom: 0.5rem; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Password</label>
                            <input type="password" name="password" required
                                style="width: 100%; padding: 0.9rem 1.2rem; border: 2px solid #ddd; border-radius: 12px; font-size: 0.95rem; font-family: inherit; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='var(--cpsu-gold)'" onblur="this.style.borderColor='#ddd'"
                                placeholder="Minimum 8 characters">
                            @error('password') <p style="color: #dc3545; font-size: 0.85rem; margin-top: 0.4rem;">{{ $message }}</p> @enderror
                        </div>

                        <div style="margin-bottom: 2rem;">
                            <label style="display: block; font-weight: 600; color: var(--cpsu-blue); margin-bottom: 0.5rem; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Confirm Password</label>
                            <input type="password" name="password_confirmation" required
                                style="width: 100%; padding: 0.9rem 1.2rem; border: 2px solid #ddd; border-radius: 12px; font-size: 0.95rem; font-family: inherit; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='var(--cpsu-gold)'" onblur="this.style.borderColor='#ddd'"
                                placeholder="Re-enter password">
                        </div>

                        <button type="submit" style="width: 100%; padding: 1rem; background: linear-gradient(135deg, var(--cpsu-blue), #003d00); color: white; border: none; border-radius: 12px; font-size: 1rem; font-weight: 600; cursor: pointer; font-family: inherit; transition: all 0.3s; text-transform: uppercase; letter-spacing: 0.5px;"
                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 5px 15px rgba(0,77,0,0.3)'"
                            onmouseout="this.style.transform=''; this.style.boxShadow=''">
                            <i class="fas fa-user-plus" style="margin-right: 0.5rem;"></i> Create Account
                        </button>
                    </form>
                </div>
            </div>

            <!-- All Accounts Section -->
            <div id="all-accounts" class="dashboard-section">
                <div style="background: white; border-radius: 20px; padding: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.08); overflow-x: auto;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                        <h3 style="color: var(--cpsu-blue); font-weight: 700;">All Accounts</h3>
                        <input type="text" id="searchAll" oninput="filterTable('allTable', this.value)" placeholder="Search..."
                            style="padding: 0.7rem 1.2rem; border: 2px solid #ddd; border-radius: 50px; font-size: 0.9rem; font-family: inherit; outline: none;">
                    </div>
                    <table id="allTable" style="width: 100%; border-collapse: collapse; min-width: 600px;">
                        <thead>
                            <tr>
                                <th style="text-align:left; padding: 1rem; background: #f8f9fa; color: var(--cpsu-blue); border-bottom: 3px solid var(--cpsu-gold); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">Name</th>
                                <th style="text-align:left; padding: 1rem; background: #f8f9fa; color: var(--cpsu-blue); border-bottom: 3px solid var(--cpsu-gold); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">Email</th>
                                <th style="text-align:left; padding: 1rem; background: #f8f9fa; color: var(--cpsu-blue); border-bottom: 3px solid var(--cpsu-gold); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">Type</th>
                                <th style="text-align:left; padding: 1rem; background: #f8f9fa; color: var(--cpsu-blue); border-bottom: 3px solid var(--cpsu-gold); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">Created</th>
                                <th style="text-align:left; padding: 1rem; background: #f8f9fa; color: var(--cpsu-blue); border-bottom: 3px solid var(--cpsu-gold); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($accounts as $account)
                            <tr style="border-bottom: 1px solid #f0f0f0; transition: background 0.2s;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background=''">
                                <td style="padding: 1rem;">{{ $account->name }}</td>
                                <td style="padding: 1rem;">{{ $account->email }}</td>
                                <td style="padding: 1rem;">
                                    <span style="padding: 0.3rem 0.8rem; border-radius: 50px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase;
                                        background: {{ $account->user_type === 'admin' ? 'linear-gradient(135deg,#3498db,#2980b9)' : 'linear-gradient(135deg,#27ae60,#229954)' }};
                                        color: white;">
                                        {{ $account->user_type }}
                                    </span>
                                </td>
                                <td style="padding: 1rem; color: #666; font-size: 0.9rem;">{{ $account->created_at->format('M d, Y') }}</td>
                                <td style="padding: 1rem;">
                                    @if($account->id !== Auth::id())
                                    <form method="POST" action="{{ route('admin.accounts.destroy', $account->id) }}" onsubmit="return confirm('Delete {{ $account->name }}?')" style="display: inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="padding: 0.4rem 0.9rem; background: linear-gradient(135deg,#e74c3c,#c0392b); color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 0.8rem; font-weight: 600;">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                    @else
                                    <span style="color: #999; font-size: 0.85rem;">Current user</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Admin Accounts Section -->
            <div id="admins" class="dashboard-section">
                <div style="background: white; border-radius: 20px; padding: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.08); overflow-x: auto;">
                    <h3 style="color: var(--cpsu-blue); font-weight: 700; margin-bottom: 1.5rem;">Admin Accounts</h3>
                    <table style="width: 100%; border-collapse: collapse; min-width: 500px;">
                        <thead>
                            <tr>
                                <th style="text-align:left; padding: 1rem; background: #f8f9fa; color: var(--cpsu-blue); border-bottom: 3px solid var(--cpsu-gold); font-size: 0.85rem; text-transform: uppercase;">Name</th>
                                <th style="text-align:left; padding: 1rem; background: #f8f9fa; color: var(--cpsu-blue); border-bottom: 3px solid var(--cpsu-gold); font-size: 0.85rem; text-transform: uppercase;">Email</th>
                                <th style="text-align:left; padding: 1rem; background: #f8f9fa; color: var(--cpsu-blue); border-bottom: 3px solid var(--cpsu-gold); font-size: 0.85rem; text-transform: uppercase;">Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($accounts->where('user_type', 'admin') as $account)
                            <tr style="border-bottom: 1px solid #f0f0f0;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background=''">
                                <td style="padding: 1rem;">{{ $account->name }}</td>
                                <td style="padding: 1rem;">{{ $account->email }}</td>
                                <td style="padding: 1rem; color: #666; font-size: 0.9rem;">{{ $account->created_at->format('M d, Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Voter Accounts Section -->
            <div id="voters" class="dashboard-section">
                <div style="background: white; border-radius: 20px; padding: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.08); overflow-x: auto;">
                    <h3 style="color: var(--cpsu-blue); font-weight: 700; margin-bottom: 1.5rem;">Voter Accounts</h3>
                    <table style="width: 100%; border-collapse: collapse; min-width: 500px;">
                        <thead>
                            <tr>
                                <th style="text-align:left; padding: 1rem; background: #f8f9fa; color: var(--cpsu-blue); border-bottom: 3px solid var(--cpsu-gold); font-size: 0.85rem; text-transform: uppercase;">Name</th>
                                <th style="text-align:left; padding: 1rem; background: #f8f9fa; color: var(--cpsu-blue); border-bottom: 3px solid var(--cpsu-gold); font-size: 0.85rem; text-transform: uppercase;">Email</th>
                                <th style="text-align:left; padding: 1rem; background: #f8f9fa; color: var(--cpsu-blue); border-bottom: 3px solid var(--cpsu-gold); font-size: 0.85rem; text-transform: uppercase;">Verified</th>
                                <th style="text-align:left; padding: 1rem; background: #f8f9fa; color: var(--cpsu-blue); border-bottom: 3px solid var(--cpsu-gold); font-size: 0.85rem; text-transform: uppercase;">Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($accounts->where('user_type', 'voter') as $account)
                            <tr style="border-bottom: 1px solid #f0f0f0;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background=''">
                                <td style="padding: 1rem;">{{ $account->name }}</td>
                                <td style="padding: 1rem;">{{ $account->email }}</td>
                                <td style="padding: 1rem;">
                                    <span style="padding: 0.3rem 0.8rem; border-radius: 50px; font-size: 0.8rem; font-weight: 700;
                                        background: {{ $account->email_verified_at ? 'linear-gradient(135deg,#d4edda,#c3e6cb)' : 'linear-gradient(135deg,#fff3cd,#ffeaa7)' }};
                                        color: {{ $account->email_verified_at ? '#155724' : '#856404' }};">
                                        {{ $account->email_verified_at ? 'Verified' : 'Pending' }}
                                    </span>
                                </td>
                                <td style="padding: 1rem; color: #666; font-size: 0.9rem;">{{ $account->created_at->format('M d, Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showSection(id) {
            document.querySelectorAll('.dashboard-section').forEach(s => s.classList.remove('active-section'));
            document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
            document.getElementById(id).classList.add('active-section');
            event.currentTarget.classList.add('active');
            const titles = { 'create-account': 'Create Account', 'all-accounts': 'All Accounts', admins: 'Admin Accounts', voters: 'Voter Accounts' };
            document.getElementById('pageTitle').textContent = titles[id] || 'Account Management';
        }

        function filterTable(tableId, query) {
            const rows = document.querySelectorAll(`#${tableId} tbody tr`);
            rows.forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(query.toLowerCase()) ? '' : 'none';
            });
        }

        function highlightType() {
            const voterChecked = document.querySelector('input[value="voter"]').checked;
            document.getElementById('voterLabel').style.borderColor = voterChecked ? 'var(--cpsu-gold)' : '#ddd';
            document.getElementById('adminLabel').style.borderColor = !voterChecked ? 'var(--cpsu-gold)' : '#ddd';
        }

        document.addEventListener('DOMContentLoaded', highlightType);
    </script>
</body>
</html>
