/**
 * WordPress dependencies.
 */
import { useEffect, useState } from '@wordpress/element';
import {
	BaseControl,
	Spinner,
	useBaseControlProps,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * External dependencies.
 */
import Select from 'react-select';
import { useIcons } from '../../data/entities/icon';

/**
 * Internal dependencies.
 */
import LHIcon from '../icon';

const IconSelectControl = ({
	label = __('Select icon', 'lhpbpp'),
	help = '',
	value,
	onChange,
	blackList,
	whiteList,
}) => {
	const [selectedOption, setSelectedOption] = useState();
	const { baseControlProps, controlProps } = useBaseControlProps({
		label,
		help,
	});
	const { icons, ...yadda } = useIcons();

	useEffect(() => {
		if (icons?.length && selectedOption?.value !== value) {
			const icon = icons.find((i) => i?.slug === value) || {};
			// Prepare the Select value.
			setSelectedOption({
				icon: { ...icon },
				value: icon.slug,
				label: icon.title,
			});
		}
	}, [icons, value, selectedOption]);

	const onSelectIcon = (option) => {
		onChange(option?.value);
		if (!option?.value) {
			setSelectedOption(null);
		}
	};

	let options = icons?.slice() || []; // Create a copy of the icons array

	// Filter options over black- or whitelist, prefering white over blacklist.
	if (whiteList?.length) {
		options = options.filter(
			(option) => whiteList.indexOf(option.slug) > -1
		);
	} else if (blackList?.length) {
		options = options.filter(
			(option) => blackList.indexOf(option.slug) < 0
		);
	}

	return (
		<BaseControl {...baseControlProps}>
			{!yadda?.hasResolved && <Spinner />}
			{yadda?.hasResolved && (
				<Select
					{...controlProps}
					openMenuOnClick={true}
					openMenuOnFocus={true}
					classNamePrefix="react-select"
					className="lh-icon-select-control react-select"
					value={selectedOption}
					onChange={onSelectIcon}
					isSearchable={true}
					isDisabled={!icons.length}
					isClearable={true}
					options={options.map((icon) => ({
						icon: { ...icon },
						value: icon.slug,
						label: icon.title,
					}))}
					getOptionLabel={(option) => (
						<div className="lh-icon-select-option-label">
							<LHIcon
								slug={option.icon.slug}
								svg={option.icon.svg}
							/>
							<span>{option.label}</span>
						</div>
					)}
				/>
			)}
		</BaseControl>
	);
};

export default IconSelectControl;
