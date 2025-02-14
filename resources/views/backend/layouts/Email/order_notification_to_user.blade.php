<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f8f8;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #27ae60;
            text-align: center;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .details-table th, .details-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .details-table th {
            background-color: #f4f4f4;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
        @media only screen and (max-width: 600px) {
            .container {
                padding: 15px;
            }
            .details-table th, .details-table td {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>📦 Order Confirmation</h1>
    <p>Hi {{ $notifiable->name }},</p>
    <p>Thank you for your order! Below are your order details:</p>

    <h2>Order Details</h2>
    <table class="details-table">
        <tr>
            <th>Order ID</th>
            <td>{{ '#'. $order->uuid }}</td>
        </tr>
        <tr>
            <th>Order Date</th>
            <td>{{ $order->order_date }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ ucfirst($order->status) }}</td>
        </tr>

    </table>

    <h2>Items Ordered</h2>
    <table class="details-table">
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
    <table class="details-table">
        <tr>
            <th>Subtotal</th>
            <td>${{ number_format($order->sub_total, 2) }}</td>
        </tr>
        <tr>
            <th>Discount</th>
            <td>${{ number_format($order->discount, 2) }}</td>
        </tr>
        <tr>
            <th>Shipping Charge</th>
            <td>${{ number_format($order->shipping_charge, 2) }}</td>
        </tr>
        <tr>
            <th>Tax</th>
            <td>${{ number_format($order->tax, 2) }}</td>
        </tr>
        <tr>
            <th>Total Amount</th>
            <td>${{ number_format($order->total_price, 2) }}</td>
        </tr>
    </table>

    <h2>Billing & Shipping Details</h2>
    <table class="details-table">
        <tr>
            <th>Name</th>
            <td>{{ $order->billingAddress->name }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $order->billingAddress->email }}</td>
        </tr>
        <tr>
            <th>Address</th>
            <td>{{ $order->billingAddress->address }}, {{ $order->billingAddress->city }}, {{ $order->billingAddress->postcode }}</td>
        </tr>
        <tr>
            <th>Contact</th>
            <td>{{ $order->billingAddress->contact }}</td>
        </tr>
    </table>

    <p>Your order is being processed and will be shipped soon. </p>
    <p>If you have any questions, feel free to contact our support team.</p>

    <p class="footer">Thanks for shopping with us!<br><strong>{{ config('app.name') }} Team</strong></p>
</div>
</body>
</html>
