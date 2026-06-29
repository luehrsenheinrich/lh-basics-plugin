<?php
/**
 * Log tester component.
 *
 * @package lhbasics\plugin-test
 */

namespace WpMunich\basicsTest\plugin\LogTester;

use WpMunich\basicsTest\plugin\Plugin_Component;

/**
 * Adds a REST endpoint that writes a test log entry.
 */
class LogTester extends Plugin_Component {
	/**
	 * {@inheritDoc}
	 */
	protected function add_actions() {
		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function add_filters() {}

	/**
	 * Register REST routes.
	 *
	 * @return void
	 */
	public function register_rest_routes() {
		register_rest_route(
			'lhbasicsp-test/v1',
			'/log',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'create_log_entry' ),
				'permission_callback' => array( $this, 'can_create_log_entry' ),
			)
		);
	}

	/**
	 * Check whether the current user can create a test log entry.
	 *
	 * @return bool Whether the current user can create a test log entry.
	 */
	public function can_create_log_entry() {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Create a test log entry.
	 *
	 * @return \WP_REST_Response|\WP_Error The REST response.
	 */
	public function create_log_entry() {
		if ( ! function_exists( 'WpMunich\basics\plugin\plugin' ) ) {
			return new \WP_Error(
				'lhbasicsp_missing',
				__( 'The L//H Basics plugin is not available.', 'lhbasicsp' ),
				array( 'status' => 500 )
			);
		}

		$severity = $this->get_current_log_severity();

		\WpMunich\basics\plugin\plugin()->logger()->log(
			$severity,
			'Test log entry created from the L//H Basics Test plugin.',
			array(
				'severity' => $severity,
				'source'   => 'lhbasicsp-test',
				'time'     => current_time( 'mysql' ),
			)
		);

		return rest_ensure_response(
			array(
				'success' => true,
				'message' => sprintf(
					/* translators: %s: log severity. */
					__( 'Test %s log entry created.', 'lhbasicsp' ),
					$severity
				),
			)
		);
	}

	/**
	 * Get the current configured log severity.
	 *
	 * @return string The current log severity.
	 */
	private function get_current_log_severity() {
		if ( class_exists( 'WpMunich\basics\plugin\Logging\Logs' ) && class_exists( 'WpMunich\basics\plugin\Logging\Log_Level' ) ) {
			return \WpMunich\basics\plugin\Logging\Log_Level::sanitize(
				get_option(
					\WpMunich\basics\plugin\Logging\Logs::SEVERITY_OPTION,
					\WpMunich\basics\plugin\Logging\Log_Level::INFO
				)
			);
		}

		return 'info';
	}
}
