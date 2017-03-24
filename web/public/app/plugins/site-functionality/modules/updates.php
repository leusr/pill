<?php

/**
 * Handles various update-related settings and works.
 */
class SFP_Updates {

	public function __construct() {
		// Handle official translations on their own place. ;)
		add_filter( 'auto_update_translation', '__return_false' );

		// Disable some plugin update notices
		// add_filter( 'site_transient_update_plugins', [ $this, 'remove_plugin_update_notifications' ] );

		// Kill update notification
		add_action( 'admin_head', [ $this, 'kill_update_notice' ], 1 );
	}

	/**
	 * Disable plugin update notices on forked plugins and
	 * on custom developed plugins when there is naming conflict with another plugin.
	 *
	 * @param object $value
	 * @return object
	 */
	public function remove_plugin_update_notifications( $value ) {
		if ( isset( $value ) && is_object( $value ) ) {
			unset( $value->response['minify-html-ascii-art/minify-html-ascii-art.php'] );
		}

		return $value;
	}

	/**
	 * Kill update alert message
	 *
	 * Update alert often is false positive, because of some
	 * modification on the translation, or just removing some junk WordPress
	 * files, like readme.html. This function kill that annoying notice.
	 */
	public function kill_update_notice() {
		remove_action( 'admin_notices', 'update_nag', 3 );
	}

}
