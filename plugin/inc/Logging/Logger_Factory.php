<?php
/**
 * Logger factory.
 *
 * @package lhbasics\plugin
 */

namespace WpMunich\basics\plugin\Logging;

/**
 * Creates the active logger adapter.
 */
class Logger_Factory {
	/**
	 * Create the active logger.
	 *
	 * @param Log_File_Manager $file_manager The log file manager.
	 * @return Logger_Interface The active logger.
	 */
	public function create( Log_File_Manager $file_manager ) {
		$severity = Log_Level::sanitize( get_option( Logs::SEVERITY_OPTION, Log_Level::INFO ) );

		if ( $file_manager->uses_woocommerce() ) {
			return new WooCommerce_Logger( wc_get_logger(), $severity );
		}

		try {
			return new Monolog_Logger( $file_manager, $severity );
		} catch ( \Throwable $throwable ) {
			return new Error_Log_Logger( $severity );
		}
	}
}
