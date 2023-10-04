<?php

namespace RedFreak\Menu;

use Illuminate\Support\Traits\Macroable;

class MenuManager
{
    use Macroable;

    public function __construct()
    {
        foreach(config('menus') as $menuName => $menuData) {
            $this->registerMenu($menuName, $menuData);
        }
    }

    public function registerMenu(string $menuName, array $menuData): void
    {
        self::macro($menuName, static function() use ($menuData) {
            return $menuData;
        });
    }
}
