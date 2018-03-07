<?php

namespace App\Observers;

use App\Reply;

class ReplyReputationObserver
{
    /**
     * @param Reply $reply
     */
    public function created($reply)
    {
        $reply->owner->gainReputation('reply_posted');
    }

    /**
     * @param Reply $reply
     */
    public function deleted($reply)
    {
        $reply->owner->loseReputation('reply_posted');

        if ($reply->isBest()) {
            $reply->owner->loseReputation('best_reply_awarded');
        }
    }
}