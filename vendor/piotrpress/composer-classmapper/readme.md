# Composer ClassMapper

This Composer command generates a `classmap.php` file based on project files.

**NOTE:** The command can be executed in projects without `composer.json` file too.

## Installation

1. Add the command as a global composer plugin:

```shell
$ composer global require piotrpress/composer-classmapper
```

2. Allow plugin execution:

```shell
$ composer config -g allow-plugins.piotrpress/composer-classmapper true
```

## Usage

1. Execute the command in project's directory:

```shell
$ composer map [-e|--exclude [REGEX]]
```

**NOTE:** The option `exclude` is regex that matches file paths to be excluded from the classmap.

2. After the command execution, simply include autoload file in the project:

```php
require __DIR__ . '/autoload.php';
```

## Example

```shell
$ composer map -e"#/vendor/composer/(.*)#"
```

## License

[MIT](license.txt)