<?php
/**
 * Helper functions.
 *
 * @package Creode Theme
 */

namespace Creode_Theme;

/**
 * Helper functions.
 */
class Helpers {

	/**
	 * Performs a deep copy of files.
	 *
	 * @param string        $source_directory_path A path to the directory to copy.
	 * @param string        $destination_directory_path A path to the directory where files should be placed.
	 * @param bool          $merge (Optional) If true pre-existing destination files will not be overridden. Defaults to true.
	 * @param callable|null $file_post_processor (Optional) A function to preform additional processing on each file. This function will be provided with two arguments, the source file path and the destination file path.
	 */
	public static function copy_directory( string $source_directory_path, string $destination_directory_path, bool $merge = true, callable|null $file_post_processor = null ) {
		// If destination directory doesn't exist, create it.
		if ( ! is_dir( $destination_directory_path ) ) {
				// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_mkdir
				mkdir( $destination_directory_path );
		}

		// Loop through files in the source directory.
		foreach ( scandir( $source_directory_path ) as $file ) {
			// Bypass references to parent directories.
			if ( '.' === substr( $file, 0, 1 ) ) {
				continue;
			}

			// If a sub-directory is found, recursively copy it's files.
			if ( is_dir( $source_directory_path . '/' . $file ) ) {
				static::copy_directory( $source_directory_path . '/' . $file, $destination_directory_path . '/' . $file, $merge, $file_post_processor );
				continue;
			}

			// Bypass if $merge is true and destination file already exists.
			if ( $merge && file_exists( $destination_directory_path . '/' . $file ) ) {
				continue;
			}

			// Copy the file.
			copy( $source_directory_path . '/' . $file, $destination_directory_path . '/' . $file );

			// Perform additional processing.
			if ( ! is_null( $file_post_processor ) ) {
				call_user_func(
					$file_post_processor,
					$source_directory_path . '/' . $file,
					$destination_directory_path . '/' . $file
				);
			}
		}
	}
}
