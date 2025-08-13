<?php
/**
 * File register JavaScript libraries.
 *
 * @package Creode Theme
 */

/**
 * Function to register JavaScript libraries.
 */
function register_js_libraries() {
	$base_url = WPMU_PLUGIN_URL . '/wordpress-theme/js-libraries/';
	wp_register_script( 'block_style_modifier', $base_url . 'block-style-modifier.js', array(), '1.0.0', true );
}

add_action( 'wp_enqueue_scripts', 'register_js_libraries' );
add_action( 'admin_enqueue_scripts', 'register_js_libraries' );
