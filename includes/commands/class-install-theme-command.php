<?php
/**
 * Command to invoke the installation of theme files.
 *
 * @package Creode Theme
 */

namespace Creode_Theme;

use WP_CLI;
use Exception;

/**
 * Command to invoke the installation of theme files.
 */
class Install_Theme_Command extends Command_Base {

	/**
	 * {@inheritdoc}
	 */
	protected function name(): string {
		return 'install';
	}

	/**
	 * Installs theme infrastructure files for custom development.
	 *
	 * This command sets up a standardised directory structure in a theme, including:
	 * - SASS build process files
	 * - Folders for post types, post fields, taxonomies, blocks, etc.
	 *
	 * If a theme directory name is not provided, files will be installed to the currently active theme.
	 *
	 * ## OPTIONS
	 *
	 * [<theme-directory>]
	 * : The directory name of the theme to install files to.
	 *   If omitted, the active theme will be used.
	 *
	 * ## EXAMPLES
	 *
	 *     # Install files to the active theme
	 *     $ wp creode-theme:install
	 *
	 *     # Install files to a specific theme directory
	 *     $ wp creode-theme:install my-child-theme
	 *
	 * @param array $args Optional. Command arguments. [<theme-directory>].
	 */
	public function __invoke( array $args = array() ) {
		$theme_name = isset( $args[0] ) ? $args[0] : null;

		try {
			$installer = new Installer( $theme_name, new Command_Message_Handler() );
			$installer->install();
		} catch ( Exception $e ) {
			WP_CLI::error( $e->getMessage() );
		}

		WP_CLI::success( 'Theme files installed successfully.' );
	}
}
