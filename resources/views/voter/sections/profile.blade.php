<div id="profile" class="dashboard-section">
    <div class="profile-section">
        <div class="profile-header">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Voter') }}&background=004d00&color=FFD700&size=120" alt="Profile" class="profile-photo">
            <div class="profile-info">
                <h3>{{ Auth::user()->name ?? 'Voter User' }}</h3>
                <div class="status-badge status-verified">Verified Voter</div>
                <p style="color: #666; margin-top: 0.8rem;">Registered Voter ID: V-{{ str_pad(Auth::id(), 4, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Email Address</div>
                <div class="info-value">{{ Auth::user()->email ?? 'voter@example.com' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Identity Verification</div>
                <div class="info-value">
                    <span class="status-badge status-verified">Verified</span>
                    <small style="display: block; color: #666; margin-top: 0.25rem;">AI Facial Recognition</small>
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Registration Date</div>
                <div class="info-value">{{ Auth::user()->created_at->format('F d, Y') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Last Login</div>
                <div class="info-value">{{ Auth::user()->last_login_at ? Auth::user()->last_login_at->format('Y-m-d H:i') : 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">AI Behavior Score</div>
                <div class="info-value">95% Consistency</div>
            </div>
            <div class="info-item">
                <div class="info-label">Registered Device</div>
                <div class="info-value">This Device (Verified)</div>
            </div>
        </div>
    </div>
</div>
