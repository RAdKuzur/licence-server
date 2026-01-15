<?php

namespace App\Services;

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

    public function licenceCheck($appKey, $licenceKey)
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
}
