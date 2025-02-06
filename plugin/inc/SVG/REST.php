<?php
/**
 * The SVG REST class.
 *
 * This file defines the REST class, which registers and manages custom REST API endpoints
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
	 * The REST namespace.
	 *
	 * This string is used as the base namespace for all REST endpoints in this component.
	 *
	 * @var string
	 */
	private string $rest_namespace = 'lhbasics/v1';

	/**
	 * Constructor.
	 *
	 * Initializes the REST endpoints by hooking into the appropriate WordPress actions.
	 */
	public function __construct() {
		$this->add_actions();
	}

	/**
	 * Add WordPress actions.
	 *
	 * Hooks the method to register REST routes into the 'rest_api_init' action.
	 *
	 * @return void
	 */
	protected function add_actions() {
		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );
	}

	/**
	 * Register custom REST API routes.
	 *
	 * Registers two endpoints:
	 * - /icons/ for retrieving a list of icons with pagination.
	 * - /icon/(?P<slug>[a-z0-9-]+)/ for retrieving a single icon by slug.
	 *
	 * @return void
	 */
	public function register_rest_routes() {
		// Register endpoint for retrieving paginated icons.
		register_rest_route(
			$this->rest_namespace,
			'/icons/',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'rest_get_icons' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'page'         => array(
						'required'          => false,
						'default'           => 1,
						'validate_callback' => 'absint',
					),
					'per_page'     => array(
						'required'          => false,
						'default'           => 10,
						'validate_callback' => function( $value ) {
							$value = absint( $value );
							return $value > 0 && $value <= 100 ? $value : 100;
						},
					),
					'search'       => array(
						'required' => false,
						'type'     => 'string',
					),
					'must_include' => array(
						'required' => false,
						'type'     => 'string',
					),
				),
			)
		);

		// Register endpoint for retrieving a single icon by slug.
		register_rest_route(
			$this->rest_namespace,
			'/icons/(?P<slug>[a-z0-9-]+)/',
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

		// Keep the old route for compatibility reasons.
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
	 * This method handles requests to the /icons/ endpoint. It optionally filters icons
	 * by a search term and/or a comma-separated list of slugs, then paginates the result.
	 *
	 * @param WP_REST_Request $request The current REST API request.
	 * @return WP_REST_Response The response containing paginated icon data.
	 */
	public function rest_get_icons( WP_REST_Request $request ): WP_REST_Response {
		// Retrieve parameters from the request.
		$slugs     = $request->get_param( 'slugs' );
		$search    = $request->get_param( 'search' );
		$lib_icons = plugin()->svg()->get_icon_library()->get_icons();

		// If a search term is provided, filter icons whose titles contain the term.
		if ( $search ) {
			$lib_icons = array_filter(
				$lib_icons,
				function( $icon ) use ( $search ) {
					return false !== stripos( $icon->get_title(), $search );
				}
			);
		}

		// Retrieve pagination parameters.
		$page        = max( 1, (int) $request->get_param( 'page' ) );
		$per_page    = min( max( 1, (int) $request->get_param( 'per_page' ) ), 100 );
		$total_icons = count( $lib_icons );
		$total_pages = (int) ceil( $total_icons / $per_page );

		// If specific slugs are provided, filter icons to only include those.
		if ( $slugs ) {
			$slugs     = explode( ',', $slugs );
			$lib_icons = array_filter(
				$lib_icons,
				function( $icon ) use ( $slugs ) {
					return in_array( $icon->get_slug(), $slugs, true );
				}
			);
		}

		// Determine the offset and slice the icons array for the current page.
		$offset      = ( $page - 1 ) * $per_page;
		$paged_icons = array_slice( $lib_icons, $offset, $per_page );

		// Prepare icons for the response by merging JSON data with the SVG markup.
		$res_icons = array_map(
			function( $icon ) {
				return wp_parse_args(
					$icon->jsonSerialize( array( 'slug', 'title' ) ),
					array(
						'svg' => plugin()->svg()->get_svg( $icon->get_slug() ),
					)
				);
			},
			$paged_icons
		);

		// Create the REST response and add pagination headers.
		$response = rest_ensure_response( $res_icons );
		$response->header( 'X-WP-Total', $total_icons );
		$response->header( 'X-WP-TotalPages', $total_pages );
		$response->header( 'X-WP-Page', $page );

		return $response;
	}

	/**
	 * Retrieve a single icon by slug via REST.
	 *
	 * This method handles requests to the /icon/(?P<slug>[a-z0-9-]+)/ endpoint.
	 * It returns the JSON representation of the icon, including its SVG markup.
	 *
	 * @param WP_REST_Request $request The current REST API request.
	 * @return WP_REST_Response The response containing the requested icon data.
	 */
	public function rest_get_icon( WP_REST_Request $request ): WP_REST_Response {
		// Retrieve the icon slug from the request.
		$slug = $request->get_param( 'slug' );

		// Get the SVG markup for the icon.
		$svg = plugin()->svg()->get_svg( $slug );

		// If the slug exists and SVG data is found, get the icon's JSON data.
		$icon = $slug && $svg
			? plugin()->svg()->get_icon_library()->get_icon( $slug )->jsonSerialize( array( 'slug', 'title' ) )
			: array();

		// Allow filtering of the response data.
		$response = apply_filters(
			'basicsp_rest_get_svg_response',
			wp_parse_args( $icon, array( 'svg' => $svg ) ),
			$slug
		);

		return rest_ensure_response( $response );
	}
}
