/**
 * WordPress dependencies.
 */
import { addFilter } from '@wordpress/hooks';
import { Button, ToggleControl } from '@wordpress/components';
import { dispatch } from '@wordpress/data';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';

const {
	EntitySelectControl,
	PostSelectControl,
	TaxonomySelectControl,
	MediaSelectControl,
	WeblinkSetting,
} = window.lhbasics.components;

function slotFillTest(Component) {
	const { MainSettings, SettingsPanel } = window.lhSettings;

	return (props) => {
		const [isCreatingLogEntry, setIsCreatingLogEntry] = useState(false);
		const [mediaId, setMediaId] = useState(null);
		const [selectedEntities, setSelectedEntitites] = useState({
			entity: null,
			page: null,
			tags: null,
		});
		const [weblink, setWeblink] = useState({
			title: '',
			url: '',
			opensInNewTab: false,
			id: null, // Not needed in non-persistent state, but added for demonstration.
		});

		const { apiSettings, setApiSettings } = props;

		const createLogEntry = () => {
			setIsCreatingLogEntry(true);

			apiFetch({
				path: '/lhbasicsp-test/v1/log',
				method: 'POST',
			}).then(
				(response) => {
					setIsCreatingLogEntry(false);
					dispatch('core/notices').createNotice(
						'success',
						response.message ??
							__('Test log entry created.', 'lhbasicsp'),
						{
							type: 'snackbar',
							isDismissible: true,
						}
					);
					window.dispatchEvent(
						new CustomEvent('lhbasicsp:test-log-created')
					);
				},
				(error) => {
					setIsCreatingLogEntry(false);
					dispatch('core/notices').createNotice(
						'error',
						error.message ??
							__('Error creating test log entry.', 'lhbasicsp'),
						{
							type: 'snackbar',
							isDismissible: true,
						}
					);
				}
			);
		};

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
							<Button
								variant="secondary"
								isBusy={isCreatingLogEntry}
								disabled={isCreatingLogEntry}
								onClick={createLogEntry}
							>
								{__('Create test log entry', 'lhbasicsp')}
							</Button>
						</div>
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
						<div className="full-width">
							<p>Entity Select Control Tests</p>
							<MediaSelectControl
								value={mediaId}
								onChange={setMediaId}
								isSelected={true}
							/>
						</div>
						<WeblinkSetting
							label={'Weblink Setting'}
							value={weblink}
							onChange={setWeblink}
						/>
					</SettingsPanel>
				</MainSettings>
			</>
		);
	};
}

addFilter('lhbasics.settings', 'slotfilltest/settings', slotFillTest);
