# TinyMCE

TinyMCE is a wrapper around WordPress TinyMCE (`window.tinymce`) for usage in plugin controls.
It is intended for admin usage where a classic editor-like field is needed inside custom settings or UI.

## Requirements

- Enqueue `lhbasicsp-admin-components` and `lhbasics-blocks-helper`.
- Enable TinyMCE dependency loading via the `lhbasics_use_tinymce` filter.
- Optionally pass additional editor CSS via the `lhbasics_tinymce_css_uri` filter.

## Example Usage

```jsx
import { useState } from '@wordpress/element';
const { TinyMCE } = window.lhbasics.components;

const MyEditorField = () => {
	const [content, setContent] = useState('');

	return (
		<TinyMCE
			id="my-editor"
			label="Text"
			value={content}
			onChange={setContent}
		/>
	);
};

export default MyEditorField;
```

## Props

### `id`

- **Type:** `string`
- **Required:** No
- **Default:** `'lh-editor'`
- **Description:** DOM id used for the `<textarea>` and TinyMCE instance.

### `value`

- **Type:** `string`
- **Required:** No
- **Description:** Initial editor content.

### `onChange`

- **Type:** `(value: string) => void`
- **Required:** No
- **Description:** Callback invoked on editor blur with the current TinyMCE content.

### BaseControl props

- **Type:** `BaseControl` props
- **Required:** No
- **Description:** Additional props are forwarded to `@wordpress/components` `BaseControl`.

## Filters

### `lhbasics_use_tinymce`

Enable or disable TinyMCE integration for the blocks helper script dependencies.

- **Type:** `bool`
- **Default:** `false`

```php
add_filter(
	'lhbasics_use_tinymce',
	static function( $use_tinymce ) {
		return true;
	}
);
```

### `lhbasics_tinymce_css_uri`

Set an additional CSS URI for TinyMCE editor content styles, usually the theme variables stylesheet.

- **Type:** `string`
- **Default:** `''`

```php
add_filter(
	'lhbasics_tinymce_css_uri',
	static function( $css_uri ) {
		return get_theme_file_uri( '/dist/css/vars.min.css' );
	}
);
```

## Theme Hint: Enqueue Additional Editor Styles

In addition to `content_css` passed to TinyMCE, a theme should enqueue related editor styles on `admin_init`.

```php
add_action(
	'admin_init',
	static function() {
		$css_vars_uri          = get_theme_file_uri( '/dist/css/vars.min.css' );
		$css_editor_styles_uri = get_theme_file_uri( '/dist/css/editor-styles.min.css' );
		wp_enqueue_style( 'namespace-editor-vars', $css_vars_uri, array(), theme()->get_theme_version() );
		wp_enqueue_style( 'namespace-editor-styles', $css_editor_styles_uri, array(), theme()->get_theme_version() );
	}
);
```
