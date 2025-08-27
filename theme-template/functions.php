<?php
/**
 * Theme functions file.
 *
 * @package :THEME_LABEL
 * @creode-wordpress-theme-version :THEME_PLUGIN_VERSION
 */

// Do not allow direct access to this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

// Register post types.
require_once __DIR__ . '/includes/post-types/all.php';

// Register post fields.
require_once __DIR__ . '/includes/post-fields/all.php';

// Register all menu locations required by this theme.
require_once __DIR__ . '/includes/register-menu-locations.php';

// Register a brand specific block category.
require_once __DIR__ . '/includes/register-brand-block-category.php';

// Load and initialize blocks.
require_once __DIR__ . '/blocks/all.php';

// Register any scripts needed.
require_once __DIR__ . '/includes/register-scripts.php';

// Register any stylesheet depandancies.
require_once __DIR__ . '/includes/register-stylesheet-dependencies.php';
