@extends('student.dashboard') @section('page_title', 'My Attendance & Bunk Analysis')

@section('content')
<style>
    .att-wrapper {
        --bg-color: #100b1f;
        --card-bg: #1c162e;
        --primary: #b3a1ff;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --text: #ebe1fe;
        --text-dim: #afa7c2;
        font-family: 'Inter', sans-serif;
        color: var(--text);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 25px;
        margin-top: 20px;
    }

    .subject-card {
        background: var(--card-bg);
        border: 1px solid #2f2747;
        border-radius: 20px;
        padding: 25px;
        transition: transform 0.3s, box-shadow 0.3s;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .subject-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        border-color: var(--primary);
    }

    .sub-title {
        font-size: 1.2rem;
        font-weight: 800;
        margin-bottom: 5px;
        color: var(--primary);
        text-align: center;
        width: 100%;
    }
    
    .sub-code {
        font-size: 0.85rem;
        color: var(--text-dim);
        margin-bottom: 20px;
        text-transform: uppercase;
        letter-spacing: 2px;
        text-align: center;
    }

    /* ── Cool Donut Chart Styles ── */
    .chart-container {
        position: relative;
        width: 160px;
        height: 160px;
        margin-bottom: 25px;
    }

    .circular-chart {
        display: block;
        margin: 0 auto;
        max-width: 100%;
        max-height: 250px;
    }

    .circle-bg {
        fill: none;
        stroke: #151026;
        stroke-width: 3.8;
    }

    .circle {
        fill: none;
        stroke-width: 2.8;
        stroke-linecap: round;
        transition: stroke-dasharray 1.5s ease-out;
    }

    .percentage-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 2rem;
        font-weight: 800;
    }

    .numbers-flex {
        display: flex;
        justify-content: space-between;
        width: 100%;
        background: #151026;
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 20px;
    }

    .num-box { text-align: center; flex: 1; }
    .num-val { font-size: 1.4rem; font-weight: 800; color: var(--text); }
    .num-label { font-size: 0.7rem; color: var(--text-dim); text-transform: uppercase; letter-spacing: 1px; margin-top: 4px; }
    .divider { width: 1px; background: #2f2747; margin: 0 10px; }

    .bunk-box {
        width: 100%;
        padding: 15px;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 700;
        text-align: center;
        border: 2px dashed;
    }

    .safe { background: rgba(16, 185, 129, 0.1); color: var(--success); border-color: var(--success); }
    .danger { background: rgba(239, 68, 68, 0.1); color: var(--danger); border-color: var(--danger); }
    .neutral { background: rgba(175, 167, 194, 0.1); color: var(--warning); border-color: var(--warning); }
</style>

<div class="att-wrapper">
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 2.5rem; font-weight: 900; letter-spacing: -1px; margin-bottom: 8px;">Attendance Analytics</h2>
        <p style="color: var(--text-dim); font-size: 1.1rem;">Real-time tracking and smart bunk predictions.</p>
    </div>

    @if($attendanceStats->isEmpty())
        <div style="text-align: center; padding: 60px 20px; background: var(--card-bg); border-radius: 20px; border: 1px dashed #4b455c;">
            <span class="material-symbols-outlined" style="font-size: 4rem; color: var(--text-dim); margin-bottom: 15px;">history_toggle_off</span>
            <h3 style="font-size: 1.5rem; margin-bottom: 10px;">No Records Found</h3>
            <p style="color: var(--text-dim);">Teachers haven't uploaded attendance for your subjects yet.</p>
        </div>
    @else
        <div class="stats-grid">
            @foreach($attendanceStats as $stat)
                @php
                    $total = $stat->total_classes;
                    $present = $stat->present_classes;
                    $percentage = $total > 0 ? round(($present / $total) * 100) : 0;
                    
                    $target = 75;
                    $bunkMsg = "";
                    $bunkClass = "";
                    $color = "";

                    if ($total == 0) {
                        $bunkMsg = "Classes not started yet.";
                        $bunkClass = "neutral";
                        $color = "#afa7c2";
                    } elseif ($percentage >= $target) {
                        $canBunk = floor(($present / 0.75) - $total);
                        if ($canBunk > 0) {
                            $bunkMsg = "Safe Zone! You can skip $canBunk upcoming class(es). 😎";
                            $bunkClass = "safe";
                            $color = "#10b981"; // Green
                        } else {
                            $bunkMsg = "On the edge! Don't bunk the next class. ⚠️";
                            $bunkClass = "neutral";
                            $color = "#f59e0b"; // Warning Orange
                        }
                    } else {
                        $needToAttend = ceil(((0.75 * $total) - $present) / 0.25);
                        $bunkMsg = "Shortage! Attend next $needToAttend class(es) to hit $target%. 🚨";
                        $bunkClass = "danger";
                        $color = "#ef4444"; // Red
                    }
                @endphp

                <div class="subject-card">
                    <div class="sub-title">{{ optional($stat->subject)->name ?? 'Unknown Subject' }}</div>
                    <div class="sub-code">Code: {{ optional($stat->subject)->code ?? 'N/A' }}</div>

                    <div class="chart-container">
                        <svg viewBox="0 0 36 36" class="circular-chart">
                            <path class="circle-bg"
                                d="M18 2.0845
                                  a 15.9155 15.9155 0 0 1 0 31.831
                                  a 15.9155 15.9155 0 0 1 0 -31.831"
                            />
                            <path class="circle"
                                stroke-dasharray="{{ $percentage }}, 100"
                                d="M18 2.0845
                                  a 15.9155 15.9155 0 0 1 0 31.831
                                  a 15.9155 15.9155 0 0 1 0 -31.831"
                                style="stroke: {{ $color }};"
                            />
                        </svg>
                        <div class="percentage-text" style="color: {{ $color }};">{{ $percentage }}<span style="font-size: 1rem;">%</span></div>
                    </div>

                    <div class="numbers-flex">
                        <div class="num-box">
                            <div class="num-val">{{ $total }}</div>
                            <div class="num-label">Conducted</div>
                        </div>
                        <div class="divider"></div>
                        <div class="num-box">
                            <div class="num-val" style="color: var(--primary);">{{ $present }}</div>
                            <div class="num-label">Attended</div>
                        </div>
                    </div>

                    <div class="bunk-box {{ $bunkClass }}">
                        {{ $bunkMsg }}
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection