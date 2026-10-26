<?php
// backend/app/Http/Controllers/ReportController.php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $period = $request->period ?? 'week'; // week | month | year
        $startDate = match($period) {
            'week'  => now()->subDays(6)->startOfDay(),
            'month' => now()->startOfMonth(),
            'year'  => now()->startOfYear(),
            default => now()->subDays(6)->startOfDay(),
        };

        // Chart data
        $chart = Bill::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as sales'),
                DB::raw('COUNT(*) as orders')
            )
            ->where('status', 'paid')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn($r) => [
                'day'    => Carbon::parse($r->date)->format('D'),
                'date'   => $r->date,
                'sales'  => (float)$r->sales,
                'orders' => $r->orders,
            ]);

        // Today's stats
        $todaySales = Bill::where('status', 'paid')
            ->whereDate('created_at', today())
            ->sum('total');

        $weekOrders = Bill::where('status', 'paid')
            ->where('created_at', '>=', $startDate)
            ->count();

        $totalProducts = Product::where('is_active', true)->count();
        $lowStock = Product::where('is_active', true)->whereRaw('stock <= min_stock')->count();

        return response()->json([
            'chart' => $chart,
            'stats' => [
                'sales'    => $todaySales,
                'orders'   => $weekOrders,
                'products' => $totalProducts,
                'lowStock' => $lowStock,
            ]
        ]);
    }

    public function topProducts(Request $request)
    {
        $limit = $request->limit ?? 10;
        $startDate = now()->subDays(30);

        $top = BillItem::select(
                'product_id',
                DB::raw('SUM(quantity) as sold'),
                DB::raw('SUM(subtotal) as revenue')
            )
            ->whereHas('bill', fn($q) => $q->where('status', 'paid')->where('created_at', '>=', $startDate))
            ->with('product:id,name')
            ->groupBy('product_id')
            ->orderByDesc('sold')
            ->limit($limit)
            ->get()
            ->map(fn($r) => [
                'name'    => $r->product->name ?? 'Unknown',
                'sold'    => $r->sold,
                'revenue' => (float)$r->revenue,
            ]);

        return response()->json($top);
    }

    public function inventory()
    {
        $data = Product::with('category')
            ->where('is_active', true)
            ->get()
            ->map(fn($p) => [
                'id'         => $p->id,
                'name'       => $p->name,
                'category'   => $p->category?->name,
                'stock'      => $p->stock,
                'min_stock'  => $p->min_stock,
                'price'      => $p->price,
                'cost_price' => $p->cost_price,
                'value'      => $p->stock * $p->cost_price,
                'low'        => $p->stock <= $p->min_stock,
            ]);

        return response()->json([
            'products'    => $data,
            'total_value' => $data->sum('value'),
            'low_stock'   => $data->where('low', true)->count(),
        ]);
    }

    public function daily(Request $request)
    {
        $date = $request->date ? Carbon::parse($request->date) : today();

        $bills = Bill::with(['items.product', 'cashier'])
            ->where('status', 'paid')
            ->whereDate('created_at', $date)
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'date'        => $date->toDateString(),
            'total_sales' => $bills->sum('total'),
            'total_orders'=> $bills->count(),
            'bills'       => $bills,
            'by_payment'  => $bills->groupBy('payment_mode')->map(fn($g) => [
                'count'  => $g->count(),
                'amount' => $g->sum('total'),
            ]),
        ]);
    }
}
