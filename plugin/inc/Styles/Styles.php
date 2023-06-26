<?php
/**
 * LHBASICSP\Styles\Component class
 *
 * @package lhbasicsp
 */

namespace WpMunich\lhbasicsp\Styles;
use WpMunich\lhbasicsp\Component;
use function add_action;
use function WpMunich\lhbasicsp\lh_plugin;

/**
 * A class to enqueue the needed scripts..
 */
class Styles extends Component {

	/**
	 * {@inheritdoc}
	 */
	protected function add_actions() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * {@inheritdoc}
	 */
	protected function add_filters() {}

	/**
	 * Enqueue needed scripts.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		// TODO: Add a check to load the lightbox only when needed.
		// For example: has_block('core/image') and has_block('core/gallery').
		wp_enqueue_style(
			'lhbasicsp',
			lh_plugin()->get_plugin_url() . '/dist/css/lightbox.min.css',
			array(),
			lh_plugin()->get_plugin_version()
		);
	}
}
