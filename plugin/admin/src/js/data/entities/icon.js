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
		label: 'Icons',
		name: 'icon',
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
 * @param {string} [params.must_include] - A single icon slug.
 * @return {Object} An object containing the icons (as `records`) and other state properties.
 */
export const useIcons = (params = {}) => {
	const { search = '', page = 1, per_page = 20, must_include } = params;
	const { records: icons, ...states } = useEntityRecords('root', 'icon', {
		search,
		page,
		per_page,
		must_include,
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
	const { record: icon, ...states } = useEntityRecord('root', 'icon', slug);
	return { icon, ...states };
};
