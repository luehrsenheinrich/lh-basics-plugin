import { dispatch } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';

dispatch(coreStore).addEntities([
	{
		name: 'lhsearch',
		kind: 'root',
		baseURL: '/wp/v2/search',
	},
]);
