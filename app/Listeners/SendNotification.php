<?php

namespace App\Listeners;

use App\Events\EventSent;
use App\Notifications\OrderNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class SendNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(EventSent $event): void
    {
        $order = $event->order;

        // Convert model to array then pick only the keys we need.
        $data = Arr::only($order->toArray(), ['id', 'status', 'total_price', 'created_at']);


        // Prefer order.customer_email, fall back to event user email if available
        $email = $order->customer_email ?? ($event->user->email ?? null);

        if (! $email) {
            Log::warning('SendNotification: order has no recipient email', ['order_id' => $order->id ?? null]);
            return;
        }

        Notification::route('mail', $email)
            ->notify(new OrderNotification($data));
    }
}
