<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Timetable - Sem {{ $semester }} - {{ $branch }}</title>
    <style>
        @media print {
            .no-print { display: none; }
            body { padding: 0; background: #fff !important; }
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            padding: 40px;
            background: #f0f0f0;
            color: #000;
        }
        .print-container {
            background: #fff;
            border: 4px solid #000;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        header {
            text-align: center;
            border-bottom: 4px solid #000;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        h1 { margin: 0; font-size: 2.5rem; text-transform: uppercase; font-weight: 900; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            border: 2px solid #000;
            padding: 12px;
            text-align: left;
        }
        th {
            background: #000;
            color: #fff;
            text-transform: uppercase;
        }
        .day-header {
            background: #e0e0e0;
            font-weight: 900;
            text-align: center;
            font-size: 1.2rem;
        }
        .no-print-btn {
            background: #000;
            color: #fff;
            padding: 15px 30px;
            border: none;
            font-weight: bold;
            cursor: pointer;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center;">
        <button class="no-print-btn" onclick="window.print()">🖨️ CLICK TO PRINT / SAVE AS PDF</button>
        <button class="no-print-btn" style="background: #666;" onclick="window.close()">✕ CLOSE</button>
    </div>

    <div class="print-container">
        <header>
            <h1>EDUSHARE SYSTEM</h1>
            <div style="font-size: 1.2rem; font-weight: bold; margin-top: 10px;">
                OFFICIAL WEEKLY TIMETABLE
            </div>
            <div style="margin-top: 5px;">
                BRANCH: {{ strtoupper($branch) }} | SEMESTER: {{ $semester }} | SESSION: 2026-27
            </div>
        </header>

        <table>
            <thead>
                <tr>
                    <th style="width: 150px;">DAY / TIME</th>
                    <th>SUBJECT (CODE)</th>
                    <th>TEACHER</th>
                    <th>ROOM</th>
                </tr>
            </thead>
            <tbody>
                @foreach($days as $day)
                    <tr>
                        <td colspan="4" class="day-header">{{ strtoupper($day) }}</td>
                    </tr>
                    @php
                        $daySlots = isset($timetables[$day]) ? $timetables[$day] : collect();
                    @endphp
                    @if($daySlots->isEmpty())
                        <tr>
                            <td colspan="4" style="text-align: center; color: #666; font-style: italic;">No classes scheduled</td>
                        </tr>
                    @else
                        @foreach($daySlots as $slot)
                            <tr>
                                <td style="font-weight: bold;">
                                    {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }} - 
                                    {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}
                                </td>
                                <td>{{ $slot->subject_name }} @if($slot->subject_code) ({{ $slot->subject_code }}) @endif</td>
                                <td>{{ $slot->teacher_name }}</td>
                                <td>{{ $slot->room_no }}</td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            </tbody>
        </table>

        <footer style="margin-top: 50px; display: flex; justify-content: space-between;">
            <div>
                Generated on: {{ date('d-M-Y H:i') }}<br>
                System: EduShare Timetable Engine
            </div>
            <div style="border-top: 2px solid #000; padding-top: 10px; width: 200px; text-align: center;">
                HOD SIGNATURE
            </div>
        </footer>
    </div>

    <script>
        // Auto-trigger print if needed
        // window.onload = () => { window.print(); };
    </script>
</body>
</html>
