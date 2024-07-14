<?php

namespace App;

use Psr\Http\Message\ServerRequestInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;

class Request
{
    private ServerRequestInterface $serverRequest;

    public function __construct()
    {
        $psr17Factory = new Psr17Factory();
        $creator = new ServerRequestCreator(
            $psr17Factory,
            $psr17Factory,
            $psr17Factory,
            $psr17Factory
        );

        $this->serverRequest = $creator->fromGlobals();
    }


    public function getBody(): false|string
    {
        return file_get_contents('php://input');
    }

    public function getQueryParam($key)
    {
        return $_GET[$key] ?? null;
    }
    public function getMethod(): string
    {
        return $this->serverRequest->getMethod();
    }

    public function getUri(): string
    {
        return (string) $this->serverRequest->getUri();
    }

    public function getHeaders(): array
    {
        return $this->serverRequest->getHeaders();
    }

    public function getParsedBody(): array
    {
        return $this->serverRequest->getParsedBody() ?? [];
    }

    public function getHeaderLine(string $name): string
    {
        return $this->serverRequest->getHeaderLine($name);
    }

    public function getServerRequest(): ServerRequestInterface
    {
        return $this->serverRequest;
    }
}
