<?php

namespace App\Services;

use App\DB;
use App\Repositories\AccessTokenRepository;
use App\Repositories\ClientRepository;
use App\Repositories\ScopeRepository;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;

class OAuth2Service
{
    private AuthorizationServer $authorizationServer;
    private ResourceServer $resourceServer;

    public function __construct()
    {
        $privateKeyPath = realpath(__DIR__ . '/../../' . OAUTH2_PRIVATE_KEY_PATH);
        $publicKeyPath = realpath(__DIR__ . '/../../' . OAUTH2_PUBLIC_KEY_PATH);

        if ($privateKeyPath === false || $publicKeyPath === false) {
            throw new \LogicException('Invalid key path supplied');
        }

        $privateKey = new CryptKey($privateKeyPath, null, false);
        $publicKey = new CryptKey($publicKeyPath, null, false);

        $db = (new DB())->getConnection();
        $clientRepository = new ClientRepository($db);
        $accessTokenRepository = new AccessTokenRepository($db);
        $scopeRepository = new ScopeRepository();

        $this->authorizationServer = new AuthorizationServer(
            $clientRepository,
            $accessTokenRepository,
            $scopeRepository,
            $privateKey,
            OAUTH2_ENCRYPTION_KEY
        );

        $this->authorizationServer->enableGrantType(
            new ClientCredentialsGrant(),
            new \DateInterval('PT1H')
        );

        $this->resourceServer = new ResourceServer(
            $accessTokenRepository,
            $publicKey
        );
    }

    /**
     * @throws OAuthServerException
     */
    public function generateToken(ServerRequestInterface $request): Response
    {
        return $this->authorizationServer->respondToAccessTokenRequest($request, new Response());
    }

    /**
     * @throws OAuthServerException
     */
    public function validateToken(ServerRequestInterface $request): ServerRequestInterface
    {
        return $this->resourceServer->validateAuthenticatedRequest($request);
    }
}
