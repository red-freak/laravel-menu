<?php

use RedFreak\Menu\Item;
use RedFreak\Menu\MenuManager;
use RedFreak\Menu\RenderOptions;

it('can render a menu without set config', function () {
    $menu = app(MenuManager::class)->add('testMenu');
    expect($menu->render())->toBe('<ul class="menu menu-level-0">'.PHP_EOL.'</ul>'.PHP_EOL);
});

it('can append name and type information to the menu', function () {
    config()->set('menus.classes.add_type_to_root', true);

    $menu = app(MenuManager::class)->add('testMenu', [RenderOptions::KEY_STYLE => 'menu']);
    expect($menu->render())->toBe('<ul class="menu menu--menu menu-level-0">'.PHP_EOL.'</ul>'.PHP_EOL);
    $menu = app(MenuManager::class)->add('testMenu');
    expect($menu->render())->toBe('<ul class="menu menu--list menu-level-0">'.PHP_EOL.'</ul>'.PHP_EOL);

    config()->set('menus.classes.add_menu_as_class', true);
    expect($menu->render())->toBe('<ul class="menu menu--list menu--testMenu menu-level-0">'.PHP_EOL.'</ul>'.PHP_EOL);
});

it('registers a render-macro for a menu', function() {
    app(MenuManager::class)->add('testMenu');
    expect(MenuManager::testMenu())->toBe('<ul class="menu menu-level-0">'.PHP_EOL.'</ul>'.PHP_EOL);
});

it('can render a menu with a label as translation key', function () {
    app()->translator->addLines(['*.testItem' => 'testItem_Translated'], 'en');
    $menu = app(MenuManager::class)->add('testMenu', [RenderOptions::KEY_USE_LABELS_AS_TRANSLATION_KEYS => true])->add(new Item('testItem', 'link'));
    expect($menu->render())->toBe(
        '<ul class="menu menu-level-0">'.PHP_EOL
        .'  <li class="menu-item menu-level-1">'.PHP_EOL
        .'    <a href="link">testItem_Translated</a>'.PHP_EOL
        .'  </li>'.PHP_EOL
        .'</ul>'.PHP_EOL
    );
});

it('can add a has-children class', function () {
    config()->set('menus.classes.add_has_children', true);
    $menu = app(MenuManager::class)->add('testMenu', [RenderOptions::KEY_USE_LABELS_AS_TRANSLATION_KEYS => true])->add(new Item('testItem', 'link'));
    expect($menu->render())->toBe(
        '<ul class="menu has-children menu-level-0">'.PHP_EOL
        .'  <li class="menu-item menu-level-1">'.PHP_EOL
        .'    <a href="link">testItem</a>'.PHP_EOL
        .'  </li>'.PHP_EOL
        .'</ul>'.PHP_EOL
    );
});
