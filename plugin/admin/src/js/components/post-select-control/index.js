/**
 * A control to select a set of posts.
 */

/**
 * WordPress dependencies.
 */
import { useMemo } from '@wordpress/element';
import {
	BaseControl,
	Spinner,
	useBaseControlProps,
} from '@wordpress/components';
import { useEntityRecords } from '@wordpress/core-data';
import { __ } from '@wordpress/i18n';

/**
 * External dependencies.
 */
import Select from 'react-select';
import { DndContext } from '@dnd-kit/core';
import { restrictToParentElement } from '@dnd-kit/modifiers';
import {
	arrayMove,
	horizontalListSortingStrategy,
	SortableContext,
} from '@dnd-kit/sortable';

import {
	MultiValue,
	MultiValueRemove,
	MultiValueContainer,
} from './multi-value';

const EntitySelectControl = ({
	kind: entityKind = 'postType',
	name: entityName = 'post',
	value,
	onChange,
	query = {},
	label = __('Select posts', 'lhpbpp'),
	help = '',
	multiple = true,
}) => {
	const { baseControlProps, controlProps } = useBaseControlProps({
		label,
		help,
	});
	const { records: entityRecords = [], isResolving } = useEntityRecords(
		entityKind,
		entityName,
		{
			per_page: -1, // This actually returns ALL records, not just 100.
			context: 'view',
			...query,
		}
	);

	const options = entityRecords
		?.filter(({ name, title }) => {
			// Don't show entities with empty titles.
			const optionLabel = title?.rendered || title || name;
			return typeof optionLabel === 'string' && optionLabel.length > 0;
		})
		?.map(({ id, name, title }) => ({
			value: id,
			label: title?.rendered || title || name,
		}));

	const selectValue = useMemo(
		() => getSelectValue(value, options, multiple),
		[value, options, multiple]
	);

	/**
	 * Handle a change in the selected option.
	 *
	 * @param {*} option The selected option
	 */
	const onSelectPost = (option) => {
		onChange(option || {});
	};

	const onSortEnd = (event) => {
		const { active, over } = event;

		if (!active || !over) return;

		const oldIndex = selectValue.findIndex(
			(item) => item.value === active.id
		);
		const newIndex = selectValue.findIndex(
			(item) => item.value === over.id
		);

		const sortedItems = arrayMove(selectValue, oldIndex, newIndex);

		onChange(sortedItems);
	};

	return (
		<BaseControl {...baseControlProps}>
			{isResolving && <Spinner />}
			{!isResolving && (
				<>
					{multiple && (
						<DndContext
							modifiers={[restrictToParentElement]}
							onDragEnd={onSortEnd}
						>
							<SortableContext
								items={options?.map((o) => o.value) || []}
								strategy={horizontalListSortingStrategy}
							>
								<Select
									{...controlProps}
									value={selectValue}
									onChange={onSelectPost}
									options={options}
									getOptionLabel={(option) => option.label}
									isClearable
									isMulti
									isSearchable={true}
									openMenuOnClick={true}
									openMenuOnFocus={true}
									closeMenuOnSelect={false}
									className={
										('react-select',
										'react-select-multi',
										'react-select-sortable')
									}
									classNamePrefix={'react-select'}
									components={{
										MultiValue,
										MultiValueContainer,
										MultiValueRemove,
									}}
								/>
							</SortableContext>
						</DndContext>
					)}
					{!multiple && (
						<Select
							value={selectValue}
							onChange={onSelectPost}
							options={options}
							getOptionLabel={(option) => option.label}
							isClearable
							isSearchable={true}
							openMenuOnClick={true}
							openMenuOnFocus={true}
							closeMenuOnSelect={false}
							className={'react-select'}
							classNamePrefix={'react-select'}
						/>
					)}
				</>
			)}
		</BaseControl>
	);
};

export const PostSelectControl = (props) => {
	return <EntitySelectControl {...props} name={'post'} />;
};

export default EntitySelectControl;

function getSelectValue(value, options = [], multiple) {
	if (multiple) {
		return value?.map((v) => {
			return options.find((o) => o.value === v.value);
		});
	}

	return options.find((o) => o.value === value?.value);
}
