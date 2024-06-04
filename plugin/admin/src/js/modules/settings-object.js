/**
 * Internal dependencies
 */
import { MainSettings } from './main-settings-slotfill';
import SettingsPanel from './settings-page/settings-panel';

/**
 * This is the settings page object where we expose methods and properties
 * that are used to extend the settings page.
 *
 * @type {Object} The settings page object.
 */
const settingsObject = {
	MainSettings,
	SettingsPanel,
};

export default settingsObject;
