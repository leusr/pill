<?php

define( 'WP_USE_THEMES', false );
define( 'WPLANG', 'hu_HU' );

// Load WordPress Bootstrap
require_once __DIR__ . '/../../../wp/wp-load.php';

if ( class_exists( 'Swift_contact_form' ) && isset( $_POST['form_slug'] ) ) {
	new Swift_contact_form;
}
