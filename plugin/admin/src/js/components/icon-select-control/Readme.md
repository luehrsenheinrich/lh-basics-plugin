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

- **useIcons:** Retrieves server-filtered icons by name, slug or keyword. With no search, `must_include` prioritizes the current value once; the selected value is also loaded separately through `useIcon`.
- **useIcon:** Fetches a specific icon by its slug if it is not already present in the icons list, ensuring that the control always displays the current selection.

The component renders a searchable dropdown using react-select, with a custom option renderer that displays each icon (using a dedicated icon component) alongside its title.

## Keyword search and filtering

The shared REST search matches names, slugs and provider keywords. Carbon aliases such as `plus` can return `add` without knowing the exact icon name. All query words must match; name matches rank first. React Select does not apply an additional title-only filter. `getOptionLabel` stays plain text for accessibility; `formatOptionLabel` renders the SVG preview.

The current value is loaded separately and remains selected during a search. `whiteList` restricts the server's slug query before pagination, so icons outside the initial page are reachable. A selected value is pinned once only when there is no search. `per_page` remains limited to 100.
