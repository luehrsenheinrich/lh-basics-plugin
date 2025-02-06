# LHIcon

LHIcon is a component that displays an icon from a custom API endpoint using the WordPress Icon component (wp.components.Icon) for consistent styling. It accepts an icon slug and optionally an SVG markup string. If the SVG is not provided, the component uses the slug to fetch the icon data via the useIcon hook.

## Usage

LHIcon integrates seamlessly with Gutenberg and custom interfaces. For example:

```jsx
import { useState } from '@wordpress/element';
import LHIcon from 'path/to/LHIcon';

const Example = () => {
  const [ iconSlug, setIconSlug ] = useState('home');

  return (
    <div>
      <LHIcon slug={ iconSlug } className="my-custom-class" />
    </div>
  );
};

export default Example;
```

## Props

### `slug`
- **Type:** `string`
- **Required:** Yes
The unique identifier for the icon to be displayed. LHIcon uses this slug to fetch the icon’s SVG markup if one is not provided via props.

### `svg`
- **Type:** `string`
- **Required:** No
An optional SVG markup string. If provided, LHIcon will render this SVG instead of fetching it from the API.

### `className`
- **Type:** `string`
- **Required:** No
Additional CSS classes to apply to the rendered icon. The component combines any provided classes with its default class names.

### Other Props
Any additional props passed to LHIcon are forwarded to the underlying WordPress Icon component.

## Internal Behavior

- **Data Fetching:**
  LHIcon uses the useIcon hook to retrieve icon data based on the provided slug. The fetched data includes the SVG markup along with optional sizing attributes.

- **SVG Parsing:**
  The component uses html-react-parser to convert the raw SVG markup into React elements. This parsed SVG is memoized to optimize performance and avoid unnecessary re-parsing.

- **Rendering:**
  When icon data is available and the SVG is successfully parsed, LHIcon renders a WPIcon element. It computes a CSS class by combining any user-specified class names with a default pattern (e.g., `lh-icon icon-[slug]`). It also sets the icon size based on the available icon dimensions.

## Example

Below is an example of how to integrate LHIcon into your application:

```jsx
import { useState } from '@wordpress/element';
import LHIcon from 'path/to/LHIcon';

const IconDisplay = () => {
  const [ selectedIcon, setSelectedIcon ] = useState('settings');

  return (
    <div>
      <h2>Selected Icon</h2>
      <LHIcon slug={ selectedIcon } className="custom-icon-style" />
    </div>
  );
};

export default IconDisplay;
```

## See Also

- [WordPress Icon Component Documentation](https://github.com/WordPress/gutenberg/tree/trunk/packages/components/src/icon)

LHIcon is part of the LH Basics Plugin and provides a convenient way to integrate custom icon functionality into your WordPress projects using the established design patterns of Gutenberg.
