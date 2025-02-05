# Icon Data Module

This module registers two icon-related entities with the WordPress Core Data store and provides custom hooks to fetch icon data. It allows you to retrieve a list of icons with filtering and pagination or a single icon by its slug.

## Entities

Two entities are registered:

- **Icon**
  - **Label:** "Icon"
  - **Name:** `icon`
  - **Kind:** `single`
  - **Base URL:** `/lhbasics/v1/icon`
  - **Key:** `slug`

- **Icons**
  - **Label:** "Icons"
  - **Name:** `icons`
  - **Kind:** `root`
  - **Base URL:** `/lhbasics/v1/icons`
  - **Key:** `slug`

## Hooks

### `useIcons`

Retrieves a list of icons with optional filtering and pagination.

**Parameters:**

- `params` (Object, optional):
  - `search` (string): A term to filter icons by title. Default is an empty string (`''`).
  - `page` (number): The page number for pagination. Default is `1`.
  - `per_page` (number): The number of icons to retrieve per page. Default is `20`.

**Returns:**

An object containing:
- `icons`: The array of icon records.
- Additional state properties provided by `useEntityRecords` (such as `isResolving` and `error`).

### `useIcon`

Retrieves a single icon by its slug.

**Parameters:**

- `slug` (string): The unique slug identifier for the icon.

**Returns:**

An object containing:
- `icon`: The icon record.
- Additional state properties provided by `useEntityRecord` (such as `isResolving` and `error`).

## Example Usage

```jsx
import { useIcons, useIcon } from '/';

const IconGallery = () => {
  // Retrieve a paginated list of icons with an optional search term.
  const { icons, isResolving, error } = useIcons({ search: 'arrow', page: 1, per_page: 20 });

  // Retrieve a specific icon by its slug.
  const { icon } = useIcon('arrow-left');

  if (isResolving) {
    return <div>Loading icons...</div>;
  }

  if (error) {
    return <div>Error loading icons.</div>;
  }

  return (
    <div>
      <h2>Icon Gallery</h2>
      {icons && icons.map((icon) => (
        <div key={icon.slug}>
          <h3>{icon.title}</h3>
          <div dangerouslySetInnerHTML={{ __html: icon.svg }} />
        </div>
      ))}
      {icon && (
        <div>
          <h3>Selected Icon: {icon.title}</h3>
          <div dangerouslySetInnerHTML={{ __html: icon.svg }} />
        </div>
      )}
    </div>
  );
};

export default IconGallery;
```

This module leverages the WordPress Core Data store to register and manage icon entities. Use the provided hooks to seamlessly integrate icon selection and display functionality into your Gutenberg or custom WordPress components.
