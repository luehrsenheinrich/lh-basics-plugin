<?php
/**
 * The basic tests for the plugin.
 *
 * @package lhbasicsp
 */

use function WpMunich\lhbasicsp\lh_plugin;

/**
 * Class Lhpbpp_Basic_Test
 */
class LHPlugin_Basic_Test extends WP_UnitTestCase {

	/**
	 * Test if the plugin exists.
	 */
	public function test_plugin_exists() {
		$this->assertTrue( function_exists( 'WpMunich\lhbasicsp\lh_plugin' ) );
	}

	/**
	 * Check if the lhbasicsp file constant is defined.
	 */
	public function test_lhbasicsp_file_constant() {
		$this->assertTrue( defined( 'LHBASICSP_FILE' ) );
	}
}
