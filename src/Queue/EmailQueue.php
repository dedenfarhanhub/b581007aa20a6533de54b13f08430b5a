<?php

namespace App\Queue;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class EmailQueue
{
    private AMQPStreamConnection $connection;
    private AMQPChannel $channel;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            RABBITMQ_HOST,
            RABBITMQ_PORT,
            RABBITMQ_USERNAME,
            RABBITMQ_PASSWORD
        );
        $this->channel = $this->connection->channel();
    }

    public function addEmailToQueue($recipient, $subject, $body): void
    {
        $this->channel->queue_declare(RABBITMQ_EMAIL_QUEUE, false, true, false, false);

        $messageBody = json_encode(['recipient' => $recipient, 'subject' => $subject, 'body' => $body]);

        $message = new AMQPMessage($messageBody, [
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
        ]);

        $this->channel->basic_publish($message, '', RABBITMQ_EMAIL_QUEUE);
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
