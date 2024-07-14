<?php

namespace App\Services;

interface LoggerInterface
{
    public function logEmail($recipient, $subject, $body, $send_at, $error = null): void;
}
