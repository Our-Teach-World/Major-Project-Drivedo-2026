@extends('admin.layouts.app')

@section('title', 'Manage HODs - CampusCore Principal')

@section('header_title', 'HOD Management')

@section('content')
<div class="row">
    <!-- Left Column: Create HOD Form -->
    <div class="col-md-5">
        <div style="background: #fff; border: 4px solid #000; padding: 25px; box-shadow: 8px 8px 0px #000; position: sticky; top: 100px;">
            <h2 style="font-weight: 900; text-transform: uppercase; margin-bottom: 25px; border-bottom: 4px solid #000; padding-bottom: 10px;">
                Add New HOD
            </h2>

            @if(session('success'))
                <div style="background: #d1fae5; border: 2px solid #000; padding: 15px; margin-bottom: 20px; font-weight: 700;">
                    ✓ {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('principal.store_hod') }}" method="POST">
                @csrf
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 800; text-transform: uppercase; margin-bottom: 8px;">Username</label>
                    <input type="text" name="username" required style="width: 100%; padding: 12px; border: 3px solid #000; font-weight: 600; font-size: 1.1rem; outline: none;" placeholder="e.g. computer_hod">
                    @error('username') <small style="color: red; font-weight: 700;">{{ $message }}</small> @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 800; text-transform: uppercase; margin-bottom: 8px;">Email (Optional)</label>
                    <input type="email" name="email" style="width: 100%; padding: 12px; border: 3px solid #000; font-weight: 600; font-size: 1.1rem; outline: none;" placeholder="hod@campuscore.com">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 800; text-transform: uppercase; margin-bottom: 8px;">Password</label>
                    <input type="password" name="password" required style="width: 100%; padding: 12px; border: 3px solid #000; font-weight: 600; font-size: 1.1rem; outline: none;">
                </div>

                <div style="margin-bottom: 30px;">
                    <label style="display: block; font-weight: 800; text-transform: uppercase; margin-bottom: 8px;">Assigned Branch</label>
                    <select name="branch" required style="width: 100%; padding: 12px; border: 3px solid #000; font-weight: 600; font-size: 1.1rem; outline: none; background: #fff; appearance: none; cursor: pointer;">
                        <option value="">Select Branch</option>
                        <option value="Civil Engineering">Civil Engineering</option>
                        <option value="Mechanical Engineering">Mechanical Engineering</option>
                        <option value="Electrical Engineering">Electrical Engineering</option>
                        <option value="Electronics Engineering (EL)">Electronics Engineering (EL)</option>
                        <option value="Computer Science & Engineering">Computer Science & Engineering</option>
                        <option value="Instrumentation & Control Plastic Technology">Instrumentation & Control Plastic Technology</option>
                        <option value="Chemical Engineering">Chemical Engineering</option>
                    </select>
                </div>

                <button type="submit" style="width: 100%; background: #000; color: #fff; border: 4px solid #000; padding: 15px; font-weight: 900; font-size: 1.3rem; text-transform: uppercase; cursor: pointer; transition: 0.2s;">
                    Deploy HOD Access
                </button>
            </form>
        </div>
    </div>

    <!-- Right Column: HOD List -->
    <div class="col-md-7">
            <div style="margin-bottom: 20px;">
                <input type="text" id="hodSearch" placeholder="🔍 Search HODs by name or dept..." 
                    style="width: 100%; padding: 12px; border: 3px solid #000; font-weight: 700; outline: none; background: #fdfdfd;" 
                    onkeyup="filterHods()">
            </div>

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; margin-top: 10px;" id="hodTable">
                    <thead>
                        <tr style="background: #000; color: #fff;">
                            <th style="padding: 15px; text-align: left; border: 2px solid #000; text-transform: uppercase; font-weight: 900;">Username</th>
                            <th style="padding: 15px; text-align: left; border: 2px solid #000; text-transform: uppercase; font-weight: 900;">Department</th>
                            <th style="padding: 15px; text-align: center; border: 2px solid #000; text-transform: uppercase; font-weight: 900;">Status</th>
                            <th style="padding: 15px; text-align: center; border: 2px solid #000; text-transform: uppercase; font-weight: 900;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hods as $hod)
                            <tr class="hod-row" style="background: #fff; border-bottom: 3px solid #000; transition: background 0.2s;">
                                <td style="padding: 15px; border: 2px solid #000;" class="hod-name">
                                    <div style="font-weight: 800; font-size: 1.1rem;">{{ $hod->username }}</div>
                                    <div style="font-size: 0.85rem; color: #666; font-weight: 600;">{{ $hod->email ?? 'no email linked' }}</div>
                                </td>
                                <td style="padding: 15px; border: 2px solid #000;" class="hod-dept">
                                    <span style="display: inline-block; background: #e2e8f0; border: 2px solid #000; padding: 4px 10px; font-weight: 800; font-size: 0.9rem; text-transform: uppercase;">
                                        {{ $hod->branch }}
                                    </span>
                                </td>
                                <td style="padding: 15px; border: 2px solid #000; text-align: center;">
                                    @if($hod->status === 'active')
                                        <span style="color: #059669; font-weight: 900; text-transform: uppercase;">ACTIVE</span>
                                    @else
                                        <span style="color: #dc2626; font-weight: 900; text-transform: uppercase;">DISABLED</span>
                                    @endif
                                </td>
                                <td style="padding: 15px; border: 2px solid #000; text-align: center;">
                                    <div style="display: flex; gap: 10px; justify-content: center;">
                                        <form action="{{ route('principal.toggle_hod_status', $hod->id) }}" method="POST" style="margin: 0;">
                                            @csrf
                                            <button type="submit" style="background: {{ $hod->status === 'active' ? '#f59e0b' : '#10b981' }}; color: #000; border: 2px solid #000; padding: 6px 12px; font-weight: 800; font-size: 0.8rem; cursor: pointer; text-transform: uppercase;">
                                                {{ $hod->status === 'active' ? 'Disable' : 'Enable' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('principal.delete_hod', $hod->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('CRITICAL: Permanent deletion of HOD access. Proceed?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background: #ef4444; color: #fff; border: 2px solid #000; padding: 6px 12px; font-weight: 800; font-size: 0.8rem; cursor: pointer; text-transform: uppercase;">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="padding: 40px; text-align: center; font-weight: 800; color: #999;">
                                    NO HODs FOUND IN THE SYSTEM
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    button:hover {
        background: #333 !important;
        transform: translate(-3px, -3px);
        box-shadow: 5px 5px 0px #000;
    }
    input:focus, select:focus {
        background: #f0fdf4 !important;
        box-shadow: 4px 4px 0px #000;
    }
    tbody tr:hover {
        background: #fafafa !important;
    }
</style>
@endpush
@push('scripts')
<script>
    function filterHods() {
        const input = document.getElementById('hodSearch');
        const filter = input.value.toUpperCase();
        const rows = document.getElementsByClassName('hod-row');

        for (let i = 0; i < rows.length; i++) {
            const name = rows[i].getElementsByClassName('hod-name')[0].textContent.toUpperCase();
            const dept = rows[i].getElementsByClassName('hod-dept')[0].textContent.toUpperCase();
            
            if (name.includes(filter) || dept.includes(filter)) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    }
</script>
@endpush
@endsection
