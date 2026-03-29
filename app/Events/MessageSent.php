<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// ShouldBroadcastNow sends the message immediately without a queue worker
class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The message instance.
     */
    public function __construct(public Message $message)
    {
        // By making this 'public', Laravel automatically 
        // includes the message data in the JSON broadcast.
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        // Must match your window.Echo.join('our-space') in React
        return [
            new PresenceChannel('our-space'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'MessageSent';
    }
}