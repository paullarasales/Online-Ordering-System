<x-admin-layout>
    <div class="container mx-auto p-6">
        <!-- Sales Report Section -->
        <h1 class="text-3xl font-semibold mb-4 text-gray-800">Sales Report</h1>
        <div class="bg-white shadow-lg rounded-lg p-6 mt-6">
            <form id="excelForm" method="POST" action="{{ route('admin.reports.download') }}">
                @csrf
                <input type="hidden" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
                <input type="hidden" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
                <input type="hidden" name="year" value="{{ request('year') }}">
                <button type="submit" 
                    class="bg-orange-500 hover:bg-orange-600 text-white py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400">
                    Download Excel Report
                </button>
            </form>
        </div>

        <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
            <form method="GET" action="{{ route('admin.reports') }}" class="flex flex-col sm:flex-row gap-4 sm:gap-6">
                <div class="flex flex-col">
                    <label for="start_date" class="text-sm font-medium text-gray-700">Start Date:</label>
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}" 
                        class="px-4 py-2 mt-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="flex flex-col">
                    <label for="end_date" class="text-sm font-medium text-gray-700">End Date:</label>
                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}" 
                        class="px-4 py-2 mt-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="flex flex-col">
                    <label for="year" class="text-sm font-medium text-gray-700">Year:</label>
                    <select name="year" id="year" class="px-4 py-2 mt-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select Year</option>
                        @for ($i = now()->year; $i >= 2000; $i--)
                            <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="bg-indigo-600 text-white py-2 px-6 mt-6 sm:mt-0 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    Filter Sales
                </button>
            </form>
        </div>

        <!-- Total Sales -->
        <div class="bg-white shadow-lg rounded-lg p-6">
            <p class="text-xl font-semibold text-gray-800 mb-4">
                <strong>Total Sales: </strong>₱{{ number_format($totalSales, 2) }}
            </p>
            <p class="text-sm text-gray-600">
                Showing sales from: 
                <span class="font-medium">{{ $startDate->toFormattedDateString() }}</span> to 
                <span class="font-medium">{{ $endDate->toFormattedDateString() }}</span>
                @if (request('year'))
                    for the year: <span class="font-medium">{{ request('year') }}</span>
                @elseif (!request('start_date') && !request('end_date'))
                    (Default: Last 7 Days)
                @endif
            </p>
        </div>

        <!-- Top Selling Products -->
        <!-- With its own date filtering -->
        <div class="bg-white shadow-lg rounded-lg p-6 mt-6">
            <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Filter Top Selling Products</h3>
                <form method="GET" action="{{ route('admin.reports') }}" class="flex flex-col sm:flex-row gap-4 sm:gap-6">
                    <div class="flex flex-col">
                        <label for="top_selling_start_date" class="text-sm font-medium text-gray-700">Start Date:</label>
                        <input type="date" name="top_selling_start_date" id="top_selling_start_date" value="{{ request('top_selling_start_date') }}" 
                            class="px-4 py-2 mt-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="flex flex-col">
                        <label for="top_selling_end_date" class="text-sm font-medium text-gray-700">End Date:</label>
                        <input type="date" name="top_selling_end_date" id="top_selling_end_date" value="{{ request('top_selling_end_date') }}" 
                            class="px-4 py-2 mt-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <button type="submit" class="bg-indigo-600 text-white py-2 px-6 mt-6 sm:mt-0 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Filter Top Selling Products
                    </button>
                </form>
            </div>

            <h3 class="text-2xl font-semibold text-gray-800 mb-4">Top Selling Products</h3>
            <table class="min-w-full border-collapse table-auto">
                <thead>
                    <tr class="border-b">
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Product Name</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Total Quantity Sold</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mostSoldProducts as $product)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $product->product_name }}</td>
                            <td class="px-4 py-2">{{ $product->total_quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Chart -->
        @if (!empty($salesPerDay))
            <div class="bg-white shadow-lg rounded-lg p-6 mt-6">
                <canvas id="salesChart" width="400" height="200"></canvas>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
            document.addEventListener('DOMContentLoaded', function () {
                const salesData = @json($salesPerDay);
                const labels = salesData.map(data => data.date);
                const data = salesData.map(data => data.sales);

                const ctx = document.getElementById('salesChart').getContext('2d');

                const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                gradient.addColorStop(0, 'rgba(255, 165, 0, 0.4)');
                gradient.addColorStop(1, 'rgba(255, 165, 0, 0.1)');

                const salesChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Daily Sales',
                            data: data,
                            borderColor: '#FFA500',
                            backgroundColor: gradient,
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }]
                    }
                });

                const pdfForm = document.getElementById('pdfForm');
                if (pdfForm) {
                    pdfForm.addEventListener('submit', function () {
                        const chartImage = salesChart.toBase64Image();
                        document.getElementById('chartImageInput').value = chartImage;
                    });
                }

                const yearElement = document.getElementById('year');
                if (yearElement) {
                    yearElement.addEventListener('change', function() {
                        const year = this.value;
                        if (year) {
                            document.getElementById('start_date').value = `${year}-01-01`;
                            document.getElementById('end_date').value = `${year}-12-31`;
                        }
                    });
                }
            });
        </script>
        @endif
    </div>
</x-admin-layout>
