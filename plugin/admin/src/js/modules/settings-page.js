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
import { MainSettingsSlot } from './main-settings-slotfill';
import SettingsPanel from './settings-page/settings-panel';

/**
 * This is the main settings page component.
 *
 * @return {JSX.Element} The settings page component.
 */
const SettingsPage = () => {
	/**
	 * The state of whether the settings have been loaded from the API.
	 */
	const [settingsInitialized, setSettingsInitialized] = useState(false);

	/**
	 * The state of wheather the documentation has been loaded from the API.
	 */
	const [documentationInitialized, setDocumentationInitialized] =
		useState(false);

	/**
	 * The state of the currently active tab. By default it is the 'dashboard' tab.
	 */
	const [activeTab, setActiveTab] = useState('settings');

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
	 * The documentation object from the API.
	 */
	const [documentation, setDocumentation] = useState({});

	/**
	 * If the settings have not been initialized, fetch them from the API.
	 */
	if (!settingsInitialized && activeTab === 'settings') {
		apiFetch({ path: '/wp/v2/settings' }).then((response) => {
			setApiSettings(cloneDeep(response));
			setOriginalApiSettings(cloneDeep(response));
			setSettingsInitialized(true);
		});
	}

	/**
	 * If the documentation tab is active, fetch the documentation from the API.
	 */
	if (!documentationInitialized && activeTab === 'documentation') {
		apiFetch({ path: '/lhbasics/v1/documentation' }).then((response) => {
			setDocumentation(cloneDeep(response));
			setDocumentationInitialized(true);
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
		}).then(
			(response) => {
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
			},
			(e) => {
				console.error(e); // eslint-disable-line no-console
				setIsSaving(false);
				dispatch('core/notices').createNotice(
					'error',
					e.message ?? __('Error saving settings.', 'lhbasicsp'),
					{
						type: 'snackbar',
						isDismissible: true,
						icon: <Icon icon="warning" />,
					}
				);
			}
		);
	};

	return (
		<SlotFillProvider>
			<AdditionalSettings
				apiSettings={apiSettings}
				setApiSettings={setApiSettings}
			/>
			<div className="settings_header">
				<div className="settings_container">
					<div className="settings_title">
						<LHLogo
							alt={__('Luehrsen // Heinrich', 'lhbasicsp')}
							className="settings_logo"
						/>
					</div>
				</div>
			</div>

			<div className="settings_tabbar">
				<button
					className={`settings_tabbar_item ${activeTab === 'settings' ? 'active' : ''}`}
					onClick={() => setActiveTab('settings')}
				>
					{__('Settings', 'lhbasicsp')}
				</button>
				<button
					className={`settings_tabbar_item ${activeTab === 'documentation' ? 'active' : ''}`}
					onClick={() => setActiveTab('documentation')}
				>
					{__('Documentation', 'lhbasicsp')}
				</button>
			</div>
			{activeTab === 'settings' && (
				<div className="settings_main tab_settings">
					{!settingsInitialized && <LoadingPanel />}
					{settingsInitialized && (
						<>
							<ModulesPanel
								apiSettings={apiSettings}
								setApiSettings={setApiSettings}
							/>
							<MainSettingsSlot />
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
			)}
			{activeTab === 'documentation' && (
				<div className="settings_main tab_documentation">
					{!documentationInitialized && <LoadingPanel />}
					{documentationInitialized &&
						documentation.map((item) => (
							<SettingsPanel
								key={item.slug}
								title={item.headline}
								icon={item.icon ?? 'admin-generic'}
							>
								<div
									className="full-width"
									dangerouslySetInnerHTML={{
										__html: item.html_content,
									}}
								/>
							</SettingsPanel>
						))}
				</div>
			)}
			<div className="settings_notices">
				<Notices />
			</div>
		</SlotFillProvider>
	);
};

export default SettingsPage;
