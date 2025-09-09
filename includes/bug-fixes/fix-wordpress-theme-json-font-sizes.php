<?php
/**
 * WordPress Theme.json Font Size Bug Fix
 *
 * Workaround for WordPress 6.8.2 bug where theme.json font size presets
 * are not properly loaded. Intercepts default font size processing and
 * applies theme's intended font size values.
 *
 * @package Creode Theme
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fix WordPress 6.8.2 theme.json font size loading bug.
 *
 * Loads theme.json file directly and extracts font sizes to ensure they
 * are properly applied. Uses wp_theme_json_data_default filter with high
 * priority because wp_theme_json_data_theme filter is not working in WordPress 6.8.2.
 */
add_filter(
	'wp_theme_json_data_default',
	function ( WP_Theme_JSON_Data $theme_json ) {
		// Load theme.json file directly using WordPress API.
		$wp_theme        = wp_get_theme();
		$theme_json_file = $wp_theme->get_file_path( 'theme.json' );

		// Ensure theme.json file exists and is readable.
		if ( ! $theme_json_file || ! is_readable( $theme_json_file ) ) {
			return $theme_json;
		}

		// Parse theme.json file.
		$theme_json_data = json_decode( file_get_contents( $theme_json_file ), true );

		// Validate JSON parsing and structure.
		if ( json_last_error() !== JSON_ERROR_NONE || ! is_array( $theme_json_data ) ) {
			return $theme_json;
		}

		// Extract font sizes from theme.json if they exist.
		$theme_font_sizes = null;

		if ( isset( $theme_json_data['settings']['typography']['fontSizes'] ) && is_array( $theme_json_data['settings']['typography']['fontSizes'] ) && ! empty( $theme_json_data['settings']['typography']['fontSizes'] ) ) {
			$theme_font_sizes = $theme_json_data['settings']['typography']['fontSizes'];
		}

		// Only proceed if we found font sizes in theme.json.
		if ( null === $theme_font_sizes ) {
			return $theme_json;
		}

		$data = $theme_json->get_data();

		// Initialize settings structure if needed.
		if ( ! isset( $data['settings'] ) ) {
			$data['settings'] = array();
		}
		if ( ! isset( $data['settings']['typography'] ) ) {
			$data['settings']['typography'] = array();
		}

		// Apply font sizes from theme.json file.
		$data['settings']['typography']['fontSizes'] = $theme_font_sizes;

		$theme_json->update_with( $data );

		return $theme_json;
	}
);
