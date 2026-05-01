<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - EduShare</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #CCD0CF;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 40px 20px;
            color: #06141B;
        }

        .register-container {
            background-color: #ffffff;
            border: 1px solid rgba(6, 20, 27, 0.05);
            box-shadow: 0 10px 50px rgba(6, 20, 27, 0.1);
            border-radius: 24px;
            max-width: 480px;
            width: 100%;
            padding: 40px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #06141B;
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            color: #253745;
            font-size: 0.9rem;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 14px;
            border: 1px solid rgba(6, 20, 27, 0.1);
            border-radius: 12px;
            font-size: 1rem;
            background-color: #F8F9F9;
            color: #06141B;
            font-family: inherit;
            transition: all 0.2s;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #253745;
            box-shadow: 0 0 0 3px rgba(37, 55, 69, 0.1);
        }

        button {
            width: 100%;
            padding: 14px;
            background-color: #253745;
            color: #CCD0CF;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(37, 55, 69, 0.15);
            margin-top: 10px;
        }

        button:hover {
            background-color: #1a2833;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(37, 55, 69, 0.25);
        }

        .links {
            text-align: center;
            margin-top: 25px;
        }

        .links p {
            color: #4A5568;
            font-size: 0.95rem;
        }

        .links a {
            color: #253745;
            text-decoration: none;
            font-weight: 700;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .alert {
            background-color: #FEF2F2;
            border: 1px solid #FECACA;
            color: #991B1B;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .help-text {
            font-size: 0.8rem;
            color: #718096;
            margin-top: 6px;
            font-weight: 500;
        }

        .error-message {
            color: #DC2626;
            font-size: 0.8rem;
            margin-top: 5px;
            font-weight: 600;
        }

        /* Dynamic fields handling */
        .dynamic-field {
            display: none;
            animation: fadeIn 0.3s ease;
        }

        .dynamic-field.show {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .section-divider {
            display: none;
            border: none;
            border-top: 1px solid rgba(6, 20, 27, 0.08);
            margin: 25px 0;
        }

        .section-divider.show {
            display: block;
        }

        .section-label {
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #718096;
            margin-bottom: 15px;
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
                <select id="role" name="role" required onchange="toggleDynamicFields(this.value)">
                    <option value="">Select Role</option>
                    <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Student</option>
                    <option value="teacher" {{ old('role') === 'teacher' ? 'selected' : '' }}>Teacher</option>
                    <option value="alumni" {{ old('role') === 'alumni' ? 'selected' : '' }}>Alumni</option>
                </select>
                <div class="help-text">Teachers and Alumni need approval before accessing the dashboard</div>
            </div>

            {{-- ── Dynamic fields based on Role ── --}}
            <hr class="section-divider" id="dynamic-divider">

            <div class="dynamic-field" id="dynamic-section-label">
                <div class="section-label" id="section-label-text">Details</div>
            </div>

            <div class="form-group dynamic-field" id="enrollment-group">
                <label for="enrollment_no">Enrollment Number</label>
                <input type="text" id="enrollment_no" name="enrollment_no" value="{{ old('enrollment_no') }}"
                    placeholder="e.g. 23010101001"
                    style="{{ $errors->has('enrollment_no') ? 'border-color: #DC2626;' : '' }}">
                @error('enrollment_no')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group dynamic-field" id="company-group">
                <label for="company">Company / Organization</label>
                <input type="text" id="company" name="company" value="{{ old('company') }}" placeholder="e.g. Google, TCS, or Self-employed">
            </div>

            <div class="form-group dynamic-field" id="bio-group">
                <label for="bio">Professional Bio</label>
                <textarea id="bio" name="bio" rows="3">{{ old('bio') }}</textarea>
                <div class="help-text">Briefly describe your experience and expertise</div>
            </div>

            <div class="form-group dynamic-field" id="branch-group">
                <label for="branch">Branch</label>
                <select id="branch" name="branch">
                    <option value="">Select Branch</option>
                    <option value="Civil Engineering" {{ old('branch') === 'Civil Engineering' ? 'selected' : '' }}>Civil
                        Engineering</option>
                    <option value="Mechanical Engineering" {{ old('branch') === 'Mechanical Engineering' ? 'selected' : '' }}>Mechanical Engineering</option>
                    <option value="Electrical Engineering" {{ old('branch') === 'Electrical Engineering' ? 'selected' : '' }}>Electrical Engineering</option>
                    <option value="Electronics Engineering (EL)" {{ old('branch') === 'Electronics Engineering (EL)' ? 'selected' : '' }}>Electronics Engineering (EL)</option>
                    <option value="Computer Science & Engineering" {{ old('branch') === 'Computer Science & Engineering' ? 'selected' : '' }}>Computer Science & Engineering</option>
                    <option value="Instrumentation & Control Plastic Technology" {{ old('branch') === 'Instrumentation & Control Plastic Technology' ? 'selected' : '' }}>Instrumentation & Control Plastic Technology
                    </option>
                    <option value="Chemical Engineering" {{ old('branch') === 'Chemical Engineering' ? 'selected' : '' }}>
                        Chemical Engineering</option>
                </select>
            </div>

            <div class="form-group dynamic-field" id="semester-group">
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
        function toggleDynamicFields(role) {
            const divider = document.getElementById('dynamic-divider');
            const labelDiv = document.getElementById('dynamic-section-label');
            const labelText = document.getElementById('section-label-text');
            const branchGroup = document.getElementById('branch-group');
            const semesterGroup = document.getElementById('semester-group');

            const branchInput = document.getElementById('branch');
            const semesterInput = document.getElementById('semester');

            const enrollmentGroup = document.getElementById('enrollment-group');
            const enrollmentInput = document.getElementById('enrollment_no');

            const companyGroup = document.getElementById('company-group');
            const bioGroup = document.getElementById('bio-group');

            // Hide all by default
            [enrollmentGroup, branchGroup, semesterGroup, companyGroup, bioGroup].forEach(g => g.classList.remove('show'));
            [enrollmentInput, branchInput, semesterInput].forEach(i => i.required = false);

            if (role === 'student') {
                divider.classList.add('show');
                labelDiv.classList.add('show');
                labelText.innerText = 'Student Details';

                enrollmentGroup.classList.add('show');
                enrollmentInput.required = true;
                branchGroup.classList.add('show');
                branchInput.required = true;
                semesterGroup.classList.add('show');
                semesterInput.required = true;

            } else if (role === 'teacher') {
                divider.classList.add('show');
                labelDiv.classList.add('show');
                labelText.innerText = 'Teacher Details';

                branchGroup.classList.add('show');
                branchInput.required = true;

            } else if (role === 'alumni') {
                divider.classList.add('show');
                labelDiv.classList.add('show');
                labelText.innerText = 'Alumni Details';

                companyGroup.classList.add('show');
                bioGroup.classList.add('show');
                branchGroup.classList.add('show');
                branchInput.required = true;

            } else {
                divider.classList.remove('show');
                labelDiv.classList.remove('show');
            }
        }

        // Re-show fields on page reload after validation error
        document.addEventListener('DOMContentLoaded', function () {
            const roleVal = document.getElementById('role').value;
            if (roleVal) {
                toggleDynamicFields(roleVal);
            }
        });
    </script>
</body>

</html>