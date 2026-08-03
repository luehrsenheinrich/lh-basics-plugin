<?php
/**
 * Common logger level methods.
 *
 * @package lhbasics\plugin
 */

namespace WpMunich\basics\plugin\Logging;

/**
 * Implements standard level helper methods through log().
 */
trait Logger_Methods {
	/**
	 * Log an emergency message.
	 *
	 * @param string $message The log message.
	 * @param array  $context Additional log context.
	 */
	public function emergency( string $message, array $context = array() ) {
		$this->log( Log_Level::EMERGENCY, $message, $context );
	}

	/**
	 * Log an alert message.
	 *
	 * @param string $message The log message.
	 * @param array  $context Additional log context.
	 */
	public function alert( string $message, array $context = array() ) {
		$this->log( Log_Level::ALERT, $message, $context );
	}

	/**
	 * Log a critical message.
	 *
	 * @param string $message The log message.
	 * @param array  $context Additional log context.
	 */
	public function critical( string $message, array $context = array() ) {
		$this->log( Log_Level::CRITICAL, $message, $context );
	}

	/**
	 * Log an error message.
	 *
	 * @param string $message The log message.
	 * @param array  $context Additional log context.
	 */
	public function error( string $message, array $context = array() ) {
		$this->log( Log_Level::ERROR, $message, $context );
	}

	/**
	 * Log a warning message.
	 *
	 * @param string $message The log message.
	 * @param array  $context Additional log context.
	 */
	public function warning( string $message, array $context = array() ) {
		$this->log( Log_Level::WARNING, $message, $context );
	}

	/**
	 * Log a notice message.
	 *
	 * @param string $message The log message.
	 * @param array  $context Additional log context.
	 */
	public function notice( string $message, array $context = array() ) {
		$this->log( Log_Level::NOTICE, $message, $context );
	}

	/**
	 * Log an info message.
	 *
	 * @param string $message The log message.
	 * @param array  $context Additional log context.
	 */
	public function info( string $message, array $context = array() ) {
		$this->log( Log_Level::INFO, $message, $context );
	}

	/**
	 * Log a debug message.
	 *
	 * @param string $message The log message.
	 * @param array  $context Additional log context.
	 */
	public function debug( string $message, array $context = array() ) {
		$this->log( Log_Level::DEBUG, $message, $context );
	}
}
