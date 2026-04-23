@extends('admin.layouts.app')

@section('title', 'Timetable Setup - Dynamic Grid')
@section('header_title', 'Dynamic Time-Grid Generator')

@section('content')
<style>
    .brutalist-card {
        background: #fff;
        border: 4px solid #000;
        box-shadow: 10px 10px 0px #000;
        padding: 25px;
        margin-bottom: 30px;
    }
    .brutalist-input {
        border: 3px solid #000;
        padding: 12px;
        font-weight: bold;
        width: 100%;
        margin-bottom: 15px;
        outline: none;
    }
    .brutalist-input:focus {
        background: #f0f0f0;
        box-shadow: 4px 4px 0px #000;
    }
    .btn-generate {
        background: #000;
        color: #fff;
        border: none;
        padding: 15px 25px;
        font-weight: 900;
        text-transform: uppercase;
        cursor: pointer;
        width: 100%;
        font-size: 1rem;
        transition: 0.2s;
    }
    .grid-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 15px;
    }
    
    @media (max-width: 768px) {
        .brutalist-card {
            padding: 15px;
            box-shadow: 5px 5px 0px #000;
        }
        .grid-container {
            grid-template-columns: 1fr;
        }
        h2 { font-size: 1.2rem; }
    }
    .slot-box {
        border: 3px solid #000;
        padding: 15px;
        background: #fff;
        position: relative;
    }
    .slot-time {
        background: #000;
        color: #fff;
        padding: 5px 10px;
        font-weight: bold;
        display: inline-block;
        margin-bottom: 10px;
    }
    .lunch-divider {
        grid-column: 1 / -1;
        background: #ffeb3b;
        border: 3px solid #000;
        padding: 10px;
        text-align: center;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 5px;
    }
    .day-header {
        grid-column: 1 / -1;
        background: #000;
        color: #fff;
        padding: 10px 20px;
        font-weight: 900;
        text-transform: uppercase;
        margin-top: 30px;
        font-size: 1.5rem;
    }
</style>

<div class="container">
    @if(session('success'))
        <div class="brutalist-card" style="background: #00ff00; color: #000;">
            <b>{{ session('success') }}</b>
        </div>
    @endif

    <!-- Part 0: Current Active Timetables -->
    <div class="brutalist-card" style="background: #f0f0f0;">
        <h2 style="font-weight: 900; margin-bottom: 20px; text-transform: uppercase;">📦 Saved Timetables ({{ $branch }})</h2>
        @if($savedSemesters->isEmpty())
            <p style="font-weight: 800; color: #666;">No timetables scheduled yet.</p>
        @else
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; background: #fff;">
                    <thead>
                        <tr style="background: #000; color: #fff;">
                            <th style="padding: 15px; border: 2px solid #000; text-align: left;">SEMESTER</th>
                            <th style="padding: 15px; border: 2px solid #000; text-align: left;">LAST UPDATED</th>
                            <th style="padding: 15px; border: 2px solid #000; text-align: center;">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($savedSemesters as $sem)
                            <tr>
                                <td style="padding: 15px; border: 2px solid #000; font-weight: 900;">Semester {{ $sem }}</td>
                                <td style="padding: 15px; border: 2px solid #000; font-weight: 700;">
                                    {{ \App\Models\Timetable::where('branch', $branch)->where('semester', $sem)->latest()->first()->updated_at->diffForHumans() }}
                                </td>
                                <td style="padding: 15px; border: 2px solid #000; text-align: center;">
                                    <a href="?semester={{ $sem }}" class="btn" style="background: #7a53ff; color: #fff; border: 2px solid #000; padding: 5px 15px; text-decoration: none; font-weight: 900; box-shadow: 3px 3px 0px #000; margin-right: 10px;">EDIT</a>
                                    <a href="{{ route('admin.timetable.print', $sem) }}" target="_blank" class="btn" style="background: #000; color: #fff; border: 2px solid #000; padding: 5px 15px; text-decoration: none; font-weight: 900; box-shadow: 3px 3px 0px #000;">PRINT PDF</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Step 1: Configuration Panel -->
    <div class="brutalist-card">
        <h2 style="font-weight: 900; margin-bottom: 20px; text-transform: uppercase;">Step 1: Shift Configuration</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px;">
            <div>
                <label style="font-weight: 800; font-size: 0.8rem;">Start Time</label>
                <input type="time" id="start_time" value="10:00" class="brutalist-input">
            </div>
            <div>
                <label style="font-weight: 800; font-size: 0.8rem;">Class Mins</label>
                <input type="number" id="class_duration" value="60" class="brutalist-input">
            </div>
            <div>
                <label style="font-weight: 800; font-size: 0.8rem;">Pre-Lunch</label>
                <input type="number" id="classes_before_lunch" value="3" class="brutalist-input">
            </div>
            <div>
                <label style="font-weight: 800; font-size: 0.8rem;">Lunch Mins</label>
                <input type="number" id="lunch_duration" value="45" class="brutalist-input">
            </div>
            <div>
                <label style="font-weight: 800; font-size: 0.8rem;">Post-Lunch</label>
                <input type="number" id="classes_after_lunch" value="3" class="brutalist-input">
            </div>
            <div>
                <label style="font-weight: 800; font-size: 0.8rem;">Default Room</label>
                <input type="text" id="global_room" placeholder="Lab-1" class="brutalist-input" oninput="syncDefaultRoom(this.value)">
            </div>
        </div>

        <div style="margin-top: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="font-weight: 800;">Select Semester</label>
                <div style="display: flex; gap: 10px;">
                    <select id="semester_select" class="brutalist-input" onchange="fetchSubjects(this.value)">
                        <option value="">-- Choose Semester --</option>
                        @for($i=1; $i<=6; $i++)
                            <option value="{{ $i }}" {{ request('semester') == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                        @endfor
                    </select>
                    <button type="button" onclick="openPrintView()" id="print_btn" class="brutalist-input" style="width: auto; background: #fff; cursor: pointer; display: {{ request('semester') ? 'block' : 'none' }};">🖨️ PRINT VIEW</button>
                </div>
            </div>
            <div style="display: flex; align-items: end;">
                <button type="button" class="btn-generate" onclick="generateWeekGrid()">⚡ {{ request('semester') ? 'Load / Reset Grid' : 'Generate Week Grid' }}</button>
            </div>
        </div>
    </div>

    <!-- Step 1.5: Subject-Teacher Allocation -->
    <div id="allocation-card" class="brutalist-card" style="display: none; border-color: #7a53ff;">
        <h2 style="font-weight: 900; margin-bottom: 20px; text-transform: uppercase;">Step 2: Subject-Teacher Allocation</h2>
        <p style="font-weight: 700; color: #555; margin-bottom: 20px;">Assign a teacher to each subject ONCE. We'll automatically apply this to the grid.</p>
        <div id="allocation-container" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 15px;">
            <!-- Dynamic allocations here -->
        </div>
    </div>

    <!-- Step 2: Generated Grid -->
    <form action="{{ route('admin.timetable.store') }}" method="POST">
        @csrf
        <input type="hidden" name="semester" id="hidden_semester" value="{{ request('semester') }}">
        <div id="mapping-container"></div> <!-- Hidden mapping inputs will go here -->
        
        <div id="timetable-container" class="grid-container">
            <!-- JS will inject grid here -->
        </div>

        <div id="submit-section" style="display: none; margin-top: 50px; text-align: center;">
            <button type="submit" onclick="prepareMapping()" style="background: #00ff00; border: 5px solid #000; padding: 20px 60px; font-size: 1.5rem; font-weight: 900; cursor: pointer; box-shadow: 10px 10px 0px #000;">🚀 DEPLOY TIMETABLE</button>
        </div>
    </form>
</div>

<script>
    let subjectsData = [];
    const teachersList = @json($teachers);
    const existingData = @json($existingData);

    // Initial load if semester exists in URL
    window.onload = () => {
        const sem = document.getElementById('semester_select').value;
        if(sem) fetchSubjects(sem);
    };

    function syncDefaultRoom(val) {
        document.querySelectorAll('.slot-room-input').forEach(input => {
            if(!input.dataset.manuallyEdited) {
                input.value = val;
            }
        });
    }

    async function fetchSubjects(semester) {
        if (!semester) {
            document.getElementById('print_btn').style.display = 'none';
            return;
        }
        document.getElementById('hidden_semester').value = semester;
        document.getElementById('print_btn').style.display = 'block';
        
        try {
            const response = await fetch(`{{ route('admin.timetable.getSubjects') }}?semester=${semester}`);
            subjectsData = await response.json();
            renderAllocationCard();
        } catch (error) {
            console.error('Error fetching subjects:', error);
        }
    }

    function openPrintView() {
        const semester = document.getElementById('semester_select').value;
        if (semester) {
            window.open(`{{ url('/admin/timetable/print') }}/${semester}`, '_blank');
        }
    }

    function renderAllocationCard() {
        const container = document.getElementById('allocation-container');
        container.innerHTML = '';
        
        subjectsData.forEach(s => {
            // Try to find a teacher already assigned to this subject in existingData
            let savedTeacher = '';
            for (let day in existingData) {
                let match = existingData[day].find(slot => slot.subject_name === s.name);
                if (match) {
                    savedTeacher = match.teacher_name;
                    break;
                }
            }

            let teacherOptions = `<option value="">-- Choose Teacher --</option>`;
            teachersList.forEach(t => {
                teacherOptions += `<option value="${t}" ${t === savedTeacher ? 'selected' : ''}>${t}</option>`;
            });

            container.insertAdjacentHTML('beforeend', `
                <div style="border: 2px solid #000; padding: 10px; background: #f9f9f9;">
                    <div style="font-weight: 900; margin-bottom: 5px;">${s.name} (${s.code})</div>
                    <select class="brutalist-input allocation-select" data-subject="${s.name}" style="margin-bottom: 0; padding: 8px;">
                        ${teacherOptions}
                    </select>
                </div>
            `);
        });
        
        document.getElementById('allocation-card').style.display = 'block';
    }

    function prepareMapping() {
        const mappingContainer = document.getElementById('mapping-container');
        mappingContainer.innerHTML = '';
        
        document.querySelectorAll('.allocation-select').forEach(select => {
            const subject = select.dataset.subject;
            const teacher = select.value;
            mappingContainer.insertAdjacentHTML('beforeend', `
                <input type="hidden" name="allocation[${subject}]" value="${teacher}">
            `);
        });
    }

    function timeToMinutes(timeStr) {
        if(!timeStr) return 0;
        const [hrs, mins] = timeStr.split(':').map(Number);
        return hrs * 60 + mins;
    }

    function minutesToTime(totalMinutes) {
        let hrs = Math.floor(totalMinutes / 60);
        let mins = totalMinutes % 60;
        return `${hrs.toString().padStart(2, '0')}:${mins.toString().padStart(2, '0')}`;
    }

    function generateWeekGrid() {
        const semester = document.getElementById('semester_select').value;
        if (!semester) {
            alert('Please select a semester first!');
            return;
        }

        const container = document.getElementById('timetable-container');
        container.innerHTML = '';
        
        const startTimeStr = document.getElementById('start_time').value;
        const duration = parseInt(document.getElementById('class_duration').value);
        const beforeLunch = parseInt(document.getElementById('classes_before_lunch').value);
        const lunchDuration = parseInt(document.getElementById('lunch_duration').value);
        const afterLunch = parseInt(document.getElementById('classes_after_lunch').value);
        const defaultRoom = document.getElementById('global_room').value;

        const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        days.forEach(day => {
            container.insertAdjacentHTML('beforeend', `<div class="day-header">${day}</div>`);
            let currentMinutes = timeToMinutes(startTimeStr);

            for (let i = 0; i < beforeLunch; i++) {
                container.insertAdjacentHTML('beforeend', createSlotHtml(day, currentMinutes, duration, defaultRoom));
                currentMinutes += duration;
            }

            container.insertAdjacentHTML('beforeend', `<div class="lunch-divider">🍱 LUNCH</div>`);
            currentMinutes += lunchDuration;

            for (let i = 0; i < afterLunch; i++) {
                container.insertAdjacentHTML('beforeend', createSlotHtml(day, currentMinutes, duration, defaultRoom));
                currentMinutes += duration;
            }
        });

        document.getElementById('submit-section').style.display = 'block';
    }

    function createSlotHtml(day, startMinutes, duration, defaultRoom) {
        const startTime = minutesToTime(startMinutes);
        const endTime = minutesToTime(startMinutes + duration);
        
        // Find saved data for this specific time slot
        let savedSubject = '';
        let savedRoom = defaultRoom;
        
        if (existingData[day]) {
            // Check if a slot exists with roughly the same start time (matching HH:MM:00 or HH:MM)
            let match = existingData[day].find(slot => {
                let slotStart = slot.start_time.substring(0, 5); // get HH:MM
                return slotStart === startTime;
            });
            if (match) {
                savedSubject = match.subject_name;
                savedRoom = match.room_no;
            }
        }

        let subjectOptions = `<option value="">-- Subject --</option>`;
        subjectsData.forEach(s => {
            subjectOptions += `<option value="${s.name}" ${s.name === savedSubject ? 'selected' : ''}>${s.name}</option>`;
        });

        return `
            <div class="slot-box">
                <div class="slot-time">${startTime} - ${endTime}</div>
                <input type="hidden" name="slots[${day}][${startMinutes}][start_time]" value="${startTime}">
                <input type="hidden" name="slots[${day}][${startMinutes}][end_time]" value="${endTime}">
                
                <select name="slots[${day}][${startMinutes}][subject_name]" class="brutalist-input" style="padding: 10px; margin-bottom: 10px;" required tabindex="1">
                    ${subjectOptions}
                </select>
                
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-weight: 800; font-size: 0.8rem;">ROOM:</span>
                    <input type="text" name="slots[${day}][${startMinutes}][room_no]" value="${savedRoom}" 
                        class="brutalist-input slot-room-input" style="margin-bottom: 0; padding: 5px 10px;" 
                        oninput="this.dataset.manuallyEdited=true" tabindex="2">
                </div>
            </div>
        `;
    }
</script>
@endsection
