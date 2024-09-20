/**
 * The modules panel for the settings page.
 */
import { ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import SettingsPanel from './../settings-panel';
import { get } from 'lodash';

/**
 * A component to render the modules panel on the LH Basics settings page.
 *
 * @param {Object}   props                The component props.
 * @param {Object}   props.apiSettings    The API settings object.
 * @param {Function} props.setApiSettings The function to set the API settings.
 *
 * @return {JSX.Element} The settings panel component.
 */
const ModulesPanel = (props) => {
	const { apiSettings, setApiSettings } = props;

	const isModuleActive = (module) => {
		const activeModules = apiSettings.active_modules || [];

		return activeModules.includes(module);
	};

	const setModuleActive = (module, active) => {
		// Detach the active modules array from the settings object.
		const settingsData = { ...apiSettings };

		const activeModules = settingsData.active_modules || [];

		if (active) {
			activeModules.push(module);
		} else {
			const index = activeModules.indexOf(module);

			if (index > -1) {
				activeModules.splice(index, 1);
			}
		}

		setApiSettings({ ...settingsData });
	};

	const availableModules = get(window, 'lhagenturSettings.modules', []);

	return (
		<SettingsPanel
			title={__('Modules', 'lhbasicsp')}
			icon="admin-settings"
		>
			<div className="help-text full-width">
				<p>
					{__(
						'Choose which modules you would like to enable on your site.',
						'lhbasicsp'
					)}
				</p>
			</div>
			{availableModules.map((module) => (
				<ToggleControl
					key={module.slug}
					checked={isModuleActive(module.slug)}
					label={module.title}
					help={module.description}
					onChange={(value) => setModuleActive(module.slug, value)}
					__nextHasNoMarginBottom
				/>
			))}
		</SettingsPanel>
	);
};

export default ModulesPanel;
