<?php
/**
 * The basic tests for the plugin.
 *
 * @package lhbasicsp
 */

use function WpMunich\basics\plugin\plugin;
use function WpMunich\basics\plugin\plugin_container;

/**
 * Class lhbasicsp_Basic_Test
 */
class Test_LHBasicp extends WP_UnitTestCase {

	/**
	 * Test if the plugin exists.
	 */
	public function test_plugin_exists() {
		$this->assertTrue( function_exists( 'WpMunich\basics\plugin\plugin' ) );
	}

	/**
	 * Check if the lhbasicsp file constant is defined.
	 */
	public function test_lhbasicsp_file_constant() {
		$this->assertTrue( defined( 'LHBASICSP_FILE' ) );
	}

	/**
	 * Module activation can be overridden by the active theme.
	 */
	public function test_module_activation_override_after_setup_theme() {
		$lightbox = plugin_container()->get( WpMunich\basics\plugin\Lightbox\Lightbox::class );

		$this->assertNotFalse(
			has_action( 'wp_enqueue_scripts', array( $lightbox, 'enqueue_scripts' ) )
		);
	}
}
