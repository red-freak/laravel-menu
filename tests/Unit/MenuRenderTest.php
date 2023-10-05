<?php

use RedFreak\Menu\MenuManager;
use RedFreak\Menu\RenderOptions;

it('can render a menu without set config', function () {
    $menu = app(MenuManager::class)->add('testMenu');
    expect($menu->render())->toBe('<ul class="menu">'.PHP_EOL.'testMenu'.PHP_EOL.'</ul>'.PHP_EOL);
});

it('can append name and type information to the menu', function () {
    config()->set('menus.classes.add_type_to_root', true);

    $menu = app(MenuManager::class)->add('testMenu', [RenderOptions::KEY_STYLE => 'menu']);
    expect($menu->render())->toBe('<ul class="menu menu--menu">'.PHP_EOL.'testMenu'.PHP_EOL.'</ul>'.PHP_EOL);
    $menu = app(MenuManager::class)->add('testMenu');
    expect($menu->render())->toBe('<ul class="menu menu--list">'.PHP_EOL.'testMenu'.PHP_EOL.'</ul>'.PHP_EOL);

    config()->set('menus.classes.add_menu_as_class', true);
    expect($menu->render())->toBe('<ul class="menu menu--list menu--testMenu">'.PHP_EOL.'testMenu'.PHP_EOL.'</ul>'.PHP_EOL);
});

it('can render a menu with a label as translation key', function () {
    app()->translator->addLines(['*.testMenu' => 'testMenu_Translated'], 'en');
    $menu = app(MenuManager::class)->add('testMenu', [RenderOptions::KEY_USE_LABELS_AS_TRANSLATION_KEYS => true]);
    expect($menu->render())->toBe('<ul class="menu">'.PHP_EOL.'testMenu_Translated'.PHP_EOL.'</ul>'.PHP_EOL);
});
