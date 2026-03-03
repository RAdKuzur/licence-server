<?php

namespace App\Repositories;

use App\Dictionaries\LicenceDictionary;
use App\Models\Licence;
use Illuminate\Support\Facades\DB;

class LicenceRepository
{
    public function all()
    {
        return Licence::all();
    }
    public function hasLicence($appKey, $licenceKey){
        return DB::table('licences')->where('app_key', $appKey)->where('licence_key', $licenceKey)->exists();
    }
    public function create($data) :int
    {
        return DB::table('licences')->insertGetId($data);
    }
    public function isLicenceValid($appKey, $licenceKey){
        return DB::table('licences')
            ->where([
                ['app_key', '=', $appKey],
                ['licence_key', '=', $licenceKey],
                ['expires_at', '>', now()],
                ['is_revoked', '=', LicenceDictionary::ACTIVE]
            ])
            ->exists();
    }
    public function getLicence($appKey, $licenceKey){
        return DB::table('licences')->where('app_key', $appKey)->where('licence_key', $licenceKey)->first();
    }

    public function revoke($id){
        return DB::table('licences')->where(['id' => $id])->update([
            'is_revoked' => LicenceDictionary::REVOKED
        ]);
    }
}
