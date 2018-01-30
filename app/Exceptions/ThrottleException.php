<?php

namespace App\Exceptions;

class ThrottleException extends \Exception
{
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json(['reason' => $this->getMessage()], 429);
        }
        return response($this->getMessage(), 429);
    }
}
