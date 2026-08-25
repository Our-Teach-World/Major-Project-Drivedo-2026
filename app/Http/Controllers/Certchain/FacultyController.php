<?php

namespace App\Http\Controllers\Certchain;

use App\Http\Controllers\Controller;

use App\Models\Certificate;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class FacultyController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $stats = [
            'my_events'        => Event::where('created_by', $user->id)->count(),
            'my_certificates'  => Certificate::where('issued_by', $user->id)->count(),
            'emails_sent'      => Certificate::where('issued_by', $user->id)->where('email_sent', true)->count(),
        ];

        $recentCerts = Certificate::with(['event'])
            ->where('issued_by', $user->id)
            ->latest()->limit(6)->get();

        $myEvents = Event::where('created_by', $user->id)
            ->latest()->limit(5)->get();

        return view('faculty.dashboard', compact('stats', 'recentCerts', 'myEvents'));
    }

    public function profile()
    {
        return view('faculty.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'designation' => 'nullable|string|max:100',
            'department'  => 'nullable|string|max:100',
            'signature'   => 'nullable|image|mimes:png,jpg|max:1024',
            'password'    => 'nullable|min:8|confirmed',
        ]);

        $updateData = [
            'name'        => $data['name'],
            'designation' => $data['designation'] ?? $user->designation,
            'department'  => $data['department'] ?? $user->department,
        ];

        if ($request->hasFile('signature')) {
            if ($user->signature_path) {
                Storage::disk('public')->delete($user->signature_path);
            }
            $path = $request->file('signature')->store('signatures', 'public');
            $updateData['signature_path'] = $path;
        }

        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);

        return back()->with('success', 'Profile updated successfully!');
    }
}
