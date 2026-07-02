<?php
/**
 * Styles Class
 *
 * @package lhbasicsp
 */

namespace WpMunich\basics\plugin\Styles;
use WpMunich\basics\plugin\Settings\Settings;
use function WpMunich\basics\plugin\plugin;

use WpMunich\basics\plugin\Plugin_Component;

/**
 * This component handles the styles of the plugin.
 */
class Styles extends Plugin_Component {

	/**
	 * {@inheritDoc}
	 */
	protected function add_actions() {
		if ( $this->is_active() ) {
			add_action( 'enqueue_block_assets', array( $this, 'create_color_helper_vars' ), 99 );
			add_action( 'admin_enqueue_scripts', array( $this, 'create_color_helper_vars' ), 99 );
		}
	}

	/**
	 * {@inheritDoc}
	 */
	protected function add_filters() {}

	/**
	 * {@inheritdoc}
	 */
	protected function must_run() {
		add_filter( 'lhagentur_available_modules', array( $this, 'add_module' ) );
	}

	/**
	 * Whether the CSS Vars Helper module is active.
	 */
	protected function is_active() {
		return $this->container()->get( Settings::class )->is_module_active( 'css-vars-helper' );
	}

	/**
	 * Filter callback to register the color vars helper module.
	 *
	 * @param array $modules The available modules.
	 * @return array The modified modules.
	 */
	public function add_module( $modules ) {
		if ( ! wp_theme_has_theme_json() ) {
			return $modules;
		}
		if ( apply_filters( 'lhagentur_disable_color_helper_module', false ) ) {
			return $modules;
		}

		$modules[] = array(
			'title'       => __( 'CSS Vars Helper', 'lhbasicsp' ),
			'description' => __( 'This module enables autogeneration of CSS variables helper classes depending on the theme\'s color palette defined at the theme.json. By default generates `--current-color` and `--current-background-color` variables.', 'lhbasicsp' ),
			'slug'        => 'css-vars-helper',
		);

		return $modules;
	}

	/**
	 * Generates CSS helper classes from the theme.json color palette.
	 *
	 * @return void
	 */
	public function create_color_helper_vars() {
		if ( ! wp_theme_has_theme_json() ) {
			return;
		}

		$wp_colors     = wp_get_global_settings( array( 'color' ) );
		$theme_palette = $wp_colors['palette']['theme'] ?? array();

		if ( empty( $theme_palette ) ) {
			return;
		}

		$helper_vars_defaults = array(
			'--current-color'            => '.has-%s-color',
			'--current-background-color' => '.has-%s-background-color',
		);

		$helper_vars = apply_filters( 'lhagentur_color_helper_vars', $helper_vars_defaults );

		$palette_css = '';
		foreach ( $theme_palette as $theme_color ) {
			$slug  = $theme_color['slug'];
			$value = $theme_color['color'];

			foreach ( $helper_vars as $var => $selector ) {
				$palette_css .= sprintf( '%s { %s: %s; }', sprintf( $selector, $slug ), $var, $value );
			}
		}

		if ( empty( $palette_css ) ) {
			return;
		}

		wp_register_style( 'lh-global-styles-helper', false );
		wp_add_inline_style( 'lh-global-styles-helper', $palette_css );
		wp_enqueue_style( 'lh-global-styles-helper' );
	}
}
