<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - EduShare</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .register-container {
            background-color: #ffffff;
            border: 2px solid #000000;
            box-shadow: 5px 5px 0px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            max-width: 440px;
            width: 100%;
            padding: 40px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #000000;
            font-size: 1.8rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #000000;
        }

        input, select {
            width: 100%;
            padding: 12px;
            border: 2px solid #000000;
            border-radius: 5px;
            font-size: 1rem;
            background-color: #ffffff;
            color: #000000;
            font-family: inherit;
        }

        input:focus, select:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #000000;
            color: #ffffff;
            border: 2px solid #000000;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: #ffffff;
            color: #000000;
        }

        .links {
            text-align: center;
            margin-top: 20px;
        }

        .links a {
            color: #000000;
            text-decoration: none;
            font-weight: 500;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .alert {
            background-color: #ffebee;
            border: 2px solid #c62828;
            color: #c62828;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .help-text {
            font-size: 0.85rem;
            color: #666666;
            margin-top: 5px;
        }

        /* Student-only fields */
        .student-only {
            display: none;
        }

        .student-only.show {
            display: block;
        }

        .section-divider {
            display: none;
            border: none;
            border-top: 2px dashed #cccccc;
            margin: 22px 0;
        }

        .section-divider.show {
            display: block;
        }

        .section-label {
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #555;
            margin-bottom: 14px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>

        @if ($errors->any())
            <div class="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}">
            @csrf

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required value="{{ old('username') }}">
                <div class="help-text">At least 3 characters</div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <div class="help-text">At least 6 characters</div>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" required onchange="toggleStudentFields(this.value)">
                    <option value="">Select Role</option>
                    <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Student</option>
                    <option value="teacher" {{ old('role') === 'teacher' ? 'selected' : '' }}>Teacher</option>
                </select>
                <div class="help-text">Teachers need approval before accessing the dashboard</div>
            </div>

            {{-- ── Student-only fields ── --}}
            <hr class="section-divider" id="student-divider">

            <div class="student-only" id="student-section-label">
                <div class="section-label">Student Details</div>
            </div>

            <div class="form-group student-only" id="branch-group">
                <label for="branch">Branch</label>
                <select id="branch" name="branch">
                    <option value="">Select Branch</option>
                    <option value="Civil Engineering"
                        {{ old('branch') === 'Civil Engineering' ? 'selected' : '' }}>
                        Civil Engineering
                    </option>
                    <option value="Mechanical Engineering"
                        {{ old('branch') === 'Mechanical Engineering' ? 'selected' : '' }}>
                        Mechanical Engineering
                    </option>
                    <option value="Electrical Engineering"
                        {{ old('branch') === 'Electrical Engineering' ? 'selected' : '' }}>
                        Electrical Engineering
                    </option>
                    <option value="Electronics Engineering (EL)"
                        {{ old('branch') === 'Electronics Engineering (EL)' ? 'selected' : '' }}>
                        Electronics Engineering (EL)
                    </option>
                    <option value="Computer Engineering/Science & Engineering"
                        {{ old('branch') === 'Computer Engineering/Science & Engineering' ? 'selected' : '' }}>
                        Computer Engineering / Science &amp; Engineering
                    </option>
                    <option value="Instrumentation & Control Plastic Technology"
                        {{ old('branch') === 'Instrumentation & Control Plastic Technology' ? 'selected' : '' }}>
                        Instrumentation &amp; Control Plastic Technology
                    </option>
                    <option value="Chemical Engineering"
                        {{ old('branch') === 'Chemical Engineering' ? 'selected' : '' }}>
                        Chemical Engineering
                    </option>
                </select>
            </div>

            <div class="form-group student-only" id="semester-group">
                <label for="semester">Semester</label>
                <select id="semester" name="semester">
                    <option value="">Select Semester</option>
                    <option value="1" {{ old('semester') == 1 ? 'selected' : '' }}>Semester 1 &nbsp;(1st Year)</option>
                    <option value="2" {{ old('semester') == 2 ? 'selected' : '' }}>Semester 2 &nbsp;(1st Year)</option>
                    <option value="3" {{ old('semester') == 3 ? 'selected' : '' }}>Semester 3 &nbsp;(2nd Year)</option>
                    <option value="4" {{ old('semester') == 4 ? 'selected' : '' }}>Semester 4 &nbsp;(2nd Year)</option>
                    <option value="5" {{ old('semester') == 5 ? 'selected' : '' }}>Semester 5 &nbsp;(3rd Year)</option>
                    <option value="6" {{ old('semester') == 6 ? 'selected' : '' }}>Semester 6 &nbsp;(3rd Year)</option>
                </select>
                <div class="help-text">3-year programme — 6 semesters total (2 per year)</div>
            </div>

            <button type="submit">Register</button>
        </form>

        <div class="links">
            <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
        </div>
    </div>

    <script>
        function toggleStudentFields(role) {
            const fields   = document.querySelectorAll('.student-only');
            const divider  = document.getElementById('student-divider');
            const branch   = document.getElementById('branch');
            const semester = document.getElementById('semester');

            if (role === 'student') {
                fields.forEach(el => el.classList.add('show'));
                divider.classList.add('show');
                branch.required   = true;
                semester.required = true;
            } else {
                fields.forEach(el => el.classList.remove('show'));
                divider.classList.remove('show');
                branch.required   = false;
                semester.required = false;
                branch.value      = '';
                semester.value    = '';
            }
        }

        // Re-show fields on page reload after validation error with role=student
        document.addEventListener('DOMContentLoaded', function () {
            const roleVal = document.getElementById('role').value;
            if (roleVal) toggleStudentFields(roleVal);
        });
    </script>
</body>
</html>
