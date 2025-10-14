<?php
/**
 * Helper functions.
 *
 * @package Creode Theme
 */

namespace Creode_Theme;

use Composer\InstalledVersions;

/**
 * Helper functions.
 */
final class Helpers {

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
			if ( '.' === substr( $file, 0, 1 ) && is_dir( $source_directory_path . '/' . $file ) ) {
				continue;
			}

			// If a sub-directory is found, recursively copy it's files.
			if ( is_dir( $source_directory_path . '/' . $file ) ) {
				self::copy_directory( $source_directory_path . '/' . $file, $destination_directory_path . '/' . $file, $merge, $file_post_processor );
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

	/**
	 * Retrieves the current version of this plugin.
	 *
	 * @return string The current version of this plugin.
	 */
	public static function get_plugin_version(): string {
		$default_version = '1.0.0';

		if ( ! class_exists( InstalledVersions::class ) ) {
			return $default_version;
		}

		$version = InstalledVersions::getPrettyVersion( 'creode/wordpress-theme' );

		if ( is_null( $version ) ) {
			return $default_version;
		}

		return $version;
	}

	/**
	 * Returns an array of all theme directory names.
	 *
	 * @return array Theme directory names.
	 */
	public static function get_all_theme_names(): array {
		$theme_root = get_theme_root();
		$names      = array();
		foreach ( scandir( $theme_root ) as $file ) {
			// Bypass references to parent directories.
			if ( '.' === substr( $file, 0, 1 ) && is_dir( $theme_root . '/' . $file ) ) {
				continue;
			}

			// Bypass if file is not a directory.
			if ( ! is_dir( $theme_root . '/' . $file ) ) {
				continue;
			}

			array_push( $names, $file );
		}

		return $names;
	}

	/**
	 * Creates a block pattern if it doesn't already exist.
	 *
	 * @param string $slug    The slug for the block pattern.
	 * @param string $name    The name/title for the block pattern.
	 * @param string $content The HTML content for the block pattern.
	 *
	 * @return void
	 */
	public static function make_pattern( string $slug, string $name, string $content ): void {
		// Check if a block pattern with this slug already exists.
		$existing_pattern = get_page_by_path( $slug, OBJECT, 'wp_block' );

		// If pattern exists, exit early.
		if ( $existing_pattern ) {
			return;
		}

		// Create the new block pattern.
		wp_insert_post(
			array(
				'post_title'   => $name,
				'post_name'    => $slug,
				'post_content' => $content,
				'post_status'  => 'publish',
				'post_type'    => 'wp_block',
			)
		);
	}
}
