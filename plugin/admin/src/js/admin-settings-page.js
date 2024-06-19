import { createRoot } from '@wordpress/element';
import SettingsPage from './modules/settings-page';
import './globalVariables';

document.addEventListener('DOMContentLoaded', () => {
	const settingsPage = document.getElementById('admin-settings-page');

	if (settingsPage) {
		createRoot(settingsPage).render(<SettingsPage />);
	}
});
