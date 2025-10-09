<?php

namespace App\Events;

use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EventSent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public Order $order;
    public  $cart;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, Order $order,  $cart)
    {
        $this->user = $user;
        $this->order = $order;
        $this->cart = $cart;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
