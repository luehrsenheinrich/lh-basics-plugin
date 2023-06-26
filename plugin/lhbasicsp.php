<?php
/**
 * The main file of the plugin.
 *
 * @package lhbasicsp
 *
 * Plugin Name: LH Basics Plugin
 * Plugin URI: https://www.luehrsen-heinrich.de
 * Description: A WordPress plugin to bundle basic perks by Luehrsen // Heinrich.
 * Author: Luehrsen // Heinrich
 * Author URI: https://www.luehrsen-heinrich.de
 * Version: 0.1.0
 * Text Domain: lhbasicsp
 * Domain Path: /languages
 */

use function WpMunich\lhbasicsp\lh_plugin;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
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

// If we are in the development environment, load some test functions.
if ( wp_get_environment_type() === 'development' ) {
	require plugin_dir_path( LHBASICSP_FILE ) . 'inc/test.php';
}

// Initialize the plugin.
call_user_func( 'WpMunich\lhbasicsp\lh_plugin' );

// Initialize the plugin update checker.
if ( class_exists( 'Puc_v4_Factory' ) ) {
	Puc_v4_Factory::buildUpdateChecker(
		'https://www.luehrsen-heinrich.de/updates/?action=get_metadata&slug=' . lh_plugin()->get_plugin_slug(),
		__FILE__, // Full path to the main plugin file or functions.php.
		lh_plugin()->get_plugin_slug()
	);
}
