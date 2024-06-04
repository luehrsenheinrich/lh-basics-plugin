# Admin Settings Page

The LH Basics Plugin provides a settings page for the admin area of the site. This page is accessible via the admin menu and allows the user to configure a number of different settings for the plugin.

The intention behind this settings page is to provide a singular location for users to configure system settings, so we can avoid having to add settings in multiple locations throughout the admin area.

That why we made this page highly extensible, so you can add your own settings to it. This can be done in two ways: by registering a new module or by extending the "MainSettings" Slot with your own fill.

## Modules

A module is a simple, boolean declaration, if a certain feature is active or not. New modules can be registered by extending the array passed throught the `lhagentur_available_modules` filter.

```php
add_filter('lhagentur_available_modules', function($modules) {
	$modules['my_module'] = [
		'title' => 'My Module',
		'description' => 'This is my module',
		'slug' => 'my_module',
	];

	return $modules;
});
```

You can then check the array values in the `active_modules` option to check if the module is active or not.

```php
$active_modules = get_option( 'active_modules', array() );

$is_module_active = in_array( $module, $active_modules, true );

return apply_filters( 'lhagentur_is_module_active', $is_module_active, $module );
```

Please not the helper functions in the `WpMunich\basics\plugin\Settings\Settings` class to get the active modules and to check if a module is active.

## MainSettings Slot

The MainSettings Slot is a simple way to add your own settings to the settings page.

But first we need to actually register the settings you want to add. Please refer to the documentation for [`register_setting`](https://developer.wordpress.org/reference/functions/register_setting/) to learn how to do this.

Once you have registered your settings, you can add them to the MainSettings Slot by extending the array passed through the `lhbasics.settings` JavaScript filter.

```js
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
```
