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
 * @param {Object}          [params={}]           - Query parameters for filtering icons.
 * @param {string}          [params.search='']    - A search term to filter icons by title.
 * @param {number}          [params.page=1]       - The page number for pagination.
 * @param {number}          [params.per_page=20]  - The number of icons to retrieve per page.
 * @param {string|string[]} [params.must_include] - A single icon slug or array of icon slugs.
 * @param {Object}          [params.query={}]     - Additional query parameters to pass through.
 * @return {Object} An object containing the icons (as `records`) and other state properties.
 */
export const useIcons = (params = {}) => {
	const {
		search = '',
		page = 1,
		per_page = 20,
		must_include,
		query = {},
	} = params;

	// Convert must_include to comma-separated string if it's an array
	const processedMustInclude =
		Array.isArray(must_include) && must_include.length > 0
			? must_include.join(',')
			: must_include;

	// Determine the per_page value: if must_include is an array and exceeds per_page, use its length
	const effectivePerPage =
		Array.isArray(must_include) &&
		must_include.length > 0 &&
		must_include.length > per_page
			? must_include.length
			: per_page;

	const { records: icons, ...states } = useEntityRecords('root', 'icon', {
		search,
		page,
		per_page: effectivePerPage,
		must_include: processedMustInclude,
		...query,
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
