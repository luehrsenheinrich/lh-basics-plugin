/**
 * Fixes the style attribute to remove the # autoset to
 * be able to use CSS variables in the color palette.
 *
 * @param {Node} node
 * @return {Node} The fixed node.
 */
export function fixHexStyleToStyle(node) {
	const mceColor = node.getAttribute('data-mce-color');
	if (mceColor?.startsWith('#var(')) {
		node.setAttribute('data-mce-color', mceColor.replace('#', ''));
	}
	const style = node.getAttribute('style');
	if (style) {
		node.setAttribute('style', style.replace(/#var\(/g, 'var('));
	}

	return node;
}
