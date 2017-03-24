<?php

/**
 * Nice image title.
 * Uppercase first letter, and replace dashes/underscores to space.
 *
 * @param string $title
 * @return string
 */
function nice_image_title( $title ) {
	return esc_attr( ucfirst( str_replace( [ '-', '_' ], ' ', $title ) ) );
}

/**
 * Print wrapper to fix_html_markup()
 *
 * @param string $content
 */
function the_fixed_html_markup( $content ) {
	echo fix_html_markup( $content );
}

/**
 * Fix html mark up on several WordPress functions.
 *
 * @param string $content
 * @return string
 */
function fix_html_markup( $content ) {

	// wp_get_archives() uses single quotes
	// wp_get_archives() uses non breakable spaces
	// wp_list_categories() leave a space if description not set on a category
	// wp_list_categories() adds line break before li closing tag
	$content = str_replace( [ "'", '&nbsp;', ' >', "\n</li>" ], [ '"', ' ', '>', '</li>' ], $content );

	 // wp_list_categories() adds classes
	$content = preg_replace( '~ class=\"[a-zA-Z0-9\-_ ]+\"~', "", $content );

	// wp_list_categories() adds separator after last item
	$content = trim( $content, " ,;\t\n" );

	return $content;
}

/**
 * Format an anchor by cutting
 * down protocol and www
 *
 * @param  string $url
 * @return string
 */
function url2label( $url ) {
	return str_replace( array( 'https://', 'http://', 'www.' ), '', $url );
}

/**
 * Wrap first world to a strong tag
 *
 * @param  string $str
 * @return string
 */
function strong_first_word( $str ) {
	$words = explode( ' ', $str );
	if ( 2 < count( $words ) ) {
		$str = '<strong>' . $words[0] . '</strong>';
	} else {
		$str = '<strong>' . $words[0] . '</strong> ';
		unset( $words[0] );
		$str .= implode( ' ', $words );
	}

	return $str;
}

/**
 * Multilangual time since
 *
 * @param  int $time Unix timestamp of date you want to calculate the time since for
 * @return string
 */
function ml_time_since( $time ) {
	$unknown_text = __( 'Sometime&#8230;', 'pillanart-theme' );
	$right_now_text = __( 'Right now', 'pillanart-theme' );

	$chunks = [
			YEAR_IN_SECONDS,
			DAY_IN_SECONDS * 30,
			WEEK_IN_SECONDS,
			DAY_IN_SECONDS,
			HOUR_IN_SECONDS,
			MINUTE_IN_SECONDS
	];
	$now = current_time( 'timestamp' );
	$seconds = $now - $time;

	// Init output string to avoid PhpStorm warning
	$output = $unknown_text;

	if ( $seconds > 0 ) {
		// Init count to avoid PhpStrong warning
		$count = 0;
		// Finding the biggest chunk (if the chunk fits, break)
		for ( $i = 0, $j = count( $chunks ); $i < $j; ++ $i ) {
			if ( ( $count = floor( $seconds / $chunks[ $i ] ) ) != 0 ) {
				break;
			}
		}

		if ( ! isset( $chunks[ $i ] ) ) {
			$output = $right_now_text;
		} else {
			switch ( $chunks[ $i ] ) {
				case YEAR_IN_SECONDS:
					$output = sprintf( _n( '%d year ago', '%d years ago', $count, 'pillanart-theme' ), $count );
					break;
				case DAY_IN_SECONDS * 30:
					$output = sprintf( _n( '%d month ago', '%d months ago', $count, 'pillanart-theme' ), $count );
					break;
				case WEEK_IN_SECONDS:
					$output = sprintf( _n( '%d week ago', '%d weeks ago', $count, 'pillanart-theme' ), $count );
					break;
				case DAY_IN_SECONDS:
					$output = sprintf( _n( '%d day ago', '%d days ago', $count, 'pillanart-theme' ), $count );
					break;
				case HOUR_IN_SECONDS:
					$output = sprintf( _n( '%d hour ago', '%d hours ago', $count, 'pillanart-theme' ), $count );
					break;
				case MINUTE_IN_SECONDS:
					$output = sprintf( _n( '%d minute ago', '%d minutes ago', $count, 'pillanart-theme' ), $count );
					break;
			}
		}
	}

	return $output;
}

// --------------------------------------------------------------------------------------
// Next function(s) may declared in a plugin.
// Plugins loads earlier than the theme.
// --------------------------------------------------------------------------------------

if ( ! function_exists( 'get_shorter' ) ) {
	/**
	 * Character based substring at words
	 *
	 * @param string $str
	 * @param int    $len
	 * @param string $chunkend
	 * @return string
	 */
	function get_shorter( $str, $len, $chunkend ) {
		$charset = get_bloginfo( 'charset' );

		if ( mb_strlen( $str, $charset ) <= $len ) {
			return $str;
		}

		// get real length of chunk ending by count html enities as one
		$chlen = mb_strlen( $chunkend, $charset );
		preg_match_all( '/&[#a-z0-9]+;/', $chunkend, $matches );
		if ( isset( $matches[0] ) ) {
			foreach ( $matches[0] as $match ) {
				$chlen -= mb_strlen( $match, $charset ) - 1;
			}
		}

		// cut at words
		$subex = mb_substr( $str, 0, $len - mb_strlen( $chunkend, $charset ), $charset );
		$exwords = explode( ' ', $subex );
		$excut = - mb_strlen( $exwords[ count( $exwords ) - 1 ], $charset );
		$str = $excut < 0 ? mb_substr( $subex, 0, $excut, $charset ) : $subex;

		// trim some in-phrase punctation along with space
		$str = trim( $str, ' ,;:');

		return $str . $chunkend;
	}
}
