<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex items-center mb-8">
            <a href="{{ route('userdashboard') }}" class="group">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 mr-4 group-hover:stroke-blue-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                </svg>
            </a>
            <h1 class="text-4xl font-extrabold">My Cart</h1>
        </div>

        @php 
            $totalPrice = 0; 
        @endphp

        @if ($cartItems->count() > 0)
            <form action="{{ route('checkout') }}" method="POST">
                @csrf
                <ul class="divide-y divide-gray-300 mb-6">
                    @foreach ($cartItems as $cartItem)
                        @php
                            $itemPrice = $cartItem->product->price * $cartItem->quantity;
                            $totalPrice += $itemPrice;
                        @endphp
                        <li class="flex items-center py-6">
                            <!-- Image -->
                            <img class="w-24 h-24 object-cover rounded-md mr-6" src="{{ $cartItem->product->photo }}" alt="{{ $cartItem->product->product_name }} Photo">

                            <!-- Product Info -->
                            <div class="flex-1">
                                <h2 class="text-xl font-semibold text-gray-800">{{ $cartItem->product->product_name }}</h2>
                                
                                <!-- Quantity and price -->
                                <div class="flex items-center mt-2">
                                    <p class="text-gray-600 mr-4">Quantity:</p>
                                    <div class="flex items-center border border-gray-300 rounded-md">
                                        <button type="button" class="px-3 py-1" onclick="updateQuantity({{ $cartItem->id }}, -1)">-</button>
                                        <span id="quantity_{{ $cartItem->id }}" class="px-3 py-1">{{ $cartItem->quantity }}</span>
                                        <button type="button" class="px-3 py-1" onclick="updateQuantity({{ $cartItem->id }}, 1)">+</button>
                                    </div>
                                </div>
                                <p class="text-gray-600 mt-2">Price: ₱<span id="price_{{ $cartItem->id }}">{{ $itemPrice }}</span></p>
                            </div>

                            <!-- Remove Button -->
                            <button type="button" class="text-red-600 hover:text-red-800 ml-6" onclick="removeFromCart({{ $cartItem->id }})">
                                Remove
                            </button>
                        </li>
                    @endforeach
                </ul>

                <!-- Total Price -->
                <div class="flex justify-between items-center border-t border-gray-300 pt-6">
                    <p class="text-xl font-semibold text-gray-800">Total Price: ₱<span id="total_price">{{ $totalPrice }}</span></p>
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600">Proceed to Checkout</button>
                </div>
            </form>
        @else
            <p class="text-gray-600 text-center">Your cart is empty.</p>
        @endif
    </div>

    <script>
        function updatePrice() {
            let totalPrice = 0;
            document.querySelectorAll('[id^=quantity_]').forEach(cartItem => {
                const quantity = parseInt(cartItem.innerText);
                const cartItemId = cartItem.id.split('_')[1];
                const pricePerItem = parseFloat(document.getElementById('price_' + cartItemId).dataset.unitPrice);
                const newPrice = quantity * pricePerItem;
                document.getElementById('price_' + cartItemId).innerText = newPrice.toFixed(2);
                totalPrice += newPrice;
            });
            document.getElementById('total_price').innerText = totalPrice.toFixed(2);
        }

        function updateQuantity(cartItemId, change) {
            const quantityElement = document.getElementById('quantity_' + cartItemId);
            let quantity = parseInt(quantityElement.innerText) + change;
            if (quantity < 1) {
                quantity = 1; // Prevents going below 1
            }
            fetch("{{ url('/updateQuantity/cart') }}/" + cartItemId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ quantity: quantity })
            })
            .then(response => response.json())
            .then(data => {
                quantityElement.innerText = quantity;
                updatePrice();
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function removeFromCart(cartItemId) {
            fetch("{{ url('/removeFromCart') }}/" + cartItemId, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                location.reload(); // Reload the page after removal
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</x-app-layout>
