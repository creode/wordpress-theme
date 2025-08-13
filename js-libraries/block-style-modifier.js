/**
 * Class to handle the addition and removal of block styles.
 * Functions "addStyle" and "removeStyle" can be called an any time because this class handles the awaiting of dependencies.
 */
class Block_Style_Modifier {

	/**
	 * Add a style to an existing block.
	 *
	 * @param {string} blockName The full name of the block to add a style to.
	 * @param {string} styleName The name of the style to add. Should be lowercase, hypen separated and contain no spaces.
	 * @param {string} styleLabel Human readable text to represent the style.
	 */
	async addStyle(blockName, styleName, styleLabel) {
		await this.awaitDependencies();
		wp.blocks.registerBlockStyle(
			blockName,
			{
				name: styleName,
				label: styleLabel,
			}
		);
	}

	/**
	 * Remove a style from an existing block.
	 *
	 * @param {string} blockName The full name of the block to remove the style from.
	 * @param {string} styleName The name of the style to remove.
	 */
	async removeStyle(blockName, styleName) {
		await this.awaitDependencies();
		await this.awaitStyleAvailability(blockName, styleName);
		wp.blocks.unregisterBlockStyle(blockName, styleName);
	}

	/**
	 * Wait until WordPress Blocks API is available.
	 */
	async awaitDependencies() {
		await new Promise(
			(resolve) => {
				const interval = setInterval(
					() => {
						if (typeof wp == 'undefined') {
							return;
						}
						if (typeof wp.blocks == 'undefined') {
							return;
						}
	
						clearInterval(interval);
						resolve();
					},
					10
				);
			}
		);
	}

	/**
	 * Waits for a style to become availible for a particular block.
	 *
	 * @param {string} blockName The name of the block.
	 * @param {string} styleName The name of the style.
	 */
	async awaitStyleAvailability(blockName, styleName) {
		await new Promise(
			(resolve) => {
				const interval = setInterval(
					() => {
						const block = wp.blocks.getBlockType(blockName);

						if (typeof block == 'undefined') {
							return;
						}

						if (!block.styles.filter((style) => { return style.name == styleName; }).length) {
							return;
						}

						clearInterval(interval);
						resolve();
					},
					10
				);
			}
		);
	}
}