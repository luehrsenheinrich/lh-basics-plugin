<?php
/**
 * Holds the Lightbox class.
 *
 * @package lhbasicsp
 */

namespace WpMunich\basics\plugin\Lightbox;
use WpMunich\basics\plugin\Plugin_Component;
use WpMunich\basics\plugin\Settings\Settings;

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
	 * {@inheritdoc}
	 */
	protected function must_run() {
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
			'title'       => __( 'Lightbox', 'lhbasicsp' ),
			'description' => __( 'This module enables a lightbox for images.', 'lhbasicsp' ),
			'slug'        => 'lightbox',
		);

		return $modules;
	}

	/**
	 * If the lightbox feature is an active option.
	 */
	protected function is_active() {
		return $this->container()->get( Settings::class )->is_module_active( 'lightbox' );
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
