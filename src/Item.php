<?php

namespace RedFreak\Menu;

class Item
{
    protected Menu|ResourceItem|null $parent = null;
    protected string $label;
    protected ?string $link;

    public function __construct(string $label, ?string $link = null, Menu|ResourceItem|null $parent = null)
    {
        if ($parent) {
            $this->setParent($parent);
        }
        $this->label = $label;
        $this->setLink($link);
    }

    public function setParent(Menu|ResourceItem $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function setLink(?string $link = null): self
    {
        $this->link = $link;

        return $this;
    }

    public function label(): string
    {
        if ($this->renderOptions()->useLabelsAsTranslationKeys()) {
            return __($this->label);
        }

        return $this->label;
    }

    public function render(int $currentLevel = 0): string
    {
        return str_pad('', $currentLevel*2, ' ') . '<a href="' . $this->link . '">' . $this->label() . '</a>'.PHP_EOL;
    }

    public function hasParent(): bool
    {
        return isset($this->parent);
    }

    public function hasChildren(): bool
    {
        return false;
    }

    public function isRootMenu(): bool
    {
        return !$this->hasParent();
    }

    public function renderOptions(): RenderOptions
    {
        return $this->parent->renderOptions();
    }
}
