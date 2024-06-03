import { addFilter } from '@wordpress/hooks';

function slotFillTest() {
	const Fill = window.LHMainSettingsSlotFill.Fill;

	return () => (
		<Fill>
			test
		</Fill>
	);
}

addFilter('lhbasics.settings', 'slotfilltest/settings', slotFillTest);
