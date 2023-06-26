/**
 * WordPress dependencies.
 */
import { useMemo } from '@wordpress/element';
import { Icon as WPIcon } from '@wordpress/components';

/**
 * External dependencies.
 */
import classNames from 'classnames';
import parse, { attributesToProps, domToReact } from 'html-react-parser';

/**
 * Internal dependencies.
 */
import { useIcon } from '../../data/entities/icon';

/**
 * Returns a wp.components.Icon element with icon from API.
 *
 * @param {Object} props           - Component props.
 * @param {string} props.slug      - Icon slug.
 * @param {string} props.svg       - Icon SVG.
 * @param {string} props.className - Icon class name.
 *
 * @return {Object} - Icon component.
 */
const LHIcon = (props) => {
	const { slug, svg } = props;
	const { icon } = useIcon(slug);

	// Further processing of the icon data.
	const markup = svg || icon?.svg;
	const parsedSvg = useMemo(
		() => (markup ? parse(markup, getParserOptions()) : null),
		[markup]
	);

	if (parsedSvg) {
		const className = classNames(
			props?.className || '',
			`lh-icon icon-${icon?.slug || slug || 'svg'}`
		);

		return <WPIcon {...props} icon={parsedSvg} className={className} />;
	}

	return <></>;
};

export default LHIcon;

/**
 * Returns the parser options for the html-react-parser library.
 */
const getParserOptions = () => {
	return {
		replace: (domNode) => {
			if (domNode.name === 'svg') {
				// Define the custom tag name.
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
	};
};
