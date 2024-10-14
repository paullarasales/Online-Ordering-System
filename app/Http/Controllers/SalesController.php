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
                        return $item->quantity * $item->product->price + 60; 
                    });
                }, 0);
            $revenueData[] = [
                'month' => Carbon::create()->month($month)->format('F Y'), 
                'revenue' => $totalRevenue,
            ];
        }

        $revenueData = array_reverse($revenueData);

        return response()->json([
            'monthly_revenue' => $revenueData,
        ]);
    }
}
