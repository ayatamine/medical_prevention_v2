<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chatMessage;

    /**
     * Create a new event instance.
     *
     * @param ChatMessage $chatMessage
     * @return void
     */
    public function __construct(ChatMessage $chatMessage)
    {
        $this->chatMessage = $chatMessage;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|Channel[]|PrivateChannel
     */
    public function broadcastOn()
    {
        // Broadcast to a channel specific to the consultation ID
        return new PrivateChannel('consultation.' . $this->chatMessage->consultation_id);
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        $message =$this->chatMessage->toArray();
        // $message['attachement'] =$message['attachement'] ? url('storage/'.$message['attachement']) : null;
        if(!$message['attachement']) unset($message['attachement_type']);
        return [
            'chat_message' => $message,
        ];
    }
}
