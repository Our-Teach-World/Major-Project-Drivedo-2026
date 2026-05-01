@extends('layouts.app')
@section('title','Manage Users')
@section('page-title','Manage Users')
@section('page-subtitle','Faculty, HODs, Coordinators and Admins')

@section('header-actions')
<a href="{{ route('admin.users.create') }}" class="btn-primary text-sm">+ Add User</a>
@endsection

@section('content')
<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Name</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Email</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Employee ID</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Department</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Role</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-5 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-900/10 text-blue-900 flex items-center justify-center font-bold text-sm">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $user->designation }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-3 text-gray-600">{{ $user->email }}</td>
                <td class="px-5 py-3 text-gray-500 font-mono text-xs">{{ $user->employee_id ?? '—' }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $user->department ?? '—' }}</td>
                <td class="px-5 py-3">
                    @php
                    $roleColors = ['admin'=>'bg-purple-100 text-purple-700','hod'=>'bg-blue-100 text-blue-700','faculty'=>'bg-green-100 text-green-700','coordinator'=>'bg-yellow-100 text-yellow-700'];
                    $role = $user->roles->first()?->name ?? 'none';
                    @endphp
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $roleColors[$role] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ ucfirst($role) }}
                    </span>
                </td>
                <td class="px-5 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td class="px-5 py-3">
                    <div class="flex gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="text-xs text-blue-600 hover:underline">Edit</a>
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.delete', $user) }}" onsubmit="return confirm('Delete this user?')">
                            @csrf @method('DELETE')
                            <button class="text-xs text-red-500 hover:underline">Delete</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-5 py-10 text-center text-gray-400">No users found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-3 border-t border-gray-100">{{ $users->links() }}</div>
</div>
@endsection
