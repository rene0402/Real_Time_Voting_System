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

        // ==================== NEW WIZARD FUNCTIONS ====================

        // Organization selection
        function selectOrg(orgId) {
            document.getElementById('selectedOrgId').value = orgId;
            document.querySelectorAll('#orgGrid .org-card').forEach(card => {
                card.style.borderColor = 'transparent';
                card.style.background = '#f8f9fa';
            });
            const selected = document.querySelector(`#orgGrid .org-card[data-id="${orgId}"]`);
            if (selected) {
                selected.style.borderColor = '#004d00';
                selected.style.background = 'rgba(0, 77, 0, 0.1)';
            }
        }

        // Eligibility selection
        function selectEligibility(type) {
            document.querySelectorAll('.eligibility-option').forEach(opt => {
                opt.style.borderColor = '#ddd';
                opt.style.background = 'white';
            });
            event.currentTarget.style.borderColor = '#004d00';
            event.currentTarget.style.background = 'rgba(0, 77, 0, 0.05)';

            const emailGroup = document.getElementById('emailDomainsGroup');
            if (emailGroup) {
                emailGroup.style.display = type === 'email_domain' ? 'block' : 'none';
            }
        }

        // Positions management
        let positions = [];
        let candidates = [];

        function addPosition() {
            const name = document.getElementById('positionName').value.trim();
            const seats = parseInt(document.getElementById('positionSeats').value);

            if (!name) {
                showNotification('Please enter a position name', 'error');
                return;
            }

            const position = {
                id: Date.now(),
                name: name,
                seats_available: seats,
                candidates: []
            };

            positions.push(position);
            renderPositions();
            updateCandidateSelect();

            document.getElementById('positionName').value = '';
            document.getElementById('positionSeats').value = '1';
        }

        function renderPositions() {
            const list = document.getElementById('positionsList');
            if (!list) return;

            if (positions.length === 0) {
                list.innerHTML = '<p style="color: #666; text-align: center; padding: 1rem;">No positions added yet.</p>';
                return;
            }

            list.innerHTML = positions.map(pos => `
                <div class="position-card" style="background: #f8f9fa; border-radius: 12px; padding: 1rem; margin-bottom: 0.5rem; border-left: 4px solid #004d00;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <strong>${pos.name}</strong>
                        <div>
                            <span style="background: #004d00; color: white; padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.8rem;">
                                ${pos.seats_available} seat${pos.seats_available > 1 ? 's' : ''}
                            </span>
                            <button onclick="removePosition(${pos.id})" style="background: #dc3545; color: white; border: none; padding: 0.3rem 0.6rem; border-radius: 4px; cursor: pointer; margin-left: 0.5rem;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div style="font-size: 0.85rem; color: #666; margin-top: 0.5rem;">
                        ${pos.candidates.length} candidate${pos.candidates.length !== 1 ? 's' : ''} added
                    </div>
                </div>
            `).join('');
        }

        function removePosition(id) {
            positions = positions.filter(p => p.id !== id);
            candidates = candidates.filter(c => c.position_id !== id);
            renderPositions();
            updateCandidateSelect();
            renderCandidates();
        }

        function updateCandidateSelect() {
            const select = document.getElementById('candidatePositionSelect');
            if (!select) return;

            select.innerHTML = '<option value="">-- Select Position --</option>' +
                positions.map(p => `<option value="${p.id}">${p.name}</option>`).join('');
        }

        function loadCandidatesForPosition() {
            const positionId = document.getElementById('candidatePositionSelect').value;
            const form = document.getElementById('candidateForm');
            if (form) {
                form.style.display = positionId ? 'block' : 'none';
            }
            renderCandidates();
        }

        function addCandidate() {
            const positionId = parseInt(document.getElementById('candidatePositionSelect').value);
            const name = document.getElementById('candidateName').value.trim();
            const party = document.getElementById('candidateParty').value.trim();
            const description = document.getElementById('candidateDescription').value.trim();

            if (!name) {
                showNotification('Please enter candidate name', 'error');
                return;
            }

            const candidate = {
                id: Date.now(),
                position_id: positionId,
                name: name,
                party_affiliation: party || 'Independent',
                description: description
            };

            candidates.push(candidate);

            const position = positions.find(p => p.id === positionId);
            if (position) {
                position.candidates.push(candidate);
            }

            renderCandidates();

            document.getElementById('candidateName').value = '';
            document.getElementById('candidateParty').value = '';
            document.getElementById('candidateDescription').value = '';
        }

        function renderCandidates() {
            const list = document.getElementById('candidatesList');
            const positionId = document.getElementById('candidatePositionSelect').value;

            if (!list) return;

            if (!positionId) {
                list.innerHTML = '<p style="color: #666; text-align: center; padding: 1rem;">Select a position to view/add candidates.</p>';
                return;
            }

            const positionCandidates = candidates.filter(c => c.position_id === parseInt(positionId));

            if (positionCandidates.length === 0) {
                list.innerHTML = '<p style="color: #666; text-align: center; padding: 1rem;">No candidates for this position yet.</p>';
                return;
            }

            // Group by party
            const grouped = {};
            positionCandidates.forEach(c => {
                const party = c.party_affiliation || 'Independent';
                if (!grouped[party]) grouped[party] = [];
                grouped[party].push(c);
            });

            let html = '';
            Object.keys(grouped).forEach(party => {
                html += `<div style="margin-bottom: 1rem;">
                    <span style="background: #004d00; color: white; padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.8rem; margin-bottom: 0.5rem; display: inline-block;">${party}</span>`;

                grouped[party].forEach(c => {
                    html += `
                        <div style="display: flex; align-items: center; gap: 1rem; padding: 0.8rem; background: white; border-radius: 8px; margin-bottom: 0.3rem; border: 1px solid #eee;">
                            <div style="width: 35px; height: 35px; border-radius: 50%; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user"></i>
                            </div>
                            <div style="flex: 1;">
                                <strong>${c.name}</strong>
                            </div>
                            <button onclick="removeCandidate(${c.id})" style="background: #dc3545; color: white; border: none; padding: 0.3rem 0.6rem; border-radius: 4px; cursor: pointer;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                });
                html += '</div>';
            });

            list.innerHTML = html;
        }

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

        // Render ballot preview
        function renderBallotPreview() {
            const preview = document.getElementById('ballotPreview');
            if (!preview) return;

            if (positions.length === 0) {
                preview.innerHTML = '<p style="text-align: center; color: #666;">Add positions and candidates to see preview</p>';
                return;
            }

            let html = `<div style="text-align: center; margin-bottom: 2rem;">
                <h3 style="color: #004d00;">${document.getElementById('electionTitle').value || 'Election Ballot'}</h3>
                <p style="color: #666;">${positions.length} Position(s) • ${candidates.length} Candidate(s)</p>
            </div>`;

            positions.forEach(position => {
                html += `<div style="margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 2px dashed #eee;">
                    <h4 style="color: #004d00; margin-bottom: 1rem;">
                        ${position.name}
                        <span style="font-size: 0.8rem; font-weight: normal; color: #666;">(Vote for ${position.seats_available})</span>
                    </h4>`;

                const posCandidates = candidates.filter(c => c.position_id === position.id);
                posCandidates.forEach(candidate => {
                    html += `
                        <div style="display: flex; align-items: center; gap: 1rem; padding: 0.8rem; background: #f8f9fa; border-radius: 8px; margin-bottom: 0.5rem;">
                            <div style="width: 35px; height: 35px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; border: 2px solid #FFD700;">
                                <i class="fas fa-user"></i>
                            </div>
                            <div style="flex: 1;">
                                <strong>${candidate.name}</strong>
                                <div style="font-size: 0.85rem; color: #666;">
                                    <span style="background: #004d00; color: white; padding: 0.1rem 0.4rem; border-radius: 3px; font-size: 0.7rem;">${candidate.party_affiliation}</span>
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
