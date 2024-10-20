<x-admin-layout>
    <div class="mb-4">
        <label for="order-filter" class="block text-sm font-medium text-gray-700">Filter by Status</label>
        <select id="order-filter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="all">All</option>
            <option value="in-queue">In Queue</option>
            <option value="processing">Processing</option>
            <option value="on-deliver">On Deliver</option>
            <option value="delivered">Delivered</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>
    @if ($orders->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($orders as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $order->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $order->user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $order->contactno }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $order->address }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $order->payment_method }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <select name="status" id="status-{{ $order->id }}" data-order-id="{{ $order->id }}" class="status-dropdown"
                                        @if($order->status == 'delivered' || $order->status == 'cancelled') disabled @endif>
                                    <option value="in-queue" {{ ($order->status == '' || $order->status == 'in-queue') ? 'selected' : '' }}>In Queue</option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="On Deliver" {{ $order->status == 'On Deliver' ? 'selected' : '' }}>On Deliver</option>
                                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.order.details', $order->id) }}" class="text-indigo-600 hover:text-indigo-900">View Details</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>No orders found.</p>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const statusDropdowns = document.querySelectorAll('.status-dropdown');
            const orderFilter = document.getElementById('order-filter');

            orderFilter.addEventListener('change', function () {
                const selectedStatus = this.value;
                fetchOrders(selectedStatus);
            });

            function fetchOrders(status) {
                fetch("{{ route('admin.orders.filter')}}?status=" + status, {
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
                        alert('Failed to fetch orders:' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error fetching orders: ', error);
                    alert('An error occured while fetching orders');
                });
            }           

            function updateOrderTable(orders) {
                const tbody = document.querySelector('tbody');
                tbody.innerHTML = '';

                if (orders.length > 0) {
                    orders.forEach(order => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="px-6 py-4 whitespace-nowrap">${order.id}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${order.user.name}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${order.contactno}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${order.address}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${order.payment_method}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <select name="status" id="status-${order.id}" data-order-id="${order.id}" class="status-dropdown"
                                        ${order.status === 'delivered' || order.status === 'cancelled' ? 'disabled' : ''}>
                                    <option value="in-queue" ${order.status === '' || order.status === 'in-queue' ? 'selected' : ''}>In Queue</option>
                                    <option value="processing" ${order.status === 'processing' ? 'selected' : ''}>Processing</option>
                                    <option value="on-deliver" ${order.status === 'on-deliver' ? 'selected' : ''}>On Deliver</option>
                                    <option value="delivered" ${order.status === 'delivered' ? 'selected' : ''}>Delivered</option>
                                    <option value="cancelled" ${order.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.order.details', $order->id) }}" class="text-indigo-600 hover:text-indigo-900">View Details</a>
                            </td>
                            
                        `;
                        tbody.appendChild(row);
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="7" class="text-center px-6 py-4">No orders found.</td></tr>';
                }
            }

            fetchOrders('all');


            statusDropdowns.forEach(dropdown => {
                dropdown.addEventListener('change', function () {
                    const orderId = this.getAttribute('data-order-id');
                    const status = this.value;

                    fetch("{{ route('admin.orders.updateStatus') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            order_id: orderId,
                            status: status
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Status updated successfully');
                        } else {
                            alert('Failed to update status: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while updating the status');
                    });
                });
            });

            setInterval(function() {
                fetch("{{ route('admin.order.stats') }}", {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.orders) {
                        data.orders.forEach(order => {
                            const statusDropdown = document.getElementById('status-' + order.id);
                            if (statusDropdown) {
                                console.log("Order ID:", order.id, "Status:", order.status);
                                
                                // Set to "In Queue" if order status is null or an empty string
                                if (!order.status || order.status.trim() === '') {
                                    console.log("Setting status to 'in-queue' for order ID:", order.id);
                                    statusDropdown.value = 'in-queue'; // Set it to "In Queue"
                                } else {
                                    statusDropdown.value = order.status; // Otherwise, set it to the actual status
                                }
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching order statuses:', error);
                });
            }, 1000); // 1-second interval
        });
    </script>
</x-admin-layout>