<?php

namespace App\Http\Controllers;

use App\Models\MentorshipRequest;
use App\Models\MentorshipSession;
use App\Models\SessionMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlumniController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        $pendingRequests = MentorshipRequest::where('alumni_id', $user->id)
            ->where('status', 'pending')
            ->count();
            
        $upcomingSessions = MentorshipSession::where('alumni_id', $user->id)
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at')
            ->take(5)
            ->get();

        return view('alumni.dashboard', compact('user', 'pendingRequests', 'upcomingSessions'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('alumni.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'company' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function requests()
    {
        $user = Auth::user();
        $requests = MentorshipRequest::with('student')
            ->where('alumni_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return view('alumni.requests', compact('user', 'requests'));
    }

    public function acceptRequest($id)
    {
        $request = MentorshipRequest::findOrFail($id);
        
        if ($request->alumni_id !== Auth::id()) {
            abort(403);
        }

        $request->update(['status' => 'accepted']);

        // Create a session when request is accepted
        MentorshipSession::create([
            'student_id' => $request->student_id,
            'alumni_id' => $request->alumni_id,
            'mentorship_request_id' => $request->id,
            'title' => 'Mentorship Session with ' . Auth::user()->username,
            'scheduled_at' => now()->addDay(),
            'status' => 'scheduled',
            'notes' => $request->message,
        ]);

        return back()->with('success', 'Request accepted! A session has been scheduled for tomorrow.');
    }

    public function declineRequest($id)
    {
        $request = MentorshipRequest::findOrFail($id);
        
        if ($request->alumni_id !== Auth::id()) {
            abort(403);
        }

        $request->update(['status' => 'declined']);

        return back()->with('success', 'Request declined.');
    }

    public function sessions()
    {
        $user = Auth::user();
        $sessions = MentorshipSession::with('student')
            ->where('alumni_id', $user->id)
            ->orderByDesc('scheduled_at')
            ->get();

        return view('alumni.sessions', compact('user', 'sessions'));
    }

    public function sessionChat($id)
    {
        $session = MentorshipSession::with(['student', 'messages.sender'])
            ->where('id', $id)
            ->where('alumni_id', Auth::id())
            ->firstOrFail();

        return view('alumni.session-chat', compact('session'));
    }

    public function sendMessage(Request $request, $id)
    {
        $session = MentorshipSession::where('id', $id)
            ->where('alumni_id', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        SessionMessage::create([
            'session_id' => $session->id,
            'sender_id' => Auth::id(),
            'sender_type' => 'alumni',
            'message' => $validated['message'],
        ]);

        return back()->with('success', 'Message sent!');
    }
}
