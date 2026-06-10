import { dispatch } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';

dispatch(coreStore).addEntities([
	{
		name: 'lhSearch',
		kind: 'root',
		baseURL: '/wp/v2/search',
	},
]);
