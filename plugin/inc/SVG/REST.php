<?php
/**
 * The SVG REST class.
 *
 * This file defines the `REST` class, which registers and manages custom REST API endpoints
 * for svg icons.
 *
 * @package lhbasicsp
 */

namespace WpMunich\basics\plugin\REST;

use WP_REST_Server;

use function WpMunich\basics\plugin\plugin;
use function add_action;
use function apply_filters;
use function esc_attr;
use function register_rest_route;
use function rest_ensure_response;
use function wp_parse_args;

/**
 * REST
 *
 * A class to register and manage svg/icon custom REST API endpoints.
 */
class REST {

	/**
	 * The namespace for REST endpoints in this component.
	 *
	 * @var string
	 */
	private $rest_namespace = 'lhbasics/v1'; // Could be outsourced to a "blank" REST component or similar.

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
	 * Register custom REST API routes.
	 *
	 * @return void
	 */
	public function register_rest_routes() {
		// Route to retrieve all icons.
		register_rest_route(
			$this->rest_namespace,
			'icons',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'rest_get_icons' ),
				'permission_callback' => '__return_true',
			)
		);

		// Route to retrieve a single icon by slug.
		register_rest_route(
			$this->rest_namespace,
			'icon(?:/(?<slug>[a-z0-9-]+))?',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'rest_get_icon' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'slug' => array(
						'type' => 'string',
					),
				),
			)
		);
	}

	/**
	 * Retrieve all icons from the icon library via REST.
	 *
	 * Returns a filtered list of icons available in the plugin's icon library. This includes
	 * options to restrict results based on specific icon slugs provided as a comma-separated
	 * parameter in the request.
	 *
	 * @param  WP_REST_Request $request The request.
	 *
	 * @return WP_REST_Response The response containing icon data.
	 */
	public function rest_get_icons( $request ) {
		$slugs     = $request->get_param( 'slugs' );
		$lib_icons = plugin()->svg()->get_icon_library()->get_icons();
		$res_icons = array();

		// Process the `slugs` parameter, if provided.
		if ( $slugs && ! empty( $slugs ) ) {
			$slugs = explode( ',', $slugs );
		}

		// Loop through icons and filter based on visibility in REST API.
		foreach ( $lib_icons as $icon ) {
			if ( $icon->show_in_rest() ) {
				if ( is_array( $slugs ) && ! in_array( $icon->get_slug(), $slugs, true ) ) {
					continue;
				}

				$res_icons[] = wp_parse_args(
					$icon->jsonSerialize( array( 'slug', 'title' ) ),
					array(
						'svg' => plugin()->svg()->get_svg( $icon->get_slug() ),
					)
				);
			}
		}
		return rest_ensure_response( $res_icons );
	}

	/**
	 * Retrieve a single icon by slug via REST.
	 *
	 * Accepts a `slug` parameter to identify the icon, and optional parameters for SVG attributes.
	 * This enables API clients to specify icon attributes dynamically.
	 *
	 * @param  WP_REST_Request $request The request.
	 *
	 * @return WP_REST_Response The response containing the requested icon data.
	 */
	public function rest_get_icon( $request ) {
		$slug = $request->get_param( 'slug' );
		$path = $request->get_param( 'path' );
		$args = $this->get_args_from_request( $request );

		$svg = plugin()->svg()->get_svg( $slug );

		// Fallback to using `path` parameter if slug is not found.
		if ( ! $svg && $path && ! empty( $path ) ) {
			$svg = plugin()->svg()->get_svg( $path );
		}

		$icon = $slug && $svg ? plugin()->svg()->get_icon_library()->get_icon( $slug )->jsonSerialize( array( 'slug', 'title' ) ) : array();

		$response = apply_filters(
			'basicsp_rest_get_svg_response',
			wp_parse_args(
				$icon,
				array( 'svg' => $svg )
			),
			$slug,
			$args
		);

		return rest_ensure_response( $response );
	}

	/**
	 * Helper function to map request parameters to SVG attributes.
	 *
	 * Extracts relevant attributes from the request and maps them to a format suitable
	 * for the plugin's `get_svg` function, supporting dynamic SVG generation.
	 *
	 * @param WP_REST_Request $request The request to check for params.
	 * @return array The $args array for `get_svg`.
	 */
	private function get_args_from_request( $request ) {
		$args = array();
		$attr = array();

		if ( isset( $request['class'] ) ) {
			$attr['class'] = esc_attr( $request['class'] );
		}
		if ( isset( $request['id'] ) ) {
			$attr['id'] = esc_attr( $request['id'] );
		}
		if ( isset( $request['width'] ) ) {
			$attr['width'] = esc_attr( $request['width'] );
		}
		if ( isset( $request['height'] ) ) {
			$attr['height'] = esc_attr( $request['height'] );
		}
		if ( isset( $request['fill'] ) ) {
			$attr['fill'] = esc_attr( $request['fill'] );
		}

		// Add attributes to $args if any were set.
		if ( count( $attr ) ) {
			$args['attributes'] = $attr;
		}

		return $args;
	}

	/**
	 * Get the current REST namespace.
	 *
	 * Returns the namespace used for this component’s REST routes.
	 *
	 * @return string The REST namespace.
	 */
	public function get_namespace() {
		return $this->rest_namespace;
	}
}
