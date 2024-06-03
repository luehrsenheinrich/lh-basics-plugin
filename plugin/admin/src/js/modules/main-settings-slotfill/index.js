/**
 * This file is used to create a slotfill for the main settings page.
 */

/**
 * External dependencies
 */
import { createSlotFill } from '@wordpress/components';

const { Fill, Slot } = createSlotFill('LHMainSettings');

const LHMainSettingsSlotFill = {
	Fill,
	Slot,
};

/**
 * Expose the slotfill to window.
 */
window.LHMainSettingsSlotFill = LHMainSettingsSlotFill;

export default LHMainSettingsSlotFill;
