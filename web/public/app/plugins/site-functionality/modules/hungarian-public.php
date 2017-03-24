<?php

/**
 * Add hungarian locale filter on public pages.
 */
class SFP_Hungarian_Public {

	public function __construct() {
		add_action( 'plugins_loaded', [ $this, 'setup_localization' ], 5, 1 );
	}

	public function setup_localization() {
		if ( $this->is_public() ) {
			add_filter( 'locale', [ $this, 'set_public_locale' ] );
		}
	}

	public function set_public_locale( $locale ) {
		return 'hu_HU' == $locale ? $locale : 'hu_HU';
	}

	protected function is_public() {
		return ! $this->is_backend() || $this->is_frontend_ajax();
	}

	protected function is_backend() {
		return is_admin() || $this->is_login_page() || $this->is_tiny_mce();
	}

	protected function is_login_page() {
		return in_array( $GLOBALS['pagenow'], ['wp-login.php', 'wp-register.php'] );
	}

	protected function is_tiny_mce() {
		return false !== strpos( $_SERVER['REQUEST_URI'], '/wp-includes/js/tinymce/' );
	}

	protected function is_frontend_ajax() {
		return defined( 'DOING_AJAX' ) && DOING_AJAX && false === strpos( wp_get_referer(), '/wp-admin/' );
	}

}
