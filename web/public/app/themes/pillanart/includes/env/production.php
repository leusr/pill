<?php

/**
 * Log 404 error
 *
 * @param boolean $sendmail Send alert mail or not (default false)
 */
function log_404_error( $sendmail = false ) {
	if ( ! is_404() ) {
		return;
	}

	$file = WP_CONTENT_DIR . '/404.log';
	if ( ! is_file( $file ) ) {
		file_put_contents( $file, '' );
	}

	$referer = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : '-';
	$log = date( 'Y-m-d H:i:s' ) . ' | '
	       . home_url( $_SERVER['REQUEST_URI'] ) . ' | '
	       . $referer . ' | '
	       . $_SERVER['HTTP_USER_AGENT'] . ' | '
	       . $_SERVER['REMOTE_ADDR'] . "\n";

	file_put_contents( $file, $log, FILE_APPEND | LOCK_EX );

	if ( true === $sendmail ) {
		// Get blog admin email
		$to = array( get_option( 'admin_email' ) );
		// Add administrator emails
		$admins = get_users( array( 'role' => 'administrator' ) );
		foreach ( $admins as $admin ) {
			$to[] = $admin->user_email;
		}
		// Remove duplicates (blog's admin email and an administrator's email are maybe the same)
		$to = array_unique( $to );
		$subject = get_bloginfo( 'name' ) . __( ' - Error 404 on the Website', 'pillanart-plugin' );
		$msg = 'Request: ' . home_url( $_SERVER['REQUEST_URI'] ) . "\n"
		       . __( 'Date' ) . ': ' . date( 'Y-m-d H:i:s' ) . "\n"
		       . 'Referer: ' . $referer . "\n"
		       . 'UAgent:  ' . $_SERVER['HTTP_USER_AGENT'] . "\n"
		       . 'IP:      ' . $_SERVER['REMOTE_ADDR'] . "\n"
		       . '--';

		wp_mail( $to, $subject, $msg );
	}
}
add_action( 'shutdown', 'log_404_error' );
