# Cacher

This library provides a simple file-based caching solution.

## Installation

```shell
$ composer require piotrpress/cacher
```

## Usage

```php
require __DIR__ . '/vendor/autoload.php';

use PiotrPress\Cacher;

$cache = new Cacher( '.cache', 3600 );

$value = $cache->get( 'hi', function ( $arg1, $arg2 ) {
    return "$arg1 $arg2";
}, 'Hello', 'world!' );

$cache->clear( 'hi' ); // clear cache for "hi" key
$cache->clear(); // clear all cache
```

You can use `php://memory` as a file to store cache in memory, for instance, while developing or testing.

Cacher takes an expiration time in seconds as the second argument. By default, it is set to `-1`, which means the cache never expires. If the value `0` is provided, the cache will be cleared on every call. 

## Requirements

Supports PHP >= `7.4` version.

## License

[MIT](license.txt)