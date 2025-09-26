# EntitySelectControl

IconSelectControl is a component for selecting posts or taxonomy terms from a list. It leverages react-select along with custom hooks to provide a searchable, clearable dropdown. If multiple select is used, the selected options are sortable via drag and drop.

## EntitySelectControl (Main-)Component

The main component `<EntitySelectControl />` follows the Gutenberg-Hooks naming with it's properties.
It can be adjusted to create a select of any `kind` (postType, taxonomy, ...) with any `name` (post, page, category, ...).

### Example Usage

```jsx
import { useState } from '@wordpress/element';
const { EntitySelectControl } = window.lhbasics.components;

const MyEntitySelector = () => {
	const [selectedEntities, setSelectedEntities] = useState( null );

	return (
		<EntitySelectControl
			label="Select Event"
			help="Select events from the list."
			value={selectedEntities}
			onChange={setSelectedEntities}
		/>
	);
};

export default MyEntitySelector;
```

## PostSelectControl (Wrapper-)Component

The wrapper component `<PostSelectControl />` wraps the `<EntitySelectControl />`, provides the property `postType` and defaults to posttype `post`. This is for readability/developer-experience.

Attenttion: All properties excepted by `<EntitySelectControl />` will be passed down, with only `name` beeing overriden in favor of the `postType` prop.

### Example Usage

```jsx
import { useState } from '@wordpress/element';
const { PostSelectControl } = window.lhbasics.components;

const MyEventSelector = () => {
	const [selectedPage, setSelectedPage] = useState( null );

	return (
		<PostSelectControl
			label="Select Event"
			help="Select events from the list."
			postType="cpt_event"
			value={selectedPage}
			onChange={setSelectedPage}
			multiple={false}
		/>
	);
};

export default MyEventSelector;
```

## TaxonomySelectControl (Wrapper-)Component

The wrapper component `<TaxonomySelectControl />` wraps the `<EntitySelectControl />`, provides the property `taxonomy` and defaults to taxonomy `category`. This is for readability/developer-experience.

Attenttion: All properties excepted by `<EntitySelectControl />` will be passed down, with `name` beeing overriden in favor of the `taxonomy` prop and `kind` fixed to `'taxonomy'`.

### Example Usage

```jsx
import { useState } from '@wordpress/element';
const { TaxonomySelectControl } = window.lhbasics.components;

const MyTagIdsSelector = () => {
	// We just want to save the tag-IDs, not the whole term objects.
	const [tagIds, setTagIds] = useState( null );

	// The component will resolve this and populate
	// the given values with it's corresponding titles.
	const mappedIds = tagIds.map((id) => ({ value: id }));

	return (
		<TaxonomySelectControl
			label="Select Event"
			help="Select events from the list."
			taxonomy="post_tag"
			value={mappedIds}
			onChange={setSelectedPage}
		/>
	);
};

export default MyTagIdsSelector;
```

## Props

### `kind`

- **Type:** `string`
- **Required:** No
- **Default:** `postType`
- **Description:** The kind of entity. Usually `postType` (default)  or `taxonomy`. If a wrapper component is used, the value is probably overriden by a static one.

### `name` | `postType` | `taxonomy`

- **Type:** `string`
- **Required:** No
- **Default:** `post`
- **Description:** The type of entity. Usually the posttype or taxonomy slug. If a wrapper component is used, the property is probably overriden by a custom property like `postType` or `taxonomy`.

### `label`

- **Type:** `string | ReactNode`
- **Required:** No
- **Default:** `'Select posts'`
- **Description:** The text label displayed above the control.

### `help`

- **Type:** `string | ReactNode`
- **Required:** No
- **Default:** `''`
- **Description:** Additional help text that provides guidance about the control. This text is associated with the input for accessibility.

### `value`

- **Type:** `object | array<object>`
- **Required:** No
- **Description:** Either the entity object or an array of entity objects. An entity object must at least contain a value property containg an entity-ID (example: `{ value: 420 }` ).

### `onChange`

- **Type:** `(value: object | array<object> | null) => void`
- **Required:** Yes
- **Description:** Callback function invoked when the entity selection changes. It receives the new entity object(s), or `null` / `[]` if the selection is cleared.

### `query`

- **Type:** `(value: object | null) => void`
- **Required:** No
- **Description:** Object of query parameters to tweak the set of selectable entities. Supports all parameters available to the REST endpoint of the given entity-type.

### `multiple`

- **Type:** `(value: boolean | null) => void`
- **Required:** Yes
- **Description:** Wether multiple select is allowed or not.
