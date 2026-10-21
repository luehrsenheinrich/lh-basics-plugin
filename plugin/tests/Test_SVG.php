<?php
/**
 * Icon metadata, search and REST regressions.
 *
 * @package lhbasicsp
 */

use WpMunich\basics\plugin\SVG\Icon;
use WpMunich\basics\plugin\SVG\Icon_Search;
use WpMunich\basics\plugin\SVG\REST;
use function WpMunich\basics\plugin\plugin;

/**
 * Exercise the public icon API through the WordPress REST server.
 */
class Test_SVG extends WP_UnitTestCase {
	/**
	 * Original library restored after every test.
	 *
	 * @var array
	 */
	private array $original;

	/**
	 * Install a small deterministic icon library.
	 */
	public function set_up() {
		parent::set_up();
		$library        = plugin()->svg()->get_icon_library();
		$this->original = $library->get_icons();
		$path           = plugin()->get_plugin_path() . 'img/icons/slashes.svg';
		$library->set_icons(
			array(
				new Icon( $path, 'add', 'Add', true, array( 'plus', 'positive', 'navigation' ) ),
				new Icon( $path, 'trash-can', 'Trash can', true, array( 'delete', 'waste', 'remove' ) ),
				new Icon( $path, 'plus', 'Plus' ),
				new Icon( $path, '3D-Cursor', '3D cursor', true, array( 'cube', 'Watson Health' ) ),
				new Icon( $path, 'hidden', 'Hidden', false, array( 'plus' ) ),
			)
		);
		new REST();
		$GLOBALS['wp_rest_server'] = new WP_REST_Server();
		do_action( 'rest_api_init', $GLOBALS['wp_rest_server'] );
	}

	/**
	 * Restore the original library.
	 */
	public function tear_down() {
		plugin()->svg()->get_icon_library()->set_icons( $this->original );
		$GLOBALS['wp_rest_server'] = null;
		parent::tear_down();
	}

	/**
	 * Constructor compatibility and normalized keyword metadata.
	 */
	public function test_metadata_and_field_selection() {
		$icon = new Icon( '/private/path.svg', 'example', 'Example', true, array( ' plus ', '', null, 'plus', '0' ) );
		$this->assertSame( array( 'plus', '0' ), $icon->get_keywords() );
		$this->assertSame( array( 'slug' => 'example' ), $icon->jsonSerialize( array( 'slug' ) ) );
		$this->assertSame( array(), ( new Icon( 'x', 'x', 'X' ) )->get_keywords() );
	}

	/**
	 * Search by aliases, multiple fields, whitespace and typos.
	 */
	public function test_ranked_keyword_search() {
		$search = new Icon_Search();
		$icons  = plugin()->svg()->get_icon_library()->get_icons();
		$this->assertSame( array( 'plus', 'add', 'hidden' ), array_keys( $search->search( $icons, 'PLUS' ) ) );
		$this->assertSame( array( 'trash-can' ), array_keys( $search->search( $icons, 'trash delete' ) ) );
		$this->assertSame( array( 'trash-can' ), array_keys( $search->search( $icons, 'trsh' ) ) );
		$this->assertSame( array( '3D-Cursor' ), array_keys( $search->search( $icons, 'watson_health' ) ) );
		$this->assertSame( $icons, $search->search( $icons, '   ' ) );
		$this->assertSame( array(), $search->search( $icons, 'nonexistentkeyword' ) );
	}

	/**
	 * Search must happen before pagination and must not leak paths or hidden icons.
	 */
	public function test_rest_keyword_search_and_totals() {
		$request = new WP_REST_Request( 'GET', '/lhbasics/v1/icons' );
		$request->set_query_params(
			array(
				'search'   => 'plus',
				'per_page' => 1,
				'page'     => 2,
			)
		);
		$response = rest_do_request( $request );
		$this->assertSame( 200, $response->get_status() );
		$this->assertSame( 'add', $response->get_data()[0]['slug'] );
		$this->assertSame( 2, $response->get_headers()['X-WP-Total'] );
		$this->assertArrayHasKey( 'keywords', $response->get_data()[0] );
		$this->assertArrayNotHasKey( 'path', $response->get_data()[0] );
	}

	/**
	 * Pins are unique and respect a slug restriction.
	 */
	public function test_slug_filter_pins_and_validation() {
		$request = new WP_REST_Request( 'GET', '/lhbasics/v1/icons' );
		$request->set_query_params(
			array(
				'slugs'        => 'add,trash-can',
				'must_include' => 'trash-can,add,hidden',
			)
		);
		$response = rest_do_request( $request );
		$this->assertCount( 2, $response->get_data() );
		$this->assertSame( 2, $response->get_headers()['X-WP-Total'] );
		$this->assertSame( array( 'add', 'trash-can' ), array_column( $response->get_data(), 'slug' ) );
		$request->set_param( 'per_page', 101 );
		$this->assertSame( 400, rest_do_request( $request )->get_status() );
	}

	/**
	 * Both single-icon routes accept the existing mixed-case Carbon slugs.
	 */
	public function test_single_icon_routes() {
		foreach ( array( 'icon', 'icons' ) as $route ) {
			$response = rest_do_request( new WP_REST_Request( 'GET', '/lhbasics/v1/' . $route . '/3D-Cursor' ) );
			$this->assertSame( 200, $response->get_status() );
			$this->assertSame( '3D-Cursor', $response->get_data()['slug'] );
			$this->assertArrayNotHasKey( 'path', $response->get_data() );
		}
	}
}
