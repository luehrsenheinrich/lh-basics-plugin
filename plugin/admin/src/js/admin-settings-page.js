import { createRoot } from '@wordpress/element';
import SettingsPage from './modules/settings-page';
import SettingsObject from './modules/settings-object';

/**
 * Expose the settings object to the global scope.
 */
window.lhSettings = SettingsObject;

document.addEventListener('DOMContentLoaded', () => {
	const settingsPage = document.getElementById('admin-settings-page');

	if (settingsPage) {
		createRoot(settingsPage).render(<SettingsPage />);
	}
});
