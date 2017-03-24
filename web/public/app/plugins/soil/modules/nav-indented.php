<?php

namespace Roots\Soil\NavIndented;

/*
 * Indented Walker: the original Walker with nice default indentation.
 * Use it with additional indent param, wich is the tabs number before the first `<ul>`
 * Eg.:
 *
 *     wp_nav_menu( array('theme_location' => 'headernav', 'indent' => 3) );
 *
 * You can enable/disable this feature with:
 *
 *      add_theme_support( 'soil-nav-indented' );
 *
 */

class Indented_Walker extends \Walker_Nav_Menu {

	/**
	 * Default indent tabs number
	 * @var int
	 */
	private $indent;

	/**
	 * Constructor
	 *
	 * Walker_Nav_Menu don't have constructor method,
	 * so don't need to call parent::_construct() here.
	 *
	 * @param int $indent
	 */
	public function __construct( $indent = 5 ) {
		$this->indent = $indent;
	}

	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $this->indent + $depth );
		$output .= "\n$indent<ul>\n";
	}

	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $this->indent + $depth );
		$output .= "$indent</ul>";
	}

	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = str_repeat( "\t", $this->indent + $depth );

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = ( ! empty( $class_names ) ) ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= "$indent<li$class_names>";

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
		$atts['href']   = ! empty( $item->url ) ? $item->url : '';

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$item_output = $args->before;
		$item_output .= '<a' . $attributes . '>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

/**
 * Nav menu class filter
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

	return array_intersect( $classes, [
		'current-menu-item',
		'current-menu-parent',
		'current-menu-ancestor',
		'menu-item-has-children'
	] );
}
add_filter( 'nav_menu_css_class', __NAMESPACE__ . '\\nav_menu_class_filter', 20, 2 );

/**
 * Clean up wp_nav_menu_args
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
		'walker'     => new Indented_Walker( $indent )
	];

	return array_merge( $args, $nav_menu_args );
}
add_filter( 'wp_nav_menu_args', __NAMESPACE__ . '\\nav_menu_args' );
