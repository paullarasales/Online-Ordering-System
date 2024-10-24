<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('My Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md sm:rounded-lg p-6">
                @forelse ($orders as $order)
                    <div class="mb-8 p-4 border border-gray-200 rounded-lg shadow-sm hover:shadow-lg transition duration-200">
                        <div class="flex justify-between items-center mb-4">
                            <p class="text-gray-600">Order Date: <span class="font-semibold">{{ $order->created_at->format('Y-m-d') }}</span></p>
                            <span class="inline-block bg-blue-100 text-blue-700 text-sm px-3 py-1 rounded-full">{{ ucfirst($order->status) }}</span>
                        </div>
                        <table class="min-w-full leading-normal mb-4">
                            <thead>
                                <tr>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Product</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Quantity</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $item->product->product_name }}</td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><img src="{{ asset($item->product->photo) }}" class="w-16 h-16 rounded-lg" alt="{{ $item->product->product_name }}"></td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $item->quantity }}</td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">₱{{ number_format($item->quantity * $item->product->price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if (in_array(strtolower($order->status), ['processing', 'in-queue']))
                            <button class="cancel-order-btn bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded mt-2 transition duration-200">Cancel Order</button>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500">You have no orders.</p>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            async function fetchOrderStatus() {
                let orderContainers = document.querySelectorAll('.order-container');

                for (let container of orderContainers) {
                    let orderId = container.dataset.orderId;

                    try {
                        const response = await fetch(`/order/${orderId}/status`);

                        if (!response.ok) {
                            throw new Error('Failed to fetch order status');
                        }

                        const data = await response.json();
                        const orderStatusElement = container.querySelector('.order-status');
                        const cancelBtn = container.querySelector('.cancel-order-btn');

                        orderStatusElement.textContent = capitalizeFirstLetter(data.status);

                        if (data.status.toLowerCase() === 'delivered') {
                            container.remove();
                            continue;
                        }

                        if (['processing', 'in-queue'].includes(data.status.toLowerCase())) {
                            if (cancelBtn) {
                                cancelBtn.disabled = false;
                                cancelBtn.addEventListener('click', async function() {
                                    await cancelOrder(orderId);
                                });
                            }
                        } else {
                            if (cancelBtn) {
                                cancelBtn.disabled = true;
                                cancelBtn.style.display = 'none';
                            }
                        }
                    } catch (error) {
                        console.error('Error fetching order status:', error);
                    }
                }
            }

            async function cancelOrder(orderId) {
                try {
                    const response = await fetch(`/order/${orderId}/cancel`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Failed to cancel order');
                    }

                    const data = await response.json();
                    const orderContainer = document.querySelector(`.order-container[data-order-id="${orderId}"]`);
                    orderContainer.querySelector('.order-status').textContent = capitalizeFirstLetter(data.status);

                } catch (error) {
                    console.error('Error cancelling order:', error);
                    alert('Failed to cancel order');
                }
            }

            function capitalizeFirstLetter(string) {
                if (!string) return string;
                return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
            }

            fetchOrderStatus();

            setInterval(fetchOrderStatus, 5000);
        });
    </script>
</x-app-layout>
