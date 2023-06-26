import GLightbox from 'glightbox';

document.addEventListener('DOMContentLoaded', () => {
	new LightboxControl();
});

class LightboxControl {
	/**
	 * The lightbox.
	 *
	 * @member {null|object}
	 */
	box = null;

	constructor() {
		// Prepare classes.
		this.prepareLightboxElements();

		this.box = GLightbox({ videosWidth: '1280px' });
	}

	/**
	 * Prepare elements for the lightbox.
	 */
	prepareLightboxElements() {
		// Prepare the image block.
		const imageBlocks = document.querySelectorAll(
			'.wp-block-image:not(.nolb)'
		);

		imageBlocks.forEach((imageBlock) => {
			// Get the link.
			const link = imageBlock.querySelector(
				'a[href*=".jpg"], a[href*=".jpeg"], a[href*=".png"], a[href*=".gif"], a[href*=".webp"]'
			);

			// If there is no link, continue.
			if (!link) {
				return;
			}

			const slideOptions = {};

			// Get the caption.
			const caption = imageBlock.querySelector('figcaption');

			if (caption) {
				slideOptions.description = caption.innerHTML;
			}

			this.addLightbox(link, slideOptions);
		});
	}

	/**
	 * Add the lightbox to an element.
	 *
	 * @param {Object} element      The element to add the lightbox to.
	 * @param {Object} slideOptions The options for the slide.
	 */
	addLightbox(element, slideOptions = {}) {
		// Add the lightbox class.
		element.classList.add('glightbox');

		/**
		 * The available slide options as an array.
		 *
		 * @see https://github.com/biati-digital/glightbox#slide-options
		 * @type {Array}
		 */
		const availableSlideOptions = [
			'title',
			'alt',
			'description',
			'descPosition',
			'type',
			'effect',
			'width',
			'height',
			'zoomable',
			'draggable',
		];

		// Check the slideOptions against the available options.
		Object.keys(slideOptions).forEach((key) => {
			if (!availableSlideOptions.includes(key)) {
				delete slideOptions[key];
			}
		});

		// Transform the slide options into data attributes on the element.
		Object.keys(slideOptions).forEach((key) => {
			if (slideOptions[key]) {
				element.setAttribute(`data-${key}`, slideOptions[key]);
			}
		});
	}
}
