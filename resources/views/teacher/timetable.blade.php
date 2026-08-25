@extends('teacher.layout')

@section('page_title', 'Personal Teaching Schedule')

@section('content')
<style>
    /* Brutalist Styles adapted for Dark Teacher Panel */
    .brutalist-title {
        font-size: 2.2rem; font-weight: 900; text-transform: uppercase;
        border: 6px solid #b3a1ff; display: inline-block; padding: 10px 20px;
        background: #1c162e; box-shadow: 8px 8px 0px #b3a1ff; margin-bottom: 30px;
        color: #fff;
    }
    .brutalist-card {
        background: #1c162e; border: 4px solid #4b455c; box-shadow: 6px 6px 0px #4b455c;
        padding: 15px; margin-bottom: 25px; position: relative;
        color: #fff;
    }
    .live-now {
        background: #ff6e84; color: #000; padding: 5px 15px; font-weight: 900;
        position: absolute; top: -15px; right: 20px; border: 3px solid #fff;
        animation: pulse 1.5s infinite;
        z-index: 10;
    }
    @keyframes pulse {
        0% { transform: scale(1); box-shadow: 0 0 0 0px rgba(255,110,132,0.7); }
        70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(255,110,132,0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0px rgba(255,110,132,0); }
    }
    .tabs-flex { display: flex; gap: 10px; margin-bottom: 30px; overflow-x: auto; padding-bottom: 10px; }
    .tab-btn {
        background: #151026; border: 3px solid #b3a1ff; padding: 10px 20px;
        font-weight: 800; cursor: pointer; text-transform: uppercase;
        color: #b3a1ff; box-shadow: 4px 4px 0px #b3a1ff;
        white-space: nowrap;
    }
    .tab-btn.active { background: #b3a1ff; color: #000; transform: translate(2px, 2px); box-shadow: 2px 2px 0px #fff; }
    
    .time-slot { font-size: 1.5rem; font-weight: 900; margin-bottom: 10px; color: #b3a1ff; }
    .subject-info { font-size: 1.2rem; font-weight: 700; color: #ebe1fe; }
    .meta-info { margin-top: 15px; display: flex; gap: 20px; font-weight: 600; color: #afa7c2; border-top: 2px dashed #4b455c; padding-top: 10px; }
    
    .day-section { display: none; }
    .day-section.active { display: block; }
    
    @media (max-width: 600px) {
        .brutalist-title { font-size: 1.5rem; width: 100%; box-sizing: border-box; text-align: center; }
        .time-slot { font-size: 1.2rem; }
        .meta-info { flex-direction: column; gap: 5px; }
    }
</style>

<div class="max-w-4xl">
    <div class="brutalist-title">TEACHING SCHEDULE</div>
    <p style="font-weight: 800; margin-bottom: 30px; color: #afa7c2;">FACULTY: {{ strtoupper($teacherName) }}</p>

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
            <h2 style="font-weight: 900; text-transform: uppercase; margin-bottom: 20px; color: #ebe1fe;">{{ $day }}'s Classes</h2>
            
            @php
                $daySlots = isset($timetables[$day]) ? $timetables[$day]->sortBy('start_time') : collect();
            @endphp

            @if($daySlots->isEmpty())
                <div class="brutalist-card" style="text-align: center; padding: 40px;">
                    <h3 style="font-weight: 800; color: #afa7c2;">NO CLASSES SCHEDULED FOR {{ strtoupper($day) }}</h3>
                </div>
            @else
                @foreach($daySlots as $slot)
                    @php
                        $start = \Carbon\Carbon::createFromFormat('H:i:s', $slot->start_time, 'Asia/Kolkata');
                        $end = \Carbon\Carbon::createFromFormat('H:i:s', $slot->end_time, 'Asia/Kolkata');
                        $isLive = ($day == $today && $currentTime->between($start, $end));
                    @endphp
                    <div class="brutalist-card" style="{{ $isLive ? 'border-color: #ff6e84; box-shadow: 10px 10px 0px #ff6e84;' : '' }}">
                        @if($isLive)
                            <div class="live-now">ONGOING SESSION</div>
                        @endif
                        
                        <div class="time-slot">{{ $start->format('h:i A') }} - {{ $end->format('h:i A') }}</div>
                        <div class="subject-info">
                            {{ strtoupper($slot->subject_name) }} 
                            @if($slot->subject_code) 
                                <span style="background: #b3a1ff; color: #000; padding: 2px 8px; font-size: 0.9rem; margin-left: 10px; font-weight: 900;">{{ $slot->subject_code }}</span>
                            @endif
                        </div>
                        
                        <div class="meta-info">
                            <span>🎓 SEM: {{ $slot->semester }}</span>
                            <span>🏛️ BRANCH: {{ $slot->branch }}</span>
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
