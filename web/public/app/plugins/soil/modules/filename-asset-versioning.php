<?php

namespace Roots\Soil\FilenameAssetVersioning;

/*
 * Remove version query string from all styles and scripts,
 * but inject it to the filename.
 *
 * (!) This feature needs the following .htaccess lines,
 *     before WordPress redirect rules:
 *
 *     RewriteCond %{REQUEST_FILENAME} !-f
 *     RewriteRule ^(.+)-([\d\.]+)(\.min)?\.(css|js)$ $1$3.$4 [L]
 *
 * You can enable/disable this feature with:
 *
 *     add_theme_support( 'soil-filename-asset-versioning' );
 *
 */

function filename_script_version( $src ) {
	if ( ! $src ) {
		return false;
	}

	/**
	 * @var $scheme
	 * @var $host
	 * @var $path
	 * @var $query
	 */
	extract( parse_url( $src ) );
	if ( ! isset( $query ) ) {
		return $src;
	}

	if ( isset( $host ) && $host !== $_SERVER['HTTP_HOST'] ) {
		// Just remove version on external resources
		return esc_url( remove_query_arg( 'ver', $src ) );
	}

	preg_match( '/&?ver=([\d\.]+)$/', $query, $matches );
	if ( ! isset( $matches[1] ) ) {
		return false;
	} else {
		$ver = $matches[1];
		$query = remove_query_arg( 'ver', $query );
	}

	/**
	 * @var $dirname
	 * @var $basename
	 * @var $extension
	 * @var $filename
	 */
	extract( pathinfo( $path ) );
	$filename = '.min' === substr( $filename, -4 ) ?
		substr( $filename, 0, -4 ) . "-$ver.min.$extension" :
		"$filename-$ver.$extension";

	$src = isset( $scheme ) && isset( $host ) ? "$scheme://$host" : "";
	$src .= isset( $dirname ) && '\\' !== $dirname ? "$dirname/$filename" : "/$filename";
	$src .= empty( $query) ? "" : "?$query";

	return esc_url( $src );
}
add_filter( 'script_loader_src', __NAMESPACE__ . '\\filename_script_version', 15, 1 );
add_filter( 'style_loader_src', __NAMESPACE__ . '\\filename_script_version', 15, 1 );
