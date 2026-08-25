@extends('admin.layouts.app')

@section('title', 'Schedule Timetable - Brutalist System')
@section('header_title', 'Weekly Schedule Creator')

@section('content')
<style>
    .brutalist-card {
        background: #fff;
        border: 4px solid #000;
        box-shadow: 10px 10px 0px #000;
        padding: 30px;
        margin-bottom: 40px;
    }
    .brutalist-input {
        border: 3px solid #000;
        padding: 12px;
        font-weight: bold;
        width: 100%;
        margin-bottom: 10px;
        outline: none;
    }
    .brutalist-input:focus {
        background: #f0f0f0;
        box-shadow: 4px 4px 0px #000;
    }
    .day-section {
        border-top: 4px solid #000;
        padding-top: 20px;
        margin-top: 30px;
    }
    .day-title {
        background: #000;
        color: #fff;
        display: inline-block;
        padding: 5px 20px;
        font-weight: 900;
        text-transform: uppercase;
        margin-bottom: 20px;
        transform: rotate(-1deg);
    }
    .slot-row {
        display: grid;
        grid-template-columns: 1fr 1fr 2fr 1.5fr 1.5fr 1fr auto;
        gap: 15px;
        background: #fff;
        border: 2px solid #000;
        padding: 15px;
        margin-bottom: 15px;
        align-items: end;
    }
    .btn-add {
        background: #000;
        color: #fff;
        border: none;
        padding: 10px 20px;
        font-weight: 800;
        cursor: pointer;
        transition: 0.2s;
    }
    .btn-add:hover {
        transform: translate(-3px, -3px);
        box-shadow: 5px 5px 0px #333;
    }
    .btn-remove {
        background: #ff4d4d;
        color: #fff;
        border: 2px solid #000;
        padding: 5px 10px;
        font-weight: bold;
        cursor: pointer;
    }
    .semester-badge {
        font-size: 2rem;
        font-weight: 900;
        border: 5px solid #000;
        padding: 10px 20px;
        display: inline-block;
        margin-bottom: 20px;
    }
</style>

<div class="container">
    @if(session('success'))
        <div class="brutalist-card" style="background: #d4ffda; color: #000;">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.timetable.store') }}" method="POST">
        @csrf
        <div class="brutalist-card">
            <h1 style="font-weight: 900; text-transform: uppercase; margin-bottom: 10px;">Select Semester</h1>
            <select name="semester" class="brutalist-input" style="font-size: 1.5rem;" required onchange="window.location.href = '?semester=' + this.value">
                <option value="">-- Choose Semester --</option>
                @for($i=1; $i<=6; $i++)
                    <option value="{{ $i }}" {{ request('semester') == $i ? 'selected' : '' }}>SEMESTER {{ $i }}</option>
                @endfor
            </select>
            <p style="margin-top: 10px; font-weight: bold;">Department: <span style="text-decoration: underline;">{{ $branch }}</span></p>
        </div>

        @if(request('semester'))
            <div class="brutalist-card">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="semester-badge">SEM {{ request('semester') }}</div>
                    <a href="{{ route('admin.timetable.print', request('semester')) }}" class="btn" style="background: #fff; border: 4px solid #000; box-shadow: 5px 5px 0px #000; padding: 15px 30px; font-weight: 900; text-decoration: none; color: #000;">🖨️ PRINT VIEW</a>
                </div>

                @php
                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                @endphp

                @foreach($days as $day)
                    <div class="day-section" id="section-{{ $day }}">
                        <div class="day-title">{{ $day }}</div>
                        <div id="slots-container-{{ $day }}">
                            @php
                                $daySlots = isset($existingTimetables[request('semester')][$day]) ? $existingTimetables[request('semester')][$day] : [null];
                            @endphp
                            
                            @foreach($daySlots as $index => $existingSlot)
                                <div class="slot-row">
                                    <div>
                                        <label>Start</label>
                                        <input type="time" name="slots[{{ $day }}][{{ $index }}][start_time]" value="{{ $existingSlot ? \Carbon\Carbon::parse($existingSlot->start_time)->format('H:i') : '' }}" class="brutalist-input" required>
                                    </div>
                                    <div>
                                        <label>End</label>
                                        <input type="time" name="slots[{{ $day }}][{{ $index }}][end_time]" value="{{ $existingSlot ? \Carbon\Carbon::parse($existingSlot->end_time)->format('H:i') : '' }}" class="brutalist-input" required>
                                    </div>
                                    <div>
                                        <label>Subject Name</label>
                                        <input type="text" name="slots[{{ $day }}][{{ $index }}][subject_name]" value="{{ $existingSlot->subject_name ?? '' }}" class="brutalist-input" placeholder="e.g. Data Structures" required>
                                    </div>
                                    <div>
                                        <label>Code</label>
                                        <input type="text" name="slots[{{ $day }}][{{ $index }}][subject_code]" value="{{ $existingSlot->subject_code ?? '' }}" class="brutalist-input" placeholder="CS-101">
                                    </div>
                                    <div>
                                        <label>Teacher</label>
                                        <input type="text" name="slots[{{ $day }}][{{ $index }}][teacher_name]" value="{{ $existingSlot->teacher_name ?? '' }}" class="brutalist-input" placeholder="Mr. Sharma" required>
                                    </div>
                                    <div>
                                        <label>Room</label>
                                        <input type="text" name="slots[{{ $day }}][{{ $index }}][room_no]" value="{{ $existingSlot->room_no ?? '' }}" class="brutalist-input" placeholder="Lab-1" required>
                                    </div>
                                    <button type="button" class="btn-remove" onclick="this.parentElement.remove()">✕</button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn-add" onclick="addSlot('{{ $day }}')">+ ADD SLOT FOR {{ strtoupper($day) }}</button>
                    </div>
                @endforeach

                <div style="margin-top: 50px; text-align: center;">
                    <button type="submit" style="background: #00ff00; border: 5px solid #000; padding: 20px 60px; font-size: 1.5rem; font-weight: 900; cursor: pointer; box-shadow: 10px 10px 0px #000;">💾 SAVE TIMETABLE</button>
                </div>
            </div>
        @else
            <div class="brutalist-card" style="text-align: center; padding: 60px;">
                <h2 style="font-weight: 900;">SELECT A SEMESTER ABOVE TO BEGIN SCHEDULING</h2>
            </div>
        @endif
    </form>
</div>

<script>
    function addSlot(day) {
        const container = document.getElementById('slots-container-' + day);
        const index = container.children.length;
        const html = `
            <div class="slot-row">
                <div>
                    <label>Start</label>
                    <input type="time" name="slots[${day}][${index}][start_time]" class="brutalist-input" required>
                </div>
                <div>
                    <label>End</label>
                    <input type="time" name="slots[${day}][${index}][end_time]" class="brutalist-input" required>
                </div>
                <div>
                    <label>Subject Name</label>
                    <input type="text" name="slots[${day}][${index}][subject_name]" class="brutalist-input" placeholder="e.g. Data Structures" required>
                </div>
                <div>
                    <label>Code</label>
                    <input type="text" name="slots[${day}][${index}][subject_code]" class="brutalist-input" placeholder="CS-101">
                </div>
                <div>
                    <label>Teacher</label>
                    <input type="text" name="slots[${day}][${index}][teacher_name]" class="brutalist-input" placeholder="Mr. Sharma" required>
                </div>
                <div>
                    <label>Room</label>
                    <input type="text" name="slots[${day}][${index}][room_no]" class="brutalist-input" placeholder="Lab-1" required>
                </div>
                <button type="button" class="btn-remove" onclick="this.parentElement.remove()">✕</button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }
</script>
@endsection
