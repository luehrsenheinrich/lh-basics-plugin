<?php
/**
 * LHBASICSP\Options\Component class
 *
 * @package lhbasicsp
 */

namespace WpMunich\lhbasicsp\Options;
use WpMunich\lhbasicsp\Component;
use function add_action;
use function add_submenu_page;
use function _e;

/**
 * A class to handle LH Basics options.
 */
class Options extends Component {

	/**
	 * {@inheritdoc}
	 */
	protected function add_actions() {
		add_action( 'admin_init', array( $this, 'register_options' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
	}

	/**
	 * {@inheritdoc}
	 */
	protected function add_filters() {}

	/**
	 * Register options.
	 *
	 * @return void
	 */
	public function register_options() {
		register_setting( 'lhbasicsp-options', 'lhb_disable_comments_active' );
		register_setting( 'lhbasicsp-options', 'lhb_lightbox_active' );
		register_setting( 'lhbasicsp-options', 'lhb_lazyloading_active' );
	}

	/**
	 * Add options page to the Settings menu.
	 *
	 * @return void
	 */
	public function add_options_page() {
		add_submenu_page(
			'options-general.php',
			__( 'LH Basics', 'lhbasicsp' ),
			__( 'LH Basics', 'lhbasicsp' ),
			'manage_options',
			'lhbasicsp-options',
			array( $this, 'render_options_page' )
		);
	}

	/**
	 * Render options page.
	 *
	 * @return void
	 */
	public function render_options_page() {
		?>
		<div class="wrap">
			<h1><?php _e( 'LH Basics Settings', 'lhbasicsp' ); ?></h1>
			<form method="post" action="options.php">
				<?php
					settings_fields('lhbasicsp-options');
					do_settings_sections('lhbasicsp-options');
				?>
				<table class="form-table">
					<tr>
						<th scope="row"><?php _e('Disable Comments', 'lhbasicsp'); ?></th>
						<td>
							<label>
								<input type="checkbox" name="lhb_disable_comments_active" value="1" <?php checked(get_option('lhb_disable_comments_active'), 1); ?>>
								<?php _e('Enable', 'lhbasicsp'); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e('Lightbox', 'lhbasicsp'); ?></th>
						<td>
							<label>
								<input type="checkbox" name="lhb_lightbox_active" value="1" <?php checked(get_option('lhb_lightbox_active'), 1); ?>>
								<?php _e('Enable', 'lhbasicsp'); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e('Lazy Loading', 'lhbasicsp'); ?></th>
						<td>
							<label>
								<input type="checkbox" name="lhb_lazyloading_active" value="1" <?php checked(get_option('lhb_lazyloading_active'), 1); ?>>
								<?php _e('Enable', 'lhbasicsp'); ?>
							</label>
						</td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
}
