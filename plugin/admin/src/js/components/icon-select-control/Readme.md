# IconSelectControl

IconSelectControl is a component for selecting icons from a list. It leverages react-select along with custom hooks to provide a searchable, clearable dropdown. The component ensures that the currently selected icon is always available in the options—even if it would normally be filtered out—by using dedicated hooks to load the icon data.

## Example Usage

```jsx
import { useState } from '@wordpress/element';
const { IconSelectControl } = window.lhbasics.components;

const MyIconSelector = () => {
	const [ selectedIcon, setSelectedIcon ] = useState( null );

	return (
		<IconSelectControl
			label="Select Icon"
			help="Choose an icon from the list."
			value={ selectedIcon }
			onChange={ setSelectedIcon }
			// Optionally, use whiteList to include only specific icons:
			// whiteList={ [ 'home', 'settings', 'user' ] }
			// Or use blackList to exclude certain icons:
			// blackList={ [ 'delete' ] }
		/>
	);
};

export default MyIconSelector;
```

## Props

### `label`

- **Type:** `string | ReactNode`
- **Required:** No
- **Default:** `'Select icon'`
- **Description:** The text label displayed above the control.

### `help`

- **Type:** `string | ReactNode`
- **Required:** No
- **Default:** `''`
- **Description:** Additional help text that provides guidance about the control. This text is associated with the input for accessibility.

### `value`

- **Type:** `string`
- **Required:** No
- **Description:** The slug of the currently selected icon. If no value is provided, the control appears empty.

### `onChange`

- **Type:** `(value: string | null) => void`
- **Required:** Yes
- **Description:** Callback function invoked when the icon selection changes. It receives the new icon slug, or `null` if the selection is cleared.

### `blackList`

- **Type:** `string[]`
- **Required:** No
- **Default:** `[]`
- **Description:** An array of icon slugs to exclude from the available selection options.

### `whiteList`

- **Type:** `string[]`
- **Required:** No
- **Default:** `[]`
- **Description:** An array of icon slugs to exclusively include in the selection options. If provided, only these icons will be available for selection.

## Internal Behavior

IconSelectControl utilizes two custom hooks:

- **useIcons:** Retrieves a list of icons filtered by a search term. It accepts a `must_include` parameter to ensure that the icon corresponding to the current value is always included.
- **useIcon:** Fetches a specific icon by its slug if it is not already present in the icons list, ensuring that the control always displays the current selection.

The component renders a searchable dropdown using react-select, with a custom option renderer that displays each icon (using a dedicated icon component) alongside its title.
