<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(
            Category::latest()->paginate(10)
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255|unique:categories,title',
            'status' => 'required|boolean',
        ]);

        $category = Category::create($request->only('title','status'));
        return response()->json([
            'message' => 'Category created successfully',
            'data' => $category
        ], 201);
    }

    public function show($id)
    {
        return response()->json(
            Category::findOrFail($id)
        );
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'title'   => 'sometimes|string|max:255|unique:categories,title,' . $category->id,
            'status' => 'sometimes|boolean',
        ]);

        $category->update($request->only('title','status'));
        return response()->json([
            'message' => 'Category updated successfully',
            'data' => $category
        ]);
    }

    public function destroy($id)
    {
        Category::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Category deleted successfully'
        ]);
    }
}

