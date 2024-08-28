# Logger

This library is compatible with [PSR-3](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md) file logger implementation.

## Installation

```console
composer require piotrpress/logger
```

## Usage

```php
require __DIR__ . '/vendor/autoload.php';

use PiotrPress\Logger;

$logger = new Logger( '/logs/error.log' );
$logger->error( 'Error example' );
```

Saves: `[2021-03-23 23:15:00] [error] Error example` to file: `/logs/error.log`

## Format

Logger supports `context` array via constructor and/or log functions optional parameter.

### Defaults:

* **format**: `"[{date}] [{level}] {message}\n"`
* **date**: `date( 'Y-m-d G:i:s' )`
* **level**: log level, with which the method has been called
* **message**: message, with which the method has been called

All additional array values, evaluated to string, can be used in `format` via corresponding keys put between a single opening brace `{` and a single closing brace `}`.

## Log Levels

Logger supports eight methods to write logs to the eight [RFC 5424](http://tools.ietf.org/html/rfc5424) levels (`debug`, `info`, `notice`, `warning`, `error`, `critical`, `alert`, `emergency`) and a ninth method `log`, which accepts a log level as the first argument.

## License

[GPL3.0](license.txt)