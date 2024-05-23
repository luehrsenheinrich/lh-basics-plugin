<?php
/**
 * Holds the Gravity Forms class.
 *
 * @package lhbasicsp
 */

namespace WpMunich\basics\plugin\Gravity_Forms;
use WpMunich\basics\plugin\Plugin_Component;

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
