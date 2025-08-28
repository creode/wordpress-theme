<?php
/**
 * Class to facilitate the enqueueing of theme assets.
 *
 * @package Creode Theme
 */

namespace Creode_Theme;

use Idleberg\ViteManifest\Manifest;

/**
 * Class to facilitate the enqueueing of theme assets.
 */
final class Asset_Enqueue {

	/**
	 * Singleton instance.
	 *
	 * @var Asset_Enqueue|null
	 */
	private static $instance = null;

	/**
	 * Array of stylesheet handles to be used as dependancies for enqueued stylesheets.
	 *
	 * @var string[]
	 */
	private $stylesheet_dependencies = array();

	/**
	 * Array of stylesheet URLs to be used as dependancies for enqueued stylesheets.
	 *
	 * @var string[]
	 */
	private $stylesheet_dependency_sources = array();

	/**
	 * Initializes the process.
	 */
	public static function init() {
		if ( ! is_null( self::$instance ) ) {
			return;
		}

		self::$instance = new self();
	}

	/**
	 * Get the singleton instance.
	 *
	 * @return Asset_Enqueue|null The singleton instance or null if uninitialized.
	 */
	public static function get_instance(): Asset_Enqueue|null {
		return self::$instance;
	}

	/**
	 * An array of Manifest objects.
	 *
	 * @var Manifest[]
	 */
	private $manifests = array();

	/**
	 * The main enqueue process.
	 */
	private function __construct() {
		$this->load_manifests();
		$this->enqueue_main_stylesheets();
		$this->enqueue_admin_stylesheets();
		$this->enqueue_editor_stylesheets();
	}

	/**
	 * Loads up to two Manifest objects into the $manifests property.
	 * In the event that a parent and child theme are both active, the manifests for both will be loaded.
	 * If a standalone theme is active, only it's single manifest will be loaded.
	 */
	private function load_manifests() {
		$theme_paths = array_unique(
			array(
				get_template_directory(),
				get_stylesheet_directory(),
			)
		);

		$theme_uris = array_unique(
			array(
				get_template_directory_uri(),
				get_stylesheet_directory_uri(),
			)
		);

		foreach ( $theme_paths as $index => $theme_path ) {
			$manifest_path   = $theme_path . '/dist/.vite/manifest.json';
			$assets_base_uri = $theme_uris[ $index ] . '/dist/assets';

			if ( ! is_readable( $manifest_path ) ) {
				continue;
			}

			array_push(
				$this->manifests,
				new Manifest(
					$manifest_path,
					$assets_base_uri
				)
			);
		}
	}

	/**
	 * Enqueues the front-end stylesheet for all manifests.
	 */
	private function enqueue_main_stylesheets() {
		add_action(
			'wp_enqueue_scripts',
			function () {
				$this->enqueue_stylesheets( 'vite-entry-points/main.js' );
			}
		);
	}

	/**
	 * Enqueues the admin stylesheet for all manifests.
	 */
	private function enqueue_admin_stylesheets() {
		add_action(
			'admin_enqueue_scripts',
			function () {
				$this->enqueue_stylesheets( 'vite-entry-points/admin.js' );
			}
		);
	}

	/**
	 * Enqueues a site editor stylesheet for all manifests.
	 */
	private function enqueue_editor_stylesheets() {
		add_action(
			'init',
			function () {
				foreach ( $this->stylesheet_dependency_sources as $source ) {
					add_editor_style( $source );
				}

				foreach ( $this->manifests as $manifest ) {
					$style = $this->get_manifest_style( $manifest, 'vite-entry-points/admin.js' );

					if ( is_null( $style ) ) {
						continue;
					}

					add_editor_style( $style['url'] );
				}
			}
		);
	}

	/**
	 * Enqueues the stylesheet for all manifests based on a single entrypoint file name.
	 *
	 * @param string $entrypoint The entrypoint file name, relative to a theme root directory.
	 */
	private function enqueue_stylesheets( string $entrypoint ) {
		foreach ( $this->manifests as $manifest ) {
			$style = $this->get_manifest_style( $manifest, $entrypoint );

			if ( is_null( $style ) ) {
				continue;
			}

			$asset = $manifest->getManifest()[ $entrypoint ];
			wp_enqueue_style( $asset['name'], $style['url'], $this->stylesheet_dependencies, $style['hash'] );
		}
	}

	/**
	 * Add a dependancy to be used for main stylesheets.
	 *
	 * @param string $handle A stylesheet handle.
	 * @param string $src A stylesheet source.
	 * @param array  $dependencies (Optional) an optional array of stylesheet handles.
	 * @param string $version (Optional) String specifying stylesheet version number.
	 * @param string $media (Optional) The media for which this stylesheet has been defined. Default 'all'. Accepts media types like 'all', 'print' and 'screen', or media queries like '(orientation: portrait)' and '(max-width: 640px)'.
	 */
	public function add_stylesheet_dependency( string $handle, string $src, array $dependencies = array(), string $version = '1', string $media = 'all' ) {
		if ( in_array( $handle, $this->stylesheet_dependencies, true ) ) {
			return;
		}

		add_action(
			'wp_enqueue_scripts',
			function () use ( $handle, $src, $dependencies, $version, $media ) {
				wp_register_style(
					$handle,
					$src,
					$dependencies,
					$version,
					$media
				);
			},
			5
		);

		add_action(
			'admin_enqueue_scripts',
			function () use ( $handle, $src, $dependencies, $version, $media ) {
				wp_register_style(
					$handle,
					$src,
					$dependencies,
					$version,
					$media
				);
			},
			5
		);

		array_push( $this->stylesheet_dependencies, $handle );
		array_push( $this->stylesheet_dependency_sources, $src );
	}

	/**
	 * Returns stylesheet information for a given manifest and a given Vite entrypoint.
	 *
	 * @param Manifest $manifest The manifest to retrieve stylesheet information from.
	 * @param string   $entrypoint The entrypoint file name, relative to a theme root directory.
	 * @return array|null An array of stylesheet information. Null if this cannot be found.
	 */
	private function get_manifest_style( Manifest $manifest, string $entrypoint ) {
		$styles = $manifest->getStyles( $entrypoint, false );

		if ( ! isset( $styles[0] ) ) {
			return null;
		}

		return $styles[0];
	}

	/**
	 * Registers a script for enqueuing.
	 *
	 * @param string     $handle The scripts handle name.
	 * @param string     $path The path to the script relative to the theme root directory (No proceeding forward slash).
	 * @param array      $dependencies (Optional) The scripts dependencies. Defaults to an empty array.
	 * @param bool|array $in_footer (Optional) Whether to enqueue the script in the footer. Defaults to true.
	 */
	public function register_vite_script( string $handle, string $path, array $dependencies = array(), $in_footer = true ) {
		foreach ( $this->manifests as $manifest ) {
			$entry_point = $manifest->getEntrypoint( $path );
			if ( empty( $entry_point ) ) {
				continue;
			}
			if ( empty( $entry_point['url'] ) ) {
				continue;
			}
			if ( empty( $entry_point['hash'] ) ) {
				continue;
			}

			wp_register_script(
				$handle,
				$entry_point['url'],
				$dependencies,
				$entry_point['hash'],
				$in_footer
			);
			break;
		}
	}
}
