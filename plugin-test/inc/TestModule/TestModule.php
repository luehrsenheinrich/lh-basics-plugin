<?php
/**
 * Holds the TestModule class.
 *
 * @package lhbasicsp
 */

namespace WpMunich\basicsTest\plugin\TestModule;
use WpMunich\basicsTest\plugin\Plugin_Component;
use function WpMunich\basicsTest\plugin\plugin;
use function add_action;
use function load_plugin_textdomain;

/**
 * A class to handle textdomains and other module related logic...
 */
class TestModule extends Plugin_Component {

	/**
	 * {@inheritDoc}
	 */
	protected function add_actions() {
	}

	/**
	 * {@inheritDoc}
	 */
	protected function add_filters() {
		add_filter( 'lhagentur_available_modules', array( $this, 'add_test_module_to_available_modules' ), 11, 1 );
	}

	/**
	 * Add a test module.
	 *
	 * @param array $modules The available modules.
	 *
	 * @return array The available modules.
	 */
	public function add_test_module_to_available_modules( $modules ) {
		$modules[] = array(
			'title'       => __( 'Test Module', 'lhbasicsp' ),
			'description' => __( 'This is a test module, added by the test plugin.', 'lhbasicsp' ),
			'slug'        => 'test-module',
		);

		return $modules;
	}
}
