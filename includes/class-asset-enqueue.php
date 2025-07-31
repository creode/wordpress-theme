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
	 * Initializes the process.
	 */
	public static function init() {
		if ( ! is_null( static::$instance ) ) {
			return;
		}

		static::$instance = new static();
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
			$manifest_path = $theme_path . '/dist/.vite/manifest.json';
			$assets_base_uri = $theme_uris[$index] . '/dist/assets';

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
				$this->enqueue_stylesheets( 'main.js' );
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
				$this->enqueue_stylesheets( 'admin.js' );
			}
		);
	}

	/**
	 * Enqueues a site editor stylesheet for all manifests.
	 */
	private function enqueue_editor_stylesheets() {
		foreach ( $this->manifests as $manifest ) {
			$style = $this->get_manifest_style( $manifest, 'admin.js' );

			if ( is_null( $style ) ) {
				continue;
			}

			add_editor_style( $style['url'] );
		}
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

			$asset = $manifest->getManifest()[$entrypoint];
			wp_enqueue_style( $asset['name'], $style['url'], array(), $style['hash'] );
		}
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
}
