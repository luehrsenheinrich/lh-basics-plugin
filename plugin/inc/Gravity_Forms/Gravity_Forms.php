<?php
/**
 * LHBASICSP\Gravity_Forms\Component class
 *
 * @package lhbasicsp
 */

namespace WpMunich\lhbasicsp\Gravity_Forms;
use WpMunich\lhbasicsp\Component;

use function add_action;
use function add_theme_support;

/**
 * Add theme supports.
 */
class Gravity_Forms extends Component {
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
