<?php

namespace RedFreak\Menu;

use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;

class MenuManager
{
    use Macroable;

    /** @var Collection<Menu>  */
    protected Collection $menus;

    public function __construct()
    {
        $this->menus = new Collection();

        foreach(config('menus', []) as $menuName => $menuData) {
            $this->add($menuName, $menuData);
        }
    }

    public function add(string $menuName, array $menuData = []): Item
    {
        $menu = $this->registerMenu($menuName, $menuData);
        $this->registerMacro($menu);

        return $menu;
    }

    protected function registerMenu(string $menuName, $menuData = []): Menu
    {
        $menu = new Menu($menuName, $menuData);
        $this->menus->put($menuName, $menu);

        return $menu;
    }

    protected function registerMacro(Item $item): void
    {
        self::macro($item->label(), static function() use ($item) {
            return $item->render();
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
