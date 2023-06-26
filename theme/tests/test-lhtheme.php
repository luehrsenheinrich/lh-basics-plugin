<?php
/**
 * The basic tests for the theme.
 *
 * @package lhpbpt
 */

use function WpMunich\lhpbpt\lh_theme;

/**
 * Class LHTheme_Basic_Test
 */
class LHTheme_Basic_Test extends WP_UnitTestCase {

	/**
	 * Test if the theme exists.
	 */
	public function test_theme_exists() {
		$this->assertTrue( function_exists( 'WpMunich\lhpbpt\lh_theme' ) );
	}
}
