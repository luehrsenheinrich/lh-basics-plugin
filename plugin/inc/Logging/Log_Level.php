<?php
/**
 * Log level helpers.
 *
 * @package lhbasics\plugin
 */

namespace WpMunich\basics\plugin\Logging;

/**
 * Normalizes and compares log levels.
 */
class Log_Level {
	public const EMERGENCY = 'emergency';
	public const ALERT     = 'alert';
	public const CRITICAL  = 'critical';
	public const ERROR     = 'error';
	public const WARNING   = 'warning';
	public const NOTICE    = 'notice';
	public const INFO      = 'info';
	public const DEBUG     = 'debug';

	/**
	 * Numeric severities.
	 *
	 * @var array<string,int>
	 */
	private const SEVERITIES = array(
		self::DEBUG     => 100,
		self::INFO      => 200,
		self::NOTICE    => 250,
		self::WARNING   => 300,
		self::ERROR     => 400,
		self::CRITICAL  => 500,
		self::ALERT     => 550,
		self::EMERGENCY => 600,
	);

	/**
	 * Get all levels.
	 *
	 * @return string[]
	 */
	public static function all() {
		return array_keys( self::SEVERITIES );
	}

	/**
	 * Sanitize a log level.
	 *
	 * @param string|null $level The requested level.
	 * @return string The sanitized level.
	 */
	public static function sanitize( $level ) {
		$level = is_string( $level ) ? strtolower( $level ) : self::INFO;

		return in_array( $level, self::all(), true ) ? $level : self::INFO;
	}

	/**
	 * Check whether a level passes the configured threshold.
	 *
	 * @param string $threshold The configured threshold.
	 * @param string $level     The log level.
	 * @return bool Whether the level should be logged.
	 */
	public static function allows( string $threshold, string $level ) {
		$threshold = self::sanitize( $threshold );
		$level     = self::sanitize( $level );

		return self::SEVERITIES[ $level ] >= self::SEVERITIES[ $threshold ];
	}

	/**
	 * Get Monolog's matching numeric level.
	 *
	 * @param string $level The log level.
	 * @return int The Monolog level.
	 */
	public static function to_monolog_level( string $level ) {
		$level = self::sanitize( $level );

		return self::SEVERITIES[ $level ];
	}
}
