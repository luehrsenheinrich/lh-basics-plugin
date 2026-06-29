<?php
/**
 * Blocks Class
 *
 * @package lhbasicsp
 */

namespace WpMunich\basics\plugin\Blocks;
use WpMunich\basics\plugin\Plugin_Component;

use function WpMunich\basics\plugin\plugin;

/**
 * A class to handle Blocks related logic.
 */
class Blocks extends Plugin_Component {

	/**
	 * {@inheritDoc}
	 */
	protected function add_actions() {
		add_action( 'init', array( $this, 'register_assets' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function add_filters() {}

	/**
	 * Register assets.
	 */
	public function register_assets() {
		// Load script assets.
		$assets = wp_json_file_decode(
			plugin()->get_plugin_path() . '/admin/dist/assets.json',
			array( 'associative' => true )
		);

		// Get blocks helper assets.
		$blocks_helper_assets = $assets['js/blocks-helper.min.js'] ?? array();

		// Handle tinyMCE dependencies.
		// Guard this as it's loading a lot of unecessary stuff if not needed.
		$use_tiny_mce = apply_filters( 'lhbasics_use_tinymce', false );
		if ( $use_tiny_mce ) {
			$blocks_helper_assets['dependencies'][] = 'wp-editor';
			$blocks_helper_assets['dependencies'][] = 'wp-tinymce';

			wp_enqueue_editor();
			wp_enqueue_media();
		}

		$blocks_helper_assets = $assets['js/blocks-helper.min.js'] ?? array();
		wp_register_script(
			'lhbasics-blocks-helper',
			plugin()->get_plugin_url() . '/admin/dist/js/blocks-helper.min.js',
			array_merge( array( 'lhbasics' ), $blocks_helper_assets['dependencies'] ),
			$blocks_helper_assets['version'],
			true
		);
		wp_register_style(
			'lhbasicsp-admin-components',
			plugin()->get_plugin_url() . '/admin/dist/css/components.min.css',
			array(),
			plugin()->get_plugin_version(),
			'all'
		);

		wp_localize_script(
			'lhbasics-blocks-helper',
			'lhbasicsBlocksHelper',
			array(
				'useTinyMCE' => $use_tiny_mce,
				'settings'   => wp_get_global_settings(),
				'cssUri'     => apply_filters( 'lhbasics_tinymce_css_uri', '' ),
			)
		);
	}

	/**
	 * Enqueue block editor assets.
	 */
	public function enqueue_block_editor_assets() {
		wp_enqueue_script( 'lhbasics-blocks-helper' );
		wp_enqueue_style( 'lhbasicsp-admin-components' );
	}
}
