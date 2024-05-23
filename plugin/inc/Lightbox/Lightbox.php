<?php
/**
 * LHBASICSP\Lightbox\Component class
 *
 * @package lhbasicsp
 */

namespace WpMunich\basics\plugin\Lightbox;
use WpMunich\basics\plugin\Plugin_Component;
use function add_action;
use function WpMunich\basics\plugin\plugin;

/**
 * A class to handle the lightbox functionality.
 */
class Lightbox extends Plugin_Component {

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
	protected function is_active() {
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
			plugin()->get_plugin_url() . '/dist/css/lightbox.min.css',
			array(),
			plugin()->get_plugin_version()
		);

		wp_enqueue_script(
			'lhbasicsp-lightbox',
			plugin()->get_plugin_url() . '/dist/js/lightbox.min.js',
			array(),
			plugin()->get_plugin_version(),
			true
		);
	}
}
