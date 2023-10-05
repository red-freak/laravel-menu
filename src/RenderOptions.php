<?php

namespace RedFreak\Menu;

class RenderOptions
{
    public const KEY_USE_LABELS_AS_TRANSLATION_KEYS = 'use_labels_as_translation_keys';
    public const KEY_STYLE = 'style';

    private const ALLOWED_STYLES = [
        'list',
        'menu',
        'div',
    ];

    protected bool $useLabelsAsTranslationKeys;
    protected string $style;

    public function __construct(array $menuData = [])
    {
        $this->useLabelsAsTranslationKeys = data_get($menuData, self::KEY_USE_LABELS_AS_TRANSLATION_KEYS, false);
        $this->setStyle(data_get($menuData, self::KEY_STYLE, 'list'));
    }
    protected function setStyle(string $style): void
    {
        if (!in_array($style, self::ALLOWED_STYLES, true)) {
            throw new \InvalidArgumentException('Invalid menu type: ' . $style);
        }

        $this->style = $style;
    }

    public function useLabelsAsTranslationKeys(): bool
    {
        return $this->useLabelsAsTranslationKeys;
    }

    public function style(): string
    {
        return $this->style;
    }

    public static function classes(Item $item): array
    {
        $classes = [];

        if ($item->isRootMenu()) {
            $classes[] = config('menus.classes.root', 'menu');

            if (config('menus.classes.add_type_to_root', false)) {
                $classes[] = config('menus.classes.root', 'menu') . '--' . $item->type();
            }

            if (config('menus.classes.add_menu_as_class', false)) {
                $classes[] = config('menus.classes.root', 'menu') . '--' . $item->label();
            }
        }
        if ($item instanceof Menu && $item->hasParent()) {
            $classes[] = config('menus.classes.root', 'sub-menu');

            if (config('menus.classes.add_has_children', false) && $item->hasChildren()) {
                $classes[] = 'has-children';
            }
        }
        if (!$item->hasParent() && !$item->isRootMenu()) {
            $classes[] = config('menus.classes.item', 'menu-item');
        }

        return $classes;
    }
}
