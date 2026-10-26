<?php
// backend/app/Http/Controllers/ProductController.php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->where('is_active', true);

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('barcode', 'like', "%{$request->search}%");
        }
        if ($request->category) {
            $query->where('category_id', $request->category);
        }
        if ($request->store_type) {
            $query->where('store_type', $request->store_type);
        }

        return response()->json($query->orderBy('name')->paginate(50));
    }

    public function search(Request $request)
    {
        $q = $request->q;
        $products = Product::with('category')
            ->where('is_active', true)
            ->where(function($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('barcode', $q);
            })
            ->limit(20)->get();

        return response()->json($products);
    }

    public function show($id)
    {
        return response()->json(Product::with('category')->findOrFail($id));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'barcode'     => 'nullable|string|unique:products',
            'category_id' => 'nullable|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'cost_price'  => 'nullable|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'min_stock'   => 'nullable|integer|min:0',
            'unit'        => 'required|string',
            'store_type'  => 'required|string',
            'description' => 'nullable|string',
        ]);

        $product = Product::create($validated);
        return response()->json($product->load('category'), 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $validated = $request->validate([
            'name'        => 'sometimes|string|max:255',
            'barcode'     => "nullable|string|unique:products,barcode,{$id}",
            'category_id' => 'nullable|exists:categories,id',
            'price'       => 'sometimes|numeric|min:0',
            'cost_price'  => 'nullable|numeric|min:0',
            'stock'       => 'sometimes|integer|min:0',
            'min_stock'   => 'nullable|integer|min:0',
            'unit'        => 'sometimes|string',
            'store_type'  => 'sometimes|string',
            'description' => 'nullable|string',
        ]);

        $product->update($validated);
        return response()->json($product->load('category'));
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_active' => false]); // Soft delete
        return response()->json(['message' => 'Product deleted']);
    }
}
