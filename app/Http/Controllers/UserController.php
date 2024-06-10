<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Verification;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;

class UserController extends Controller
{
    public function index()
    {
        $products = Product::paginate(8);
        return view('dashboard', compact('products'));
    }

    public function profile()
    {
        $user = Auth::user();

        return view('profile', compact('user'));
    }

    public function verifyAccountForm()
    {

        $userVerification = Verification::where('user_id', auth()->id())->first();
        // dd($user);
        return view('verification', compact('userVerification'));
    }

    public function verify(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'valid_id1' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'valid_id2' => 'required|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        $user = Auth::user();

        $validId = new Verification;
        $validId->user_id = $user->id;

        if ($request->hasFile('valid_id1') && $request->hasFile('valid_id2')) {
            $file1 = $request->file('valid_id1');
            $file2 = $request->file('valid_id2');

            $filename1 = time() . '_1.' . $file1->getClientOriginalExtension();
            $filename2 = time() . '_2.' . $file2->getClientOriginalExtension();

            $path1 = $file1->storeAs('verifications', $filename1, 'public');
            $path2 = $file2->storeAs('verifications', $filename2, 'public');

            $validId->valid_id1 = $path1;
            $validId->valid_id2 = $path2;
        }

        $validId->save();

        return redirect()->route('verify.message')->with('success');
    }

    public function verifyMessage()
    {
        return view('verification-message');
    }

    public function addToCartPage()
    {
        $userId = Auth::id();
        $cart = Cart::firstOrCreate(['user_id' => $userId]);
        $cartItems = $cart->items()->with('product')->get();

        return view('cart', compact('cartItems'));
    }

    public function addToCart($productId)
    {
        $userId = Auth::id();

        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        $verification = Verification::where('user_id', $userId)->first();
        // dd($verification);
        if (!$verification || !$verification->verified) {
            return redirect()->route('verify.form')->with('error', 'Please verify your account first');
        }

        $cartItems = $cart->items;

        if ($cartItems->contains('product_id', $productId)) {
            return redirect()->route('cart')->with('success', 'Product is already in the cart');
        } else {
            $cart->items()->create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => 1
            ]);

            $cartItems = $cart->items;

            return redirect()->route('userdashboard')->with('success', 'Product Added Successfully');
        }
    }

    public function updateQuantity(Request $request, $cartItemId)
    {
        // Validate the request data
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            // Find the cart item by ID
            $cartItem = CartItem::findOrFail($cartItemId);

            $newTotalPrice = $cartItem->product->price * $request->input('quantity');

            // Update the quantity
            $cartItem->quantity = $request->input('quantity');
            $cartItem->total_price = $newTotalPrice;
            $cartItem->save();

        // Return a success response
        return response()->json(['message' => 'Quantity updated successfully']);
        } catch (\Exception $e) {
            // Return an error response if something goes wrong
            return response()->json(['error' => 'Failed to update quantity'], 500);
        }
    }

    public function prepareCheckout(Request $request)
    {
        $cartItems = CartItem::where('user_id', auth()->id())->with('product')->get();

        $totalPrice = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        $paymentMethod = 'Cash On Delivery';

        return view('confirmation', compact('cartItems', 'totalPrice', 'paymentMethod'));
    }

    public function createOrder(Request $request)
    {
        // dd($request->all());
        // Fetch cart items from the request
        $cartItemsData = $request->input('cartItems');

        // Create a new order
        $order = new Order();
        $order->user_id = auth()->id();
        $order->address = $request->input('address');
        $order->payment_method = $request->input('payment_method');
        $order->save();

        foreach ($cartItemsData as $cartItemId => $cartItemData) {
            $order->items()->create([
                'product_id' => $cartItemData['product_id'],
                'quantity' => $cartItemData['quantity'],
            ]);
        }

        CartItem::where('user_id', auth()->id())->delete();


        return redirect()->route('thankyou', ['orderId' => $order->id]);
    }

    public function thankyou($orderId)
    {

        $order = Order::with('items')->find($orderId);


        if ($order) {

            foreach ($order->items as $item) {

                $item->product = Product::find($item->product_id);
            }

            // Pass the order object to the view
            return view('thankyou', compact('order'));
        } else {

            return redirect()->route('home')->with('error', 'Order not found.');
        }
    }
    
}
