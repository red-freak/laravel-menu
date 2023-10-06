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

    public function add(string $menuName, array $menuData = []): Menu
    {
        $menu = $this->registerMenu($menuName, $menuData);
        $this->registerMacro($menu);

        return $menu;
    }

    /**
     * Forget a menu (mainly for testing purposes).
     *
     * @param  string  $menuName
     *
     * @return $this
     */
    public function flush(string $menuName): self
    {
        $this->menus->forget($menuName);

        return $this;
    }

    public function get(string $menuName): Menu
    {
        return $this->menus->get($menuName);
    }

    protected function registerMenu(string $menuName, $menuData = []): Menu
    {
        if ($this->menus->has($menuName)) {
            return $this->get($menuName);
        }

        $menu = new Menu($menuName, $menuData);
        $this->menus->put($menuName, $menu);

        return $menu;
    }

    protected function registerMacro(Item $item): void
    {
        self::macro($item->label(), function() use ($item) {
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
