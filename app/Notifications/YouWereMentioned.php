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

        $isReply = ($subject instanceof Reply);
        $this->subject['title'] = $isReply ? $subject->thread->title : $subject->title;
        $this->subject['owner'] = $isReply ? $subject->owner->name : $subject->creator->name;
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
            'message' => $this->subject['owner'] . ' mentioned you in ' . $this->subject['title'],
            'link' => $this->subject->path()
        ];
    }
}
