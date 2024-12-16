<x-admin-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-semibold mb-4 text-gray-800">Sales Report</h1>

        <!-- Date Range Filter Form -->
        <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
            <form method="GET" action="{{ route('admin.reports') }}" class="flex flex-col sm:flex-row gap-4 sm:gap-6">
                
                <div class="flex flex-col">
                    <label for="start_date" class="text-sm font-medium text-gray-700">Start Date:</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" 
                        class="px-4 py-2 mt-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="flex flex-col">
                    <label for="end_date" class="text-sm font-medium text-gray-700">End Date:</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" 
                        class="px-4 py-2 mt-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <button type="submit" class="bg-indigo-600 text-white py-2 px-6 mt-6 sm:mt-0 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    View Sales
                </button>
            </form>
        </div>

        <!-- Displaying Total Sales -->
        <div class="bg-white shadow-lg rounded-lg p-6">
            <p class="text-xl font-semibold text-gray-800 mb-4"><strong>Total Sales: </strong>₱{{ number_format($totalSales, 2) }}</p>

            @if ($startDate && $endDate)
                <p class="text-sm text-gray-600">Showing sales from: 
                    <span class="font-medium">{{ $startDate->toFormattedDateString() }}</span> to 
                    <span class="font-medium">{{ $endDate->toFormattedDateString() }}</span>
                </p>
            @endif
        </div>

        <!-- Chart.js Sales Data -->
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
            gradient.addColorStop(0, 'rgba(76, 175, 80, 0.4)');
            gradient.addColorStop(1, 'rgba(76, 175, 80, 0.1)');

            const salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Daily Sales',
                        data: data,
                        borderColor: '#4CAF50',
                        backgroundColor: gradient,
                        borderWidth: 3,
                        fill: true,
                        pointRadius: 4,
                        pointBackgroundColor: '#4CAF50',
                        pointBorderColor: '#ffffff',
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: '#4CAF50',
                        pointHoverBorderColor: '#ffffff',
                        tension: 0.4, // Bezier curve interpolation
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return `₱${tooltipItem.raw.toFixed(2)}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            ticks: {
                                callback: function(value) {
                                    return '₱' + value.toFixed(2); // Format y-axis values as currency
                                },
                                beginAtZero: true
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: 45,
                                minRotation: 0
                            }
                        }
                    }
                }
            });
        </script>
        @endif
    </div>
</x-admin-layout>
