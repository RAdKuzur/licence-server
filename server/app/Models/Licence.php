<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** @property int $id
 * @property string $app_key
 * @property string $licence_key
 * @property $expires_at
 * @property $is_revoked
*/
class Licence extends Model
{
    protected $table = 'licences';
    protected $fillable = [
        'app_key',
        'licence_key',
        'expires_at',
        'is_revoked'
    ];
}
