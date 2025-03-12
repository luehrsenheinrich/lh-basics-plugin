# Weblink Components

The Weblink Components offer simple controls for link objects.

## WeblinkControl (Main-)Component

The main component `<WeblinkControl />` should be used with blocks, only.

### Link Object Schema

The (default) link object schema to use with the `<WeblinkControl />`.

```json
{
	"url": "",
	"title": "",
	"opensInNewTab": false,
}
```

### Example Usage

```jsx
import { useState } from '@wordpress/element';
import WeblinkControl from 'path/to/WeblinkControl';

const MyWeblinkControl = () => {
	const [link, setLink] = useState({ url: '', link: '', opensInNewTab: false });

	return (
		<WeblinkControl
			label="A block's link"
			value={link}
			onChange={setLink}
		/>
	);
};

export default MyWeblinkControl;
```

### Props

#### `label`

- **Type:** `string | ReactNode`
- **Required:** No
- **Default:** `''`
- **Description:** The text label displayed above the control.

#### `value`

- **Type:** `object`
- **Required:** No
- **Description:** The link object to control.

#### `onChange`

- **Type:** `(value: object) => void`
- **Required:** Yes
- **Description:** Callback function invoked when the link object changes. It receives the new link object.

#### `subjectName`

- **Type:** `string`
- **Required:** No
- **Default:** `'weblink'`
- **Description:** The subject name of the link.

## WeblinkToolbarButton Component

The component `<WeblinkToolbarButton />` should be used with blocks, only.

### Link Object Schema

The (default) link object schema to use with the `<WeblinkToolbarButton />`.

```json
{
	"url": "",
	"title": "",
	"opensInNewTab": false,
}
```

### Example Usage

```jsx
import { BlockControls } from `@wordpress/block-editor`;
import { useState } from '@wordpress/element';
import WeblinkToolbarButton from 'path/to/WeblinkToolbarButton';

const MyWeblinkControl = () => {
	const [link, setLink] = useState({ url: '', link: '', opensInNewTab: false });

	return (
		<BlockControls>
			<WeblinkToolbarButton
				value={link}
				onChange={setLink}
			/>
		</BlockControls>
	);
};

export default MyWeblinkControl;
```

### Props

#### `value`

- **Type:** `object`
- **Required:** No
- **Description:** The link object to control.

#### `onChange`

- **Type:** `(value: object) => void`
- **Required:** Yes
- **Description:** Callback function invoked when the link object changes. It receives the new link object.

## WeblinkSetting Component

The component `<WeblinkSetting />` should be used outside of block-editor context elements like blocks (e.g. at LH Settings).

### Link Object Schema

The (default) link object schema to use with the `<WeblinkSetting />`.
Note: This component __does not__ utilize the `@wordpress/block-editor`.`<LinkControl />` because of the block-editor data context not working properly with just that component and its dependencies.
Note2: The `id` is mandatory here for a more straight forward approach to hold info if a link is internal or external.

```json
{
	"id": 0,
	"url": "",
	"title": "",
	"opensInNewTab": false,
}
```

### Example Usage

```jsx
import { useState } from '@wordpress/element';
import WeblinkSetting from 'path/to/WeblinkSetting';

const MyWeblinkSetting = () => {
	const [link, setLink] = useState({ id: 0, url: '', link: '', opensInNewTab: false });

	return (
		<WeblinkSetting
			label="A block's link"
			value={link}
			onChange={setLink}
		/>
	);
};

export default MyWeblinkSetting;
```

### Props

#### `label`

- **Type:** `string | ReactNode`
- **Required:** No
- **Default:** `''`
- **Description:** The text label displayed above the control.

#### `value`

- **Type:** `object`
- **Required:** No
- **Description:** The link object to control.

#### `onChange`

- **Type:** `(value: object) => void`
- **Required:** Yes
- **Description:** Callback function invoked when the link object changes. It receives the new link object.

#### `subjectName`

- **Type:** `string`
- **Required:** No
- **Default:** `'weblink'`
- **Description:** The subject name of the link.
