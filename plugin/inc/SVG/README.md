# SVG Component
The SVG component is intended to ease the handling of SVG images in the WordPress
enviroment. It provides two exposed plugin functions, that read, parse and output
the SVG code in the desired manner.

## Relative Paths
The paths passed to the functions are relative paths based on the active theme or
current plugin. The component first looks into the theme folder to find the file
and then in the plugin folder.


## Filters

### `lhagentur_svg_icon_library_icons`
Filters the array of `<Icon>`s passed to the `<Icon_Library>`.

#### Parameters
* `(array) $icons` - An array of `<Icon>`s.

#### Returns
* `(array) $icons` - The updated / created array of `<Icon>`s

#### Example
```PHP
use WpMunich\basics\plugin\SVG\Icon;

# ...
add_filter( 'lhagentur_svg_icons_library_icons', 'my_svg_icons_library_icons' );

# ...
function my_svg_icons_library_icons( array $icons ): array {
	// If there's some case in which no icons should be registered.
	if (some_other_condition) {
		return $icons;
	}

	$img_path = 'my/img/path/';
	$my_icons = array(
		new Icon(
			$icon_path . 'icons/my-icon.svg',
			'my-icon--slug',
			__( 'My Icon Label', 'textdomain' )
		),
		# ...
	);

	// If you want to add icons.
	if ( some_condition ) {
		return array_merge(
			$icons,
			$my_icons
		);
	}

	// If you want to override icons.
	return $my_icons;
}
```


## Functions

### get_svg( (sting) $path, (array) $arguments )
The `get_svg()` function returns the SVG DOM for the file in the given path.

#### Arguments
* `(string) $path` - The given path relative to the current theme or plugin.
* `(array) $arguments` - An array of arguments to modify the behavior of the function.
  - `(array) $attributes` - An array of HTML attributes applied to the returned SVG tag. Valid array keys are 'class', 'id', 'width', 'height', 'fill'.
  - `(string) $return_type` - The desired return type for the SVG DOM. Valid inputs are 'tag' and 'base64'. Defaults to 'tag'.

#### Returns
`(string)` The svg HTML dom in the type defined in `return_type`.

### get_admin_menu_icon( (string) $path )
A wrapper for the `get_svg()` function that provides the fitting arguments to use
SVGs in admin menu items.

#### Arguments
* `(string) $path` - The given path relative to the current theme or plugin.

#### Returns
`(string)` The base64 encoded svg HTML dom.

## Example
### Base SVG
```
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 355.55 425.2">
  <path d="M355.55 0h-64.07L115.36 425.2h64.07zM240.19 0h-64.07L0 425.2h64.07z"/>
</svg>
```

### Code
```
$arguments = array(
	'attributes' => array(
		'fill'  => '#26b8ff',
		'class' => 'slashes-svg',
		'id'    => 'slashes',
	),
);

return plugin()->svg()->get_svg( 'img/icons/slashes.svg', $arguments );
```
### Returns
```
<svg xmlns="http://www.w3.org/2000/svg" class="slashes-svg" fill="#26b8ff" id="slashes" viewBox="0 0 355.55 425.2">
  <path d="M355.55 0h-64.07L115.36 425.2h64.07zM240.19 0h-64.07L0 425.2h64.07z"></path>
</svg>
```
