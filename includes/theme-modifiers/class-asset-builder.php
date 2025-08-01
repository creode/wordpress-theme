<?php
/**
 * Class to compile front-end assets.
 *
 * @package Creode Theme
 */

namespace Creode_Theme;

/**
 * Class to compile front-end assets.
 */
final class Asset_Builder extends Theme_Modifier_Base {

	/**
	 * {@inheritdoc}
	 */
	protected function modify_theme() {
		$this->build();
	}

	/**
	 * Compiles front-end assets.
	 */
	private function build() {
		$output_strings = array();

		exec(
			implode(
				' && ',
				array(
					'cd ' . get_theme_root() . '/' . $this->theme_directory_name,
					'npm install',
					'npm run build',
				)
			),
			$output_strings
		);

		if ( is_null( $this->message_handler ) ) {
			return;
		}

		foreach ( $output_strings as $output_string ) {
			$this->message_handler->handle( $output_string );
		}
	}
}
