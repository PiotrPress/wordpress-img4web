# WordPress Notice

This library simplifies the displaying of Notices in the WordPress dashboard.

## Installation

```shell
$ composer require piotrpress/wordpress-notice
```

## Example

```php
require __DIR__ . '/vendor/autoload.php';

namespace PiotrPress\WordPress;

// Example #1
echo new Notice( 'This is an example notice message', Notice::ERROR );

// Example #2
Hooks::add( new Notice( 'This is an example notice message', Notice::ERROR ) );
```

## Arguments

- `string` **$message** - The notice message.
- `string` **$type** - The notice type. Possible values are `Notice::INFO`, `Notice::SUCCESS`, `Notice::WARNING`, `Notice::ERROR`. Default is `null`.
- `bool` **$dismissible** - Whether the notice is dismissible. Possible values are `true`, `false`. Default is `false`.
- `bool` **$alt** - The alternative notice display style (with filled-in background). Possible values are `true`, `false`. Default is `false`.

## Requirements

PHP >= `7.4` version.

## License

[GPL3.0](license.txt)