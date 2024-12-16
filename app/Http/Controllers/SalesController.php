<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;

class SalesController extends Controller
{
    public function monthlyRevenue(Request $request)
{
    // Optional date range filtering
    $startDate = $request->input('start_date') 
        ? Carbon::parse($request->input('start_date')) 
        : Carbon::now()->subYear();
    $endDate = $request->input('end_date') 
        ? Carbon::parse($request->input('end_date')) 
        : Carbon::now();

    $revenueData = [];
    $currentYear = $endDate->year;
    $currentMonth = $endDate->month;

    for ($i = 0; $i < 12; $i++) {
        $month = ($currentMonth - $i) <= 0 ? 12 + ($currentMonth - $i) : $currentMonth - $i;
        $year = ($currentMonth - $i) <= 0 ? $currentYear - 1 : $currentYear;

        $totalRevenue = Order::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->where('status', 'delivered')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with('items.product')
            ->get()
            ->reduce(function ($carry, $order) {
                return $carry + $order->items->sum(function ($item) {
                    return $item->quantity * $item->product->price + 60; // Assuming a fixed shipping fee
                });
            }, 0);

        $revenueData[] = [
            'month' => $month,
            'year' => $year,
            'revenue' => $totalRevenue,
        ];
    }

    $revenueData = array_reverse($revenueData);

    // Most Sold Products with date filtering
    $mostSoldProducts = Order::with('items.product')
        ->where('status', 'delivered')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->get()
        ->flatMap(function ($order) {
            return $order->items->map(function ($item) {
                return [
                    'product_name' => $item->product->product_name,
                    'quantity' => $item->quantity
                ];
            });
        })
        ->groupBy('product_name')
        ->map(function ($items) {
            return [
                'product_name' => $items->first()['product_name'],
                'total_quantity' => $items->sum('quantity'),
            ];
        })
        ->sortByDesc('total_quantity')
        ->take(5)
        ->values();

    // Overall Sales (Total Sales)
    $totalSales = OrderItem::join("products", "order_items.product_id", "=", "products.id")
        ->join("orders", "order_items.order_id", "=", "orders.id")
        ->where("orders.status", "delivered")
        ->whereBetween("orders.created_at", [$startDate, $endDate])
        ->selectRaw("SUM(order_items.quantity * products.price + 60) as total_sales")
        ->value("total_sales") ?? 0;

    return response()->json([
        'monthlyRevenue' => $revenueData,
        'mostSoldProducts' => $mostSoldProducts,
        'totalSales' => $totalSales
    ]);
}

    public function mostSoldProducts(Request $request)
    {
        // Optional date range filtering
        $startDate = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date'))->startOfDay() 
            : Carbon::now()->subYear();
        $endDate = $request->input('end_date') 
            ? Carbon::parse($request->input('end_date'))->endOfDay() 
            : Carbon::now();

        $mostSoldProducts = Order::with('items.product')
            ->where('status', 'delivered')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->flatMap(function ($order) {
                return $order->items->map(function ($item) {
                    return [
                        'product_name' => $item->product->product_name,
                        'total_quantity' => $item->quantity,
                    ];
                });
            })
            ->groupBy('product_name')
            ->map(function ($group) {
                return [
                    'product_name' => $group->first()['product_name'],
                    'total_quantity' => $group->sum('total_quantity'),
                ];
            })
            ->sortByDesc('total_quantity')
            ->take(10)
            ->values()
            ->toArray();

        return response()->json([
            'most_sold_products' => $mostSoldProducts,
        ]);
    }

    public function reports(Request $request)
    {
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : Carbon::now()->subMonth()->startOfDay();

        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : Carbon::now()->endOfDay();

        \Log::info('Start Date: ' . $startDate);
        \Log::info('End Date: ' . $endDate);

        $totalSales = OrderItem::join("products", "order_items.product_id", "=", "products.id")
            ->join("orders", "order_items.order_id", "=", "orders.id")
            ->where("orders.status", "delivered")
            ->whereBetween("orders.created_at", [$startDate, $endDate])
            ->selectRaw("SUM(order_items.quantity * products.price + 60) as total_sales")
            ->value("total_sales") ?? 0;

        \Log::info('Total Sales: ' . $totalSales);

        // Return the view with the data
        return view('admin.report', compact('totalSales'));
    }

    

}