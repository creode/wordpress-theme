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
	 * The prefix to be added to commands.
	 *
	 * @var string
	 */
	private $name_prefix = 'creode-theme:';

	/**
	 * Returns the name of the command.
	 * This should be a hyphen separated string.
	 * This value will be prefixed with the value of the $name_prefix property.
	 * This should be typed immediately after the wp command to execute the command.
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
		WP_CLI::add_command( $instance->name_prefix . $instance->name(), static::class );
	}
}
