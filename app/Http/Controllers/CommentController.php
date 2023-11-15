<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CommentController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:1000'],
            'event_id' => ['required', Rule::exists('events', 'id')],
            'user_id' => ['required', Rule::exists('users', 'id')]
        ]);

        $comment = Comment::create($data);

        return response([
            'message' => 'Comment successfully created'
        ], 201);
    }
}
