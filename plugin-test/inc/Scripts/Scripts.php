<?php
/**
 * Holds the Scripts class.
 *
 * @package lhbasicsp
 */

namespace WpMunich\basicsTest\plugin\Scripts;
use WpMunich\basicsTest\plugin\Plugin_Component;
use function WpMunich\basicsTest\plugin\plugin;

/**
 * A class to handle textdomains and other scripts related logic...
 */
class Scripts extends Plugin_Component {

	/**
	 * {@inheritDoc}
	 */
	protected function add_actions() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function add_filters() {}

	/**
	 * Enqueue assets.
	 *
	 * @return void
	 */
	public function enqueue_assets() {
		$screen = get_current_screen();
		$assets = wp_json_file_decode( plugin()->get_plugin_path() . '/admin/dist/assets.json', array( 'associative' => true ) );

		$scipt_assets = $assets['js/script.min.js'];

		wp_enqueue_script(
			'lhbasicsp-test',
			plugin()->get_plugin_url() . '/admin/dist/js/script.min.js',
			array_merge( $scipt_assets['dependencies'], array( 'lhbasics' ) ),
			$scipt_assets['version'],
			true
		);
	}
}
