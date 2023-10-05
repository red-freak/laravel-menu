<?php

use RedFreak\Menu\MenuManager;

it('does not know an unregistered menu', function () {
    expect(MenuManager::hasMenu('unregistered-menu'))->toBeFalse();
});

it('can register a menu', function () {
    $manager = app(MenuManager::class);
    $manager->add('test', []);
    $manager->add('test2');

    expect(MenuManager::hasMenu('unregistered-menu'))->toBeFalse();

    expect(MenuManager::hasMenu('test'))->toBeTrue();
    expect(MenuManager::hasMenu('test2'))->toBeTrue();
    expect(MenuManager::menus())->toBe(['test', 'test2']);
});

it('throws an exception when trying to register a menu with an invalid style', function () {
    app(MenuManager::class)->add('test', ['style' => 'invalid']);
})->throws(InvalidArgumentException::class, 'Invalid menu type: invalid');
