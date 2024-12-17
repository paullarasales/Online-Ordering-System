<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px; border: 1px solid #ccc; text-align: center; }
        th { background-color: #f4f4f4; }
        h1, h2 { text-align: center; margin-bottom: 10px; }
        p { text-align: center; font-size: 16px; }
        img { display: block; margin: 20px auto; width: 100%; max-width: 600px; }
    </style>
</head>
<body>
    <h1>Sales Report</h1>
    <h2>From {{ $startDate->toFormattedDateString() }} to {{ $endDate->toFormattedDateString() }}</h2>

    <p><strong>Total Sales:</strong> ₱{{ number_format($totalSales, 2) }}</p>

    <!-- Render Chart Image -->
    @if ($chartImage)
        <img src="{{ $chartImage }}" alt="Sales Chart">
    @endif

    <!-- Sales Data Table -->
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Sales (₱)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($salesPerDay as $day)
                <tr>
                    <td>{{ $day->date }}</td>
                    <td>{{ number_format($day->sales, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
