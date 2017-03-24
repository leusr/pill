<?php

namespace Roots\Soil\NavUglify;

/*
 * Uglify Walker
 *
 * Removes every whitespace and classes from `ul` tags, and don't support any
 * before/after param (wich are empty by default). For `li` tags I left the
 * classes here, but I used to filter them on `nav_menu_css_class` hook.
 *
 * If you use this feature, in wp_nav_menu args you need to define only the
 * theme_location param. E.g.:
 *
 *      wp_nav_menu( ['theme_location' => 'headerbar'] );
 *
 * You can enable/disable this feature with:
 *
 *      add_theme_support( 'soil-nav-uglify' );
 *
 */

class Uglify_Walker extends \Walker_Nav_Menu {

	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$output .= '<ul>';
	}

	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		$output .= '</ul>';
	}

	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= '<li' . $class_names . '>';

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
		$atts['href']   = ! empty( $item->url ) ? $item->url : '';

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$item_output = '<a' . $attributes . '>';
		$item_output .= apply_filters( 'the_title', $item->title, $item->ID );
		$item_output .= '</a>';

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		$output .= '</li>';
	}
}

/**
 * Nav menu class filter
 *
 * @param array    $classes
 * @param \WP_Post $item
 * @return array
 */
function nav_menu_class_filter( $classes, $item ) {
	// Hack: add ancestor to custom post type
	// In WP Menus add a menu class named: {post_type}-menu-item
	$post_type = get_query_var( 'post_type' );
	if( in_array( $post_type . '-menu-item', $classes ) ) {
		array_push( $classes, 'current-menu-ancestor' );
	}

	return array_intersect( $classes, array(
		'current-menu-item',
		'current-menu-parent',
		'current-menu-ancestor',
		'menu-item-has-children'
	) );
}
add_filter( 'nav_menu_css_class', __NAMESPACE__ . '\\nav_menu_class_filter', 10, 2 );

/**
 * Clean up wp_nav_menu_args
 *
 * @param array $args
 * @return array
 */
function nav_menu_args( $args = [] ) {
	if ( isset( $args['indent'] ) ) {
		unset( $args['indent'] );
	}

	$nav_menu_args = array(
		'container'  => false,
		'items_wrap' => '%3$s',
		'walker'     => new Uglify_Walker
	);

	return array_merge( $args, $nav_menu_args );
}
add_filter( 'wp_nav_menu_args', __NAMESPACE__ . '\\nav_menu_args' );
