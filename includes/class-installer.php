<?php
/**
 * Handles the installation of theme files.
 *
 * @package Creode Theme
 */

namespace Creode_Theme;

use enshrined\svgSanitize\Helper;

/**
 * Handles the installation of theme files.
 */
class Installer {

	/**
	 * The directory path to install theme files.
	 *
	 * @var string
	 */
	private $theme_path;

	/**
	 * Installs theme files to a specified path.
	 *
	 * @param string|null $theme_path (Optional) The directory path to install theme files. If null on unspecified the active theme will be used.
	 */
	public function __construct( string|null $theme_path = null ) {
		if ( is_null( $theme_path ) ) {
			$theme_path = get_stylesheet_directory();
		}

		$this->theme_path = $theme_path;
		$this->copy_theme_files();
	}

	/**
	 * Copies theme files from the theme-template directory into the specified location.
	 */
	private function copy_theme_files() {
		Helpers::copy_directory( __DIR__ . '/../theme-template', $this->theme_path );
	}
}
