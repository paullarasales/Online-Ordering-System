<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Notifications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-semibold text-gray-900">New Orders:</h1>
                    <div id="new-orders-container" class="mt-6">
                        <!-- New orders will be dynamically added here -->
                        @foreach ($newOrders->reverse() as $order)
                            <div class="border-t border-gray-200 py-4">
                                <p class="text-lg">{{ $order->user->name }} placed an order:</p>
                                <p class="mt-2">Order ID: {{ $order->id }}</p>
                                <p>{{ $order->status}}</p>
                            </div>
                        @endforeach
                    </div>

                    <h1 class="text-2xl font-semibold text-gray-900 mt-6">All Orders:</h1>
                    <div id="orders-container" class="mt-6">
                        <!-- All orders will be dynamically added here -->
                        @foreach ($orders->reverse() as $order)
                            <div class="border-t border-gray-200 py-4">
                                <p class="text-lg">{{ $order->user->name }} placed an order:</p>
                                <p class="mt-2">Order ID: {{ $order->id }}</p>
                                <p>{{ $order->status}}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            async function fetchNewOrdersAndUpdate() {
                try {
                    const response = await fetch('/admin/fetch-new-orders');
                    const data = await response.json();

                    if (data.newOrders.length > 0) {
                        const newOrdersContainer = document.getElementById('new-orders-container');
                        newOrdersContainer.innerHTML = '';

                        data.newOrders.reverse().forEach(order => {
                            const orderHtml = `
                                <div class="border-t border-gray-200 py-4">
                                    <p class="text-lg">${order.user.name} placed an order:</p>
                                    <p class="mt-2">Order ID: ${order.id}</p>
                                </div>
                            `;
                            newOrdersContainer.insertAdjacentHTML('beforeend', orderHtml);
                        });

                        alert('New Order');
                    }
                } catch (error) {
                    console.error('Error fetching new orders:', error);
                }
            }

            setInterval(fetchNewOrdersAndUpdate, 4000);
        });
    </script>
</x-admin-layout>
