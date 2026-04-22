<div id="completed" class="dashboard-section">
                <div class="table-container">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                        <h3 class="chart-title">Completed Elections</h3>
                        <input type="text" class="search-input" id="completedSearch" placeholder="Search completed elections...">
                    </div>
                    <div id="completedTableContainer">
                        <table id="completedTable">
                            <thead>
                                <tr>
                                    <th>Election Name</th>
                                    <th>Type</th>
                                    <th>End Date</th>
                                    <th>Total Votes</th>
                                    <th>Winner</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="completedTableBody">
                                <!-- Data will be loaded via AJAX -->
                            </tbody>
                        </table>
                        <div id="completedLoadingIndicator" style="text-align: center; padding: 2rem; display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Loading completed elections...
                        </div>
                        <div id="completedNoDataMessage" style="text-align: center; padding: 2rem; display: none;">
                            <i class="fas fa-check-circle"></i> No completed elections found.
                        </div>
                    </div>
                </div>
            </div>
        </div>
