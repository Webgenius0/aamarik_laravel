<?php

namespace App\Notifications;

use App\Helper\Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendChallengeNotifyUser extends Notification
{
    use Queueable;

    private $data;
    private $user;
    /**
     * Create a new notification instance.
     */
    public function __construct($data, $user)
    {
        $this->data = $data;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $title   = $this->data['title'] ?? 'Default Title';
        $message = $this->data['message'] ?? 'No message provided';

        $data = [
            'title' => $title,
            'message' => $message,
        ];
        //notify user in firebase
        if ($this->user->firebaseTokens) {
            foreach ($this->user->firebaseTokens as $firebaseToken) {
                // Call the sendNotifyMobile method
                Helper::sendNotifyMobile($firebaseToken->token, $data);
            }
        }
        return [
            'title'   => $title,
            'message' => $message,
        ];
    }
}
