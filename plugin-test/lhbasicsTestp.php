<?php
/**
 * The main file of the test plugin.
 *
 * @package lhbasics\plugin-test
 *
 * Plugin Name: L//H Basics Test
 * Plugin URI: https://www.luehrsen-heinrich.de
 * Description: This plugin is a test plugin for the L//H Basics plugin.
 * Author: Luehrsen // Heinrich
 * Author URI: https://www.luehrsen-heinrich.de
 * Version: 0.0.0.0.0.1
 * Text Domain: lhbasicsp
 * Domain Path: /languages
 */

use function WpMunich\basicsTest\plugin\plugin;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	exit( 1 );
}

// Set a constant for the plugin's main file.
if ( ! defined( 'LHBASICSTESTP_FILE' ) ) {
	/**
	 * The path to the main file of the plugin.
	 *
	 * @var string
	 */
	define( 'LHBASICSTESTP_FILE', __FILE__ );
}

// Load the autoloader.
require plugin_dir_path( LHBASICSTESTP_FILE ) . 'vendor/autoload.php';

// Load the `wp_lhbasicsp()` entry point function.
require plugin_dir_path( LHBASICSTESTP_FILE ) . 'inc/functions.php';


// Initialize the plugin.
call_user_func( 'WpMunich\basicsTest\plugin\plugin' );
