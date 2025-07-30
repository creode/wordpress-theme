<?php
/**
 * Handles the replacement of strings within text file content.
 *
 * @package Creode Theme
 */

namespace Creode_Theme;

/**
 * Handles the replacement of strings within text file content.
 */
class File_String_Replacer {

	/**
	 * Singleton instance.
	 *
	 * @var File_String_Replacer|null
	 */
	private static $instance = null;

	/**
	 * A keyed array of string replacements.
	 * For each item, the key should be the string to find and replace, the value should be the string to replace it with.
	 *
	 * @var array
	 */
	private $replacements = array();

	/**
	 * Returns the singleton instance.
	 *
	 * @return File_String_Replacer The singleton instance.
	 */
	public static function get_instance(): File_String_Replacer {
		if ( is_null( static::$instance ) ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {}

	/**
	 * Adds/updates replacement strings.
	 *
	 * @param array $replacements See $this->replacements description above.
	 */
	public function update_replacements( array $replacements ) {
		$this->replacements = array_merge(
			$this->replacements,
			$replacements
		);
	}

	/**
	 * Replaces strings within the contents of a specified file.
	 *
	 * @param string $file_path A path to a file whose contents should be updated.
	 */
	public function replace( string $file_path ) {
		// Check if file exists and is readable.
		if ( ! is_readable( $file_path ) ) {
			return;
		}

		// Get file contents.
		$contents = file_get_contents( $file_path );
		if ( false === $contents ) {
				return false;
		}

		// Perform replacements.
		$new_contents = str_replace( array_keys( $this->replacements ), array_values( $this->replacements ), $contents );

		// Write new contents back to file.
		file_put_contents( $file_path, $new_contents );
	}
}
