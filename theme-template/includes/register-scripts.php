<?php
/**
 * File for registering JavaScript.
 *
 * @package :THEME_LABEL
 * @creode-wordpress-theme-version :THEME_PLUGIN_VERSION
 */

use Creode_Theme\Asset_Enqueue;

/**
 * Enqueues front-end scripts.
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		$asset_enqueue = Asset_Enqueue::get_instance();
		
		wp_register_script( 'match-height', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.2/jquery.matchHeight-min.js', array( 'jquery' ), '0.7.2', true );

		$asset_enqueue->register_vite_script( 'example_script', 'js/example-script.js' );
		wp_enqueue_script( 'example_script' );
	}
);

/**
 * Enqueues admin scripts.
 */
add_action(
	'admin_enqueue_scripts',
	function () {
		$asset_enqueue = Asset_Enqueue::get_instance();

		wp_register_script( 'match-height', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.2/jquery.matchHeight-min.js', array( 'jquery' ), '0.7.2', true );

		$asset_enqueue->register_vite_script( 'block_styles', 'js/admin/block-styles.js', array( 'block_style_modifier' ) );
		wp_enqueue_script( 'block_styles' );
	}
);
