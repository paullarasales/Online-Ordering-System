<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-4">Thank You</h1>

        <!-- Display order details -->
        <h2 class="text-xl font-semibold mb-2">Order Details</h2>
        <p><strong>Order ID:</strong> {{ $order->id }}</p>
        <p><strong>User ID:</strong> {{ $order->user_id }}</p>
        <p><strong>Address:</strong> {{ $order->address }}</p>
        <p><strong>Payment Method:</strong> {{ $order->payment_method }}</p>

        <!-- Display order items -->
        <h2 class="text-xl font-semibold mt-4 mb-2">Order Items</h2>
        <ul class="divide-y divide-gray-200">
            @foreach ($order->items as $item)
                <li class="flex items-center py-2">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold">{{ optional($item->product)->product_name }}</h3>
                        <p class="text-gray-600">Quantity: {{ $item->quantity }}</p>
                    </div>
                    <p class="text-gray-600">Price: ₱{{ optional($item->product)->price }}</p>
                </li>
            @endforeach
        </ul>

        <!-- Close or Exit Button -->
        <div class="mt-8">
            <div class="mt-8">
                <a href="{{ route('userdashboard') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Close
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
