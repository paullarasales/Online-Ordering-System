<x-admin-layout>
    <div class="container mx-auto py-6 px-4 bg-gray-100 rounded-lg shadow-lg">
        <div class="flex space-x-2 mb-4">
            <button class="filter-btn p-2 bg-gray-300 text-white rounded hover:bg-blue-700" data-status="all">All</button>
            <button class="filter-btn p-2 bg-yellow-300 text-gray-700 rounded hover:bg-gray-400" data-status="in-queue">In Queue</button>
            <button class="filter-btn p-2 bg-blue-400 text-gray-700 rounded hover:bg-gray-400" data-status="processing">Processing</button>
            <button class="filter-btn p-2 bg-orange-400 text-gray-700 rounded hover:bg-gray-400" data-status="on-deliver">On Delivery</button>
            <button class="filter-btn p-2 bg-green-400 text-gray-700 rounded hover:bg-gray-400" data-status="delivered">Delivered</button>
            <button class="filter-btn p-2 bg-red-400 text-gray-700 rounded hover:bg-gray-400" data-status="cancelled">Cancelled</button>
        </div>

        @if ($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 bg-white rounded-lg shadow-lg">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Order ID</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer Name</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Payment Method</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="order-table-body">
                        @foreach ($orders as $order)
                            <tr class="hover:bg-gray-100 transition ease-in-out duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-md font-medium text-gray-800">{{ $order->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-md text-gray-800">{{ $order->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-md text-gray-800">{{ $order->payment_method }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-block px-4 py-2 rounded-md text-white 
                                        @if($order->status == 'in-queue') bg-yellow-400 
                                        @elseif($order->status == 'processing') bg-blue-400 
                                        @elseif($order->status == 'on-deliver') bg-orange-400 
                                        @elseif($order->status == 'delivered') bg-green-400 
                                        @elseif($order->status == 'cancelled') bg-red-400 
                                        @endif">
                                        {{ $order->status == 'on-deliver' ? 'On Delivery' : ucfirst(str_replace('-', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('admin.order.details', $order->id) }}" class="text-blue-600 hover:text-blue-800 text-md font-semibold transition duration-150 ease-in-out">View Details</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center text-gray-600 mt-6">No orders found.</p>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const orderTableBody = document.getElementById('order-table-body');

            filterButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const selectedStatus = this.dataset.status;

                    // Update button styles
                    filterButtons.forEach(btn => btn.classList.remove('bg-blue-500', 'text-white', 'hover:bg-blue-700'));
                    this.classList.add('bg-blue-500', 'text-white', 'hover:bg-blue-700');

                    fetchOrders(selectedStatus);
                });
            });

            function fetchOrders(status) {
                fetch("{{ route('admin.orders.filter') }}?status=" + status, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateOrderTable(data.orders);
                    } else {
                        alert('Failed to fetch orders: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error fetching orders: ', error);
                    alert('An error occurred while fetching orders');
                });
            }

            function updateOrderTable(orders) {
                orderTableBody.innerHTML = '';

                if (orders.length > 0) {
                    orders.forEach(order => {
                        const row = `
                            <tr class="hover:bg-gray-100 transition ease-in-out duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">${order.id}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">${order.user.name}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">${order.payment_method}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-block px-4 py-2 rounded-md text-white 
                                        ${order.status === 'in-queue' ? 'bg-yellow-400' : ''}
                                        ${order.status === 'processing' ? 'bg-blue-400' : ''}
                                        ${order.status === 'on-deliver' ? 'bg-orange-400' : ''}
                                        ${order.status === 'delivered' ? 'bg-green-400' : ''}
                                        ${order.status === 'cancelled' ? 'bg-red-400' : ''}">
                                        ${order.status === 'on-deliver' ? 'On Delivery' : order.status.charAt(0).toUpperCase() + order.status.slice(1).replace('-', ' ')}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="/admin/orders/${order.id}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold transition duration-150 ease-in-out">View Details</a>
                                </td>
                            </tr>
                        `;
                        orderTableBody.innerHTML += row;
                    });
                } else {
                    orderTableBody.innerHTML = '<tr><td colspan="5" class="text-center px-6 py-4 text-gray-600">No orders found</td></tr>';
                }
            }
        });
    </script>
</x-admin-layout>