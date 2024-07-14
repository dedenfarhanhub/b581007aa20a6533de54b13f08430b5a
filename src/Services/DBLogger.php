<?php

namespace App\Services;

use App\DB;

class DBLogger implements LoggerInterface
{
    public function logEmail($recipient, $subject, $body, $send_at, $error = null): void
    {
        $pdo = DB::getInstance()->getConnection();
        $stmt = $pdo->prepare(
            "INSERT INTO email_logs (recipient, subject, body, send_at, error) 
                    VALUES (:to_email, :subject, :body, :send_at, :error)"
        );
        $stmt->bindParam(':to_email', $recipient);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':body', $body);
        $stmt->bindParam(':send_at', $send_at);
        $stmt->bindParam(':error', $error);
        $stmt->execute();
    }
}
