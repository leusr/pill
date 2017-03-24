<?php

/*
Plugin Name:   Swift Contact Form
Description:   Fast and secure SPAM protected contact form with HTML and text emails.
Version:       1.1.0
Author:        Gyorgy Papp
Text Domain:   swift-contact-form
Domain Path:   /languages
*/

// If this file is called directly, abort.
! defined( 'WPINC' ) and exit;

// Load plugin class
require_once plugin_dir_path( __FILE__ ) . 'swift-contact-form.class.php';

// (!) Class instantiation is at the theme.