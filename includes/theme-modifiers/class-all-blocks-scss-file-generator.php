<?php
/**
 * Class to generate the SCSS file that invokes render mixins for all block stylesheets.
 *
 * @package Creode Theme
 */

namespace Creode_Theme;

/**
 * Class to generate the SCSS file that invokes render mixins for all block stylesheets.
 */
final class All_Blocks_Scss_File_Generator extends Theme_Modifier_Base {

	/**
	 * {@inheritdoc}
	 */
	protected function modify_theme() {
		$this->generate_file();
	}

	/**
	 * Generates the file.
	 */
	private function generate_file() {
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_is_writable
		if ( ! is_writable( get_theme_root() . '/' . $this->theme_directory_name . '/blocks/_all.scss' ) ) {
			return;
		}
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
		file_put_contents(
			get_theme_root() . '/' . $this->theme_directory_name . '/blocks/_all.scss',
			$this->generate_file_content()
		);
	}

	/**
	 * Generates the file content.
	 *
	 * @return string The file content.
	 */
	private function generate_file_content(): string {
		$content     = '';
		$stylesheets = $this->get_stylesheets();

		foreach ( $stylesheets as $stylesheet ) {
			$content .= '@use \'' . $stylesheet . '\';' . PHP_EOL;
		}

		$content .= PHP_EOL;

		$content .= '@mixin render {' . PHP_EOL;

		foreach ( array_keys( $stylesheets ) as $stylesheet ) {
			$content .= '	@include ' . $stylesheet . '.render;' . PHP_EOL;
		}

		$content .= '}';

		return $content;
	}

	/**
	 * Returns an array of stylesheet paths relative to the blocks directory.
	 *
	 * @return array A keyed array of stylesheets. For each item the value is the relative file path and the key is the name of the stylesheet as it would be recognised by modular SASS.
	 */
	private function get_stylesheets(): array {
		$blocks_directory_path = get_theme_root() . '/' . $this->theme_directory_name . '/blocks';

		if ( ! is_dir( $blocks_directory_path ) ) {
			return array();
		}

		$stylesheets = array();

		foreach ( scandir( $blocks_directory_path ) as $block_directory ) {
			// Bypass references to parent directories.
			if ( '.' === substr( $block_directory, 0, 1 ) && is_dir( $blocks_directory_path . '/' . $block_directory ) ) {
				continue;
			}

			$assets_directory_path = $blocks_directory_path . '/' . $block_directory . '/assets';

			if ( ! is_dir( $assets_directory_path ) ) {
				continue;
			}

			foreach ( scandir( $assets_directory_path ) as $asset ) {
				// Bypass references to parent directories.
				if ( '.' === substr( $asset, 0, 1 ) && is_dir( $assets_directory_path . '/' . $asset ) ) {
					continue;
				}
				// Bypass files that arn't .scss files.
				if ( '.scss' !== substr( $asset, -5 ) ) {
					continue;
				}

				// Bypass if the file doesn't contain a render mixin.
				// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
				if ( ! str_contains( file_get_contents( $assets_directory_path . '/' . $asset ), '@mixin render' ) ) {
					continue;
				}

				$asset_path_parts           = explode( '/', $asset );
				$asset_name                 = array_pop( $asset_path_parts );
				$asset_name                 = str_replace( '.scss', '', $asset_name );
				$asset_name                 = '_' === substr( $asset_name, 0, 1 ) ? substr( $asset_name, 1 ) : $asset_name;
				$stylesheets[ $asset_name ] = $block_directory . '/assets/' . $asset;
			}
		}

		return $stylesheets;
	}
}
