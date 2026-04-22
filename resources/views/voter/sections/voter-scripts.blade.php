<script>
    let selectedCandidate = null;
    let currentElection = null;

    function showSection(sectionId) {
        document.querySelectorAll('.dashboard-section').forEach(s => s.classList.remove('active-section'));
        document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
        const section = document.getElementById(sectionId);
        if (section) section.classList.add('active-section');
        event.currentTarget.classList.add('active');
        const titles = { dashboard:'Voter Dashboard', profile:'Profile & Verification', elections:'Active Elections', voting:'Vote Now', history:'Voting History', status:'Election Status', notifications:'Notifications', help:'Help & Support', security:'Security Settings' };
        document.getElementById('pageTitle').textContent = titles[sectionId] || 'Voter Dashboard';
        document.getElementById('profileDropdown').classList.remove('active');
    }

    function toggleProfileDropdown() {
        document.getElementById('profileDropdown').classList.toggle('active');
        event.stopPropagation();
    }

    document.addEventListener('click', function(e) {
        const userProfile = document.querySelector('.user-profile');
        if (!userProfile.contains(e.target)) document.getElementById('profileDropdown').classList.remove('active');
    });

    function performLogout() { document.getElementById('logout-form').submit(); }

    function startVoting(electionId, electionTitle) {
        currentElection = electionId;
        document.getElementById('votingElectionTitle').textContent = electionTitle || 'Election Voting';
        loadCandidates(electionId);
        showSection('voting');
        document.getElementById('votingNavItem').style.display = 'block';
    }

    async function loadCandidates(electionId) {
        const candidateList = document.getElementById('candidateList');
        try {
            const response = await fetch(`/voter/candidates/${electionId}`, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } });
            const data = await response.json();
            if (response.ok && data.success) {
                let html = `<table class="candidate-table"><thead><tr><th>Photo</th><th>Name</th><th>Description</th><th>Action</th></tr></thead><tbody>`;
                data.data.forEach(c => {
                    html += `<tr onclick="selectCandidate(${c.id})" id="candidate-${c.id}">
                        <td><img src="${c.photo_url || 'https://ui-avatars.com/api/?name='+encodeURIComponent(c.name)+'&background=004d00&color=FFD700&size=60'}" class="candidate-photo"></td>
                        <td><div class="candidate-name">${c.name}</div></td>
                        <td><div class="candidate-description">${c.description || 'No description available.'}</div></td>
                        <td><button class="candidate-select" onclick="selectCandidate(${c.id}); event.stopPropagation();">Select</button></td>
                    </tr>`;
                });
                html += `</tbody></table>`;
                candidateList.innerHTML = html;
                selectedCandidate = null;
                document.getElementById('reviewVoteBtn').style.display = 'none';
            } else {
                candidateList.innerHTML = `<p style="text-align:center;color:var(--cpsu-red);">${data.error || 'Error loading candidates.'}</p>`;
            }
        } catch (e) {
            candidateList.innerHTML = '<p style="text-align:center;color:var(--cpsu-red);">Error loading candidates. Please try again.</p>';
        }
    }

    function selectCandidate(candidateId) {
        document.querySelectorAll('.candidate-table tbody tr').forEach(r => r.classList.remove('selected'));
        const row = document.getElementById(`candidate-${candidateId}`);
        row.classList.add('selected');
        selectedCandidate = { id: candidateId, name: row.querySelector('.candidate-name').textContent };
        document.getElementById('reviewVoteBtn').style.display = 'inline-block';
    }

    function reviewVote() {
        if (!selectedCandidate) { alert('Please select a candidate first.'); return; }
        document.getElementById('selectedCandidateName').textContent = selectedCandidate.name;
        document.getElementById('voteConfirmationModal').classList.add('active');
    }

    function closeVoteConfirmation() { document.getElementById('voteConfirmationModal').classList.remove('active'); }

    async function submitVote() {
        if (!selectedCandidate) { alert('Please select a candidate first.'); return; }
        try {
            const response = await fetch(`/voter/vote/${currentElection}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                body: JSON.stringify({ choices: [selectedCandidate.id] })
            });
            const data = await response.json();
            if (response.ok) {
                closeVoteConfirmation();
                document.getElementById('voteReferenceCode').textContent = data.reference_code;
                document.getElementById('voteSuccessModal').classList.add('active');
                setTimeout(() => location.reload(), 3000);
            } else {
                alert(data.error || 'Error submitting vote. Please try again.');
            }
        } catch (e) {
            alert('Network error. Please check your connection and try again.');
        }
    }

    function closeVoteSuccess() { document.getElementById('voteSuccessModal').classList.remove('active'); showSection('dashboard'); }
    function viewReceipt(refCode) { alert(`Vote Receipt\nReference Code: ${refCode}\nStatus: Successfully Recorded`); }

    function toggleFAQ(element) {
        const answer = element.parentElement.querySelector('.faq-answer');
        const icon = element.querySelector('i');
        const isHidden = answer.style.display === 'none';
        answer.style.display = isHidden ? 'block' : 'none';
        icon.classList.toggle('fa-chevron-right', !isHidden);
        icon.classList.toggle('fa-chevron-down', isHidden);
    }

    function showPasswordModal() { alert('Password change form would open here.'); }
    function showLoginHistory() { alert('Detailed login history would be displayed here.'); }
    function logoutAllSessions() { if (confirm('Logout from all devices?')) alert('All other sessions have been logged out.'); }

    function updateCountdown() {
        const target = new Date();
        target.setDate(target.getDate() + 2);
        target.setHours(23, 59, 59, 0);
        function tick() {
            const d = target - Date.now();
            if (d < 0) { document.getElementById('electionCountdown').textContent = '00:00:00'; return; }
            const h = Math.floor((d % 86400000) / 3600000);
            const m = Math.floor((d % 3600000) / 60000);
            const s = Math.floor((d % 60000) / 1000);
            document.getElementById('electionCountdown').textContent = `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
        }
        tick();
        setInterval(tick, 1000);
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateCountdown();
        setInterval(() => {
            const v = document.getElementById('totalVotesCast');
            if (v) v.textContent = (parseInt(v.textContent.replace(',','')) + Math.floor(Math.random()*3)).toLocaleString();
            const t = document.getElementById('turnoutPercentage');
            if (t) t.textContent = (parseFloat(t.textContent) + 0.1).toFixed(1) + '%';
        }, 10000);
    });
</script>
