import { dispatch } from '@wordpress/data';
import {
	store as coreStore,
	useEntityRecord,
	useEntityRecords,
} from '@wordpress/core-data';

// Register icons as entities.
dispatch(coreStore).addEntities([
	{
		label: 'Icon',
		name: 'icon',
		kind: 'single',
		baseURL: '/lhpbpp/v1/icon',
		key: 'slug',
	},
	{
		label: 'Icons',
		name: 'icons',
		kind: 'root',
		baseURL: '/lhpbpp/v1/icons',
		key: 'slug',
	},
]);

/**
 * Returns all icons.
 */
export const useIcons = () => {
	// Map records to icons, pass everything else.
	const { records: icons, ...states } = useEntityRecords('root', 'icons');
	return { icons, ...states };
};

/**
 * Returns a single icon.
 *
 * @param {string} slug - Icon slug.
 *
 * @return {Object} - Icon object.
 */
export const useIcon = (slug) => {
	// Map record to icon, pass everything else.
	const { record: icon, ...states } = useEntityRecord('single', 'icon', slug);
	return { icon, ...states };
};
