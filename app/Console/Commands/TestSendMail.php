<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Order;
use App\Models\Cart;
use App\Listeners\SendMail as SendMailListener;
use App\Listeners\SendNotification as SendNotificationListener;

class TestSendMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:send-mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test order mail and notification (writes to log using MAIL_MAILER=log)';

    public function handle()
    {
        // Create in-memory models (not saved)
        $user = new User(['id' => 9999, 'name' => 'Tinker User', 'email' => 'tinker@example.com']);
        $order = new Order([
            'id' => 8888,
            'user_id' => 9999,
            'order_number' => 'TEST-ORD-9999',
            'customer_email' => 'tinker@example.com',
            'customer_name' => 'Tinker User',
            'address' => '123 Test St',
            'total_price' => 123.45,
            'status' => 'testing',
            'created_at' => now(),
        ]);

        $cartItems = collect([
            new Cart(['product_name' => 'Test Product A', 'quantity' => 1, 'unit_price' => 12.34, 'total_price' => 12.34]),
            new Cart(['product_name' => 'Test Product B', 'quantity' => 2, 'unit_price' => 55.55, 'total_price' => 111.10]),
        ]);

        // Manually set relation to avoid DB lazy-loading
        $order->setRelation('user', $user);

        $this->info('Calling SendMail listener...');
        $mailListener = new SendMailListener();
        $mailListener->handle(new \App\Events\EventSent($user, $order, $cartItems));

        $this->info('Calling SendNotification listener...');
        $notifyListener = new SendNotificationListener();
        $notifyListener->handle(new \App\Events\EventSent($user, $order, $cartItems));

        $this->info('Done — check storage/logs/laravel.log');

        return 0;
    }
}
