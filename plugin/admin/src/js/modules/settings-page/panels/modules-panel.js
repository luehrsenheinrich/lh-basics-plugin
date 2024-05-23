/**
 * The modules panel for the settings page.
 */
import { ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import SettingsPanel from './../settings-panel';

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

	return (
		<SettingsPanel
			title={__('Modules', 'lhagenturp')}
			icon="admin-settings"
		>
			<div className="help-text full-width">
				<p>
					{__(
						'Choose which modules you would like to enable on your site.',
						'lhagenturp'
					)}
				</p>
			</div>
			<ToggleControl
				checked={isModuleActive('cpt_lh_testimonial')}
				label={__('Testimonials', 'lhagenturp')}
				help={__(
					'If enabled, the testimonials module will be active.',
					'lhagenturp'
				)}
				onChange={(value) =>
					setModuleActive('cpt_lh_testimonial', value)
				}
				__nextHasNoMarginBottom
			/>
			<ToggleControl
				checked={isModuleActive('cpt_lh_client')}
				label={__('Clients', 'lhagenturp')}
				help={__(
					'If enabled, the clients module will be active.',
					'lhagenturp'
				)}
				onChange={(value) => setModuleActive('cpt_lh_client', value)}
				__nextHasNoMarginBottom
			/>
		</SettingsPanel>
	);
};

export default ModulesPanel;
