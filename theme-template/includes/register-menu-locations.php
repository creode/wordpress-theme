<?php
/**
 * File for registering menu locations.
 *
 * @package :THEME_LABEL
 * @creode-wordpress-theme-version :THEME_PLUGIN_VERSION
 */

add_action(
	'after_setup_theme',
	function () {
		register_nav_menus(
			array(
				'primary' => 'Primary Menu',
			)
		);
	}
);
