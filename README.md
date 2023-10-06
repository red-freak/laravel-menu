# Laravel Menu
Laravel Menu is a package to create menus for laravel applications. It's a package to also solve the problem for module based applications (see [red-freak/laravel-modules](https://github.com/red-freak/laravel-modules)).

[![Latest Version](https://img.shields.io/github/release/red-freak/laravel-menu.svg)](https://github.com/red-freak/laravel-menu/releases)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE.md)
[![run-tests](https://img.shields.io/github/actions/workflow/status/red-freak/laravel-menu/laravel.yml?label=tests)](https://github.com/red-freak/laravel-menu/actions)
[![Total Downloads](https://img.shields.io/packagist/dt/red-freak/laravel-menu.svg)](https://packagist.org/packages/spatie/browsershot)
[![Total Downloads](https://img.shields.io/packagist/dt/red-freak/laravel-menu.svg)](https://packagist.org/packages/spatie/browsershot)

![Supported PHP Version](https://img.shields.io/badge/8.0%2C%208.1%2C%208.2-555?logo=php)
![Supported Laravel Version](https://img.shields.io/badge/8.x%2C%209.x%2C%2010.x-555?logo=laravel)

## Installation

Simply install the package via composer:

```bash
composer require red-freak/laravel-menu
```

## How to use

### Basic usage
The base idea is to be able to create or add items via a `Menu`-Facade. It can be accessed from anywhere. So you are able to have different (e.g. module-bases) ServiceProviders to do so.
```php
Menu::add('home')
    ->add(new Item('Home', '/'))
    ->add(new Item('About', '/about'))
    ->add(new Item('Login', '/login'));
```

```html
<ul class="menu menu-level-0">
  <li class="menu-item menu-level-1">
    <a href="/">Home</a>
  </li>
  <li class="menu-item menu-level-1">
    <a href="/about">About</a>
  </li>
  <li class="menu-item menu-level-1">
    <a href="/login">Login</a>
  </li>
</ul>
```

### Add items to a menu
You can define the menus in the config file for later use in your project and can also add items to a menu via the `Menu`-Facade. And of course it can work with labels.
```php
// define the menu anywhere to use translation keys as labels (or do it by config)
Menu::add('home', [
    RenderOptions::KEY_USE_LABELS_AS_TRANSLATION_KEYS => true
]);

...

// have the corresponding translation keys via files (or in this case via the `Translator`-Facade)
app()->translator->addLines([
    'menu.label.home' => 'Home',
    'menu.label.about' => 'About',
    'menu.label.login' => 'Login',
], 'en');

...

// Now you can add items via the `Item` class ...
Menu::get('home')
    ->add(new Item('menu.label.home', 'http://localhost'))
    ->add(new Item('menu.label.about', 'http://localhost/about'))
    ->add(new Item('menu.label.login', 'http://localhost/login'));

// ... or add them via key-value ...
Menu::get('home')
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

// ... or add them via routes ...
// Route::get('/', fn() => 'home')->name('home');
// Route::get('/about', fn() => 'about')->name('about');
// Route::get('/login', fn() => 'login')->name('login');
Menu::get('home')
    ->add([Menu::KEY_ITEM_ROUTE => 'home'])
    ->add([Menu::KEY_ITEM_ROUTE => 'about'])
    ->add([Menu::KEY_ITEM_ROUTE => 'login']);
```
If you now call `Menu::home()` via the automatically create macro, you will get the following result:
```html
<ul class="menu menu-level-0">
  <li class="menu-item menu-level-1">
    <a href="http://localhost">Home</a>
  </li>
  <li class="menu-item menu-level-1">
    <a href="http://localhost/about">About</a>
  </li>
  <li class="menu-item menu-level-1">
    <a href="http://localhost/login">Login</a>
  </li>
</ul>
```

### Add a submenu for a Model
```php
// define the route and the translation keys
Route::resource('users', Controller::class);
app()->translator->addLines([
    'menu.label.users.index' => 'Users',
    'menu.label.users.create' => 'create User',
], 'en');
```
```php
// define the menu and add a submenu by a model
Menu::add('home', [RenderOptions::KEY_USE_LABELS_AS_TRANSLATION_KEYS => true])->add([
    Menu::KEY_ITEM_MODEL => User::class,
]);
```
If you now call `Menu::home()` via the automatically create macro, you will get the following result:
```html
<ul class="menu menu-level-0">
  <li class="sub-menu menu-item menu-level-1">
    Users
    <ul class="sub-menu menu-item menu-level-2">
      <li class="menu-item menu-level-3">
        <a href="http://localhost/users">Users</a>
      </li>
      <li class="menu-item menu-level-3">
        <a href="http://localhost/users/create">create User</a>
      </li>
    </ul>
  </li>
</ul>
```

## run tests
`vendor/bin/pest --coverage-html ./tests/reports/pest`

## special thanks
- [Spatie](https://github.com/spatie) for general inspiration on package development and their workflows
