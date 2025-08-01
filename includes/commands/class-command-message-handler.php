<?php
/**
 * A class to handle command line feedback messages.
 *
 * @package Creode Theme
 */

namespace Creode_Theme;

use WP_CLI;

/**
 * A class to handle command line feedback messages.
 */
class Command_Message_Handler implements Message_Handler {

	/**
	 * {@inheritdoc}
	 */
	public function handle( string $message, bool $error = false ) {
		if ( $error ) {
			WP_CLI::error( $message );
		}

		WP_CLI::line( $message );
	}
}
