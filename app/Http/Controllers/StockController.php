<?php
// backend/app/Http/Controllers/StockController.php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockHistory;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->where('is_active', true);

        if ($request->low_stock) {
            $query->whereRaw('stock <= min_stock');
        }
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        return response()->json($query->orderBy('name')->paginate(50));
    }

    public function lowStock()
    {
        $products = Product::with('category')
            ->where('is_active', true)
            ->whereRaw('stock <= min_stock')
            ->orderBy('stock')
            ->get();

        return response()->json($products);
    }

    public function update(Request $request, $productId)
    {
        $request->validate([
            'type'     => 'required|in:add,remove,set',
            'quantity' => 'required|integer|min:1',
            'note'     => 'nullable|string',
        ]);

        $product = Product::findOrFail($productId);
        $oldStock = $product->stock;

        if ($request->type === 'add') {
            $product->increment('stock', $request->quantity);
        } elseif ($request->type === 'remove') {
            if ($product->stock < $request->quantity) {
                return response()->json(['message' => 'Insufficient stock'], 400);
            }
            $product->decrement('stock', $request->quantity);
        } else {
            $product->update(['stock' => $request->quantity]);
        }

        // Log history
        StockHistory::create([
            'product_id'  => $product->id,
            'user_id'     => auth()->id(),
            'type'        => $request->type,
            'quantity'    => $request->quantity,
            'stock_before'=> $oldStock,
            'stock_after' => $product->fresh()->stock,
            'note'        => $request->note,
        ]);

        return response()->json($product->fresh());
    }

    public function history($productId)
    {
        $history = StockHistory::with('user')
            ->where('product_id', $productId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($history);
    }
}
