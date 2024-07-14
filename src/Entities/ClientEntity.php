<?php

namespace App\Entities;

use League\OAuth2\Server\Entities\ClientEntityInterface;

class ClientEntity implements ClientEntityInterface
{
    private string $identifier;

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getName(): string
    {
        return 'Client Name';
    }

    public function getRedirectUri(): ?string
    {
        return null;
    }

    public function isConfidential(): bool
    {
        return true;
    }
}
