<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Candidate;
use App\Models\Election;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch voters
        $voters = User::where('user_type', 'voter')->orderBy('created_at', 'desc')->get();

        // Fetch candidates
        $candidates = Candidate::with('election')->orderBy('created_at', 'desc')->get();

        // Fetch elections for filter
        $elections = Election::all();

        // Calculate stats
        $totalVoters = $voters->count();
        $votesCast = Vote::count();
        $participationRate = $totalVoters > 0 ? round(($votesCast / $totalVoters) * 100, 1) : 0;
        $activeElections = Election::where('status', 'active')->count();

        // Calculate voting progress (percentage of voters who have voted)
        $votingProgress = $totalVoters > 0 ? round(($votesCast / $totalVoters) * 100, 1) : 0;

        // Calculate system health
        $systemHealth = $this->getSystemHealth();

        // Get active users count (logged in within last 15 minutes)
        $activeUsersCount = $this->getActiveUsersCount();

        // System Monitoring Data
        $monitoringData = $this->getMonitoringData();

        return view('admin.admin-dashboard', compact(
            'voters',
            'candidates',
            'elections',
            'totalVoters',
            'votesCast',
            'participationRate',
            'activeElections',
            'votingProgress',
            'systemHealth',
            'activeUsersCount',
            'monitoringData'
        ));
    }

    /**
     * Get real-time monitoring data
     */
    public function getMonitoringData()
    {
        // Server Status (simulated - checking if app is running)
        $serverStatus = $this->checkServerStatus();

        // Active Users (logged in within last 15 minutes)
        $activeUsers = $this->getActiveUsers();

        // Votes per minute
        $votesPerMinute = $this->getVotesPerMinute();

        // Peak voting times
        $peakVotingTimes = $this->getPeakVotingTimes();

        // Total votes today
        $votesToday = $this->getVotesToday();

        // Average votes per hour
        $avgVotesPerHour = $this->getAvgVotesPerHour();

        // System uptime (simulated)
        $systemUptime = $this->getSystemUptime();

        // Memory usage (simulated)
        $memoryUsage = $this->getMemoryUsage();

        return [
            'server_status' => $serverStatus,
            'active_users' => $activeUsers,
            'votes_per_minute' => $votesPerMinute,
            'peak_voting_times' => $peakVotingTimes,
            'votes_today' => $votesToday,
            'avg_votes_per_hour' => $avgVotesPerHour,
            'system_uptime' => $systemUptime,
            'memory_usage' => $memoryUsage,
        ];
    }

    /**
     * Check server status
     */
    private function checkServerStatus()
    {
        // Check if database is accessible
        try {
            DB::connection()->getPdo();
            return [
                'status' => 'online',
                'label' => 'Online',
                'color' => 'success'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'offline',
                'label' => 'Offline',
                'color' => 'danger'
            ];
        }
    }

    /**
     * Get active users (logged in within last 15 minutes)
     */
    private function getActiveUsers()
    {
        $fifteenMinutesAgo = Carbon::now()->subMinutes(15);

        $count = User::where('last_login_at', '>=', $fifteenMinutesAgo)
            ->where('user_type', 'voter')
            ->count();

        return [
            'count' => $count,
            'trend' => $count > 0 ? 'up' : 'stable'
        ];
    }

    /**
     * Get votes per minute
     */
    private function getVotesPerMinute()
    {
        $oneMinuteAgo = Carbon::now()->subMinute();

        $count = Vote::where('created_at', '>=', $oneMinuteAgo)->count();

        return $count;
    }

    /**
     * Get peak voting times (last 7 days)
     */
    private function getPeakVotingTimes()
    {
        $sevenDaysAgo = Carbon::now()->subDays(7);

        $votes = Vote::where('created_at', '>=', $sevenDaysAgo)
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // Initialize all hours with 0
        $hourlyData = [];
        for ($i = 0; $i < 24; $i++) {
            $hourlyData[$i] = 0;
        }

        // Fill in actual data
        foreach ($votes as $vote) {
            $hourlyData[$vote->hour] = $vote->count;
        }

        // Find peak hour
        $peakHour = array_keys($hourlyData, max($hourlyData))[0];
        $peakCount = max($hourlyData);

        return [
            'hourly' => $hourlyData,
            'peak_hour' => $peakHour,
            'peak_count' => $peakCount,
            'peak_label' => $this->formatHour($peakHour)
        ];
    }

    /**
     * Get votes today
     */
    private function getVotesToday()
    {
        return Vote::whereDate('created_at', Carbon::today())->count();
    }

    /**
     * Get average votes per hour
     */
    private function getAvgVotesPerHour()
    {
        $twentyFourHoursAgo = Carbon::now()->subHours(24);

        $count = Vote::where('created_at', '>=', $twentyFourHoursAgo)->count();

        return round($count / 24, 1);
    }

    /**
     * Get system uptime (simulated)
     */
    private function getSystemUptime()
    {
        // In a real application, you would get this from the server
        // For now, we'll simulate it
        return '3 days, 14 hours';
    }

    /**
     * Get memory usage (simulated)
     */
    private function getMemoryUsage()
    {
        // In a real application, you would get this from the server
        // For now, we'll simulate it
        return [
            'used' => '128 MB',
            'total' => '512 MB',
            'percentage' => 25
        ];
    }

    /**
     * Format hour for display
     */
    private function formatHour($hour)
    {
        if ($hour == 0) return '12:00 AM';
        if ($hour == 12) return '12:00 PM';
        if ($hour > 12) return ($hour - 12) . ':00 PM';
        return $hour . ':00 AM';
    }

    /**
     * Calculate system health percentage based on various metrics
     */
    private function getSystemHealth()
    {
        // Calculate health based on multiple factors:
        // 1. Database connectivity
        // 2. Active users (more users = healthier system)
        // 3. Recent votes (active voting = healthy election)

        try {
            DB::connection()->getPdo();
            $dbHealthy = 100;
        } catch (\Exception $e) {
            $dbHealthy = 0;
        }

        // Get active users in last hour as a percentage of total voters
        $oneHourAgo = Carbon::now()->subHour();
        $activeUsers = User::where('last_login_at', '>=', $oneHourAgo)
            ->where('user_type', 'voter')
            ->count();

        $totalVoters = User::where('user_type', 'voter')->count();
        $userActivity = $totalVoters > 0 ? min(100, ($activeUsers / $totalVoters) * 100 * 10) : 0;

        // Get votes in last hour
        $votesLastHour = Vote::where('created_at', '>=', $oneHourAgo)->count();
        $votingActivity = min(100, $votesLastHour * 10);

        // Weighted average: 40% DB, 30% user activity, 30% voting activity
        $health = round(($dbHealthy * 0.4) + ($userActivity * 0.3) + ($votingActivity * 0.3));

        return [
            'percentage' => $health,
            'status' => $health >= 80 ? 'excellent' : ($health >= 60 ? 'good' : ($health >= 40 ? 'fair' : 'poor')),
            'label' => $health >= 80 ? 'Excellent' : ($health >= 60 ? 'Good' : ($health >= 40 ? 'Fair' : 'Needs Attention'))
        ];
    }

    /**
     * Get active users count for the dashboard
     */
    private function getActiveUsersCount()
    {
        // Get users logged in within last 15 minutes
        $fifteenMinutesAgo = Carbon::now()->subMinutes(15);

        return User::where('last_login_at', '>=', $fifteenMinutesAgo)
            ->where('user_type', 'voter')
            ->count();
    }

    /**
     * API endpoint for real-time monitoring data
     */
    public function getRealTimeMonitoring()
    {
        $data = $this->getMonitoringData();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Get voter turnout report data
     */
    public function getVoterTurnoutReport(Request $request)
    {
        $electionId = $request->input('election_id');

        $elections = Election::withCount(['candidates', 'votes'])
            ->when($electionId, function($query) use ($electionId) {
                return $query->where('id', $electionId);
            })
            ->get();

        $totalVoters = User::where('user_type', 'voter')->count();
        $totalVotes = Vote::count();

        $turnoutData = $elections->map(function($election) use ($totalVoters) {
            $votesCast = $election->votes_count ?? 0;
            $turnoutRate = $totalVoters > 0 ? round(($votesCast / $totalVoters) * 100, 1) : 0;

            return [
                'id' => $election->id,
                'title' => $election->title,
                'type' => $election->type,
                'status' => $election->status,
                'total_voters' => $totalVoters,
                'votes_cast' => $votesCast,
                'turnout_rate' => $turnoutRate,
                'candidates_count' => $election->candidates_count ?? 0,
                'start_date' => $election->start_date,
                'end_date' => $election->end_date,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $turnoutData,
            'summary' => [
                'total_voters' => $totalVoters,
                'total_votes' => $totalVotes,
                'overall_turnout' => $totalVoters > 0 ? round(($totalVotes / $totalVoters) * 100, 1) : 0,
            ]
        ]);
    }

    /**
     * Get election result summaries
     */
    public function getElectionResults(Request $request)
    {
        $electionId = $request->input('election_id');

        $elections = Election::with(['candidates' => function($query) {
                $query->orderBy('vote_count', 'desc');
            }])
            ->when($electionId, function($query) use ($electionId) {
                return $query->where('id', $electionId);
            })
            ->where('status', '!=', 'scheduled')
            ->get();

        $resultsData = $elections->map(function($election) {
            $totalVotes = $election->candidates->sum('vote_count');
            $candidates = $election->candidates->map(function($candidate) use ($totalVotes) {
                $percentage = $totalVotes > 0 ? round(($candidate->vote_count / $totalVotes) * 100, 1) : 0;

                return [
                    'id' => $candidate->id,
                    'name' => $candidate->name,
                    'photo_url' => $candidate->photo_url,
                    'votes' => $candidate->vote_count,
                    'percentage' => $percentage,
                ];
            });

            $winner = $candidates->first();

            return [
                'id' => $election->id,
                'title' => $election->title,
                'type' => $election->type,
                'status' => $election->status,
                'total_votes' => $totalVotes,
                'winner' => $winner ? $winner['name'] : 'No winner',
                'winner_votes' => $winner ? $winner['votes'] : 0,
                'candidates' => $candidates,
                'end_date' => $election->end_date,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $resultsData
        ]);
    }

    /**
     * Get AI voting pattern analysis
     */
    public function getAIPatternAnalysis(Request $request)
    {
        $days = $request->input('days', 7);
        $startDate = Carbon::now()->subDays($days);

        // Get voting patterns by hour
        $votesByHour = Vote::where('created_at', '>=', $startDate)
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // Get voting patterns by day
        $votesByDay = Vote::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get voting patterns by user (voter turnout over time)
        $voterActivity = User::where('user_type', 'voter')
            ->where('last_login_at', '>=', $startDate)
            ->selectRaw('DATE(last_login_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Analyze anomalies (simplified AI detection)
        $hourlyData = array_fill(0, 24, 0);
        foreach ($votesByHour as $item) {
            $hourlyData[$item->hour] = $item->count;
        }

        $avgVotesPerHour = array_sum($hourlyData) / 24;
        $anomalies = [];

        foreach ($hourlyData as $hour => $count) {
            if ($count > $avgVotesPerHour * 3 && $avgVotesPerHour > 0) {
                $anomalies[] = [
                    'type' => 'unusual_activity',
                    'hour' => $hour,
                    'count' => $count,
                    'threshold' => round($avgVotesPerHour * 3),
                    'description' => "Unusual voting activity detected at " . $this->formatHour($hour),
                ];
            }
        }

        // Get peak hours
        $peakHours = array_keys($hourlyData, max($hourlyData));
        $peakHour = !empty($peakHours) ? $peakHours[0] : 0;

        // Get voting velocity (votes per minute trends)
        $votesPerMinute = [];
        for ($i = 0; $i < 60; $i += 5) {
            $votesPerMinute[] = rand(0, 10); // Simplified - in real app, calculate actual
        }

        return response()->json([
            'success' => true,
            'data' => [
                'votes_by_hour' => $hourlyData,
                'votes_by_day' => $votesByDay->toArray(),
                'voter_activity' => $voterActivity->toArray(),
                'anomalies' => $anomalies,
                'peak_hour' => $peakHour,
                'peak_hour_label' => $this->formatHour($peakHour),
                'avg_votes_per_hour' => round($avgVotesPerHour, 1),
                'total_votes' => Vote::where('created_at', '>=', $startDate)->count(),
                'unique_voters' => Vote::where('created_at', '>=', $startDate)->distinct()->count('user_id'),
            ]
        ]);
    }

    /**
     * Get time-based voting graphs data
     */
    public function getTimeBasedVotingData(Request $request)
    {
        $days = $request->input('days', 7);
        $electionId = $request->input('election_id');

        $startDate = Carbon::now()->subDays($days);

        // Get votes by hour for each day
        $hourlyVotes = Vote::where('created_at', '>=', $startDate)
            ->when($electionId, function($query) use ($electionId) {
                return $query->where('election_id', $electionId);
            })
            ->selectRaw('DATE(created_at) as date, HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('date', 'hour')
            ->orderBy('date')
            ->orderBy('hour')
            ->get();

        // Group by date
        $dailyData = [];
        foreach ($hourlyVotes as $vote) {
            $date = $vote->date;
            if (!isset($dailyData[$date])) {
                $dailyData[$date] = array_fill(0, 24, 0);
            }
            $dailyData[$date][$vote->hour] = $vote->count;
        }

        // Get cumulative votes over time
        $cumulativeVotes = Vote::where('created_at', '>=', $startDate)
            ->when($electionId, function($query) use ($electionId) {
                return $query->where('election_id', $electionId);
            })
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $runningTotal = 0;
        $cumulativeData = [];
        foreach ($cumulativeVotes as $item) {
            $runningTotal += $item->count;
            $cumulativeData[] = [
                'date' => $item->date,
                'count' => $runningTotal,
            ];
        }

        // Get comparison with previous period
        $previousStartDate = $startDate->copy()->subDays($days);
        $previousVotes = Vote::where('created_at', '>=', $previousStartDate)
            ->where('created_at', '<', $startDate)
            ->count();

        $currentVotes = Vote::where('created_at', '>=', $startDate)->count();

        $comparison = [
            'previous_period' => $previousVotes,
            'current_period' => $currentVotes,
            'change' => $previousVotes > 0 ? round((($currentVotes - $previousVotes) / $previousVotes) * 100, 1) : 0,
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'daily_hourly' => $dailyData,
                'cumulative' => $cumulativeData,
                'comparison' => $comparison,
                'total_votes' => $currentVotes,
                'days_analyzed' => $days,
            ]
        ]);
    }

    /**
     * Export report to CSV
     */
    public function exportReport(Request $request)
    {
        $type = $request->input('type', 'voter_turnout');

        $filename = $type . '_report_' . date('Y-m-d') . '.csv';

        switch ($type) {
            case 'voter_turnout':
                $data = $this->getVoterTurnoutReportData();
                break;
            case 'election_results':
                $data = $this->getElectionResultsData();
                break;
            case 'ai_patterns':
                $data = $this->getAIPatternsReportData($request);
                break;
            case 'time_based':
                $data = $this->getTimeBasedReportData($request);
                break;
            default:
                $data = [];
        }

        $csvContent = $this->generateCSV($data);

        return response()->make($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Export report to PDF
     */
    public function exportReportPDF(Request $request)
    {
        $type = $request->input('type', 'voter_turnout');

        $data = [];
        $title = '';

        switch ($type) {
            case 'voter_turnout':
                $title = 'Voter Turnout Report';
                $data = $this->getVoterTurnoutReportData();
                break;
            case 'election_results':
                $title = 'Election Results Report';
                $data = $this->getElectionResultsData();
                break;
            case 'ai_patterns':
                $title = 'AI Voting Patterns Report';
                $data = $this->getAIPatternsReportData($request);
                break;
            case 'time_based':
                $title = 'Time-Based Analysis Report';
                $data = $this->getTimeBasedReportData($request);
                break;
            default:
                $title = 'Voting System Report';
                $data = $this->getVoterTurnoutReportData();
        }

        // Generate HTML for PDF
        $html = $this->generatePDFHTML($title, $data);

        // Return as downloadable HTML (can be printed to PDF via browser)
        return response($html, 200, [
            'Content-Type' => 'text/html',
            'Content-Disposition' => 'attachment; filename="' . $type . '_report_' . date('Y-m-d') . '.html"',
        ]);
    }

    /**
     * Get AI patterns report data for export
     */
    private function getAIPatternsReportData(Request $request)
    {
        $days = $request->input('days', 7);
        $startDate = Carbon::now()->subDays($days);

        $votesByHour = Vote::where('created_at', '>=', $startDate)
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        $data = [];
        $data[] = ['Hour', 'Votes Cast'];

        $hourlyData = array_fill(0, 24, 0);
        foreach ($votesByHour as $item) {
            $hourlyData[$item->hour] = $item->count;
        }

        for ($i = 0; $i < 24; $i++) {
            $data[] = [
                $this->formatHour($i),
                $hourlyData[$i]
            ];
        }

        return $data;
    }

    /**
     * Get time-based report data for export
     */
    private function getTimeBasedReportData(Request $request)
    {
        $days = $request->input('days', 7);
        $startDate = Carbon::now()->subDays($days);

        $votesByDate = Vote::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $data = [];
        $data[] = ['Date', 'Cumulative Votes'];

        $runningTotal = 0;
        foreach ($votesByDate as $item) {
            $runningTotal += $item->count;
            $data[] = [
                $item->date,
                $runningTotal
            ];
        }

        return $data;
    }

    /**
     * Generate HTML for PDF export
     */
    private function generatePDFHTML($title, $data)
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>' . $title . '</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 20px; }
                h1 { color: #004d00; border-bottom: 2px solid #FFD700; padding-bottom: 10px; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
                th { background-color: #004d00; color: white; }
                tr:nth-child(even) { background-color: #f9f9f9; }
                .header { display: flex; justify-content: space-between; margin-bottom: 20px; }
                .date { color: #666; }
                @media print {
                    body { -webkit-print-color-adjust: exact; }
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>' . $title . '</h1>
                <div class="date">Generated: ' . date('Y-m-d H:i:s') . '</div>
            </div>
            <table>
        ';

        foreach ($data as $index => $row) {
            if ($index === 0) {
                $html .= '<thead><tr>';
                foreach ($row as $cell) {
                    $html .= '<th>' . htmlspecialchars($cell) . '</th>';
                }
                $html .= '</tr></thead><tbody>';
            } else {
                $html .= '<tr>';
                foreach ($row as $cell) {
                    $html .= '<td>' . htmlspecialchars($cell) . '</td>';
                }
                $html .= '</tr>';
            }
        }

        $html .= '
            </tbody>
            </table>
            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; color: #666; font-size: 12px;">
                <p>Real-Time Voting System - Reports & Analytics</p>
            </div>
        </body>
        </html>
        ';

        return $html;
    }

    /**
     * Get voter turnout data for export
     */
    private function getVoterTurnoutReportData()
    {
        $elections = Election::with('votes')->get();
        $totalVoters = User::where('user_type', 'voter')->count();

        $data = [];
        $data[] = ['Election Title', 'Type', 'Status', 'Total Voters', 'Votes Cast', 'Turnout Rate (%)', 'Start Date', 'End Date'];

        foreach ($elections as $election) {
            $votesCast = $election->votes->count();
            $turnoutRate = $totalVoters > 0 ? round(($votesCast / $totalVoters) * 100, 1) : 0;

            $data[] = [
                $election->title,
                $election->type,
                $election->status,
                $totalVoters,
                $votesCast,
                $turnoutRate,
                $election->start_date,
                $election->end_date,
            ];
        }

        return $data;
    }

    /**
     * Get election results data for export
     */
    private function getElectionResultsData()
    {
        $elections = Election::with(['candidates' => function($query) {
            $query->orderBy('vote_count', 'desc');
        }])->get();

        $data = [];
        $data[] = ['Election Title', 'Candidate Name', 'Votes', 'Percentage (%)'];

        foreach ($elections as $election) {
            $totalVotes = $election->candidates->sum('vote_count');

            foreach ($election->candidates as $candidate) {
                $percentage = $totalVotes > 0 ? round(($candidate->vote_count / $totalVotes) * 100, 1) : 0;

                $data[] = [
                    $election->title,
                    $candidate->name,
                    $candidate->vote_count,
                    $percentage,
                ];
            }
        }

        return $data;
    }

    /**
     * Generate CSV content from data array
     */
    private function generateCSV($data)
    {
        $csv = '';
        foreach ($data as $row) {
            $csv .= implode(',', array_map(function($item) {
                return '"' . str_replace('"', '""', $item) . '"';
            }, $row)) . "\n";
        }
        return $csv;
    }
}
