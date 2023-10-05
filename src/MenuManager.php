<?php

namespace RedFreak\Menu;

use Illuminate\Support\Traits\Macroable;

class MenuManager
{
    use Macroable;

    public function __construct()
    {
        foreach(config('menus', []) as $menuName => $menuData) {
            $this->add($menuName, $menuData);
        }
    }

    public function add(string $menuName, array $menuData = []): void
    {
        self::macro($menuName, static function() use ($menuData) {
            return $menuData;
        });
    }

    public static function hasMenu(string $menuName): bool
    {
        return self::hasMacro($menuName);
    }

    public static function menus(): array
    {
        return array_keys(self::$macros);
    }
}
