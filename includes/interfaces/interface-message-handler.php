<?php
/**
 * Interface to handle messages to the user.
 *
 * @package Creode Theme
 */

namespace Creode_Theme;

/**
 * Interface to handle messages to the user.
 */
interface Message_Handler {

	/**
	 * Handles a message.
	 *
	 * @param string $message The message to handle.
	 * @param bool   $error (Optional) Wether the message should be treated as an error. Defaults to false.
	 */
	public function handle( string $message, bool $error = false );
}
