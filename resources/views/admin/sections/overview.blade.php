<div id="overview" class="dashboard-section active-section">
                <!-- Alert Panel -->
                <div class="alert-panel">
                    <div class="alert-title"><i class="fas fa-exclamation-triangle"></i> AI Security Alert</div>
                    <div class="alert-content">Multiple voting attempts detected from IP: 192.168.1.100. Confidence: 85%</div>
                </div>

                <!-- Quick Stats -->
                <div class="stats-row">
                    <div class="stat-item">
                        <div class="stat-value" id="totalVoters">{{ number_format($totalVoters) }}</div>
                        <div class="stat-label">Registered Voters</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="votesCast">{{ number_format($votesCast) }}</div>
                        <div class="stat-label">Votes Cast</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="participationRate">{{ $participationRate }}%</div>
                        <div class="stat-label">Participation Rate</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="activeElections">{{ $activeElections }}</div>
                        <div class="stat-label">Active Elections</div>
                    </div>
                </div>

                <!-- Main Cards -->
                <div class="dashboard-grid">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Voting Progress</div>
                            <i class="fas fa-chart-line" style="font-size: 2rem; color: var(--cpsu-blue);"></i>
                        </div>
                        <div class="card-value">{{ $votingProgress }}%</div>
                        <div class="card-change">{{ $votesCast }} votes cast</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $votingProgress }}%"></div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">System Health</div>
                            <i class="fas fa-heartbeat" style="font-size: 2rem; color: var(--cpsu-green);"></i>
                        </div>
                        <div class="card-value">{{ $systemHealth['percentage'] ?? 100 }}%</div>
                        <div class="card-change">{{ $systemHealth['label'] ?? 'Operational' }}</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $systemHealth['percentage'] ?? 100 }}%; background: linear-gradient(90deg, var(--cpsu-green), #229954);"></div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Active Users</div>
                            <i class="fas fa-user-clock" style="font-size: 2rem; color: var(--cpsu-purple);"></i>
                        </div>
                        <div class="card-value">{{ $activeUsersCount }}</div>
                        <div class="card-change">Currently active</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ min(100, $activeUsersCount * 2) }}%; background: linear-gradient(90deg, var(--cpsu-purple), #8e44ad);"></div>
                        </div>
                    </div>
                </div>

                <!-- Charts -->
                <div class="charts-section">
                    <div class="chart-container">
                        <div class="chart-title">Votes Per Hour (Today)</div>
                        <canvas id="votesChart"></canvas>
                    </div>
                    <div class="chart-container">
                        <div class="chart-title">Election Status Distribution</div>
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Live Results Section -->
