<?php

namespace App\Http\Controllers;

use App\Models\MentorshipRequest;
use App\Models\MentorshipSession;
use App\Models\SessionMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MentorshipController extends Controller
{
    public function browseAlumni(Request $request)
    {
        $query = User::where('role', 'alumni')->where('application_status', 'approved');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%")
                  ->orWhere('company', 'LIKE', "%{$request->search}%")
                  ->orWhere('bio', 'LIKE', "%{$request->search}%");
            });
        }

        $alumni = $query->paginate(12);
        return view('student.mentorship.browse', compact('alumni'));
    }

    public function sendRequest(Request $request)
    {
        $request->validate([
            'alumni_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        MentorshipRequest::create([
            'student_id' => Auth::id(),
            'alumni_id' => $request->alumni_id,
            'message' => $request->message,
            'status' => 'pending',
            'expires_at' => now()->addDays(7),
        ]);

        return back()->with('success', 'Mentorship request sent successfully!');
    }

    public function myRequests()
    {
        $requests = MentorshipRequest::with('alumni')
            ->where('student_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return view('student.mentorship.requests', compact('requests'));
    }

    public function mySessions()
    {
        $sessions = MentorshipSession::with('alumni')
            ->where('student_id', Auth::id())
            ->orderByDesc('scheduled_at')
            ->get();

        return view('student.mentorship.sessions', compact('sessions'));
    }

    public function sessionChat($id)
    {
        $session = MentorshipSession::with(['alumni', 'messages.sender'])
            ->where('id', $id)
            ->where('student_id', Auth::id())
            ->firstOrFail();

        return view('student.mentorship.session-chat', compact('session'));
    }

    public function sendSessionMessage(Request $request, $id)
    {
        $session = MentorshipSession::where('id', $id)
            ->where('student_id', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        SessionMessage::create([
            'session_id' => $session->id,
            'sender_id' => Auth::id(),
            'sender_type' => 'student',
            'message' => $validated['message'],
        ]);

        return back()->with('success', 'Message sent!');
    }
}
