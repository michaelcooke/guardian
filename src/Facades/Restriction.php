<?php

namespace MichaelCooke\Guardian\Facades;

use Illuminate\Support\Facades\Facade;

class Restriction extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'guardian.restriction';
    }
}