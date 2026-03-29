<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class MessageSentNotification extends Notification
{
    use Queueable;

    protected $message;

    public function __construct($message)
    {
        // We pass the message data into the notification
        $this->message = $message;
    }

    public function via($notifiable)
    {
        // Tell Laravel to send this via the WebPush channel
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        // This is what the actual notification bubble looks like on the phone/desktop
        return (new WebPushMessage)
            ->title('New message from ' . $this->message->user->name)
            ->body($this->message->body)
            ->icon('/logo.png') // Make sure you have a logo.png in your /public folder!
            ->data(['url' => url('/app')]) // Open the app when clicked
            ->options(['TTL' => 1000]); // Time to live (seconds)
    }
}