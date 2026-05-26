/**
 * WordPress dependencies.
 */
import {
	BlockControls,
	InspectorControls,
	useBlockProps,
} from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { PanelBody, TextControl } from '@wordpress/components';
const { IconSelectControl, WeblinkControl, WeblinkToolbarButton } =
	window.lhbasics.components;

const Edit = (props) => {
	const { attributes, setAttributes } = props;

	const { icon, link } = attributes;

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Settings', 'lhpbpp')}>
					<p>{__('This is a demo block.', 'lhpbpp')}</p>
					<TextControl
						label={__('Demo block text', 'lhpbpp')}
						value={attributes.text || ''}
						onChange={(value) => setAttributes({ text: value })}
					/>
					<IconSelectControl
						label={__('Select an icon', 'lhpbpp')}
						value={icon}
						onChange={(value) => setAttributes({ icon: value })}
					/>
				</PanelBody>
			</InspectorControls>
			<BlockControls>
				<WeblinkToolbarButton
					value={link}
					onChange={(value) => setAttributes({ link: value })}
				/>
			</BlockControls>
			<div {...useBlockProps()}>
				<p>{__('This is a demo block.', 'lhpbpp')}</p>
				<WeblinkControl
					label={__('Demo block link', 'lhpbpp')}
					value={link}
					onChange={(value) => setAttributes({ link: value })}
				/>
			</div>
		</>
	);
};

export default Edit;
