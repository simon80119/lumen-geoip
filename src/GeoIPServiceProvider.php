<?php

namespace Codenexus\GeoIP;

use Illuminate\Support\ServiceProvider;
use Codenexus\GeoIP\Console\Commands\UpdateCommand;

class GeoIPServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register()
    {
        $this->app->singleton('geoip', function () {
            return new GeoIP;
        });

        $this->app->singleton('command.geoip.update', function () {
            return new UpdateCommand;
        });

        $this->commands(
            'command.geoip.update'
        );
    }
}
