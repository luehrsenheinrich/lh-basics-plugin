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
 * @return {Component} The settings panel component.
 */
const ModulesPanel = (props) => {
	const { apiSettings, setApiSettings } = props;

	const isModuleActive = (module) => {
		const activeModules = apiSettings.active_modules || [];

		return activeModules.includes(module);
	};

	const setModuleActive = (module, active) => {
		const activeModules = apiSettings.active_modules || [];

		if (active) {
			setApiSettings({
				...apiSettings,
				active_modules: [...new Set([...activeModules, module])],
			});

			return;
		}

		setApiSettings({
			...apiSettings,
			active_modules: activeModules.filter(
				(activeModule) => activeModule !== module
			),
		});
	};

	const availableModules = get(window, 'lhagenturSettings.modules', []);

	return (
		<SettingsPanel title={__('Modules', 'lhbasicsp')} icon="admin-settings">
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
