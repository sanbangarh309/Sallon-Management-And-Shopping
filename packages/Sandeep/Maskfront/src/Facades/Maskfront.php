<?php

namespace Sandeep\Maskfront\Facades;

use Illuminate\Support\Facades\Facade;

class Maskfront extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'maskfront';
    }
}
