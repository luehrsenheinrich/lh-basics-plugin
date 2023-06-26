<?php
/**
 * The template for displaying a single link.
 *
 * @package lhpbpt
 */

use function WpMunich\lhpbpt\render_attributes;

$args = wp_parse_args(
	$args ?? array(),
	array(
		'url'                => '',
		'content'            => '',
		'target'             => '',
		'title'              => '',
		'rel'                => '',
		'className'          => '',
		'linkTagName'        => 'a',
		'type'               => '',
		'name'               => '',
		'htmlAttributes'     => array(),
	)
);

// Attr `rel` should always be 'noopener noreferrer' on target="_blank".
// If no rel is set (e.g. by lh/button) force it.
$rel = empty( $rel ) && $args['target'] === '_blank' ? 'noopener noreferrer' : $args['rel'];

$link_classes = classNames(
	$args['className'],
	'lh-link',
);

// Validate, that linktagname is either a or button.
if ( ! in_array( $args['linkTagName'], array( 'a', 'button' ), true ) ) {
	$args['linkTagName'] = 'a';
}

$link_attributes = array_merge(
	$args['htmlAttributes'],
	array(
		'href'   => $args['url'],
		'target' => $args['target'],
		'title'  => $args['title'],
		'rel'    => $rel,
		'class'  => $link_classes,
		'type'   => $args['type'],
		'name'   => $args['name'],
	)
);

?>
<<?php echo esc_attr( $args['linkTagName'] ); ?> <?php echo render_attributes( $link_attributes ); ?>>
	<?php echo wp_kses_post( $args['content'] ); ?>
</<?php echo esc_attr( $args['linkTagName'] ); ?>>
