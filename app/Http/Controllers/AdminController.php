<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Category;
use App\Models\Message;
use App\Models\User;
use App\Models\Verification;
use App\Models\Order;
use App\Models\OrderItem;

class AdminController extends Controller
{
    public function dashboard()
    {
        $userCount = User::where("usertype", "user")->count();
        $orderCount = Order::count();

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
        $orders = Order::with("user")
            ->orderBy("created_at", "desc")
            ->limit(8)
            ->get();

        return view(
            "admin.dashboard",
            compact("orders", "totalSales", "orderCount", "userCount")
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
        $filter = $request->input("filter", "all");

        if ($filter === "all") {
            $products = Product::with("category")->get();
        } else {
            $products = Product::whereHas("category", function ($query) use (
                $filter
            ) {
                $query->where("category_name", $filter);
            })
                ->with("category")
                ->get();
        }
        return response()->json([
            'products' => $products
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

}
