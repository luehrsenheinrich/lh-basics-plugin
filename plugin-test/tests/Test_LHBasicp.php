<?php
/**
 * The basic tests for the plugin.
 *
 * @package lhbasicsp
 */

use function WpMunich\basics\plugin\plugin;

/**
 * Class lhbasicsp_Basic_Test
 */
class Test_LHBasicp extends WP_UnitTestCase {

	/**
	 * Test if the plugin exists.
	 */
	public function test_plugin_exists() {
		$this->assertTrue( function_exists( 'WpMunich\basicsTest\plugin\plugin' ) );
	}

	/**
	 * Check if the lhbasicsp file constant is defined.
	 */
	public function test_lhbasicsp_file_constant() {
		$this->assertTrue( defined( 'LHBASICSTESTP_FILE' ) );
	}
}
