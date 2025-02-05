/* eslint-disable camelcase */
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
 * Retrieves a list of icons with optional filtering and pagination.
 *
 * @param {Object} [params={}]          - Query parameters for filtering icons.
 * @param {string} [params.search='']   - A search term to filter icons by title.
 * @param {number} [params.page=1]      - The page number for pagination.
 * @param {number} [params.per_page=20] - The number of icons to retrieve per page.
 * @return {Object} An object containing the icons (as `records`) and other state properties.
 */
export const useIcons = (params = {}) => {
	const { search = '', page = 1, per_page = 20 } = params;
	const { records: icons, ...states } = useEntityRecords('root', 'icons', {
		search,
		page,
		per_page,
	});
	return { icons, ...states };
};

/**
 * Retrieves a single icon by its slug.
 *
 * @param {string} slug - The unique slug identifier for the icon.
 * @return {Object} An object containing the icon (as `record`) and other state properties.
 */
export const useIcon = (slug) => {
	const { record: icon, ...states } = useEntityRecord('single', 'icon', slug);
	return { icon, ...states };
};
