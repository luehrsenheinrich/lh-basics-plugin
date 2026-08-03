<?php
/**
 * Monolog adapter.
 *
 * @package lhbasics\plugin
 */

namespace WpMunich\basics\plugin\Logging;

use WpMunich\basics\plugin\Dependencies\Monolog\Handler\StreamHandler;
use WpMunich\basics\plugin\Dependencies\Monolog\Logger;

/**
 * Logger backed by the prefixed Monolog package.
 */
class Monolog_Logger implements Logger_Interface {
	use Logger_Methods;

	/**
	 * Monolog logger instance.
	 *
	 * @var Logger
	 */
	private $logger;

	/**
	 * Constructor.
	 *
	 * @param Log_File_Manager $file_manager The log file manager.
	 * @param string           $severity     The configured minimum severity.
	 */
	public function __construct( Log_File_Manager $file_manager, string $severity ) {
		$this->logger = new Logger( 'lhbasicsp' );
		$this->logger->pushHandler(
			new StreamHandler(
				$file_manager->prepare_monolog_log_file(),
				Log_Level::to_monolog_level( $severity )
			)
		);
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param string $level   The log level.
	 * @param string $message The log message.
	 * @param array  $context Additional log context.
	 */
	public function log( string $level, string $message, array $context = array() ) {
		$this->logger->log( Log_Level::sanitize( $level ), $message, $context );
	}
}
