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

require_once __DIR__ . '/includes/class-helpers.php';
require_once __DIR__ . '/includes/class-file-string-replacer.php';
require_once __DIR__ . '/includes/class-installer.php';
require_once __DIR__ . '/includes/class-command-base.php';
require_once __DIR__ . '/includes/commands/class-install-theme-command.php';

// Register commands.
Install_Theme_Command::register();
