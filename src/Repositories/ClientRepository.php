<?php

namespace App\Repositories;

use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use App\Entities\ClientEntity;
use PDO;

class ClientRepository implements ClientRepositoryInterface
{
    private PDO $db;
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getClientEntity($clientIdentifier): ?ClientEntityInterface
    {
        $stmt = $this->db->prepare("SELECT * FROM oauth_clients WHERE id = ?");
        $stmt->execute([$clientIdentifier]);
        $client = $stmt->fetch();

        if ($client) {
            $clientEntity = new ClientEntity();
            $clientEntity->setIdentifier($client['id']);
            return $clientEntity;
        }

        return null;
    }

    public function validateClient($clientIdentifier, $clientSecret, $grantType): bool
    {
        $stmt = $this->db->prepare("SELECT * FROM oauth_clients WHERE id = ? AND secret = ?");
        $stmt->execute([$clientIdentifier, $clientSecret]);
        return $stmt->fetch() !== false;
    }
}
