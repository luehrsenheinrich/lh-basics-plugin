/**
 * WordPress dependencies.
 */
import { addFilter } from '@wordpress/hooks';
import { ToggleControl } from '@wordpress/components';
import { useState } from '@wordpress/element';

const { EntitySelectControl, PostSelectControl, TaxonomySelectControl } =
	window.lhbasics.components;

function slotFillTest(Component) {
	const { MainSettings, SettingsPanel } = window.lhSettings;

	return (props) => {
		const [selectedEntities, setSelectedEntitites] = useState({
			entity: null,
			page: null,
			tags: null,
		});
		const { apiSettings, setApiSettings } = props;

		return (
			<>
				<Component {...props} />
				<MainSettings>
					<SettingsPanel title="Slot Fill Test" icon="admin-generic">
						<div className="help-text full-width">
							<p>
								This panel is added by the Test Plugin to test
								the slot fill system.
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
						<div className="full-width">
							<p>Entity Select Control Tests</p>
							{/* "Raw" component, Posts of `post` by default */}
							<EntitySelectControl
								value={selectedEntities.entity}
								onChange={(value) =>
									setSelectedEntitites({
										...selectedEntities,
										entity: value,
									})
								}
							/>
							{/* ESC wrapper with `postType` prop */}
							<PostSelectControl
								label={'Select Pages'}
								postType={'page'}
								value={selectedEntities.page}
								onChange={(value) =>
									setSelectedEntitites({
										...selectedEntities,
										post: value,
									})
								}
							/>
							{/* ESC wrapper for taxonomies with `taxonomie` prop */}
							<TaxonomySelectControl
								label={'Select tags'}
								taxonomy={'post_tag'}
								value={selectedEntities.tags}
								onChange={(value) =>
									setSelectedEntitites({
										...selectedEntities,
										tags: value,
									})
								}
							/>
						</div>
					</SettingsPanel>
				</MainSettings>
			</>
		);
	};
}

addFilter('lhbasics.settings', 'slotfilltest/settings', slotFillTest);
