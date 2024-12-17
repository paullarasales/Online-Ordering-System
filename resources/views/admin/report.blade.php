<x-admin-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-semibold mb-4 text-gray-800">Sales Report</h1>
        <div class="bg-white shadow-lg rounded-lg p-6 mt-6">
            <form id="pdfForm" method="POST" action="{{ route('admin.reports.download') }}">
                @csrf
                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                <input type="hidden" name="chart_image" id="chartImageInput">
                <button type="submit" 
                    class="bg-orange-500 hover:bg-orange-600 text-white py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400">
                    Download PDF Report
                </button>
            </form>
        </div>

        <!-- Date Range Filter Form -->
        <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
            <form method="GET" action="{{ route('admin.reports') }}" class="flex flex-col sm:flex-row gap-4 sm:gap-6">
                <div class="flex flex-col">
                    <label for="start_date" class="text-sm font-medium text-gray-700">Start Date:</label>
                    <input type="date" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}" 
                        class="px-4 py-2 mt-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="flex flex-col">
                    <label for="end_date" class="text-sm font-medium text-gray-700">End Date:</label>
                    <input type="date" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}" 
                        class="px-4 py-2 mt-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <button type="submit" class="bg-indigo-600 text-white py-2 px-6 mt-6 sm:mt-0 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    View Sales
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
                @if (!request('start_date') && !request('end_date'))
                    (Default: Last 7 Days)
                @endif
            </p>
        </div>

        <!-- Chart -->
        @if (!empty($salesPerDay))
        <div class="bg-white shadow-lg rounded-lg p-6 mt-6">
            <canvas id="salesChart" width="400" height="200"></canvas>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
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

            document.getElementById('pdfForm').addEventListener('submit', function () {
                const chartImage = salesChart.toBase64Image();
                document.getElementById('chartImageInput').value = chartImage;
            });
        </script>
        @endif
    </div>
</x-admin-layout>
