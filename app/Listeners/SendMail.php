<?php

namespace App\Listeners;

use App\Events\EventSent;
use App\Mail\OrderMAil;
use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendMail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(EventSent $event): void
    {
   
        $user = $event->order->user ?? null;
        // The OrderMAil mailable expects (User $user, Order $order, Cart $cart)
        // The EventSent event already carries the User instance as $event->user.
        Mail::to($event->order->customer_email)->send(new OrderMAil(
            $event->user,
            $event->order,
            $event->cart
        ));
}

}
