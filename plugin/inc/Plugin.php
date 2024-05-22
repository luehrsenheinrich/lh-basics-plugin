<?php
/**
 * LHBASICSP\Plugin class
 *
 * @package lhbasicsp
 */

namespace WpMunich\lhbasicsp;
use function get_plugin_data;
use function plugin_dir_url;

/**
 * Main class for the plugin.
 *
 * This class takes care of initializing plugin features and available template tags.
 */
class Plugin {

	/**
	 * Disable_Comments component.
	 *
	 * @var Disable_Comments\Disable_Comments;
	 */
	protected $disable_comments;

	/**
	 * Gravity_Forms component.
	 *
	 * @var Gravity_Forms\Gravity_Forms;
	 */
	protected $gravity_forms;

	/**
	 * I18N component.
	 *
	 * @var i18n\I18N;
	 */
	protected $i18n;

	/**
	 * Lazysizes component.
	 *
	 * @var Lazysizes\Lazysizes;
	 */
	protected $lazysizes;

	/**
	 * Lightbox component.
	 *
	 * @var Lightbox\Lightbox;
	 */
	protected $lightbox;

	/**
	 * Options component.
	 *
	 * @var Options\Options;
	 */
	protected $options;

	/**
	 * Constructor.
	 *
	 * @param Disable_Comments\Disable_Comments $disable_comments Disable_Comments component.
	 * @param Gravity_Forms\Gravity_Forms       $gravity_forms    Gravity_Forms component.
	 * @param i18n\I18N                         $i18n             I18N component.
	 * @param Lazysizes\Lazysizes               $lazysizes        Lazysizes component.
	 * @param Lightbox\Lightbox                 $lightbox         Lightbox component.
	 * @param Options\Options                   $options          Options component.
	 */
	public function __construct(
		Disable_Comments\Disable_Comments $disable_comments,
		Gravity_Forms\Gravity_Forms $gravity_forms,
		i18n\I18N $i18n,
		Lazysizes\Lazysizes $lazysizes,
		Lightbox\Lightbox $lightbox,
		Options\Options $options
	) {
		$this->disable_comments = $disable_comments;
		$this->gravity_forms    = $gravity_forms;
		$this->i18n             = $i18n;
		$this->lazysizes        = $lazysizes;
		$this->lightbox         = $lightbox;
		$this->options          = $options;
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
}
