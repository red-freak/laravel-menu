<?php

namespace RedFreak\Menu\Facades;

use Illuminate\Support\Facades\Facade;

class Menu extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'red-freak::menu';
    }
}
