<?php

namespace Codenexus\GeoIP;

use GeoIp2\Database\Reader;

class GeoIP {
	/**
	 * The client IP address
	 * 
	 * @var float
	 */
	protected $client_ip;

	/**
	 * Create a new GeoIP instance
	 */
	public function __construct()
	{
		$this->ip = $this->getClientIp();
	}

	/**
	 * Retrieve location data from database
	 * 
	 * @param  float $ip
	 * 
	 * @return object
	 */
	public function getLocation($ip = null)
	{
		// If no IP given set $ip to $this->client_ip
		if (! $ip) {
			$ip = $this->getClientIp();
		}

		// Check IP to make sure it is valid
		if (! $this->checkIp($ip))
		{
			throw new \Exception("IP Address is either not a valid IPv4/IPv6 address or falls within the private or reserved ranges");
		}

		$reader = new Reader(storage_path('app/geoip.mmdb'));
		$record = $reader->city($ip);

		return $record;
	}

    /**
     * Checks IP to make sure it is a valid IPv4 or IPv6 address and
     * not within a private or reserved range
     * 
     * @param  float $ip
     * 
     * @return float|bool
     */
    public function checkIp($ip)
    {
		return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
    }

    /**
     * Gets the value of client_ip.
     *
     * @return mixed
     */
    public function getClientIp()
    {
        return $this->client_ip;
    }

    /**
     * Sets the value of client_ip to one of the values stored
     * in an environmental variable
     *
     * @param mixed $client_ip
     *
     * @return self
     */
    private function setClientIp($client_ip)
    {
		$headerKeys = [
			'HTTP_CLIENT_IP',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_X_FORWARDED',
			'HTTP_X_CLUSTER_CLIENT_IP',
			'HTTP_FORWARDED_FOR',
			'HTTP_FORWARDED',
			'REMOTE_ADDR'
		];

		foreach($headerKeys as $key)
	    {
			if (env($key)) {
	    		$this->client_ip = env($key);
	    	}
	    }

        return $this;
    }
}