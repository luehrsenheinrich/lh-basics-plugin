/**
 * WordPress dependencies.
 */
import {
	__experimentalLinkControl as LinkControl, // eslint-disable-line @wordpress/no-unsafe-wp-apis
} from '@wordpress/block-editor';
import { Popover, ToolbarButton } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useState } from '@wordpress/element';

/**
 * Assets.
 */
import { link as linkIcon, linkOff as linkOffIcon } from '@wordpress/icons';

export default function WeblinkToolbarButton({ value, onChange, onRemove }) {
	const [popoverAnchor, setPopoverAnchor] = useState();
	const [isOpen, setIsOpen] = useState(false);

	const hasURL = value?.url !== undefined && value?.url !== '';

	const handleOnRemove = () => {
		if (onRemove) {
			onRemove();
		} else {
			onChange({
				url: '',
				title: value?.title ?? '',
				opensInNewTab: false,
			});
		}
		setIsOpen(false);
	};
	return (
		<>
			<ToolbarButton
				ref={setPopoverAnchor}
				icon={hasURL ? linkOffIcon : linkIcon}
				label={
					hasURL
						? __('Edit Link', 'lhbasicsp')
						: __('Add Link', 'lhbasicsp')
				}
				onClick={() => setIsOpen(!isOpen)}
			/>
			{isOpen && (
				<Popover anchor={popoverAnchor}>
					<LinkControl
						value={value}
						onChange={onChange}
						onRemove={handleOnRemove}
					/>
				</Popover>
			)}
		</>
	);
}
