<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected array $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $orderId = $this->data['id'] ?? null;
        $status = $this->data['status'] ?? null;
        $total = $this->data['total_price'] ?? null;

        $message = (new MailMessage)
            ->subject('Order update')
            ->line("Order " . ($orderId ?? ''));

        if ($status) {
            $message->line("Status: {$status}");
        }

        if ($total) {
            $message->line("Total: {$total}");
        }

        $message->action('View order', url('/'));

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return $this->data;
    }
}
