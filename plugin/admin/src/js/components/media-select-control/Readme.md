# MediaSelectControl

IconSelectControl is a component for selecting media from the media library.
Besides `lhbasics-blocks-helper` script and `lhbasicsp-admin-components`
this component requires to enqueue wp media assets as well with `wp_enqueue_media()`

## Example Usage

```jsx
import { useState } from '@wordpress/element';
const { MediaSelectControl } = window.lhbasics.components;

const MyIconSelector = () => {
	const [ mediaId, setMediaId ] = useState( null );

	return (
		<MediaSelectControl
			value={mediaId}
			onChange={setMediaId}
			isSelected={true}
		/>
	);
};

export default MyIconSelector;
```

## Props

### `label`

- **Type:** `string | ReactNode`
- **Required:** No
- **Default:** `'Select image'`
- **Description:** The text label displayed above the control.

### `labelSet`

- **Type:** `string | ReactNode`
- **Required:** No
- **Default:** `'Set image'`
- **Description:** The text label displayed at the "set {media}" action control.

### `help`

- **Type:** `string | ReactNode`
- **Required:** No
- **Default:** `''`
- **Description:** Additional help text that provides guidance about the control. This text is associated with the input for accessibility.

### `value`

- **Type:** `number`
- **Required:** No
- **Description:** The id of the currently selected media. If no value is provided, the control renders a "Set {media}" button. If a value is present, a preview with "Replace / Remove" buttons is rendered.

### `onChange`

- **Type:** `(value: number | null) => void`
- **Required:** Yes
- **Description:** Callback function invoked when the media selection changes. It receives the new media id, or `null` if the selection is cleared.

### `allowedTypes`

- **Type:** `string[]`
- **Required:** No
- **Default:** `['image]`
- **Description:** An array of mime types to allow to select from.

### `imageSize`

- **Type:** `string`
- **Required:** No
- **Default:** `'full'`
- **Description:** The image size to use for the preview. (Note: No effect on other media types besides 'image' at the moment.)

### `isSelected`

- **Type:** `boolean`
- **Required:** No
- **Default:** `false`
- **Description:** Wether the control is selected or not. This has effect on displaying the "Replace / Remove" buttons. If MediaSelectControl is used within a button render, provide the block's `isSelected` property. If used in the sidebar or settings page simply pass `true`.

### `className`

- **Type:** `string`
- **Required:** No
- **Default:** ``
- **Description:** Additional class name(s) for the control.

### `classNamePreview`

- **Type:** `string`
- **Required:** No
- **Default:** ``
- **Description:** Additional class name(s) for the media preview.

### `getPreview`

- **Type:** `({ media }) => React.Component`
- **Required:** No
- **Default:** ``
- **Description:** Function to override the preview. Media info is provided as an argument.
- **Usage Example:** `getPreview={({ media }) => <img src={media.url} />}`


## Optional focal point

Pass `onFocalPointChange` to enable the Core `FocalPointPicker` for image media.
The picker replaces the thumbnail as the single interactive image preview, only
while the control is selected. Replace/Remove actions remain below it. Non-image media and existing callers are unchanged.

- `focalPoint`: `{ x: number, y: number }`, normalized coordinates from 0 to 1.
  Defaults visually to `{ x: 0.5, y: 0.5 }`; rendering does not write a value.
- `onFocalPointChange`: receives the new point during drag and numeric editing.
- `labelFocalPoint`: optional label, default `Focal point`; pass an empty string
  to omit the extra legend. Native coordinate labels remain visible.

The consumer owns storage, validation and image-change/reset policy. The control
does not write post or attachment metadata. For cropped images, apply the point
as CSS `object-position: x * 100% y * 100%` in the consumer's preview and frontend.

```jsx
<MediaSelectControl
    value={imageId}
    onChange={setImageId}
    isSelected={true}
    focalPoint={imageFocalPoint}
    onFocalPointChange={setImageFocalPoint}
/>
```


### `hideLabelFromVision`

Optional boolean (default `false`), forwarded to Core `BaseControl`. Use it when
a surrounding panel already supplies the visible heading. The label remains
available to assistive technology and still names the media-library dialog.
