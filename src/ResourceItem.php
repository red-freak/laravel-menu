<?php

namespace RedFreak\Menu;

use Illuminate\Database\Eloquent\Model;

class ResourceItem extends Item
{
    protected Model $model;

    public function __construct(Model $model, Menu $parent)
    {
        $this->model = $model;

        $label = $model->getTable().'.index';

        parent::__construct(
            config('menus.default_translation_prefix', 'menu.label.') .$model->getTable().'.index',
            route($label),
            $parent
        );
    }

    public function render(int $currentLevel = 0): string
    {
        if ($this->renderOptions()->shouldSetLinkAtSubmenuAnchor()) {
            $html = str_pad('', $currentLevel*2, ' ') . '<a href="' . $this->link . '">' . $this->label() . '</a>'.PHP_EOL;
        } else {
            $html = str_pad('', $currentLevel*2, ' ') . $this->label() . PHP_EOL;
        }

        if (count(RenderOptions::modelRoutes()) === 1) return $html;

        $html .= str_pad('', $currentLevel*2, ' ') . '<ul class="' . implode(' ', RenderOptions::classes($this, $currentLevel++)) . '">' . PHP_EOL;
        foreach (RenderOptions::modelRoutes() as $modelRoute) {
            $route = $this->model->getTable().'.'.$modelRoute;
            $label = $this->getRouteLabel($route);

            $html .= str_pad('', $currentLevel*2, ' ') . '<li class="'.implode(' ', RenderOptions::classes(new Item('', '', $this), $currentLevel)).'">'.PHP_EOL;
            $html .= str_pad('', $currentLevel*2 + 2, ' ') . '<a href="' . route($route) . '">' . $label . '</a>'.PHP_EOL;
            $html .= str_pad('', $currentLevel*2, ' ') . '</li>'.PHP_EOL;
        }
        --$currentLevel;
        $html .= str_pad('', $currentLevel*2, ' ') . '</ul>' . PHP_EOL;

        return $html;
    }

    private function getRouteLabel(string $route): string
    {
        $label = config('menus.default_translation_prefix', 'menu.label.') . $route;

        return config('menus.use_labels_as_translation_keys', false) ? __($label) : $label;
    }
}
