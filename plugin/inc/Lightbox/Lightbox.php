<?php
/**
 * LHBASICSP\Lightbox\Component class
 *
 * @package lhbasicsp
 */

namespace WpMunich\lhbasicsp\Lightbox;
use WpMunich\lhbasicsp\Component;
use function add_action;
use function WpMunich\lhbasicsp\lh_plugin;

/**
 * A class to handle the lightbox functionality.
 */
class Lightbox extends Component {

	/**
	 * {@inheritdoc}
	 */
	protected function add_actions() {
		if ( $this->is_active() ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}
	}

	/**
	 * {@inheritdoc}
	 */
	protected function add_filters() {}

	/**
	 * If the lightbox feature is an active option.
	 */
	private function is_active() {
		return (bool) get_option( 'lhb_lightbox_active' );
	}

	/**
	 * Enqueue needed scripts and styles for the lightbox.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_enqueue_style(
			'lhbasicsp-lightbox',
			lh_plugin()->get_plugin_url() . '/dist/css/lightbox.min.css',
			array(),
			lh_plugin()->get_plugin_version()
		);

		wp_enqueue_script(
			'lhbasicsp-lightbox',
			lh_plugin()->get_plugin_url() . '/dist/js/lightbox.min.js',
			array(),
			lh_plugin()->get_plugin_version(),
			true
		);
	}
}
