import { fixHexStyleToStyle } from './utils';

const { MutationObserver } = window;

export default class HexToVarFixObserver {
	constructor() {
		this.observer = new MutationObserver(this.mutationCallback);
	}

	mutationCallback(mutations) {
		for (const m of mutations) {
			for (const node of m.addedNodes) {
				if (!(node instanceof window.HTMLElement)) {
					continue;
				}

				// Detect the color popup.
				if (
					node.matches?.('.mce-colorbutton-grid') ||
					node.querySelector?.('.mce-colorbutton-grid')
				) {
					node.querySelectorAll('div[data-mce-color]').forEach(
						(colorNode) => {
							fixHexStyleToStyle(colorNode);
						}
					);
				}
			}
		}
	}

	observe(targetNode) {
		this.observer.observe(targetNode, { childList: true, subtree: true });
	}

	disconnect() {
		this.observer.disconnect();
	}
}
