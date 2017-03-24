<?php

/**
 * Show some debug data under development
 */
function queries_timer_info() {
	?>
		<div class="performance">
			<p>
				<strong><?php echo get_num_queries(); ?></strong> Q /
				<strong><?php echo timer_stop( 0, 3 ); ?></strong> s
				<span title="<?php echo $_SERVER['HTTP_USER_AGENT']; ?>">UA</span>
			</p>
		</div>
	<?php
}
add_action( 'wp_footer', 'queries_timer_info', 99 );

/**
 * Insert livereload script to footer.
 */
function grunt_livereload() {
	?>
		<script><?php echo file_get_contents( assets_path( 'js/livereload.min.js' ) ); ?></script>
	<?php
}
// add_action( 'wp_footer', 'grunt_livereload', 20 );


if ( ! function_exists( 'prc_locale' ) ) {
	/**
	 * Print current locale
	 */
	function prc_locale() {
		prc( get_locale() );
    }
}
// add_action( 'wp_footer', 'prc_locale', 9990 );


if ( ! class_exists( 'Trace_actions_filters' ) ) {
	/**
	 * Trace actions and filters
	 */
	class Trace_actions_filters {

		static $hooks;

		static function track_hooks() {
			$filter = current_filter();
			if ( ! empty( $GLOBALS['wp_filter'][ $filter ] ) ) {
				foreach ( $GLOBALS['wp_filter'][ $filter ] as $priority => $tag_hooks ) {
					foreach ( $tag_hooks as $hook ) {
						if ( is_array( $hook['function'] ) ) {
							if ( is_object( $hook['function'][0] ) ) {
								$func = get_class( $hook['function'][0] ) . '->'
								        . $hook['function'][1];
							} elseif ( is_string( $hook['function'][0] ) ) {
								$func = $hook['function'][0] . '::' . $hook['function'][1];
							}
						} elseif ( $hook['function'] instanceof Closure ) {
							$func = 'a closure';
						} elseif ( is_string( $hook['function'] ) ) {
							$func = $hook['function'];
						}
						self::$hooks[] = 'On hook  ' . str_pad( $filter, 30 )
						                 . ' run  ' . str_pad( $func, 30 )
						                 . ' at priority  ' . $priority;
					}
				}
			}
		}

	}
}

if ( ! function_exists( 'prc_actions_filters' ) ) {
	/**
	 * Print trace result
	 */
	function prc_actions_filters() {
		echo "\n\n<!--\n\tTrace actions and filters:\n\t"
		     . implode( "\n\t", Trace_actions_filters::$hooks )
		     . "\n-->\n\n";
	}
}
// add_action( 'all', array( 'Trace_actions_filters', 'track_hooks' ) );
// add_action( 'shutdown', 'prc_actions_filters', 9999 );


if ( ! function_exists( 'prc_rewrite_rules' ) ) {
	/**
	 * Print rewrite rules
	 */
	function prc_rewrite_rules() {
		global $wp_rewrite;

		prc($wp_rewrite);
	}
}
// add_action( 'shutdown', 'prc_rewrite_rules' );
