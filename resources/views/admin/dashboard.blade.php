<x-admin-layout>
    <div class="flex flex-col w-full min-h-screen p-6"> <!-- Light yellow background -->
        <!-- Top Section: Overview Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <!-- Total Sales Card -->
            <div class="bg-black p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 ease-in-out text-white border-l-4 border-yellow-400">
                <h2 class="text-lg font-semibold mb-2">Total Sales</h2>
                <div class="text-3xl font-bold">₱{{ number_format($totalSales, 2) }}</div>
                <div class="mt-2 text-sm flex items-center {{ $salesChange >= 0 ? 'text-yellow-400' : 'text-red-300' }}">
                    <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $salesChange >= 0 ? 'M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941' : 'M2.25 6 9 12.75l4.286-4.286a11.948 11.948 0 0 1 4.306 6.43l.776 2.898m0 0 3.182-5.511m-3.182 5.51-5.511-3.181' }}" />
                    </svg>
                    {{ abs($salesChange) }} from last month
                </div>
            </div>

            <!-- Total Orders Card -->
            <div class="bg-black p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 ease-in-out text-white border-l-4 border-yellow-400">
                <h2 class="text-lg font-semibold mb-2">Total Orders</h2>
                <div class="text-3xl font-bold">{{ $orderCount }}</div>
                <div class="mt-2 text-sm flex items-center {{ $orderChange >= 0 ? 'text-yellow-400' : 'text-red-300' }}">
                    <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $orderChange >= 0 ? 'M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941' : 'M2.25 6 9 12.75l4.286-4.286a11.948 11.948 0 0 1 4.306 6.43l.776 2.898m0 0 3.182-5.511m-3.182 5.51-5.511-3.181' }}" />
                    </svg>
                    {{ abs($orderChange) }} from last month
                </div>
            </div>

            <!-- Total Customers Card -->
            <div class="bg-black p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 ease-in-out text-white border-l-4 border-yellow-400">
                <h2 class="text-lg font-semibold mb-2">Total Customers</h2>
                <div class="text-3xl font-bold">{{ $userCount }}</div>
                <div class="mt-2 text-sm flex items-center {{ $customerChange >= 0 ? 'text-yellow-400' : 'text-red-300' }}">
                    <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $customerChange >= 0 ? 'M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941' : 'M2.25 6 9 12.75l4.286-4.286a11.948 11.948 0 0 1 4.306 6.43l.776 2.898m0 0 3.182-5.511m-3.182 5.51-5.511-3.181' }}" />
                    </svg>
                    {{ abs($customerChange) }} from last month
                </div>
            </div>
        </div>

        <!-- Bottom Section: Orders Table -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="flex justify-between items-center p-4 bg-gray-100 border-b">
                <h2 class="text-xl font-semibold text-gray-800">Recent Orders</h2>
                <button class="text-sm text-yellow-500 hover:underline">View all</button>
            </div>
            @if ($orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Order ID</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Customer Name</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($orders as $index => $order)
                                <tr class="{{ $index % 2 == 0 ? 'bg-gray-50' : 'bg-white' }}">
                                    <td class="px-6 py-4 text-md text-gray-800">{{ $order->id }}</td>
                                    <td class="px-6 py-4 text-md text-gray-800">{{ $order->user->name }}</td>
                                    <td class="px-6 py-4 text-md text-gray-800">{{ ucfirst(strtolower($order->status)) }}</td>
                                    <td class="px-6 py-4 text-md text-gray-800">{{ $order->created_at->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-4 text-gray-500">No recent orders found.</div>
            @endif
        </div>
    </div>
</x-admin-layout>
