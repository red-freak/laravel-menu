<?php

namespace RedFreak\Menu\Facades;

use Illuminate\Support\Facades\Facade;
use RedFreak\Menu\Menu as MenuDto;
use RedFreak\Menu\MenuManager;

/**
 * @method static MenuDto add(string $menuName, array $menuData = [])
 * @method static bool hasMenu(string $menuName)
 * @method static array menus()
 *
 * @see MenuManager
 */
class Menu extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'red-freak::menu';
    }
}
