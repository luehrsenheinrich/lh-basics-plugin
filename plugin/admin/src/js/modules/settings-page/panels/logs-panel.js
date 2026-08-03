/**
 * Logs panel for the settings page.
 */

/**
 * WordPress dependencies
 */
import { useEffect, useState } from '@wordpress/element';
import {
	Button,
	SelectControl,
	Spinner,
	TextareaControl,
} from '@wordpress/components';
import { dispatch } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';

/**
 * Internal dependencies
 */
import SettingsPanel from './../settings-panel';

const LOG_SEVERITY_SETTING = 'lhbasicsp_log_severity';

/**
 * Logs panel component.
 *
 * @param {Object}   props                The component props.
 * @param {Object}   props.apiSettings    The API settings object.
 * @param {Function} props.setApiSettings The function to set the API settings.
 *
 * @return {Component} The logs panel component.
 */
const LogsPanel = ({ apiSettings, setApiSettings }) => {
	const [logData, setLogData] = useState(null);
	const [isLoading, setIsLoading] = useState(false);
	const [isClearing, setIsClearing] = useState(false);

	const loadLogs = () => {
		setIsLoading(true);

		apiFetch({ path: '/lhbasicsp/v1/logs' }).then(
			(response) => {
				setLogData(response);
				setIsLoading(false);
			},
			(error) => {
				setIsLoading(false);
				dispatch('core/notices').createNotice(
					'error',
					error.message ?? __('Error loading logs.', 'lhbasicsp'),
					{
						type: 'snackbar',
						isDismissible: true,
					}
				);
			}
		);
	};

	useEffect(() => {
		loadLogs();

		window.addEventListener('lhbasicsp:test-log-created', loadLogs);

		return () => {
			window.removeEventListener('lhbasicsp:test-log-created', loadLogs);
		};
	}, []);

	const clearLogs = () => {
		setIsClearing(true);

		apiFetch({ path: '/lhbasicsp/v1/logs', method: 'DELETE' }).then(
			(response) => {
				setLogData(response);
				setIsClearing(false);
				dispatch('core/notices').createNotice(
					'success',
					__('Logs cleared.', 'lhbasicsp'),
					{
						type: 'snackbar',
						isDismissible: true,
					}
				);
			},
			(error) => {
				setIsClearing(false);
				dispatch('core/notices').createNotice(
					'error',
					error.message ?? __('Error clearing logs.', 'lhbasicsp'),
					{
						type: 'snackbar',
						isDismissible: true,
					}
				);
			}
		);
	};

	const levels = logData?.levels ?? [
		'debug',
		'info',
		'notice',
		'warning',
		'error',
		'critical',
		'alert',
		'emergency',
	];

	const severity =
		apiSettings[LOG_SEVERITY_SETTING] ?? logData?.severity ?? 'info';

	const setSeverity = (value) => {
		setApiSettings({
			...apiSettings,
			[LOG_SEVERITY_SETTING]: value,
		});
	};

	return (
		<SettingsPanel title={__('Logs', 'lhbasicsp')} icon="media-text">
			<div className="full-width settings-log-toolbar">
				<SelectControl
					label={__('Severity', 'lhbasicsp')}
					value={severity}
					options={levels.map((level) => ({
						label: level.charAt(0).toUpperCase() + level.slice(1),
						value: level,
					}))}
					onChange={setSeverity}
					__nextHasNoMarginBottom
				/>
				<div className="settings-log-actions">
					<Button
						variant="secondary"
						isBusy={isLoading}
						disabled={isLoading || isClearing}
						onClick={loadLogs}
					>
						{__('Reload', 'lhbasicsp')}
					</Button>
					<Button
						variant="secondary"
						isDestructive
						isBusy={isClearing}
						disabled={isLoading || isClearing}
						onClick={clearLogs}
					>
						{__('Clear logs', 'lhbasicsp')}
					</Button>
				</div>
			</div>
			<div className="full-width settings-log-meta">
				<span>{logData?.backend ?? __('Loading', 'lhbasicsp')}</span>
				<span>{logData?.path ?? ''}</span>
			</div>
			<div className="full-width settings-log-viewer">
				{isLoading && <Spinner />}
				{!isLoading && (
					<TextareaControl
						label={__('Log viewer', 'lhbasicsp')}
						value={
							logData?.content ||
							__('No log entries found.', 'lhbasicsp')
						}
						readOnly
						rows={18}
						__nextHasNoMarginBottom
					/>
				)}
			</div>
		</SettingsPanel>
	);
};

export default LogsPanel;
