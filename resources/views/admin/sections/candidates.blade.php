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
