<?php
/**
 * The block template for the 'lh/separator' block.
 *
 * @param array    $attributes The block attributes.
 * @param string   $content The block content.
 * @param WP_Block $block The block type.
 *
 * @package lhpbp\plugin
 */

$attr = wp_parse_args(
	$attributes,
	array(
		'icon' => '',
	)
);

?>

<div <?php echo get_block_wrapper_attributes(); ?>>
	<p><?php _e( 'This is a demo block.', 'lhpbpp' ); ?></p>
</div>
