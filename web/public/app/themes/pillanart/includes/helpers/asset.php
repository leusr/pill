<?php

/**
 * Normalize assets direcotry path
 * @param null|string $src
 * @return string|false
 */
function assets_path( $src = null ) {
	$root_rel_base = str_replace( home_url(), "", THEME_ASSETS_BASEURL );

	$path = home_url() === site_url() ?
		realpath( rtrim( ABSPATH, '/' ) . $root_rel_base ) :
		realpath( dirname( ABSPATH ) . $root_rel_base );

	if ( false === $path ) {
		return false;
	}

	return isset( $src ) ? "$path/" . ltrim( $src, '/' ) : $path;
}

/**
 * Generate URLs to assets.
 * @param null|string $src
 * @return string
 */
function assets_url( $src = null ) {
	return isset( $src ) ? esc_url( THEME_ASSETS_BASEURL . ltrim( $src, '/' ) ) : THEME_ASSETS_BASEURL;
}

/**
 * Generate URLs to assets using dotmin() helper.
 * @param string $src
 * @return string
 */
function assets_min_url( $src ) {
	return esc_url( dotmin( THEME_ASSETS_BASEURL . ltrim( $src, '/' ) ) );
}

/**
 * Generate relative URLs to assets.
 * wp_enqueue_* needs absolute url, so FV_ASSETS_BASEURL must begin with home_url().
 * This helper trims home_url(), and returns with abs rel url of an asset or the assets
 * directory.
 *
 * @param null|string $src
 * @return string
 */
function assets_rel_url( $src = null ) {
	$root_rel_base = str_replace( home_url(), "", THEME_ASSETS_BASEURL );

	return isset( $src ) ? esc_url( $root_rel_base . ltrim( $src, '/' ) ) : $root_rel_base;
}

/**
 * Insert or return with `.min` depending on SCRIPT_DEBUG and WP_ENV constants.
 *
 * If $src was given insert `.min` before the extension, or leave it as is.
 * If no $src return with `.min` or empty string.
 *
 * @param null|string $src
 * @return string
 */
function dotmin( $src = null ) {
	$is_debug = defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG;

	if ( ! isset( $src ) ) {
		return $is_debug ? "" : '.min';
	}

	if ( $is_debug ) {
		return $src;
	}

	$path = pathinfo( $src );

	return $path['dirname'] . '/' . $path['filename'] . '.min.' . $path['extension'];
}

if ( ! function_exists( 'svg' ) ) {
	/**
	 * Drops out an svg from the - suprise! - svg folder.
	 * @param string $name The filename without extension
	 * @return bool|string False if file not exists
	 */
	function svg( $name ) {
		$rel_assets = str_replace( home_url(), "", assets_url() );
		$path = dirname( ABSPATH ) . $rel_assets . 'svg/' . $name . '.svg';

		if ( ! file_exists( $path ) ) {
			return false;
		}

		return file_get_contents( $path );
	}
}
