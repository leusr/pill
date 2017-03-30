<?php

class Pillanart_MS {

	/**
	 * Instance of this class.
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Return an instance of this class.
	 *
	 * @static
	 * @staticvar object|null $instance
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Pillanart_MS ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Initialize the plugin by setting localization and set up actions and filters
	 */
	public function __construct() {
		add_action( 'plugins_loaded', [ $this, 'load_plugin_textdomain' ], 15, 0 );
		add_action( 'init', [ $this, 'register_custom_types' ] );
	}

	/**
	 * Load the plugin text domain for translation.
	 */
	public function load_plugin_textdomain() {
		$domain = 'pillanart-ms';
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
		load_textdomain( $domain, plugin_dir_path( __FILE__ ) . 'languages/' . $locale . '.mo' );
	}

    /**
     * Register custom post types & taxonomies
     *
     * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 * @link http://codex.wordpress.org/Function_Reference/register_taxonomy
     */
    public function register_custom_types() {
		// Members
        register_post_type( 'member', [
            'labels' => [
                'name'               => _x( 'Members', 'post type general name', 'pillanart-ms' ),
                'singular_name'      => _x( 'Member', 'post type singular name', 'pillanart-ms' ),
                'menu_name'          => _x( 'Members', 'admin menu', 'pillanart-ms' ),
                'name_admin_bar'     => _x( 'Member', 'add new on admin bar', 'pillanart-ms' ),
                'add_new'            => _x( 'Add New', 'member', 'pillanart-ms' ),
                'add_new_item'       => __( 'Add New Member', 'pillanart-ms' ),
                'new_item'           => __( 'New Member', 'pillanart-ms' ),
                'edit_item'          => __( 'Edit Member', 'pillanart-ms' ),
                'view_item'          => __( 'View Member', 'pillanart-ms' ),
                'all_items'          => __( 'All Members', 'pillanart-ms' ),
                'search_items'       => __( 'Search Members', 'pillanart-ms' ),
                'not_found'          => __( 'No member found.', 'pillanart-ms' ),
                'not_found_in_trash' => __( 'No member found in Trash.', 'pillanart-ms' )
            ],
            'public'            => true,
            'show_in_nav_menus' => true,
            'menu_position'     => 7,
            'menu_icon'         => 'dashicons-camera',
            'supports'          => [ 'title', 'editor', 'author', 'thumbnail' ],
            'has_archive'       => true,
            'rewrite'           => [
	            'slug'       => 'eskuvoi-fotosok',
	            'with_front' => false,
	            'feeds'      => false,
	            'pages'      => false
            ]
        ] );

//		// Partners
//	    register_post_type( 'partner', [
//		    'labels' => [
//			    'name'               => _x( 'Partners', 'post type general name', 'pillanart-ms' ),
//			    'singular_name'      => _x( 'Partner', 'post type singular name', 'pillanart-ms' ),
//			    'menu_name'          => _x( 'Partners', 'admin menu', 'pillanart-ms' ),
//			    'name_admin_bar'     => _x( 'Partner', 'add new on admin bar', 'pillanart-ms' ),
//			    'add_new'            => _x( 'Add New', 'partner', 'pillanart-ms' ),
//			    'add_new_item'       => __( 'Add New Partner', 'pillanart-ms' ),
//			    'new_item'           => __( 'New Partner', 'pillanart-ms' ),
//			    'edit_item'          => __( 'Edit Partner', 'pillanart-ms' ),
//			    'view_item'          => __( 'View Partner', 'pillanart-ms' ),
//			    'all_items'          => __( 'All Partners', 'pillanart-ms' ),
//			    'search_items'       => __( 'Search Partners', 'pillanart-ms' ),
//			    'not_found'          => __( 'No partner found.', 'pillanart-ms' ),
//			    'not_found_in_trash' => __( 'No partner found in Trash.', 'pillanart-ms' )
//		    ],
//		    'public'            => true,
//		    'show_in_nav_menus' => true,
//		    'menu_position'     => 7,
//		    'menu_icon'         => 'dashicons-groups',
//		    'supports'          => [ 'title', 'editor', 'author', 'thumbnail' ],
//		    'taxonomies'        => [ 'service' ],
//		    'has_archive'       => true,
//		    'rewrite'           => [
//			    'slug'       => 'oket-ajanljuk',
//			    'with_front' => false,
//			    'feeds'      => false,
//			    'pages'      => false
//		    ]
//	    ] );

//		// Services of Partners
//		register_taxonomy( 'service', 'partner', [
//			'labels' => [
//				'name'              => _x( 'Services', 'taxonomy general name', 'pillanart-ms' ),
//				'singular_name'     => _x( 'Service', 'taxonomy singular name', 'pillanart-ms' ),
//				'all_items'         => __( 'All Services', 'pillanart-ms' ),
//				'parent_item'       => __( 'Parent Service', 'pillanart-ms' ),
//				'parent_item_colon' => __( 'Parent Service:', 'pillanart-ms' ),
//				'edit_item'         => __( 'Edit Service', 'pillanart-ms' ),
//				'update_item'       => __( 'Update Service', 'pillanart-ms' ),
//				'add_new_item'      => __( 'Add New Service', 'pillanart-ms' ),
//				'new_item_name'     => __( 'New Service Name', 'pillanart-ms' ),
//				'menu_name'         => __( 'Service', 'pillanart-ms' ),
//				'search_items'      => __( 'Search Service', 'pillanart-ms' ),
//				'not_found'         => __( 'No service found.', 'pillanart-ms' )
//			],
//			'hierarchical'      => true,
//			'show_ui'           => true,
//			'show_admin_column' => true,
//			'query_var'         => true,
//			'rewrite'           => [ 'slug' => 'szolgaltatas' ]
//		] );
    }

}
