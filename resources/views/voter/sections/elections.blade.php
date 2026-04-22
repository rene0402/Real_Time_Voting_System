<div id="elections" class="dashboard-section">
    <div class="card">
        <div class="card-header">
            <div class="card-title" style="font-size: 1.2rem; text-transform: none;">Elections Available for Voting</div>
        </div>
        <div class="election-grid">
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
