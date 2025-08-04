<?php
/**
 * Command to invoke the compilation of theme assets.
 *
 * @package Creode Theme
 */

namespace Creode_Theme;

use WP_CLI;
use Exception;

/**
 * Command to invoke the compilation of theme assets.
 */
class Build_Command extends Command_Base {

	/**
	 * {@inheritdoc}
	 */
	protected function name(): string {
		return 'build';
	}

	/**
	 * Compiles front-end assets for all installed themes.
	 *
	 * This command will build and compile assets (such as SCSS and JavaScript)
	 * for all themes installed on the site. This is especially useful for multisite
	 * networks or sites using parent/child theme structures, ensuring all relevant
	 * theme assets are up to date.
	 *
	 * ## EXAMPLES
	 *
	 *     # Build assets for all installed themes
	 *     $ wp creode-theme:build
	 *
	 * @param array $args Not used.
	 */
	public function __invoke( array $args = array() ) {
		$message_handler = new Command_Message_Handler();

		try {
			All_Blocks_Scss_File_Generator::do_for_all_themes( $message_handler );
			Asset_Builder::do_for_all_themes( $message_handler );
		} catch ( Exception $e ) {
			WP_CLI::error( $e->getMessage() );
		}
	}
}
