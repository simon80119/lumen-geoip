<?php

namespace CodeOrange\GeoIP\Console\Commands;

use CodeOrange\GeoIP\GeoIPUpdater;
use Illuminate\Console\Command;

class UpdateCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'geoip:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update geoip database files to the latest version';

    /**
     * Updater Object.
     *
     * @var \CodeOrange\GeoIPlm\GeoIPUpdater
     */
    protected $updater;

    /**
     * Create a new console command instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->updater = new GeoIPUpdater();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $result = $this->updater->update();

        if (!$result) {
            $this->error('Update failed!');

            return;
        }

        $this->info('New database file ('.$result.') installed');
    }

    public function fire()
    {
        $this->handle();
    }
}
