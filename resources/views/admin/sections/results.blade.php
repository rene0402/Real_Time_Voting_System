<div id="results" class="dashboard-section">
                <div class="tabs">
                    <div class="tab active" onclick="showElectionTab('president')">President</div>
                    <div class="tab" onclick="showElectionTab('vice-president')">Vice President</div>
                    <div class="tab" onclick="showElectionTab('secretary')">Secretary</div>
                </div>

                <div class="chart-container">
                    <div class="chart-title">Live Presidential Election Results</div>
                    <canvas id="resultsChart"></canvas>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Candidate</th>
                                <th>Votes</th>
                                <th>Percentage</th>
                                <th>Trend</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>John Doe (Party A)</td>
                                <td>450</td>
                                <td>45%</td>
                                <td><span class="card-change"><i class="fas fa-arrow-up"></i> +12 votes/min</span></td>
                            </tr>
                            <tr>
                                <td>Jane Smith (Party B)</td>
                                <td>380</td>
                                <td>38%</td>
                                <td><span class="card-change"><i class="fas fa-arrow-up"></i> +8 votes/min</span></td>
                            </tr>
                            <tr>
                                <td>Bob Johnson (Independent)</td>
                                <td>170</td>
                                <td>17%</td>
                                <td><span class="card-change negative"><i class="fas fa-arrow-down"></i> -2 votes/min</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Voter Management Section -->
