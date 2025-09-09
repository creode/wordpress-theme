<?php
/**
 * Bug Fixes - Load All
 *
 * This file loads all bug fixes for WordPress core issues.
 *
 * @package Creode Theme
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load all bug fixes.
require_once __DIR__ . '/fix-wordpress-theme-json-font-sizes.php';
