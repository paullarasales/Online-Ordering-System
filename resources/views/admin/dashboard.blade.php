<x-admin-layout>
    <div class="flex flex-col w-full min-h-screen bg-gray-50 p-6">
        <!-- Year Display -->
        <div class="text-2xl font-semibold mb-6">
            Analytics for the Year: {{ $currentYear }}
        </div>

        <!-- Year Filter Form -->
        <form method="GET" action="{{ route('dashboard') }}" class="mb-6 flex items-center space-x-4">
            <label for="year" class="block text-sm font-medium text-gray-700">Select Year</label>
            <select id="year" name="year" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @for ($year = 2020; $year <= Carbon\Carbon::now()->year; $year++)
                    <option value="{{ $year }}" {{ $currentYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endfor
            </select>
            <button type="submit" class="mt-2 px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition duration-300">Filter</button>
        </form>

        <!-- Top Section: Mini Analytics -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <!-- Total Sales Card -->
            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 ease-in-out text-black flex flex-col justify-between border-l-4 border-yellow-500">
                <h2 class="text-lg font-semibold mb-2">Total Sales</h2>
                <div class="text-3xl font-bold">₱{{ number_format($totalSales, 2) }}</div>
                <div class="mt-1 text-sm flex items-center {{ $salesChange >= 0 ? 'text-green-500' : 'text-red-500' }}">
                    <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $salesChange >= 0 ? 'M3 17l6-6 4 4 8-8' : 'M3 7l6 6 4-4 8 8' }}" />
                    </svg>
                    {{ abs($salesChange) }} from last month
                </div>
            </div>

            <!-- Total Customers Card -->
            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 ease-in-out text-black flex flex-col justify-between border-l-4 border-yellow-500">
                <h2 class="text-lg font-semibold mb-2">Total Customers</h2>
                <div class="text-3xl font-bold">{{ $userCount }}</div>
                <div class="mt-1 text-sm flex items-center {{ $customerChange >= 0 ? 'text-green-500' : 'text-red-500' }}">
                    <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $customerChange >= 0 ? 'M3 17l6-6 4 4 8-8' : 'M3 7l6 6 4-4 8 8' }}" />
                    </svg>
                    {{ abs($customerChange) }} from last month
                </div>
            </div>

            <!-- Total Orders Card -->
            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 ease-in-out text-black flex flex-col justify-between border-l-4 border-yellow-500">
                <h2 class="text-lg font-semibold mb-2">Total Orders</h2>
                <div class="text-3xl font-bold">{{ $orderCount }}</div>
                <div class="mt-1 text-sm flex items-center {{ $orderChange >= 0 ? 'text-green-500' : 'text-red-500' }}">
                    <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $orderChange >= 0 ? 'M3 17l6-6 4 4 8-8' : 'M3 7l6 6 4-4 8 8' }}" />
                    </svg>
                    {{ abs($orderChange) }} from last month
                </div>
            </div>
        </div>

        <!-- Bottom Section: Analytics and Recent Orders -->
        <div class="flex flex-col space-y-6">
            <!-- Analytics Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 ease-in-out text-black">
                    <h2 class="text-lg font-semibold mb-4">Sales Over Time</h2>
                    <canvas id="salesChart" class="w-full h-64"></canvas>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 ease-in-out text-black">
                    <h2 class="text-lg font-semibold mb-4">Most Sold Products</h2>
                    <canvas id="mostSoldChart" class="w-full h-64"></canvas>
                </div>
            </div>

            <!-- Recent Orders Section -->
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
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Order ID</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Customer Name</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($orders as $index => $order)
                                    <tr class="{{ $index % 2 == 0 ? 'bg-gray-50' : 'bg-white' }}">
                                        <td class="px-4 py-4 text-md text-gray-800">{{ $order->id }}</td>
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
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            let mostSoldChart;
            const salesCtx = document.getElementById('salesChart').getContext('2d');
            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: @json($salesMonths),
                    datasets: [{
                        label: 'Sales Over Time',
                        data: @json($salesData),
                        borderColor: '#F59E0B',
                        backgroundColor: 'rgba(245, 158, 11, 0.2)',
                        fill: true,
                        tension: 0.1,
                    }],
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            ticks: {
                                font: {
                                    size: 12,
                                },
                            },
                        },
                        y: {
                            ticks: {
                                font: {
                                    size: 12,
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

                const mostSoldCtx = document.getElementById('mostSoldChart').getContext('2d');
                if (mostSoldChart) {
                    mostSoldChart.destroy();
                }

                mostSoldChart = new Chart(mostSoldCtx, {
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