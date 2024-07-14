<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\EmailController;
use App\Queue\EmailQueue;
use App\Request;
use App\Response;

class EmailControllerTest extends TestCase
{
    protected EmailController $controller;
    protected $queueMock;

    protected function setUp(): void
    {
        $this->queueMock = $this->createMock(EmailQueue::class);

        $this->controller = new EmailController($this->queueMock);
    }

    public function testSendEmailSuccess()
    {
        $payload = [
            'recipient' => 'test@example.com',
            'subject' => 'Test Subject',
            'body' => 'Test Body',
        ];
        $request = $this->createMock(Request::class);
        $request->method('getBody')->willReturn(json_encode($payload));

        $response = $this->createMock(Response::class);
        $response->method('withStatus')->willReturnSelf(); // Ensure methods return $this
        $response->method('success')->willReturnSelf();

        $this->queueMock->expects($this->once())
            ->method('addEmailToQueue')
            ->with($payload['recipient'], $payload['subject'], $payload['body']);

        $result = $this->controller->sendEmail($request, $response);
        $this->assertInstanceOf(Response::class, $result);
    }

    public function testSendEmailInvalidInput()
    {
        $payload = [
            'subject' => 'Test Subject',
            'body' => 'Test Body',
        ];
        $request = $this->createMock(Request::class);
        $request->method('getBody')->willReturn(json_encode($payload));

        $response = $this->createMock(Response::class);
        $response->expects($this->once())
            ->method('withStatus')
            ->with(400)
            ->willReturnSelf();
        $response->expects($this->once())
            ->method('error')
            ->with('Invalid input', ['Invalid input']);

        $result = $this->controller->sendEmail($request, $response);

        $this->assertInstanceOf(Response::class, $result);
    }
}