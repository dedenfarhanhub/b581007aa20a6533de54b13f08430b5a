<?php

namespace App\Controllers;

use App\Services\OAuth2Service;
use App\Request;
use App\Response;
use League\OAuth2\Server\Exception\OAuthServerException;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ServerRequestInterface;

class TokenController
{
    private OAuth2Service $oauth2Service;

    public function __construct(OAuth2Service $oauth2Service)
    {
        $this->oauth2Service = $oauth2Service;
    }

    public function generateToken(Request $request, Response $response): Response
    {
        try {
            $psr17Factory = new Psr17Factory();
            $serverRequest = $psr17Factory->createServerRequest(
                $request->getMethod(),
                $request->getUri(),
                $request->getHeaders()
            );

            $serverRequest = $serverRequest->withParsedBody($request->getParsedBody());

            $psrResponse = $this->oauth2Service->generateToken($serverRequest);

            $response->withStatus($psrResponse->getStatusCode());
            foreach ($psrResponse->getHeaders() as $name => $values) {
                foreach ($values as $value) {
                    $response->addHeader($name, $value);
                }
            }
            $bodyContent = (string) $psrResponse->getBody();
            $bodyArray = json_decode($bodyContent, true);

            $response->success($bodyArray);
        } catch (OAuthServerException $e) {
            $response->withStatus($e->getHttpStatusCode());
            $response->error($e->getMessage(), ['hint' => $e->getHint()]);
        } catch (\Exception $e) {
            $response->withStatus(500);
            $response->error('An unexpected error occurred', ['message' => $e->getMessage()]);
        }

        return $response;
    }
}
