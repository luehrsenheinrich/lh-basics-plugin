import { MediaUpload } from '@wordpress/media-utils';
import {
	BaseControl,
	Button,
	ButtonGroup,
	DropZone,
	ResponsiveWrapper,
	Spinner,
} from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import classNames from 'classnames';

const MediaSelectControl = ({
	label = __('Select image', 'lhbasicsp'),
	labelSet = __('Set image', 'lhbasicsp'),
	help = '',
	value,
	onChange,
	allowedTypes = ['image'],
	imageSize = 'full',
	isSelected,
	className,
	classNamePreview = 'lh-preview-image',
	getPreview,
}) => {
	const { editorSettings, media } = useSelect((select) => {
		const { getMedia } = select('core');
		const { getSettings } = select('core/block-editor');
		return {
			media: value ? getMedia(value) : null,
			editorSettings: getSettings(),
		};
	});

	let mediaWidth, mediaHeight, mediaSourceUrl, mediaTitle;
	if (media) {
		const filename = media.source_url.split('/').pop();

		mediaTitle = `${media.title.rendered} (${filename})`;
		if (media.media_type === 'image') {
			const { sizes } = media.media_details;
			const sizeKey = sizes[imageSize] !== undefined ? imageSize : 'full';

			mediaWidth = sizes[sizeKey]?.width || media.media_details.width;
			mediaHeight = sizes[sizeKey]?.height || media.media_details.height;
			mediaSourceUrl = sizes[sizeKey]?.source_url || media.source_url;
		} else {
			// TODO: Better non-image media type previews.
			mediaWidth = 64;
			mediaHeight = 64;
			mediaSourceUrl = `${window.lhagenturSettings.pluginUrl}/img/icons/media--document.svg`;
		}
	}

	const onUpdateMedia = ({ id }) => {
		onChange(id);
	};
	const onDropMedia = (filesList) => {
		editorSettings.mediaUpload({
			allowedTypes,
			filesList,
			onFileChange([{ id }]) {
				onChange(id);
			},
		});
	};
	const onRemoveMedia = () => {
		onChange(0);
	};

	const hasMedia = !!value && media;
	const isPreview = !isSelected && hasMedia;

	const getPreviewImage = () =>
		hasMedia ? (
			<img
				src={mediaSourceUrl}
				alt={mediaTitle}
				className={classNamePreview}
				width={mediaWidth}
				height={mediaHeight}
			/>
		) : null;

	if (isPreview) {
		return <>{getPreview ? getPreview({ media }) : getPreviewImage()}</>;
	}

	const clsPrefix = 'lh-media-select-control';
	const controlClassNames = classNames(clsPrefix, 'stack', className, {
		'is-selected': isSelected,
	});

	return (
		<BaseControl
			id={clsPrefix}
			label={label}
			help={help}
			className={controlClassNames}
		>
			<MediaUpload
				title={label}
				onSelect={onUpdateMedia}
				allowedTypes={allowedTypes}
				modalClass={`${clsPrefix}__media-modal`}
				value={value}
				render={({ open }) => (
					<div className={`${clsPrefix}__container`}>
						<Button
							className={
								!value
									? `${clsPrefix}__toggle`
									: `${clsPrefix}__preview`
							}
							onClick={open}
							aria-label={__('Edit or update', 'lhbasicsp')}
							variant={'secondary'}
						>
							{!!value && !media && <Spinner />}
							{!value && <>{labelSet}</>}
							{hasMedia && (
								<ResponsiveWrapper>
									{getPreview
										? getPreview({ media })
										: getPreviewImage()}
								</ResponsiveWrapper>
							)}
						</Button>
						<DropZone onFilesDrop={onDropMedia} />
					</div>
				)}
			/>
			{!!value && (
				<ButtonGroup className={`${clsPrefix}__actions`}>
					{media && !media.isLoading && (
						<MediaUpload
							title={label}
							onSelect={onUpdateMedia}
							allowedTypes={allowedTypes}
							modalClass={`${clsPrefix}__media-modal`}
							render={({ open }) => (
								<Button onClick={open} variant="secondary">
									{__('Replace', 'lhbasicsp')}
								</Button>
							)}
						/>
					)}
					<Button
						onClick={onRemoveMedia}
						variant="tertiary"
						isDestructive
					>
						{__('Remove', 'lhbasicsp')}
					</Button>
				</ButtonGroup>
			)}
		</BaseControl>
	);
};

export default MediaSelectControl;
