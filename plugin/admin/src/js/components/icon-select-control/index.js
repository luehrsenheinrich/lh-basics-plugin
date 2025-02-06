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

/**
 * Custom hooks for icon data.
 */
import { useIcons, useIcon } from '../../data/entities/icon';

/**
 * Internal dependency: a component for rendering an icon.
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
	// Local state to hold the currently selected option.
	const [selectedOption, setSelectedOption] = useState(null);
	// Local state to control the search term for filtering icons.
	const [searchTerm, setSearchTerm] = useState('');

	// Get base control props for consistent label and help text handling.
	const { baseControlProps, controlProps } = useBaseControlProps({
		label,
		help,
	});

	// Retrieve icons matching the search term.
	// The "must_include" parameter ensures that the icon matching the current "value" is always present.
	const { icons, hasResolved } = useIcons({
		search: searchTerm,
		must_include: value,
	});

	// Load the icon for the current value using a dedicated hook.
	// This is useful if the icon is not present in the initial list.
	const { icon: valueIcon, hasResolved: valueIconHasResolved } =
		useIcon(value);

	/**
	 * Consolidated effect to update the selected option.
	 *
	 * If no "value" is provided, the selected option is cleared.
	 * Otherwise, this effect first prefers the dedicated valueIcon (if resolved),
	 * and then falls back to searching the icon in the icons list.
	 * The selected option is only updated if it differs from the current state.
	 */
	useEffect(() => {
		// Clear the selection if no value is provided.
		if (!value) {
			if (selectedOption !== null) {
				setSelectedOption(null);
			}
			return;
		}

		// Determine the new icon data from useIcon (if available) or from the icons list.
		let newIcon = null;
		if (valueIconHasResolved && valueIcon) {
			newIcon = valueIcon;
		} else if (icons && icons.length) {
			newIcon = icons.find((i) => i.slug === value) || null;
		}

		// Update the selected option if the icon exists and is different from the current selection.
		if (
			newIcon &&
			(!selectedOption || selectedOption.value !== newIcon.slug)
		) {
			setSelectedOption({
				icon: newIcon,
				value: newIcon.slug,
				label: newIcon.title,
			});
		}
	}, [value, valueIcon, valueIconHasResolved, icons, selectedOption]);

	/**
	 * Event handler for when an icon is selected.
	 *
	 * Updates both the parent value (via onChange) and the internal selected option.
	 *
	 * @param {Object|null} option The selected option, or null if cleared.
	 */
	const onSelectIcon = (option) => {
		onChange(option?.value || null);
		setSelectedOption(option || null);
	};

	/**
	 * Event handler for input change in the search field.
	 *
	 * Updates the local searchTerm state.
	 *
	 * @param {string} inputValue The new input value.
	 */
	const onInputChange = (inputValue) => {
		setSearchTerm(inputValue);
	};

	// Prepare the icons as options for react-select.
	// Also apply whitelist or blacklist filtering if provided.
	let options = icons ? [...icons] : [];
	if (whiteList.length > 0) {
		options = options.filter((option) => whiteList.includes(option.slug));
	} else if (blackList.length > 0) {
		options = options.filter((option) => !blackList.includes(option.slug));
	}

	// Map each icon to the format required by react-select.
	const selectOptions = options.map((icon) => ({
		icon,
		value: icon.slug,
		label: icon.title,
	}));

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
				options={selectOptions}
				/**
				 * Custom renderer for the options in the dropdown.
				 * Renders the icon (via LHIcon) along with its title.
				 * @param {Object} option
				 */
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
