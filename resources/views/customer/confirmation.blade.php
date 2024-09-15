<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-4">Confirm Order</h1>
        
        <ul class="divide-y divide-gray-200">
            @foreach ($cartItems as $cartItem)
                <li class="flex items-center py-4">
                    <div class="flex-1">
                        <h2 class="text-lg font-semibold">{{ $cartItem->product->product_name }}</h2>
                        <p class="text-gray-600">Quantity: {{ $cartItem->quantity }}</p>
                        <p class="text-gray-600">Price: ₱{{ $cartItem->product->price }}</p>
                        <p class="text-gray-600">Shipping Fee: ₱60</p>
                    </div>
                    <img class="w-24 h-24 object-cover rounded-lg" src="{{ $cartItem->product->photo }}" alt="{{ $cartItem->product->product_name }} Photo">
                </li>
            @endforeach
        </ul>

        <!-- Display total price -->
        <p class="text-gray-600 mt-4">Total Price: ₱{{ $totalPrice }}</p>

        <!-- Address input field -->
    <form action="{{ route('createOrder') }}" method="POST" class="mt-4">
        @csrf
        <div class="mb-4">
            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
            <input type="text" name="address" id="address" class="mt-1 p-2 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div class="mb-4">
            <label for="contactno" class="block text-sm font-medium text-gray-700">Contact Number</label>
            <input type="text" name="contactno" id="contactno" class="mt-1 p-2 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <!-- Method of payment -->
        <div class="mb-4">
            <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
            <select name="payment_method" id="payment_method" class="mt-1 p-2 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="Cash on delivery" selected>Cash on Delivery (COD)</option>
            </select>
        </div>

        <!-- Hidden input fields for cart items -->
        @foreach ($cartItems as $cartItem)
            <input type="hidden" name="cartItems[{{ $cartItem->id }}][product_id]" value="{{ $cartItem->product_id }}">
            <input type="hidden" name="cartItems[{{ $cartItem->id }}][quantity]" value="{{ $cartItem->quantity }}">
        @endforeach

        <!-- Confirm button -->
        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Confirm Order</button>
    </form>

    </div>
</x-app-layout>
