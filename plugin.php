<?php declare( strict_types = 1 );

/**
 * Plugin Name: Img4Web
 * Plugin URI: https://wordpress.org/plugins/img4web
 * Description: This WordPress plugin uses img4web.app to convert images to WEBP and/or AVIF formats.
 * Version: 0.2.0
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: Img4Web
 * Author URI: https://img4web.app
 * License: GPL v3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: img4web
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) or exit;

require __DIR__ . '/autoload.php';

Img4Web\Plugin::getInstance( __FILE__ );
Img4Web\Command::getInstance();