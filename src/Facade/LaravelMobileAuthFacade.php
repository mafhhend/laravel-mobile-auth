<?php

namespace Facade;

use Illuminate\Support\Facades\Facade;

class LaravelMobileAuthFacade extends Facade
{

    public static function getFacadeAccessor()
    {
        return "LaravelMobileAuth";
    }
}
