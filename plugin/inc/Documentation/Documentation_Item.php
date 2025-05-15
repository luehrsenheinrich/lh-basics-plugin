<?php
/**
 * Abstract class representing a documentation item.
 *
 * @package lhbasicsp
 */

namespace WpMunich\basics\plugin\Documentation;

/**
 * Documentation item.
 *
 * Represents a single documentation entry with headline, HTML content, icon, and slug.
 */
class Documentation_Item implements \JsonSerializable {
	/**
	 * The headline for the documentation item.
	 *
	 * @var string
	 */
	protected $headline;

	/**
	 * The HTML content for the documentation item.
	 *
	 * @var string
	 */
	protected $html_content;

	/**
	 * The icon for the documentation item.
	 *
	 * @var string
	 */
	protected $icon;

	/**
	 * The slug for the documentation item.
	 *
	 * @var string
	 */
	protected $slug;

	/**
	 * The priority for the documentation item.
	 * Lower values mean higher priority.
	 *
	 * @var int
	 */
	protected $priority = 10;

	/**
	 * Constructor.
	 *
	 * @param string $headline     The headline for the documentation item.
	 * @param string $html_content The HTML content for the documentation item.
	 * @param string $icon         The icon for the documentation item.
	 * @param string $slug         The slug for the documentation item.
	 * @param int    $priority     The priority for the documentation item (default 10).
	 */
	public function __construct( $headline, $html_content, $icon, $slug, $priority = 10 ) {
		$this->headline     = $headline;
		$this->html_content = $html_content;
		$this->icon         = $icon;
		$this->slug         = $slug;
		$this->priority     = $priority;
	}

	/**
	 * Get the headline.
	 *
	 * @return string
	 */
	public function get_headline() {
		return $this->headline;
	}

	/**
	 * Get the HTML content.
	 *
	 * @return string
	 */
	public function get_html_content() {
		return $this->html_content;
	}

	/**
	 * Get the icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return $this->icon;
	}

	/**
	 * Get the slug.
	 *
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * Get the priority.
	 *
	 * @return int
	 */
	public function get_priority() {
		return $this->priority;
	}

	/**
	 * Convert the documentation item to an array for JSON serialization.
	 *
	 * @return array
	 */
	public function to_json() {
		return array(
			'headline'     => $this->get_headline(),
			'html_content' => $this->get_html_content(),
			'icon'         => $this->get_icon(),
			'slug'         => $this->get_slug(),
			'priority'     => $this->get_priority(),
		);
	}

	/**
	 * Specify data which should be serialized to JSON.
	 *
	 * @return mixed
	 */
	public function jsonSerialize() : mixed {
		return $this->to_json();
	}
}
