module.exports = {
	plugins: [
		['postcss-import'],
		[
			'@csstools/postcss-global-data',
			// Add when needed.
			// Disabled for now.
			// {
			// 	files: [
			// 		'./plugin/src/css/vars.css',
			// 		'./plugin/src/css/vars/_media-queries.css',
			// 	],
			// },
		],
		[
			'postcss-preset-env',
			{
				stage: 1,
			},
		],
		['cssnano'],
	],
};
