<?php
/**
 * The main file of the plugin.
 *
 * @package lhbasics\plugin
 */

namespace WpMunich\basicsTest\plugin;

use function get_plugin_data;
use function plugin_dir_url;

/**
 * Main class for the plugin.
 *
 * This class takes care of initializing plugin features and available template tags.
 */
class Plugin {
	/**
	 * Constructor.
	 *
	 * @param i18n\I18N             $i18n The i18n component.
	 * @param Scripts\Scripts       $scripts The scripts component.
	 * @param TestModule\TestModule $test_module The test module component.
	 */
	public function __construct(
		private i18n\I18N $i18n,
		private Scripts\Scripts $scripts,
		private TestModule\TestModule $test_module
	) {
	}

	/**
	 * Get the plugin version.
	 *
	 * @return string The plugin version.
	 */
	public function get_plugin_version() {
		// Check if we can use the `get_plugin_data()` function.
		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		/**
		 * The plugin data as an array.
		 * We use this to avoid updating plugin data on multiple locations. This makes
		 * the file header of the plugin main file the single source of truth.
		 */
		$plugin_data = get_plugin_data( LHBASICSTESTP_FILE );

		return $plugin_data['Version'] ?? '0.0.1';
	}

	/**
	 * Get the main plugin file.
	 *
	 * @return string The main plugin file.
	 */
	public function get_plugin_file() {
		return LHBASICSTESTP_FILE;
	}

	/**
	 * Get the plugin directory path.
	 *
	 * @return string The plugin directory path.
	 */
	public function get_plugin_path() {
		return plugin_dir_path( $this->get_plugin_file() );
	}

	/**
	 * Get the plugin directory URL.
	 */
	public function get_plugin_url() {
		return plugin_dir_url( $this->get_plugin_file() );
	}

	/**
	 * Get the plugin slug.
	 *
	 * @return string The plugin slug.
	 */
	public function get_plugin_slug() {
		return 'lhbasicsTestp';
	}

	/**
	 * Get the DI container.
	 *
	 * @return \DI\Container The DI container.
	 */
	public function container() {
		return plugin_container();
	}
}
