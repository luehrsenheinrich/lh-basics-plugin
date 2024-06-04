/**
 * This file is used to create a slotfill for the main settings page.
 */

/**
 * External dependencies
 */
import { createSlotFill } from '@wordpress/components';

/**
 * Create the main settings slotfill.
 */
const { Fill: MainSettings, Slot: MainSettingsSlot } =
	createSlotFill('LHMainSettings');

export { MainSettings, MainSettingsSlot };
