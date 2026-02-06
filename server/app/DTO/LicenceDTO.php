<?php

namespace App\DTO;

class LicenceDTO
{
    public ?int $id;
    public ?string $appKey;
    public ?string $licenceKey;
    public $expiresAt;
    public ?int $isRevoked;

    public function __construct(
        ?int $id = null,
        ?string $appKey = null,
        ?string $licenceKey = null,
        $expiresAt = null,
        ?int $isRevoked = null,
    )
    {
        $this->id = $id;
        $this->appKey = $appKey;
        $this->licenceKey = $licenceKey;
        $this->expiresAt = $expiresAt;
        $this->isRevoked = $isRevoked;
    }

    public function toArray() : array
    {
        return [
            'app_key' => $this->appKey,
            'licence_key' => $this->licenceKey,
            'expires_at' => $this->expiresAt,
            'is_revoked' => $this->isRevoked,
        ];
    }
}
