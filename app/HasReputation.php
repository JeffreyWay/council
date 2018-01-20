<?php

namespace App;

trait HasReputation
{
    /**
     * Clear user reputation.
     *
     * @return void
     */
    public function clearReputation()
    {
        $this->reputation = 0;

        $this->save();
    }

    /**
     * Award reputation points to the model.
     *
     * @param  string $action
     */
    public function gainReputation($action)
    {
        $this->increment('reputation', config("council.reputation.{$action}"));
    }

    /**
     * Reduce reputation points for the model.
     *
     * @param  string $action
     */
    public function loseReputation($action)
    {
        $this->decrement('reputation', config("council.reputation.{$action}"));
    }
}
