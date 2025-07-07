<div class="row">
    <div class="col-md-6">
        <h6>Customer Information</h6>
        <p><strong>Name:</strong> {{ $sale->customer->name ?? 'N/A' }}</p>
        <p><strong>Invoice #:</strong> {{ $sale->invoice_number }}</p>
        <p><strong>Date:</strong> {{ $sale->invoice_date->format('d/m/Y') }}</p>
    </div>
    <div class="col-md-6">
        <h6>Sale Summary</h6>
        <p><strong>Total Sales:</strong> {{ number_format($sale->total_sales, 2) }}</p>
        <p><strong>Net Payable:</strong> {{ number_format($sale->net_payable, 2) }}</p>
        <p><strong>Created By:</strong> {{ $sale->username }}</p>
    </div>
</div>

<hr>

<h6>Items</h6>
<table class="table table-sm">
    <thead>
        <tr>
            <th>Item</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sale->items as $item)
        <tr>
            <td>{{ $item->item_name }}</td>
            <td>{{ number_format($item->price, 2) }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ number_format($item->total, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>