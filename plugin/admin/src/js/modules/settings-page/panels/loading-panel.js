/**
 * The loading indicator for the settings page.
 */

import { Spinner } from '@wordpress/components';

const LoadingPanel = () => {
	return (
		<div className="loading-panel">
			<Spinner />
		</div>
	);
};

export default LoadingPanel;
