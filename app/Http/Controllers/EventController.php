<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    public function getEventsApproved()
    {
        $events = Event::where('is_approved', true)->with('place')->paginate(5);

        return response()->json($events);
    }

    public function getEventsUnapproved()
    {
        $events = Event::where('is_approved', false)->with('place')->paginate(5);

        return response()->json($events);

    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'unique:events,name','max:255'],
            'event_start' => ['required', 'date', 'after_or_equal:' . now()->toDateString()],
            'event_end' => ['required', 'date', 'after_or_equal:event_start'],
            'capacity_max' => ['nullable', 'integer', 'max:255'],
            'place_id' => ['required', Rule::exists('places', 'id')],
            'event_start_time' => ['nullable', 'date_format:H:i'],
            'description' => ['nullable', 'string', 'max:255']
        ]);


        $event = Event::create($data);

        return response([
            'message' => 'Event successfully created'
        ], 201);
    }

    public function update(Event $event, Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string','max:255', Rule::unique('events', 'name')->ignore($event->id)],
            'event_start' => ['required', 'date', 'after_or_equal:' . now()->toDateString()],
            'event_end' => ['required', 'date', 'after_or_equal:event_start'],
            'capacity_max' => ['nullable', 'integer', 'max:255'],
            'place_id' => ['required', Rule::exists('places', 'id')],
            'event_start_time' => ['nullable', 'date_format:H:i'],
            'description' => ['nullable', 'string', 'max:255']
        ]);

        $event->update($data);

        return response([
            'message' => 'Event successfully updated'
        ], 201);
    }

    public function delete(Event $event)
    {
        $event->delete();
        return response()->noContent();
    }

    public function approve(Event $event)
    {
        $data = [
            'is_approved' => true
        ];

        $event->update($data);

        return response([
            'message' => 'Event successfully approved'
        ], 201);
    }
}
