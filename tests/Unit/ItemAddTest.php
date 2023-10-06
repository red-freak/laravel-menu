<?php

use Illuminate\Support\Facades\Route;
use RedFreak\Menu\Facades\Menu;
use RedFreak\Menu\Item;
use RedFreak\Menu\MenuServiceProvider;
use RedFreak\Menu\Tests\Controllers\TestController;
use RedFreak\Menu\Tests\Models\TestModel;

beforeEach(function() {
    app()->register(MenuServiceProvider::class);
    Menu::add('test', []);
});

it('throws an exception if the add method is called with an invalid value', function () {
    Menu::get('test')->add(['foo' => 'bar']);
})->throws(InvalidArgumentException::class);

it('can add an item-object to a menu', function () {
    Menu::get('test')->add(new Item('test-item', 'test-link'));

    expect(Menu::get('test')->items())->toHaveCount(1);
    expect(Menu::get('test')->items())->toHaveKey('test-item');
    expect(Menu::get('test')->items()['test-item']->label())->toBe('test-item');
    expect(Menu::get('test')->hasItem('test-item'))->toBeTrue();
});

it('can add an item by an array to a menu', function () {
    Menu::get('test')->add([
        Menu::KEY_ITEM_LABEL => 'test-item',
        Menu::KEY_ITEM_LINK => 'test-link',
    ]);

    expect(Menu::get('test')->items())->toHaveCount(1);
    expect(Menu::get('test')->items())->toHaveKey('test-item');
    expect(Menu::get('test')->items()['test-item']->label())->toBe('test-item');
    expect(Menu::get('test')->hasItem('test-item'))->toBeTrue();
});

it('can add an item by a route to an menu', function () {
    $route = Route::get('test-route', fn() => 'test')->name('test-route');

    Menu::get('test')->add([Menu::KEY_ITEM_ROUTE => $route]);

    $expectedLabel = config('menus.default_translation_prefix', 'menu.label.') . 'test-route';
    expect(Menu::get('test')->items())->toHaveCount(1);
    expect(Menu::get('test')->items())->toHaveKey($expectedLabel);
    expect(Menu::get('test')->items()[$expectedLabel]->label())->toBe($expectedLabel);
    expect(Menu::get('test')->hasItem($expectedLabel))->toBeTrue();
});

it('can add an item by a route name to an menu', function () {
    // we have to do it this way to set the name in the test context correctly
    Route::group(['as' => 'test-route'], function() {
        Route::get('test-route', fn() => 'test');
    });

    Menu::get('test')->add([Menu::KEY_ITEM_ROUTE => 'test-route']);

    $expectedLabel = config('menus.default_translation_prefix', 'menu.label.') . 'test-route';
    expect(Menu::get('test')->items())->toHaveCount(1);
    expect(Menu::get('test')->items())->toHaveKey($expectedLabel);
    expect(Menu::get('test')->items()[$expectedLabel]->label())->toBe($expectedLabel);
    expect(Menu::get('test')->hasItem($expectedLabel))->toBeTrue();
});

it('can add an item by a model to an menu', function () {
    $model = new TestModel();
    Route::resource($model->getTable(), TestController::class);

    Menu::get('test')->add([Menu::KEY_ITEM_MODEL => $model]);

    $expectedLabel = $model::class . '::' . $model->getKey();
    expect(Menu::get('test')->items())->toHaveCount(1);
    expect(Menu::get('test')->items())->toHaveKey($expectedLabel);
    expect(Menu::get('test')->items()[$expectedLabel]->label())->toBe(config('menus.default_translation_prefix', 'menu.label.') . $model->getTable() . '.index');
    expect(Menu::get('test')->hasItem($expectedLabel))->toBeTrue();
});
