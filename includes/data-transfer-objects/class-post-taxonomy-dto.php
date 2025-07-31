<?php
/**
 * Class to store details about a taxonomy used by a specific class.
 *
 * @package Creode Theme
 */

namespace Creode_Theme;

/**
 * Class to store details about a taxonomy used by a specific class.
 */
class Post_Taxonomy_DTO {

	/**
	 * The name of the taxonomy.
	 *
	 * @var string
	 */
	private $name = '';

	/**
	 * The taxonomy arguments.
	 * See: https://developer.wordpress.org/reference/functions/register_taxonomy/
	 *
	 * @var array
	 */
	private $args = array();

	/**
	 * The constructor.
	 *
	 * @param string $name The name of the taxonomy.
	 * @param array  $args The taxonomy arguments. See: https://developer.wordpress.org/reference/functions/register_taxonomy/.
	 */
	public function __construct( string $name, array $args ) {
		$this->name = $name;
		$this->args = $args;
	}

	/**
	 * Get a property value.
	 *
	 * @param string $name The property name.
	 * @return mixed The property value.
	 */
	public function __get( string $name ): mixed {
		return $this->$name;
	}
}
