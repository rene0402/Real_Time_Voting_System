<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Election Management - Real-Time Voting System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Font Awesome -->
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
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
            color: #333;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Step Wizard Styles */
        .wizard-container {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .wizard-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .wizard-header h2 {
            color: var(--cpsu-blue);
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .wizard-steps {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .wizard-step {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.8rem 1.5rem;
            background: var(--cpsu-light);
            border-radius: 50px;
            font-weight: 600;
            color: #999;
            transition: all 0.3s;
        }

        .wizard-step.active {
            background: linear-gradient(135deg, var(--cpsu-blue), #003d00);
            color: white;
            box-shadow: 0 4px 15px rgba(0, 77, 0, 0.3);
        }

        .wizard-step.completed {
            background: var(--cpsu-green);
            color: white;
        }

        .wizard-step-number {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
        }

        .wizard-content {
            display: none;
        }

        .wizard-content.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: var(--cpsu-blue);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .form-input, .form-select {
            width: 100%;
            padding: 0.9rem 1.2rem;
            border: 2px solid #ddd;
            border-radius: 12px;
            font-size: 0.95rem;
            font-family: inherit;
            transition: all 0.3s;
        }

        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: var(--cpsu-gold);
            box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.1);
        }

        .form-textarea {
            min-height: 100px;
            resize: vertical;
        }

        .btn {
            padding: 0.9rem 1.8rem;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-family: inherit;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
            background: var(--cpsu-light);
            color: #666;
            border: 2px solid #ddd;
        }

        .btn-secondary:hover {
            background: #eaeaea;
        }

        .btn-success {
            background: linear-gradient(135deg, var(--cpsu-green), #229954);
            color: white;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);
        }

        .wizard-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid var(--cpsu-light);
        }

        /* Position Management */
        .position-card {
            background: var(--cpsu-light);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--cpsu-blue);
        }

        .position-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .position-title {
            font-weight: 700;
            color: var(--cpsu-blue);
            font-size: 1.1rem;
        }

        .position-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            background: var(--cpsu-blue);
            color: white;
        }

        .position-badge.multi {
            background: var(--cpsu-purple);
        }

        /* Candidates List */
        .candidate-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: white;
            border-radius: 10px;
            margin-bottom: 0.5rem;
            border: 1px solid #eee;
        }

        .candidate-photo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--cpsu-gold);
        }

        .candidate-info {
            flex: 1;
        }

        .candidate-name {
            font-weight: 600;
            color: var(--cpsu-dark);
        }

        .candidate-party {
            font-size: 0.85rem;
            color: #666;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .party-badge {
            padding: 0.2rem 0.6rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            background: var(--cpsu-light);
            color: #666;
        }

        /* Ballot Preview */
        .ballot-preview {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            border: 2px solid var(--cpsu-gold);
        }

        .ballot-position {
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px dashed #eee;
        }

        .ballot-position:last-child {
            border-bottom: none;
        }

        .ballot-position-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--cpsu-blue);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .ballot-candidate {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: var(--cpsu-light);
            border-radius: 10px;
            margin-bottom: 0.5rem;
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .ballot-candidate:hover {
            border-color: var(--cpsu-gold);
            transform: translateX(5px);
        }

        .ballot-candidate.selected {
            border-color: var(--cpsu-green);
            background: rgba(40, 167, 69, 0.1);
        }

        /* Eligibility Options */
        .eligibility-option {
            padding: 1rem;
            border: 2px solid #ddd;
            border-radius: 12px;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s;
        }

        .eligibility-option:hover {
            border-color: var(--cpsu-gold);
        }

        .eligibility-option.selected {
            border-color: var(--cpsu-blue);
            background: rgba(0, 77, 0, 0.05);
        }

        .eligibility-option input {
            display: none;
        }

        .eligibility-icon {
            font-size: 1.5rem;
            color: var(--cpsu-blue);
            margin-bottom: 0.5rem;
        }

        .eligibility-title {
            font-weight: 600;
            color: var(--cpsu-dark);
        }

        .eligibility-desc {
            font-size: 0.85rem;
            color: #666;
        }

        /* Organizations Grid */
        .org-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .org-card {
            padding: 1rem;
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid transparent;
            background: var(--cpsu-light);
        }

        .org-card:hover {
            border-color: var(--cpsu-gold);
            transform: translateY(-3px);
        }

        .org-card.selected {
            border-color: var(--cpsu-blue);
            background: rgba(0, 77, 0, 0.1);
        }

        .org-color {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin: 0 auto 0.5rem;
        }

        .org-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--cpsu-dark);
        }

        .org-short {
            font-size: 0.8rem;
            color: #666;
        }

        /* Table Styles */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .data-table th {
            background: var(--cpsu-blue);
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .data-table td {
            padding: 1rem;
            border-bottom: 1px solid #eee;
        }

        .data-table tr:hover {
            background: var(--cpsu-light);
        }

        .status-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-active { background: #d4edda; color: #155724; }
        .status-scheduled { background: #d1ecf1; color: #0c5460; }
        .status-closed { background: #e2e3e5; color: #383d41; }

        /* Action Buttons */
        .action-btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.8rem;
            margin-right: 0.3rem;
            transition: all 0.3s;
        }

        .btn-view { background: #3498db; color: white; }
        .btn-edit { background: #f39c12; color: white; }
        .btn-delete { background: #e74c3c; color: white; }

        .action-btn:hover {
            transform: translateY(-2px);
            opacity: 0.9;
        }

        /* Notification */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            color: white;
            font-weight: 500;
            z-index: 9999;
            animation: slideIn 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .notification-success { background: var(--cpsu-green); }
        .notification-error { background: var(--cpsu-red); }
        .notification-info { background: var(--cpsu-blue); }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* Party Colors */
        .party-united-front { background: #004d00; color: white; }
        .party-student-first { background: #6f42c1; color: white; }
        .party-independent { background: #95a5a6; color: white; }

        /* Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
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
            border-radius: 20px;
            padding: 2rem;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--cpsu-blue);
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Back Button -->
        <div style="margin-bottom: 1rem;">
            <a href="{{ route('admin.dashboard') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--cpsu-blue); text-decoration: none; font-weight: 600;">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
        <!-- Wizard Container -->
        <div class="wizard-container">
            <div class="wizard-header">
                <h2><i class="fas fa-vote-yea"></i> Create New Election</h2>
                <p style="color: #666;">Follow the steps below to configure your election</p>
            </div>

            <!-- Wizard Steps -->
            <div class="wizard-steps">
                <div class="wizard-step active" data-step="1">
                    <span class="wizard-step-number">1</span>
                    <span>Basic Info</span>
                </div>
                <div class="wizard-step" data-step="2">
                    <span class="wizard-step-number">2</span>
                    <span>Voter Eligibility</span>
                </div>
                <div class="wizard-step" data-step="3">
                    <span class="wizard-step-number">3</span>
                    <span>Positions</span>
                </div>
                <div class="wizard-step" data-step="4">
                    <span class="wizard-step-number">4</span>
                    <span>Candidates</span>
                </div>
                <div class="wizard-step" data-step="5">
                    <span class="wizard-step-number">5</span>
                    <span>Preview</span>
                </div>
            </div>

            <!-- Step 1: Basic Information -->
            <div class="wizard-content active" id="step-1">
                <h3 style="color: var(--cpsu-blue); margin-bottom: 1.5rem;">Step 1: Basic Information & Organization</h3>

                <div class="form-group">
                    <label class="form-label">Select Organization *</label>
                    <div class="org-grid" id="organizationGrid">
                        <!-- Organizations will be loaded here -->
                    </div>
                    <input type="hidden" id="selectedOrganization" name="organization_id">
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Election Title *</label>
                        <input type="text" class="form-input" id="electionTitle" placeholder="e.g., SSG General Elections 2026">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Election Type *</label>
                        <select class="form-select" id="electionType">
                            <option value="single">Single Position</option>
                            <option value="multi">Multi-Position</option>
                            <option value="referendum">Referendum</option>
                        </select>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Start Date & Time *</label>
                        <input type="datetime-local" class="form-input" id="electionStart">
                    </div>

                    <div class="form-group">
                        <label class="form-label">End Date & Time *</label>
                        <input type="datetime-local" class="form-input" id="electionEnd">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea class="form-input form-textarea" id="electionDescription" placeholder="Brief description of the election..."></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Voting Method</label>
                    <select class="form-select" id="votingMethod">
                        <option value="fptp">First Past The Post (FPTP)</option>
                        <option value="ranked_choice">Ranked Choice Voting</option>
                        <option value="approval">Approval Voting</option>
                    </select>
                    <small style="color: #666; margin-top: 0.3rem; display: block;">FPTP is the simplest method where candidate with most votes wins.</small>
                </div>
            </div>

            <!-- Step 2: Voter Eligibility -->
            <div class="wizard-content" id="step-2">
                <h3 style="color: var(--cpsu-blue); margin-bottom: 1.5rem;">Step 2: Voter Eligibility</h3>

                <div class="form-group">
                    <label class="form-label">Who can vote in this election?</label>

                    <label class="eligibility-option" onclick="selectEligibility('all')">
                        <input type="radio" name="eligibility" value="all_members">
                        <div class="eligibility-icon"><i class="fas fa-users"></i></div>
                        <div class="eligibility-title">All Verified Members</div>
                        <div class="eligibility-desc">Any verified user in the system can vote</div>
                    </label>

                    <label class="eligibility-option" onclick="selectEligibility('email_domain')">
                        <input type="radio" name="eligibility" value="email_domain">
                        <div class="eligibility-icon"><i class="fas fa-envelope"></i></div>
                        <div class="eligibility-title">Email Domain Filter</div>
                        <div class="eligibility-desc">Only users with specific email domains can vote (e.g., @ub.edu.ph)</div>
                    </label>

                    <label class="eligibility-option" onclick="selectEligibility('student_ids')">
                        <input type="radio" name="eligibility" value="student_ids">
                        <div class="eligibility-icon"><i class="fas fa-id-card"></i></div>
                        <div class="eligibility-title">Student ID List</div>
                        <div class="eligibility-desc">Only users with specific student IDs can vote</div>
                    </label>

                    <label class="eligibility-option" onclick="selectEligibility('manual')">
                        <input type="radio" name="eligibility" value="manual">
                        <div class="eligibility-icon"><i class="fas fa-user-check"></i></div>
                        <div class="eligibility-title">Manual Voter List</div>
                        <div class="eligibility-desc">Manually add eligible voters</div>
                    </label>
                </div>

                <div class="form-group" id="emailDomainsGroup" style="display: none;">
                    <label class="form-label">Allowed Email Domains</label>
                    <input type="text" class="form-input" id="allowedDomains" placeholder="e.g., @ub.edu.ph, @cpsu.edu.ph (comma separated)">
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <input type="checkbox" id="requireVerification" checked>
                        Require email verification before voting
                    </label>
                </div>
            </div>

            <!-- Step 3: Positions -->
            <div class="wizard-content" id="step-3">
                <h3 style="color: var(--cpsu-blue); margin-bottom: 1.5rem;">Step 3: Positions</h3>

                <div class="form-group">
                    <div style="display: flex; gap: 1rem; align-items: flex-end;">
                        <div style="flex: 1;">
                            <label class="form-label">Position Name *</label>
                            <input type="text" class="form-input" id="positionName" placeholder="e.g., President, Secretary">
                        </div>
                        <div style="width: 150px;">
                            <label class="form-label">Seats</label>
                            <input type="number" class="form-input" id="positionSeats" value="1" min="1">
                        </div>
                        <div style="width: 180px;">
                            <label class="form-label">Type</label>
                            <select class="form-select" id="positionType">
                                <option value="single_winner">Single Winner</option>
                                <option value="multi_winner">Multi-Winner</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="addPosition()">
                            <i class="fas fa-plus"></i> Add
                        </button>
                    </div>
                </div>

                <div id="positionsList">
                    <!-- Added positions will appear here -->
                </div>

                <div style="background: var(--cpsu-light); padding: 1rem; border-radius: 10px; margin-top: 1rem;">
                    <p style="color: #666; font-size: 0.9rem;">
                        <i class="fas fa-info-circle"></i>
                        <strong>Tip:</strong> Add all positions that will be contested in this election.
                        For multi-seat positions (e.g., 5 Senators), set seats to 5.
                    </p>
                </div>
            </div>

            <!-- Step 4: Candidates -->
            <div class="wizard-content" id="step-4">
                <h3 style="color: var(--cpsu-blue); margin-bottom: 1.5rem;">Step 4: Candidates</h3>

                <div class="form-group">
                    <label class="form-label">Select Position</label>
                    <select class="form-select" id="candidatePositionSelect" onchange="loadCandidatesForPosition()">
                        <option value="">-- Select Position --</option>
                    </select>
                </div>

                <div id="candidateForm" style="display: none;">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Candidate Name *</label>
                            <input type="text" class="form-input" id="candidateName" placeholder="Full name">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Party/Affiliation</label>
                            <input type="text" class="form-input" id="candidateParty" placeholder="e.g., United Front, Student First">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea class="form-input form-textarea" id="candidateDescription" placeholder="Brief bio..."></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Campaign Manifesto/Platform</label>
                        <textarea class="form-input form-textarea" id="candidateManifesto" placeholder="Key points of their campaign..."></textarea>
                    </div>

                    <button type="button" class="btn btn-success" onclick="addCandidate()">
                        <i class="fas fa-plus"></i> Add Candidate
                    </button>
                </div>

                <div id="candidatesList">
                    <!-- Candidates will be listed here by position -->
                </div>
            </div>

            <!-- Step 5: Ballot Preview -->
            <div class="wizard-content" id="step-5">
                <h3 style="color: var(--cpsu-blue); margin-bottom: 1.5rem;">Step 5: Ballot Preview</h3>

                <div class="ballot-preview" id="ballotPreview">
                    <div style="text-align: center; padding: 2rem; color: #666;">
                        <i class="fas fa-spinner fa-spin" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                        <p>Configure positions and candidates to see the ballot preview</p>
                    </div>
                </div>
            </div>

            <!-- Wizard Actions -->
            <div class="wizard-actions">
                <button type="button" class="btn btn-secondary" id="prevBtn" onclick="prevStep()" style="visibility: hidden;">
                    <i class="fas fa-arrow-left"></i> Previous
                </button>
                <button type="button" class="btn btn-primary" id="nextBtn" onclick="nextStep()">
                    Next <i class="fas fa-arrow-right"></i>
                </button>
                <button type="button" class="btn btn-success" id="submitBtn" onclick="createElection()" style="display: none;">
                    <i class="fas fa-check"></i> Create Election
                </button>
            </div>
        </div>

        <!-- Existing Elections Table -->
        <div class="wizard-container">
            <h3 style="color: var(--cpsu-blue); margin-bottom: 1.5rem;">
                <i class="fas fa-list"></i> Existing Elections
            </h3>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Election</th>
                        <th>Organization</th>
                        <th>Status</th>
                        <th>Positions</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="electionsTableBody">
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem;">
                            <i class="fas fa-spinner fa-spin"></i> Loading elections...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // State
        let currentStep = 1;
        const totalSteps = 5;
        let selectedOrgId = null;
        let positions = [];
        let candidates = [];
        let organizations = [];

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadOrganizations();
            loadElections();
        });

        // Load Organizations
        async function loadOrganizations() {
            try {
                const response = await fetch('/admin/api/organizations', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                const data = await response.json();

                if (data.success) {
                    organizations = data.data;
                    renderOrganizations();
                }
            } catch (error) {
                console.error('Error loading organizations:', error);
            }
        }

        // Render Organizations
        function renderOrganizations() {
            const grid = document.getElementById('organizationGrid');
            grid.innerHTML = organizations.map(org => `
                <div class="org-card ${selectedOrgId === org.id ? 'selected' : ''}" onclick="selectOrganization(${org.id})">
                    <div class="org-color" style="background: ${org.color}"></div>
                    <div class="org-name">${org.name}</div>
                    <div class="org-short">${org.short_name}</div>
                </div>
            `).join('');
        }

        // Select Organization
        function selectOrganization(id) {
            selectedOrgId = id;
            document.getElementById('selectedOrganization').value = id;
            renderOrganizations();
        }

        // Load Elections
        async function loadElections() {
            try {
                const response = await fetch('/admin/elections', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                const data = await response.json();

                if (data.success) {
                    renderElections(data.data);
                }
            } catch (error) {
                console.error('Error loading elections:', error);
            }
        }

        // Render Elections Table
        function renderElections(elections) {
            const tbody = document.getElementById('electionsTableBody');

            if (elections.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: #666;">
                            <i class="fas fa-inbox"></i> No elections found. Create your first election above.
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = elections.map(election => `
                <tr>
                    <td><strong>${election.title}</strong></td>
                    <td>${election.organization ? election.organization.short_name : 'N/A'}</td>
                    <td><span class="status-badge status-${election.status}">${election.status}</span></td>
                    <td>${election.total_positions || 0}</td>
                    <td>${new Date(election.start_date).toLocaleDateString()}</td>
                    <td>${new Date(election.end_date).toLocaleDateString()}</td>
                    <td>
                        <button class="action-btn btn-view" onclick="viewElection(${election.id})"><i class="fas fa-eye"></i></button>
                        <button class="action-btn btn-edit" onclick="editElection(${election.id})"><i class="fas fa-edit"></i></button>
                        <button class="action-btn btn-delete" onclick="deleteElection(${election.id})"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `).join('');
        }

        // Eligibility Selection
        function selectEligibility(type) {
            document.querySelectorAll('.eligibility-option').forEach(opt => opt.classList.remove('selected'));
            event.currentTarget.classList.add('selected');

            const emailGroup = document.getElementById('emailDomainsGroup');
            emailGroup.style.display = type === 'email_domain' ? 'block' : 'none';
        }

        // Add Position
        function addPosition() {
            const name = document.getElementById('positionName').value.trim();
            const seats = parseInt(document.getElementById('positionSeats').value);
            const type = document.getElementById('positionType').value;

            if (!name) {
                showNotification('Please enter a position name', 'error');
                return;
            }

            const position = {
                id: Date.now(),
                name: name,
                seats_available: seats,
                election_type: type,
                candidates: []
            };

            positions.push(position);
            renderPositions();
            updateCandidateSelect();

            // Clear form
            document.getElementById('positionName').value = '';
            document.getElementById('positionSeats').value = '1';
        }

        // Render Positions
        function renderPositions() {
            const list = document.getElementById('positionsList');

            if (positions.length === 0) {
                list.innerHTML = '<p style="color: #666; text-align: center; padding: 1rem;">No positions added yet.</p>';
                return;
            }

            list.innerHTML = positions.map(pos => `
                <div class="position-card">
                    <div class="position-header">
                        <span class="position-title">${pos.name}</span>
                        <div>
                            <span class="position-badge ${pos.seats_available > 1 ? 'multi' : ''}">${pos.seats_available} seat${pos.seats_available > 1 ? 's' : ''}</span>
                            <button class="action-btn btn-delete" onclick="removePosition(${pos.id})" style="margin-left: 0.5rem;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <p style="font-size: 0.9rem; color: #666;">
                        ${pos.candidates.length} candidate${pos.candidates.length !== 1 ? 's' : ''} added
                    </p>
                </div>
            `).join('');
        }

        // Remove Position
        function removePosition(id) {
            positions = positions.filter(p => p.id !== id);
            candidates = candidates.filter(c => c.position_id !== id);
            renderPositions();
            updateCandidateSelect();
            renderCandidates();
        }

        // Update Candidate Position Select
        function updateCandidateSelect() {
            const select = document.getElementById('candidatePositionSelect');
            select.innerHTML = '<option value="">-- Select Position --</option>' +
                positions.map(p => `<option value="${p.id}">${p.name}</option>`).join('');
        }

        // Load Candidates for Position
        function loadCandidatesForPosition() {
            const positionId = document.getElementById('candidatePositionSelect').value;
            const form = document.getElementById('candidateForm');
            form.style.display = positionId ? 'block' : 'none';
            renderCandidates();
        }

        // Add Candidate
        function addCandidate() {
            const positionId = parseInt(document.getElementById('candidatePositionSelect').value);
            const name = document.getElementById('candidateName').value.trim();
            const party = document.getElementById('candidateParty').value.trim();
            const description = document.getElementById('candidateDescription').value.trim();
            const manifesto = document.getElementById('candidateManifesto').value.trim();

            if (!name) {
                showNotification('Please enter candidate name', 'error');
                return;
            }

            const candidate = {
                id: Date.now(),
                position_id: positionId,
                name: name,
                party_affiliation: party || 'Independent',
                description: description,
                manifesto: manifesto
            };

            candidates.push(candidate);

            // Add to position
            const position = positions.find(p => p.id === positionId);
            if (position) {
                position.candidates.push(candidate);
            }

            renderCandidates();

            // Clear form
            document.getElementById('candidateName').value = '';
            document.getElementById('candidateParty').value = '';
            document.getElementById('candidateDescription').value = '';
            document.getElementById('candidateManifesto').value = '';
        }

        // Render Candidates
        function renderCandidates() {
            const list = document.getElementById('candidatesList');
            const positionId = document.getElementById('candidatePositionSelect').value;

            if (!positionId) {
                list.innerHTML = '<p style="color: #666; text-align: center; padding: 1rem;">Select a position to view/add candidates.</p>';
                return;
            }

            const positionCandidates = candidates.filter(c => c.position_id === parseInt(positionId));
            const position = positions.find(p => p.id === parseInt(positionId));

            if (positionCandidates.length === 0) {
                list.innerHTML = `<p style="color: #666; text-align: center; padding: 1rem;">No candidates for ${position.name} yet.</p>`;
                return;
            }

            // Group by party
            const grouped = {};
            positionCandidates.forEach(c => {
                const party = c.party_affiliation || 'Independent';
                if (!grouped[party]) grouped[party] = [];
                grouped[party].push(c);
            });

            let html = `<h4 style="color: var(--cpsu-blue); margin-bottom: 1rem;">${position.name}</h4>`;

            Object.keys(grouped).forEach(party => {
                html += `<div style="margin-bottom: 1rem;">
                    <span class="party-badge" style="background: var(--cpsu-blue); color: white; padding: 0.3rem 0.8rem; border-radius: 4px; font-size: 0.8rem; margin-bottom: 0.5rem; display: inline-block;">${party}</span>`;

                grouped[party].forEach(c => {
                    html += `
                        <div class="candidate-item">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--cpsu-light); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="candidate-info">
                                <div class="candidate-name">${c.name}</div>
                            </div>
                            <button class="action-btn btn-delete" onclick="removeCandidate(${c.id})"><i class="fas fa-trash"></i></button>
                        </div>
                    `;
                });

                html += '</div>';
            });

            list.innerHTML = html;
        }

        // Remove Candidate
        function removeCandidate(id) {
            const candidate = candidates.find(c => c.id === id);
            if (candidate) {
                const position = positions.find(p => p.id === candidate.position_id);
                if (position) {
                    position.candidates = position.candidates.filter(c => c.id !== id);
                }
            }
            candidates = candidates.filter(c => c.id !== id);
            renderCandidates();
        }

        // Navigation
        function nextStep() {
            if (currentStep === 1) {
                // Validate step 1
                const title = document.getElementById('electionTitle').value.trim();
                const start = document.getElementById('electionStart').value;
                const end = document.getElementById('electionEnd').value;

                if (!title || !start || !end) {
                    showNotification('Please fill in all required fields', 'error');
                    return;
                }

                if (new Date(end) <= new Date(start)) {
                    showNotification('End date must be after start date', 'error');
                    return;
                }
            }

            if (currentStep === 3 && positions.length === 0) {
                showNotification('Please add at least one position', 'error');
                return;
            }

            if (currentStep < totalSteps) {
                currentStep++;
                updateWizard();
            }

            if (currentStep === 5) {
                renderBallotPreview();
            }
        }

        function prevStep() {
            if (currentStep > 1) {
                currentStep--;
                updateWizard();
            }
        }

        function updateWizard() {
            // Update step content
            document.querySelectorAll('.wizard-content').forEach((content, index) => {
                content.classList.toggle('active', index + 1 === currentStep);
            });

            // Update step indicators
            document.querySelectorAll('.wizard-step').forEach((step, index) => {
                step.classList.remove('active', 'completed');
                if (index + 1 === currentStep) {
                    step.classList.add('active');
                } else if (index + 1 < currentStep) {
                    step.classList.add('completed');
                }
            });

            // Update buttons
            document.getElementById('prevBtn').style.visibility = currentStep === 1 ? 'hidden' : 'visible';
            document.getElementById('nextBtn').style.display = currentStep === totalSteps ? 'none' : 'inline-block';
            document.getElementById('submitBtn').style.display = currentStep === totalSteps ? 'inline-block' : 'none';
        }

        // Render Ballot Preview
        function renderBallotPreview() {
            const preview = document.getElementById('ballotPreview');

            if (positions.length === 0) {
                preview.innerHTML = '<p style="text-align: center; color: #666;">Add positions and candidates to see preview</p>';
                return;
            }

            let html = `<div style="text-align: center; margin-bottom: 2rem;">
                <h3 style="color: var(--cpsu-blue);">${document.getElementById('electionTitle').value || 'Election Ballot'}</h3>
                <p style="color: #666;">${positions.length} Position(s) • ${candidates.length} Candidate(s)</p>
            </div>`;

            positions.forEach(position => {
                html += `
                    <div class="ballot-position">
                        <div class="ballot-position-title">
                            ${position.name}
                            <span style="font-size: 0.8rem; font-weight: normal; color: #666;">
                                (Vote for ${position.seats_available})
                            </span>
                        </div>
                `;

                const posCandidates = candidates.filter(c => c.position_id === position.id);
                posCandidates.forEach(candidate => {
                    const partyClass = candidate.party_affiliation.toLowerCase().replace(/\s+/g, '-');
                    html += `
                        <div class="ballot-candidate">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--cpsu-light); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="candidate-info">
                                <div class="candidate-name">${candidate.name}</div>
                                <div class="candidate-party">
                                    <span class="party-badge">${candidate.party_affiliation}</span>
                                </div>
                            </div>
                        </div>
                    `;
                });

                if (posCandidates.length === 0) {
                    html += '<p style="color: #999; font-style: italic;">No candidates yet</p>';
                }

                html += '</div>';
            });

            preview.innerHTML = html;
        }

        // Create Election
        async function createElection() {
            try {
                // Create election
                const electionResponse = await fetch('/admin/elections', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        title: document.getElementById('electionTitle').value,
                        organization_id: selectedOrgId,
                        type: document.getElementById('electionType').value,
                        start_date: document.getElementById('electionStart').value,
                        end_date: document.getElementById('electionEnd').value,
                        description: document.getElementById('electionDescription').value,
                        voting_method: document.getElementById('votingMethod').value,
                        voter_eligibility: {
                            type: document.querySelector('input[name="eligibility"]:checked')?.value || 'all_members',
                            domains: document.getElementById('allowedDomains').value.split(',').map(d => d.trim()).filter(d => d)
                        }
                    })
                });

                const electionData = await electionResponse.json();

                if (!electionData.success) {
                    showNotification(electionData.message || 'Failed to create election', 'error');
                    return;
                }

                const electionId = electionData.data.id;

                // Create positions
                for (const position of positions) {
                    const posResponse = await fetch(`/admin/elections/${electionId}/positions`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            name: position.name,
                            seats_available: position.seats_available,
                            election_type: position.election_type,
                            order: positions.indexOf(position) + 1
                        })
                    });

                    const posData = await posResponse.json();

                    if (posData.success && position.candidates.length > 0) {
                        // Create candidates for this position
                        for (const candidate of position.candidates) {
                            await fetch('/admin/candidates', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    election_id: electionId,
                                    position_id: posData.data.id,
                                    name: candidate.name,
                                    party_affiliation: candidate.party_affiliation,
                                    description: candidate.description,
                                    manifesto: candidate.manifesto
                                })
                            });
                        }
                    }
                }

                showNotification('Election created successfully!', 'success');

                // Reset form
                resetForm();
                loadElections();

            } catch (error) {
                console.error('Error creating election:', error);
                showNotification('Failed to create election', 'error');
            }
        }

        // Reset Form
        function resetForm() {
            currentStep = 1;
            selectedOrgId = null;
            positions = [];
            candidates = [];

            document.getElementById('electionTitle').value = '';
            document.getElementById('electionStart').value = '';
            document.getElementById('electionEnd').value = '';
            document.getElementById('electionDescription').value = '';

            renderOrganizations();
            renderPositions();
            renderCandidates();
            updateWizard();
        }

        // View Election
        function viewElection(id) {
            window.location.href = `/admin/elections/${id}`;
        }

        // Edit Election
        function editElection(id) {
            window.location.href = `/admin/elections/${id}/edit`;
        }

        // Delete Election
        async function deleteElection(id) {
            if (!confirm('Are you sure you want to delete this election?')) return;

            try {
                const response = await fetch(`/admin/elections/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    showNotification('Election deleted successfully', 'success');
                    loadElections();
                } else {
                    showNotification(data.message || 'Failed to delete election', 'error');
                }
            } catch (error) {
                showNotification('Failed to delete election', 'error');
            }
        }

        // Show Notification
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 5000);
        }
    </script>
</body>
</html>

