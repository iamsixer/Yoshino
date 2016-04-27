<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Leancloud extends Facade {

    public static function getFacadeAccessor()
    {
        return 'LeancloudService';
    }

}