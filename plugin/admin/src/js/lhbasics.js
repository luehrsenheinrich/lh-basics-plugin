/**
 * This file exposes some key variables to the global scope.
 */

import SettingsObject from './modules/settings-object';

/**
 * This exists because of BC reasons.
 */
window.lhSettings = SettingsObject;

/**
 * We expose our tools and settings to the window.lhbasics object.
 */
window.lhbasics = {};

/**
 * The settings object for the plugin.
 */
window.lhbasics.settings = SettingsObject;

/**
 * The components we expose.
 */
window.lhbasics.components = {};
