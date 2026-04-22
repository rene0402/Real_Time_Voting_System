<div id="monitoring" class="dashboard-section">
                <!-- Real-Time System Monitoring -->
                <div class="stats-row">
                    <!-- Server Status -->
                    <div class="stat-item">
                        <div class="stat-value">
                            <span id="serverStatusIndicator" class="status-dot {{ $monitoringData['server_status']['color'] === 'success' ? 'online' : 'offline' }}"></span>
                            <span id="serverStatusText">{{ $monitoringData['server_status']['label'] }}</span>
                        </div>
                        <div class="stat-label">Server Status</div>
                    </div>

                    <!-- Active Users -->
                    <div class="stat-item">
                        <div class="stat-value" id="activeUsersCount">{{ $monitoringData['active_users']['count'] }}</div>
                        <div class="stat-label">Active Users (15 min)</div>
                    </div>

                    <!-- Votes Per Minute -->
                    <div class="stat-item">
                        <div class="stat-value" id="votesPerMinuteCount">{{ $monitoringData['votes_per_minute'] }}</div>
                        <div class="stat-label">Votes Per Minute</div>
                    </div>

                    <!-- Votes Today -->
                    <div class="stat-item">
                        <div class="stat-value" id="votesTodayCount">{{ number_format($monitoringData['votes_today']) }}</div>
                        <div class="stat-label">Votes Today</div>
                    </div>
                </div>

                <!-- Second Row -->
                <div class="dashboard-grid">
                    <!-- Peak Voting Times -->
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Peak Voting Times</div>
                            <i class="fas fa-clock" style="font-size: 1.5rem; color: var(--cpsu-blue);"></i>
                        </div>
                        <div class="card-value" id="peakTime">{{ $monitoringData['peak_voting_times']['peak_label'] }}</div>
                        <div class="card-change">{{ $monitoringData['peak_voting_times']['peak_count'] }} votes at peak hour</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $monitoringData['peak_voting_times']['peak_count'] > 0 ? min(100, ($monitoringData['peak_voting_times']['peak_count'] / max($monitoringData['votes_today'], 1)) * 100) : 0 }}%"></div>
                        </div>
                    </div>

                    <!-- Average Votes Per Hour -->
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Average Votes/Hour</div>
                            <i class="fas fa-chart-line" style="font-size: 1.5rem; color: var(--cpsu-purple);"></i>
                        </div>
                        <div class="card-value" id="avgVotesPerHour">{{ $monitoringData['avg_votes_per_hour'] }}</div>
                        <div class="card-change">Last 24 hours</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ min(100, $monitoringData['avg_votes_per_hour'] * 10) }}%; background: linear-gradient(90deg, var(--cpsu-purple), #8e44ad);"></div>
                        </div>
                    </div>

                    <!-- System Uptime -->
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">System Uptime</div>
                            <i class="fas fa-server" style="font-size: 1.5rem; color: var(--cpsu-green);"></i>
                        </div>
                        <div class="card-value">{{ $monitoringData['system_uptime'] }}</div>
                        <div class="card-change">Since last restart</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 100%; background: linear-gradient(90deg, var(--cpsu-green), #229954);"></div>
                        </div>
                    </div>

                    <!-- Memory Usage -->
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Memory Usage</div>
                            <i class="fas fa-microchip" style="font-size: 1.5rem; color: var(--cpsu-orange);"></i>
                        </div>
                        <div class="card-value">{{ $monitoringData['memory_usage']['used'] }} / {{ $monitoringData['memory_usage']['total'] }}</div>
                        <div class="card-change">{{ $monitoringData['memory_usage']['percentage'] }}% used</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $monitoringData['memory_usage']['percentage'] }}%; background: linear-gradient(90deg, var(--cpsu-orange), #e67e22);"></div>
                        </div>
                    </div>
                </div>

                <!-- Peak Hours Chart -->
                <div class="chart-container" style="margin-top: 2rem;">
                    <div class="chart-title">Voting Activity by Hour (Last 7 Days)</div>
                    <canvas id="monitoringChart"></canvas>
                </div>

                <!-- Auto-refresh indicator -->
                <div style="text-align: center; margin-top: 1.5rem; color: #666;">
                    <i class="fas fa-sync fa-spin" style="margin-right: 0.5rem;"></i>
                    Auto-refreshing every 5 seconds
                </div>
            </div>

            <!-- Reports & Analytics Section -->
