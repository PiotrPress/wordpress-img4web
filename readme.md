# WordPress Img4Web

This WordPress plugin uses img4web.app service to convert images to WEBP and/or AVIF formats.

## Usage via `WP-CLI`

```shell
$ wp convert [<file>...] [--type=<type>] [--quality=<quality>] [--overwrite] [--network] [--dry-run] [--no-success] [--no-skipped] [--no-failed] [--no-stats]
```

### Where:
- `file` - The path to the image file to convert (if not provided, all images will be converted).
- `--type` - The type of the output format. Possible values: `webp`, `avif`. Default: `webp,avif`.
- `--quality` - The quality of the output format. Possible values: `0-100`. Default: `-1`.
- `--overwrite` - Overwrite existing files. Default: `false`.
- `--network` - Convert images for all sites in the network. Default: `false`.
- `--dry-run` - Do not convert images, only show what would be done. Default: `false`.
- `--no-success` - Do not display success messages. Default: `false`.
- `--no-skipped` - Do not display skipped messages. Default: `false`.
- `--no-failed` - Do not display failed messages. Default: `false`.
- `--no-stats` - Do not display statistics. Default: `false`.

### Note:
Run command as `www-data` user to avoid permission issues e.g. `$ sudo -u www-data wp convert ...`.

## Cron

Example of a cron job that converts all images to `WEBP` and `AVIF` formats every day at midnight: `$ sudo crontab -u www-data -e` 

```
0 0 * * * /usr/bin/wp convert --path=/path/to/wordpress --no-success --no-skipped --no-stats
```

## Requirements

PHP >= `7.4` version.

## License

[GPL v3 or later](license.txt)