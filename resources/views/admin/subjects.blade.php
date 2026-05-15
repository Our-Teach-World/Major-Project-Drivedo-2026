@extends('admin.layouts.app')

@section('title', 'Manage Subjects - CampusCore Admin')
@section('header_title', '📚 Manage Subjects')

@push('styles')
    <style>
        /* Bulk Add Row Styles */
        .subject-row {
            display: flex;
            gap: 15px;
            align-items: center;
            margin-bottom: 15px;
            padding: 15px;
            border: 2px dashed #000;
            border-radius: 5px;
            background: #fafafa;
        }

        .subject-row .form-group {
            margin-bottom: 0;
            flex: 1;
        }

        .remove-btn {
            background-color: #ef4444;
            color: white;
            border: 2px solid #000;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 5px;
            font-weight: bold;
            transition: 0.2s;
        }
        
        .remove-btn:hover {
            background-color: #dc2626;
            box-shadow: 2px 2px 0px #000;
        }

        /* Tabs Styles */
        .tabs {
            display: flex;
            border-bottom: 2px solid #000;
            margin-bottom: 20px;
            overflow-x: auto;
        }

        .tab-btn {
            padding: 12px 24px;
            background: #f5f5f5;
            border: none;
            border-right: 2px solid #000;
            border-top: 2px solid #000;
            border-left: 2px solid transparent;
            font-weight: bold;
            cursor: pointer;
            font-size: 1rem;
            transition: 0.2s;
            border-radius: 8px 8px 0 0;
            margin-right: 5px;
            margin-bottom: -2px;
        }
        
        .form-control {
            border: 2px solid #000;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 1px 1px 0px #000;
            transition: 0.2s;
        }
        .tab-btn:first-child {
            border-left: 2px solid #000;
        }

        .tab-btn.active {
            background-color: #000;
            color: #fff;
            border-color: #000;
            border-bottom: 2px solid #000;
        }

        .tab-btn:hover:not(.active) {
            background-color: #e5e5e5;
        }

        .tab-pane {
            display: none;
            animation: fadeIn 0.3s ease-in-out;
        }

        .tab-pane.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #000;
        }

        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #000;
            border-right: 1px solid #000;
        }

        th {
            background-color: #f9fafb;
            font-weight: bold;
            border-bottom: 2px solid #000;
        }

        td:last-child, th:last-child {
            border-right: none;
        }
        
    </style>
@endpush

@section('content')

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul style="margin-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <h2 style="margin-bottom: 20px;">Bulk Add Subjects</h2>
        <form action="{{ route('admin.subjects.bulkStore') }}" method="POST">
            @csrf
            <div class="form-group" style="max-width: 300px; margin-bottom: 20px;">
                <label>Select Semester</label>
                <select name="semester" class="form-control" required>
                    <option value="">-- Choose Semester --</option>
                    <option value="1">1st Semester</option>
                    <option value="2">2nd Semester</option>
                    <option value="3">3rd Semester</option>
                    <option value="4">4th Semester</option>
                    <option value="5">5th Semester</option>
                    <option value="6">6th Semester</option>
                </select>
            </div>

            <div id="subjects-container">
                <div class="subject-row">
                    <div class="form-group">
                        <label>Subject Name</label>
                        <input type="text" name="subjects[0][name]" class="form-control" required placeholder="e.g. C Language">
                    </div>
                    <div class="form-group">
                        <label>Subject Code</label>
                        <input type="text" name="subjects[0][code]" class="form-control" required placeholder="e.g. 3001">
                    </div>
                    <div class="form-group" style="flex: 0 0 auto; margin-top: 25px;">
                        <button type="button" class="remove-btn" style="visibility: hidden;">X</button>
                    </div>
                </div>
            </div>

            <div style="margin-top: 20px; display: flex; gap: 15px;">
                <button type="button" class="btn" id="add-row-btn">+ Add Another Subject</button>
                <button type="submit" class="btn btn-primary">Save All Subjects</button>
            </div>
        </form>
    </div>

    <div class="card">
        <h2 style="margin-bottom: 20px;">Existing Subjects</h2>
        
        @if($subjects->isEmpty())
            <p style="color: #6b7280; padding: 20px 0;">No subjects found for your branch.</p>
        @else
            <div class="tabs" id="semester-tabs">
                @foreach($subjects->keys() as $index => $semester)
                    <button class="tab-btn {{ $index === 0 ? 'active' : '' }}" data-target="tab-{{ $semester }}" type="button">
                        Semester {{ $semester }}
                    </button>
                @endforeach
            </div>

            <div class="tab-content">
                @foreach($subjects as $semester => $semesterSubjects)
                    <div class="tab-pane {{ $loop->first ? 'active' : '' }}" id="tab-{{ $semester }}">
                        <div style="overflow-x: auto;">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th style="width: 100px; text-align:center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($semesterSubjects as $subject)
                                        <tr>
                                            <td>{{ $subject->code }}</td>
                                            <td>{{ $subject->name }}</td>
                                            <td style="text-align:center;">
                                                <form action="{{ route('admin.subjects.destroy', $subject->id) }}" method="POST" class="confirm-delete-form" data-confirm-message="Are you sure you want to delete {{ $subject->name }}?">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn" style="padding: 5px 10px; font-size: 0.85rem; color: #dc2626; border-color: #dc2626;">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Bulk Add Dynamic Rows Logic
            const container = document.getElementById('subjects-container');
            const addBtn = document.getElementById('add-row-btn');
            let rowIndex = 1;

            addBtn.addEventListener('click', () => {
                const row = document.createElement('div');
                row.className = 'subject-row';
                row.innerHTML = `
                    <div class="form-group">
                        <label>Subject Name</label>
                        <input type="text" name="subjects[${rowIndex}][name]" class="form-control" required placeholder="Subject Name">
                    </div>
                    <div class="form-group">
                        <label>Subject Code</label>
                        <input type="text" name="subjects[${rowIndex}][code]" class="form-control" required placeholder="Subject Code">
                    </div>
                    <div class="form-group" style="flex: 0 0 auto; margin-top: 25px;">
                        <button type="button" class="remove-btn" onclick="this.closest('.subject-row').remove()">X</button>
                    </div>
                `;
                container.appendChild(row);
                rowIndex++;
            });

            // Semester Tabs Logic
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabPanes = document.querySelectorAll('.tab-pane');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    tabBtns.forEach(b => b.classList.remove('active'));
                    tabPanes.forEach(p => p.classList.remove('active'));

                    btn.classList.add('active');
                    const targetPane = document.getElementById(btn.getAttribute('data-target'));
                    if (targetPane) {
                        targetPane.classList.add('active');
                    }
                });
            });
        });
    </script>
@endpush
