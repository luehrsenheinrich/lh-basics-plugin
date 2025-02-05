import { useMemo } from '@wordpress/element';
import { Icon as WPIcon } from '@wordpress/components';

import classNames from 'classnames';
import parse, { attributesToProps, domToReact } from 'html-react-parser';

import { useIcon } from '../../data/entities/icon';

/**
 * LHIcon renders a wp.components.Icon element using an SVG either passed as a prop
 * or retrieved via the API using the icon's slug.
 *
 * @param {Object} props
 * @param {string} props.slug      - Icon slug.
 * @param {string} props.svg       - Icon SVG markup.
 * @param {string} props.className - Additional CSS class names.
 *
 * @return {Object|null} Icon component or null if no icon data is available.
 */
const LHIcon = ({ slug, svg, className, ...rest }) => {
	const { icon } = useIcon(slug);
	const markup = svg || icon?.svg;

	// Memoize the parsed SVG markup to avoid unnecessary re-parsing.
	const parsedSvg = useMemo(
		() => (markup ? parse(markup, getParserOptions()) : null),
		[markup]
	);

	if (icon && parsedSvg) {
		const computedClassName = classNames(
			className,
			`lh-icon icon-${icon.slug || slug || 'svg'}`
		);
		const size = parseInt(icon?.width || icon?.height, 10) || null;

		return (
			<WPIcon
				{...rest}
				icon={parsedSvg}
				className={computedClassName}
				size={size}
			/>
		);
	}

	return null;
};

export default LHIcon;

/**
 * Returns parser options for html-react-parser.
 *
 * The replace callback transforms <svg> elements by converting their attributes
 * to React props and preserving their children.
 *
 * @return {Object} Parser options.
 */
const getParserOptions = () => ({
	replace: (domNode) => {
		if (domNode.name === 'svg') {
			const CustomTag = domNode.name;
			const props = attributesToProps(domNode.attribs);

			return (
				<CustomTag {...props} key={domNode.attribs.id}>
					{domToReact(domNode.children)}
				</CustomTag>
			);
		}

		return domNode;
	},
});
