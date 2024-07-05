<x-admin-layout>
    <h1 class="text-3xl font-bold mb-4">Order Contents</h1>

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
                                <select name="status" id="status-{{ $order->id }}" data-order-id="{{ $order->id }}" class="status-dropdown">
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="on deliver" {{ $order->status == 'on deliver' ? 'selected' : '' }}>On Deliver</option>
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
        });
    </script>
</x-admin-layout>
