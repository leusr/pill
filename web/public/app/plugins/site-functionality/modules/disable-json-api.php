<?php

/**
 * Disable the new shiny JSON REST API
 *
 * @since WordPress 4.4
 */
class SFP_Disable_JSON_API {

	public function __construct() {
		// Filters for WP-API version 1.x
		add_filter( 'json_enabled', '__return_false' );
		add_filter( 'json_jsonp_enabled', '__return_false' );

		// Filters for WP-API version 2.x
		add_filter( 'rest_enabled', '__return_false' );
		add_filter( 'rest_jsonp_enabled', '__return_false' );
	}

}
