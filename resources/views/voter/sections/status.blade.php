<div id="status" class="dashboard-section">
    <div class="dashboard-grid">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Total Votes Cast</div>
                <i class="fas fa-chart-bar" style="font-size: 2rem; color: #3498db;"></i>
            </div>
            <div class="card-value" id="totalVotesCast">1,247</div>
            <div class="card-subtitle">Across all active elections</div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-title">Turnout Percentage</div>
                <i class="fas fa-percentage" style="font-size: 2rem; color: var(--cpsu-green);"></i>
            </div>
            <div class="card-value" id="turnoutPercentage">62.3%</div>
            <div class="card-subtitle">Of registered voters</div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-title">Live Progress</div>
                <i class="fas fa-tachometer-alt" style="font-size: 2rem; color: var(--cpsu-red);"></i>
            </div>
            <div class="card-value" id="votingProgress">45%</div>
            <div style="margin-top: 1rem; height: 10px; background: #ecf0f1; border-radius: 10px; overflow: hidden;">
                <div style="width: 45%; height: 100%; background: linear-gradient(90deg, var(--cpsu-blue), var(--cpsu-purple)); border-radius: 10px;"></div>
            </div>
        </div>
    </div>
    <div class="card" style="margin-top: 2rem;">
        <div class="card-header">
            <div class="card-title" style="font-size: 1.2rem; text-transform: none;">Live Election Updates</div>
        </div>
        <div class="table-container" style="padding: 0; box-shadow: none;">
            <table>
                <thead>
                    <tr><th>Election</th><th>Status</th><th>Votes Cast</th><th>Turnout</th><th>Time Remaining</th></tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Presidential Election 2024</td>
                        <td><span class="status-badge status-active">Active</span></td>
                        <td>892</td><td>44.6%</td><td>2 days, 5 hours</td>
                    </tr>
                    <tr>
                        <td>Board of Directors Election</td>
                        <td><span class="status-badge status-active">Active</span></td>
                        <td>1,247</td><td>62.3%</td><td>7 days, 5 hours</td>
                    </tr>
                    <tr>
                        <td>Constitutional Amendment</td>
                        <td><span class="status-badge status-closed">Closed</span></td>
                        <td>1,845</td><td>92.2%</td><td>Results pending</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
