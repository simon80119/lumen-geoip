<?php

namespace CodeOrange\GeoIP;

use PharData;
use Exception;

class GeoIPUpdater {
	/**
	 * Main update method.
	 *
	 * @return bool|string
	 */

    public function update()
    {
        $databasePath = storage_path('app/geoip.mmdb');
        $this->withTemporaryDirectory(function ($directory) use($databasePath){
            $license_key = env('MAXMIND_LICENSE_KEY');
            $url = "https://download.maxmind.com/app/geoip_download?edition_id=GeoLite2-City&license_key=$license_key&suffix=tar.gz";
            $tarFile = sprintf('%s/maxmind.tar.gz', $directory);
            file_put_contents($tarFile, fopen($url, 'r'));
            $archive = new PharData($tarFile);
            $file = $this->findDatabaseFile($archive);
            $relativePath = "{$archive->getFilename()}/{$file->getFilename()}";
            $archive->extractTo($directory, $relativePath);
            file_put_contents($databasePath, fopen("{$directory}/{$relativePath}", 'r'));
        });
        return "Database file ({$databasePath}) updated.";
	}
    /**
     * Provide a temporary directory to perform operations in and and ensure
     * it is removed afterwards.
     *
     * @param  callable  $callback
     * @return void
     */
    protected function withTemporaryDirectory(callable $callback)
    {
        $directory = tempnam(sys_get_temp_dir(), 'maxmind');
        if (file_exists($directory)) {
            unlink($directory);
        }
        mkdir($directory);
        try {
            $callback($directory);
        } finally {
            $this->deleteDirectory($directory);
        }
    }
    /**
     * Recursively search the given archive to find the .mmdb file.
     *
     * @param  \PharData  $archive
     * @return mixed
     * @throws \Exception
     */
    protected function findDatabaseFile($archive)
    {
        foreach ($archive as $file) {
            if ($file->isDir()) {
                return $this->findDatabaseFile(new PharData($file->getPathName()));
            }
            if (pathinfo($file, PATHINFO_EXTENSION) === 'mmdb') {
                return $file;
            }
        }
        throw new Exception('Database file could not be found within archive.');
    }
    /**
     * Recursively delete the given directory.
     *
     * @param  string  $directory
     * @return mixed
     */
    protected function deleteDirectory(string $directory)
    {
        if (!file_exists($directory)) {
            return true;
        }
        if (!is_dir($directory)) {
            return unlink($directory);
        }
        foreach (scandir($directory) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            if (!$this->deleteDirectory($directory . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }
        return rmdir($directory);
    }
}
