<?php

namespace App\Entities;

use League\OAuth2\Server\Entities\ScopeEntityInterface;

class ScopeEntity implements ScopeEntityInterface
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

    #[\Override] public function jsonSerialize(): mixed
    {
        // TODO: Implement jsonSerialize() method.
    }
}
