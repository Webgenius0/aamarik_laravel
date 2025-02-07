@php
    $systemSetting = App\Models\Setting::first();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- FAVICON --}}
    <link rel="shortcut icon" type="image/x-icon"
          href="{{ isset($systemSetting->favicon) && !empty($systemSetting->favicon) ? asset($systemSetting->favicon) : asset('uploads/defult-image/favicon.png') }}" />

    <title>{{ $systemSetting->system_name }}</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        body { font-family: 'Arial', sans-serif; margin: 0; padding: 20px; background-color: #ffffff; color: #333; }
        .invoice-container { background: #ffffff; color: #333; padding: 40px; border-radius: 10px; max-width: 900px; margin: auto; box-shadow: 0px 5px 15px rgba(0,0,0,0.1); }


        .header { text-align: left; }
        .header img { width: 50px; margin-bottom: 10px; }
        .header h1 { font-size: 15px; color: #333; }
        .invoice-details { display: flex; justify-content: space-between; margin-top: 20px; }
        .invoice-details div { width: 48%; }
        .invoice-details h3 { font-size: 18px; color: #555; }
        .invoice-details p { margin: 5px 0; font-size: 16px; color: #666; }
        .table-container { margin-top: 30px; }
        table { width: 100%; border-collapse: collapse; background: #fff; color: #333; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 12px; text-align: left; font-size: 16px; }
        th { background: #f8f8f8; }
        .footer { text-align: center; margin-top: 30px; font-size: 14px; color: #666; }
        .qr-code { text-align: center; margin-top: 20px; }
        .qr-code img { width: 80px; height: 80px; }
        .download-btn { background: #007BFF; color: #fff; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; margin-top: 20px; }
    </style>
</head>
<body>
<div class="invoice-container" id="invoice">
    <div class="header">
        <img src="{{ asset($logoPath) }}" alt="Company Logo">
        <h1>{{ $setting->title ?? config('app.name') }}</h1>
    </div>

    <div class="invoice-details">
        <div>
            <h3>Invoice To:</h3>
            <p><strong>Name:</strong> {{ $order->billingAddress->name }}</p>
            <p><strong>Email:</strong> {{ $order->billingAddress->email }}</p>
            <p><strong>Contact:</strong> {{ $order->billingAddress->contact }}</p>
            <p><strong>Billing Address:</strong> {{ $order->billingAddress->address }}</p>
            <p><strong>City:</strong> {{ $order->billingAddress->city }}</p>
            <p><strong>Postcode:</strong> {{ $order->billingAddress->postcode }}</p>
            <p><strong>GP Number:</strong> {{ $order->billingAddress->gp_number }}</p>
            <p><strong>GP Address:</strong> {{ $order->billingAddress->gp_address }}</p>
        </div>
        <div>
            <h3>Order Details:</h3>
            <p><strong>Order ID:</strong> #{{ $order->uuid ?? ''  }}</p>
            <p><strong>Order Date:</strong>  {{ $order->created_at->format('Y-m-d h:i A') }}</p>
            <p><strong>Delivery Date:</strong> {{ $order->status == 'delivered' ? $order->updated_at->format('Y-m-d  h:i A') : 'Not Delivered' }}</p>
            <p><strong>Status:</strong> {{ $order->status  }}</p>
        </div>
    </div>

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
                        <td>{{ $item->medicine->title }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ $item->unit_price }}</td>
                        <td>${{ $item->total_price }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="invoice-details" style="margin-top: 30px;">
        <div>

        </div>
        <div>
            <h3>Payment Summary</h3>
            <p><strong>Subtotal:</strong> ${{ $order->sub_total  }}</p>
            <p><strong>Discount:</strong> -${{ $order->discount }}</p>
            <p><strong>Shipping charge:</strong> ${{ $order->shipping_charge ?? '0.00' }} (2%)</p> <!-- Show the shipping charge dynamically -->
            <p><strong>Tax:</strong> ${{ $order->tax }} (10%)</p>
            <p><strong>Total Amount:</strong> ${{ $totalPrice }}</p> <!-- Show the dynamically calculated total price -->

        </div>
    </div>

    <div class="qr-code">
        <h3>Scan to View Order Details</h3>
        <img src="{{ asset($qr_code_path) }}" alt="QR code">
    </div>

    <div class="footer">
        <p>Thank you for your order! If you have any concerns, feel free to contact our support team. We're here to help!</p>
        <p><strong>Company Name:</strong> {{ $setting->title ? $setting->title : config('app.name') }}</p>

        <p><strong>Company Address:</strong> {{ $setting->address ?? '... .. .' }}</p>
    </div>

    <button class="download-btn" onclick="downloadInvoice()">Download PDF</button>
</div>

<script>
    function downloadInvoice() {
        const { jsPDF } = window.jspdf;
        html2canvas(document.querySelector("#invoice"), { scale: 2 }).then(canvas => {
            let pdf = new jsPDF('p', 'mm', 'a4');
            let imgData = canvas.toDataURL('image/png');
            let imgWidth = 210;
            let imgHeight = (canvas.height * imgWidth) / canvas.width;
            pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight);
            pdf.save("Luxury_Invoice.pdf");
        });
    }
</script>
</body>
</html>
