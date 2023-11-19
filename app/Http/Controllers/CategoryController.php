<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function getCategoriesAll()
    {
        $categories = Category::all();

        return response()->json($categories);
    }

    public function getCategoriesChildrenApproved(Category $category)
    {
        $categories = $category->categories->where('is_approved', true);

        return response()->json($categories);
    }

    public function getCategoriesTopLevelApproved()
    {
        $categories = Category::where('parent_category_id', null)->where('is_approved', true)->get();

        return response()->json($categories);
    }

    public function getCategoriesApproved()
    {
        $categories = Category::where('is_approved', true)->with('category')->get();

        return response()->json($categories);
    }

    public function getCategoriesUnapproved()
    {
        $categories = Category::where('is_approved', false)->with('category')->get();

        return response()->json($categories);

    }

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

    public function delete(Category $category)
    {
        $category->delete();
        return response()->noContent();
    }

    public function update(Category $category, Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255']
        ]);

        $category->update($data);

        return response([
            'message' => 'Category successfully updated'
        ], 201);
    }

    public function approve(Category $category)
    {
        $data = [
            'is_approved' => true
        ];

        $category->update($data);

        return response([
            'message' => 'Category successfully approved'
        ], 201);
    }

    public function categoryDetailPage(Category $category)
    {
        $category -> load(['events']);
        return view('categorydetail', compact("category"));
    }
}
