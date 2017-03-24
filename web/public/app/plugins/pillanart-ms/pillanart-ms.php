<?php
/*
Plugin Name:   Pillana(r)t Members and SEO
Plugin URI:
Description:   Handles members post type, member-related meta boxes and search engine optimization.
Version:       2.0.0
Author:        György Papp
Author URI:
Text Domain:   pillanart-ms
Domain Path:   /languages
*/

// If this file is called directly, abort.
! defined( 'WPINC' ) and exit;

// Includes
require_once  plugin_dir_path( __FILE__ ) . 'includes/aiof-seo.class.php';
require_once  plugin_dir_path( __FILE__ ) . 'includes/pillanart-ms.class.php';

// Get instance of plugin object(s)
add_action( 'plugins_loaded', [ 'Pillanart_MS', 'get_instance' ] );
add_action( 'plugins_loaded', [ 'AIOF_Seo', 'get_instance' ] );

// Dashboard and Administrative Functionality
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
	// Admin-only includes
	require_once  plugin_dir_path( __FILE__ ) . 'includes/form-helper.class.php';
	require_once  plugin_dir_path( __FILE__ ) . 'includes/pillanart-ms-admin.class.php';

	// Get instance of admin-related object
	add_action( 'plugins_loaded', [ 'Pillanart_MS_Admin', 'get_instance' ] );
}