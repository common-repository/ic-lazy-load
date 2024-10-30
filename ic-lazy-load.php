<?php
/**
 * Plugin Name: IC Lazy Load
 * Plugin URI:  https://itclanbd.com/
 * Description: Simple lazy loading for images
 * Version:     1.0.1
 * Author:      ITclan BD
 * Author URI:  https://itclanbd.com/
 * Text Domain: ic-lazy-load
 * Domain Path: /languages
 * License:     GPL2
 */
 
 
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ITCLAN_LAZY_LOAD_VERSION', '1.0.1' );

/**
 * Loading init file
 * @since 1.0
 * @author ITclan BD
 */
require_once plugin_dir_path( __FILE__ ) . '/inc/init.php';