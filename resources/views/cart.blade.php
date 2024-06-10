<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 py-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('userdashboard') }}" class="group">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 mr-2 mb-4 group-hover:stroke-blue-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                </svg>
            </a>
            <h1 class="text-3xl font-bold mb-4">My Cart</h1>
        </div>
        
        @php 
            $totalPrice = 0; 
        @endphp
        @if ($cartItems->count() > 0)
            <form action="{{ route('checkout') }}" method="POST">
                @csrf
                <ul class="divide-y divide-gray-200">
                    @foreach ($cartItems as $cartItem)
                        @php
                            // Calculate price for each item
                            $itemPrice = $cartItem->product->price * $cartItem->quantity;
                            // Accumulate total price
                            $totalPrice += $itemPrice;
                        @endphp
                        <li class="flex items-center py-4">
                            <div class="flex-1">
                                <h2 class="text-lg font-semibold">{{ $cartItem->product->product_name }}</h2>
                                <p class="text-gray-600">
                                    Quantity: 
                                    <button type="button" class="text-gray-600" onclick="updateQuantity({{ $cartItem->id }}, -1)">-</button>
                                    <span id="quantity_{{ $cartItem->id }}">{{ $cartItem->quantity }}</span>
                                    <button type="button" class="text-gray-600" onclick="updateQuantity({{ $cartItem->id }}, 1)">+</button>
                                </p>
                                <p class="text-gray-600">Price: ₱<span id="price_{{ $cartItem->id }}">{{ $itemPrice }}</span></p>
                            </div>
                            <img class="w-24 h-24 object-cover rounded-lg" src="{{ $cartItem->product->photo }}" alt="{{ $cartItem->product->product_name }} Photo">
                        </li>
                    @endforeach
                </ul>
                <!-- Display total price -->
                <p class="text-gray-600 mt-4">Total Price: ₱<span id="total_price">{{ $totalPrice }}</span></p>
                <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Checkout</button>
            </form>
        @else
            <p class="text-gray-600">Your cart is empty.</p>
        @endif
    </div>

    <script>
        function updatePrice() {
            let totalPrice = 0;
            let cartItems = document.querySelectorAll('[id^=quantity_]');
    
            cartItems.forEach(function(cartItem) {
                let quantity = parseInt(cartItem.innerText);
                let cartItemId = cartItem.id.split('_')[1];
                let price = parseFloat(document.getElementById('price_' + cartItemId).innerText);
                totalPrice += quantity * price;
            });
    
            document.getElementById('total_price').innerText = totalPrice.toFixed(2);
        }
    
        function updateQuantity(cartItemId, change) {
            var quantityElement = document.getElementById('quantity_' + cartItemId);
            var quantity = parseInt(quantityElement.innerText) + change;
    
            // Send AJAX request
            fetch("{{ url('/updateQuantity/cart') }}/" + cartItemId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ quantity: quantity })
            })
            .then(response => {
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error('Failed to update quantity');
                }
            })
            .then(data => {
                // Update UI with new quantity
                quantityElement.innerText = quantity;
                updatePrice();
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</x-app-layout>
