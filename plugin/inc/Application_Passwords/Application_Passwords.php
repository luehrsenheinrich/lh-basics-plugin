<?php
/**
 * Holds the Application_Passwords class.
 *
 * @package lhbasicsp
 */

namespace WpMunich\basics\plugin\Application_Passwords;

use WpMunich\basics\plugin\Plugin_Component;
use WpMunich\basics\plugin\Settings\Settings;

/**
 * Enables WordPress application passwords when the module is active.
 */
class Application_Passwords extends Plugin_Component {

	public const MODULE = 'application_passwords';

	/**
	 * {@inheritDoc}
	 */
	protected function add_actions() {}

	/**
	 * {@inheritDoc}
	 */
	protected function add_filters() {
		add_filter( 'wp_is_application_passwords_available', '__return_true', 20 );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function must_run() {
		add_filter( 'lhagentur_available_modules', array( $this, 'add_module' ) );
	}

	/**
	 * Add the module definition.
	 *
	 * @param array $modules The available modules.
	 * @return array The modified modules.
	 */
	public function add_module( $modules ) {
		$modules[] = array(
			'title'       => __( 'Application Passwords', 'lhbasicsp' ),
			'description' => __( 'Enables WordPress application passwords and overrides earlier filters that disable them.', 'lhbasicsp' ),
			'slug'        => self::MODULE,
		);

		return $modules;
	}

	/**
	 * Whether the application passwords module is active.
	 *
	 * @return bool Whether the module is active.
	 */
	protected function is_active() {
		return $this->container()->get( Settings::class )->is_module_active( self::MODULE );
	}
}
