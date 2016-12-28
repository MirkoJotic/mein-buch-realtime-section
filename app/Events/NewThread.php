<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast as ShouldBroadcast;

class NewThread implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $thread;
    public $taskThreadExists;
    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($thread, $user, $taskThreadExists = false)
    {
        $this->thread = $thread;
        $this->taskThreadExists = $taskThreadExists;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('newthread.'.$this->user->id);
    }
}
