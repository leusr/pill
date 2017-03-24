<?php
/*
Plugin Name:   Site Functionality Plugin
Plugin URI:
Description:   Custom functionality plugin for pillanart.hu
Version:       1.5.0
Author:        György Papp
Author URI:
Text Domain:   site-functionality-plugin
Domain Path:   /languages

License:       WTFPL License
License URI:   http://www.wtfpl.net/
*/

// If this file is called directly, abort.
! defined( 'WPINC' ) && die;

if ( ! class_exists( 'SFP' ) ) {
	class SFP {

		/**
		 * Instance of the class
		 * @var object|null
		 */
		private static $instance = null;

		/**
		 * Get instance
		 *
		 * @static
		 * @staticvar object|null $instance
		 * @return object
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof SFP ) ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		/**
		 * SFP constructor.
		 */
		public function __construct() {
			$this->modules();
			add_action( 'plugins_loaded', [ $this, 'load_textdomain' ], 10 );
		}

		/**
		 * Load the plugin text domain for translation.
		 */
		public function load_textdomain() {
			$mofile = plugin_dir_path( __FILE__ ) . 'languages/site-functionality-plugin-' . get_locale() . '.mo';
			if ( is_file( $mofile ) ) {
				load_textdomain( 'site-functionality-plugin', $mofile );
			}
		}

		/**
		 * Load and init modules.
		 */
		private function modules() {
			foreach ( glob( __DIR__ . '/modules/*.php' ) as $file ) {
				include_once $file;
				$classname = $this->get_classname( $file );
				if ( class_exists( $classname ) ) {
					new $classname;
				}
			}
		}

		/**
		 * Get classname from filename.
		 *
		 * Naming conversions:
		 *  - add SFP_ prefix
		 *  - dashes in filename must be underscores in class name
		 *  - since class_exists() and class instantiation is case-insensitive(!) there is freedom about
		 *    uppercased or lowercased words within class names.
		 *    Both SFP_some_THIng {} or
		 *         sfp_SOME_thiNG {} will work.
		 *
		 * E.g.:  some-useful-wp-mod.php => class SFP_Some_Useful_WP_API_Mod {}
		 *
		 * @param $file
		 * @return string
		 */
		private function get_classname( $file ) {
			$classname = 'SFP';
			$words = explode( '-', basename( $file, '.php' ) );
			foreach ( $words as $word ) {
				$classname .= '_' . $word;
			}

			return $classname;
		}

		/** Disable object clone */
		public function __clone() {}

		/** Disable unserializing of the class */
		public function __wakeup() {}

	}
}

/**
 * Return the instance
 *
 * @return object
 */
function SFP_Run() {
	return SFP::get_instance();
}
SFP_Run();
