<?php

namespace App\Http\Controllers\Certchain;

use App\Http\Controllers\Controller;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with(['creator', 'certificates'])
            ->latest()->paginate(12);
        return view('faculty.events.index', compact('events'));
    }

    public function create()
    {
        return view('faculty.events.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_type' => 'required|string',
            'event_date' => 'required|date',
            'event_end_date' => 'nullable|date|after_or_equal:event_date',
            'venue' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
        ]);

        $adminUsername = session('admin_username');
        $issuer = \App\Models\User::where('username', $adminUsername)->first() 
            ?? \App\Models\User::where('email', 'like', 'hod%')->first()
            ?? \App\Models\User::first();

        Event::create([...$data, 'created_by' => $issuer->id]);

        return redirect()->route('teacher.certchain.events.index')->with('success', 'Event created successfully!');
    }

    public function edit(Event $event)
    {
        return view('faculty.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_type' => 'required|string',
            'event_date' => 'required|date',
            'event_end_date' => 'nullable|date',
            'venue' => 'nullable|string',
            'department' => 'nullable|string',
            'status' => 'required|in:active,completed,cancelled',
        ]);

        $event->update($data);
        return redirect()->route('teacher.certchain.events.index')->with('success', 'Event updated!');
    }

    public function destroy(Event $event)
    {
        if ($event->certificates()->count() > 0) {
            return back()->with('error', 'Cannot delete event with issued certificates.');
        }
        $event->delete();
        return redirect()->route('teacher.certchain.events.index')->with('success', 'Event deleted.');
    }
}
