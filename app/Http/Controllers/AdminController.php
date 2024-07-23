<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Category;
use App\Models\Message;
use App\Models\User;
use App\Models\Verification;
use App\Models\Order;

class AdminController extends Controller
{
    public function dashboard() {
        return view('admin.dashboard');
    }

    public function customer() {

         // Fetch customers along with their verification information
        $customers = User::where('usertype', 'user')->with('verification')->get();

        $verifiedUsers = $customers->filter(function ($customer) {
            return $customer->verification && $customer->verification->verified;
        });

        $notVerifiedUsers = $customers->filter(function ($customer) {
            return !$customer->verification || !$customer->verification->verified;
        });

        return view('admin.customer', compact('verifiedUsers', 'notVerifiedUsers'));
    }


    public function order() {
        // Fetch all orders from the database
        $orders = Order::with('user')->orderBy('created_at', 'desc')->get();

        // Pass the orders to the view
        return view('admin.order', compact('orders'));
    }

    public function show($orderId)
    {
        // Retrieve the order object along with its items and products using eager loading
        $order = Order::with('items')->find($orderId);

        // Check if the order exists
        if ($order) {
            // Pass the order object to the view
            // Loop through each item and retrieve its associated product
            foreach ($order->items as $item) {
                // Load the product associated with the item
                $item->product = Product::find($item->product_id);
            }
            return view('admin.order_details', compact('order'));
        } else {
            // Handle the case where the order is not found
            return redirect()->route('admin.orders')->with('error', 'Order not found.');
        }
    }

    public function analytic() {
        return view('admin.analytics');
    }

    public function message() {

        $adminId = User::where('usertype', 'admin')->value('id');


        $customerMessages = Message::where('sender_id', '!=', $adminId)
                                    ->orderBy('created_at', 'desc')
                                    ->get();


        $adminMessages = Message::where('sender_id', $adminId)
                                ->orderBy('created_at', 'desc')
                                ->get();

        return view('admin.message', compact('customerMessages', 'adminMessages'));
    }

    public function checkNewMessages()
    {
        $newMessagesCount = Message::where('is_read', false)->count();
        return response()->json(['new_messages_count' => $newMessagesCount]);
    }

    public function getMessages(Request $request)
    {
        $senderId = $request->input('sender_id');


        $messages = Message::where('sender_id', $senderId)->get();

        return response()->json(['messages' => $messages]);
    }


    public function product() {
        $products = Product::paginate(8);

        return view('admin.product', compact('products'));
    }


    public function addProduct() {
        $category = Category::all();
        return view('admin.add-product', compact('category'));
    }

    public function edit(string $id)
    {
        $product = Product::find($id);
        $categories = Category::all();

        return view('admin.product-update', compact('product', 'categories'));
    }

    public function profile()
    {
        $user = Auth::user();
        // dd($user);
        return view('admin.profile', compact('user'));
    }

    public function viewUserImages($userId)
    {
        $userImages = Verification::where('user_id', $userId)->get();

        return view('admin.view_user_images', compact('userImages'));
    }

    public function verifyImage(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'image_id' => 'required|exists:verifications,id',
            'action' => 'required|in:verify,reject',
        ]);

        // Get the image record
        $image = Verification::findOrFail($request->input('image_id'));

        // dd($image);

        // Update the verified status based on the action
        if ($request->input('action') === 'verify') {
            $image->update(['verified' => true]);
        } else {

        }

        // Redirect back or return a response
        return redirect()->back()->with('success', 'Image verification status updated successfully.');
    }

    public function updateStatus(Request $request)
    {
        $orderId = $request->input('order_id');
        $status = $request->input('status');

        $order = Order::find($orderId);
        if ($order) {
            $order->status = $status;
            $order->save();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }
    }

    public function fetchOrdersAndVerification()
    {
        $orders = Order::all()->map(function ($order) {
            $order->type = 'order';
            return $order;
        });
    
        $verifications = Verification::all()->map(function ($verification) {
            $verification->type = 'verification';
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

        $notifications = $orders->merge($verifications)->sortByDesc('created_at');
    
        return view('admin.notification', compact('newOrders', 'orders', 'newVerifications', 'verifications', 'notifications'));
    }
    
    

    public function fetchNewOrders()
    {
        $newOrders = Order::where('notified', false)->get();

        foreach ($newOrders as $order) {
            $order->notified = true;
            $order->save();
        }

        return response()->json(['newOrders' => $newOrders]);
    }


    public function fetchNewVerifications()
    {
        $newVerifications = Verification::with('user')
                            ->where('notified', false)
                            ->get();

        foreach ($newVerifications as $verification) {
            $verification->notified = true;
            $verification->save();
        }

        return response()->json(['newVerifications' => $newVerifications]);
    }

    public function justFetchOrders()
    {
        $orders = Order::with('user')
                        ->where('notified', false)
                        ->get();

        return response()->json(['orders' => $orders]);
    }

    public function justFetchVerifications()
    {
        $verifications = Verification::with('user')
                        ->where('notified', false)
                        ->get();

        return response()->json(['verifications' => $verifications]);
    }
}
