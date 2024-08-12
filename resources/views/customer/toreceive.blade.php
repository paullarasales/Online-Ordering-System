<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Orders to Receive</h1>
        <ul class="space-y-4">
            @foreach ($orders as $order)
                @php
                    $totalPrice = $order->items->sum(function ($item) {
                        return $item->quantity * $item->product->price;
                    });
                @endphp
                <li class="flex flex-col bg-white p-4 rounded shadow-md">
                    <div class="flex items-start space-x-4">
                        <img src="{{ $order->items->first()->product->photo }}" alt="{{ $order->items->first()->product->name }}" class="w-24 h-24 object-cover rounded">
                        <div class="flex-1">
                            <p class="text-lg font-semibold">{{ $order->items->first()->product->product_name }}</p>
                            <p class="text-gray-600">Status: <span class="font-medium text-blue-600">{{ $order->status }}</span></p>
                            <div class="mt-4">
                                @foreach ($order->items as $item)
                                    <div class="mb-2">
                                        <p class="font-medium">{{ $item->product->name }}</p>
                                        <p class="text-gray-700">Quantity: {{ $item->quantity }}</p>
                                    </div>
                                @endforeach
                                <!-- Total Price and Button outside the item loop -->
                                <div class="flex flex-row items-center justify-between w-full mt-4">
                                    <p class="font-semibold text-gray-800">Total Price: ₱{{ number_format($totalPrice, 2) }}</p>
                                    <button onclick="markAsReceived({{ $order->id }})" class="h-10 w-36 bg-red-500 text-white font-medium rounded">Order Received</button>
                                </div>
                            </div>
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
