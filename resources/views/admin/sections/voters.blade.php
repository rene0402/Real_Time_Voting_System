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
