<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function attend(Event $event)
    {
        $user = auth()->user();
        if ($user->events()->where('event_id', $event->id)->exists()) {
            return response([
                'message' => 'User is already attending the event'
            ], 400);
        }

        if (!$event->is_approved) {
            return response([
                'message' => 'Event isnt approved yet'
            ], 400);
        }

        if ($event->capacity_max != null && $event->capacity_current >= $event->capacity_max) {
            return response([
                'message' => 'Event is full'
            ], 400);
        }

        $user->events()->attach($event);

        $event->increment('capacity_current');

        $event->load(['place', 'users']);

        return response([
            'message' => 'User is now attending the event',
            'event' => $event,
        ], 201);
    }

    public function getUserEvents()
    {
        $user = auth()->user();
        $events = $user->events;

        return response()->json($events);
    }

    public function calendarPage()
    {
        return view('calendar');
    }
    public function eventsPage()
    {
        return view('events');
    }

    public function placesPage()
    {
        return view('places');
    }

    public function categoriesPage()
    {
        return view('categories');
    }
}
