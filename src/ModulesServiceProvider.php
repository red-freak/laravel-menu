<?php

namespace RedFreak\Menu;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerFacades();
    }

    protected function registerFacades(): void
    {

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
