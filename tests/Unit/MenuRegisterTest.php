<?php

use RedFreak\Menu\MenuManager;

it('does not know an unregistered menu', function () {
    expect(MenuManager::hasMenu('unregistered-menu'))->toBeFalse();
});

it('can register a menu', function () {
    $manager = app(MenuManager::class);
    $manager->registerMenu('test', []);

    expect(MenuManager::hasMenu('unregistered-menu'))->toBeFalse();

    expect(MenuManager::hasMenu('test'))->toBeTrue();
    expect(MenuManager::menus())->toBe(['test']);
});
