<?php
/**
 * WooCommerce logger adapter.
 *
 * @package lhbasics\plugin
 */

namespace WpMunich\basics\plugin\Logging;

/**
 * Logger backed by WooCommerce's logger.
 */
class WooCommerce_Logger implements Logger_Interface {
	use Logger_Methods;

	/**
	 * WooCommerce logger instance.
	 *
	 * @var object
	 */
	private $logger;

	/**
	 * Minimum severity.
	 *
	 * @var string
	 */
	private $severity;

	/**
	 * Constructor.
	 *
	 * @param object $logger   The WooCommerce logger.
	 * @param string $severity The configured minimum severity.
	 */
	public function __construct( $logger, string $severity ) {
		$this->logger   = $logger;
		$this->severity = Log_Level::sanitize( $severity );
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param string $level   The log level.
	 * @param string $message The log message.
	 * @param array  $context Additional log context.
	 */
	public function log( string $level, string $message, array $context = array() ) {
		$level = Log_Level::sanitize( $level );

		if ( ! Log_Level::allows( $this->severity, $level ) ) {
			return;
		}

		$context['source'] = 'lhbasicsp';

		$this->logger->log( $level, $message, $context );
	}
}
