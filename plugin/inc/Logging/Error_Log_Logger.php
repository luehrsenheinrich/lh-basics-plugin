<?php
/**
 * PHP error_log fallback adapter.
 *
 * @package lhbasics\plugin
 */

namespace WpMunich\basics\plugin\Logging;

/**
 * Last-resort logger for unwritable file targets.
 */
class Error_Log_Logger implements Logger_Interface {
	use Logger_Methods;

	/**
	 * Minimum severity.
	 *
	 * @var string
	 */
	private $severity;

	/**
	 * Constructor.
	 *
	 * @param string $severity The configured minimum severity.
	 */
	public function __construct( string $severity ) {
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

		error_log( sprintf( '[lhbasicsp] %s: %s %s', strtoupper( $level ), $message, wp_json_encode( $context ) ) );
	}
}
