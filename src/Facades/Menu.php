<?php

namespace RedFreak\Menu\Facades;

use Illuminate\Support\Facades\Facade;
use RedFreak\Menu\Menu as MenuDto;
use RedFreak\Menu\MenuManager;

/**
 * @method static MenuDto add(string $menuName, array $menuData = [])
 * @method static self flush(string $menuName)
 * @method static MenuDto get(string $menuName)
 * @method static bool hasMenu(string $menuName)
 * @method static array menus()
 *
 * @see MenuManager
 */
class Menu extends Facade
{
    public const KEY_ITEM_LABEL = 'label';
    public const KEY_ITEM_LINK = 'link';
    public const KEY_ITEM_ROUTE = 'route';
    public const KEY_ITEM_MODEL = 'model';

    protected static function getFacadeAccessor(): string
    {
        return 'red-freak::menu';
    }
}
