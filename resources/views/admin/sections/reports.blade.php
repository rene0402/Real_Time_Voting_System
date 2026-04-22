<div id="reports" class="dashboard-section">
                <!-- Report Filters -->
                <div class="filters-section">
                    <div class="filters-row">
                        <div class="filter-group">
                            <label class="filter-label">Report Type</label>
                            <select class="filter-input" id="reportType" onchange="loadReportData()">
                                <option value="voter-turnout">Voter Turnout</option>
                                <option value="election-results">Election Results</option>
                                <option value="ai-patterns">AI Voting Patterns</option>
                                <option value="time-based">Time-Based Analysis</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Election</label>
                            <select class="filter-input" id="reportElectionFilter" onchange="loadReportData()">
                                <option value="">All Elections</option>
                                @if(isset($elections))
                                    @foreach($elections as $election)
                                        <option value="{{ $election->id }}">{{ $election->title }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Date Range</label>
                            <select class="filter-input" id="reportDateRange" onchange="loadReportData()">
                                <option value="7">Last 7 Days</option>
                                <option value="14">Last 14 Days</option>
                                <option value="30">Last 30 Days</option>
                                <option value="90">Last 90 Days</option>
                                <option value="all">All Time</option>
                            </select>
                        </div>
                        <div class="filter-actions">
                            <button class="btn-export" onclick="exportToCSV()">
                                <i class="fas fa-file-csv"></i> Export CSV
                            </button>
                            <button class="btn-export" style="background: linear-gradient(135deg, #e74c3c, #c0392b);" onclick="exportToPDF()">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Report Tabs -->
                <div class="tabs">
                    <div class="tab active" onclick="showReportTab('turnout')">Voter Turnout</div>
                    <div class="tab" onclick="showReportTab('results')">Election Results</div>
                    <div class="tab" onclick="showReportTab('ai-patterns')">AI Patterns</div>
                    <div class="tab" onclick="showReportTab('time-based')">Time Analysis</div>
                </div>

                <!-- Voter Turnout Report -->
                <div id="report-turnout" class="report-tab active">
                    <!-- Summary Stats -->
                    <div class="stats-row">
                        <div class="stat-item">
                            <div class="stat-value" id="totalRegisteredVoters">-</div>
                            <div class="stat-label">Total Registered</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="totalVotesCast">-</div>
                            <div class="stat-label">Total Votes Cast</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="overallTurnout">-</div>
                            <div class="stat-label">Overall Turnout %</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="avgTurnout">-</div>
                            <div class="stat-label">Avg per Election</div>
                        </div>
                    </div>

                    <!-- Turnout Chart -->
                    <div class="charts-section">
                        <div class="chart-container" style="height: 350px;">
                            <div class="chart-title">Voter Turnout by Election</div>
                            <canvas id="turnoutChart"></canvas>
                        </div>
                        <div class="chart-container" style="height: 350px;">
                            <div class="chart-title">Turnout Distribution</div>
                            <canvas id="turnoutPieChart"></canvas>
                        </div>
                    </div>

                    <!-- Turnout Table -->
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Election</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Votes Cast</th>
                                    <th>Turnout Rate</th>
                                    <th>Date Range</th>
                                </tr>
                            </thead>
                            <tbody id="turnoutTableBody">
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 2rem;">
                                        <i class="fas fa-spinner fa-spin"></i> Loading turnout data...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Election Results Report -->
                <div id="report-results" class="report-tab">
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Election</th>
                                    <th>Candidate</th>
                                    <th>Votes</th>
                                    <th>Percentage</th>
                                    <th>Status</th>
                                    <th>Winner</th>
                                </tr>
                            </thead>
                            <tbody id="resultsTableBody">
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 2rem;">
                                        <i class="fas fa-spinner fa-spin"></i> Loading results data...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Results Chart -->
                    <div class="chart-container" style="margin-top: 2rem; height: 400px;">
                        <div class="chart-title">Election Results Comparison</div>
                        <canvas id="resultsComparisonChart"></canvas>
                    </div>
                </div>

                <!-- AI Patterns Report -->
                <div id="report-ai-patterns" class="report-tab">
                    <!-- AI Stats -->
                    <div class="stats-row">
                        <div class="stat-item">
                            <div class="stat-value" id="aiTotalVotes">-</div>
                            <div class="stat-label">Total Votes Analyzed</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="aiUniqueVoters">-</div>
                            <div class="stat-label">Unique Voters</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="aiPeakHour">-</div>
                            <div class="stat-label">Peak Voting Hour</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="aiAvgPerHour">-</div>
                            <div class="stat-label">Avg Votes/Hour</div>
                        </div>
                    </div>

                    <!-- AI Pattern Chart -->
                    <div class="charts-section">
                        <div class="chart-container" style="height: 350px;">
                            <div class="chart-title">Voting Activity by Hour</div>
                            <canvas id="aiHourlyChart"></canvas>
                        </div>
                        <div class="chart-container" style="height: 350px;">
                            <div class="chart-title">Daily Voting Trends</div>
                            <canvas id="aiDailyChart"></canvas>
                        </div>
                    </div>

                    <!-- Anomalies Section -->
                    <div class="table-container">
                        <h3 class="chart-title" style="margin-bottom: 1.5rem;">Detected Anomalies</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Time</th>
                                    <th>Count</threshold>
                                    <th>Threshold</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="anomaliesTableBody">
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 2rem;">
                                        <i class="fas fa-spinner fa-spin"></i> Loading anomaly data...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Time-Based Analysis Report -->
                <div id="report-time-based" class="report-tab">
                    <!-- Time Stats -->
                    <div class="stats-row">
                        <div class="stat-item">
                            <div class="stat-value" id="timeTotalVotes">-</div>
                            <div class="stat-label">Total Votes</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="timeDaysAnalyzed">-</div>
                            <div class="stat-label">Days Analyzed</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="timeChangePercent">-</div>
                            <div class="stat-label">vs Previous Period</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="timeAvgDaily">-</div>
                            <div class="stat-label">Avg Daily Votes</div>
                        </div>
                    </div>

                    <!-- Cumulative Votes Chart -->
                    <div class="chart-container" style="height: 400px; margin-bottom: 2rem;">
                        <div class="chart-title">Cumulative Votes Over Time</div>
                        <canvas id="cumulativeChart"></canvas>
                    </div>

                    <!-- Hourly Heatmap -->
                    <div class="chart-container" style="height: 400px;">
                        <div class="chart-title">Voting Activity Heatmap (Hourly)</div>
                        <canvas id="heatmapChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Completed Elections Section -->
