<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class OrderNotificationToUser extends Notification
{
    use Queueable;

    private $order;

    /**
     * Create a new notification instance.
     */
    public function __construct($order)
    {
        // Load necessary relationships to avoid N+1 query issues
        $this->order = $order->load('orderItems.medicine', 'billingAddress', 'coupon');

    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database','mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Order Confirmation')
            ->markdown('backend.layouts.Email.order_notification_to_user', [
                'order' => $this->order,
                'notifiable' => $notifiable,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_uuid' => $this->order->uuid, // Public UUID for order reference
            'user_name' => $notifiable->name, // User's name
            'message' => "Thank you for your order! Your order #{$this->order->uuid} has been placed and is currently being processed.",
            'created_at' => now()->toDateTimeString(), // Timestamp of the notification
        ];

    }
}
