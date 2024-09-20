<?php
/**
 * Holds the Gravity Forms class.
 *
 * @package lhbasicsp
 */

namespace WpMunich\basics\plugin\Gravity_Forms;
use WpMunich\basics\plugin\Plugin_Component;
use WpMunich\basics\plugin\Settings\Settings;

/**
 * A class to change aspects of Gravity Forms.
 */
class Gravity_Forms extends Plugin_Component {
	/**
	 * {@inheritdoc}
	 */
	public function add_actions() {}

	/**
	 * {@inheritdoc}
	 */
	public function add_filters() {
		add_filter( 'gform_address_display_format', array( $this, 'gform_address_display_format' ) );
	}

	/**
	 * {@inheritdoc}
	 */
	public function must_run() {
		add_filter( 'lhagentur_available_modules', array( $this, 'add_module' ) );
	}

	/**
	 * Add the module defintion for this component.
	 *
	 * @param array $modules The available modules.
	 *
	 * @return array
	 */
	public function add_module( $modules ) {
		$modules[] = array(
			'title'       => __( 'Gravity Forms', 'lhbasicsp' ),
			'description' => __( 'This module changes aspects of Gravity Forms.', 'lhbasicsp' ),
			'slug'        => 'gravity_forms',
		);

		return $modules;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function is_active() {
		return $this->container()->get( Settings::class )->is_module_active( 'gravity_forms' );
	}

	/**
	 * Change the address format for Gravity Forms.
	 *
	 * @param string $format The address format.
	 *
	 * @return string
	 */
	public function gform_address_display_format( $format ) {
		return 'zip_before_city';
	}
}
