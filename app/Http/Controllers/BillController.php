<?php
// backend/app/Http/Controllers/BillController.php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
    public function index(Request $request)
    {
        $query = Bill::with(['items.product', 'cashier'])
            ->orderBy('created_at', 'desc');

        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }
        if ($request->cashier_id) {
            $query->where('cashier_id', $request->cashier_id);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        return response()->json($query->paginate(30));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'          => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.price'      => 'required|numeric|min:0',
            'subtotal'       => 'required|numeric',
            'discount_amount'=> 'nullable|numeric',
            'tax_amount'     => 'nullable|numeric',
            'total'          => 'required|numeric',
            'payment_mode'   => 'required|in:cash,card,upi',
            'amount_paid'    => 'required|numeric',
        ]);

        return DB::transaction(function () use ($request) {
            // Generate bill number
            $billNumber = 'BILL-' . date('Ymd') . '-' . str_pad(
                Bill::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT
            );

            $bill = Bill::create([
                'bill_number'     => $billNumber,
                'cashier_id'      => auth()->id(),
                'customer_name'   => $request->customer['name'] ?? 'Walk-in Customer',
                'customer_phone'  => $request->customer['phone'] ?? null,
                'subtotal'        => $request->subtotal,
                'discount'        => $request->discount ?? 0,
                'discount_type'   => $request->discount_type ?? 'percent',
                'discount_amount' => $request->discount_amount ?? 0,
                'tax_amount'      => $request->tax_amount ?? 0,
                'total'           => $request->total,
                'payment_mode'    => $request->payment_mode,
                'amount_paid'     => $request->amount_paid,
                'change_amount'   => max(0, $request->amount_paid - $request->total),
                'status'          => 'paid',
            ]);

            foreach ($request->items as $item) {
                BillItem::create([
                    'bill_id'    => $bill->id,
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                    'subtotal'   => $item['price'] * $item['quantity'],
                ]);

                // Deduct stock
                Product::where('id', $item['product_id'])
                    ->decrement('stock', $item['quantity']);
            }

            return response()->json($bill->load(['items.product', 'cashier']), 201);
        });
    }

    public function show($id)
    {
        return response()->json(Bill::with(['items.product', 'cashier'])->findOrFail($id));
    }

    public function void($id)
    {
        $bill = Bill::findOrFail($id);
        if ($bill->status === 'void') {
            return response()->json(['message' => 'Bill already voided'], 400);
        }

        DB::transaction(function () use ($bill) {
            // Restore stock
            foreach ($bill->items as $item) {
                Product::where('id', $item->product_id)
                    ->increment('stock', $item->quantity);
            }
            $bill->update(['status' => 'void']);
        });

        return response()->json(['message' => 'Bill voided successfully']);
    }
}
