<x-admin-layout>
    <div class="flex flex-col w-full min-h-screen p-6">
        <!-- Top Section: Overview Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <!-- Total Sales Card -->
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 ease-in-out">
                <h2 class="text-gray-400 text-lg font-semibold mb-2">Total Sales</h2>
                <div class="text-2xl font-medium text-gray-800">₱{{ number_format($totalSales, 2) }}</div>
                <div class="mt-2 text-sm text-green-500 flex items-center">
                    <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                    </svg>
                    in the last month
                </div>
            </div>

            <!-- Total Orders Card -->
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 ease-in-out">
                <h2 class="text-gray-400 text-lg font-semibold mb-2">Total Orders</h2>
                <div class="text-2xl font-medium text-gray-800">{{ $orderCount }}</div>
                <div class="mt-2 text-sm text-red-500 flex items-center">
                    <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 6 9 12.75l4.286-4.286a11.948 11.948 0 0 1 4.306 6.43l.776 2.898m0 0 3.182-5.511m-3.182 5.51-5.511-3.181" />
                    </svg>
                    in the last month
                </div>
            </div>

            <!-- Total Customers Card -->
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 ease-in-out">
                <h2 class="text-gray-400 text-lg font-semibold mb-2">Total Customers</h2>
                <div class="text-2xl font-medium text-gray-800">{{ $userCount }}</div>
                <div class="mt-2 text-sm text-green-500 flex items-center">
                    <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                    </svg>
                    in the last month
                </div>
            </div>
        </div>

        <!-- Bottom Section: Orders Table -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="flex justify-between items-center p-4">
                <h2 class="text-xl font-semibold text-gray-800">Recent Orders</h2>
                <button class="text-sm text-blue-500 hover:underline">View all</button>
            </div>
            @if ($orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Order ID</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Customer Name</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Contact Number</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Address</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Payment Method</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($orders as $order)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-800">{{ $order->id }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800">{{ $order->user->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800">{{ $order->contactno }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800">{{ $order->address }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800">{{ $order->payment_method }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center text-gray-500 p-4">No orders found.</p>
            @endif
        </div>
    </div>
</x-admin-layout>
