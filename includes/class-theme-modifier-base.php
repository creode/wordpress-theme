<?php
/**
 * Abstract theme modifier class.
 * Used when modifications must be made to a single theme or all themes.
 *
 * @package Creode Theme
 */

namespace Creode_Theme;

/**
 * Abstract theme modifier class.
 */
abstract class Theme_Modifier_Base {

	/**
	 * The name of the theme directory.
	 *
	 * @var string
	 */
	protected $theme_directory_name;

	/**
	 * A message handler to provide any feedback to the user.
	 *
	 * @var Message_Handler|null
	 */
	protected $message_handler;

	/**
	 * Accepts a theme directory name and performs modifications.
	 *
	 * @param string               $theme_directory_name The name of the theme directory.
	 * @param Message_Handler|null $message_handler (Optional) A message handler to provide any feedback to the user.
	 */
	public function __construct( string $theme_directory_name, Message_Handler|null $message_handler = null ) {
		$this->theme_directory_name = $theme_directory_name;
		$this->message_handler      = $message_handler;
		$this->modify_theme();
	}

	/**
	 * Performs all required modifications.
	 */
	abstract protected function modify_theme();
}
