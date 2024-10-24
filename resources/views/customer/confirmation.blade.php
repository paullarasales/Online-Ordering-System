<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8 text-start">Confirm Order</h1>

        <div class="flex justify-between space-x-8">
            <div class="w-1/2">
                <form id="orderForm" action="{{ route('createOrder') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium text-gray-700">Shipping Address</label>
                        <input type="text" name="address" id="address" class="mt-1 p-2 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="House no., Street, Barangay, City/Municipality, Province, ZIP Code">
                        <p class="text-red-500 text-sm mt-1 hidden" id="addressError">Please enter your address.</p>
                    </div>

                    <div class="mb-4">
                        <label for="contactno" class="block text-sm font-medium text-gray-700">Contact Number</label>
                        <input type="text" name="contactno" id="contactno" class="mt-1 p-2 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="text-red-500 text-sm mt-1 hidden" id="contactError">Please enter your contact number.</p>
                    </div>

                    <div class="mb-4">
                        <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                        <p class="mt-1 p-2 block w-full border-gray-300 rounded-md bg-gray-100">Cash on Delivery (COD)</p>
                        <input type="hidden" name="payment_method" value="Cash on Delivery">
                    </div>

                    @foreach ($cartItems as $cartItem)
                        <input type="hidden" name="cartItems[{{ $cartItem->id }}][product_id]" value="{{ $cartItem->product_id }}">
                        <input type="hidden" name="cartItems[{{ $cartItem->id }}][quantity]" value="{{ $cartItem->quantity }}">
                    @endforeach

                    <div class="flex justify-between mt-6">
                        <a href="{{ route('userdashboard') }}" class="px-4 py-2 bg-white text-black border rounded hover:bg-gray-200">Back</a>

                        <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Confirm Order</button>
                    </div>
                </form>
            </div>
            <div class="w-1/2">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-2xl font-semibold mb-4">Order Summary</h2>

                    <ul class="divide-y divide-gray-200">
                        @foreach ($cartItems as $cartItem)
                            <li class="flex items-center py-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold">{{ $cartItem->product->product_name }}</h3>
                                    <p class="text-gray-600">Quantity: {{ $cartItem->quantity }}</p>
                                    <p class="text-gray-600">Price: ₱{{ $cartItem->product->price }}</p>
                                    <p class="text-gray-600">Shipping Fee: ₱60</p>
                                </div>
                                <img class="w-20 h-20 object-cover rounded-lg" src="{{ $cartItem->product->photo }}" alt="{{ $cartItem->product->product_name }} Photo">
                            </li>
                        @endforeach
                    </ul>

                    <p class="text-gray-600 mt-4">Total Price: ₱{{ $totalPrice }}</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('orderForm').addEventListener('submit', function(event) {
            document.getElementById('addressError').classList.add('hidden');
            document.getElementById('contactError').classList.add('hidden');
            
            let isValid = true;

            const address = document.getElementById('address').value.trim();
            if (!address) {
                document.getElementById('addressError').classList.remove('hidden');
                isValid = false;
            }

            const contactno = document.getElementById('contactno').value.trim();
            if (!contactno) {
                document.getElementById('contactError').classList.remove('hidden');
                isValid = false;
            }

            if (!isValid) {
                event.preventDefault();
            }
        });
    </script>
</x-app-layout>
