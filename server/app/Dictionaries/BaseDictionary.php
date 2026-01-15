<?php

namespace App\Dictionaries;

interface BaseDictionary
{
    public static function type();
    public static function get($index) ;
}
