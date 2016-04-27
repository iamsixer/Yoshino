<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Lecloud extends Facade {

    public static function getFacadeAccessor()
    {
        return 'LecloudService';
    }

}