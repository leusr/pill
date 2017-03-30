<?php

if ( ! function_exists( 'load_mu_plugin' ) ) {
	/**
	 * Load a must use plugin where the dirnames and main php filenames are same.
	 *
	 * @param $name
	 */
	function load_mu_plugin( $name ) {
		$path = __DIR__ . '/' . $name . '/' . $name . '.php';
		if ( file_exists( $path ) ) {
			require_once $path;
		}
	}
}

load_mu_plugin( 'wp-tweaks' );
load_mu_plugin( 'pillanart-ms' );
