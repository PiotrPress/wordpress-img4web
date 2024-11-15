# Singleton

This library is a [Singleton](https://en.wikipedia.org/wiki/Singleton_pattern) (anti)pattern implementation using a [Traits](https://www.php.net/manual/en/language.oop5.traits.php) with a support for an [Object Inheritance](https://www.php.net/manual/en/language.oop5.inheritance.php) and passing parameters to the constructor.

## Installation

```console
composer require piotrpress/singleton
```

## Usage

```php
require __DIR__ . '/vendor/autoload.php';

use PiotrPress\Singleton;

class ExampleParent {
    use Singleton;
}

class Example extends ExampleParent {
    protected function __construct( $arg ) {}
}

Example::getInstance( 'arg' );
```

## Requirements

PHP >= `7.4` version.

## License

[GPL3.0](license.txt)