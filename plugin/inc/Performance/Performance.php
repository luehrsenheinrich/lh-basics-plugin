<?php
/**
 * Performance Class
 *
 * @package lhbasicsp
 */

namespace WpMunich\basics\plugin\Performance;
use WpMunich\basics\plugin\Plugin_Component;

/**
 * A class to handle performance related logic.
 */
class Performance extends Plugin_Component {

	/**
	 * {@inheritDoc}
	 */
	protected function add_actions() {
		add_action( 'init', array( $this, 'disable_emojis' ) );
		add_action( 'load-edit.php', array( $this, 'bulk_edit_defer_term_counting' ) );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function add_filters() {}

	/**
	 * Disable emojis.
	 */
	public function disable_emojis() {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		add_filter( 'emoji_svg_url', '__return_false' );
	}

	/**
	 * Defers term counting when bulk editing to avoid slow queries for each post updated.
	 *
	 * @see https://github.com/felixarntz/felixarntz-mu-plugins/blob/main/felixarntz-mu-plugins/bulk-edit-defer-term-counting.php
	 *
	 * @return void
	 */
	public function bulk_edit_defer_term_counting() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_REQUEST['bulk_edit'] ) ) {
			wp_defer_term_counting( true );
			add_action(
				'shutdown',
				static function () {
					wp_defer_term_counting( false );
				}
			);
		}
	}
}
