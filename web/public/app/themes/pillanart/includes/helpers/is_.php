<?php

/**
 * Is current page the login page?
 * @return bool
 */
function is_login_page() {
	return in_array( $GLOBALS['pagenow'], ['wp-login.php', 'wp-register.php'] );
}

/**
 * is_home() / is_front_page() WordPress-independent replacement.
 * @return bool
 */
function is_domain_root() {
	return '/' === parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
}

/**
 * Is WP_ENV production?
 * @return bool
 */
function is_env_prod() {
	if ( ! defined( 'WP_ENV' ) ) {
		return true;
	}

	return 'production' === WP_ENV;
}

/**
 * Is WP_ENV staging
 * @return bool
 */
function is_env_stag() {
	return defined( 'WP_ENV' ) && 'staging' === WP_ENV;
}

/**
 * Is WP_ENV development?
 * @return bool
 */
function is_env_dev() {
	return defined( 'WP_ENV' ) && 'development' === WP_ENV;
}
