<?php
/**
 * The class to represent an Icon Object.
 *
 * @package lhbasicsp
 */

namespace WpMunich\basics\plugin\SVG;

/**
 * A single Icon.
 */
class Icon implements \JsonSerializable {
	/**
	 * The path of the icon relative to the theme.
	 *
	 * @var string
	 */
	protected string $path;

	/**
	 * Wether to expose the Icon to the REST API or not.
	 *
	 * @var boolean
	 */
	protected bool $show_in_rest;

	/**
	 * The slug of the icon.
	 *
	 * @var string
	 */
	protected string $slug;

	/**
	 * The readable title of an icon.
	 *
	 * @var string
	 */
	protected string $title;

	/**
	 * Class constructor
	 *
	 * @param string $path         The path of the icon relative to the theme.
	 * @param string $slug         The slug of the icon.
	 * @param string $title        The readable title of an icon.
	 * @param bool   $show_in_rest Wether to expose the Icon to the REST api or not.
	 * @return void
	 */
	public function __construct( $path = null, $slug = null, $title = null, $show_in_rest = true ) {
		if ( $path && ! empty( $path ) ) {
			$this->set_path( $path );
		}
		if ( $slug && ! empty( $slug ) ) {
			$this->set_slug( $slug );
		}
		if ( $title && ! empty( $title ) ) {
			$this->set_title( $title );
		}

		$this->set_show_in_rest( $show_in_rest );
	}

	/**
	 * Return the icon's path.
	 *
	 * @return string The icon's path.
	 */
	public function get_path() {
		return $this->path;
	}

	/**
	 * Return the icon's slug.
	 *
	 * @return string The icon's slug.
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * Return the icon's title.
	 *
	 * @return string The icon's title.
	 */
	public function get_title() {
		return $this->title;
	}

	/**
	 * Return if the Icon can be exposed to the REST API.
	 *
	 * @return bool True if exposable, false otherwise.
	 */
	public function show_in_rest() {
		return $this->show_in_rest;
	}

	/**
	 * Set the icon's path.
	 *
	 * @param string $path The path to set.
	 * @return void.
	 */
	public function set_path( $path ) {
		if ( ! empty( $path ) ) {
			$this->path = $path;
		}
	}

	/**
	 * Set the icon's slug.
	 *
	 * @param string $slug The slug to set.
	 * @return void.
	 */
	public function set_slug( $slug ) {
		if ( ! empty( $slug ) ) {
			$this->slug = $slug;
		}
	}

	/**
	 * Set the icon's title.
	 *
	 * @param string $title The title to set.
	 * @return void.
	 */
	public function set_title( $title ) {
		if ( ! empty( $title ) ) {
			$this->title = $title;
		}
	}

	/**
	 * Set if the Icon can be exposed to the REST API.
	 *
	 * @param bool $show_in_rest True if exposable, false otherwise.
	 * @return void.
	 */
	public function set_show_in_rest( $show_in_rest ) {
		if ( is_bool( $show_in_rest ) ) {
			$this->show_in_rest = $show_in_rest;
		}
	}

	/**
	 * Serializes the object to a value that can be serialized natively by wp_|json_encode().
	 *
	 * @return mixed The serialized object.
	 */
	#[\ReturnTypeWillChange]
	public function jsonSerialize() : array {
		$resp = array();
		// Filter for fields.
		// E.g. REST shouldn't expose 'path'.
		foreach ( array( 'path', 'slug', 'title' ) as $field ) {
			if ( is_callable( array( $this, "get_{$field}" ) ) ) {
				$callback       = "get_{$field}";
				$resp[ $field ] = $this->$callback();
			}
		}
		return $resp;
	}
}
