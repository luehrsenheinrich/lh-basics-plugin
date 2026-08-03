<?php
/**
 * Tests for the application passwords module.
 *
 * @package lhbasicsp
 */

use WpMunich\basics\plugin\Application_Passwords\Application_Passwords;

/**
 * Test the application passwords module.
 */
class Test_Application_Passwords extends WP_UnitTestCase {
	/**
	 * Component created by the current test.
	 *
	 * @var Application_Passwords|null
	 */
	private $component = null;

	/**
	 * Prepare a clean module and filter state.
	 */
	public function set_up() {
		parent::set_up();

		update_option( 'active_modules', array() );
		remove_filter( 'wp_is_application_passwords_available', '__return_false', 10 );
		remove_filter( 'wp_is_application_passwords_available', '__return_true', 20 );
	}

	/**
	 * Restore the filter state after each test.
	 */
	public function tear_down() {
		if ( null !== $this->component ) {
			remove_filter( 'lhagentur_available_modules', array( $this->component, 'add_module' ) );
		}

		remove_filter( 'wp_is_application_passwords_available', '__return_false', 10 );
		remove_filter( 'wp_is_application_passwords_available', '__return_true', 20 );
		update_option( 'active_modules', array() );

		parent::tear_down();
	}

	/**
	 * Test that the module is offered in the settings module list.
	 */
	public function test_module_is_available() {
		$modules = apply_filters( 'lhagentur_available_modules', array() );
		$modules = wp_list_filter( $modules, array( 'slug' => Application_Passwords::MODULE ) );

		$this->assertCount( 1, $modules );
	}

	/**
	 * Test that an inactive module does not override application password availability.
	 */
	public function test_inactive_module_does_not_register_override() {
		$this->component = new Application_Passwords();

		$this->assertFalse( has_filter( 'wp_is_application_passwords_available', '__return_true' ) );

		add_filter( 'wp_is_application_passwords_available', '__return_false', 10 );

		$this->assertFalse( apply_filters( 'wp_is_application_passwords_available', true ) );
	}

	/**
	 * Test that the active module overrides Patchstack's equivalent filter.
	 */
	public function test_active_module_overrides_earlier_filter() {
		update_option( 'active_modules', array( Application_Passwords::MODULE ) );
		add_filter( 'wp_is_application_passwords_available', '__return_false', 10 );

		$this->component = new Application_Passwords();

		$this->assertSame( 20, has_filter( 'wp_is_application_passwords_available', '__return_true' ) );
		$this->assertTrue( apply_filters( 'wp_is_application_passwords_available', true ) );
	}
}
