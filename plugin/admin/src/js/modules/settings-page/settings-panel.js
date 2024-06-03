/**
 * This component represents a single settings panel on a L//H settings page.
 */

import { Icon } from '@wordpress/components';

const SettingsPanel = ({ title, icon, children }) => {
	return (
		<div className="settings-panel">
			<div className="settings-panel-header">
				<h2 className="settings-panel-title">
					<Icon icon={icon} />
					{title}
				</h2>
			</div>
			<div className="settings-panel-body">{children}</div>
		</div>
	);
};

export default SettingsPanel;
