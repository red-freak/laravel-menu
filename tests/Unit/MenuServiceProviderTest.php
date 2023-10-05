<?php

namespace RedFreak\Menu\Tests\Unit;

use RedFreak\Menu\Facades\Menu;
use RedFreak\Menu\MenuManager;
use RedFreak\Menu\MenuServiceProvider;

it('can load the menu service provider', function () {
    app()->register(MenuServiceProvider::class);
    expect(app()->providerIsLoaded(MenuServiceProvider::class))->toBeTrue();
});

it('can resolve the facade', function () {
    app()->register(MenuServiceProvider::class);
    expect(app()->bound('red-freak::menu'))->toBeTrue();
    expect(Menu::getFacadeRoot()::class)->toBe(MenuManager::class);
});
