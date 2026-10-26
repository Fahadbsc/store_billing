<?php
// backend/app/Http/Controllers/CategoryController.php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('name')->get();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:categories',
            'description' => 'nullable|string',
            'store_type'  => 'nullable|string',
        ]);
        $cat = Category::create($request->only('name', 'description', 'store_type'));
        return response()->json($cat, 201);
    }

    public function update(Request $request, $id)
    {
        $cat = Category::findOrFail($id);
        $request->validate([
            'name'        => "sometimes|string|max:100|unique:categories,name,{$id}",
            'description' => 'nullable|string',
            'store_type'  => 'nullable|string',
        ]);
        $cat->update($request->only('name', 'description', 'store_type'));
        return response()->json($cat);
    }

    public function destroy($id)
    {
        $cat = Category::findOrFail($id);
        if ($cat->products()->count() > 0) {
            return response()->json(['message' => 'Cannot delete category with products'], 400);
        }
        $cat->delete();
        return response()->json(['message' => 'Category deleted']);
    }
}
