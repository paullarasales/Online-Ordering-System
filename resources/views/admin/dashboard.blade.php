<x-admin-layout>
    <div class="flex flex-col w-full min-h-screen bg-gray-50 p-4"> <!-- Reduced padding -->
        <!-- Mini Analytics Section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6"> <!-- Reduced gap and margin -->
            <!-- Total Sales Card -->
            <div class="bg-white p-4 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 ease-in-out text-black flex flex-col justify-between border-l-4 border-gradient-to-r from-yellow-400 to-orange-500">
                <h2 class="text-lg font-semibold mb-2">Total Sales</h2>
                <div class="text-3xl font-bold">₱{{ number_format($totalSales, 2) }}</div> <!-- Reduced font size -->
                <div class="mt-1 text-sm flex items-center {{ $salesChange >= 0 ? 'text-yellow-400' : 'text-red-300' }}">
                    <svg class="w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $salesChange >= 0 ? 'M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941' : 'M2.25 6 9 12.75l4.286-4.286a11.948 11.948 0 0 1 4.306 6.43l.776 2.898m0 0 3.182-5.511m-3.182 5.51-5.511-3.181' }}" />
                    </svg>
                    {{ abs($salesChange) }} from last month
                </div>
            </div>

            <!-- Total Customers Card -->
            <div class="bg-white p-4 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 ease-in-out text-black flex flex-col justify-between border-l-4 border-gradient-to-r from-yellow-400 to-orange-500">
                <h2 class="text-lg font-semibold mb-2">Total Customers</h2>
                <div class="text-3xl font-bold">{{ $userCount }}</div>
                <div class="mt-1 text-sm flex items-center {{ $customerChange >= 0 ? 'text-yellow-400' : 'text-red-300' }}">
                    <svg class="w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $customerChange >= 0 ? 'M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941' : 'M2.25 6 9 12.75l4.286-4.286a11.948 11.948 0 0 1 4.306 6.43l.776 2.898m0 0 3.182-5.511m-3.182 5.51-5.511-3.181' }}" />
                    </svg>
                    {{ abs($customerChange) }} from last month
                </div>
            </div>

            <!-- Total Orders Card -->
            <div class="bg-white p-4 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 ease-in-out text-black flex flex-col justify-between border-l-4 border-gradient-to-r from-yellow-400 to-orange-500">
                <h2 class="text-lg font-semibold mb-2">Total Orders</h2>
                <div class="text-3xl font-bold">{{ $orderCount }}</div>
                <div class="mt-1 text-sm flex items-center {{ $orderChange >= 0 ? 'text-yellow-400' : 'text-red-300' }}">
                    <svg class="w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $orderChange >= 0 ? 'M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941' : 'M2.25 6 9 12.75l4.286-4.286a11.948 11.948 0 0 1 4.306 6.43l.776 2.898m0 0 3.182-5.511m-3.182 5.51-5.511-3.181' }}" />
                    </svg>
                    {{ abs($orderChange) }} from last month
                </div>
            </div>
        </div>
        <div class="flex gap-6 items-center justify-center">
            <div class="w-[45%] bg-white p-4 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 ease-in-out text-black flex flex-col justify-between border-l-4 border-gradient-to-r from-yellow-400 to-orange-500">
                <h2 class="text-lg font-semibold mb-2">Order Analytics</h2>
                <canvas id="salesChart" class="w-full h-32"></canvas>
            </div>

            <div class="w-[45%] bg-white p-4 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 ease-in-out text-black flex flex-col justify-between border-l-4 border-gradient-to-r from-yellow-400 to-orange-500">
                <h2 class="text-lg font-semibold mb-2">Most Sold Products</h2>
                <canvas id="mostSoldChart" class="w-full h-32"></canvas>
            </div>
        </div>
        <!-- Recent Orders Section -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-6 mt-5"> <!-- Reduced margin bottom -->
            <div class="flex justify-between items-center p-4 bg-gray-100 border-b">
                <h2 class="text-xl font-semibold text-gray-800">Recent Orders</h2>
                <button class="text-sm text-yellow-500 hover:underline">View all</button>
            </div>
            @if ($orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Order ID</th> <!-- Reduced padding -->
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Customer Name</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($orders as $index => $order)
                                <tr class="{{ $index % 2 == 0 ? 'bg-gray-50' : 'bg-white' }}">
                                    <td class="px-4 py-4 text-md text-gray-800">{{ $order->id }}</td> <!-- Reduced padding -->
                                    <td class="px-4 py-4 text-md text-gray-800">{{ $order->user->name }}</td>
                                    <td class="px-4 py-4 text-md text-gray-800">{{ ucfirst(strtolower($order->status)) }}</td>
                                    <td class="px-4 py-4 text-md text-gray-800">{{ $order->created_at->format('d M Y') }}</td>
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

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            let mostSoldChart;
            const ctx = document.getElementById('salesChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($salesMonths),  // Array of month names (e.g., ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'])
                    datasets: [{
                        label: 'Sales Over Time',
                        data: @json($salesData),  // Array of sales data for those months
                        borderColor: '#F59E0B',  // Custom color for the line
                        fill: false,
                        tension: 0.1,
                    }],
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            ticks: {
                                font: {
                                    size: 10,
                                },
                            },
                        },
                        y: {
                            ticks: {
                                font: {
                                    size: 10,
                                },
                            },
                        },
                    },
                },
            });
            async function fetchMostSoldProducts() {
            const response = await fetch('http://localhost:8000/api/most-sold-products');
            const data = await response.json();

            const productNames = data.most_sold_products.map(product => product.product_name || 'Unknown Product');
            const productQuantities = data.most_sold_products.map(product => product.total_quantity);

            const ctx = document.getElementById('mostSoldChart').getContext('2d');
            if (mostSoldChart) mostSoldChart.destroy();

            mostSoldChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: productNames,
                    datasets: [{
                        label: 'Total Quantity Sold',
                        data: productQuantities,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        fetchMostSoldProducts();
        </script>
    @endpush
</x-admin-layout>
