<?php

/**
 * Theme constants
 */
define( 'THEME_ASSETS_BASEURL', home_url( '/assets/' ) );

class Pillanart {

	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'content_width' ], 0 );
		add_action( 'after_setup_theme', [ $this, 'theme_setup' ], 0 );
		add_action( 'after_setup_theme', [ $this, 'customize_excerpt' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'assets' ] );

		$this->theme_version();
		$this->includes();
	}

	/**
	 * Set the content width in pixels, based on the theme's design and stylesheet.
	 * Priority 0 to make it available to lower priority callbacks.
	 *
	 * @global int $content_width
	 */
	public function content_width() {
		$GLOBALS['content_width'] = apply_filters( 'content_width', 760 );
	}

	/**
	 * Sets up theme defaults and registers support
	 * for various WordPress features.
	 */
	public function theme_setup() {
		// Load theme textdomain
		load_theme_textdomain( 'pillanart-theme', get_template_directory() . '/languages' );

		// Register nav menus
		register_nav_menus( [
				'top'    => __( 'Top navigation line', 'pillanart-theme' ),
				'main'   => __( 'Main navigation', 'pillanart-theme' ),
				'bottom' => __( 'Bottom navigation line', 'pillanart-theme' ),
				'mobile' => __( 'Mobile navigation', 'pillanart-theme' )
		] );

		// Soil modules
		add_theme_support( 'soil-clean-up' );
		add_theme_support( 'soil-filename-asset-versioning' );
		add_theme_support( 'soil-disable-trackbacks' );
		add_theme_support( 'soil-google-analytics', get_option( 'ga_id' ) );
		add_theme_support( 'soil-nice-search' );
		add_theme_support( 'soil-relative-urls' );
		add_theme_support( 'soil-nav-item-walker' );

		// Let WordPress handle document title
		add_theme_support( 'title-tag' );

		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );

		// Switch default core markup to output valid HTML5
		add_theme_support( 'html5',	[ 'search-form', 'caption' ] );

		// Thumbnails
		add_theme_support( 'post-thumbnails' );

		// Custom image sizes
		add_image_size( 'small-3x2', 90, 60, true );
		add_image_size( 'medium-3x2', 260, 173, true );
		add_image_size( 'billboard-4x3', 374, 281, true );
		add_image_size( 'qsxga', 2560, 2048, false );
		add_filter( 'image_size_names_choose', [ $this, 'custom_sizes_to_admin' ] );

		// Support editor style
		add_editor_style();
	}

	/**
	 * Custom sizes to Media Library
	 *
	 * @param array $sizes Default size_slug => Display name pairs...
	 * @return array        ...extended with new sizes.
	 */
	public function custom_sizes_to_admin( $sizes ) {
		return array_merge( $sizes, [
				'small-3x2'     => __( 'Small 3:2', 'pillanart-theme' ),
				'medium-3x2'    => __( 'Medium 3:2', 'pillanart-theme' ),
				'billboard-4x3' => __( 'Billboard 4:3', 'pillanart-theme' ),
				'qsxga'         => __( 'QSXGA', 'pillanart-theme' )
		] );
	}

	/**
	 * Customize the excerpt
	 *
	 * Set expert length to 25 words, and set more sign
	 * to hellips instead of three dots
	 */
	public function customize_excerpt() {
		add_filter( 'excerpt_length', function() { return 25; } );
		add_filter( 'excerpt_more', function() { return '&#8230;'; } );
	}

	/**
	 * Enqueue scripts and styles.
	 * (!) Always enqueue the uncompressed version with dotmin() or assets_min_url() helper.
	 *     Those helpers serve the compressed or original assets depending on
	 *     SCRIPT_DEBUG (and WP_ENV) constant.
	 */
	public function assets() {
		// CSS
		wp_enqueue_style( 'pillanart', assets_min_url( '/css/pillanart.css' ), [], $this->theme_version() );

		// jQuery
		$jquery_url = dotmin( 'http://code.jquery.com/jquery-3.2.1.js' );
		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', $jquery_url, [], null, true );
		add_filter( 'script_loader_src', [ $this, 'jquery_local_fallback' ], 10, 2 );
		wp_enqueue_script( 'jquery' );

		// Scripts
		wp_enqueue_script( 'fotovig', assets_min_url( '/js/pillanart-pkgd.js' ), ['jquery'], $this->theme_version(), true );
	}

	/**
	 * Output the local fallback immediately after jQuery's <script>
	 * @link http://wordpress.stackexchange.com/a/12450
	 *
	 * @param string      $src
	 * @param string|null $handle
	 * @return string
	 */
	public function jquery_local_fallback( $src, $handle = null ) {
		static $add_jquery_fallback = false;

		if ( $add_jquery_fallback ) {
			echo '<script>window.jQuery || document.write(\'<script src="'
			     . $add_jquery_fallback
			     . '"><\/script>\')</script>' . "\n";
			$add_jquery_fallback = false;
		}

		if ( $handle === 'jquery' ) {
			$jquery_local_url = assets_min_url( '/js/lib/jquery.js' );
			$add_jquery_fallback = apply_filters( 'script_loader_src', $jquery_local_url, 'jquery-fallback' );
		}

		return $src;
	}

	/**
	 * Save theme version to database, and return with it.
	 *
	 * @return string
	 */
	public function theme_version() {
		if ( ! $theme_version = get_option( 'theme_version' ) ) {
			// Set up option if not exists
			$theme = wp_get_theme();
			$theme_version = $theme->get( 'Version' );

			// In WP-Universe update_option is the "best practice" of add_option...
			update_option( 'theme_version', $theme_version, true );
		}

		return $theme_version;
	}

	/**
	 * Include theme functions
	 */
	protected function includes() {
		// Hard includes (required)
		$files = [
				'template-tags',              // Custom template tags
				'shortcodes',                 // Custum shortcodes
				'helpers/asset',              // Asset url helpers
				'helpers/html',               // HTML helpers
				'helpers/is_',                // is_(conditional) helpers
				'simple-file-cache',          // Output buffer to file cache
				'real-favicon-generator',     // Re-generate favicons transient and related meta files if theme version changed
				'twitter',                    // Latest tweets
				'redirects'                   // Redirect false-positive 404 pages
		];
		foreach ( $files as $file ) {
			$path = __DIR__ . '/includes/' . $file . '.php';
			require_once $path;
		}

		// Soft includes
		$this->environment_includes();
	}

	/**
	 * Environment-based functions
	 */
	protected function environment_includes() {
		if ( ! defined( 'WP_ENV' ) ) {
			return;
		}

		$path = __DIR__ . '/includes/env/' . WP_ENV . '.php';
		if ( file_exists( $path ) ) {
			include_once $path;
		}
	}
}

/**
 * Ready, steady, GO!
 */
new Pillanart;
