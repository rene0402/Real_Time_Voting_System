<div id="ai-alerts" class="dashboard-section">
                <div class="table-container">
                    <h3 class="chart-title">AI Fraud Detection Alerts</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Voter ID</th>
                                <th>Flag Reason</th>
                                <th>AI Confidence</th>
                                <th>IP Address</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>14:30:22</td>
                                <td>#045</td>
                                <td>Multiple vote attempts</td>
                                <td>92%</td>
                                <td>192.168.1.100</td>
                                <td><span class="status-badge status-flagged">Flagged</span></td>
                                <td>
                                    <button class="action-btn btn-view">Review</button>
                                    <button class="action-btn btn-dismiss">Dismiss</button>
                                </td>
                            </tr>
                            <tr>
                                <td>14:15:45</td>
                                <td>#128</td>
                                <td>Unusual voting speed</td>
                                <td>78%</td>
                                <td>192.168.1.205</td>
                                <td><span class="status-badge status-flagged">Flagged</span></td>
                                <td>
                                    <button class="action-btn btn-view">Review</button>
                                    <button class="action-btn btn-approve">Confirm</button>
                                </td>
                            </tr>
                            <tr>
                                <td>13:58:12</td>
                                <td>#067</td>
                                <td>Suspicious IP pattern</td>
                                <td>85%</td>
                                <td>192.168.1.150</td>
                                <td><span class="status-badge status-pending">Under Review</span></td>
                                <td>
                                    <button class="action-btn btn-approve">Approve</button>
                                    <button class="action-btn btn-delete">Invalidate</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Election Management Section -->
