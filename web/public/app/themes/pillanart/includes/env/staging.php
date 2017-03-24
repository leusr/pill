<?php

/**
 * Only logged in users allowed
 */
function only_logged_in_users() {
    if ( is_user_logged_in() || is_login_page() ) {
        return;
    }

    if ( ! is_domain_root() ) {
        http_response_code( '503' );
        header( 'Location: /' );
        exit;
    } else {
        include __DIR__ . '/itworks.html';
        exit;
    }
}
add_action( 'after_setup_theme', 'only_logged_in_users', 1 );
