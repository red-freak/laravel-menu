<?php

namespace RedFreak\Menu;

use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerFacades();
    }

    protected function registerFacades(): void
    {
        $this->app->singleton(MenuManager::class, function() {
            return new MenuManager();
        });

        $this->app->alias(MenuManager::class, 'red-freak::menu');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->registerCommands();
        }
    }

    protected function registerCommands(): void
    {

    }
}
