<?php
/**
 * Handles the installation of theme files.
 *
 * @package Creode Theme
 */

namespace Creode_Theme;

/**
 * Handles the installation of theme files.
 */
class Installer {

	/**
	 * The theme directory name to install theme files.
	 *
	 * @var string
	 */
	private $theme_name;

	/**
	 * Initializes the object with a theme name.
	 *
	 * @param string|null $theme_name (Optional) The theme directory name. If null or unspecified the active theme will be used.
	 */
	public function __construct( string|null $theme_name = null ) {
		if ( is_null( $theme_name ) ) {
			$theme_name = get_stylesheet();
		}

		$this->theme_name = $theme_name;
	}

	/**
	 * Installs theme files.
	 */
	public function install() {
		$this->copy_theme_files();
	}

	/**
	 * Copies theme files from the theme-template directory into the specified location.
	 */
	private function copy_theme_files() {
		Helpers::copy_directory( __DIR__ . '/../theme-template', get_theme_root() . '/' . $this->theme_name );
	}
}
