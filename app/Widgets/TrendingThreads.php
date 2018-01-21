<?php

namespace App\Widgets;

use App\Trending;


class TrendingThreads
{
    public $cacheLifeTime = 10;
    public $contextAs = '$trending';
    public $minifyOutput = true;

    /**
     * @param \App\Trending $trending
     *
     * @return array
     */
	public function data(Trending $trending)
	{
		return $trending->get();
	}

}
