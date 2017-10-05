<?php

namespace MichaelCooke\Guardian\Facades;

use Illuminate\Support\Facades\Facade;

class User extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'guardian.user';
    }
}