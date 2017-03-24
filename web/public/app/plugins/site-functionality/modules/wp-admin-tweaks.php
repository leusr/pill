<?php

/**
 * Add more comfort with removing some... as they called: "feature".
 */
class SFP_WP_Admin_Tweaks {

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'remove_admin_menus' ] );
		add_action( 'admin_head', [ $this, 'kill_update_notice' ], 1 );
		add_filter( 'user_contactmethods', [ $this, 'hide_instant_messaging' ], 10, 1 );
	}

	/**
	 * Remove items from WordPress admin menu.
	 * @link http://codex.wordpress.org/Function_Reference/remove_menu_page#Examples
	 */
	public function remove_admin_menus() {
		remove_menu_page( 'edit-comments.php' );
	}

	/**
	 * Kill update alert message.
	 *
	 * Update alert often is false positive, because of some
	 * modification on the translation, or just removing some junk WordPress
	 * files, like readme.html. This function kill that annoying notice.
	 */
	public function kill_update_notice() {
		remove_action( 'admin_notices', 'update_nag', 3 );
	}

	/**
	 * Disable some useless fields on user profile page.
	 * @param array $fields
	 * @return array
	 */
	public function hide_instant_messaging( $fields ) {
		unset( $fields['aim'] );
		unset( $fields['yim'] );
		unset( $fields['jabber'] );

		return $fields;
	}

}
