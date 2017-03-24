<?php
/*
Plugin Name:   Variable dumpers
Plugin URI:
Description:   Everyones all time favirote debug helpers: <code>pr()</code> = formatted <pre>print_r()</pre>, <code>vd()</code> = formatted <pre>var_dump()</pre>. <code>prc()</code> and <code>vdc()</code> drops the variables to html comment.
Version:       0.1.0
Author:        Gyorgy Papp
Author URI:

License:       WTFPL License
License URI:   http://www.wtfpl.net/
*/

/**
 * Wrap output to stylish <pre> tag.
 *
 * @param string $output            The output what we are interested.
 * @param string $color  (optional) The css color property.
 */
function vds_wrap_to_pre( $output, $color = '#22d' ) {
	echo '<pre style="position: relative;
			z-index: 9000;
			background-color: rgba(255,255,255,.9);
			border: 1px solid rgba(0,0,0,.1);
            box-shadow: 0 3px 0 rgba(0,0,0,.1);
            -webkit-border-radius: 5px;
                    border-radius: 5px;
            color: ' . $color . ';
            font: 13px/1.4 Consolas, monospace;
            margin: 20px;
            padding: 10px;
            white-space: pre-wrap;">'
	. "{$output}</pre>\n";
}

/**
 * Wrap output to html comment.
 *
 * @param string $output
 */
function vds_wrap_to_comment( $output ) {
	echo "\n\n<!--\n{$output}\n-->\n\n";
}

/**
 * Das Terminator function.
 *
 * @param mixed $exitcode
 */
function vds_maybe_terminate_script( $exitcode ) {
	if ( false === $exitcode ) return;
	true === $exitcode && exit;
	exit( $exitcode );
}

if ( ! function_exists( 'pr' ) ) {
	/**
	 * Formatted print_r()
	 *
	 * @param mixed $var             The variable which we are interested.
	 * @param mixed $exit (optional) Exit or not, with exitcode, error message or nothing.
	 */
	function pr( $var, $exit = false ) {
		ob_start();
		print_r( $var );
		vds_wrap_to_pre( ob_get_clean(), '#22d' );
		vds_maybe_terminate_script( $exit );
	}
}

if ( ! function_exists( 'vd' ) ) {
	/**
	 * Formatted var_dump()
	 *
	 * @param mixed $var
	 * @param mixed $exit
	 */
	function vd( $var, $exit = false ) {
		ob_start();
		var_dump( $var );
		vds_wrap_to_pre( ob_get_clean(), '#d22' );
		vds_maybe_terminate_script( $exit );
	}
}

if ( ! function_exists( 'prc' ) ) {
	/**
	 * Comment print_r()
	 *
	 * @param mixed $var
	 * @param mixed $exit
	 */
	function prc( $var, $exit = false ) {
		ob_start();
		print_r( $var );
		vds_wrap_to_comment( ob_get_clean() );
		vds_maybe_terminate_script( $exit );
	}
}

if ( ! function_exists( 'vdc' ) ) {
	/**
	 * Comment var_dump()
	 *
	 * @param mixed $var
	 * @param mixed $exit
	 */
	function vdc( $var, $exit = false ) {
		ob_start();
		var_dump( $var );
		vds_wrap_to_comment( ob_get_clean() );
		vds_maybe_terminate_script( $exit );
	}
}
