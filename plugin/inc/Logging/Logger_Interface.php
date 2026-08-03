<?php
/**
 * Logger interface.
 *
 * @package lhbasics\plugin
 */

namespace WpMunich\basics\plugin\Logging;

/**
 * Internal logger contract.
 */
interface Logger_Interface {
	/**
	 * System is unusable.
	 *
	 * @param string $message The log message.
	 * @param array  $context Additional log context.
	 */
	public function emergency( string $message, array $context = array() );

	/**
	 * Action must be taken immediately.
	 *
	 * @param string $message The log message.
	 * @param array  $context Additional log context.
	 */
	public function alert( string $message, array $context = array() );

	/**
	 * Critical conditions.
	 *
	 * @param string $message The log message.
	 * @param array  $context Additional log context.
	 */
	public function critical( string $message, array $context = array() );

	/**
	 * Runtime errors.
	 *
	 * @param string $message The log message.
	 * @param array  $context Additional log context.
	 */
	public function error( string $message, array $context = array() );

	/**
	 * Exceptional occurrences that are not errors.
	 *
	 * @param string $message The log message.
	 * @param array  $context Additional log context.
	 */
	public function warning( string $message, array $context = array() );

	/**
	 * Normal but significant events.
	 *
	 * @param string $message The log message.
	 * @param array  $context Additional log context.
	 */
	public function notice( string $message, array $context = array() );

	/**
	 * Interesting events.
	 *
	 * @param string $message The log message.
	 * @param array  $context Additional log context.
	 */
	public function info( string $message, array $context = array() );

	/**
	 * Detailed debug information.
	 *
	 * @param string $message The log message.
	 * @param array  $context Additional log context.
	 */
	public function debug( string $message, array $context = array() );

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param string $level   The log level.
	 * @param string $message The log message.
	 * @param array  $context Additional log context.
	 */
	public function log( string $level, string $message, array $context = array() );
}
