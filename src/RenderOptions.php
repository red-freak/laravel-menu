<?php

namespace RedFreak\Menu;

class RenderOptions
{
    public const KEY_USE_LABELS_AS_TRANSLATION_KEYS = 'use_labels_as_translation_keys';
    public const KEY_STYLE = 'style';
    public const KEY_LABEL = 'label';
    public const KEY_LINK = 'link';
    public const KEY_LINK_SUBMENU_ANCHOR = 'link_submenu_anchor';

    private const ALLOWED_STYLES = [
        'list',
        'menu',
        'div',
    ];

    protected string $style;
    protected bool $useLabelsAsTranslationKeys;
    protected bool $linkSubmenuAnchor;


    public function __construct(array $menuData = [])
    {
        $this->setStyle(data_get($menuData, self::KEY_STYLE, 'list'));
        $this->useLabelsAsTranslationKeys = data_get($menuData, self::KEY_USE_LABELS_AS_TRANSLATION_KEYS, false);
        $this->linkSubmenuAnchor = data_get($menuData, self::KEY_LINK_SUBMENU_ANCHOR, false);

    }

    /* SETTER */

    protected function setStyle(string $style): void
    {
        if (!in_array($style, self::ALLOWED_STYLES, true)) {
            throw new \InvalidArgumentException('Invalid menu type: ' . $style);
        }

        $this->style = $style;
    }

    /* GETTER */

    public function style(): string
    {
        return $this->style;
    }

    public function useLabelsAsTranslationKeys(): bool
    {
        return $this->useLabelsAsTranslationKeys;
    }

    public function shouldSetLinkAtSubmenuAnchor(): bool
    {
        return $this->linkSubmenuAnchor;
    }

    /* other methods */

    public static function classes(Item $item, int $currentLevel): array
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
        if (($item instanceof Menu || $item instanceof ResourceItem) && $item->hasParent()) {
            $classes[] = config('menus.classes.root', 'sub-menu');

            if (config('menus.classes.add_has_children', false) && $item->hasChildren()) {
                $classes[] = 'has-children';
            }
        }
        if ($item->hasParent() && !$item->hasChildren()) {
            $classes[] = config('menus.classes.item', 'menu-item');
        }

        // Add level class as last class for better readability
        $classes[] = 'menu-level-' . $currentLevel;

        return $classes;
    }

    public static function modelRoutes(): array
    {
        return array_merge(['index'], config('menus.model_routes', ['create']));
    }

    public function toMenuDataArray(): array
    {
        return [
            self::KEY_USE_LABELS_AS_TRANSLATION_KEYS => $this->useLabelsAsTranslationKeys(),
            self::KEY_STYLE => $this->style(),
            self::KEY_LINK_SUBMENU_ANCHOR => $this->linkSubmenuAnchor,
        ];
    }
}
