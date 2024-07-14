<?php

namespace App\Controllers;

use App\Queue\EmailQueue;
use App\Request;
use App\Response;

class EmailController
{
    private EmailQueue $queue;

    public function __construct(EmailQueue $queue)
    {
        $this->queue = $queue;
    }

    public function sendEmail(Request $request, Response $response): Response
    {
        $body = json_decode($request->getBody(), true);

        if (empty($body['recipient']) || empty($body['subject']) || empty($body['body'])) {
            return $response->withStatus(400)->error('Invalid input', ['Invalid input']);
        }

        $this->queue->addEmailToQueue($body['recipient'], $body['subject'], $body['body']);

        return $response->withStatus(200)->success(['message' => 'Email sent successfully']);
    }
}
