<div id="dashboard" class="dashboard-section active-section">
    <div class="notification-panel">
        <div class="notification-item success">
            <div><strong>Welcome back!</strong> Your identity has been verified via AI facial recognition.</div>
            <div class="notification-time">Just now</div>
        </div>
    </div>
    <div class="dashboard-grid">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Verification Status</div>
                <i class="fas fa-user-check" style="font-size: 2rem; color: var(--cpsu-green);"></i>
            </div>
            <div class="card-value">Verified</div>
            <div class="card-subtitle">AI Verification Score: 98%</div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-title">Active Elections</div>
                <i class="fas fa-vote-yea" style="font-size: 2rem; color: #3498db;"></i>
            </div>
            <div class="card-value" id="activeElectionsCount">2</div>
            <div class="card-subtitle">Available to vote in</div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-title">Voting Status</div>
                <i class="fas fa-check-circle" style="font-size: 2rem; color: var(--cpsu-green);"></i>
            </div>
            <div class="card-value" id="votingStatus">Voted</div>
            <div class="card-subtitle">In 1 of 2 elections</div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-title">Next Election</div>
                <i class="fas fa-clock" style="font-size: 2rem; color: var(--cpsu-red);"></i>
            </div>
            <div class="countdown-label">Time Remaining:</div>
            <div class="countdown-timer" id="electionCountdown">23:45:12</div>
        </div>
    </div>
    <div class="card" style="margin-top: 2rem;">
        <div class="card-header">
            <div class="card-title" style="font-size: 1.2rem; text-transform: none;">Your Active Elections</div>
        </div>
        <div class="election-grid" id="activeElectionsList">
            @forelse($electionsWithStatus ?? [] as $election)
                <div class="election-card">
                    <div class="election-header">
                        <div class="election-title">{{ $election->title }}</div>
                        <span class="status-badge status-{{ $election->status == 'active' ? 'active' : 'closed' }}">{{ ucfirst($election->status) }}</span>
                    </div>
                    <div class="election-time">
                        <i class="fas fa-clock"></i>
                        @if($election->status == 'active') Ends: {{ $election->end_date->format('M d, Y H:i') }}
                        @else {{ $election->time_remaining }} @endif
                    </div>
                    <div class="election-description">{{ $election->description ?? 'Vote for your preferred candidates in this election.' }}</div>
                    @if($election->has_voted)
                        <button class="vote-btn voted" disabled><i class="fas fa-check"></i> Already Voted</button>
                    @elseif($election->status == 'active')
                        <button class="vote-btn" onclick="startVoting({{ $election->id }}, '{{ $election->title }}')">Vote Now</button>
                    @else
                        <button class="vote-btn closed" disabled>Voting Closed</button>
                    @endif
                </div>
            @empty
                <div class="election-card">
                    <div class="election-description" style="text-align: center; padding: 2rem;">
                        <i class="fas fa-info-circle" style="font-size: 2rem; color: #6c757d; margin-bottom: 1rem;"></i>
                        <p>No active elections available at the moment.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
