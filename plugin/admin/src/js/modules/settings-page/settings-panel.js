/**
 * This component represents a single settings panel on a L//H settings page.
 */

import { Icon } from '@wordpress/components';

/**
 * A component to render a settings panel on the LH Basics settings page.
 *
 * @param {Object}      props          The component props.
 * @param {string}      props.title    The title of the settings panel.
 * @param {string}      props.icon     The icon of the settings panel.
 * @param {JSX.Element} props.children The children of the settings panel.
 *
 * @return {JSX.Element} The settings panel component.
 */
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
