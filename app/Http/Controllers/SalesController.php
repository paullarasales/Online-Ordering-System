<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;

class SalesController extends Controller
{
    public function monthlyRevenue(Request $request)
    {
        $revenueData = [];
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        for ($i = 0; $i < 12; $i++) {
            $month = ($currentMonth - $i) <= 0 ? 12 + ($currentMonth - $i) : $currentMonth - $i;
            $year = ($currentMonth - $i) <= 0 ? $currentYear - 1 : $currentYear;

            $totalRevenue = Order::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->where('status', 'delivered')
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

        $mostSoldProducts = Order::with('items.product')
            ->where('status', 'delivered')
            ->whereBetween('created_at', [Carbon::now()->subYear(), Carbon::now()])
            ->get()
            ->flatMap(function ($order) {
                return $order->items->map(function ($item) {
                    return [
                        'product_id' => $item->product->id,
                        'product_name' => $item->product->product_name,
                        'quantity' => $item->quantity
                    ];
                });
            })
            ->groupBy('product_id')
            ->map(function ($items) {
                return [
                    'product_name' => $items->first()['product_name'],
                    'total_quantity' => $items->sum('quantity'),
                ];
            })
            ->sortByDesc('total_quantity')
            ->take(5)
            ->values();

        return response()->json([
            'monthly_revenue' => $revenueData,
            'most_sold_products' => $mostSoldProducts,
        ]);
    }

    public function mostSoldProducts(Request $request)
    {
        $mostSoldProducts = Order::with('items.product')
            ->where('status', 'delivered')
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
}
