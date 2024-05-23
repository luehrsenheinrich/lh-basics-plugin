import { createRoot } from '@wordpress/element';
import SettingsPage from './modules/settings-page';

document.addEventListener('DOMContentLoaded', () => {
	const settingsPage = document.getElementById('admin-settings-page');
	if (settingsPage) {
		createRoot(settingsPage).render(<SettingsPage />);
	}
});
