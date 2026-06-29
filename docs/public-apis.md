# Public APIs and Extension Points

This document lists the LH Basics Plugin APIs that can be used by themes, project plugins, test plugins, and editor integrations. Anything not listed here should be treated as internal and may change without a compatibility promise.

## PHP Entrypoints

Use the plugin helper to reach the public service surface:

```php
use function WpMunich\basics\plugin\plugin;

$version = plugin()->get_plugin_version();
$path    = plugin()->get_plugin_path();
$url     = plugin()->get_plugin_url();
$logger  = plugin()->logger();
```

The main methods exposed by `WpMunich\basics\plugin\Plugin` are:

- `get_plugin_version()`
- `get_plugin_file()`
- `get_plugin_path()`
- `get_plugin_url()`
- `get_plugin_slug()`
- `container()`
- `logger()`
- `settings()`
- `svg()`

`WpMunich\basics\plugin\plugin_container()` exposes the PHP-DI container for advanced integration, but callers should prefer the typed helpers above. Runtime Composer dependencies are prefixed into `WpMunich\basics\plugin\Dependencies\`; do not reference `plugin/vendor-prefixed` classes from external code.

## Settings Modules

The Luehrsen // Heinrich settings screen uses `active_modules` as its module option. Add project-specific modules with the `lhagentur_available_modules` filter:

```php
add_filter(
	'lhagentur_available_modules',
	function( array $modules ): array {
		$modules[] = array(
			'title'       => __( 'My Module', 'textdomain' ),
			'description' => __( 'Enables my feature.', 'textdomain' ),
			'slug'        => 'my_module',
		);

		return $modules;
	}
);
```

Check module state through the Settings component:

```php
$is_active = plugin()->settings()->is_module_active( 'my_module' );
```

The result can be overridden with:

- `lhagentur_is_module_active`

Built-in module slugs:

- `disable_comments`
- `gravity_forms`
- `lazysizes`
- `lightbox`
- `css-vars-helper`
- `logs`

## Logging

The logger is available through `plugin()->logger()` and implements `WpMunich\basics\plugin\Logging\Logger_Interface`, a PSR-3-style contract with these methods:

- `emergency( string $message, array $context = array() )`
- `alert( string $message, array $context = array() )`
- `critical( string $message, array $context = array() )`
- `error( string $message, array $context = array() )`
- `warning( string $message, array $context = array() )`
- `notice( string $message, array $context = array() )`
- `info( string $message, array $context = array() )`
- `debug( string $message, array $context = array() )`
- `log( string $level, string $message, array $context = array() )`

Example:

```php
plugin()->logger()->info(
	'Imported record {record_id}.',
	array( 'record_id' => 123 )
);
```

The log file lives below `lh/lh.log` in the WordPress uploads directory. The `logs` module adds the admin log viewer and registers:

- Option: `lhbasicsp_log_severity`
- REST route: `GET /wp-json/lhbasicsp/v1/logs`
- REST route: `DELETE /wp-json/lhbasicsp/v1/logs`

Log routes require `manage_options`.

## SVG and Icons

The SVG component is available through `plugin()->svg()`.

```php
$svg = plugin()->svg()->get_svg(
	'img/icons/slashes.svg',
	array(
		'attributes' => array(
			'class' => 'slashes-svg',
			'fill'  => '#26b8ff',
		),
	)
);
```

Public methods:

- `get_icon_library()`
- `get_svg( string $pointer, array $args = array() )`
- `get_admin_menu_icon( string $path )`

Register icons with the exact filter name:

```php
use WpMunich\basics\plugin\SVG\Icon;

add_filter(
	'lhagentur_svg_icon_library_icons',
	function( array $icons ): array {
		$icons[] = new Icon(
			get_stylesheet_directory() . '/img/icons/my-icon.svg',
			'my-icon',
			__( 'My Icon', 'textdomain' )
		);

		return $icons;
	}
);
```

Additional SVG hooks:

- `basicsp_rest_get_svg_response`
- `wpm_svg_allowed_attributes`

Public icon REST routes:

- `GET /wp-json/lhbasics/v1/icons/`
- `GET /wp-json/lhbasics/v1/icons/{slug}/`
- `GET /wp-json/lhbasics/v1/icon/{slug}/` for compatibility

The icons collection route supports `page`, `per_page`, `search`, `slugs`, and `must_include` query parameters. Icon REST routes are publicly readable.

## Admin JavaScript

The plugin registers these admin script/style handles:

- `lhbasics`
- `lhbasics-blocks-helper`
- `lhbasicsp-admin-components`
- `lhagentur-settings-page`

`lhbasics` exposes the settings integration globally:

```js
const { MainSettings, SettingsPanel } = window.lhSettings;
```

For newer code, use:

```js
const { MainSettings, SettingsPanel } = window.lhbasics.settings;
```

Extend the General settings tab with the `lhbasics.settings` JavaScript filter:

```js
import { addFilter } from '@wordpress/hooks';

function addSettingsFill() {
	const { MainSettings, SettingsPanel } = window.lhbasics.settings;

	return (props) => (
		<MainSettings>
			<SettingsPanel title="Project Settings" icon="admin-generic">
				{/* project controls */}
			</SettingsPanel>
		</MainSettings>
	);
}

addFilter('lhbasics.settings', 'project/settings', addSettingsFill);
```

The settings page localizes `window.lhagenturSettings` with:

- `pluginUrl`
- `restUrl`
- `modules`

## Admin Components

When `lhbasics-blocks-helper` is enqueued, components are available under `window.lhbasics.components`:

- `IconSelectControl`
- `LHIcon`
- `EntitySelectControl`
- `PostSelectControl`
- `TaxonomySelectControl`
- `SearchSelectControl`
- `MediaSelectControl`
- `WeblinkControl`
- `WeblinkSetting`
- `WeblinkToolbarButton`
- `TinyMCE`

TinyMCE support is opt-in:

```php
add_filter( 'lhbasics_use_tinymce', '__return_true' );
add_filter( 'lhbasics_tinymce_css_uri', fn() => get_stylesheet_directory_uri() . '/editor.css' );
```

## Style Helpers

The `css-vars-helper` module generates helper CSS variables from the theme color palette. Customize generated variables with:

```php
add_filter(
	'lhagentur_color_helper_vars',
	function( array $vars ): array {
		$vars['--current-border-color'] = '.has-%s-border-color';

		return $vars;
	}
);
```

Keys are CSS custom properties. Values are `sprintf` selectors where `%s` receives the theme color slug.
