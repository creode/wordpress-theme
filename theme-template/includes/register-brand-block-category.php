<?php
/**
 * File for registering a brand specific block category.
 *
 * @package :THEME_LABEL
 */

// Register the block category.
add_filter(
	'block_categories_all',
	function ( array $categories ) {
		array_unshift(
			$categories,
			array(
				'slug'  => ':THEME_NAME',
				'title' => ':THEME_LABEL',
			)
		);

		return $categories;
	}
);

// Set the block category as default.
Creode_Blocks\Helpers::set_default_block_category( ':THEME_NAME' );
