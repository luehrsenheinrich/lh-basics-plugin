/**
 * WordPress dependencies.
 */
import { useEffect, useState } from '@wordpress/element';
import { BaseControl, useBaseControlProps } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * External dependencies.
 */
import Select from 'react-select';
import { useIcons, useIcon } from '../../data/entities/icon';

/**
 * Internal dependencies.
 */
import LHIcon from '../icon';

const IconSelectControl = ({
	label = __('Select icon', 'lhbasicsp'),
	help = '',
	value,
	onChange,
	blackList = [],
	whiteList = [],
}) => {
	const [selectedOption, setSelectedOption] = useState(null);
	const [searchTerm, setSearchTerm] = useState('');
	const { baseControlProps, controlProps } = useBaseControlProps({
		label,
		help,
	});
	const { icons, hasResolved } = useIcons({
		search: searchTerm,
		must_include: value,
	});

	// Load the icon for the current value, it might not have been in the initial list of icons.
	const { icon: valueIcon, hasResolved: valueIconHasResolved } =
		useIcon(value);

	// If the valueIcon has resolved and selectedOption is not set, set it.
	useEffect(() => {
		if (valueIconHasResolved && valueIcon && !selectedOption) {
			setSelectedOption({
				icon: valueIcon,
				value: valueIcon.slug,
				label: valueIcon.title,
			});
		}
	}, [valueIcon, valueIconHasResolved, selectedOption]);

	useEffect(() => {
		if (icons?.length && value) {
			const icon = icons.find((i) => i.slug === value) || null;
			if (icon && selectedOption?.value !== icon.slug) {
				setSelectedOption({
					icon,
					value: icon.slug,
					label: icon.title,
				});
			}
		} else if (!value && selectedOption) {
			setSelectedOption(null);
		}
	}, [icons, value, selectedOption]); // include selectedOption here

	const onSelectIcon = (option) => {
		onChange(option?.value || null);
		setSelectedOption(option || null);
	};

	const onInputChange = (inputValue) => {
		setSearchTerm(inputValue);
	};

	let options = icons ? [...icons] : [];

	if (whiteList.length) {
		options = options.filter((option) => whiteList.includes(option.slug));
	} else if (blackList.length) {
		options = options.filter((option) => !blackList.includes(option.slug));
	}

	return (
		<BaseControl {...baseControlProps}>
			<Select
				{...controlProps}
				openMenuOnClick
				openMenuOnFocus
				classNamePrefix="react-select"
				className="lh-icon-select-control react-select"
				value={selectedOption}
				onChange={onSelectIcon}
				onInputChange={onInputChange}
				isSearchable
				inputValue={searchTerm}
				isClearable
				isLoading={!hasResolved}
				options={options.map((icon) => ({
					icon,
					value: icon.slug,
					label: icon.title,
				}))}
				getOptionLabel={(option) => (
					<div className="lh-icon-select-option-label">
						<LHIcon slug={option.icon.slug} svg={option.icon.svg} />
						<span>{option.label}</span>
					</div>
				)}
			/>
		</BaseControl>
	);
};

export default IconSelectControl;
