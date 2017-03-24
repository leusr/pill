<?php

/**
 * Redirect old links
 * (if WordPress does not yet)
 */
add_action( 'template_redirect', 'redirect_old_links' );

function redirect_old_links() {
	if ( ! is_404() ) {
		return;
	}

	$req = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );

	// Old post links `/magazin/post-slug`
	if ( preg_match( '#^/magazin/([\w-\.]+)$#', $req, $matches ) ) {
		global $wpdb;
		$post = $wpdb->get_row( "SELECT * FROM $wpdb->posts WHERE post_name = '" . $matches[1] . "'" );
		if ( is_object( $post ) && $post->post_status == 'publish' ) {
			wp_redirect( get_permalink( $post->ID ), 301 );
		}
	}

	// Old category links `/magazin/rovat/category-slug`
	if ( preg_match( '#^/magazin/rovat/([\w-\.]+)$#', $req, $matches ) ) {
		wp_redirect( home_url( '/' . $matches[1] ), 301 );
	}

	// Old tag links `/magazin/cimke/tag-slug`
	if ( preg_match( '#^/magazin/cimke/([\w-\.]+)$#', $req, $matches ) ) {
		wp_redirect( home_url( '/tag/' . $matches[1] ), 301 );
	}
}


/**
 * Stop guessing URLs, I know what I do, fck!
 */
add_filter( 'redirect_canonical', 'no_redirect_on_404' );

function no_redirect_on_404( $redirect_url ) {
	if ( is_404() ) {
		return false;
	}

	return $redirect_url;
}
