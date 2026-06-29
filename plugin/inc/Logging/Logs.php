<?php
/**
 * Logs module component.
 *
 * @package lhbasics\plugin
 */

namespace WpMunich\basics\plugin\Logging;

use WpMunich\basics\plugin\Plugin_Component;
use WpMunich\basics\plugin\Settings\Settings;

/**
 * Registers logging settings and admin REST endpoints.
 */
class Logs extends Plugin_Component {
	public const MODULE          = 'logs';
	public const SEVERITY_OPTION = 'lhbasicsp_log_severity';

	/**
	 * Log file manager.
	 *
	 * @var Log_File_Manager
	 */
	private $file_manager;

	/**
	 * Constructor.
	 *
	 * @param Log_File_Manager $file_manager The log file manager.
	 */
	public function __construct( Log_File_Manager $file_manager ) {
		$this->file_manager = $file_manager;

		parent::__construct();
	}

	/**
	 * {@inheritDoc}
	 */
	protected function add_actions() {
		add_action( 'init', array( $this, 'register_settings' ) );
		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function add_filters() {}

	/**
	 * {@inheritDoc}
	 */
	protected function must_run() {
		add_filter( 'lhagentur_available_modules', array( $this, 'add_module' ) );
	}

	/**
	 * Whether the logs module is active.
	 *
	 * @return bool Whether the logs module is active.
	 */
	protected function is_active() {
		return $this->container()->get( Settings::class )->is_module_active( self::MODULE );
	}

	/**
	 * Add the module definition.
	 *
	 * @param array $modules The available modules.
	 * @return array The modified modules.
	 */
	public function add_module( $modules ) {
		$modules[] = array(
			'title'       => __( 'Logs', 'lhbasicsp' ),
			'description' => __( 'Enables plugin logs and the log viewer.', 'lhbasicsp' ),
			'slug'        => self::MODULE,
		);

		return $modules;
	}

	/**
	 * Register logging settings.
	 *
	 * @return void
	 */
	public function register_settings() {
		register_setting(
			'lhagentur',
			self::SEVERITY_OPTION,
			array(
				'type'              => 'string',
				'description'       => 'The minimum severity for plugin logs.',
				'sanitize_callback' => array( Log_Level::class, 'sanitize' ),
				'show_in_rest'      => array(
					'schema' => array(
						'type' => 'string',
						'enum' => Log_Level::all(),
					),
				),
				'default'           => Log_Level::INFO,
			)
		);
	}

	/**
	 * Register REST routes for the admin log viewer.
	 *
	 * @return void
	 */
	public function register_rest_routes() {
		register_rest_route(
			'lhbasicsp/v1',
			'/logs',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_logs' ),
					'permission_callback' => array( $this, 'can_manage_logs' ),
				),
				array(
					'methods'             => \WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'clear_logs' ),
					'permission_callback' => array( $this, 'can_manage_logs' ),
				),
			)
		);
	}

	/**
	 * Check whether the current user can manage logs.
	 *
	 * @return bool Whether the current user can manage logs.
	 */
	public function can_manage_logs() {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Get current log data.
	 *
	 * @return \WP_REST_Response The response.
	 */
	public function get_logs() {
		return rest_ensure_response( $this->get_log_data() );
	}

	/**
	 * Clear the current log file.
	 *
	 * @return \WP_REST_Response The response.
	 */
	public function clear_logs() {
		$deleted = $this->file_manager->clear_current_log();
		$data    = $this->get_log_data();

		$data['deleted'] = $deleted;

		return rest_ensure_response( $data );
	}

	/**
	 * Get log data for REST responses.
	 *
	 * @return array<string,mixed> The log data.
	 */
	private function get_log_data() {
		$data = $this->file_manager->read_current_log();

		$data['levels']   = Log_Level::all();
		$data['severity'] = Log_Level::sanitize( get_option( self::SEVERITY_OPTION, Log_Level::INFO ) );

		return $data;
	}
}
