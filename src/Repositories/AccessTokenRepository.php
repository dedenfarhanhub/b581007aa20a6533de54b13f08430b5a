<?php

namespace App\Repositories;

use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use App\Entities\AccessTokenEntity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use PDO;

class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity): void
    {
        $stmt = $this->db->prepare("
            INSERT INTO oauth_access_tokens (id, client_id, user_id, scopes,
                                             revoked, expires_at, created_at, updated_at)
            VALUES (:id, :client_id, :user_id, :scopes, :revoked, :expires_at, :created_at, :updated_at)
        ");

        $stmt->execute([
            'id' => $accessTokenEntity->getIdentifier(),
            'client_id' => $accessTokenEntity->getClient()->getIdentifier(),
            'user_id' => $accessTokenEntity->getUserIdentifier(),
            'scopes' => '{' . implode(',', $accessTokenEntity->getScopes()) . '}',
            'revoked' => 'false', // Ensure this is a string 'false'
            'expires_at' => $accessTokenEntity->getExpiryDateTime()->format('Y-m-d H:i:s'),
            'created_at' => (new \DateTime())->format('Y-m-d H:i:s'),
            'updated_at' => (new \DateTime())->format('Y-m-d H:i:s')
        ]);
    }

    public function revokeAccessToken($tokenId): void
    {
        $stmt = $this->db->prepare("UPDATE oauth_access_tokens SET revoked = true WHERE id = ?");
        $stmt->execute([$tokenId]);
    }

    public function isAccessTokenRevoked($tokenId): bool
    {
        $stmt = $this->db->prepare("SELECT revoked FROM oauth_access_tokens WHERE id = ?");
        $stmt->execute([$tokenId]);
        return (bool) $stmt->fetchColumn();
    }

    public function getNewToken(
        ClientEntityInterface $clientEntity,
        array $scopes,
        $userIdentifier = null
    ): AccessTokenEntityInterface {
        $accessToken = new AccessTokenEntity();
        $accessToken->setClient($clientEntity);
        $accessToken->setUserIdentifier($userIdentifier);
        foreach ($scopes as $scope) {
            $accessToken->addScope($scope);
        }
        return $accessToken;
    }
}
