/**
 * WordPress dependencies
 */
import { useState } from '@wordpress/element';
import {
	Button,
	Icon,
	SlotFillProvider,
	withFilters,
} from '@wordpress/components';
import { dispatch } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';

/**
 * External dependencies
 */
import { cloneDeep, isEqual } from 'lodash';

/**
 * Internal dependencies
 */
import ModulesPanel from './settings-page/panels/modules-panel';
import LoadingPanel from './settings-page/panels/loading-panel';
import Notices from '../components/settings-notices';
import LHLogo from '../../../../img/icons/lh_logo.svg';
import MainSettingsSlotFill from './main-settings-slotfill';

const SettingsPage = () => {
	/**
	 * The state of whether the settings have been loaded from the API.
	 */
	const [settingsInitialized, setSettingsInitialized] = useState(false);

	/**
	 * If we are currently saving the settings.
	 */
	const [isSaving, setIsSaving] = useState(false);

	/**
	 * The settings object from the API.
	 */
	const [apiSettings, setApiSettings] = useState({});

	/**
	 * The original settings object from the API, which is used to determine
	 * whether settings have changed.
	 */
	const [originalApiSettings, setOriginalApiSettings] = useState({});

	/**
	 * If the settings have not been initialized, fetch them from the API.
	 */
	if (!settingsInitialized) {
		apiFetch({ path: '/wp/v2/settings' }).then((response) => {
			setApiSettings(cloneDeep(response));
			setOriginalApiSettings(cloneDeep(response));
			setSettingsInitialized(true);
		});
	}

	const AdditionalSettings = withFilters('lhbasics.settings')(() => <></>);

	const onSaveSettings = () => {
		// Compare the current settings with the original settings to determine
		// which settings have changed.
		const changedSettings = Object.keys(apiSettings).filter(
			(key) => !isEqual(apiSettings[key], originalApiSettings[key])
		);

		// If no settings have changed, do nothing.
		if (changedSettings.length === 0) {
			dispatch('core/notices').createNotice(
				'info',
				__('No changes to save', 'lhbasicsp'),
				{
					type: 'snackbar',
					isDismissible: true,
					icon: <Icon icon="info" />,
				}
			);

			return;
		}

		setIsSaving(true);

		// Create a new object containing only the changed settings.
		const changedSettingsData = {};
		changedSettings.forEach((key) => {
			changedSettingsData[key] = apiSettings[key];
		});

		// Save the settings.
		apiFetch({
			path: '/wp/v2/settings',
			method: 'POST',
			data: changedSettingsData,
		}).then((response) => {
			setIsSaving(false);
			setApiSettings(cloneDeep(response));
			setOriginalApiSettings(cloneDeep(response));
			dispatch('core/notices').createNotice(
				'success',
				__('Settings Saved.', 'lhbasicsp'),
				{
					type: 'snackbar',
					isDismissible: true,
					icon: <Icon icon="saved" />,
					actions: [
						{
							label: __('Refresh', 'lhbasicsp'),
							onClick: () => {
								window.location.reload();
							},
						},
					],
				}
			);
		});
	};

	return (
		<SlotFillProvider>
			<AdditionalSettings />
			<div className="settings_header">
				<div className="settings_container">
					<div className="settings_title">
						<LHLogo
							alt={__('Luehrsen // Heinrich', 'lhbasicsp')}
							className="settings_logo"
						/>
						<br />
						<h1>{__('System Settings', 'lhbasicsp')}</h1>
					</div>
				</div>
			</div>
			<div className="settings_main">
				{!settingsInitialized && <LoadingPanel />}
				{settingsInitialized && (
					<>
						<ModulesPanel
							apiSettings={apiSettings}
							setApiSettings={setApiSettings}
						/>
						<MainSettingsSlotFill.Slot
							fillProps={{ apiSettings, setApiSettings }}
						/>
						<div className="settings_buttons">
							<Button
								variant="primary"
								isBusy={isSaving}
								disabled={isSaving}
								onClick={() => onSaveSettings()}
							>
								{__('Save', 'lhbasicsp')}
							</Button>
						</div>
					</>
				)}
			</div>
			<div className="settings_notices">
				<Notices />
			</div>
		</SlotFillProvider>
	);
};

export default SettingsPage;
