<?php

namespace App;

use App\Notifications\YouWereMentioned;

class Mentions
{
    /**
     * Fetch all mentioned users within the subject's body.
     *
     * @param $body
     * @return bool
     */
    protected function fetchUsers($body)
    {
       preg_match_all('/@([\w\-]+)/', $body, $matches);

       return $matches[1];
    }

    /**
     * Notify all mentioned users within the subject's body.
     *
     * @param $subject
     */
    public function notifyMentionedUsers($subject)
    {
        User::whereIn('name', $this->fetchUsers($subject->body))
            ->get()
            ->each(function ($user) use ($subject) {
                $user->notify(new YouWereMentioned($subject));
            });
    }

}