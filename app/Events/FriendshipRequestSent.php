<?php

namespace App\Events;

use App\Models\FriendshipRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class FriendshipRequestSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public $request;

    public function __construct(FriendshipRequest $request)
    {
        $this->request = $request;
    }

    public function broadcastOn()
    {
        return new Channel('friendship.' . $this->request->receiver_id);
    }
}
