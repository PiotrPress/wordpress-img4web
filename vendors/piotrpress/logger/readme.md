# Logger

This library is compatible with [PSR-3](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md) file logger implementation.

## Installation

```console
composer require piotrpress/logger
```

## Example

```php
require __DIR__ . '/vendor/autoload.php';

use PiotrPress\Logger;
use PiotrPress\Logger\FileHandler;
use PiotrPress\Logger\ErrorLogHandler;

$logger = new Logger(
    new FileHandler( __DIR__ . '/' . date( 'Y-m-d' ) . '.log' ),
    new ErrorLogHandler() 
);
    
$logger->error( '[{module}] Example error', [ 'module' => 'Core' ] );
```

Saves: `[error] [Core] Example error` to file: `{Y-m-d}.log` and sends to PHP error log.

## Logger

[Logger](/src/Logger.php) take any number of handlers implementing [HandlerInterface](/src/Handler/HandlerInterface.php) as constructor arguments.

## Handlers

- [ErrorLogHandler](/src/Handler/ErrorLogHandler.php) - send logs to PHP error log.
- [FileHandler](/src/Handler/FileHandler.php) - send logs to file.

**NOTE:** Both handlers support optional [FormatterInterface](/src/Formatter/FormatterInterface.php) parameter. 

## Formatters

- [ErrorLogFormatter](/src/Formatter/ErrorLogFormatter.php) - formats [LogRecord](/src/LogRecord.php) using [error_log](/tpl/error_log.php) template.
- [FileFormatter](/src/Formatter/FileFormatter.php) - formats [LogRecord](/src/LogRecord.php) using [file](/tpl/file.php) template.

**NOTE:** Both formatters support optional path to `template` parameter.

## Levels

Logger supports eight log methods to write logs to the eight [RFC 5424](http://tools.ietf.org/html/rfc5424) levels (`debug`, `info`, `notice`, `warning`, `error`, `critical`, `alert`, `emergency`) and a ninth method `log`, which accepts a log level as the first argument.

## Context

All Logger log methods supports optional `context` array parameter.

All additional `context` array values, evaluated to string, can be used in `message` via corresponding keys put between a single opening brace `{` and a single closing brace `}` according to [PSR-3](https://www.php-fig.org/psr/psr-3/#13-context) guidelines.

Context values can be also used in `templates` files as regular PHP variables.

## Requirements

Supports PHP >= `7.4` version.

## License

[GPL3.0](license.txt)