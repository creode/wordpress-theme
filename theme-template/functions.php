<?php
/**
 * Theme functions file.
 *
 * @package :THEME_LABEL
 */

// Do not allow direct access to this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

// Register all menu locations required by this theme.
require_once __DIR__ . '/includes/register-menu-locations.php';

// Register a brand specific block category.
require_once __DIR__ . '/includes/register-brand-block-category.php';

// Load and initialize blocks.
require_once __DIR__ . '/blocks/all.php';

// Register any scripts needed.
require_once __DIR__ . '/includes/register-scripts.php';
