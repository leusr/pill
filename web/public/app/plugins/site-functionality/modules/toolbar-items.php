<?php

/**
 * Remove annoying / unused items from Toolbar (formerly known as WP Admin Bar).
 */
class SFP_Toolbar_Items {

	public function __construct() {
		add_action( 'admin_bar_menu',[ $this, 'remove_toolbar_items' ], 99 );
	}

	/**
	 * Remove unused items from Toolbar.
	 * @param WP_Admin_Bar $bar
	 */
	public function remove_toolbar_items( $bar ) {
		$bar->remove_node( 'wp-logo' );    // Logo is great, but the subitems are sucks.
		$bar->remove_node( 'customize' );  // This site is well done customized.
		$bar->remove_node( 'comments' );   // No comment allowing on this site.
	}

}