<div class="order-details">
    <h4 class="text-lg font-bold mb-2">Order Information</h4>
    <p><strong>Order ID:</strong> {{ $order->uuid }}</p>
    <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
    <p><strong>Order Date:</strong> {{ $order->created_at->format('d-m-Y') }}</p>
    <p><strong>Delivery Date:</strong> {{ $order->status == 'delivered' ? $order->updated_at->format('d-m-Y') : 'Not Delivered' }}</p>

    <h4 class="text-lg font-bold mb-2">User Information</h4>
    <p><strong>Name:</strong> {{ $order->user->name }}</p>
    <p><strong>Email:</strong> {{ $order->user->email }}</p>

    <h4 class="text-lg font-bold mb-2">Billing Address</h4>
    <p><strong>Name:</strong> {{ $order->billingAddress->name ?? 'N/A' }}</p>
    <p><strong>Email:</strong> {{ $order->billingAddress->email ?? 'N/A' }}</p>
    <p><strong>Address:</strong> {{ $order->billingAddress->address ?? 'N/A' }}</p>
    <p><strong>Contact:</strong> {{ $order->billingAddress->contact ?? 'N/A' }}</p>
    <p><strong>City:</strong> {{ $order->billingAddress->city ?? 'N/A' }}</p>
    <p><strong>Postcode:</strong> {{ $order->billingAddress->postcode ?? 'N/A' }}</p>

    <h4 class="text-lg font-bold mb-2">Order Items</h4>
    <table class="min-w-full table-auto border">
        <thead>
        <tr>
            <th class="px-4 py-2 border">Medicine</th>
            <th class="px-4 py-2 border">Quantity</th>
            <th class="px-4 py-2 border">Unit Price</th>
            <th class="px-4 py-2 border">Total Price</th>
        </tr>
        </thead>
        <tbody>
        @foreach($order->orderItems as $item)
            <tr>
                <td class="px-4 py-2 border">{{ $item->medicine->name ?? 'N/A' }}</td>
                <td class="px-4 py-2 border">{{ $item->quantity }}</td>
                <td class="px-4 py-2 border">{{ number_format($item->unit_price, 2) }}</td>
                <td class="px-4 py-2 border">{{ number_format($item->total_price, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <h4 class="text-lg font-bold mb-2">Treatment Information</h4>
    <p><strong>Name:</strong> {{ $order->treatment->name ?? 'N/A' }}</p>

    <h4 class="text-lg font-bold mb-2">Pricing Summary</h4>
    <p><strong>Sub Total:</strong> {{ number_format($order->sub_total, 2) }}</p>
    <p><strong>Discount:</strong> {{ number_format($order->discount, 2) }}</p>
    <p><strong>Total Price:</strong> {{ number_format($order->total_price, 2) }}</p>

    <h4 class="text-lg font-bold mb-2">Order Note</h4>
    <p>{{ $order->note ?? 'N/A' }}</p>
</div>
