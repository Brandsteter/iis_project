<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'description' => ['string', 'max:255']
        ]);

        $place = Place::create($data);

        return response([
            'message' => 'Place successfully created'
        ], 201);
    }

    public function delete(Place $place)
    {
        $place->delete();
        return response()->noContent();
    }

    public function update(Place $place, Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'description' => ['string', 'max:255']
        ]);

        $place->update($data);

        return response([
            'message' => 'Place successfully updated'
        ], 201);
    }

    public function approve(Place $place)
    {
        $data = [
            'is_approved' => true
        ];

        $place->update($data);

        return response([
            'message' => 'Place successfully approved'
        ], 201);
    }

    public function getPlacesApproved()
    {
        $places = Place::where('is_approved', true)->paginate(10);

        return response()->json($places);
    }

    public function getPlacesUnapproved()
    {
        $places = Place::where('is_approved', false)->paginate(5);

        return response()->json($places);
    }

    public function placeDetailPage(Place $place)
    {
        return view('placedetail', compact('place'));
    }
}
