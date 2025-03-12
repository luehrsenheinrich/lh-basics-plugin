/* eslint-disable @wordpress/no-unsafe-wp-apis */
/**
 * A control to select a weblink, which consits of a URL, a label and an icon.
 */

/**
 * WordPress dependencies.
 */
import { Dropdown, Button, PanelRow, TextControl } from '@wordpress/components';
import { __experimentalLinkControl as LinkControl } from '@wordpress/block-editor';
import { useState } from '@wordpress/element';
import { __, sprintf } from '@wordpress/i18n';
import { filterURLForDisplay, safeDecodeURI } from '@wordpress/url';

/**
 * External dependencies.
 */
import classNames from 'classnames';

export default function WeblinkControl({
	label,
	value,
	onChange,
	subjectName,
	extraControls,
}) {
	const [popoverAnchor, setPopoverAnchor] = useState();
	if (value === undefined || value === null) {
		value = {};
	}

	if (subjectName === undefined) {
		subjectName = __('weblink', 'lhbasicsp');
	}

	const controlId = `lh-weblink-control--${subjectName
		.replace(' ', '')
		.toLowerCase()}`;

	const buttonClassNames = classNames({
		'lh-weblink-control__preview': true,
	});

	return (
		<div className="lh-weblink-control" ref={setPopoverAnchor}>
			{label && (
				<label
					htmlFor={controlId}
					className="lh-weblink-control__label"
				>
					{label}
				</label>
			)}
			<Dropdown
				id={controlId}
				contentClassName="lh-weblink-control__dropdown-content"
				focusOnMount="container"
				popoverProps={{
					anchor: popoverAnchor,
					resize: false,
				}}
				renderToggle={({ isOpen, onToggle }) => (
					<Button
						variant="link"
						onClick={onToggle}
						aria-expanded={isOpen}
						className={buttonClassNames}
					>
						<div className="lh-weblink-control__preview-title">
							{value.title ||
								sprintf(
									/** translators: %s is the name of the subject */
									__('Edit %s', 'lhbasicsp'),
									subjectName
								)}
						</div>
						{value.url && (
							<div className="lh-weblink-control__preview-url">
								{filterURLForDisplay(
									safeDecodeURI(value.url),
									30
								)}
							</div>
						)}
					</Button>
				)}
				renderContent={() => {
					return (
						<LinkControl
							className={'lh-weblink-control__link-control'}
							value={value}
							onChange={(newValue) => {
								onChange({ ...value, ...newValue });
							}}
							renderControlBottom={() => (
								<div className="block-editor-link-control__tools lh-weblink-control__tools">
									<PanelRow className="panel-row-full-width">
										<TextControl
											label={__('Title', 'lhbasicsp')}
											value={value.title ?? ''}
											onChange={(newValue) => {
												onChange({
													...value,
													title: newValue,
												});
											}}
										/>
									</PanelRow>
									{extraControls}
								</div>
							)}
						/>
					);
				}}
			/>
		</div>
	);
}
