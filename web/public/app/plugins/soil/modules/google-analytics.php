<?php

namespace Roots\Soil\GoogleAnalytics;

/*
 * Google Analytics snippet from HTML5 Boilerplate
 *
 * Cookie domain is 'auto' configured. See: http://goo.gl/VUCHKM
 *
 * You can enable/disable this feature with:
 *
 *     add_theme_support( 'soil-google-analytics', 'UA-XXXXX-Y' );
 * or:
 *     add_theme_support( 'soil-google-analytics', 'UA-XXXXX-Y', 'wp_head' );
 *
 */

function load_script() {
	$gaID = options( 'gaID' );
	if ( ! $gaID ) {
		return;
	}
	$loadGA = ( ! defined('WP_ENV' ) || \WP_ENV === 'production' ) && ! current_user_can( 'manage_options' );
	$loadGA = apply_filters('soil/loadGA', $loadGA);

	if ( $loadGA ) : ?>
<script src="https://www.google-analytics.com/analytics.js" async defer></script>
<script>window.ga=function(){ga.q.push(arguments)};ga.q=[];ga.l=+new Date;ga('create','<?= $gaID; ?>','auto');ga('send','pageview')</script>
	<?php else : ?>
<script>(function(s,o,i,l){s.ga=function(){s.ga.q.push(arguments);if(o['log'])o.log(i+l.call(arguments))};s.ga.q=[];s.ga.l=+new Date;}(window,console,'Google Analytics: ',[].slice));ga('create','<?= $gaID; ?>','auto');ga('send','pageview')</script>
	<?php endif;
}

function options( $option = null ) {
	static $options;
	if ( ! isset( $options ) ) {
		$options = \Roots\Soil\Options::getByFile( __FILE__ ) + ['', 'wp_footer'];
		$options['gaID'] = &$options[0];
		$options['hook'] = &$options[1];
	}

	return is_null( $option ) ? $options : $options[ $option ];
}

add_action( options( 'hook' ), __NAMESPACE__ . '\\load_script', 20 );
