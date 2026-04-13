<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PrincipalController extends Controller
{
    public function dashboard()
    {
        // Explicitly using namespace to ensure correct model is used
        $totalStudents = \App\Models\User::where('role', 'student')->count();
        $totalTeachers = \App\Models\User::where('role', 'teacher')->count();
        
        // Count branches from students, teachers, and admin tables (excluding 'All')
        $branches = Student::distinct()->pluck('branch')
            ->merge(Teacher::distinct()->pluck('branch'))
            ->merge(Admin::where('role', 'hod')->distinct()->pluck('branch'))
            ->filter(function($value) {
                return !empty($value) && $value !== 'All';
            })
            ->unique()
            ->count();

        return view('principal.dashboard', compact('totalStudents', 'totalTeachers', 'branches'));
    }

    public function manageHods()
    {
        $hods = Admin::where('role', 'hod')->orderBy('id', 'DESC')->get();
        return view('principal.manage_hods', compact('hods'));
    }

    public function storeHod(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:admin,username',
            'email' => 'nullable|email|unique:admin,email',
            'password' => 'required|min:4',
            'branch' => 'required',
        ]);

        Admin::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'branch' => $request->branch,
            'role' => 'hod',
            'status' => 'active',
        ]);

        return back()->with('success', 'HOD created successfully.');
    }

    public function toggleHodStatus($id)
    {
        $hod = Admin::findOrFail($id);
        $newStatus = ($hod->status === 'active') ? 'disabled' : 'active';
        $hod->update(['status' => $newStatus]);

        return back()->with('success', "HOD account has been " . ($newStatus === 'active' ? 'enabled' : 'disabled') . ".");
    }

    public function deleteHod($id)
    {
        $hod = Admin::findOrFail($id);
        
        // Safety check: Cannot delete principal from here
        if ($hod->role === 'principal') {
            return back()->with('error', 'The Principal account cannot be deleted.');
        }

        $hod->delete();

        return back()->with('success', 'HOD account deleted successfully.');
    }

}
