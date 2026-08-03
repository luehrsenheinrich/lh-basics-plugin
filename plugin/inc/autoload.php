<?php
/**
 * Autoload plugin classes without exposing Composer's runtime autoloader.
 *
 * @package lhbasics\plugin
 */

spl_autoload_register(
	function ( $class_name ) {
		$prefix = 'WpMunich\\basics\\plugin\\';

		if ( 0 !== strpos( $class_name, $prefix ) ) {
			return;
		}

		$relative_class = substr( $class_name, strlen( $prefix ) );
		$file           = __DIR__ . '/' . str_replace( '\\', '/', $relative_class ) . '.php';

		if ( file_exists( $file ) ) {
			require $file;
		}
	}
);
