<?php

namespace App\Notifications;

use App\Reply;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class YouWereMentioned extends Notification
{
    use Queueable;

    /**
     * @var \App\Reply or \App\Thread
     */
    protected $subject;

    /**
     * Create a new notification instance.
     *
     * @param $subject
     */
    public function __construct($subject)
    {
        $this->subject = $subject;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => $this->message(),
            'notifier' => $this->user(),
            'link' => $this->subject->path()
        ];
    }

    /**
     * Get a message title for the notification.
     */
    public function message()
    {
        return sprintf('%s mentioned you in "%s"', $this->user()->username, $this->subject->title());
    }

    /**
     * Get the associated user for the subject.
     */
    public function user()
    {
        return $this->subject instanceof Reply ? $this->subject->owner : $this->subject->creator;
    }
}
