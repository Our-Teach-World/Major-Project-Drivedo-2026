<?php

namespace App\Http\Controllers\Certchain;

use App\Models\CertchainEvent as Event;
use Illuminate\Http\Request;

class EventController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        // Only show events created by this teacher
        $events = Event::where('created_by', auth()->id())
            ->with(['certificates'])
            ->latest()
            ->paginate(12);
        return view('certchain.faculty.events.index', compact('events'));
    }

    public function create()
    {
        $teacherBranch = auth()->user()->teacherProfile->branch ?? '';
        return view('certchain.faculty.events.create', compact('teacherBranch'));
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

        $data['created_by'] = auth()->id();
        $data['status'] = 'active';

        Event::create($data);

        return redirect()->route('teacher.certchain.events.index')->with('success', 'Event created successfully!');
    }

    public function edit(Event $event)
    {
        return view('certchain.faculty.events.edit', compact('event'));
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
