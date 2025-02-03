<?php
/**
 * Settings Class
 *
 * @package lhbasicsp
 */

namespace WpMunich\basics\plugin\Settings;

use WpMunich\basics\plugin\Plugin_Component;
use function WpMunich\basics\plugin\plugin;

/**
 * This component handles the settings of the plugin.
 */
class Settings extends Plugin_Component {

	/**
	 * {@inheritDoc}
	 */
	protected function add_actions() {
		add_action( 'admin_menu', array( $this, 'add_options_pages' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'init', array( $this, 'register_settings' ) );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function add_filters() {}

	/**
	 * Add the options pages.
	 *
	 * @return void
	 */
	public function add_options_pages() {
		add_options_page(
			__( 'Luehrsen // Heinrich Settings', 'lhbasicsp' ),
			__( 'Luehrsen // Heinrich', 'lhbasicsp' ),
			'manage_options',
			'lhagentur-settings',
			array( $this, 'render_options_page' )
		);
	}

	/**
	 * Render the options page.
	 *
	 * @return void
	 */
	public function render_options_page() {
		?>
		<div id="admin-settings-page" class="lhagentur-settings"></div>
		<?php
	}

	/**
	 * Enqueue assets.
	 *
	 * @return void
	 */
	public function enqueue_assets() {
		$screen = get_current_screen();
		$assets = wp_json_file_decode( plugin()->get_plugin_path() . '/admin/dist/assets.json', array( 'associative' => true ) );

		$lhbasics_assets = $assets['js/lhbasics.min.js'] ?? array();
		wp_register_script( 'lhbasics', plugin()->get_plugin_url() . '/admin/dist/js/lhbasics.min.js', $lhbasics_assets['dependencies'], $lhbasics_assets['version'], true );

		if ( in_array( $screen->id, array( 'settings_page_lhagentur-settings' ), true ) ) {
			$admin_script_assets = $assets['js/admin-settings-page.min.js'] ?? array();

			wp_enqueue_script( 'lhagentur-settings-page', plugin()->get_plugin_url() . '/admin/dist/js/admin-settings-page.min.js', $admin_script_assets['dependencies'], $admin_script_assets['version'], true );

			wp_localize_script(
				'lhagentur-settings-page',
				'lhagenturSettings',
				array(
					'pluginUrl' => plugin()->get_plugin_url(),
					'restUrl'   => get_rest_url(),
					'modules'   => $this->get_available_modules(),
				)
			);

			wp_enqueue_style( 'lhagentur-settings-page', plugin()->get_plugin_url() . '/admin/dist/css/admin-settings-page.min.css', array( 'wp-components' ), plugin()->get_plugin_version() );
		}
	}

	/**
	 * Get the available modules.
	 *
	 * @return array
	 */
	private function get_available_modules() {
		/**
		 * An array of module definitions.
		 * This is the structure of a module definition:
		 * array(
		 *    'title'       => (string) 'Module Title',
		 *    'description' => (string) 'Module Description',
		 *    'slug'        => (string) 'module-slug',
		 * )
		 *
		 * @return array
		 */
		$modules = apply_filters( 'lhagentur_available_modules', array() );

		// Enforce a numeric array.
		$modules = array_values( $modules );

		return $modules;
	}

	/**
	 * Check if a module is active.
	 *
	 * @param string $module The module to check.
	 *
	 * @return bool
	 */
	public function is_module_active( string $module ) {
		$active_modules = get_option( 'active_modules', array() );

		$is_module_active = in_array( $module, $active_modules, true );

		return apply_filters( 'lhagentur_is_module_active', $is_module_active, $module );
	}

	/**
	 * Register the settings needed for this plugin and theme.
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_setting/
	 *
	 * @return void
	 */
	public function register_settings() {
		register_setting(
			'lhagentur',
			'active_modules',
			array(
				'type'         => 'array',
				'description'  => 'The active modules of the plugin.',
				'show_in_rest' => array(
					'schema' => array(
						'type'  => 'array',
						'items' => array(
							'type' => 'string',
						),
					),

				),
				'default'      => array(),
			)
		);
	}
}
