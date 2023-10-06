<?php

namespace RedFreak\Menu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Route;
use Illuminate\Support\Collection;
use RedFreak\Menu\Facades\Menu as MenuFacade;

class Menu extends Item
{
    /** @var Collection<Item>  */
    protected Collection $items;

    protected RenderOptions $renderOptions;

    public function __construct(string $menuName, array $menuData = [], ?Menu $parent = null)
    {
        parent::__construct(data_get($menuData, 'label', $menuName), data_get($menuData, 'link'), $parent);

        $this->items = new Collection();
        $this->renderOptions = new RenderOptions($menuData);
    }

    public function hasChildren(): bool
    {
        return $this->items->isNotEmpty();
    }

    /**
     * Adds an item to the menu.
     *
     * @param  Item|array  $item  The item or an array of suitable values (@see MenuFacade).
     *
     * @return $this
     */
    public function add(Item|array $item): self
    {
        // logic
        if (is_a($item, Item::class)) {
            return $this->addItem($item);
        }
        if ($link = data_get($item, MenuFacade::KEY_ITEM_LINK)) {
            return $this->addItem(new Item(data_get($item, MenuFacade::KEY_ITEM_LABEL), $link, $this));
        }

        if ($route = data_get($item, MenuFacade::KEY_ITEM_ROUTE)) {
            return $this->addItemByRoute($route, $item);
        }

        if ($model = data_get($item, MenuFacade::KEY_ITEM_MODEL)) {
            return $this->addItemByModel($model, $item);
        }

        throw new \InvalidArgumentException('unknown options-set');
    }

    /**
     * Appends an Item to the Menu. (sets itself as parent)
     *
     * @param  Item  $item
     *
     * @return $this
     */
    public function addItem(Item $item): self
    {
        $item->setParent($this);

        $this->items->put($item->label(), $item);

        return $this;
    }

    /**
     * Creates a submenu by a model.
     *
     * @param  class-string|Model  $model
     *
     * @return $this
     */
    public function addItemByModel(string|Model $model): self
    {
        if (is_string($model)) {
            $model = new $model();
        }

        $this->items->put($model::class.'::'.$model->getKey(), new ResourceItem($model, $this));

        return $this;
    }

    /**
     * Appends an item to the Menu a Route o route name.
     *
     * @param  string|Route  $routeToUse
     * @param  array  $options
     *
     * @return $this
     */
    public function addItemByRoute(string|Route $routeToUse, array $options = []): self
    {
        $route = is_string($routeToUse) ? app('router')->getRoutes()->getByName($routeToUse) : $routeToUse;

        $label = data_get($options, MenuFacade::KEY_ITEM_LABEL) ?? config('menus.default_translation_prefix', 'menu.label.').$route->getName();

        $this->items->put($label, new Item($label, route($route->getName()), $this));

        return $this;
    }

    public function hasItem(string $label): bool
    {
        return $this->items->has($label);
    }

    public function items(): Collection
    {
        return $this->items;
    }

    public function type(): string
    {
        return $this->renderOptions->style();
    }

    public function render(int $currentLevel = 0): string
    {
        $html = str_pad('', $currentLevel*2, ' ') . '<ul class="' . implode(' ', RenderOptions::classes($this, $currentLevel++)) . '">' . PHP_EOL;

        foreach ($this->items as $item) {
            $html .= str_pad('', $currentLevel*2, ' ') . '<li class="'.implode(' ', RenderOptions::classes($item, $currentLevel)).'">'.PHP_EOL;
            $html .= $item->render($currentLevel+1);
            $html .= str_pad('', $currentLevel*2, ' ') . '</li>'.PHP_EOL;
        }
        --$currentLevel;

        $html .= str_pad('', $currentLevel*2, ' ') . '</ul>' . PHP_EOL;

        return $html;
    }

    public function renderOptions(): RenderOptions
    {
        return $this->renderOptions;
    }
}
