<?php

namespace App\Http\Controllers\Web\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;

use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class InvoiceController extends Controller
{
    public function show($id)
    {
        // Fetch order details from the database
        $order = Order::with(['user', 'treatment', 'orderItems', 'billingAddress'])->where('uuid',$id)->first();

    }
    /**
     * Generate and download the invoice as PDF.
     */
    public function downloadInvoice($id)
    {
        // Fetch order details from the database
        $order = Order::with(['user', 'treatment', 'orderItems', 'billingAddress'])->where('uuid',$id)->first();

        // Fetch setting (logo & company info)
        $setting = Setting::first();

        // Ensure logo exists, otherwise use a default image
        $logoPath = public_path(optional($setting)->logo ? $setting->logo : 'uploads/default-image/logo.png');
        $qr_code_path = public_path(optional($order)->qr_code);

        $pdf = SnappyPdf::loadView('backend.layouts.Order.invoice', compact('order', 'setting', 'logoPath', 'qr_code_path'));

        return $pdf->download('invoice_' . $order->order_number . '.pdf');
    }
}
