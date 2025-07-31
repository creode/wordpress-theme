<?php
/**
 * Base post fields class.
 * Registers a new field group against posts that have a given support.
 *
 * @package Creode Theme
 */

 namespace Creode_Theme;

/**
 * Base extra post fields class.
 */
abstract class Base_Post_Fields {

	/**
	 * Singleton instances of this class.
	 *
	 * @var Base_Extra_Post_Fields[]
	 */
	private static $instances = array();

	/**
	 * Set singleton instance of this class.
	 */
	public static function init() {
		if ( isset( static::$instances[ static::class ] ) ) {
			return;
		}

		static::$instances[ static::class ] = new static();
	}

	/**
	 * Registration process entry point
	 */
	private function __construct() {
		$this->register_field_group();
	}

	/**
	 * Returns the name of the group.
	 *
	 * @return string The name of the group.
	 */
	abstract protected function group_name(): string;

	/**
	 * Returns the user facing title of the field group.
	 *
	 * @return string The title of the field group.
	 */
	abstract protected function group_title(): string;

	/**
	 * Returns the ACF fields array for the field group.
	 *
	 * @return array An ACF fields array.
	 */
	abstract protected function fields(): array;

	/**
	 * Returns the post support string that should be used to determine if the field group should be set against a post type.
	 *
	 * @return string The support.
	 */
	abstract protected function support(): string;

	/**
	 * Registers a fields group containing the fields returned from the "fields" function.
	 */
	private function register_field_group() {
		add_action(
			'init',
			function () {
				if ( ! function_exists( 'acf_add_local_field_group' ) ) {
					return;
				}

				acf_add_local_field_group(
					array(
						'key'      => 'group_' . $this->group_name(),
						'title'    => $this->group_title(),
						'fields'   => $this->fields(),
						'location' => $this->get_location_array(),
						'position' => 'side',
					)
				);
			},
			20
		);
	}

	/**
	 * Provides the ACF location array for the fields group.
	 *
	 * @return array An ACF location array.
	 */
	private function get_location_array(): array {
		return array_map(
			function ( string $post_type ) {
				return array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => $post_type,
					),
				);
			},
			$this->get_applicable_post_types()
		);
	}

	/**
	 * Provides an array of all applicable post types for this field group.
	 *
	 * @return string[] An array of post types.
	 */
	private function get_applicable_post_types(): array {
		$post_types = array();

		foreach ( get_post_types() as $post_type ) {
			if ( ! post_type_supports( $post_type, $this->support() ) ) {
				continue;
			}

			array_push( $post_types, $post_type );
		}

		return $post_types;
	}
}
