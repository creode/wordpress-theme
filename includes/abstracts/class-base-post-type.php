<?php
/**
 * Base post type class.
 *
 * @package Creode Theme
 */

namespace Creode_Theme;

/**
 * Base post type class.
 */
abstract class Base_Post_Type {

	/**
	 * Singleton instances of this class.
	 *
	 * @var Base_Post_Type[]
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
	 * Post registration process entry point
	 */
	private function __construct() {
		if ( ! $this->setup() ) {
			return;
		}

		$this->register_post_type();
		$this->register_post_taxonomies();
	}

	/**
	 * Function to be overridden for performing post-type-specific setup actions.
	 *
	 * @return bool Whether the setup was successful.
	 */
	protected function setup(): bool {
		$this->update_breadcrumbs();
		return true;
	}

	/**
	 * Register the post type.
	 */
	private function register_post_type() {
		add_action(
			'init',
			function () {
				register_post_type(
					$this->name(),
					$this->get_processed_args()
				);
			}
		);
	}

	/**
	 * Returns the processed post type arguments.
	 * Will generate default values and combine with results of the args function.
	 *
	 * @return array The post type arguments.
	 */
	private function get_processed_args(): array {
		$args = array(
			'public' => $this->public(),
			'label'  => $this->label(),
			'labels' => array(
				'name'         => $this->label(),
				'new_item'     => 'New ' . $this->singular_label(),
				'add_new_item' => 'Add New ' . $this->singular_label(),
				'edit_item'    => 'Edit ' . $this->singular_label(),
			),
		);

		$overrides = $this->args();

		if ( ! empty( $overrides['labels'] ) ) {
			$args['labels'] = array_merge(
				$args['labels'],
				$overrides['labels']
			);
			unset( $overrides['labels'] );
		}

		$args = array_merge(
			$args,
			$overrides
		);

		return $args;
	}

	/**
	 * Register all associated taxonomies.
	 */
	private function register_post_taxonomies() {
		add_action(
			'init',
			function () {
				foreach ( $this->taxonomies() as $taxonomy ) {
					register_taxonomy(
						$this->name() . '-' . $taxonomy->name,
						$this->name(),
						$taxonomy->args
					);
				}
			}
		);
	}

	/**
	 * Provides all associated taxonomies.
	 *
	 * @return Post_Taxonomy_DTO[]
	 */
	protected function taxonomies(): array {
		return array();
	}

	/**
	 * Returns post type arguments.
	 * See: https://developer.wordpress.org/reference/functions/register_post_type/
	 *
	 * @return array The post type arguments.
	 */
	protected function args(): array {
		return array();
	}

	/**
	 * Returns whether a post type is intended for use publicly.
	 *
	 * @return bool Whether a post type is intended for use publicly.
	 */
	abstract protected function public(): bool;

	/**
	 * Returns the name of the post type (Singular).
	 *
	 * @return string The name of the post type.
	 */
	abstract protected function name(): string;

	/**
	 * Returns the label of the post type (Plural).
	 *
	 * @return string The label of the post type.
	 */
	abstract protected function label(): string;

	/**
	 * Returns the singular label of the post type.
	 *
	 * @return string The singular label of the post type.
	 */
	abstract protected function singular_label(): string;

	/**
	 * Returns a meaningful label for the archive page.
	 *
	 * @return string A meaningful label.
	 */
	protected function archive_page_label(): string {
		return $this->label();
	}

	/**
	 * Updates Yoast breadcrumb text.
	 */
	protected function update_breadcrumbs() {
		add_filter(
			'wpseo_frontend_presentation',
			function ( $presentation ) {
				if ( empty( $presentation->breadcrumbs ) ) {
					return $presentation;
				}

				$presentation->breadcrumbs = array_map(
					function ( array $breadchumb_item ) {
						if ( empty( $breadchumb_item['ptarchive'] ) ) {
							return $breadchumb_item;
						}
						if ( $this->name() !== $breadchumb_item['ptarchive'] ) {
							return $breadchumb_item;
						}

						$breadchumb_item['text'] = $this->archive_page_label();

						return $breadchumb_item;
					},
					$presentation->breadcrumbs
				);

				return $presentation;
			}
		);
	}
}
