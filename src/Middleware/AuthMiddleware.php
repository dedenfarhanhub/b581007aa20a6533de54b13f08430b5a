<?php

namespace App\Middleware;

use App\DB;
use App\Repositories\AccessTokenRepository;
use App\Repositories\ClientRepository;
use App\Repositories\ScopeRepository;
use App\Request;
use App\Response;
use App\Services\OAuth2Service;
use League\OAuth2\Server\Exception\OAuthServerException;
use Nyholm\Psr7\Factory\Psr17Factory;

class AuthMiddleware extends Middleware
{
    private OAuth2Service $oauth2Service;

    public function __construct(callable $next)
    {
        parent::__construct($next);
        $this->oauth2Service = new OAuth2Service();
    }

    public function handle(Request $request, Response $response): void
    {
        $authorizationHeader = $request->getHeaderLine('Authorization');

        if (empty($authorizationHeader)) {
            $response->withStatus(401)->error('Unauthorized', ['error' => 'Unauthorized']);
            return;
        }

        $token = $this->extractBearerToken($authorizationHeader);
        if (empty($token)) {
            $response->withStatus(401)->error('Unauthorized', ['error' => 'Unauthorized']);
            return;
        }

        try {
            $psr17Factory = new Psr17Factory();
            $serverRequest = $psr17Factory->createServerRequest('GET', '/');
            $serverRequest = $serverRequest->withHeader('Authorization', 'Bearer ' . $token);

            $this->oauth2Service->validateToken($serverRequest);
            ($this->next)($request, $response);
        } catch (OAuthServerException $e) {
            $response->withStatus(401)->error('Unauthorized', ['error' => 'Unauthorized']);
            return;
        }
    }

    private function extractBearerToken(string $authorizationHeader): ?string
    {
        if (preg_match('/Bearer\s(\S+)/', $authorizationHeader, $matches)) {
            return $matches[1];
        }
        return null;
    }
}
