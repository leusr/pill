<?php
/*
Plugin Name:    Minify HTML and ASCII Art
Plugin URI:
Description:    Quick and dirty on-the-fly HTML compression with nice ASCII art based on Minify project's <a href="https://github.com/mrclay/minify/blob/master/lib/Minify/HTML.php" target="_blank">Minify_HTML</a> class written by Stephen Clay.
Version:        1.0.0
Author:         Gyorgy Papp
Author URI:
License:        WTFPL
License URI:    http://www.wtfpl.net/txt/copying/
*/

// If this file is called directly, abort.
! defined( 'WPINC' ) && die;

// Load the class
require_once 'Compress_HTML.php';

/**
 * Output buffer callback
 *
 * @param string $html
 * @return string
 */
function minify_html_obcb( $html ) {
	$options = [
		'useNewLines'     => false,
		'isXhtml'         => false,
		'jsCleanComments' => false
	];

	$html = Compress_HTML::minify( $html, $options );
	$html = add_ascii_art( $html );

	return $html;
}

/**
 * Start the output buffering
 */
function minify_html_ob() {
	if ( is_admin() || is_login_page() )
		return;

	ob_start( 'minify_html_obcb' );
}
add_action( 'init', 'minify_html_ob' );

/**
 * Replace `<meta name="ascii-art" content="yes">` with ASCII Art
 *
 * @param string $html
 * @return string
 */
function add_ascii_art( $html ) {
	$tag = '<meta name="ascii-art" content="yes">';

	if ( strstr( $html, $tag ) ) {
		$path = plugin_dir_path( __FILE__) . '/ascii_art.html';
		$html = file_exists( $path ) ?
			str_replace( $tag, file_get_contents( $path ), $html ) :
			str_replace( $tag, "", $html );
	}

	return $html;
}
