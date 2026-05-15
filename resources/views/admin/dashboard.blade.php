@extends('admin.layouts.app')

@section('title', 'Dashboard Overview - CampusCore Admin')
@section('header_title', '📊 Dashboard Overview')

@push('styles')
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background-color: #ffffff;
            border: 2px solid #000000;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 4px 4px 0px #000;
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #000000;
            margin-bottom: 10px;
        }

        .stat-label {
            font-weight: 600;
            color: #333333;
        }

        .charts-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .chart-container {
            background-color: #ffffff;
            border: 2px solid #000000;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 4px 4px 0px #000;
        }

        @media (max-width: 768px) {
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .charts-section { grid-template-columns: 1fr; }
        }
        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr; }
        }
    </style>
@endpush

@section('content')

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $totalUsers }}</div>
            <div class="stat-label">Total Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $approvedUsers }}</div>
            <div class="stat-label">Approved Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $pendingUsers }}</div>
            <div class="stat-label">Pending Approvals</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $teachers }}</div>
            <div class="stat-label">Teachers</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $students }}</div>
            <div class="stat-label">Students</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $alumni }}</div>
            <div class="stat-label">Alumni</div>
        </div>
    </div>

    <div class="charts-section">
        <div class="chart-container">
            <h2 style="margin-bottom: 15px;">User Status Distribution</h2>
            <canvas id="userStatusChart" height="200"></canvas>
        </div>
        <div class="chart-container">
            <h2 style="margin-bottom: 15px;">Role Distribution</h2>
            <canvas id="roleChart" height="200"></canvas>
        </div>
    </div>

    <!-- Staff Notice Board has been moved to a dedicated page accessible via the sidebar -->

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // User Status Chart
        const userStatusChart = new Chart(document.getElementById('userStatusChart'), {
            type: 'bar',
            data: {
                labels: ['Approved', 'Pending'],
                datasets: [{
                    label: 'Users',
                    data: [{{ $approvedUsers }}, {{ $pendingUsers }}],
                    backgroundColor: ['#4ade80', '#fbbf24'],
                    borderColor: ['#000000', '#000000'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });

        const roleChart = new Chart(document.getElementById('roleChart'), {
            type: 'doughnut',
            data: {
                labels: ['Teachers', 'Students', 'Alumni'],
                datasets: [{
                    data: [{{ $teachers }}, {{ $students }}, {{ $alumni }}],
                    backgroundColor: ['#3b82f6', '#f472b6', '#a855f7'],
                    borderColor: '#000000',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        });
    </script>
@endpush
