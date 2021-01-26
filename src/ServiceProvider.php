<?php

namespace Marshmallow\Domain;

use Marshmallow\Domain\Console\InstallCommand;
use Marshmallow\Domain\Console\DomainWhoisCommand;
use Marshmallow\Domain\Console\DomainAvailableCommand;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/trans-ip.php',
            'trans-ip'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/trans-ip.php' => config_path('trans-ip.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                DomainAvailableCommand::class,
            ]);
        }
    }
}
