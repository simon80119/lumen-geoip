<?php

namespace Codenexus\GeoIP\Console\Commands;

use Codenexus\GeoIP\GeoIPUpdater;
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
     * @var \Codenexus\GeoIPlm\GeoIPUpdater
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
    public function fire()
    {
        $result = $this->updater->update();

        if (!$result) {
            $this->error('Update failed!');

            return;
        }

        $this->info('New database file ('.$result.') installed');
    }
}
