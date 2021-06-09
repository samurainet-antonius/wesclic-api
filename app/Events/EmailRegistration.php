<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EmailRegistration extends Event implements ShouldBroadcast
{

    use SerializesModels,InteractsWithSockets;
    public $request;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->request = $data;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('registration');
    }
}
