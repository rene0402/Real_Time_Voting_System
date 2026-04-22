<div id="voting" class="dashboard-section">
    <div class="voting-interface">
        <h2 style="color: var(--cpsu-blue); margin-bottom: 1rem; font-weight: 700;" id="votingElectionTitle">Select an Election</h2>
        <p style="color: #666; margin-bottom: 2rem;">Select your preferred candidate. You can review your selection before submitting.</p>
        <div class="candidate-list" id="candidateList"></div>
        <div style="text-align: center; margin-top: 2rem;">
            <button class="action-btn btn-secondary" onclick="showSection('elections')">Back to Elections</button>
            <button class="action-btn btn-primary" id="reviewVoteBtn" onclick="reviewVote()" style="display: none;">
                <i class="fas fa-eye"></i> Review Vote
            </button>
        </div>
    </div>
</div>
