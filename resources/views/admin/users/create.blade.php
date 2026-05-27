@extends('layouts.certchain_app')
@section('title','Create User')
@section('page-title','Create New User')
@section('page-subtitle','Add a faculty member, HOD, or coordinator')

@section('content')
<div class="max-w-2xl">
<form method="POST" action="{{ route('admin.users.store') }}">
@csrf
<div class="space-y-5">
    <div class="card p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Personal Information</h3>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Employee ID</label>
                <input type="text" name="employee_id" value="{{ old('employee_id') }}" placeholder="e.g. FAC001"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Department</label>
                <input type="text" name="department" value="{{ old('department') }}" placeholder="e.g. Computer Science"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Designation</label>
                <input type="text" name="designation" value="{{ old('designation') }}" placeholder="e.g. Assistant Professor"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Role <span class="text-red-500">*</span></label>
                <select name="role" required class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" id="roleSelect">
                    <option value="">Select role…</option>
                    @foreach($roles as $role)
                    <option value="{{ strtolower($role->name) }}" {{ old('role') === strtolower($role->name) ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mt-4 hidden" id="branchDiv">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Branch <span class="text-red-500">*</span></label>
                <select name="branch" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" id="branchSelect">
                    <option value="">Select branch…</option>
                    @foreach($branches as $branch)
                    <option value="{{ $branch }}" {{ old('branch') === $branch ? 'selected' : '' }}>{{ $branch }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mt-4 hidden" id="alumniDiv">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Alumni Details</label>
                <textarea name="alumni_details" rows="3" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('alumni_details') }}</textarea>
                <label class="block text-sm font-medium text-gray-700 mb-1.5 mt-2">Company / Organization</label>
                <input type="text" name="company" value="{{ old('company') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                <label class="block text-sm font-medium text-gray-700 mb-1.5 mt-2">Professional Bio</label>
                <textarea name="bio" rows="3" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('bio') }}</textarea>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const roleSelect = document.getElementById('roleSelect');
                    const branchDiv = document.getElementById('branchDiv');
                    const alumniDiv = document.getElementById('alumniDiv');
                    function toggleFields() {
                        const role = roleSelect.value.toLowerCase();
                        if (['student', 'teacher', 'alumni'].includes(role)) {
                            branchDiv.classList.remove('hidden');
                        } else {
                            branchDiv.classList.add('hidden');
                        }
                        if (role === 'alumni') {
                            alumniDiv.classList.remove('hidden');
                        } else {
                            alumniDiv.classList.add('hidden');
                        }
                    }
                    roleSelect.addEventListener('change', toggleFields);
                    toggleFields(); // initial call
                });
            </script>
        </div>
    </div>

    <div class="card p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Password</h3>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                <input type="password" name="password" required minlength="8"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirm Password <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation" required
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="btn-primary">Create User</button>
        <a href="{{ route('admin.users') }}" class="px-5 py-2 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50">Cancel</a>
    </div>
</div>
</form>
</div>
@endsection
