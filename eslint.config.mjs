import wordpress from '@wordpress/eslint-plugin';

export default [
	...wordpress.configs.recommended,
	{
		settings: {
			'import/resolver': {
				node: true,
			},
		},
		rules: {
			'import/no-unresolved': [
				'error',
				{
					ignore: ['^@wordpress/'],
				},
			],
			'import/no-extraneous-dependencies': 'off',
			'import/named': 'off',
			'import/default': 'off',
		},
	},
];
