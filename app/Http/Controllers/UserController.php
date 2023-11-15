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

        $user->events()->attach($event);

        return response([
            'message' => 'User is now attending the event'
        ], 201);
    }
}
