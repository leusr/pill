<?php

/**
 * Display thumbnails in post lists
 */
class SFP_WP_Admin_Columns {

	public function __construct() {
		add_action( 'admin_init', [ $this, 'add_hooks' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_style' ] );
	}

	public function add_hooks() {
		add_filter( 'manage_posts_columns', [ $this, 'register_columns' ] );
		add_filter( 'manage_pages_columns', [ $this, 'register_columns' ] );
		add_action( 'manage_posts_custom_column', [ $this, 'render_columns' ], 10, 2 );
		add_action( 'manage_pages_custom_column', [ $this, 'render_columns' ], 10, 2 );
	}

	/**
	 * Add some style
	 */
	public function enqueue_style() {
		if ( ! in_array( get_current_screen()->id, [ 'edit-post', 'edit-page', 'edit-member' ] ) ) {
			return;
		}

		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? "" : '.min';

		$src = plugins_url( "assets/thumbnail-col{$min}.css", __DIR__ );
		wp_enqueue_style( 'thumbnails-col', $src );
	}

	/**
	 * Register post columns
	 *
	 * @param array $columns
	 * @return array
	 */
	public function register_columns( $columns ) {
		return  array_merge( $columns, [ 'post-thumbnails' => __( 'Image', 'site-functionality-plugin' ) ] );
	}

	/**
	 * Display columns
	 *
	 * @param string $column Current column slug
	 * @param    int $pid    Current WP_Post ID
	 */
	public function render_columns( $column, $pid ) {
		switch ( $column ) {
			case 'post-thumbnails':
				the_post_thumbnail( 'thumbnail' );
				break;
		}
	}

}
