/**
 * WordPress dependencies.
 */
import {
	BlockControls,
	InspectorControls,
	useBlockProps,
} from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { PanelBody } from '@wordpress/components';
const {
	PostSelectControl,
	IconSelectControl,
	SearchSelectControl,
	WeblinkControl,
	WeblinkToolbarButton,
} = window.lhbasics.components;

const Edit = (props) => {
	const { attributes, setAttributes } = props;

	const { icon, link, postSingle, posts } = attributes;

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Settings', 'lhpbpp')}>
					<p>{__('This is a demo block.', 'lhpbpp')}</p>
					<IconSelectControl
						label={__('Select an icon', 'lhpbpp')}
						value={icon}
						onChange={(value) => setAttributes({ icon: value })}
					/>
					<PostSelectControl
						label={__('Select posts', 'lhpbpp')}
						value={posts.map((id) => ({ value: id }))}
						onChange={(value) =>
							setAttributes({
								posts: value.map((item) => item.value),
							})
						}
						multiple={true}
					/>
					<SearchSelectControl
						label={__('Search for something', 'lhpbpp')}
						value={{ value: postSingle }}
						onChange={({ value }) =>
							setAttributes({ postSingle: value })
						}
						query={{ search: 'example' }}
						multiple={false}
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
