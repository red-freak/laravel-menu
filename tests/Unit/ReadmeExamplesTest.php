<?php

use Illuminate\Foundation\Auth\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;
use RedFreak\Menu\Facades\Menu;
use RedFreak\Menu\Item;
use RedFreak\Menu\MenuServiceProvider;
use RedFreak\Menu\RenderOptions;

const EXPECTED_RENDER_OUTPUT = '<ul class="menu menu-level-0">'.PHP_EOL
                               .'  <li class="menu-item menu-level-1">'.PHP_EOL
                               .'    <a href="http://localhost">Home</a>'.PHP_EOL
                               .'  </li>'.PHP_EOL
                               .'  <li class="menu-item menu-level-1">'.PHP_EOL
                               .'    <a href="http://localhost/about">About</a>'.PHP_EOL
                               .'  </li>'.PHP_EOL
                               .'  <li class="menu-item menu-level-1">'.PHP_EOL
                               .'    <a href="http://localhost/login">Login</a>'.PHP_EOL
                               .'  </li>'.PHP_EOL
                               .'</ul>'.PHP_EOL;

beforeEach(function() {
    app()->register(MenuServiceProvider::class);
    app()->translator->addLines([
        'menu.label.home' => 'Home',
        'menu.label.about' => 'About',
        'menu.label.login' => 'Login',
    ], 'en');
});

it('can fulfill the basic usage example', function () {
    Menu::add('home', [RenderOptions::KEY_USE_LABELS_AS_TRANSLATION_KEYS => true])
        ->add(new Item('menu.label.home', 'http://localhost'))
        ->add(new Item('menu.label.about', 'http://localhost/about'))
        ->add(new Item('menu.label.login', 'http://localhost/login'));

    expect(Menu::get('home')->render())->toBe(EXPECTED_RENDER_OUTPUT);
});

it('can fulfill the add items examples', function () {
    // Item class
    Menu::flush('home')->add('home', [RenderOptions::KEY_USE_LABELS_AS_TRANSLATION_KEYS => true])
        ->add(new Item('menu.label.home', 'http://localhost'))
        ->add(new Item('menu.label.about', 'http://localhost/about'))
        ->add(new Item('menu.label.login', 'http://localhost/login'));
    expect(Menu::get('home')->render())->toBe(EXPECTED_RENDER_OUTPUT);

    // key-value
    Menu::flush('home')->add('home', [RenderOptions::KEY_USE_LABELS_AS_TRANSLATION_KEYS => true])
        ->add([
            Menu::KEY_ITEM_LABEL => 'Home',
            Menu::KEY_ITEM_LINK => 'http://localhost',
        ])
        ->add([
            Menu::KEY_ITEM_LABEL => 'About',
            Menu::KEY_ITEM_LINK => 'http://localhost/about',
        ])
        ->add([
            Menu::KEY_ITEM_LABEL => 'Login',
            Menu::KEY_ITEM_LINK => 'http://localhost/login',
        ]);
    expect(Menu::get('home')->render())->toBe(EXPECTED_RENDER_OUTPUT);

    // route
    Route::group(['as' => 'home'], function() {
        Route::get('/', fn() => 'home');
    });
    Route::group(['as' => 'about'], function() {
        Route::get('/about', fn() => 'about');
    });
    Route::group(['as' => 'login'], function() {
        Route::get('/login', fn() => 'login');
    });
    Menu::flush('home')->add('home', [RenderOptions::KEY_USE_LABELS_AS_TRANSLATION_KEYS => true])
        ->add([Menu::KEY_ITEM_ROUTE => 'home'])
        ->add([Menu::KEY_ITEM_ROUTE => 'about'])
        ->add([Menu::KEY_ITEM_ROUTE => 'login']);
    expect(Menu::get('home')->render())->toBe(EXPECTED_RENDER_OUTPUT);
});

it('can fulfill the add by model example', function () {
    Route::resource('users', Controller::class);
    app()->translator->addLines([
        'menu.label.users.index' => 'Users',
        'menu.label.users.create' => 'create User',
    ], 'en');

    Menu::add('home', [RenderOptions::KEY_USE_LABELS_AS_TRANSLATION_KEYS => true])->add([
        Menu::KEY_ITEM_MODEL => User::class,
    ]);

    expect(Menu::home())->toBe('<ul class="menu menu-level-0">'.PHP_EOL
        .'  <li class="sub-menu menu-item menu-level-1">'.PHP_EOL
        .'    Users'.PHP_EOL
        .'    <ul class="sub-menu menu-item menu-level-2">'.PHP_EOL
        .'      <li class="menu-item menu-level-3">'.PHP_EOL
        .'        <a href="http://localhost/users">Users</a>'.PHP_EOL
        .'      </li>'.PHP_EOL
        .'      <li class="menu-item menu-level-3">'.PHP_EOL
        .'        <a href="http://localhost/users/create">create User</a>'.PHP_EOL
        .'      </li>'.PHP_EOL
        .'    </ul>'.PHP_EOL
        .'  </li>'.PHP_EOL
        .'</ul>'.PHP_EOL
    );
});
