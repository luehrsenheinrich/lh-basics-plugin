<?php
/**
 * LHBASICSP\Disable_Comments\Disable_Comments class
 *
 * @package lhbasicsp
 */

namespace WpMunich\basics\plugin\Disable_Comments;

use WpMunich\basics\plugin\Plugin_Component;

/**
 * A class to handle fullsite editing & theme.json
 */
class Disable_Comments extends Plugin_Component {

	/**
	 * {@inheritdoc}
	 */
	protected function add_actions() {
		add_action( 'init', array( $this, 'remove_comments_support' ) );
		add_action( 'admin_menu', array( $this, 'remove_comments_menu' ) );
		add_action( 'admin_init', array( $this, 'redirect_from_comments_page' ) );
		add_action( 'admin_init', array( $this, 'remove_comments_from_dashboard' ) );
		add_action( 'init', array( $this, 'remove_comments_admin_bar' ) );
		add_action( 'wp_before_admin_bar_render', array( $this, 'remove_comments_admin_bar_menu' ) );
	}

	/**
	 * {@inheritdoc}
	 */
	protected function add_filters() {
		add_filter( 'comments_open', '__return_false', 20, 2 );
		add_filter( 'pings_open', '__return_false', 20, 2 );
		add_filter( 'comments_array', array( $this, 'filter_comments_array' ), 10, 2 );
		add_filter( 'wp_count_comments', array( $this, 'filter_wp_count_comments' ), 10, 2 );
		add_filter( 'the_comments', array( $this, 'filter_the_comments' ), 10, 2 );
	}

	/**
	 * If the feature is an active option.
	 */
	protected function is_active() {
		return (bool) get_option( 'lhb_disable_comments_active' );
	}

	/**
	 * Remove comments support globally.
	 *
	 * @return void
	 */
	public function remove_comments_support() {
		$post_types = get_post_types();
		foreach ( $post_types as $post_type ) {
			if ( post_type_supports( $post_type, 'comments' ) ) {
				remove_post_type_support( $post_type, 'comments' );
				remove_post_type_support( $post_type, 'trackbacks' );
			}
		}
	}

	/**
	 * Filter the comments array.
	 *
	 * @param array $comments The comments array.
	 * @param int   $post_id  The post ID.
	 * @return array
	 */
	public function filter_comments_array( $comments, $post_id ) {
		$comments = array();
		return $comments;
	}

	/**
	 * Remove the comments menu.
	 *
	 * @return void
	 */
	public function remove_comments_menu() {
		remove_menu_page( 'edit-comments.php' );
	}

	/**
	 * Redirect from the comments page.
	 *
	 * @return void
	 */
	public function redirect_from_comments_page() {
		global $pagenow;
		if ( $pagenow === 'edit-comments.php' ) {
			wp_redirect( admin_url() );
			exit;
		}
	}

	/**
	 * Remove the comments from the dashboard.
	 *
	 * @return void
	 */
	public function remove_comments_from_dashboard() {
		remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
	}

	/**
	 * Remove the comments admin bar.
	 *
	 * @return void
	 */
	public function remove_comments_admin_bar() {
		if ( is_admin_bar_showing() ) {
			remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );
		}
	}

	/**
	 * Remove the comments admin bar menu.
	 *
	 * @return void
	 */
	public function remove_comments_admin_bar_menu() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu( 'comments' );
	}

	/**
	 * Filter the wp_count_comments function.
	 *
	 * @param array $counts The counts array.
	 * @param int   $post_id The post ID.
	 * @return array
	 */
	public function filter_wp_count_comments( $counts, $post_id ) {
		$counts = array(
			'approved'       => 0,
			'spam'           => 0,
			'trash'          => 0,
			'total_comments' => 0,
			'post-trashed'   => 0,
			'all'            => 0,
			'moderated'      => 0,
		);

		return (object) $counts;
	}

	/**
	 * Filter the the_comments function.
	 *
	 * @param array            $comments The comments array.
	 * @param WP_Comment_Query $query  Current instance of WP_Comment_Query (passed by reference).
	 * @return array
	 */
	public function filter_the_comments( $comments, $query ) {
		$comments = array();
		return $comments;
	}
}
