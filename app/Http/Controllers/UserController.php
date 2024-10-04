<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Verification;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Message;

class UserController extends Controller
{
    public function index()
    {
        $products = Product::paginate(8);
        return view("customer.dashboard", compact("products"));
    }

    public function notification()
    {
        $user = Auth::user();

        $verifications = Verification::where("user_id", $user->id)
            ->where("notifiedbyuser", false)
            ->get();

        foreach ($verifications as $verification) {
            $verification->notifiedbyuser = true;
            $verification->save();
        }

        $orders = Order::where("user_id", $user->id)
            ->where("notifiedbyuser", false)
            ->get();

        foreach ($orders as $order) {
            $order->notifiedbyuser = true;
            $order->save();
        }
        return view("customer.notification", [
            "verifications" => $verifications,
            "orders" => $orders,
        ]);
    }
    
    public function profile()
    {
        $user = Auth::user();
        return view("customer.profile", compact("user"));
    }

    public function verifyAccountForm()
    {
        $userVerification = Verification::where(
            "user_id",
            auth()->id()
        )->first();
        return view("customer.verification", compact("userVerification"));
    }

    public function verificationTest() {
        return view('customer.verificationTest');
    }

    public function verificationStatus()
    {
        $userVerification = Verification::where("user_id", auth()->id())->first();

        if ($userVerification) {
            if ($userVerification->status === 'rejected') {
                return response()->json([
                    'status' => 'rejected',
                    'message' => 'Your verification was rejected. Please upload a new ID.'
                ]);
            } else if ($userVerification->status === 'verified') {
                return response()->json([
                    'status' => 'verified',
                    'message' => 'Your account is verified'
                ]);
            } else if ($userVerification->status === 'pending') {
                return response()->json([
                    'status' => 'pending',
                    'message' => 'Please wait while we verify your account.'
                ]);
            } 
        } else {
            return response()->json([
                'status' => 'none',
                'message' => 'Your account is not verified. Please upload two valid valid IDs.'
            ]);
        }
    }


    public function verify(Request $request)
    {
        $request->validate([
            "valid_id1" => "required|image|mimes:jpg,png,jpeg|max:2048",
        ]);
    
        $user = Auth::user();

        $existingVerification = Verification::where("user_id", $user->id)->first();
    
        if ($existingVerification && $existingVerification->status === 'pending') {
            return redirect()
                ->route("verify.message")
                ->with("status", "You cannot submit another ID until the previous one is reviewed.");
        }
    
        if ($existingVerification && $existingVerification->status === 'rejected') {
            $existingVerification->status = 'pending';
            $existingVerification->notified = false;
            $existingVerification->notifiedbyuser = false;
    
            if ($request->hasFile("valid_id1")) {
                $file = $request->file("valid_id1");
                $filename = time() . "." . $file->getClientOriginalExtension();
                $path = $file->storeAs("verifications", $filename, "public");
                $existingVerification->valid_id1 = $path;
            }
    
            $existingVerification->save();
    
            return redirect()->route("verify.message")->with("success", "Verification resubmitted successfully.");
        }
    
        if (!$existingVerification) {
            $validId = new Verification();
            $validId->user_id = $user->id;
            $validId->notified = false;
            $validId->status = "pending";
            $validId->notifiedbyuser = false;
    
            if ($request->hasFile("valid_id1")) {
                $file = $request->file("valid_id1");
                $filename = time() . "." . $file->getClientOriginalExtension();
                $path = $file->storeAs("verifications", $filename, "public");
                $validId->valid_id1 = $path;
            }
    
            $validId->save();
        }
    
        return redirect()->route("verify.message")->with("success", "Verification submitted successfully.");
    }
    
    public function verifyMessage()
    {
        return view("customer.verification-message");
    }

    public function addToCart($productId)
    {
        $validatedData = $request->validate([
            'address' => 'required|string|max:255',
            'contactno' => 'required|string|max:15',
            'payment_method' => 'required',
        ], [
            'address.required' => 'The address field is required.',
            'contactno.required' => 'The contact number field is required.',
        ]);

        $userId = Auth::id();
        
        if (!$userId) {
            return redirect()->route("login")->with("error", "Please log in first");
        }

        $cart = Cart::firstOrCreate(["user_id" => $userId]);

        $verification = Verification::where("user_id", $userId)->first();
        if (!$verification || !$verification->verified) {
            return redirect()->route("verify.form")->with("error", "Please verify your account first.");
        }
        
        $cartItems = $cart->items;
        if ($cartItems->contains("product_id", $productId)) {
            return redirect()->route("cart")->with("success", "Product is already in the cart.");
        }

        $cart->items()->create([
            "user_id" => $userId,
            "product_id" => $productId,
            "quantity" => 1,
        ]);

        return redirect()->route("userdashboard")->with("success", "Product added successfully");
    }

    public function addToCartPage()
    {
        $userId = Auth::id();
        $cart = Cart::firstOrCreate(['user_id' => $userId]);
        $cartItems = $cart->items()->with('product')->get();

        $unnotified = CartItem::where('cart_id', $cart->id)
                            ->where('notified', false)
                            ->get();

        foreach ($unnotified as $item) {
            $item->notified = true;
            $item->save();
        }

        return view('customer.cart', [
            'cartItems' => $cartItems,
            'unnotified' => $unnotified
        ]);
    }
 
    public function updateQuantity(Request $request, $cartItemId)
    {
        $request->validate([
            "quantity" => "required|integer|min:1",
        ]);

        try {
            // Find the cart item by ID
            $cartItem = CartItem::findOrFail($cartItemId);

            $newTotalPrice =
                $cartItem->product->price * $request->input("quantity");

            // Update the quantity
            $cartItem->quantity = $request->input("quantity");
            $cartItem->total_price = $newTotalPrice;
            $cartItem->save();

            // Return a success response
            return response()->json([
                "message" => "Quantity updated successfully",
            ]);
        } catch (\Exception $e) {
            return response()->json(
                ["error" => "Failed to update quantity"],
                500
            );
        }
    }

    public function prepareCheckout(Request $request)
    {
        $cartItems = CartItem::where("user_id", auth()->id())
            ->with("product")
            ->get();

        $totalPrice = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price + 60;
        });

        $paymentMethod = "Cash On Delivery";

        return view(
            "customer.confirmation",
            compact("cartItems", "totalPrice", "paymentMethod")
        );
    }

    public function createOrder(Request $request)
    {
        // Fetch cart items from the request
        $cartItemsData = $request->input("cartItems");

        // Create a new order
        $order = new Order();
        $order->user_id = auth()->id();
        $order->address = $request->input("address");
        $order->contactno = $request->input("contactno");
        $order->payment_method = $request->input("payment_method");
        $order->notified = false;
        $order->markasreceived = false;
        $order->notifiedbyuser = false;
        $order->save();

        // if ($request->input("address") || $request->input("contactno") === "") {
        //     return back()->with('Error', 'Please fill out the form.');
        // } else {
        //     $order->address = $request->input("address");
        //     $order->contactno = $request->input("contactno");
        // }

        foreach ($cartItemsData as $cartItemId => $cartItemData) {
            $order->items()->create([
                "product_id" => $cartItemData["product_id"],
                "quantity" => $cartItemData["quantity"],
            ]);
        }

        CartItem::where("user_id", auth()->id())->delete();

        return redirect()->route("thankyou", ["orderId" => $order->id]);
    }

    public function thankyou($orderId)
    {
        $order = Order::with("items")->find($orderId);

        if ($order) {
            foreach ($order->items as $item) {
                $item->product = Product::find($item->product_id);
            }
            return view("customer.thankyou", compact("order"));
        } else {
            return redirect()->route("home")->with("error", "Order not found.");
        }
    }

    public function viewOrder()
    {
        $userId = auth()->id();

        $orders = Order::where("user_id", $userId)
            ->with("items.product")
            ->get();

        return view("customer.myorder", compact("orders"));
    }

    public function toReceive()
    {
        $userId = auth()->id();

        $orders = Order::where("user_id", $userId)
            ->with("items.product")
            ->where("status", "on deliver")
            ->get();

        return view("customer.toreceive", compact("orders"));
    }

    public function getToReceiveCount()
    {
        try {
            $userId = auth()->id();

            $unreadToReceive = Order::where("user_id", $userId)
                ->where("status", "on deliver")
                ->where("markasreceived", false)
                ->count();

            return response()->json(["unreadToReceive" => $unreadToReceive]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function markAsReceived($orderId)
    {
        try {
            $order = Order::findOrFail($orderId);

            $order->markasreceived = true;
            $order->status = "delivered";
            $order->save();

            return response()->json([
                "success" => "Order marked as received successfully",
            ]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function fetchOrderStatus($orderId)
    {
        $order = Order::findOrFail($orderId);

        // Calculate total amount
        $totalAmount = $order->items->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return response()->json([
            "status" => $order->status,
            "total_amount" => $totalAmount,
        ]);
    }

    public function cancelOrder(Request $request, Order $order)
    {
        if (strcasecmp($order->status, "processing") === 0) {
            $order->status = "cancelled";
            $order->notified = false;
            $order->notifiedbyuser = false;
            $order->save();
        }
        return response()->json(["status" => $order->status]);
    }

    public function getImageStatus()
    {
        $user = Auth::user();
        $verification = $user->verification()->latest()->first();

        if (!$verification) {
            return response()->json(
                ["status" => "error", "message" => "Image not found"],
                404
            );
        }

        return response()->json(["status" => $verification->status]);
    }

    public function getCountNotif()
    {
        $user = Auth::user();

        $unreadCount = Verification::where("user_id", $user->id)
            ->where("notifiedbyuser", false)
            ->count();

        $unreadOrderCount = Order::where("user_id", $user->id)
            ->where("notifiedbyuser", false)
            ->count();

        return response()->json([
            "unreadCount" => $unreadCount,
            "unreadOrderCount" => $unreadOrderCount,
        ]);
    }

    public function getOrderStatus()
    {
        try {
            $user = Auth::user();
            $orders = $user->orders()->with("items.product")->get();

            if ($orders->isEmpty()) {
                return response()->json(
                    ["status" => "error", "message" => "Order not found"],
                    400
                );
            }

            $ordersData = $orders->map(function ($order) {
                return [
                    "status" => $order->status,
                    "products" => $order->items->map(function ($item) {
                        return [
                            "product_name" => $item->product->product_name,
                        ];
                    }),
                ];
            });

            return response()->json(["orders" => $ordersData]);
        } catch (\Exception $e) {
            // Log the exception message for debugging
            \Log::error("Error fetching order status: " . $e->getMessage());
            return response()->json(
                ["status" => "error", "message" => "An error occurred"],
                500
            );
        }
    }

    public function messages()
    {
        $messages = Message::where("notifiedbyuser", false)->get();

        foreach ($messages as $message) {
            $message->notifiedbyuser = true;
            $message->save();
        }
        return view("chat.index", ["messages" => $messages]);
    }

    public function addToCartCount()
    {
        try {
            $count = CartItem::where("notified", false)->count();
            return response()->json(["count" => $count]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function filter(Request $request)
    {
        $filter = $request->input('filter', 'all');

        if ($filter === 'all') {
            $products = Product::with("category")->get();
        } else {
            $products = Product::whereHas("category", function ($query) use ($filter) {
                $query->where("category_name", $filter);
            })->with("category")->get();
        }

        return response()->json([
            'products' => $products
        ]);
    }
}
