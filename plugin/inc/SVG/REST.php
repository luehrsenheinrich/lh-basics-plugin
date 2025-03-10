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
		$slugs        = $request->get_param( 'slugs' );
		$search       = $request->get_param( 'search' );
		$must_include = $request->get_param( 'must_include' );
		$lib_icons    = plugin()->svg()->get_icon_library()->get_icons();

		// If a search term is provided, filter icons whose titles contain the term.
		if ( $search ) {
			$lib_icons = $this->fuzzy_filter_icons_by_title( $lib_icons, $search, 0.3 );
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
		$offset = ( $page - 1 ) * $per_page;
		if ( ! $search && ! empty( $must_include ) ) {
			// If there's no search but must_include is provided, get the required icon.
			$must_include_icon = plugin()->svg()->get_icon_library()->get_icon( $must_include );

			if ( $must_include_icon ) {
				// Add the icon to the $offset index of $lib_icons.
				$lib_icons = array_merge( array_slice( $lib_icons, 0, $offset ), array( $must_include_icon ), array_slice( $lib_icons, $offset ) );
			}
		}
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

	/**
	 * Convert a UTF‑8 string to a single‐byte extended ASCII representation.
	 *
	 * This function finds all multibyte sequences and, for each unique one, assigns a
	 * single-byte code in the range 128–255 (in order of appearance). It then returns
	 * the converted string.
	 *
	 * @param string $str The input UTF‑8 string.
	 * @param array  &$map A reference to an encoding map. Unique multibyte sequences will be added here.
	 * @return string The converted string.
	 */
	private function utf8_to_extended_ascii( $str, &$map ) {
		// Find all multibyte sequences in the string.
		if ( ! preg_match_all( '/[\xC0-\xF7][\x80-\xBF]+/', $str, $matches ) ) {
			// If no multibyte characters, return the original string.
			return $str;
		}

		// For each multibyte character, if we haven’t seen it, map it to a unique code.
		foreach ( $matches[0] as $mbc ) {
			if ( ! isset( $map[ $mbc ] ) ) {
				$map[ $mbc ] = chr( 128 + count( $map ) );
			}
		}

		// Replace the multibyte sequences with their corresponding single-byte codes.
		return strtr( $str, $map );
	}

	/**
	 * A UTF‑8–aware wrapper for levenshtein().
	 *
	 * This function converts both input strings using utf8_to_extended_ascii() so that
	 * the built‑in levenshtein() function (which works on bytes) produces a result that
	 * better reflects character differences.
	 *
	 * @param string $s1 First string.
	 * @param string $s2 Second string.
	 * @return int The Levenshtein distance.
	 */
	private function levenshtein_utf8( $s1, $s2 ) {
		$char_map = array();
		$s1_ascii = $this->utf8_to_extended_ascii( $s1, $char_map );
		// Note: reusing the same map ensures that identical multibyte sequences get mapped to the same byte.
		$s2_ascii = $this->utf8_to_extended_ascii( $s2, $char_map );
		return levenshtein( $s1_ascii, $s2_ascii );
	}

	/**
	 * Fuzzy filter icons by title using a sliding window and UTF‑8–aware Levenshtein.
	 *
	 * This method compares the search query against substrings of the icon's title (of the same length
	 * as the search query) and returns icons for which the normalized Levenshtein distance is less than
	 * or equal to the specified threshold.
	 *
	 * @param array  $lib_icons Array of icon objects.
	 * @param string $search    The search query.
	 * @param float  $threshold Normalized threshold (e.g. 0.3 means at most 30% difference).
	 * @return array Filtered array of icon objects.
	 */
	private function fuzzy_filter_icons_by_title( $lib_icons, $search, $threshold = 0.3 ) {
		// Normalize the search query.
		$search        = mb_strtolower( trim( $search ), 'UTF-8' );
		$search_length = mb_strlen( $search, 'UTF-8' );

		return array_filter(
			$lib_icons,
			function( $icon ) use ( $search, $search_length, $threshold ) {
				// Normalize the icon title.
				$title        = mb_strtolower( $icon->get_title(), 'UTF-8' );
				$title_length = mb_strlen( $title, 'UTF-8' );

				if ( $title_length < $search_length ) {
					// If the title is shorter than the search query, compare the entire title.
					$distance   = $this->levenshtein_utf8( $title, $search );
					$normalized = $distance / $search_length;
				} else {
					// Slide a window over the title to compute the minimum distance.
					$min_distance = PHP_INT_MAX;
					for ( $i = 0; $i <= $title_length - $search_length; $i++ ) {
						$substring = mb_substr( $title, $i, $search_length, 'UTF-8' );
						$distance  = $this->levenshtein_utf8( $substring, $search );
						if ( $distance < $min_distance ) {
							$min_distance = $distance;
							// Early exit if an exact match is found.
							if ( 0 === $min_distance ) {
								break;
							}
						}
					}
					$normalized = $min_distance / $search_length;
				}
				return ( $normalized <= $threshold );
			}
		);
	}

}
