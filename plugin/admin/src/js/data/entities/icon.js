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
		baseURL: '/lhbasics/v1/icon',
		key: 'slug',
	},
	{
		label: 'Icons',
		name: 'icons',
		kind: 'root',
		baseURL: '/lhbasics/v1/icons',
		key: 'slug',
	},
]);

/**
 * Returns all icons.
 *
 * @param {Object} params
 */
export const useIcons = (params = {}) => {
	// eslint-disable-next-line camelcase
	const { search = '', page = 1, per_page = 20 } = params;

	// Map records to icons, pass everything else.
	const { records: icons, ...states } = useEntityRecords('root', 'icons', {
		// eslint-disable-next-line camelcase
		per_page,
		page,
		search,
	});
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
