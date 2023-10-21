<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_category_id' => ['nullable', Rule::exists('categories', 'id')]
        ]);

        $category = Category::create($data);

        return response([
            'message' => 'Category successfully created'
        ], 201);
    }

}
