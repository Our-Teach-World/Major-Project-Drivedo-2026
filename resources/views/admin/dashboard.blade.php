<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - EduShare</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            color: #000000;
        }

        .navbar {
            background-color: #ffffff;
            border-bottom: 2px solid #000000;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-title {
            font-weight: 700;
            font-size: 1.3rem;
        }

        .navbar-actions {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 8px 16px;
            border: 2px solid #000000;
            background-color: #ffffff;
            color: #000000;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn:hover {
            background-color: #000000;
            color: #ffffff;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        h1, h2 {
            color: #000000;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 30px;
        }

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
        }

        .actions {
            background-color: #ffffff;
            border: 2px solid #000000;
            padding: 20px;
            border-radius: 8px;
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .actions a {
            padding: 12px 24px;
            background-color: #000000;
            color: #ffffff;
            border: 2px solid #000000;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .actions a:hover {
            background-color: #ffffff;
            color: #000000;
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .charts-section {
                grid-template-columns: 1fr;
            }

            .actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-title">⚙️ Admin Dashboard</div>
        <div class="navbar-actions">
            <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <h1>Dashboard Overview</h1>

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
        </div>

        <div class="charts-section">
            <div class="chart-container">
                <h2>User Status Distribution</h2>
                <canvas id="userStatusChart" height="200"></canvas>
            </div>
            <div class="chart-container">
                <h2>Role Distribution</h2>
                <canvas id="roleChart" height="200"></canvas>
            </div>
        </div>

        <div class="actions">
            <a href="{{ route('admin.users') }}">📋 Manage Users</a>
            <a href="{{ route('admin.add-user') }}">➕ Add New User</a>
            <a href="{{ route('admin.export') }}">📊 Export Data</a>
        </div>
    </div>

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
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Role Chart
        const roleChart = new Chart(document.getElementById('roleChart'), {
            type: 'doughnut',
            data: {
                labels: ['Teachers', 'Students'],
                datasets: [{
                    data: [{{ $teachers }}, {{ $students }}],
                    backgroundColor: ['#3b82f6', '#f472b6'],
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
    </script>
</body>
</html>
