/**
 * WordPress dependencies.
 */
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { PanelBody } from '@wordpress/components';

const Edit = () => {
	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Settings', 'lhpbpp')}>
					<p>{__('This is a demo block.', 'lhpbpp')}</p>
				</PanelBody>
			</InspectorControls>
			<div {...useBlockProps()}>
				<p>{__('This is a demo block.', 'lhpbpp')}</p>
			</div>
		</>
	);
};

export default Edit;
