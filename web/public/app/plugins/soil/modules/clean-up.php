<?php

namespace Roots\Soil\CleanUp;

/*
 * Clean up WordPress in html head, content and some admin areas.
 *
 * You can enable/disable this feature with:
 *
 *      add_theme_support( 'soil-clean-up' );
 *
 */

/**
 * Clean up wp_head()
 *
 * Remove unnecessary <link>'s
 * Remove inline CSS and JS from WP emoji support
 * Remove inline CSS used by Recent Comments widget
 * Remove inline CSS used by posts with galleries
 * Remove self-closing tag and change ''s to "'s on rel_canonical()
 */
function head_cleanup() {
	// Originally from http://wpengineer.com/1438/wordpress-header/
	remove_action( 'wp_head', 'feed_links_extra', 3 );
	add_action( 'wp_head', 'ob_start', 1, 0 );
	add_action( 'wp_head', function() {
		$pattern = '/.*' . preg_quote( esc_url( get_feed_link( 'comments_' . get_default_feed() ) ), '/' ) . '.*[\r\n]+/';
		echo preg_replace( $pattern, '', ob_get_clean() );
	}, 3, 0 );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'use_default_gallery_style', '__return_false' );

	global $wp_widget_factory;

	if ( isset( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'] ) ) {
		remove_action( 'wp_head', [
			$wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
			'recent_comments_style'
		] );
	}

	if ( ! class_exists( 'WPSEO_Frontend' ) ) {
		remove_action( 'wp_head', 'rel_canonical' );
		add_action( 'wp_head', __NAMESPACE__ . '\\rel_canonical' );
	}
}
add_action( 'init', __NAMESPACE__ . '\\head_cleanup' );

/**
 * Add proper canonical link (if WPSEO not handled this already)
 */
function rel_canonical() {
	global $wp_the_query;

	if ( ! is_singular() ) {
		return;
	}

	if ( ! $id = $wp_the_query->get_queried_object_id() ) {
		return;
	}

	$link = get_permalink( $id );
	echo '<link rel="canonical" href="' . $link . '">' . "\n";
}

/**
 * Remove the WordPress version from RSS feeds
 */
add_filter( 'the_generator', '__return_false' );

/**
 * Clean up language_attributes() used in <html> tag
 * Remove dir="ltr"
 */
function language_attributes() {
	$attributes = [];

	if ( is_rtl() ) {
		$attributes[] = 'dir="rtl"';
	}

	$lang = get_bloginfo( 'language' );

	if ( $lang ) {
		$attributes[] = "lang=\"$lang\"";
	}

	$output = implode( ' ', $attributes );
	$output = apply_filters( 'soil/language_attributes', $output );

	return $output;
}
add_filter( 'language_attributes', __NAMESPACE__ . '\\language_attributes' );

/**
 * Clean up output of stylesheet <link> tags
 *
 * @param string $input
 * @return string
 */
function clean_style_tag( $input ) {
    $link = "!<link rel='stylesheet'\s?(id='[^']+')?\s+href='(.*)' type='text/css' media='(.*)' />!";
	preg_match_all( $link, $input, $matches );
    if ( empty( $matches[2] ) ) {
        return $input;
    }

	// Only display media if it is meaningful
	$media = $matches[3][0] !== '' && $matches[3][0] !== 'all' ? ' media="' . $matches[3][0] . '"' : '';

	return '<link rel="stylesheet" href="' . $matches[2][0] . '"' . $media . '>' . "\n";
}
add_filter( 'style_loader_tag', __NAMESPACE__ . '\\clean_style_tag' );

/**
 * Clean up output of <script> tags
 *
 * @param string $input
 * @return mixed
 */
function clean_script_tag( $input ) {
	$input = str_replace( "type='text/javascript' ", '', $input );

	return str_replace( "'", '"', $input );
}
add_filter( 'script_loader_tag', __NAMESPACE__ . '\\clean_script_tag' );

/**
 * Wrap embedded media as suggested by Readability
 * @link https://gist.github.com/965956
 * @link http://www.readability.com/publishers/guidelines#publisher
 *
 * @param string $cache
 * @return string
 */
function embed_wrap( $cache ) {
	return '<div class="entry-content-asset">' . $cache . '</div>';
}
add_filter( 'embed_oembed_html', __NAMESPACE__ . '\\embed_wrap' );

/**
 * Remove unnecessary dashboard widgets
 * @link http://www.deluxeblogtips.com/2011/01/remove-dashboard-widgets-in-wordpress.html
 */
function remove_dashboard_widgets() {
	remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
	// remove_meta_box( 'dashboard_primary', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
}
add_action( 'admin_init', __NAMESPACE__ . '\\remove_dashboard_widgets' );

/**
 * Remove unnecessary self-closing tags
 *
 * @param string $input
 * @return string
 */
function remove_self_closing_tags( $input ) {
	return str_replace( ' />', '>', $input );
}
add_filter( 'get_avatar', __NAMESPACE__ . '\\remove_self_closing_tags' ); // <img />
add_filter( 'comment_id_fields', __NAMESPACE__ . '\\remove_self_closing_tags' ); // <input />
add_filter( 'post_thumbnail_html', __NAMESPACE__ . '\\remove_self_closing_tags' ); // <img />

/**
 * Don't return the default description in the RSS feed if it hasn't been changed
 *
 * @param string $bloginfo
 * @return string
 */
function remove_default_description( $bloginfo ) {
	$default_tagline = 'Just another WordPress site';

	return ( $bloginfo === $default_tagline ) ? '' : $bloginfo;
}
add_filter( 'get_bloginfo_rss', __NAMESPACE__ . '\\remove_default_description' );

/**
 * Filter function used to remove the tinymce emoji plugin.
 *
 * @param array $plugins
 * @return array Difference betwen the two arrays
 */
function disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, [ 'wpemoji' ] );
	} else {
		return [];
	}
}
add_filter( 'tiny_mce_plugins', __NAMESPACE__ . '\\disable_emojis_tinymce' );

/**
 * Unregister WP Widgets to speeding up WP Admin.
 */
function unregister_wp_widgets() {
	$widgets = array(
		'WP_Widget_Pages',
		'WP_Widget_Calendar',
		'WP_Widget_Archives',
		// Links Manager and its Widget is unregistered already since 3.5.
		// 'WP_Widget_Links',
		'WP_Widget_Meta',
		'WP_Widget_Search',
		'WP_Widget_Text',
		'WP_Widget_Categories',
		'WP_Widget_Recent_Posts',
		'WP_Widget_Recent_Comments',
		'WP_Widget_RSS',
		'WP_Widget_Tag_Cloud',
		'WP_Nav_Menu_Widget'
	);

	foreach ($widgets as $widget ) {
		unregister_widget( $widget );
	}
}
add_action( 'widgets_init', __NAMESPACE__ . '\\unregister_wp_widgets' );
