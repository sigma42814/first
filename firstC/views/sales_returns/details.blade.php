<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <p><strong>Return Number:</strong> {{ $salesReturn->return_number }}</p>
            <p><strong>Date:</strong> {{ $salesReturn->return_date->format('d/m/Y') }}</p>
            <p><strong>Customer:</strong> {{ $salesReturn->customer->name }}</p>
        </div>
        <div class="col-md-6">
            <p><strong>Username:</strong> {{ $salesReturn->username }}</p>
            <p><strong>Reason:</strong> {{ $salesReturn->reason ?? 'N/A' }}</p>
        </div>
    </div>

    <div class="table-responsive mt-3">
        <table class="table table-sm table-bordered">
            <thead class="bg-light">
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
                @foreach($salesReturn->items as $item)
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
            <tfoot class="bg-light">
                <tr>
                    <td colspan="7" class="text-end"><strong>Total Return:</strong></td>
                    <td>{{ number_format($salesReturn->total_return, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>