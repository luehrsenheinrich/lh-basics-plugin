import { useEffect, useRef } from '@wordpress/element';
import {
	BaseControl,
	Button,
	useBaseControlProps,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import HexToVarFixObserver from './HexToVarFixObserver';

const COLOR_PALETTE =
	window?.lhbasicsBlocksHelper?.settings?.color?.palette?.theme ?? [];
const TEXTCOLOR_MAP = COLOR_PALETTE.flatMap(({ color, name }) => [color, name]);

function WPTinyMCE({ id = 'lh-editor', value, onChange, ...baseProps }) {
	const initialized = useRef(false);
	const observerRef = useRef(null);

	const { baseControlProps } = useBaseControlProps(baseProps);

	useEffect(() => {
		if (initialized.current || !window.tinymce) {
			return;
		}
		initialized.current = true;

		window.tinymce
			.init({
				selector: `#${id}`,
				menubar: false,
				content_css: window.lhbasicsBlocksHelper.cssUri,
				// WP typical settings:
				plugins: [
					'paste',
					'link',
					'lists',
					'wplink',
					'wordpress',
					'textcolor',
					'media',
				],
				toolbar:
					'fontsizeselect | bold italic | align | link bullist forecolor',
				fontsize_formats:
					'Default=11pt Small=var(--font-size-small) Regular=var(--font-size-regular) Large=var(--font-size-large) Extra Large=var(--font-size-xlarge)',
				mediaButtons: true,
				wpautop: true,
				quicktags: true,
				textcolor_map: TEXTCOLOR_MAP,
				color_cols: 5,
				setup(editor) {
					// Set initial value.
					editor.on('init', () => {
						editor.setContent(value ?? '');
					});
					// Save content on blur (when editor loses focus).
					editor.on('blur', () => {
						onChange?.(editor.getContent());
					});
				},
			})
			.then(() => {
				const observer = new HexToVarFixObserver();
				observer.observe(document.body);
				observerRef.current = observer;
			});

		return () => {
			observerRef.current?.disconnect();
			observerRef.current = null;
			window.tinymce.get(id)?.remove();
			initialized.current = false;
		};
	});

	return (
		<BaseControl {...baseControlProps}>
			<div
				className="stack"
				style={{ '--space': 'calc(var(--grid-gutter-height) / 3)' }}
			>
				<div id={`wp-${id}-media-buttons`}>
					<Button
						variant="secondary"
						type="button"
						id="insert-media-button"
						className="insert-media add_media"
						data-editor={id}
						aria-haspopup="dialog"
						aria-controls="wp-media-modal"
					>
						<span
							className="wp-media-buttons-icon"
							aria-hidden="true"
						></span>
						{__('Add Media', 'm94526p')}
					</Button>
				</div>
				<div className="tinymce-container">
					<textarea id={id} defaultValue={value} />
				</div>
			</div>
		</BaseControl>
	);
}

export default WPTinyMCE;
