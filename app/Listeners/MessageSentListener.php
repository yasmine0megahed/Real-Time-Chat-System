<?php

namespace App\Listeners;

use App\Events\MessageSent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class MessageSentListener
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
    public function handle(MessageSent $event): void
    {
        Log::info('Message Sent:', [
            'message' => $event->message->message,
            'sender_id' => $event->message->sender_id,
            'receiver_id' => $event->message->receiver_id,
        ]);
    }
}
