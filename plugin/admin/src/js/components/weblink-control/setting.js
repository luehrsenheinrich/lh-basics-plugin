/**
 * A setting to select a weblink, which consits of a URL, a label and an icon.
 *
 * This component is different to the WeblinkControl as the WLC only works,
 * within a block-editor context, while the *Setting component is intended to be used
 * in a settings page.
 */

/**
 * WordPress dependencies.
 */
import apiFetch from '@wordpress/api-fetch';
import {
	Dropdown,
	Button,
	TextControl,
	ToggleControl,
} from '@wordpress/components';
import { useEffect, useState } from '@wordpress/element';
import { __, sprintf } from '@wordpress/i18n';
import { filterURLForDisplay, safeDecodeURI } from '@wordpress/url';

/**
 * External dependencies.
 */
import AsyncSelect from 'react-select/async';

/**
 * Constants.
 */
const CLS_BASE = 'lh-weblink-setting';
const REST_SEARCH_PATH = `/wp/v2/search`;
const DEFAULT_OPTIONS = [
	{
		id: 0,
		title: __('External URL', 'kbsp'),
	},
];

export default function WeblinkSetting({
	label,
	value,
	onChange,
	subjectName,
	extraControls,
}) {
	const [popoverAnchor, setPopoverAnchor] = useState();
	const [selectedPost, setSelectedPost] = useState(null);

	if (value === undefined || value === null) {
		value = {};
	}

	if (subjectName === undefined) {
		subjectName = __('weblink', 'kbsp');
	}

	const controlId = `${CLS_BASE}--${subjectName
		.replace(' ', '')
		.toLowerCase()}`;

	const loadOptions = async (query) => {
		const searchUrl = `${REST_SEARCH_PATH}?search=${query}`;
		try {
			const data = await apiFetch({ path: searchUrl });
			if (query.startsWith('http')) {
				return [...data, ...DEFAULT_OPTIONS];
			}
			return data;
		} catch (err) {
			return [];
		}
	};

	useEffect(() => {
		if (value?.id > 0 && !selectedPost) {
			apiFetch({ path: `${REST_SEARCH_PATH}?include=${value.id}` }).then(
				(data) => {
					setSelectedPost(data);
				}
			);
		}
	}, [value, selectedPost]);

	const isExternalUrl = value?.id === 0;

	return (
		<div className={CLS_BASE} ref={setPopoverAnchor}>
			{label && (
				<label htmlFor={controlId} className={`${CLS_BASE}__label`}>
					{label}
				</label>
			)}
			<Dropdown
				id={controlId}
				contentClassName={`${CLS_BASE}__dropdown-content`}
				focusOnMount={'container'}
				popoverProps={{
					anchor: popoverAnchor,
					className: `${CLS_BASE}__popover`,
				}}
				renderToggle={({ isOpen, onToggle }) => (
					<Button
						variant="link"
						onClick={onToggle}
						aria-expanded={isOpen}
						className={`${CLS_BASE}__preview`}
					>
						<div className={`${CLS_BASE}__preview-title`}>
							{value.title ||
								sprintf(
									/** translators: %s is the name of the subject */
									__('Edit %s', 'kbsp'),
									subjectName
								)}
						</div>
						{value.url && (
							<div className={`${CLS_BASE}__preview-url`}>
								{filterURLForDisplay(
									safeDecodeURI(value.url),
									30
								)}
							</div>
						)}
					</Button>
				)}
				renderContent={({ onClose }) => {
					return (
						<>
							<AsyncSelect
								label={__('Search for a post / page', 'kbsp')}
								className={`${CLS_BASE}__select`}
								classNamePrefix={`${CLS_BASE}__select`}
								isClearable
								getOptionLabel={(option) => option.title}
								getOptionValue={(option) => option.id}
								defaultValue={selectedPost}
								// defaultOptions={DEFAULT_OPTIONS}
								loadOptions={loadOptions}
								onChange={(selectedItem) => {
									onChange({
										...value,
										id: selectedItem?.id || 0,
										title: selectedItem?.title || '',
										url: selectedItem?.url || '',
									});
								}}
							/>
							<TextControl
								label={__('Title', 'kbsp')}
								value={value.title ?? ''}
								onChange={(newValue) => {
									onChange({
										...value,
										title: newValue,
									});
								}}
							/>
							{isExternalUrl && (
								<TextControl
									label={__('URL', 'kbsp')}
									value={value.url ?? ''}
									onChange={(newValue) => {
										onChange({
											...value,
											id: 0, // Editing the URL invalidates "internal link".
											url: newValue,
										});
									}}
								/>
							)}
							<ToggleControl
								label={__('Open in new tab', 'kbsp')}
								checked={value?.opensInNewTab ?? false}
								onChange={() => {
									onChange({
										...value,
										opensInNewTab: !value.opensInNewTab,
									});
								}}
							/>
							{extraControls}
							<div className={`${CLS_BASE}__footer`}>
								<Button variant={'primary'} onClick={onClose}>
									{__('Apply', 'kbsp')}
								</Button>
								<Button variant={'link'} onClick={onClose}>
									{__('Cancel', 'kbsp')}
								</Button>
							</div>
						</>
					);
				}}
			/>
		</div>
	);
}
