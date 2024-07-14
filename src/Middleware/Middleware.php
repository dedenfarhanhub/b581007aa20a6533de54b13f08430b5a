<?php

namespace App\Middleware;

use App\Request;
use App\Response;

class Middleware
{
    protected $next;

    public function __construct(callable $next)
    {
        $this->next = $next;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        return call_user_func($this->next, $request, $response);
    }
}
