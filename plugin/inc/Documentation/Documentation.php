<?php
/**
 * Holds the Documentation class.
 *
 * @package lhbasicsp
 */

namespace WpMunich\basics\plugin\Documentation;

use WpMunich\basics\plugin\Plugin_Component;
use WpMunich\basics\plugin\Documentation\Documentation_Item;
use WpMunich\basics\plugin\Plugin;
use \Parsedown;

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

		// Load documentation items from markdown files.
		$items = array_merge( $items, $this->load_documentation_items_from_markdown() );

		// Filter the items before returning.
		$items = apply_filters( 'lhbasics/documentation_items', $items );

		return $items;
	}

	/**
	 * Load documentation items from markdown files.
	 *
	 * @return Documentation_Item[] The documentation items.
	 */
	public function load_documentation_items_from_markdown() {
		$plugin    = $this->container()->get( Plugin::class );
		$parsedown = new Parsedown();

		$markdown_files = apply_filters(
			'lhbasics/documentation_markdown_files',
			array(
				$plugin->get_plugin_path() . 'inc/Documentation/Default.md',
			)
		);

		$documentation_items = array();

		foreach ( $markdown_files as $file_path ) {
			if ( ! file_exists( $file_path ) ) {
				continue;
			}

			$content = file_get_contents( $file_path );
			if ( $content === false ) {
				continue;
			}

			// Extract the first H1 as the title and remove it from content.
			if ( preg_match( '/^# (.+)$/m', $content, $matches ) ) {
				$title   = trim( $matches[1] );
				$content = preg_replace( '/^# .+$/m', '', $content, 1 );
			} else {
				$title = basename( $file_path );
			}

			// Step down all other headlines by one level (to max 6).
			$content = preg_replace_callback(
				'/^(#{1,5})\s(.+)$/m',
				function ( $m ) {
					$level = min( strlen( $m[1] ) + 1, 6 );
					return str_repeat( '#', $level ) . ' ' . $m[2];
				},
				$content
			);

			// Make image URLs relative to the markdown file location.
			$dir      = dirname( $file_path );
			$base_url = str_replace( ABSPATH, site_url( '/' ), $dir ) . '/';
			$content  = preg_replace_callback(
				'/!\[([^\]]*)\]\(([^)]+)\)/',
				function ( $matches ) use ( $base_url ) {
					$alt = $matches[1];
					$src = $matches[2];
					// If the src is already absolute (starts with http or /), leave it.
					if ( preg_match( '#^(https?://|/)#', $src ) ) {
						return $matches[0];
					}
					// Otherwise, make it relative to the markdown file's URL.
					$relative_url = $base_url . ltrim( $src, '/' );
					return '![' . $alt . '](' . $relative_url . ')';
				},
				$content
			);

			$html_content = $parsedown->text( trim( $content ) );

			// Generate a slug from the title.
			$slug = sanitize_title( $title );

			$documentation_items[] = new Documentation_Item(
				$title,
				$html_content,
				'admin-generic',
				md5( $file_path )
			);
		}

		return $documentation_items;
	}
}
