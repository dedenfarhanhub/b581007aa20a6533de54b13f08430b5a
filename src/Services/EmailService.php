<?php

namespace App\Services;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class EmailService
{
    private PHPMailer $mailer;
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->mailer = new PHPMailer(true);
        $this->logger = $logger;
    }

    public function setMailer(PHPMailer $mailer): void
    {
        $this->mailer = $mailer;
    }

    public function send($recipient, $subject, $body): bool
    {
        try {
            $this->mailer->isSMTP();
            $this->mailer->Host = MAIL_HOST;
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = MAIL_USERNAME;
            $this->mailer->Password = MAIL_PASSWORD;
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mailer->Port = MAIL_PORT;

            $this->mailer->setFrom(MAIL_FROM_ADDRESS, MAIL_FROM_NAME);
            $this->mailer->addAddress($recipient);

            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;

            $this->mailer->send();
            $this->logger->logEmail($recipient, $subject, $body, date('Y-m-d H:i:s'));
            return true;
        } catch (Exception $e) {
            $this->logger->logEmail($recipient, $subject, $body, null, $e->errorMessage());
            return false;
        }
    }
}
