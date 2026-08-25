@extends('student.dashboard')

@section('page_title', 'My Timetable')

@section('content')
<style>
    /* Brutalist Styles adapted for SPA integration */
    .brutalist-title {
        font-size: 2.2rem; font-weight: 900; text-transform: uppercase;
        border: 6px solid #000; display: inline-block; padding: 10px 20px;
        background: #fff; box-shadow: 8px 8px 0px #000; margin-bottom: 30px;
        color: #000;
    }
    .brutalist-card {
        background: #fff; border: 4px solid #000; box-shadow: 6px 6px 0px #000;
        padding: 15px; margin-bottom: 20px; position: relative;
        color: #000;
    }
    .live-now {
        background: #000; color: #fff; padding: 5px 15px; font-weight: 900;
        position: absolute; top: -15px; right: 20px; border: 3px solid #000;
        animation: pulse 1.5s infinite;
        z-index: 10;
    }
    @keyframes pulse {
        0% { transform: scale(1); background: #000; }
        50% { transform: scale(1.05); background: #ff0000; }
        100% { transform: scale(1); background: #000; }
    }
    .tabs-flex { display: flex; gap: 10px; margin-bottom: 30px; overflow-x: auto; padding-bottom: 10px; }
    .tab-btn {
        background: #fff; border: 3px solid #000; padding: 10px 20px;
        font-weight: 800; cursor: pointer; text-transform: uppercase;
        box-shadow: 4px 4px 0px #000;
        color: #000;
        white-space: nowrap;
    }
    .tab-btn.active { background: #000; color: #fff; transform: translate(2px, 2px); box-shadow: 2px 2px 0px #000; }
    
    .time-slot { font-size: 1.5rem; font-weight: 900; margin-bottom: 10px; }
    .subject-info { font-size: 1.2rem; font-weight: 700; color: #333; }
    .meta-info { margin-top: 15px; display: flex; gap: 20px; font-weight: 600; color: #666; border-top: 2px dashed #000; padding-top: 10px; }
    
    .day-section { display: none; }
    .day-section.active { display: block; }

    @media (max-width: 600px) {
        .brutalist-title { font-size: 1.5rem; width: 100%; box-sizing: border-box; text-align: center; }
        .time-slot { font-size: 1.2rem; }
        .meta-info { flex-direction: column; gap: 5px; }
    }
</style>

<div class="container-fluid" style="padding: 10px 0;">
    <div class="brutalist-title">MY SCHEDULE</div>
    <p style="font-weight: 800; margin-bottom: 30px; color: #666;">BRANCH: {{ $branch }} | SEMESTER: {{ $semester }}</p>

    @php
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $currentTime = \Carbon\Carbon::now('Asia/Kolkata');
        $today = $currentTime->format('l');
        if(!in_array($today, $days)) $today = 'Monday';
    @endphp

    <div class="tabs-flex">
        @foreach($days as $day)
            <button class="tab-btn {{ $day == $today ? 'active' : '' }}" onclick="showDay('{{ $day }}', this)">{{ substr($day, 0, 3) }}</button>
        @endforeach
    </div>

    @foreach($days as $day)
        <div id="day-{{ $day }}" class="day-section {{ $day == $today ? 'active' : '' }}">
            <h2 style="font-weight: 900; text-transform: uppercase; margin-bottom: 20px; color: #000;">{{ $day }}'s Classes</h2>
            
            @php
                $daySlots = isset($timetables[$day]) ? $timetables[$day]->sortBy('start_time') : collect();
            @endphp

            @if($daySlots->isEmpty())
                <div class="brutalist-card" style="text-align: center; padding: 40px;">
                    <h3 style="font-weight: 800; color: #666;">NO CLASSES SCHEDULED FOR {{ strtoupper($day) }}</h3>
                </div>
            @else
                @foreach($daySlots as $slot)
                    @php
                        $start = \Carbon\Carbon::createFromFormat('H:i:s', $slot->start_time, 'Asia/Kolkata');
                        $end = \Carbon\Carbon::createFromFormat('H:i:s', $slot->end_time, 'Asia/Kolkata');
                        $isLive = ($day == $today && $currentTime->between($start, $end));
                    @endphp
                    <div class="brutalist-card" style="{{ $isLive ? 'border-color: #ff0000; box-shadow: 10px 10px 0px #ff0000;' : '' }}">
                        @if($isLive)
                            <div class="live-now">LIVE NOW / ONGOING</div>
                        @endif
                        
                        <div class="time-slot">{{ $start->format('h:i A') }} - {{ $end->format('h:i A') }}</div>
                        <div class="subject-info">
                            {{ strtoupper($slot->subject_name) }} 
                            @if($slot->subject_code) 
                                <span style="background: #000; color: #fff; padding: 2px 8px; font-size: 0.9rem; margin-left: 10px;">{{ $slot->subject_code }}</span>
                            @endif
                        </div>
                        
                        <div class="meta-info">
                            <span>👨‍🏫 {{ $slot->teacher_name }}</span>
                            <span>🏢 ROOM: {{ $slot->room_no }}</span>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    @endforeach
</div>

@push('scripts')
<script>
    function showDay(day, btn) {
        document.querySelectorAll('.day-section').forEach(s => s.classList.remove('active'));
        const target = document.getElementById('day-' + day);
        if(target) target.classList.add('active');
        
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }
</script>
@endpush
@endsection
