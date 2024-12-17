<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\SalesController;
use App\Models\Product;
use App\Models\Category;
use App\Models\Message;
use App\Models\User;
use App\Models\Verification;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use PDF;

class AdminController extends Controller
{
    private function getLastMonthSales()
    {
        return OrderItem::join("products", "order_items.product_id", "=", "products.id")
            ->join("orders", "order_items.order_id", "=", "orders.id")
            ->where("orders.status", "delivered")
            ->whereMonth("orders.created_at", Carbon::now()->subMonth()->month)
            ->whereYear("orders.created_at", Carbon::now()->subMonth()->year)
            ->selectRaw("SUM(order_items.quantity * products.price + 60) as total_sales")
            ->value("total_sales");
    }

    private function getLastMonthOrders()
    {
        return Order::whereMonth("created_at", Carbon::now()->subMonth()->month)
            ->whereMonth("created_at", Carbon::now()->subMonth()->year)
            ->where('status', 'delivered')
            ->count();
    }

    private function getLastMonthCustomers() 
    {
        return User::where("usertype", "user")
            ->whereYear("created_at", Carbon::now()->subMonth()->month)
            ->whereYear("created_at", Carbon::now()->subMonth()->year)
            ->count();
    }

    public function dashboard()
    {
        $userCount = User::where("usertype", "user")->count();
        $orderCount = Order::count();
        $salesController = new SalesController();

        $totalSales = OrderItem::join(
            "products",
            "order_items.product_id",
            "=",
            "products.id"
        )
            ->join("orders", "order_items.order_id", "=", "orders.id")
            ->where("orders.status", "delivered")
            ->selectRaw(
                "SUM(order_items.quantity * products.price + 60) as total_sales"
            )
            ->value("total_sales");

        $lastMonthSales = $this->getLastMonthSales();
        $lastMonthOrders = $this->getLastMonthOrders();
        $lastMonthCustomers = $this->getLastMonthCustomers();

        $salesChange = $totalSales - $lastMonthSales;
        $orderChange = $orderCount - $lastMonthOrders;
        $customerChange = $userCount - $lastMonthCustomers;

        $salesData = [];
        $salesMonths = [];
        for ($i = 0; $i < 12; $i++) {
            $month = Carbon::now()->subMonths($i)->format('M');
            $salesMonths[] = $month;

            $monthlySales = OrderItem::join("products", "order_items.product_id", "=", "products.id")
                ->join("orders", "order_items.order_id", "=", "orders.id")
                ->where("orders.status", "delivered")
                ->whereMonth("orders.created_at", Carbon::now()->subMonths($i)->month)
                ->whereYear("orders.created_at", Carbon::now()->subMonths($i)->year)
                ->selectRaw("SUM(order_items.quantity * products.price + 60) as total_sales")
                ->value("total_sales");

            $salesData[] = $monthlySales ?? 0;
        }

        $orders = Order::with("user")
            ->orderBy("created_at", "desc")
            ->limit(15)
            ->get();

        return view(
            "admin.dashboard",
            compact("orders", "totalSales", "orderCount", "userCount", "salesChange", "orderChange", "customerChange", "lastMonthSales", "lastMonthOrders", "lastMonthCustomers", "salesData", "salesMonths")
        );
    }


    public function customer()
    {
        // Fetch customers along with their verification information
        $customers = User::where("usertype", "user")
            ->with("verification")
            ->get();

            foreach ($customers as $customer) {
                $customer->checkBlockedStatus(); // Use the method from User model
            }

        $verifiedUsers = $customers->filter(function ($customer) {
            return $customer->verification && $customer->verification->verified;
        });

        $notVerifiedUsers = $customers->filter(function ($customer) {
            return !$customer->verification ||
                !$customer->verification->verified;
        });

        return view(
            "admin.customer",
            compact("verifiedUsers", "notVerifiedUsers")
        );
    }

    public function order()
    {
        // Fetch all orders from the database
        $orders = Order::with("user")->orderBy("created_at", "desc")->get();

        // Pass the orders to the view
        return view("admin.order", compact("orders"));
    }

    public function show($orderId)
    {
        // Retrieve the order object along with its items and products using eager loading
        $order = Order::with("items")->find($orderId);

        // Check if the order exists
        if ($order) {
            // Pass the order object to the view
            // Loop through each item and retrieve its associated product
            foreach ($order->items as $item) {
                // Load the product associated with the item
                $item->product = Product::find($item->product_id);
            }
            return view("admin.order_details", compact("order"));
        } else {
            // Handle the case where the order is not found
            return redirect()
                ->route("admin.orders")
                ->with("error", "Order not found.");
        }
    }

    public function message()
    {
        $messages = Message::where("notified", false)->get();

        foreach ($messages as $message) {
            $message->notified = true;
            $message->save();
        }

        return view("admin.message", ["messages" => $messages]);
    }

    public function checkNewMessages()
    {
        $newMessagesCount = Message::where("is_read", false)->count();
        return response()->json(["new_messages_count" => $newMessagesCount]);
    }

    public function getMessages(Request $request)
    {
        $senderId = $request->input("sender_id");

        $messages = Message::where("sender_id", $senderId)->get();

        return response()->json(["messages" => $messages]);
    }

    public function product()
    {
        $products = Product::paginate(8);

        return view("admin.product", compact("products"));
    }

    public function addProduct()
    {
        $category = Category::all();
        return view("admin.add-product", compact("category"));
    }

    public function edit(string $id)
    {
        $product = Product::find($id);
        $categories = Category::all();

        return view("admin.product-update", compact("product", "categories"));
    }

    public function profile()
    {
        $user = Auth::user();
        return view("admin.profile", compact("user"));
    }

    public function viewUserImages($userId)
    {
        $userImages = Verification::where("user_id", $userId)->get();

        return view("admin.view_user_images", compact("userImages"));
    }

    public function verifyImage(Request $request)
    {
        $request->validate([
            "image_id" => "required|exists:verifications,id",
            "action" => "required|in:verify,reject",
        ]);

        $image = Verification::findOrFail($request->input("image_id"));

        if ($request->input("action") === "verify") {
            $image->update([
                "verified" => true,
                "status" => "verified",
                "notified" => true,
                "notifiedbyuser" => false,
            ]);
            return redirect()
                ->route("customer")
                ->with("success", "Image verified successfully.");
        } elseif ($request->input("action") === "reject") {
            if ($image->valid_id1 && Storage::disk('public')->exists($image->valid_id1)) {
                Storage::disk('public')->delete($image->valid_id1);
            }
            $image->update([
                "verified" => false,
                "status" => "rejected",
                "notified" => true,
                "notifiedbyuser" => false,
            ]);
            return redirect()
                ->route("customer")
                ->with("success", "Image rejected successfully.");
        }
        return redirect()->back()->with("error", "Invalid action.");
    }

    public function updateStatus(Request $request)
    {
        $orderId = $request->input("order_id");
        $status = $request->input("status");

        $order = Order::find($orderId);
        if ($order) {
            $order->status = $status;
            $order->notifiedbyuser = false;
            $order->save();
            return response()->json(["success" => true]);
        } else {
            return response()->json(
                ["success" => false, "message" => "Order not found"],
                404
            );
        }
    }

    public function fetchOrdersAndVerification()
    {
        // Eager load the user for orders and verifications
        $orders = Order::with("user")
            ->get()
            ->map(function ($order) {
                $order->type = "order";
                $order->action =
                    $order->status === "cancelled" ? "cancelled" : "processing";
                return $order;
            });

        $verifications = Verification::with("user")
            ->get()
            ->map(function ($verification) {
                $verification->type = "verification";
                return $verification;
            });

        $newOrders = $orders->filter(function ($order) {
            return !$order->notified;
        });

        $newVerifications = $verifications->filter(function ($verification) {
            return !$verification->notified;
        });

        foreach ($newOrders as $order) {
            $order->notified = true;
            $order->save();
        }

        foreach ($newVerifications as $verification) {
            $verification->notified = true;
            $verification->save();
        }

        $notifications = $orders
            ->merge($verifications)
            ->sortByDesc("created_at");

        return view(
            "admin.notification",
            compact(
                "newOrders",
                "orders",
                "newVerifications",
                "verifications",
                "notifications"
            )
        );
    }

    public function fetchOrdersAndVerifications()
    {
        $newOrders = Order::where("notified", false)->get();
        foreach ($newOrders as $order) {
            $order->notified = true;
            $order->save();
        }

        $newVerifications = Verification::with("user")
            ->where("notified", false)
            ->get();
        foreach ($newVerifications as $verification) {
            $verification->notified = true;
            $verification->save();
        }

        return response()->json([
            "newOrders" => $newOrders,
            "newVerifications" => $newVerifications,
        ]);
    }

    public function filter(Request $request)
    {
        $status = $request->query('status');

        $validStatuses = ['all', 'in-queue', 'processing', 'on-deliver', 'delivered', 'cancelled'];
        if (!in_array($status, $validStatuses)) {
            return response()->json(['success' => false, 'message' => 'Invalid status'], 400);
        }

        try {
            $orders = $status === 'all' 
                ? Order::with('user')->get() 
                : Order::with('user')->where('status', $status)->get();

            return response()->json(['success' => true, 'orders' => $orders, 'status' => $status]);
        } catch (\Exception $e) {
            \Log::error('Error fetching orders: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error fetching orders: ' . $e->getMessage()], 500);
        }
    }

    public function productFilter(Request $request)
    {
        $filter = $request->input('filter');
        $products = Product::with('category')
            ->when($filter && $filter !== 'all', function ($query) use ($filter) {
                $query->whereHas('category', function ($q) use ($filter) {
                    $q->where('category_name', $filter);
                });
            })
            ->paginate(10);

        return response()->json([
            'products' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'product_name' => $product->product_name,
                    'price' => $product->price,
                    'photo' => asset($product->photo),
                    'category_name' => $product->category->category_name,
                ];
            }),
        ]);
    }


    public function fetchNewVerifications()
    {
        $newVerifications = Verification::with("user")
            ->where("notified", false)
            ->get();

        foreach ($newVerifications as $verification) {
            $verification->notified = true;
            $verification->save();
        }

        return response()->json(["newVerifications" => $newVerifications]);
    }

    public function justFetchOrders()
    {
        $orders = Order::with("user")->where("notified", false)->get();

        return response()->json(["orders" => $orders]);
    }

    public function justFetchVerifications()
    {
        $verifications = Verification::with("user")
            ->where("notified", false)
            ->get();

        return response()->json(["verifications" => $verifications]);
    }

    public function adminGetCountNotif()
    {
        try {
            $unreadVerification = Verification::where(
                "notified",
                false
            )->count();
            $unreadOrder = Order::where("notified", false)->count();

            return response()->json([
                "unreadVerification" => $unreadVerification,
                "unreadOrder" => $unreadOrder,
            ]);
        } catch (\Exception $e) {
            \Log::error(
                "Error fetching notification counts: " . $e->getMessage()
            );
            return response()->json(["error" => "server error"], 500);
        }
    }

    public function getUsers()
    {
        try {
            $users = User::where("usertype", "user")->get();
            return response()->json(["users" => $users]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage(), 500]);
        }
    }

    public function OrderStatus() {
        try {
            $orders = Order::all();
    
            if ($orders->isEmpty()) {
                return response()->json([
                    "status" => "error",
                    "message" => "Order not found"
                ], 400);
            }
    
            $orderStatus = $orders->map(function ($order) {
                return [
                    "id" => $order->id, 
                    "status" => $order->status,
                ];
            });
    
            return response()->json(['orders' => $orderStatus]);
        } catch (\Exception $e) {
            \Log::error("Error fetching order status: " . $e->getMessage());
            return response()->json([
                "status" => "error",
                "message" => "An error occurred"
            ], 500);
        }
    }
    
    public function users()
    {
        return view("admin.users");
    }

    public function blockUser($userId)
    {
        $user = User::findOrFail($userId);

        if ($user) {
            $user->is_blocked = true;
            $user->blocked_until = now()->addHour();
            $user->save();

            return redirect()->back()->with('success', 'User blocked successfully!');
        }
        
        return redirect()->back()->with('error', 'Something went wrong when blocking the user!');
    }

    public function sendReceipt(Request $request, $id)
    {
        if ($request->has('send_receipt')) {
            $order = Order::findOrFail($id);

            Mail::to($order->user->email)->send(new \App\Mail\ReceiptMail($order));

            return back()->with('success', 'Receipt sent successfully');
        }

        return back()->with('error', 'Receipt sending failed');
    }

    public function reports(Request $request)
    {
        //last 7 days
        $defaultStartDate = Carbon::now()->subDays(7)->startOfDay();
        $defaultEndDate = Carbon::now()->endOfDay();
    
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : $defaultStartDate;
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : $defaultEndDate;
    
        $salesPerDay = OrderItem::join("products", "order_items.product_id", "=", "products.id")
            ->join("orders", "order_items.order_id", "=", "orders.id")
            ->where("orders.status", "delivered")
            ->whereBetween("orders.created_at", [$startDate, $endDate])
            ->selectRaw("DATE(orders.created_at) as date, SUM(order_items.quantity * products.price + 60) as sales")
            ->groupBy("date")
            ->orderBy("date", "asc")
            ->get();
    
        $totalSales = $salesPerDay->sum('sales');
    
        return view('admin.report', compact('salesPerDay', 'totalSales', 'startDate', 'endDate'));
    }
    
    public function downloadReport(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::now()->subDays(7)->startOfDay();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfDay();
    
        $salesPerDay = OrderItem::join("products", "order_items.product_id", "=", "products.id")
            ->join("orders", "order_items.order_id", "=", "orders.id")
            ->where("orders.status", "delivered")
            ->whereBetween("orders.created_at", [$startDate, $endDate])
            ->selectRaw("DATE(orders.created_at) as date, SUM(order_items.quantity * products.price + 60) as sales")
            ->groupBy("date")
            ->orderBy("date", "asc")
            ->get();
    
        $totalSales = $salesPerDay->sum('sales');
    
        $chartImage = $request->input('chart_image');
    
        $data = [
            'salesPerDay' => $salesPerDay,
            'totalSales' => $totalSales,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'chartImage' => $chartImage,
        ];
    
        $pdf = PDF::loadView('admin.pdf_report', $data);
    
        return $pdf->download('sales_report.pdf');
    }

}
