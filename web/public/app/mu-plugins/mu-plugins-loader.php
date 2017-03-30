<?php

load_mu_plugin( 'wp-tweaks' );
load_mu_plugin( 'pillanart-ms' );

if ( ! function_exists( 'load_mu_plugins' ) ) {
	/**
	 * This logic works only till dirnames and main php filenames are the same.
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
