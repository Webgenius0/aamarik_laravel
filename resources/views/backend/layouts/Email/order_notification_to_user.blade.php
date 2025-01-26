<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 14px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .responsive-table {
            width: 100%;
            max-width: 100%;
            overflow-x: auto;
        }
        @media only screen and (max-width: 600px) {
            table, th, td {
                font-size: 12px;
            }
            .responsive-table {
                overflow-x: scroll;
            }
        }
    </style>
</head>
<body>
<h1>📦 Order Confirmation</h1>
<p>Hello {{ $notifiable->name }},</p>
<p>Thank you for your order! We are excited to let you know that your order has been successfully placed and is being processed.</p>

<h2>Order Details</h2>
<div class="responsive-table">
    <table>
        <tr>
            <td><strong>Order ID</strong></td>
            <td>{{ $order->uuid }}</td>
        </tr>
        <tr>
            <td><strong>Sub Total</strong></td>
            <td>${{ number_format($order->sub_total, 2) }}</td>
        </tr>
        @if ($order->discount && $order->coupon)
            <tr>
                <td><strong>Discount Applied</strong></td>
                <td>${{ number_format($order->discount, 2) }} (Coupon: {{ $order->coupon->code }})</td>
            </tr>
        @endif
        <tr>
            <td><strong>Total Amount</strong></td>
            <td>${{ number_format($order->total_price, 2) }}</td>
        </tr>
        <tr>
            <td><strong>Status</strong></td>
            <td>{{ ucfirst($order->status) }}</td>
        </tr>
    </table>
</div>

<h2>Items Ordered</h2>
<div class="responsive-table">
    <table>
        <thead>
        <tr>
            <th>Item</th>
            <th>Quantity</th>
            <th>Price</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($order->orderItems as $item)
            <tr>
                <td>{{ $item->medicine->title }}</td>
                <td>{{ $item->quantity }}</td>
                <td>${{ number_format($item->total_price, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<h2>Delivery Information</h2>
<div class="responsive-table">
    <table>
        <tr>
            <td><strong>Name</strong></td>
            <td>{{ $order->billingAddress->name }}</td>
        </tr>
        <tr>
            <td><strong>Address</strong></td>
            <td>{{ $order->billingAddress->address }}, {{ $order->billingAddress->city }}</td>
        </tr>
        <tr>
            <td><strong>Contact</strong></td>
            <td>{{ $order->billingAddress->contact }}</td>
        </tr>
    </table>
</div>

<p>Your order will be shipped soon. We’ll notify you once it’s on its way!</p>
<p>If you have any questions or need assistance, feel free to reply to this email or contact our support team.</p>
<p>Thank you for shopping with us!</p>

<p>Thanks,</p>
<p><strong>{{ config('app.name') }} Team</strong></p>
</body>
</html>
