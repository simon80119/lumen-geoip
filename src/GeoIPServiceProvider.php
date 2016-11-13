<?php

namespace Codenexus\GeoIP;

use Codenexus\GeoIP\Console\Commands\UpdateCommand;
use Illuminate\Support\ServiceProvider;

class GeoIPServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register()
    {
        $this->app->singleton('geoip', function () {
            return new GeoIP();
        });

        $this->app->singleton('command.geoip.update', function () {
            return new UpdateCommand();
        });

        $this->commands(
            'command.geoip.update'
        );
    }
}
