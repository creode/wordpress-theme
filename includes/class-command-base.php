<?php
/**
 * Abstract command class.
 *
 * @package Creode Theme
 */

namespace Creode_Theme;

use WP_CLI;

/**
 * Abstract command class.
 */
abstract class Command_Base {

	/**
	 * Returns the name of the command.
	 * This should be a hyphen separated string.
	 * This should be typed immediately after the wp command.
	 * 
	 * @return string The name of the command.
	 */
	abstract protected function name(): string;

	/**
	 * This class must be invocable.
	 * 
	 * @param array $args An array of arguments might be provided.
	 */
	abstract public function __invoke( array $args = array() );

	/**
	 * Registers this command.
	 */
	public static function register() {
		if ( ! defined( 'WP_CLI' ) ) {
			return;
		}

		if ( ! WP_CLI ) {
			return;
		}

		$instance = new static();
		WP_CLI::add_command( $instance->name(), static::class );
	}
}
