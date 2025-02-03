<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        body { font-family: 'Arial', sans-serif; margin: 0; padding: 20px; background-color: #f5f5f5; }
        .invoice-container { background: #fff; padding: 30px; border-radius: 10px; max-width: 800px; margin: auto; box-shadow: 0px 5px 10px rgba(0,0,0,0.1); }
        .header { text-align: center; }
        .header img { width: 150px; margin-bottom: 20px; }
        .header h1 { font-size: 26px; color: #333; }
        .invoice-details { display: flex; justify-content: space-between; margin-top: 20px; }
        .invoice-details div { width: 48%; }
        .invoice-details h3 { font-size: 16px; color: #444; }
        .invoice-details p { margin: 3px 0; font-size: 14px; color: #666; }
        .table-container { margin-top: 30px; }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 12px; text-align: left; font-size: 14px; }
        th { background: #222; color: #fff; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #666; }
        .qr-code { text-align: center; margin-top: 20px; }
        .qr-code img { width: 100px; height: 100px; }
    </style>
</head>
<body>

<div class="invoice-container">
    <!-- Brand Logo -->
    <div class="header">
{{--        @if($logoPath)--}}
{{--            <img src="{{ asset($logoPath) }}" alt="QR code" width="100" height="100">--}}
{{--        @endif--}}
        <h3>{{ $setting->title ?? 'Company Name' }}</h3>
    </div>

    <!-- Invoice Details -->
    <div class="invoice-details">
        <div>
            <h3>Invoice To:</h3>
            <p><strong>Name:</strong> {{ $order->user->name ?? '' }}</p>
            <p><strong>Email:</strong> {{ $order->user->email ?? '' }}</p>
            <p><strong>Billing Address:</strong> {{ $order['billing_address'] ?? '' }}, {{ $order->billing_address->city ?? '' }}</p>
            <p><strong>Contact:</strong> {{ $order->billing_address->contact ?? '' }}</p>
        </div>
        <div>
            <h3>Order Details:</h3>
            <p><strong>Order Number:</strong> #{{ $order->uuid }}</p>
            <p><strong>Order Date:</strong> {{ $order->created_at->format('Y-m-d') }}</p>
            <p><strong>Delivery Date:</strong> {{ $order->delivery_date ?? 'N/A' }}</p>
            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
        </div>
    </div>

    <!-- Order Items -->
    <div class="table-container">
        <h3>Order Items</h3>
        <table>
            <thead>
            <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total Price</th>
            </tr>
            </thead>
            <tbody>
            @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $item->medicine->title ?? 'N/A' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->unit_price, 2) }}</td>
                    <td>${{ number_format($item->total_price, 2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pricing Summary -->
    <div class="invoice-details" style="margin-top: 30px;">
        <div>
            <h3>Payment Summary</h3>
            <p><strong>Subtotal:</strong> ${{ number_format($order->sub_total, 2) }}</p>
            <p><strong>Discount:</strong> -${{ number_format($order->discount, 2) }}</p>
            <p><strong>Total Amount:</strong> ${{ number_format($order->total_price, 2) }}</p>
        </div>
    </div>

    <!-- QR Code for Order Details -->
    <div class="qr-code">
        <h3>Scan to View Order Details</h3>
{{--        @if($qr_code_path)--}}
{{--            <img src="{{ asset($qr_code_path) }}" alt="QR code">--}}
{{--        @endif--}}
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Thank you for your business! If you have any questions, please contact us.</p>
    </div>
</div>

</body>
</html>
