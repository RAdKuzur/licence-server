<?php

namespace App\Services;

use App\DTO\LicenceDTO;
use App\Repositories\LicenceRepository;

class LicenceService
{
    public LicenceRepository $licenceRepository;
    public function __construct(
        LicenceRepository $licenceRepository
    )
    {
        $this->licenceRepository = $licenceRepository;
    }
    public function all() : array
    {
        $data = [];
        $licences = $this->licenceRepository->all();
        foreach ($licences as $licence) {
            $data[] = new LicenceDTO(
                id: $licence->id,
                appKey: $licence->app_key,
                licenceKey: $licence->licence_key,
                expiresAt: $licence->expires_at,
                isRevoked: $licence->is_revoked,
            );
        }
        return $data;
    }
    public function create(LicenceDTO $licenceDTO) : void
    {
        $this->licenceRepository->create($licenceDTO->toArray());
    }
    public function licenceCheck($appKey, $licenceKey) : array
    {
        if ($this->licenceRepository->hasLicence($appKey, $licenceKey)) {
            if($this->licenceRepository->isLicenceValid($appKey, $licenceKey)){
                return [
                    'code' => 200,
                    'message' => 'Licence valid',
                    'data' => $this->licenceRepository->getLicence($appKey, $licenceKey)
                ];
            };
            return [
                'code' => 403,
                'message' => 'Licence is not valid',
                'data' => $this->licenceRepository->getLicence($appKey, $licenceKey)
            ];
        }
        else {
            return [
                'code' => 404,
                'message' => 'Licence not found',
                'data' => null
            ];
        }
    }
    public function revoke($id)
    {
        $this->licenceRepository->revoke($id);
    }
}
