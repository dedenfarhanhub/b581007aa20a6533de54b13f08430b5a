<?php

use App\Services\LoggerInterface;
use PHPMailer\PHPMailer\PHPMailer;
use PHPUnit\Framework\TestCase;
use App\Services\EmailService;

class EmailServiceTest extends TestCase
{
    protected EmailService $emailService;
    protected $mockMailer;
    protected $mockLogger;

    protected function setUp(): void
    {
        $this->mockMailer = $this->createMock(PHPMailer::class);
        $this->mockLogger = $this->createMock(LoggerInterface::class);

        $this->emailService = new EmailService($this->mockLogger);
        $this->emailService->setMailer($this->mockMailer);

        if (!defined('MAIL_HOST')) {
            define('MAIL_HOST', 'smtp.example.com');
        }
        if (!defined('MAIL_USERNAME')) {
            define('MAIL_USERNAME', 'test@example.com');
        }
        if (!defined('MAIL_PASSWORD')) {
            define('MAIL_PASSWORD', 'secret');
        }
        if (!defined('MAIL_PORT')) {
            define('MAIL_PORT', 587);
        }
        if (!defined('MAIL_FROM_ADDRESS')) {
            define('MAIL_FROM_ADDRESS', 'noreply@example.com');
        }
        if (!defined('MAIL_FROM_NAME')) {
            define('MAIL_FROM_NAME', 'Example');
        }
    }

    public function testSendEmailSuccess()
    {
        $this->mockMailer->expects($this->once())->method('send')->willReturn(true);

        $this->mockLogger->expects($this->once())
            ->method('logEmail')
            ->with('test@example.com', 'Test Subject', 'Test Body', $this->anything());

        $result = $this->emailService->send('test@example.com', 'Test Subject', 'Test Body');
        $this->assertTrue($result);
    }

    public function testSendEmailFailure()
    {
        $this->mockMailer->method('send')
            ->will($this->throwException(new \PHPMailer\PHPMailer\Exception('SMTP Error')));

        $this->mockLogger->expects($this->once())
            ->method('logEmail')
            ->with('test@example.com', 'Test Subject', 'Test Body', null, $this->anything());

        $result = $this->emailService->send('test@example.com', 'Test Subject', 'Test Body');
        $this->assertFalse($result);
    }
}
