<x-admin-layout>
    <h1 class="text-3xl font-bold mb-4">Order Contents</h1>

    @if ($orders->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Name</th>
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
                            <td class="px-6 py-4 whitespace-nowrap">{{ $order->address }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $order->payment_method }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <select name="status" id="status-{{ $order->id }}" data-order-id="{{ $order->id }}" class="status-dropdown">
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="on deliver" {{ $order->status == 'on deliver' ? 'selected' : '' }}>On Deliver</option>
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
        document.addEventListener('DOMContentLoaded', function() {
            let dropdowns = document.querySelectorAll('.status-dropdown');

            dropdowns.forEach(function(dropdown) {
                dropdown.addEventListener('change', function() {
                    let orderId = this.dataset.orderId;
                    let status = this.value;
                    let xhr = new XMLHttpRequest();
                    xhr.open('PATCH', `/admin/order/${orderId}/status`, true);
                    xhr.setRequestHeader('Content-Type', 'application/json');
                    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Order status updated successfully.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                        } else if (xhr.readyState === 4) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'An error occurred while updating the order status.',
                                icon: 'error',
                                confirmButtonText: 'OK',
                            });
                        }
                    }
                    xhr.send(JSON.stringify({ status: status }));
                });
            });
        });
    </script>
</x-admin-layout>
