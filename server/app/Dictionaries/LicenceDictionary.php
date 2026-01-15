<?php

namespace App\Dictionaries;

class LicenceDictionary implements BaseDictionary
{
    public const ACTIVE = 1;
    public const REVOKED = 2;

    public static function type(){
        return [
            self::ACTIVE => 'Лицензия отозвана',
            self::REVOKED => 'Лицензия отозвана'
        ];
    }
    public static function get($index){
        return self::type()[$index];
    }
    public static function index($index){
        return array_search($index, self::type());
    }
}
