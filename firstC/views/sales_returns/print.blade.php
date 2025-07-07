<!DOCTYPE html>
<html>
<head>
    <title>Sales Return - {{ $salesReturn->return_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .company-info {
            margin-bottom: 20px;
        }
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Sales Return</h2>
        <h3>#{{ $salesReturn->return_number }}</h3>
    </div>

    <div class="invoice-info">
        <div>
            <p><strong>Date:</strong> {{ $salesReturn->return_date->format('Y-m-d') }}</p>
            <p><strong>Customer:</strong> {{ $salesReturn->customer->name }}</p>
        </div>
        <div>
            <p><strong>Username:</strong> {{ $salesReturn->username }}</p>
            <p><strong>Reason:</strong> {{ $salesReturn->reason ?? 'N/A' }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Batch</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Dis 1</th>
                <th>Dis 2</th>
                <th>Bonus</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($salesReturn->items as $item)
                <tr>
                    <td>{{ $item->item->item_name }}</td>
                    <td>{{ $item->batch_number ?? 'N/A' }}</td>
                    <td>{{ number_format($item->price, 2) }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->discount }}%</td>
                    <td>{{ $item->discount2 }}%</td>
                    <td>{{ $item->bonus }}</td>
                    <td>{{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" class="text-right"><strong>Total Return:</strong></td>
                <td>{{ number_format($salesReturn->total_return, 2) }}</td>
            </tr>
            <tr>
                <td colspan="7" class="text-right"><strong>Net Payable:</strong></td>
                <td>{{ number_format($salesReturn->net_payable, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <div>
            <p><strong>Printed By:</strong> {{ auth()->user()->name ?? 'System' }}</p>
        </div>
        <div>
            <p><strong>Printed At:</strong> {{ now()->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>