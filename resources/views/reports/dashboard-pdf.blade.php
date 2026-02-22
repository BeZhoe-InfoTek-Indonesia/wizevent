<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Executive Report</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.6; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { margin: 0; color: #1a1a1a; text-transform: uppercase; letter-spacing: 2px; }
        .header p { margin: 5px 0 0; color: #666; font-style: italic; }
        
        .section { margin-bottom: 40px; }
        .section-title { font-size: 18px; font-weight: bold; border-left: 5px solid #36A2EB; padding-left: 10px; margin-bottom: 20px; background: #f9f9f9; padding-top: 5px; padding-bottom: 5px; }
        
        .stats-grid { display: table; width: 100%; border-collapse: separate; border-spacing: 15px 0; margin-left: -15px; margin-right: -15px; }
        .stat-card { display: table-cell; background: #fff; border: 1px solid #ddd; padding: 15px; text-align: center; border-radius: 8px; width: 25%; }
        .stat-value { font-size: 24px; font-weight: bold; color: #36A2EB; margin-bottom: 5px; }
        .stat-label { font-size: 12px; color: #666; text-transform: uppercase; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #f2f2f2; color: #333; text-align: left; padding: 12px; border: 1px solid #ddd; font-size: 13px; }
        td { padding: 12px; border: 1px solid #ddd; font-size: 13px; }
        tr:nth-child(even) { background-color: #fafafa; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
        
        .chart-placeholder { background: #f5f5f5; border: 1px dashed #ccc; height: 100px; text-align: center; line-height: 100px; color: #888; margin-top: 10px; font-style: italic; }
        
        .badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; color: #fff; }
        .badge-info { background-color: #36A2EB; }
        .badge-success { background-color: #4BC0C0; }
        .badge-warning { background-color: #FF9F40; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Executive Dashboard Report</h1>
        <p>Generated on {{ now()->format('F d, Y - H:i') }}</p>
    </div>

    <div class="section">
        <div class="section-title">Operational Summary</div>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">{{ $stats['total_users'] }}</div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $stats['total_events'] }}</div>
                <div class="stat-label">Total Events</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $stats['total_roles'] }}</div>
                <div class="stat-label">Active Roles</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $stats['total_activities'] }}</div>
                <div class="stat-label">System Logs</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Financial Planning Overview</div>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">Rp {{ number_format($financials['total_budget_target'], 0, ',', '.') }}</div>
                <div class="stat-label">Total Budget Target</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">Rp {{ number_format($financials['total_revenue_target'], 0, ',', '.') }}</div>
                <div class="stat-label">Total Revenue Target</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" style="color: {{ $financials['projected_profit'] >= 0 ? '#4BC0C0' : '#FF6384' }}">
                    Rp {{ number_format($financials['projected_profit'], 0, ',', '.') }}
                </div>
                <div class="stat-label">Projected Net Profit</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Event Status Distribution</div>
        <table>
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Count</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                @foreach($events_by_status as $status)
                <tr>
                    <td>{{ ucfirst($status->status) }}</td>
                    <td>{{ $status->total }}</td>
                    <td>{{ number_format(($status->total / $stats['total_events']) * 100, 1) }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">User Role Distribution</div>
        <table>
            <thead>
                <tr>
                    <th>Role</th>
                    <th>Users Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users_by_role as $role)
                <tr>
                    <td>{{ $role->name }}</td>
                    <td>{{ $role->users_count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Latest System Activities</div>
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Action</th>
                    <th>Log Name</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach($latest_activities as $activity)
                <tr>
                    <td>{{ $activity->causer->name ?? 'System' }}</td>
                    <td>{{ $activity->description }}</td>
                    <td><span class="badge badge-info">{{ $activity->log_name }}</span></td>
                    <td>{{ $activity->created_at->format('Y-m-d H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        Event Management System - Confidential Managerial Report - Page 1
    </div>
</body>
</html>
