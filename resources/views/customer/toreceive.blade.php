<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-8 text-center">Orders to Receive</h1>
        
        <ul class="space-y-6">
            @foreach ($orders as $order)
                @php
                    $totalPrice = $order->items->sum(function ($item) {
                        return $item->quantity * $item->product->price;
                    });
                @endphp
                <li class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-all">
                    <div class="flex flex-col space-y-4">
                        <div class="flex justify-between items-center">
                            <div class="text-lg font-semibold text-gray-800">Order Status: 
                              @if ($order->status === "on-deliver") 
                                <span class="font-medium text-blue-600">On Deliver</span>
                              @endif
                            </div>
                            <button onclick="markAsReceived({{ $order->id }})" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                Mark as Received
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            @foreach ($order->items as $item)
                                <div class="flex bg-gray-100 p-4 rounded-lg shadow-sm hover:shadow-md transition">
                                    <img src="{{ $item->product->photo }}" alt="{{ $item->product->name }}" class="w-24 h-24 object-cover rounded-md">
                                    <div class="flex-1 pl-4">
                                        <p class="text-xl font-medium text-gray-800">{{ $item->product->name }}</p>
                                        <p class="text-gray-600">Price: ₱{{ number_format($item->product->price, 2) }}</p>
                                        <p class="text-gray-600">Quantity: {{ $item->quantity }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-6 flex items-center justify-between bg-gray-50 p-4 rounded-lg shadow-sm">
                            <p class="text-lg font-semibold text-gray-800">Total Price: ₱{{ number_format($totalPrice, 2) }}</p>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <script>
        async function markAsReceived(orderId) {
            try {
                const response = await fetch(`/orders/${orderId}/mark-as-received`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok.');
                }

                const data = await response.json();
                console.log('Updated successfully');
                location.reload();
            } catch (error) {
                console.error('Error marking the order as received:', error);
            }
        }
    </script>
</x-app-layout>
