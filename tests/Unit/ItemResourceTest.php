<?php

use Illuminate\Support\Facades\Route;
use RedFreak\Menu\Facades\Menu;
use RedFreak\Menu\MenuServiceProvider;
use RedFreak\Menu\Tests\Controllers\TestController;
use RedFreak\Menu\Tests\Models\TestModel;
use RedFreak\Menu\Tests\Models\TestModel2;
use RedFreak\Menu\RenderOptions;

beforeEach(function() {
    app()->register(MenuServiceProvider::class);
});

it('can attach a model based items', function () {
    $model = new TestModel();
    Route::resource($model->getTable(), TestController::class);

    expect(Menu::add('test', [
        RenderOptions::KEY_LINK_SUBMENU_ANCHOR => true, // whether to set the link at the submenu anchor or not
    ])->addItemByModel(TestModel::class)->render())->toBe(
        '<ul class="menu menu-level-0">'.PHP_EOL
        . '  <li class="sub-menu menu-item menu-level-1">'.PHP_EOL
        . '    <a href="http://localhost/test_models">menu.label.test_models.index</a>'.PHP_EOL
        . '    <ul class="sub-menu menu-item menu-level-2">'.PHP_EOL
        . '      <li class="menu-item menu-level-3">'.PHP_EOL
        . '        <a href="http://localhost/test_models">menu.label.test_models.index</a>'.PHP_EOL
        . '      </li>'.PHP_EOL
        . '      <li class="menu-item menu-level-3">'.PHP_EOL
        . '        <a href="http://localhost/test_models/create">menu.label.test_models.create</a>'.PHP_EOL
        . '      </li>'.PHP_EOL
        . '    </ul>'.PHP_EOL
        . '  </li>'.PHP_EOL
        . '</ul>'.PHP_EOL
    );

    expect(Menu::add('test2', [
        RenderOptions::KEY_LINK_SUBMENU_ANCHOR => false, // whether to set the link at the submenu anchor or not
    ])->addItemByModel($model)->render())->toBe(
        '<ul class="menu menu-level-0">'.PHP_EOL
        . '  <li class="sub-menu menu-item menu-level-1">'.PHP_EOL
        . '    menu.label.test_models.index'.PHP_EOL
        . '    <ul class="sub-menu menu-item menu-level-2">'.PHP_EOL
        . '      <li class="menu-item menu-level-3">'.PHP_EOL
        . '        <a href="http://localhost/test_models">menu.label.test_models.index</a>'.PHP_EOL
        . '      </li>'.PHP_EOL
        . '      <li class="menu-item menu-level-3">'.PHP_EOL
        . '        <a href="http://localhost/test_models/create">menu.label.test_models.create</a>'.PHP_EOL
        . '      </li>'.PHP_EOL
        . '    </ul>'.PHP_EOL
        . '  </li>'.PHP_EOL
        . '</ul>'.PHP_EOL
    );
});

it('overrides a before added model', function() {
    $model = new TestModel();
    Route::resource($model->getTable(), TestController::class);

    Menu::add('test', [
        RenderOptions::KEY_LINK_SUBMENU_ANCHOR => false, // whether to set the link at the submenu anchor or not
    ])->addItemByModel($model)->addItemByModel($model);

    expect(Menu::add('test')->render())->toBe(
        '<ul class="menu menu-level-0">'.PHP_EOL
        . '  <li class="sub-menu menu-item menu-level-1">'.PHP_EOL
        . '    menu.label.test_models.index'.PHP_EOL
        . '    <ul class="sub-menu menu-item menu-level-2">'.PHP_EOL
        . '      <li class="menu-item menu-level-3">'.PHP_EOL
        . '        <a href="http://localhost/test_models">menu.label.test_models.index</a>'.PHP_EOL
        . '      </li>'.PHP_EOL
        . '      <li class="menu-item menu-level-3">'.PHP_EOL
        . '        <a href="http://localhost/test_models/create">menu.label.test_models.create</a>'.PHP_EOL
        . '      </li>'.PHP_EOL
        . '    </ul>'.PHP_EOL
        . '  </li>'.PHP_EOL
        . '</ul>'.PHP_EOL
    );
});

it('can add more than one model', function() {
    $model = new TestModel();
    Route::resource($model->getTable(), TestController::class);
    $model2 = new TestModel2();
    Route::resource($model2->getTable(), TestController::class);

    Menu::add('test', [
        RenderOptions::KEY_LINK_SUBMENU_ANCHOR => false, // whether to set the link at the submenu anchor or not
    ])->addItemByModel($model)->addItemByModel($model2);

    expect(Menu::add('test')->render())->toBe(
        '<ul class="menu menu-level-0">'.PHP_EOL
        . '  <li class="sub-menu menu-item menu-level-1">'.PHP_EOL
        . '    menu.label.test_models.index'.PHP_EOL
        . '    <ul class="sub-menu menu-item menu-level-2">'.PHP_EOL
        . '      <li class="menu-item menu-level-3">'.PHP_EOL
        . '        <a href="http://localhost/test_models">menu.label.test_models.index</a>'.PHP_EOL
        . '      </li>'.PHP_EOL
        . '      <li class="menu-item menu-level-3">'.PHP_EOL
        . '        <a href="http://localhost/test_models/create">menu.label.test_models.create</a>'.PHP_EOL
        . '      </li>'.PHP_EOL
        . '    </ul>'.PHP_EOL
        . '  </li>'.PHP_EOL
        . '  <li class="sub-menu menu-item menu-level-1">'.PHP_EOL
        . '    menu.label.test_model2s.index'.PHP_EOL
        . '    <ul class="sub-menu menu-item menu-level-2">'.PHP_EOL
        . '      <li class="menu-item menu-level-3">'.PHP_EOL
        . '        <a href="http://localhost/test_model2s">menu.label.test_model2s.index</a>'.PHP_EOL
        . '      </li>'.PHP_EOL
        . '      <li class="menu-item menu-level-3">'.PHP_EOL
        . '        <a href="http://localhost/test_model2s/create">menu.label.test_model2s.create</a>'.PHP_EOL
        . '      </li>'.PHP_EOL
        . '    </ul>'.PHP_EOL
        . '  </li>'.PHP_EOL
        . '</ul>'.PHP_EOL
    );
});
