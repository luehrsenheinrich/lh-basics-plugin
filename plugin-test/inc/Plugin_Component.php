<?php
/**
 * This file contains the plugin component class.
 * It is used to define the basic structure of a component, which we use to
 * extend the plugin and the accompanying theme. A component is a class that
 * contains all the logic for a specific feature of the plugin or theme. It combines
 * actions and filters, the logic and helper functions.
 *
 * @package lhbasics\plugin
 */

namespace WpMunich\basicsTest\plugin;

/**
 * Class class for a plugin component.
 */
abstract class Plugin_Component {
	/**
	 * Constructor.
	 * Used to initialize the component and add the needed actions and filters.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->must_run();

		if ( ! $this->is_active() ) {
			return;
		}

		$this->add_actions();
		$this->add_filters();
	}

	/**
	 * Add the needed WordPress actions for the component.
	 *
	 * @see https://codex.wordpress.org/Plugin_API/Action_Reference
	 */
	abstract protected function add_actions();

	/**
	 * Add the needed WordPress filters for the component.
	 *
	 * @see https://codex.wordpress.org/Plugin_API/Filter_Reference
	 */
	abstract protected function add_filters();

	/**
	 * Get the parent class.
	 *
	 * @return Object The parent class.
	 */
	public function get_parent() {
		return get_parent_class( $this );
	}

	/**
	 * Get the DI container.
	 *
	 * @return \DI\Container The DI container.
	 */
	protected function container() {
		return plugin_container();
	}

	/**
	 * If this component is active.
	 *
	 * @return bool True if the component is active, false otherwise.
	 */
	protected function is_active() {
		return true;
	}

	/**
	 * A funtion that is called even when the component is not active.
	 */
	protected function must_run() {
		// Do nothing.
	}
}
