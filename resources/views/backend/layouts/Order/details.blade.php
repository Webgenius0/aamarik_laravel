<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <th>Order ID</th>
            <td>{{ $order->uuid }}</td>
        </tr>
        <tr>
            <th>Customer Name</th>
            <td>{{ $order->user->name }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $order->user->email }}</td>
        </tr>
        <tr>
            <th>Order Date</th>
            <td>{{ $order->created_at->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <th>Billing Address</th>
            <td>{{ $order->billingAddress->address ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Order Status</th>
            <td>{{ ucfirst($order->status) }}</td>
        </tr>
        <tr>
            <th>Subtotal</th>
            <td>${{ number_format($order->sub_total, 2) }}</td>
        </tr>
        <tr>
            <th>Discount</th>
            <td>${{ number_format($order->discount, 2) }}</td>
        </tr>
        <tr>
            <th>Total</th>
            <td>${{ number_format($order->total_price, 2) }}</td>
        </tr>
    </table>
</div>

<h5>Order Items</h5>
<table class="table table-striped">
    <thead>
    <tr>
        <th>#</th>
        <th>Medicine</th>
        <th>Quantity</th>
        <th>Unit Price</th>
        <th>Total Price</th>
    </tr>
    </thead>
    <tbody>
    @foreach($order->orderItems as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->medicine_id }}</td>
            <td>{{ $item->quantity }}</td>
            <td>${{ number_format($item->unit_price, 2) }}</td>
            <td>${{ number_format($item->total_price, 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
