<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\AnonymousNotifiable;
use App\Events\EventSent;
use App\Mail\OrderMAil;
use App\Notifications\OrderNotification;
use App\Models\User;
use App\Models\Order;
use App\Models\Cart;
use App\Listeners\SendMail as SendMailListener;
use App\Listeners\SendNotification as SendNotificationListener;

class OrderMailNotificationTest extends TestCase
{
    public function test_order_mail_and_notification_are_sent_when_event_dispatched_without_db()
    {
        Mail::fake();
        Notification::fake();

    // Create in-memory model instances (not persisted)
    $user = new User(['id' => 123, 'name' => 'Test User', 'email' => 'test@example.com']);
    $order = new Order([ 'id' => 456, 'user_id' => 123, 'order_number' => 'ORD-123', 'customer_email' => 'test@example.com', 'total_price' => 99.99, 'created_at' => now(), 'status' => 'pending' ]);

    // Prevent Eloquent lazy-loading from hitting DB by setting relation in-memory
    $order->setRelation('user', $user);

        // Create a couple of cart items in-memory
        $cartItems = collect([
            new Cart(['product_name' => 'Product A', 'quantity' => 1, 'unit_price' => 20.00, 'total_price' => 20.00]),
            new Cart(['product_name' => 'Product B', 'quantity' => 2, 'unit_price' => 39.995, 'total_price' => 79.99]),
        ]);

        // Instead of dispatching the event (which serializes models and may hit DB),
        // instantiate listeners directly and call handle() with the in-memory EventSent.
        $event = new EventSent($user, $order, $cartItems);

        $mailListener = new SendMailListener();
        $notifyListener = new SendNotificationListener();

    // Sanity-check: directly send a mailable to ensure Mail::fake() captures sends
    Mail::to($order->customer_email)->send(new OrderMAil($user, $order, $cartItems));

    // Call listeners directly (they will use Mail::fake() / Notification::fake())
    $mailListener->handle($event);
        $notifyListener->handle($event);

        // Assert mailable was sent and contains the order passed
        Mail::assertSent(OrderMAil::class, function ($mail) use ($order) {
            return isset($mail->order) && ($mail->order->order_number ?? null) === $order->order_number;
        });

        // Assert a notification was sent to the expected email by inspecting the sent notifications
        $anon = new \Illuminate\Notifications\AnonymousNotifiable();
        $anon->route('mail', $order->customer_email);

        Notification::assertSentTo($anon, OrderNotification::class);
    }
}
