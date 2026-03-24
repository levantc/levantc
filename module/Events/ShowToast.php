<?php

namespace Eta\Core\Module\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Eta\Core\Module\Actions\Feedback\Toast;

class ShowToast implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var array
     * The serialized Toast data that will be sent to the frontend.
     * Expected keys: variant, title, message, actions[]
     */
    public array $toast;

    /**
     * Constructor
     *
     * @param array $toast
     * Initialize the event with the Toast payload.
     * Should already be serialized via Toast::toArray().
     */
    public function __construct(array $toast)
    {
        $this->toast = $toast;
    }

    /**
     * The channel the event should broadcast on.
     *
     * @return Channel
     * Using a public/general channel named 'toast'.
     * Could consider private channels if user-specific notifications are needed.
     */
    public function broadcastOn(): Channel
    {
        return new Channel('toast');
    }

    /**
     * Customize the event name for broadcasting.
     */
    public function broadcastAs(): string
    {
        return 'ShowToast';
    }

    /**
     * Customize the broadcast payload.
     *
     * @return array
     * Provides the 'toast' key to the frontend.
     * Keeps the frontend integration simple (Vue + vue-sonner).
     */
    public function broadcastWith(): array
    {
        return [
            'toast' => $this->toast
        ];
    }
}
