<?php
/**
 * The file that provides access to the plugin object.
 *
 * @package lhbasics\plugin
 */

namespace WpMunich\basics\plugin;

use WpMunich\basics\plugin\Dependencies\DI\ContainerBuilder;
use WpMunich\basics\plugin\Logging\Logger_Factory;
use WpMunich\basics\plugin\Logging\Logger_Interface;
use WpMunich\basics\plugin\Logging\Log_File_Manager;

use function WpMunich\basics\plugin\Dependencies\DI\factory;

/**
 * Provides access to all available functions of the plugin.
 *
 * When called for the first time, the function will initialize the plugin.
 *
 * @return Plugin The main plugin component.
 */
function plugin() {
	static $plugin = null;

	if ( null === $plugin ) {
		/**
		 * The main plugin component.
		 *
		 * @var Plugin $plugin
		 */
		$plugin = plugin_container()->get( Plugin::class );
	}

	return $plugin;
}

/**
 * Provides access to the plugin's DI container.
 *
 * @link https://github.com/PHP-DI/PHP-DI
 * @return \WpMunich\basics\plugin\Dependencies\DI\Container The plugin's DI container.
 */
function plugin_container() {
	static $container = null;

	if ( null === $container ) {
		$builder = new ContainerBuilder();
		$builder->addDefinitions(
			array(
				Logger_Interface::class => factory(
					function ( Logger_Factory $logger_factory, Log_File_Manager $file_manager ) {
						return $logger_factory->create( $file_manager );
					}
				),
			)
		);

		$container = $builder->build();
	}

	return $container;
}
