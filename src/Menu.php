<?php

namespace RedFreak\Menu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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

    public function add(Item $item): self
    {
        $this->items->put($item->label(), $item);

        return $this;
    }

    public function addFromModel(Model $model): self
    {
        $this->items->put($model->getKey(), new ResourceItem($model));

        return $this;
    }

    public function type(): string
    {
        return $this->renderOptions->style();
    }

    public function render(): string
    {
        $html = '<ul class="' . implode(' ', RenderOptions::classes($this)) . '">' . PHP_EOL;
        $html .= $this->label() . PHP_EOL;

        foreach ($this->items as $item) {
            $html .= '<li class="' . implode(' ', RenderOptions::classes($item)) . '">' . PHP_EOL
                     . $item->render()
                     . '</li>' . PHP_EOL;
        }

        $html .= '</ul>' . PHP_EOL;

        return $html;
    }

    public function renderOptions(): RenderOptions
    {
        if ($this->isRootMenu()) {
            return $this->renderOptions;
        }

        return $this->parent->renderOptions();
    }
}
