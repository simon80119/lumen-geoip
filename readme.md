# GeoIP for Lumen

[![Latest Stable Version](https://poser.pugx.org/codenexus/lumen-geoip/v/stable)](https://packagist.org/packages/codenexus/lumen-geoip) [![Total Downloads](https://poser.pugx.org/codenexus/lumen-geoip/downloads)](https://packagist.org/packages/codenexus/lumen-geoip) [![License](https://poser.pugx.org/codenexus/lumen-geoip/license)](https://packagist.org/packages/codenexus/lumen-geoip) [![composer.lock](https://poser.pugx.org/codenexus/lumen-geoip/composerlock)](https://packagist.org/packages/codenexus/lumen-geoip) [![StyleCI](https://styleci.io/repos/44153079/shield)](https://styleci.io/repos/44153079)

Determine the geographical location of website visitors based on their IP addresses.

## Installation

To install this package, just install through composer

```
$ composer require codenexus/lumen-geoip
```

### Providers

Next, open `bootstrap/app.php` and add under the Register Service Providers section:

```php
...
$app->register(Codenexus\GeoIP\GeoIPServiceProvider::class);
```

### Update MaxMind GeoLite2 City database

Run this on the command line from the root of your project:

```
$ php artisan geoip:update
```

### Usage

GeoIP will try to determine the IP using the following http headers: `HTTP_CLIENT_IP`, `HTTP_X_FORWARDED_FOR`, `HTTP_X_FORWARDED`, `HTTP_X_CLUSTER_CLIENT_IP`, `HTTP_FORWARDED_FOR`, `HTTP_FORWARDED`, `REMOTE_ADDR` in this order.  Optionally you can set an IP as the only parameter to set it.

```php
$record = app()->geoip->getLocation('232.223.11.11');
$record = GeoIP::getLocation('232.223.11.11'); // If you have enabled facades

print($record->country->isoCode . "\n"); // 'US'
print($record->country->name . "\n"); // 'United States'
print($record->country->names['zh-CN'] . "\n"); // '美国'

print($record->mostSpecificSubdivision->name . "\n"); // 'Minnesota'
print($record->mostSpecificSubdivision->isoCode . "\n"); // 'MN'

print($record->city->name . "\n"); // 'Minneapolis'

print($record->postal->code . "\n"); // '55455'

print($record->location->latitude . "\n"); // 44.9733
print($record->location->longitude . "\n"); // -93.2323
```

### Other Methods

These methods are also available to use within your applications.

```php
app()->geoip->checkIp($ip) // Checks IP to make sure IP is a valid IPv4 or IPv6 address and not within a private or reserved range
app()->geoip->getIp() // Returns the detected client IP
```

### Default Location Data

When an IP is not detected it will be set to 127.0.0.1 which will ultimately throw an Exception.  If you are not in production your record will default to the following data.

```php
array (
    "ip"           => "232.223.11.11",
    "isoCode"      => "US",
    "country"      => "United States",
    "city"         => "New Haven",
    "state"        => "CT",
    "postal_code"  => "06510",
    "lat"          => 41.28,
    "lon"          => -72.88,
    "timezone"     => "America/New_York",
    "continent"    => "NA",
    "default"      => false
);
```

## Change Log

#### v2.0.0

- Simplified namespace
- Added Facade support
- Added default location when in development
- Fixed bug where detected IP was always blank
