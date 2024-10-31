<?php
/**
 * The main file of the plugin.
 *
 * @package lhbasics\plugin
 *
 * Plugin Name: L//H Basics
 * Plugin URI: https://www.luehrsen-heinrich.de
 * Description: A plugin that provides basic functionality for our WordPress projects.
 * Author: Luehrsen // Heinrich
 * Author URI: https://www.luehrsen-heinrich.de
 * Version: 0.3.1
 * Text Domain: lhbasicsp
 * Domain Path: /languages
 */

use function WpMunich\basics\plugin\plugin;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	exit( 1 );
}

// Set a constant for the plugin's main file.
if ( ! defined( 'LHBASICSP_FILE' ) ) {
	/**
	 * The path to the main file of the plugin.
	 *
	 * @var string
	 */
	define( 'LHBASICSP_FILE', __FILE__ );
}

// Load the autoloader.
require plugin_dir_path( LHBASICSP_FILE ) . 'vendor/autoload.php';

// Load the `wp_lhbasicsp()` entry point function.
require plugin_dir_path( LHBASICSP_FILE ) . 'inc/functions.php';


// Initialize the plugin.
call_user_func( 'WpMunich\basics\plugin\plugin' );

// Initialize the plugin update checker.
if ( class_exists( 'Puc_v4_Factory' ) ) {
	Puc_v4_Factory::buildUpdateChecker(
		'https://www.luehrsen-heinrich.de/updates/?action=get_metadata&slug=' . plugin()->get_plugin_slug(),
		__FILE__, // Full path to the main plugin file or functions.php.
		plugin()->get_plugin_slug()
	);
}
