<?php
/**
 * The template for displaying a single icon.
 *
 * @package lhpbpt
 */

use function WpMunich\lhpbpp\lh_plugin;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'pointer'        => '',
		'className'      => '',
		'iconAttributes' => array(),
		'wrapperTagName' => 'span',
	)
);

$args['iconAttributes'] = wp_parse_args(
	$args['iconAttributes'],
	array(
		'class'  => '',
		'width'  => '24',
		'height' => '24',
	)
);

$icon_svg = lh_plugin()->svg()->get_svg(
	$args['pointer'],
	array(
		'attributes' => $args['iconAttributes'],
	)
);


if ( ! empty( $args['pointer'] ) && ! empty( $icon_svg ) ) :
	$wrapper_class_names = classNames(
		'icon',
		$args['className'],
	);
	?>

	<?php if ( ! empty( $args['wrapperTagName'] ) ) : ?>
		<<?php echo esc_attr( $args['wrapperTagName'] ); ?> class="<?php echo esc_attr( $wrapper_class_names ); ?>">
	<?php endif; ?>
		<?php echo wp_kses_post( $icon_svg ); ?>
	<?php if ( ! empty( $args['wrapperTagName'] ) ) : ?>
		</<?php echo esc_attr( $args['wrapperTagName'] ); ?>>
	<?php endif; ?>

	<?php
endif;
