<?php
/**
 * The SVG REST class.
 *
 * This file defines the `REST` class, which registers and manages custom REST API endpoints
 * for SVG icons.
 *
 * @package lhbasicsp
 */

namespace WpMunich\basics\plugin\SVG;

use WP_REST_Server;
use WP_REST_Request;
use WP_REST_Response;

use function WpMunich\basics\plugin\plugin;
use function add_action;
use function apply_filters;
use function register_rest_route;
use function rest_ensure_response;
use function wp_parse_args;

/**
 * REST
 *
 * A class to register and manage SVG/icon custom REST API endpoints.
 */
class REST {

	/**
	 * The namespace for REST endpoints in this component.
	 *
	 * @var string
	 */
	private string $rest_namespace = 'lhbasics/v1';

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->add_actions();
	}

	/**
	 * Add WordPress actions.
	 *
	 * @return void
	 */
	protected function add_actions() {
		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );
	}

	/**
	 * Register custom REST API routes.
	 *
	 * @return void
	 */
	public function register_rest_routes() {
		register_rest_route(
			$this->rest_namespace,
			'/icons/',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'rest_get_icons' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'page'     => array(
						'required'          => false,
						'default'           => 1,
						'validate_callback' => 'absint',
					),
					'per_page' => array(
						'required'          => false,
						'default'           => 10,
						'validate_callback' => function( $value ) {
							$value = absint( $value );
							return $value > 0 && $value <= 100 ? $value : 100;
						},
					),
				),
			)
		);

		register_rest_route(
			$this->rest_namespace,
			'/icon/(?P<slug>[a-z0-9-]+)/',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'rest_get_icon' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'slug' => array(
						'required' => true,
						'type'     => 'string',
					),
				),
			)
		);
	}

	/**
	 * Retrieve all icons with pagination.
	 *
	 * @param WP_REST_Request $request The request.
	 * @return WP_REST_Response The response containing paginated icon data.
	 */
	public function rest_get_icons( WP_REST_Request $request ): WP_REST_Response {
		$slugs     = $request->get_param( 'slugs' );
		$lib_icons = plugin()->svg()->get_icon_library()->get_icons();

		$page        = max( 1, $request->get_param( 'page' ) );
		$per_page    = min( max( 1, $request->get_param( 'per_page' ) ), 100 );
		$total_icons = count( $lib_icons );
		$total_pages = (int) ceil( $total_icons / $per_page );

		if ( $slugs ) {
			$slugs     = explode( ',', $slugs );
			$lib_icons = array_filter(
				$lib_icons,
				function( $icon ) use ( $slugs ) {
					return in_array( $icon->get_slug(), $slugs, true );
				}
			);
		}

		$offset      = ( $page - 1 ) * $per_page;
		$paged_icons = array_slice( $lib_icons, $offset, $per_page );

		$res_icons = array_map(
			function( $icon ) {
				return wp_parse_args(
					$icon->jsonSerialize( array( 'slug', 'title' ) ),
					array( 'svg' => plugin()->svg()->get_svg( $icon->get_slug() ) )
				);
			},
			$paged_icons
		);

		$response = rest_ensure_response( $res_icons );
		$response->header( 'X-WP-Total', $total_icons );
		$response->header( 'X-WP-TotalPages', $total_pages );
		$response->header( 'X-WP-Page', $page );

		return $response;
	}

	/**
	 * Retrieve a single icon by slug via REST.
	 *
	 * @param WP_REST_Request $request The request.
	 * @return WP_REST_Response The response containing the requested icon data.
	 */
	public function rest_get_icon( WP_REST_Request $request ): WP_REST_Response {
		$slug = $request->get_param( 'slug' );
		$svg  = plugin()->svg()->get_svg( $slug );
		$icon = $slug && $svg ? plugin()->svg()->get_icon_library()->get_icon( $slug )->jsonSerialize( array( 'slug', 'title' ) ) : array();

		$response = apply_filters(
			'basicsp_rest_get_svg_response',
			wp_parse_args( $icon, array( 'svg' => $svg ) ),
			$slug
		);

		return rest_ensure_response( $response );
	}
}
