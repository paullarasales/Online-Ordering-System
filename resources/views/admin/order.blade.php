<x-admin-layout>
    <div class="container mx-auto py-6 px-4 bg-gray-100 rounded-lg shadow-lg">
        <div class="flex space-x-2 mb-4">
            <button class="filter-btn p-2 bg-gray-300 text-white rounded hover:bg-blue-700" data-status="all">All</button>
            <button class="filter-btn p-2 bg-yellow-300 text-gray-700 rounded hover:bg-gray-400" data-status="in-queue">In Queue</button>
            <button class="filter-btn p-2 bg-blue-400 text-gray-700 rounded hover:bg-gray-400" data-status="processing">Processing</button>
            <button class="filter-btn p-2 bg-orange-400 text-gray-700 rounded hover:bg-gray-400" data-status="on-deliver">On Deliver</button>
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
                                    <select name="status" id="status-{{ $order->id }}" data-order-id="{{ $order->id }}" class="status-dropdown p-2 w-32 rounded-md border border-gray-300 text-md" @if($order->status == 'delivered' || $order->status == 'cancelled') disabled @endif>
                                        <option value="in-queue" {{ ($order->status == 'in-queue') ? 'selected' : '' }}>In Queue</option>
                                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="on-deliver" {{ $order->status == 'on-deliver' ? 'selected' : '' }}>On Deliver</option>
                                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
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
                                    <select name="status" id="status-${order.id}" data-order-id="${order.id}" class="status-dropdown p-2 rounded-md border border-gray-300 text-sm" ${order.status === 'delivered' || order.status === 'cancelled' ? 'disabled' : ''}>
                                        <option value="in-queue" ${order.status === 'in-queue' ? 'selected' : ''}>In Queue</option>
                                        <option value="processing" ${order.status === 'processing' ? 'selected' : ''}>Processing</option>
                                        <option value="on-deliver" ${order.status === 'on-deliver' ? 'selected' : ''}>On Deliver</option>
                                        <option value="delivered" ${order.status === 'delivered' ? 'selected' : ''}>Delivered</option>
                                        <option value="cancelled" ${order.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                                    </select>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('admin.order.details', $order->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold transition duration-150 ease-in-out">View Details</a>
                                </td>
                            </tr>
                        `;
                        orderTableBody.innerHTML += row;
                    });
                } else {
                    orderTableBody.innerHTML = '<tr><td colspan="5" class="text-center px-6 py-4 text-gray-600">No orders found</td></tr>';
                }

                attachStatusChangeListeners();
            }

            function attachStatusChangeListeners() {
                const statusDropdowns = document.querySelectorAll('.status-dropdown');
                statusDropdowns.forEach(dropdown => {
                    dropdown.addEventListener('change', function () {
                        const orderId = this.dataset.orderId;
                        const newStatus = this.value;
                        updateOrderStatus(orderId, newStatus);
                    });
                });
            }

            function updateOrderStatus(orderId, status) {
                fetch("{{ route('admin.orders.updateStatus') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ order_id: orderId, status: status }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Order status updated successfully');
                    } else {
                        alert('Failed to update order status: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error updating order status: ', error);
                    alert('An error occurred while updating the order status');
                });
            }

            attachStatusChangeListeners();
        });
    </script>
</x-admin-layout>
