<?php

namespace RedFreak\Modules\Tests;

use Orchestra\Testbench\Concerns\CreatesApplication;
use Orchestra\Testbench\PHPUnit\TestCase as BaseTestCase;
use RedFreak\Modules\ModulesServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use RedFreak\Modules\Tests\Behaviour\DoesNotCreateApplication;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /** @var \Illuminate\Foundation\Application|null */
    protected static ?Application $appStore = null;

    protected ?Application $app = null;

    protected function setUp(): void
    {
        parent::setUp();

        if (!in_array(DoesNotCreateApplication::class, class_uses($this), true)) {
            if (!self::$appStore) {
                self::$appStore = $this->createApplication();
            }

            $this->app = self::$appStore;
        }
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array<int, class-string<\Illuminate\Support\ServiceProvider>>
     */
    protected function getPackageProviders($app)
    {
        return [
            ModulesServiceProvider::class
        ];
    }
}
