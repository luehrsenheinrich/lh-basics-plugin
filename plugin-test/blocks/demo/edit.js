/**
 * WordPress dependencies.
 */
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { PanelBody } from '@wordpress/components';
const { IconSelectControl } = window.lhbasics.components;
import { useState } from '@wordpress/element';
const Edit = () => {
	const [selectedIcon, setSelectedIcon] = useState();

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Settings', 'lhpbpp')}>
					<p>{__('This is a demo block.', 'lhpbpp')}</p>
					<IconSelectControl
						label={__('Select an icon', 'lhpbpp')}
						value={selectedIcon}
						onChange={setSelectedIcon}
					/>
				</PanelBody>
			</InspectorControls>
			<div {...useBlockProps()}>
				<p>{__('This is a demo block.', 'lhpbpp')}</p>
			</div>
		</>
	);
};

export default Edit;
