<?php
/**
 * Log file access and hardening.
 *
 * @package lhbasics\plugin
 */

namespace WpMunich\basics\plugin\Logging;

/**
 * Manages log file paths for the active logging backend.
 */
class Log_File_Manager {
	private const LOG_SOURCE = 'lhbasicsp';
	private const MAX_BYTES  = 524288;

	/**
	 * Get the current backend name.
	 *
	 * @return string The backend name.
	 */
	public function get_backend() {
		return $this->uses_woocommerce() ? 'woocommerce' : 'monolog';
	}

	/**
	 * Get the current log file path.
	 *
	 * @return string The log file path.
	 */
	public function get_log_file_path() {
		if ( $this->uses_woocommerce() ) {
			$woocommerce_path = $this->get_woocommerce_log_file_path();

			if ( $woocommerce_path ) {
				return $woocommerce_path;
			}
		}

		return $this->get_monolog_log_file_path();
	}

	/**
	 * Prepare the Monolog fallback file for writing.
	 *
	 * @return string The writable log file path.
	 */
	public function prepare_monolog_log_file() {
		$directory = $this->get_monolog_log_directory();

		if ( ! file_exists( $directory ) ) {
			wp_mkdir_p( $directory );
		}

		$this->harden_directory( $directory );

		$path = $this->get_monolog_log_file_path();

		if ( ! file_exists( $path ) && is_writable( $directory ) ) {
			touch( $path );
		}

		$this->harden_file( $path );

		return $path;
	}

	/**
	 * Read the current log file.
	 *
	 * @return array<string,mixed> The log data.
	 */
	public function read_current_log() {
		$path = $this->get_log_file_path();

		if ( ! file_exists( $path ) || ! is_readable( $path ) ) {
			return array(
				'backend' => $this->get_backend(),
				'content' => '',
				'exists'  => false,
				'path'    => $path,
				'size'    => 0,
			);
		}

		$size   = filesize( $path );
		$handle = fopen( $path, 'rb' );

		if ( false === $handle ) {
			return array(
				'backend' => $this->get_backend(),
				'content' => '',
				'exists'  => true,
				'path'    => $path,
				'size'    => $size,
			);
		}

		if ( $size > self::MAX_BYTES ) {
			fseek( $handle, -1 * self::MAX_BYTES, SEEK_END );
		}

		$content = stream_get_contents( $handle );
		fclose( $handle );

		return array(
			'backend'   => $this->get_backend(),
			'content'   => false === $content ? '' : $content,
			'exists'    => true,
			'isTrimmed' => $size > self::MAX_BYTES,
			'path'      => $path,
			'size'      => $size,
		);
	}

	/**
	 * Delete the current log file.
	 *
	 * @return bool Whether a file was deleted.
	 */
	public function clear_current_log() {
		if ( $this->uses_woocommerce() ) {
			$deleted = false;

			foreach ( $this->get_woocommerce_log_file_paths( true ) as $path ) {
				if ( file_exists( $path ) ) {
					$deleted = wp_delete_file( $path ) || $deleted;
				}
			}

			return $deleted;
		}

		$path = $this->get_monolog_log_file_path();

		if ( ! file_exists( $path ) ) {
			return false;
		}

		return wp_delete_file( $path );
	}

	/**
	 * Whether WooCommerce logging is available.
	 *
	 * @return bool Whether WooCommerce should be used.
	 */
	public function uses_woocommerce() {
		return function_exists( 'wc_get_logger' );
	}

	/**
	 * Get the Monolog log directory.
	 *
	 * @return string The directory path.
	 */
	private function get_monolog_log_directory() {
		$upload_dir = wp_upload_dir();
		$base_dir   = $upload_dir['basedir'] ?? WP_CONTENT_DIR . '/uploads';

		return trailingslashit( $base_dir ) . 'lh';
	}

	/**
	 * Get the Monolog log file path.
	 *
	 * @return string The file path.
	 */
	private function get_monolog_log_file_path() {
		return trailingslashit( $this->get_monolog_log_directory() ) . 'lh.log';
	}

	/**
	 * Get the current WooCommerce log file path.
	 *
	 * @return string|null The log file path, if known.
	 */
	private function get_woocommerce_log_file_path() {
		$paths = $this->get_woocommerce_log_file_paths( false );

		return $paths[0] ?? null;
	}

	/**
	 * Get WooCommerce log file paths for this plugin source.
	 *
	 * @param bool $existing_only Whether to only return files that already exist.
	 * @return string[] The log file paths.
	 */
	private function get_woocommerce_log_file_paths( bool $existing_only ) {
		$paths = array();

		if ( class_exists( 'WC_Log_Handler_File' ) && method_exists( 'WC_Log_Handler_File', 'get_log_file_path' ) ) {
			$paths[] = \WC_Log_Handler_File::get_log_file_path( self::LOG_SOURCE );
		}

		if ( defined( 'WC_LOG_DIR' ) ) {
			$globbed_paths = glob( trailingslashit( WC_LOG_DIR ) . self::LOG_SOURCE . '-*.log' );

			if ( is_array( $globbed_paths ) ) {
				$paths = array_merge( $paths, $globbed_paths );
			}
		}

		$paths = array_values( array_unique( array_filter( $paths ) ) );

		if ( $existing_only ) {
			$paths = array_values(
				array_filter(
					$paths,
					function ( $path ) {
						return file_exists( $path );
					}
				)
			);
		}

		usort(
			$paths,
			function ( $left, $right ) {
				return ( file_exists( $right ) ? filemtime( $right ) : 0 ) <=> ( file_exists( $left ) ? filemtime( $left ) : 0 );
			}
		);

		return $paths;
	}

	/**
	 * Harden a log directory against public reads where supported.
	 *
	 * @param string $directory The directory path.
	 * @return void
	 */
	private function harden_directory( string $directory ) {
		if ( is_dir( $directory ) && is_writable( $directory ) ) {
			chmod( $directory, 0700 );
		}

		$this->write_guard_file( trailingslashit( $directory ) . 'index.php', "<?php\n// Silence is golden.\n" );
		$this->write_guard_file(
			trailingslashit( $directory ) . '.htaccess',
			"Order deny,allow\nDeny from all\n<IfModule mod_authz_core.c>\nRequire all denied\n</IfModule>\n"
		);
		$this->write_guard_file(
			trailingslashit( $directory ) . 'web.config',
			"<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<configuration><system.webServer><authorization><deny users=\"*\" /></authorization></system.webServer></configuration>\n"
		);
	}

	/**
	 * Harden a log file against public reads where supported.
	 *
	 * @param string $path The file path.
	 * @return void
	 */
	private function harden_file( string $path ) {
		if ( file_exists( $path ) && is_writable( $path ) ) {
			chmod( $path, 0600 );
		}
	}

	/**
	 * Write a guard file if it does not exist.
	 *
	 * @param string $path    The file path.
	 * @param string $content The file content.
	 * @return void
	 */
	private function write_guard_file( string $path, string $content ) {
		if ( file_exists( $path ) ) {
			return;
		}

		if ( ! is_writable( dirname( $path ) ) ) {
			return;
		}

		file_put_contents( $path, $content );
		$this->harden_file( $path );
	}
}
