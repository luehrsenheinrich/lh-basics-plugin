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
					<div className="help-text full-width">
						<p>
							This panel is added by the Test Plugin to test the
							slot fill system.
						</p>
					</div>
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
