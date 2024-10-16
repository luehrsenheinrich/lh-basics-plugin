<?php
/**
 * The main file of the plugin.
 *
 * @package lhbasics\plugin
 */

namespace WpMunich\basics\plugin;
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
	 * @param i18n\I18N                         $i18n The i18n component.
	 * @param Disable_Comments\Disable_Comments $disable_comments The disable comments component.
	 * @param Gravity_Forms\Gravity_Forms       $gravity_forms The gravity forms component.
	 * @param Lazysizes\Lazysizes               $lazysizes The lazysizes component.
	 * @param Lightbox\Lightbox                 $lightbox The lightbox component.
	 * @param Settings\Settings                 $settings The settings component.
	 * @param SVG\SVG                           $svg The svg component.
	 */
	public function __construct(
		private i18n\I18N $i18n,
		private Disable_Comments\Disable_Comments $disable_comments,
		private Gravity_Forms\Gravity_Forms $gravity_forms,
		private Lazysizes\Lazysizes $lazysizes,
		private Lightbox\Lightbox $lightbox,
		private Settings\Settings $settings,
		private SVG\SVG $svg,
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
		$plugin_data = get_plugin_data( LHBASICSP_FILE );

		return $plugin_data['Version'] ?? '0.0.1';
	}

	/**
	 * Get the main plugin file.
	 *
	 * @return string The main plugin file.
	 */
	public function get_plugin_file() {
		return LHBASICSP_FILE;
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
		return 'lhbasicsp';
	}

	/**
	 * Get the DI container.
	 *
	 * @return \DI\Container The DI container.
	 */
	public function container() {
		return plugin_container();
	}

	/**
	 * Get the SVG component.
	 */
	public function svg() {
		return $this->svg;
	}
}
