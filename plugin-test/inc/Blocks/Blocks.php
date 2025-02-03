<?php
/**
 * Load blocks for the test component.
 *
 * @package basicsTest\plugin
 */

namespace WpMunich\basicsTest\plugin\Blocks;
use WpMunich\basicsTest\plugin\Plugin_Component;

use function WpMunich\basicsTest\plugin\plugin;
use function acf_register_block_type;
use function add_action;
use function add_filter;
use function apply_filters;
use function get_current_screen;
use function register_block_type;
use function wp_enqueue_script;
use function wp_json_file_decode;
use function wp_set_script_translations;

/**
 * A class to handle the plugins blocks.
 */
class Blocks extends Plugin_Component {

	/**
	 * {@inheritDoc}
	 */
	protected function add_actions() {
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );
		add_action( 'init', array( $this, 'register_blocks' ) );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function add_filters() {
		add_filter( 'block_categories_all', array( $this, 'add_block_categories' ), 10, 2 );
	}

	/**
	 * Register the plugins custom block category.
	 *
	 * @param array   $categories The block categories.
	 * @param WP_Post $post     The current post that is edited.
	 */
	public function add_block_categories( $categories, $post ) {
		return array_merge(
			$categories,
			array(
				array(
					'slug'  => 'basicsTestp-blocks',
					'title' => __( 'Basic Test Blocks', 'basicsTestp' ),
				),
			)
		);
	}

	/**
	 * Enqueue the block scripts and styles.
	 */
	public function enqueue_block_editor_assets() {
		$screen = get_current_screen();

		$assets = wp_json_file_decode( plugin()->get_plugin_path() . '/admin/dist/assets.json', array( 'associative' => true ) );

		if ( ! in_array( $screen->id, array( 'widgets' ), true ) ) {
			$block_helper_assets = $assets['js/blocks-helper.min.js'] ?? array();
			wp_enqueue_script(
				'basicsTestp-blocks-helper',
				plugin()->get_plugin_url() . 'admin/dist/js/blocks-helper.min.js',
				array_merge( array( 'lhbasics-blocks-helper' ), $block_helper_assets['dependencies'] ),
				$block_helper_assets['version'],
				true
			);
		}

		$block_assets = $assets['js/blocks.min.js'] ?? array();
		wp_enqueue_script(
			'basicsTestp-blocks',
			plugin()->get_plugin_url() . 'admin/dist/js/blocks.min.js',
			array_merge( array( 'lhbasics-blocks-helper' ), $block_assets['dependencies'] ),
			$block_assets['version'],
			true
		);

		wp_enqueue_style(
			'basicsTestp-admin-components',
			plugin()->get_plugin_url() . '/admin/dist/css/components.min.css',
			array(),
			plugin()->get_plugin_version(),
			'all'
		);

		/**
		 * Load the translations for the block editor assets.
		 */
		$dir  = plugin()->get_plugin_path();
		$path = $dir . '/languages/';

		wp_set_script_translations(
			'basicsTestp-blocks',
			'basicsTestp',
			$path
		);

		wp_set_script_translations(
			'basicsTestp-blocks-helper',
			'basicsTestp',
			$path
		);
	}

	/**
	 * Register the blocks.
	 */
	public function register_blocks() {
		$blocks_path = plugin()->get_plugin_path() . 'blocks/';

		$custom_blocks = array(
			'demo',
		);

		foreach ( $custom_blocks as $block ) {
			register_block_type(
				$blocks_path . $block . '/',
				array(
					'render_callback' => array( $this, 'provide_render_callback' ),
				)
			);
		}
	}

	/**
	 * Provide the render callback for the block.
	 *
	 * @param array    $attributes The block attributes.
	 * @param string   $content The block content.
	 * @param WP_Block $block The block type.
	 *
	 * @return string The rendered block.
	 */
	public function provide_render_callback( $attributes, $content, $block ) {
		$blocks_path = plugin()->get_plugin_path() . 'blocks/';
		ob_start();

		switch ( $block->name ) {
			case 'lh/demo':
				include $blocks_path . 'demo/template.php';
				break;
		}

		$block_html = ob_get_clean();

		return $block_html;
	}
}
