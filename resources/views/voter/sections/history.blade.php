<div id="history" class="dashboard-section">
    <div class="table-container">
        <h3 style="color: var(--cpsu-blue); margin-bottom: 1.5rem; font-weight: 700;">Voting History</h3>
        <table>
            <thead>
                <tr>
                    <th>Election</th>
                    <th>Date & Time</th>
                    <th>Status</th>
                    <th>Reference Code</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Board of Directors Election</td>
                    <td>Dec 12, 2024 14:30</td>
                    <td><span class="status-badge status-verified">Recorded</span></td>
                    <td><code style="background: var(--cpsu-light); padding: 0.3rem 0.6rem; border-radius: 6px; font-weight: 600;">VREF-{{ strtoupper(Str::random(8)) }}</code></td>
                    <td><button class="action-btn btn-primary" onclick="viewReceipt('VREF-{{ strtoupper(Str::random(8)) }}')"><i class="fas fa-receipt"></i> Receipt</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
