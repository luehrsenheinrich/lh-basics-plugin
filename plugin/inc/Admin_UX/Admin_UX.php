<?php
/**
 * Holds the Admin_UX class.
 *
 * @package lhbasicsp
 */

namespace WpMunich\basics\plugin\Admin_UX;

use WpMunich\basics\plugin\Plugin_Component;

/**
 * A class to disable comments.
 */
class Admin_UX extends Plugin_Component {

	/**
	 * {@inheritdoc}
	 */
	protected function add_actions() {
		add_action( 'enqueue_block_editor_assets', array( $this, 'disable_block_editor_full_screen_mode' ) );
		add_action( 'do_meta_boxes', array( $this, 'remove_dashboard_widgets' ) );
		add_action( 'login_errors', array( $this, 'use_ambiguous_login_error' ) );

		// Disable XML-RPC.
		add_filter( 'xmlrpc_enabled', '__return_false' );
	}

	/**
	 * {@inheritdoc}
	 */
	protected function add_filters() {

	}

	/**
	 * Disable the full screen mode in the block editor.
	 *
	 * @see https://github.com/felixarntz/felixarntz-mu-plugins/blob/main/felixarntz-mu-plugins/disable-block-editor-fullscreen-mode.php
	 *
	 * @return void
	 */
	public function disable_block_editor_full_screen_mode() {
		$script = "window.onload = function() { const isFullscreenMode = wp.data.select( 'core/edit-post' ).isFeatureActive( 'fullscreenMode' ); if ( isFullscreenMode ) { wp.data.dispatch( 'core/edit-post' ).toggleFeature( 'fullscreenMode' ); } }";
		wp_add_inline_script( 'wp-blocks', $script );
	}

	/**
	 * Removes all default widgets from the WordPress dashboard.
	 *
	 * @see https://github.com/felixarntz/felixarntz-mu-plugins/blob/main/felixarntz-mu-plugins/remove-dashboard-widgets.php
	 *
	 * @param string $screen_id The screen ID.
	 *
	 * @return void
	 */
	public function remove_dashboard_widgets( $screen_id ) {
		global $wp_meta_boxes;

		if ( 'dashboard' !== $screen_id ) {
			return;
		}

		if ( true ) {
			$default_widgets = array(
				'dashboard_right_now'         => 'normal',
				'network_dashboard_right_now' => 'normal',
				'dashboard_activity'          => 'normal',
				'dashboard_quick_press'       => 'side',
				'dashboard_primary'           => 'side',
			);

			$default_widgets_to_remove = array_merge(
				array_keys( $default_widgets ),
				array( 'dashboard_site_health', 'welcome_panel' )
			);

			foreach ( $default_widgets_to_remove as $widget_id ) {
				if ( isset( $default_widgets[ $widget_id ] ) ) {
					remove_meta_box( $widget_id, $screen_id, $default_widgets[ $widget_id ] );
				} elseif ( 'dashboard_site_health' === $widget_id ) {
					// Remove Site Health unless there are critical issues or recommendations.
					if ( isset( $wp_meta_boxes[ $screen_id ]['normal']['core']['dashboard_site_health'] ) ) {
						$get_issues = get_transient( 'health-check-site-status-result' );
						if ( false === $get_issues ) {
							remove_meta_box( 'dashboard_site_health', $screen_id, 'normal' );
						} else {
							$issue_counts = json_decode( $get_issues, true );
							if ( empty( $issue_counts['critical'] ) && empty( $issue_counts['recommended'] ) ) {
								remove_meta_box( 'dashboard_site_health', $screen_id, 'normal' );
							}
						}
					}
				} elseif ( 'welcome_panel' === $widget_id ) {
					remove_action( 'welcome_panel', 'wp_welcome_panel' );
				}
			}
		}
	}

	/**
	 * Modifies the error messages for a failed login attempt to be more ambiguous.
	 *
	 * @see https://github.com/felixarntz/felixarntz-mu-plugins/blob/main/felixarntz-mu-plugins/use-ambiguous-login-error.php
	 *
	 * @param string $error The error message.
	 *
	 * @return string The modified error message.
	 */
	public function use_ambiguous_login_error( $error ) {
		global $errors;

		if ( ! is_wp_error( $errors ) ) {
			return $error;
		}

		$error_codes = array_intersect(
			$errors->get_error_codes(),
			array(
				'invalid_username',
				'invalid_email',
				'incorrect_password',
				'invalidcombo',
			)
		);
		if ( $error_codes ) {
			$error  = '<strong>' . esc_html__( 'Error:', 'lhbasicsp' ) . '</strong> ';
			$error .= esc_html__( 'The username/email address or password is incorrect. Please try again.', 'lhbasicsp' );
		}

		return $error;
	}

}
