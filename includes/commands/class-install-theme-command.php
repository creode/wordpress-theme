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
	 * {@inheritdoc}
	 */
	public function __invoke( array $args = array() ) {
		$theme_name = isset( $args[0] ) ? $args[0] : null;

		try {
			$installer = new Installer( $theme_name );
			$installer->install();
		} catch ( Exception $e ) {
			WP_CLI::error( $e->getMessage() );
		}

		WP_CLI::success( 'Theme files installed successfully.' );
	}
}
