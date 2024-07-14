<?php

namespace App\Queue;

use App\Services\EmailService;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

class EmailWorker
{
    private AMQPStreamConnection $connection;
    private AMQPChannel $channel;
    private EmailService $emailService;
    private int $maxRetries = 3;

    public function __construct(EmailService $emailService)
    {
        $this->connection = new AMQPStreamConnection(
            RABBITMQ_HOST,
            RABBITMQ_PORT,
            RABBITMQ_USERNAME,
            RABBITMQ_PASSWORD
        );
        $this->channel = $this->connection->channel();
        $this->emailService = $emailService;

        // Declare queue 'email_queue'
        $this->channel->queue_declare(RABBITMQ_EMAIL_QUEUE, false, true, false, false);
    }

    public function processQueue(): bool
    {
        echo " [*] Waiting for messages. To exit press CTRL+C\n";

        $callback = function (AMQPMessage $msg) {
            $emailData = json_decode($msg->body, true);
            $recipient = $emailData['recipient'];
            $subject = $emailData['subject'];
            $body = $emailData['body'];
            echo " [x] Received ", $msg->body, "\n";

            $retryCount = 0;
            if ($msg->has('application_headers')) {
                $headers = $msg->get('application_headers')->getNativeData();
                $retryCount = isset($headers['x-retry']) ? (int)$headers['x-retry'] : 0;
            }
            if ($this->emailService->send($recipient, $subject, $body)) {
                echo " [x] Sent email to ", $emailData['recipient'], "\n";
            } else {
                $retryCount++;
                if ($retryCount <= $this->maxRetries) {
                    echo " [x] Failed to send email to ", $recipient;
                    echo ". Retrying ", $retryCount, "/", $this->maxRetries, "\n";
                    $headers = new AMQPTable(['x-retry' => $retryCount]);
                    $retryMsg = new AMQPMessage($msg->body, [
                        'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
                        'application_headers' => $headers
                    ]);
                    $this->channel->basic_publish($retryMsg, '', $_ENV['RABBITMQ_EMAIL_QUEUE']);
                } else {
                    echo " [x] Failed to send email to ", $recipient, " after ", $this->maxRetries, " retries\n";
                }
            }
            $msg->ack();
        };

        $this->channel->basic_consume(
            $_ENV['RABBITMQ_EMAIL_QUEUE'],
            '',
            false,
            false, // no_ack set to false
            false,
            false,
            $callback
        );

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }

        return true;
    }

    public function __destruct()
    {
        try {
            if ($this->connection) {
                $this->channel->close();
                $this->connection->close();
            }
        } catch (\Exception $e) {
            echo 'Exception during RabbitMQ connection closing: ' . $e->getMessage();
        }
    }
}
