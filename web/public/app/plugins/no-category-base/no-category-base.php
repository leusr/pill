<?php
/*
Plugin Name: No Category Base
Description: Removes '/category' from your category permalinks.
Version: 1.2.0
Author: iDope
Author URI: http://efextra.com/
*/

/*
Copyright 2008, Saurabh Gupta <saurabh0@gmail.com>

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Refresh rules on activation/deactivation/category changes
 */
register_activation_hook( __FILE__, 'no_category_base_refresh_rules' );

add_action( 'created_category', 'no_category_base_refresh_rules' );
add_action( 'edited_category', 'no_category_base_refresh_rules' );
add_action( 'delete_category', 'no_category_base_refresh_rules' );

function no_category_base_refresh_rules() {
	global $wp_rewrite;
	$wp_rewrite->flush_rules();
}

register_deactivation_hook( __FILE__, 'no_category_base_deactivate' );

function no_category_base_deactivate() {
	remove_filter( 'category_rewrite_rules', 'no_category_base_rewrite_rules' );
	// We don't want to insert our custom rules again
	no_category_base_refresh_rules();
}

/**
 * Remove category base
 */
add_action( 'init', 'no_category_base_permastruct' );

function no_category_base_permastruct() {
	global $wp_rewrite;
	$wp_rewrite->extra_permastructs['category']['struct'] = '%category%';
}

/**
 * Add our custom category rewrite rules
 */
add_filter( 'category_rewrite_rules', 'no_category_base_rewrite_rules' );

function no_category_base_rewrite_rules( $category_rewrite ) {
	$category_rewrite = array();
	$categories = get_categories( array( 'hide_empty' => false ) );
	foreach ( $categories as $category ) {
		$category_nicename = $category->slug;
		if ( $category->parent === $category->cat_ID ) { // Recursive Recursion
			$category->parent = 0;
		} elseif ( $category->parent != 0 ) {
			$category_nicename = get_category_parents( $category->parent, false, '/', true ) . $category_nicename;
		}
		$category_rewrite[ '(' . $category_nicename . ')/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$' ]
			= 'index.php?category_name=$matches[1]&feed=$matches[2]';
		$category_rewrite[ '(' . $category_nicename . ')/page/?([0-9]{1,})/?$' ]
			= 'index.php?category_name=$matches[1]&paged=$matches[2]';
		$category_rewrite[ '(' . $category_nicename . ')/?$' ]
			= 'index.php?category_name=$matches[1]';
	}

	// Redirect support from Old Category Base
	$old_category_base = get_option( 'category_base' ) ? get_option( 'category_base' ) : 'category';
	$old_category_base = trim( $old_category_base, '/' );
	$category_rewrite[ $old_category_base . '/(.*)$' ] = 'index.php?category_redirect=$matches[1]';

	return $category_rewrite;
}

/* For Debugging *
add_filter( 'rewrite_rules_array', 'no_category_base_rewrite_rules_array' );
function no_category_base_rewrite_rules_array( $category_rewrite ) {
	prc( $category_rewrite );
} /**/

/**
 * Add 'category_redirect' query variable
 */
add_filter( 'query_vars', 'no_category_base_query_vars' );

function no_category_base_query_vars( $public_query_vars ) {
	$public_query_vars[] = 'category_redirect';

	return $public_query_vars;
}

/**
 * Redirect if 'category_redirect' is set
 */
add_filter( 'request', 'no_category_base_request' );

function no_category_base_request( $query_vars ) {
	if ( isset( $query_vars['category_redirect'] ) ) {
		$catlink = trailingslashit( get_option( 'home' ) )
		           . user_trailingslashit( $query_vars['category_redirect'], 'category' );
		status_header( 301 );
		header( "Location: $catlink" );
		exit;
	}

	return $query_vars;
}
