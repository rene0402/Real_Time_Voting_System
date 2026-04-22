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
