<?php

namespace App;

class Response
{
    private int $statusCode = 200;
    private array $headers = [];
    private string $body = '';

    public function withStatus(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function addHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;
        return $this;
    }

    public function send(): void
    {
        http_response_code($this->statusCode);
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
        echo $this->body;
    }

    public function sendJson(array $data): void
    {
        $this->addHeader('Content-Type', 'application/json');
        $this->setBody(json_encode($data));
        $this->send();
    }

    public function success(array $data): static
    {
        $this->sendJson(['status' => 'success', 'data' => $data]);
        return $this;
    }

    public function error(string $message, array $errors = []): static
    {
        $this->sendJson(['status' => 'error', 'message' => $message, 'errors' => $errors]);
        return $this;
    }
}
