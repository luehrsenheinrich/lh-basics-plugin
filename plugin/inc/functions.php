<?php
/**
 * The `wp_lhbasicsp()` function.
 *
 * @package lhbasicsp
 */

namespace WpMunich\lhbasicsp;

/**
 * Provides access to all available functions of the plugin.
 *
 * When called for the first time, the function will initialize the plugin.
 *
 * @return Plugin The main plugin component.
 */
function lh_plugin() {
	static $plugin = null;

	if ( null === $plugin ) {
		$builder   = new \DI\ContainerBuilder();
		$container = $builder->build();

		$plugin = $container->get( Plugin::class );
	}
	return $plugin;
}
