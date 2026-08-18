@extends('teacher.layout')
@section('page_title', 'Smart Attendance')

@section('content')
<style>
    /* Reset & Base variables for this scope */
    .attendance-wrapper {
        --primary-color: #7a53ff;
        --success-color: #10b981;
        --danger-color: #ef4444;
        --card-bg: #1c162e;
        --text-color: #ebe1fe;
        font-family: 'Inter', sans-serif;
        color: var(--text-color);
    }

    /* Top Controls */
    .controls-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        background: var(--card-bg);
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 24px;
        align-items: flex-end;
    }

    .input-group { display: flex; flex-direction: column; gap: 8px; flex: 1; min-width: 200px; }
    .input-group label { font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #afa7c2; }
    .input-group select, .input-group input {
        background: #151026; border: 1px solid #4b455c; padding: 12px; border-radius: 8px; color: white;
    }

    /* Bulk Actions */
    .bulk-actions {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
    }
    .action-btn {
        flex: 1;
        padding: 10px;
        border-radius: 8px;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 12px;
        cursor: pointer;
        border: 2px solid transparent;
        transition: 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .btn-all-present { background: rgba(16, 185, 129, 0.1); color: var(--success-color); border-color: var(--success-color); }
    .btn-all-absent { background: rgba(239, 68, 68, 0.1); color: var(--danger-color); border-color: var(--danger-color); }
    .action-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0,0,0,0.3); }

    /* Student List Form */
    .student-card {
        background: var(--card-bg); padding: 15px 20px; border-radius: 12px; margin-bottom: 12px;
        display: flex; justify-content: space-between; align-items: center; transition: 0.2s;
    }
    .student-card:hover { transform: translateX(5px); border-left: 4px solid var(--primary-color); }
    
    .student-info { display: flex; align-items: center; gap: 15px; }
    .avatar { width: 40px; height: 40px; background: rgba(122, 83, 255, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #b3a1ff; }
    
    /* Beautiful Custom Toggle Switch */
    .toggle-switch { position: relative; display: inline-block; width: 60px; height: 30px; }
    .toggle-switch input { opacity: 0; width: 0; height: 0; }
    .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: var(--danger-color); transition: .4s; border-radius: 30px; }
    .slider:before { position: absolute; content: ""; height: 22px; width: 22px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%; }
    
    /* Checked State = Present (Green) */
    input:checked + .slider { background-color: var(--success-color); }
    input:checked + .slider:before { transform: translateX(30px); }

    .status-label { font-size: 14px; font-weight: bold; width: 60px; text-align: center; }
    .status-present { color: var(--success-color); }
    .status-absent { color: var(--danger-color); }

    /* Sticky Footer */
    .summary-footer {
        position: sticky; bottom: 20px; background: var(--card-bg); padding: 15px 25px; border-radius: 12px;
        display: flex; justify-content: space-between; align-items: center; box-shadow: 0 -10px 20px rgba(0,0,0,0.5); border: 1px solid var(--primary-color);
    }
    .save-btn { background: var(--primary-color); color: white; border: none; padding: 12px 25px; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 16px; transition: 0.2s; }
    .save-btn:hover { background: #6c3bff; transform: scale(1.05); }
    .save-btn:disabled { background: #4b455c; cursor: not-allowed; opacity: 0.5; }

    /* Sunday Warning */
    .sunday-warning {
        background: rgba(239, 68, 68, 0.1); border: 1px solid var(--danger-color); color: var(--danger-color);
        padding: 10px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-weight: bold;
        display: none;
    }

    /* Mobile Responsiveness */
    @media (max-width: 600px) {
        .student-card { flex-direction: column; align-items: flex-start; gap: 15px; }
        .toggle-container { width: 100%; display: flex; justify-content: space-between; align-items: center; background: #151026; padding: 10px; border-radius: 8px; }
        .summary-footer { flex-direction: column; gap: 15px; text-align: center; }
        .save-btn { width: 100%; }
    }
</style>

<div class="attendance-wrapper">
    <div id="sundayNotice" class="sunday-warning">
        ⚠️ Selection Restricted: Sunday is a holiday. Attendance cannot be recorded for this day.
    </div>

    <form action="{{ route('attendance.store') }}" method="POST" id="attendanceForm">
        @csrf

        <div class="controls-container">
            <div class="input-group">
                <label>Date of Lecture</label>
                <input type="date" name="attendance_date" id="attendanceDate" value="{{ $todayDate }}" onchange="checkSunday(this.value)" required>
            </div>
            
            <div class="input-group">
                <label>Select Subject</label>
                <select name="subject_id" required>
                    <option value="">-- Choose Subject --</option>
                    @foreach($subjects as $sub)
                        <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="input-group" style="align-items: flex-end; justify-content: center;">
                <span style="font-size: 14px; color: #afa7c2;">Marking Attendance for</span>
                <strong style="color: #b3a1ff; font-size: 18px;">Semester {{ $semester }}</strong>
            </div>
        </div>

        <div class="bulk-actions">
            <button type="button" class="action-btn btn-all-present" onclick="markAll(true)">
                <span class="material-symbols-outlined">done_all</span> Mark All Present
            </button>
            <button type="button" class="action-btn btn-all-absent" onclick="markAll(false)">
                <span class="material-symbols-outlined">close_fullscreen</span> Mark All Absent
            </button>
        </div>

        <div class="student-list" id="studentList">
            @forelse($students as $student)
            <div class="student-card">
                <div class="student-info">
                    <div class="avatar">{{ strtoupper(substr($student->name ?? $student->username, 0, 1)) }}</div>
                    <div>
                        <div style="font-weight: bold; font-size: 16px;">{{ $student->name ?? $student->username }}</div>
                        <div style="font-size: 12px; color: #afa7c2;">Roll No: {{ $student->id }}</div>
                    </div>
                </div>

                <div class="toggle-container">
                    <span class="status-label status-absent" id="label-{{ $student->id }}">Absent</span>
                    
                    <input type="hidden" name="attendance[{{ $student->id }}]" value="Absent">
                    <label class="toggle-switch">
                        <input type="checkbox" name="attendance[{{ $student->id }}]" value="Present" 
                               checked class="attendance-checkbox" 
                               onchange="updateStatus(this, 'label-{{ $student->id }}')">
                        <span class="slider"></span>
                    </label>

                    <span class="status-label status-present" id="label-{{ $student->id }}-p">Present</span>
                </div>
            </div>
            @empty
            <div style="text-align: center; padding: 50px; color: #afa7c2;">
                <h3 style="margin-bottom: 10px;">No students found!</h3>
                <p>There are no students enrolled in Semester {{ $semester }}.</p>
            </div>
            @endforelse
        </div>

        @if($students->count() > 0)
        <div class="summary-footer">
            <div>
                <span style="margin-right: 20px;">Total: <strong id="totalCount">{{ $students->count() }}</strong></span>
                <span style="color: var(--success-color); margin-right: 20px;">Present: <strong id="presentCount">{{ $students->count() }}</strong></span>
                <span style="color: var(--danger-color);">Absent: <strong id="absentCount">0</strong></span>
            </div>
            <button type="submit" id="saveBtn" class="save-btn" onclick="this.innerHTML='Saving...'; this.style.opacity='0.7';">
                Save Attendance
            </button>
        </div>
        @endif
    </form>
</div>

<script>
    // JS for real-time Present/Absent counter
    function updateCounters() {
        const checkboxes = document.querySelectorAll('.attendance-checkbox');
        let present = 0;
        let absent = 0;
        
        checkboxes.forEach(chk => {
            if (chk.checked) present++;
            else absent++;
        });

        document.getElementById('presentCount').innerText = present;
        document.getElementById('absentCount').innerText = absent;
    }

    function updateStatus(checkbox, labelId) {
        updateCounters();
    }

    // Mark All Logic
    function markAll(status) {
        const checkboxes = document.querySelectorAll('.attendance-checkbox');
        checkboxes.forEach(chk => {
            chk.checked = status;
        });
        updateCounters();
    }

    // Sunday Lock Logic
    function checkSunday(dateString) {
        const day = new Date(dateString).getUTCDay();
        const notice = document.getElementById('sundayNotice');
        const saveBtn = document.getElementById('saveBtn');
        const list = document.getElementById('studentList');

        if (day === 0) { // 0 is Sunday
            notice.style.display = 'block';
            saveBtn.disabled = true;
            list.style.opacity = '0.3';
            list.style.pointerEvents = 'none';
        } else {
            notice.style.display = 'none';
            saveBtn.disabled = false;
            list.style.opacity = '1';
            list.style.pointerEvents = 'auto';
        }
    }

    // Initialize counters and check sunday on page load
    document.addEventListener('DOMContentLoaded', () => {
        updateCounters();
        checkSunday(document.getElementById('attendanceDate').value);
    });
</script>
@endsection