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
        $item->setParent($this);
        $this->items->put($item->label(), $item);

        return $this;
    }

    /**
     * Creates navigation items by a model.
     * @param  class-string|Model  $model
     *
     * @return $this
     */
    public function addFromModel(string|Model $model): self
    {
        if (is_string($model)) {
            $model = new $model();
        }

        $this->items->put($model::class.'::'.$model->getKey(), new ResourceItem($model, $this));

        return $this;
    }

    public function type(): string
    {
        return $this->renderOptions->style();
    }

    public function render(int $currentLevel = 0): string
    {
        $html = str_pad('', $currentLevel*2, ' ') . '<ul class="' . implode(' ', RenderOptions::classes($this, $currentLevel++)) . '">' . PHP_EOL;

//        dd($this->items->toArray());

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
        if ($this->isRootMenu()) {
            return $this->renderOptions;
        }

        return $this->parent->renderOptions();
    }
}
