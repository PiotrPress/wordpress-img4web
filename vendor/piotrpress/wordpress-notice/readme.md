# WordPress Notice

This library simplifies the displaying of Notices in the WordPress dashboard.

## Installation

```shell
$ composer require piotrpress/wordpress-notice
```

## Example

```php
require __DIR__ . '/vendor/autoload.php';

use PiotrPress\WordPress\Notice;

new Notice( 'This is an example notice message', 'error' );
```

## Requirements

PHP >= `7.4` version.

## License

[GPL3.0](license.txt)