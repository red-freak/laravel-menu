<?php

namespace RedFreak\Menu;

class Item
{
    protected ?Menu $parent = null;
    protected string $label;
    protected ?string $link;

    public function __construct(string $label, ?string $link = null, ?Menu $parent = null)
    {
        $this->parent = $parent;
        $this->label = $label;
        $this->link = $link;
    }

    public function label(): string
    {
        if ($this->renderOptions()->useLabelsAsTranslationKeys()) {
            return __($this->label);
        }

        return $this->label;
    }

    public function render(): string
    {
        return $this->label;
    }

    public function hasParent(): bool
    {
        return isset($this->parent);
    }

    public function isRootMenu(): bool
    {
        return ! $this->hasParent();
    }

    public function renderOptions(): RenderOptions
    {
        return $this->parent->renderOptions();
    }
}
