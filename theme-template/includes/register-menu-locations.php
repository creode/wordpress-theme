<?php
/**
 * File for registering menu locations.
 *
 * @package :THEME_LABEL
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
