<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @forelse ($orders as $order)
                    <div class="mb-8 order-container" data-order-id="{{ $order->id }}">
                        <p>{{ __('Order Date: ') }}{{ $order->created_at->format('Y-m-d') }}</p>
                        <p>{{ __('Status: ') }}<span class="order-status">{{ ucfirst($order->status) }}</span></p> <!-- Display status -->

                        <table class="min-w-full leading-normal mt-4">
                            <thead>
                                <tr>
                                    <th scope="col" class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Product
                                    </th>
                                    <th scope="col" class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Image
                                    </th>
                                    <th scope="col" class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Quantity
                                    </th>
                                    <th scope="col" class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Total
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($order->items as $item)
                                    <tr>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            {{ $item->product->product_name }}
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <img src="{{ asset($item->product->photo) }}" alt="{{ $item->product->product_name }}" class="w-16 h-16">
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            {{ $item->quantity }}
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            ₱{{ number_format($item->quantity * $item->product->price, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            No items found for this order.
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>

                        @if ($order->status === 'delivered')
                            <button class="cancel-order-btn bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded mt-4">Cancel Order</button>
                        @endif
                    </div>
                @empty
                    <div class="mb-8">
                        <p>{{ __('You have no orders.') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function fetchOrderStatus() {
                let orderContainers = document.querySelectorAll('.order-container');

                orderContainers.forEach(function(container) {
                    let orderId = container.dataset.orderId;
                    let xhr = new XMLHttpRequest();
                    xhr.open('GET', `/order/${orderId}/status`, true);

                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            let response = JSON.parse(xhr.responseText);
                            container.querySelector('.order-status').textContent = response.status;

                            // Display total amount
                            container.querySelector('.order-total').textContent = '$' + response.total_amount.toFixed(2);

                            // Enable cancellation button if status is 'delivered'
                            if (response.status.toLowerCase() === 'delivered') {
                                let cancelBtn = container.querySelector('.cancel-order-btn');
                                if (cancelBtn) {
                                    cancelBtn.disabled = false;
                                }
                            }
                        }
                    }

                    xhr.send();
                });
            }

            // Handle cancellation button click
            document.addEventListener('click', function(event) {
                if (event.target.classList.contains('cancel-order-btn')) {
                    let orderId = event.target.closest('.order-container').dataset.orderId;
                    let xhr = new XMLHttpRequest();
                    xhr.open('POST', `/order/${orderId}/cancel`, true);
                    xhr.setRequestHeader('Content-Type', 'application/json');

                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            // Optionally update UI or show a confirmation message
                            console.log('Order cancelled successfully.');
                        }
                    }

                    xhr.send(JSON.stringify({ orderId: orderId }));
                }
            });

            setInterval(fetchOrderStatus, 5000);
        });
    </script>
</x-app-layout>
