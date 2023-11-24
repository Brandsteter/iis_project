<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    public function getEventsApproved()
    {
        $events = Event::where('is_approved', true)->with('place', 'users', 'creator')->paginate(5);

        return response()->json($events);
    }

    public function getEventsUnapproved()
    {
        $events = Event::where('is_approved', false)->with('place', 'creator')->paginate(5);

        return response()->json($events);

    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'unique:events,name','max:255'],
            'event_start' => ['required', 'date', 'after_or_equal:' . now()->toDateString()],
            'event_end' => ['required', 'date', 'after_or_equal:event_start'],
            'capacity_max' => ['nullable', 'integer'],
            'place_id' => ['required', Rule::exists('places', 'id')],
            'event_start_time' => ['nullable', 'date_format:H:i'],
            'event_end_time' => ['nullable', 'date_format:H:i'],
            'description' => ['nullable', 'string', 'max:255']
        ]);

        $user = auth()->user();
        $data['creator_user_id'] = $user->id;

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
            'event_end_time' => ['nullable', 'date_format:H:i'],
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

    public function addCategory(Event $event, Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required', Rule::exists('categories', 'id')]
        ]);

        $category = Category::find($data['category_id']);

        if ($event->categories()->where('category_id', $category->id)->exists()) {
            return response([
                'message' => 'Event already has this category'
            ], 400);
        }

        if (!$category->is_approved) {
            return response([
                'message' => 'Category is not approved yet'
            ], 400);
        }

        $event->categories()->attach($category);

        return response([
            'message' => 'Added category for event'
        ], 201);

    }

    public function removeCategory(Event $event, Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required', Rule::exists('categories', 'id')]
        ]);

        $category = Category::find($data['category_id']);

        $event->categories()->detach($category);

        return response([
            'message' => 'Removed category from event'
        ], 201);

    }

    public function eventDetailPage(Event $event)
    {
        $event->load(['place', 'users', 'creator']);
        return view('eventdetail', compact('event'));
    }

}
