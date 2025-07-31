<?php
/**
 * Creode Theme MU plugin.
 *
 * @package Creode Theme
 */

namespace Creode_Theme;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

// Require dependencies manged by composer.
require_once ABSPATH . 'vendor/autoload.php';

// Require includes.
require_once __DIR__ . '/includes/data-transfer-objects/all.php';
require_once __DIR__ . '/includes/abstracts/all.php';
require_once __DIR__ . '/includes/class-helpers.php';
require_once __DIR__ . '/includes/class-file-string-replacer.php';
require_once __DIR__ . '/includes/class-installer.php';
require_once __DIR__ . '/includes/class-command-base.php';
require_once __DIR__ . '/includes/commands/all.php';
require_once __DIR__ . '/includes/class-asset-enqueue.php';

// Register commands.
Install_Theme_Command::register();

// Enqueue assets from parent and child themes.
Asset_Enqueue::init();
