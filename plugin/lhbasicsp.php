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
 * x-release-please-start-version
 * Version: 0.5.1
 * x-release-please-end
 * Text Domain: lhbasicsp
 * Domain Path: /languages
 */

use function WpMunich\basics\plugin\plugin;
use WpMunich\basics\plugin\Dependencies\YahnisElsts\PluginUpdateChecker\v5\PucFactory;
use WpMunich\basics\plugin\Dependencies\YahnisElsts\PluginUpdateChecker\v5p7\PucFactory as VersionedPucFactory;
use WpMunich\basics\plugin\Dependencies\YahnisElsts\PluginUpdateChecker\v5p7\Plugin\UpdateChecker as PluginUpdateChecker;
use WpMunich\basics\plugin\Dependencies\YahnisElsts\PluginUpdateChecker\v5p7\Theme\UpdateChecker as ThemeUpdateChecker;
use WpMunich\basics\plugin\Dependencies\YahnisElsts\PluginUpdateChecker\v5p7\Vcs\BitBucketApi;
use WpMunich\basics\plugin\Dependencies\YahnisElsts\PluginUpdateChecker\v5p7\Vcs\GitHubApi;
use WpMunich\basics\plugin\Dependencies\YahnisElsts\PluginUpdateChecker\v5p7\Vcs\GitLabApi;
use WpMunich\basics\plugin\Dependencies\YahnisElsts\PluginUpdateChecker\v5p7\Vcs\PluginUpdateChecker as VcsPluginUpdateChecker;
use WpMunich\basics\plugin\Dependencies\YahnisElsts\PluginUpdateChecker\v5p7\Vcs\ThemeUpdateChecker as VcsThemeUpdateChecker;

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

// Load first-party plugin classes and prefixed dependencies.
require plugin_dir_path( LHBASICSP_FILE ) . 'inc/autoload.php';
require plugin_dir_path( LHBASICSP_FILE ) . 'vendor-prefixed/autoload.php';
require plugin_dir_path( LHBASICSP_FILE ) . 'vendor-prefixed/DI/functions.php';

// Load the `wp_lhbasicsp()` entry point function.
require plugin_dir_path( LHBASICSP_FILE ) . 'inc/functions.php';

/**
 * Register Plugin Update Checker classes with the prefixed factory.
 *
 * Mozart prefixes Plugin Update Checker's classes, but we intentionally do not
 * autoload its original loader because it relies on relative require paths that
 * no longer match the prefixed layout.
 *
 * @return void
 */
function lhbasicsp_register_update_checker_versions() {
	static $registered = false;

	if ( $registered || ! class_exists( PucFactory::class ) ) {
		return;
	}

	$registered = true;

	foreach (
		array(
			'Plugin\\UpdateChecker'    => PluginUpdateChecker::class,
			'Theme\\UpdateChecker'     => ThemeUpdateChecker::class,
			'Vcs\\PluginUpdateChecker' => VcsPluginUpdateChecker::class,
			'Vcs\\ThemeUpdateChecker'  => VcsThemeUpdateChecker::class,
			'GitHubApi'                => GitHubApi::class,
			'BitBucketApi'             => BitBucketApi::class,
			'GitLabApi'                => GitLabApi::class,
		)
		as $puc_general_class => $puc_versioned_class
	) {
		PucFactory::addVersion( $puc_general_class, $puc_versioned_class, '5.7' );
		VersionedPucFactory::addVersion( $puc_general_class, $puc_versioned_class, '5.7' );
	}
}

lhbasicsp_register_update_checker_versions();

// Initialize the plugin.
call_user_func( 'WpMunich\basics\plugin\plugin' );

// Initialize the plugin update checker.
if ( class_exists( PucFactory::class ) ) {
	PucFactory::buildUpdateChecker(
		'https://www.luehrsen-heinrich.de/updates/?action=get_metadata&slug=' . plugin()->get_plugin_slug(),
		__FILE__, // Full path to the main plugin file or functions.php.
		plugin()->get_plugin_slug()
	);
}
