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
                    <div class="election-tab" onclick="showElectionSection('basic-info')">Basic Info</div>
                    <div class="election-tab" onclick="showElectionSection('voter-eligibility')">Voter Eligibility</div>
                    <div class="election-tab" onclick="showElectionSection('positions')">Positions</div>
                    <div class="election-tab" onclick="showElectionSection('candidates')">Candidates</div>
                    <div class="election-tab" onclick="showElectionSection('ballot-preview')">Ballot Preview</div>
                    <div class="election-tab" onclick="showElectionSection('manage')">Manage</div>
                    <div class="election-tab" onclick="showElectionSection('security')">Security</div>
                </div>

<!-- Election Overview Panel -->
                <div id="election-overview" class="election-section active-election-section">
                    <div class="table-container">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                            <h3 class="chart-title">All Elections</h3>
                            <div style="display: flex; gap: 1rem;">
                                <input type="text" class="search-input" placeholder="Search..." id="electionSearch">
                                <button class="action-btn btn-primary" onclick="window.location.href='/admin/election-management'">+ New Election</button>
                            </div>
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

<!-- Basic Info Section -->
                <div id="election-basic-info" class="election-section">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Step 1: Basic Information & Organization</div>
                        </div>
                        <div class="card-body">
<p>Create and manage election basic information. <a href="{{ route('admin.election-management') }}">Go to full election wizard</a></p>
                        </div>
                    </div>
                </div>

                <!-- Voter Eligibility Section -->
                <div id="election-voter-eligibility" class="election-section">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Step 2: Voter Eligibility</div>
                        </div>
                        <div class="card-body">
<p>Configure voter eligibility settings. <a href="{{ route('admin.election-management') }}">Go to full election wizard</a></p>
                        </div>
                    </div>
                </div>

                <!-- Positions Section -->
                <div id="election-positions" class="election-section">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Step 3: Positions</div>
                        </div>
                        <div class="card-body">
<p>Manage election positions. <a href="{{ route('admin.election-management') }}">Go to full election wizard</a></p>
                        </div>
                    </div>
                </div>

                <!-- Candidates Section -->
                <div id="election-candidates" class="election-section">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Step 4: Candidates</div>
                        </div>
                        <div class="card-body">
<p>Add and manage candidates. <a href="{{ route('admin.election-management') }}">Go to full election wizard</a></p>
                        </div>
                    </div>
                </div>

                <!-- Ballot Preview Section -->
                <div id="election-ballot-preview" class="election-section">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Step 5: Ballot Preview</div>
                        </div>
                        <div class="card-body">
<p>Preview the ballot before publishing. <a href="{{ route('admin.election-management') }}">Go to full election wizard</a></p>
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
