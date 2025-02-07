<?php

namespace App\Http\Controllers\Web\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function show($id)
    {
        // Fetch order details from the database
        $order = Order::with(['user', 'treatment', 'orderItems', 'billingAddress'])->where('uuid',$id)->first();
        if (!$order) {
            abort(404);
        }
        // Fetch setting (logo & company info)
        $setting = Setting::first();

        $logoPath = $setting->logo ;
        $totalPrice = $order->sub_total - $order->discount + $order->royal_mail_tracked_price + $order->shipping_charge + $order->tax;

        return view('backend.layouts.Order.show_invoice_details',compact('order','setting','totalPrice','logoPath'));

    }
    /**
     * Generate and download the invoice as PDF.
     */
    public function downloadInvoice($id)
    {
        // Fetch order details from the database
        $order = Order::with(['user', 'treatment', 'orderItems','orderItems.medicine', 'billingAddress'])->where('uuid',$id)->first();

        // Fetch setting (logo & company info)
        $setting = Setting::first();

        // Ensure logo exists, otherwise use a default image
//        $logoPath = $this->base64ImageHelper(public_path(optional($setting)->logo ? $setting->logo : 'uploads/default-image/logo.png'));
//       $qr_code_path =  $this->base64ImageHelper(public_path($order->qr_code ?? 'No QR Code'));
        $logoPath = $setting->logo ;
        $qr_code_path =  $order->qr_code ?? 'No QR Code';

        $totalPrice = $order->sub_total - $order->discount + $order->royal_mail_tracked_price + $order->shipping_charge + $order->tax;


        return view('backend.layouts.Order.invoice', compact('order', 'logoPath', 'qr_code_path','setting','totalPrice'));
    }

    function base64ImageHelper($filePath) {
        // Encode image to Base64
        if (!file_exists($filePath)) {
            return "File not found!";
        }
        $imageData = file_get_contents($filePath);
        return base64_encode($imageData);
    }
}
