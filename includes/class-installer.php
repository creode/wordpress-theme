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
	 * The file string replacer.
	 *
	 * @var File_String_Replacer
	 */
	private $file_string_replacer;

	/**
	 * The theme directory name to install theme files.
	 *
	 * @var string
	 */
	private $theme_name;

	/**
	 * A human readable label to identify the theme.
	 *
	 * @var string
	 */
	private $theme_label;

	/**
	 * Initializes the object with a theme name.
	 *
	 * @param string|null $theme_name (Optional) The theme directory name. If null or unspecified the active theme will be used.
	 */
	public function __construct( string|null $theme_name = null ) {
		$this->file_string_replacer = File_String_Replacer::get_instance();

		if ( is_null( $theme_name ) ) {
			$theme_name = get_stylesheet();
		}

		$this->theme_name = $theme_name;
		$this->set_theme_label();
		$this->update_file_string_replacements();
	}

	/**
	 * Sets the $theme_label property based on the provided theme name.
	 */
	private function set_theme_label() {
		$theme = wp_get_theme( $this->theme_name );

		if ( $theme->exists() && is_string( $theme->title ) ) {
			$this->theme_label = ucwords( $theme->title );
			return;
		}

		$this->theme_label = str_replace( '_', ' ', $this->theme_name );
		$this->theme_label = str_replace( '-', ' ', $this->theme_name );
		$this->theme_label = ucwords( $this->theme_label );
	}

	/**
	 * Updates the global file string replacements based on known values within the context of this class.
	 */
	private function update_file_string_replacements() {
		$this->file_string_replacer->update_replacements(
			array(
				':THEME_NAME'  => $this->theme_name,
				':THEME_LABEL' => $this->theme_label,
			)
		);
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
		Helpers::copy_directory(
			__DIR__ . '/../theme-template',
			get_theme_root() . '/' . $this->theme_name,
			true,
			function ( string $source_file_path, $destination_file_path ) {
				$this->file_string_replacer->replace( $destination_file_path );
			}
		);
	}
}
