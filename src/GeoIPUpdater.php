<?php

namespace Codenexus\GeoIP;

class GeoIPUpdater
{
    /**
     * Main update method.
     *
     * @return bool|string
     */
    public function update()
    {
        $url = 'http://geolite.maxmind.com/download/geoip/database/GeoLite2-City.mmdb.gz';
        $databasePath = storage_path('app/geoip.mmdb');

        // Download latest MaxMind GeoLite2 City database to temp location
        $tmpFile = tempnam(sys_get_temp_dir(), 'maxmind');
        file_put_contents($tmpFile, fopen($url, 'r'));

        // Extract database to proper storage location
        file_put_contents($databasePath, gzopen($tmpFile, 'r'));

        // Delete temp file
        unlink($tmpFile);

        return $databasePath;
    }
}
