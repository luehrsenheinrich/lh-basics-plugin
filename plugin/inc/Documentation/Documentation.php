<?php
/**
 * Holds the Documentation class.
 *
 * @package lhbasicsp
 */

namespace WpMunich\basics\plugin\Documentation;

use WpMunich\basics\plugin\Plugin_Component;
use WpMunich\basics\plugin\Documentation\Documentation_Item;

/**
 * A class to provide documentation for this and related plugins.
 * This class primarily takes Markdown content from various sources,
 * orders them and provides them to the admin interface via a custom
 * endpoint in the WordPress REST API.
 */
class Documentation extends Plugin_Component {
	/**
	 * {@inheritdoc}
	 */
	protected function add_actions() {
		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );
	}

	/**
	 * {@inheritdoc}
	 */
	protected function add_filters() {

	}

	/**
	 * Register REST API routes for documentation.
	 */
	public function register_rest_routes() {
		register_rest_route(
			'lhbasics/v1',
			'/documentation',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'rest_get_documentation_items' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);
	}

	/**
	 * REST callback to get documentation items.
	 *
	 * @return array
	 */
	public function rest_get_documentation_items() {
		$items = $this->load_documentation_items();

		return rest_ensure_response( $items );
	}

	/**
	 * Load the documentation items.
	 *
	 * @return Documentation_Item[] The documentation items.
	 */
	public function load_documentation_items() {
		$items = array();

		// Load documentation items from various sources.
		// For example, you can load items from a database, file, or API.

		$items[] = new Documentation_Item(
			'Example Item',
			'<p>This is an example documentation item.</p>',
			'admin-generic',
			'example-item'
		);

		return $items;
	}
}
