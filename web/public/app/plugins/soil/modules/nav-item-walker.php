<?php

namespace Roots\Soil\ItemWalker;

use Roots\Soil\Utils;

/*
 * Item_Walker features:
 *     - nice indentation
 *     - creates <span> tags if href is empty or dummy hash
 *     - replace spaces to non-breakable spaces in submenus
 *
 * Use it with additional indent param, wich is the tabs number before the first `<ul>`
 * Eg.:
 *
 *     wp_nav_menu( [ 'theme_location' => 'headernav', 'indent' => 3 ] );
 *
 * You can enable/disable this feature with:
 *
 *      add_theme_support( 'soil-nav-item-walker' );
 *
 */

class Item_Walker extends \Walker_Nav_Menu {

	/**
	 * Default indent tabs number
	 * @var int
	 */
	private $indent;

	/**
	 * Constructor
	 * Walker_Nav_Menu don't have constructor method,
	 * so don't need to call parent::_construct() here.
	 *
	 * @param int $indent
	 */
	public function __construct( $indent = 5 ) {
		$this->indent = $indent;
	}

	public function start_lvl( &$output, $depth = 0, $args = [] ) {
		$indent = str_repeat( "\t", $this->indent + $depth );
		$output .= "\n$indent<ul>\n";
	}

	public function end_lvl( &$output, $depth = 0, $args = [] ) {
		$indent = str_repeat( "\t", $this->indent + $depth );
		$output .= "$indent</ul>";
	}

	public function start_el( &$output, $item, $depth = 0, $args = [], $id = 0 ) {
		$indent = str_repeat( "\t", $this->indent + $depth );

		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		$classes = empty( $item->classes ) ? [] : (array) $item->classes;
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = ( ! empty( $class_names ) ) ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= "$indent<li$class_names>";

		$atts = [];
		! empty( $item->attr_title ) && $atts['title'] = $item->attr_title;
		! empty( $item->target ) && $atts['target'] = $item->target;
		! empty( $item->xfn ) && $atts['rel'] = $item->xfn;
		! empty( $item->url ) && $atts['href'] = $item->url;

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			$value = ( 'href' == $attr ) ? esc_url( $value ) : esc_attr( $value );
			$attributes .= ' ' . $attr . '="' . $value . '"';
		}

		// Don't create links with empty href
		$tag = isset( $atts['href'] ) ? 'a' : 'span';

		// No spaces in submenu please
		if ( $depth ) {
			$item->title = str_replace( ' ', '&nbsp;', $item->title );
		}

		$item_output = $args->before;
		$item_output .= '<' . $tag . $attributes . '>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= "</$tag>";
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
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

	// Hack: make Blog item current on single posts
	$title = apply_filters( 'the_title', $item->title, $item->ID );
	if ( is_single() && $title == 'Blog' ) {
		$classes[] = 'current-menu-single';
	}

	// Filter classes
	return array_intersect( $classes, [
		'current-menu-item',
		'current-menu-parent',
		'current-menu-ancestor',
		'menu-item-has-children',

		// fotovig.hu custom classes
		'current-menu-single',
		'menu-item-clients'
	] );
}
add_filter( 'nav_menu_css_class', __NAMESPACE__ . '\\nav_menu_class_filter', 20, 2 );

/**
 * Clean up wp_nav_menu_args
 *
 * @param array $args
 * @return array
 */
function nav_menu_args( $args = [] ) {
	if ( isset( $args['indent'] ) ) {
		$indent = (int) $args['indent'];
		$indent = $indent < 1 ? 1 : $indent;

		// `indent` is non-official parameter, it need only
		// at constructing Indented_Walker object
		unset( $args['indent'] );
	} else {
		$indent = 1;
	}

	$after = str_repeat( "\t", $indent - 1 );

	$nav_menu_args = [
		'container'  => false,
		'items_wrap' => "\n" . '%3$s' . $after,
		'walker'     => new Item_Walker( $indent )
	];

	return array_merge( $args, $nav_menu_args );
}
add_filter( 'wp_nav_menu_args', __NAMESPACE__ . '\\nav_menu_args' );

/**
 * Filter urls in menus
 *
 * @param array $atts
 * @return array
 */
function nav_menu_link_href( $atts ) {
	if ( ! isset( $atts['href'] ) ) {
		return $atts;
	}

	if ( empty( $atts['href'] ) || '#' == $atts['href'] ) {
		unset( $atts['href'] );
	} else {
		$atts['href'] = Utils\root_relative_url( $atts['href'] );
	}

	return $atts;
}
add_filter( 'nav_menu_link_attributes', __NAMESPACE__ . '\\nav_menu_link_href' );
