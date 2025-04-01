<?php

namespace App\Events;

use App\Models\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $user_id;
    public $order_id;
    public $notificationCount;

    public function __construct($message, $user_id, $order_id)
    {
        cache()->forget('notifications_' . $user_id);

        $this->notificationCount = $notificationCount = Notification::where('user_id', $user_id)
            ->whereNull('read_at')
            ->count();
        $this->message = $message;
        $this->user_id = $user_id;
        $this->order_id = $order_id;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('notifications.' . $this->user_id);
    }

    public function broadcastAs()
    {
        return 'notification.received';
    }

    // تحديد البيانات التي سيتم إرسالها
    public function broadcastWith()
    {
        return [
            'order_id' => $this->order_id,
            'user_id' => $this->user_id,
            'message' => $this->message,
            'timestamp' => now()->toDateTimeString(),
            'notificationCount' => $this->notificationCount
        ];
    }
}
