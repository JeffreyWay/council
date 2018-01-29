<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class ThrottleException extends Exception
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
            return response()->json(['reason' => $this->getMessage()], Response::HTTP_TOO_MANY_REQUESTS);
        }
        return response($this->getMessage(), Response::HTTP_TOO_MANY_REQUESTS);
    }
}
