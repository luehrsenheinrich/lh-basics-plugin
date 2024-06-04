/**
 * WordPress dependencies.
 */
import { addFilter } from '@wordpress/hooks';
import { ToggleControl } from '@wordpress/components';

function slotFillTest() {
	const { MainSettings, SettingsPanel } = window.lhSettings;

	return (props) => {
		const { apiSettings, setApiSettings } = props;

		return (
			<MainSettings>
				<SettingsPanel title="Slot Fill Test" icon="admin-generic">
					<ToggleControl
						label="Use smilies"
						checked={apiSettings.use_smilies ?? false}
						onChange={(value) =>
							setApiSettings({
								...apiSettings,
								use_smilies: value,
							})
						}
					/>
				</SettingsPanel>
			</MainSettings>
		);
	};
}

addFilter('lhbasics.settings', 'slotfilltest/settings', slotFillTest);
