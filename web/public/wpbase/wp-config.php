<?php

require dirname( __FILE__ ) . '/../.conf/common.php';

/** Only ABSPATH remained here. */
if ( ! defined('ABSPATH') ) {
	define('ABSPATH', dirname(__FILE__) . '/');
}

require_once ABSPATH . 'wp-settings.php';
