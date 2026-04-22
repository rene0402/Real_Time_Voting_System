<div id="notifications" class="dashboard-section">
    <div class="card">
        <div class="card-header">
            <div class="card-title" style="font-size: 1.2rem; text-transform: none;">Notifications & Alerts</div>
        </div>
        <div class="notification-panel">
            <div class="notification-item success">
                <div><strong>Identity Verified</strong><br>Your facial recognition verification was successful.</div>
                <div class="notification-time">2 hours ago</div>
            </div>
            <div class="notification-item">
                <div><strong>Election Reminder</strong><br>Presidential Election ends in 2 days. Cast your vote soon!</div>
                <div class="notification-time">1 day ago</div>
            </div>
            <div class="notification-item warning">
                <div><strong>Security Alert</strong><br>New login detected from different device. Was this you?</div>
                <div class="notification-time">2 days ago</div>
            </div>
            <div class="notification-item">
                <div><strong>System Update</strong><br>Voting system maintenance scheduled for Sunday 2 AM - 4 AM.</div>
                <div class="notification-time">3 days ago</div>
            </div>
        </div>
    </div>
</div>

<div id="help" class="dashboard-section">
    <div class="help-section">
        <h3 style="color: var(--cpsu-blue); margin-bottom: 2rem; font-weight: 700;">Help & Support Center</h3>
        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <i class="fas fa-chevron-right" style="margin-right: 0.5rem;"></i>How do I cast my vote?
            </div>
            <div class="faq-answer" style="display: none;">
                Go to "Active Elections", click "Vote Now", select your candidate, review and confirm.
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <i class="fas fa-chevron-right" style="margin-right: 0.5rem;"></i>Can I change my vote after submitting?
            </div>
            <div class="faq-answer" style="display: none;">
                No. Votes cannot be changed once submitted. Please review carefully before confirming.
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <i class="fas fa-chevron-right" style="margin-right: 0.5rem;"></i>How is my vote kept secret?
            </div>
            <div class="faq-answer" style="display: none;">
                We use end-to-end encryption. Your identity is separated from your vote choice.
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <i class="fas fa-chevron-right" style="margin-right: 0.5rem;"></i>What if I encounter technical issues?
            </div>
            <div class="faq-answer" style="display: none;">
                Contact support at support@securevote.example.com or call +1-800-VOTE-NOW.
            </div>
        </div>
        <div style="margin-top: 3rem; padding-top: 2rem; border-top: 2px solid var(--cpsu-light);">
            <h4 style="color: var(--cpsu-blue); margin-bottom: 1.5rem; font-weight: 700;">Contact Support</h4>
            <div style="display: flex; gap: 2rem; flex-wrap: wrap;">
                <div><p><strong>Email:</strong><br>support@securevote.example.com</p></div>
                <div><p><strong>Phone:</strong><br>+1-800-VOTE-NOW</p></div>
                <div><p><strong>Hours:</strong><br>24/7 during election periods</p></div>
            </div>
        </div>
    </div>
</div>

<div id="security" class="dashboard-section">
    <div class="profile-section">
        <h3 style="color: var(--cpsu-blue); margin-bottom: 2rem; font-weight: 700;">Account Security Settings</h3>
        <div class="security-grid">
            <div class="security-item">
                <h4 style="color: var(--cpsu-blue); margin-bottom: 1rem; font-weight: 600;">Two-Factor Authentication</h4>
                <p style="color: #666; margin-bottom: 1rem;">Add an extra layer of security to your account</p>
                <label class="toggle-switch">
                    <input type="checkbox" id="twoFactorToggle" checked>
                    <span class="toggle-slider"></span>
                </label>
            </div>
            <div class="security-item">
                <h4 style="color: var(--cpsu-blue); margin-bottom: 1rem; font-weight: 600;">Change Password</h4>
                <p style="color: #666; margin-bottom: 1rem;">Update your account password</p>
                <button class="action-btn btn-primary" onclick="showPasswordModal()"><i class="fas fa-key"></i> Change Password</button>
            </div>
            <div class="security-item">
                <h4 style="color: var(--cpsu-blue); margin-bottom: 1rem; font-weight: 600;">Login History</h4>
                <p style="color: #666; margin-bottom: 1rem;">View recent account activity</p>
                <button class="action-btn btn-secondary" onclick="showLoginHistory()"><i class="fas fa-history"></i> View History</button>
            </div>
            <div class="security-item">
                <h4 style="color: var(--cpsu-blue); margin-bottom: 1rem; font-weight: 600;">Logout All Sessions</h4>
                <p style="color: #666; margin-bottom: 1rem;">Sign out from all devices</p>
                <button class="action-btn btn-danger" onclick="logoutAllSessions()"><i class="fas fa-sign-out-alt"></i> Logout Everywhere</button>
            </div>
        </div>
        <div class="table-container" style="margin-top: 2rem;">
            <h4 style="color: var(--cpsu-blue); margin-bottom: 1.5rem; font-weight: 700;">Recent Login Activity</h4>
            <table>
                <thead>
                    <tr><th>Date & Time</th><th>Device</th><th>Location</th><th>IP Address</th><th>Status</th></tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ now()->format('Y-m-d H:i') }}</td>
                        <td>Chrome on Windows</td>
                        <td>Current Location</td>
                        <td>{{ request()->ip() }}</td>
                        <td><span class="status-badge status-active">Current</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
