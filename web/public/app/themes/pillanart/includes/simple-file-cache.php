<?php

/**
 * SFC (simple file-based cache) class
 *
 * Goals:
 *     - set cache name only once
 *     - set expire in the same level
 *     - wrap output buffering
 *     - minify output if Compress_HTML available
 *     - apply only to logged out users (by default)
 *
 * Usage:
 *     <?php $sfc = new SFC( 'some-name', HOUR_IN_SECONDS ); if ( $sfc->off ) : ?>
 *
 *     ... Do whatever expensive process...
 *
 *     <?php endif; $sfc->end() ?>
 */
class SFC {

	/**
	 * Ignore expire param.
	 */
	const IGNORE_EXPIRE = false;

	/**
	 * @var bool Need to process output or not?
	 */
	public $off = false;

	/**
	 * @var string Cache directory.
	 */
	private $cache_dir;

	/**
	 * @var string Cache file path.
	 */
	private $file;

	/**
	 * @var int Expire in seconds.
	 */
	private $expire;

	/**
	 * @var bool Enable caching for logged in users?
	 */
	private $logged_in_users;

	/**
	 * @var bool|string The output.
	 */
	private $output = false;

	/**
	 * Start the block.
	 *
	 * @param string $name
	 * @param int    $expire          (optional) default 0 (never)
	 * @param bool   $logged_in_users (optional) Cache allowed for logged in users? default false
	 * @param bool   $regen           (optional) Force cache regeneration? default false
	 */
	public function __construct( $name, $expire = 0, $logged_in_users = false, $regen = false ) {
		// Check logged in users
		$this->logged_in_users = $logged_in_users;
		if ( $this->is_disabled() ) {
			$this->off = true;
			return;
		}

		// Create cache dir if not exists
		$this->cache_dir = WP_CONTENT_DIR . '/cache';
		if ( ! is_dir( $this->cache_dir ) ) {
			mkdir( $this->cache_dir, 0770 );
		}

		$this->file = $this->cache_dir . '/' . md5( $name ) . '.html';
		$this->expire = $expire;

		if ( true === $regen || false === $this->output = $this->get_cache() ) {
			// Cache forced to regeneration, expired or not exists. Start output buffering.
			$this->off = true;
			ob_start();
		}
	}

	/**
	 * End of block.
	 */
	public function end() {
		if ( $this->is_disabled() )
			return;

		if ( false === $this->output ) {
			$this->output = ob_get_clean();
			$this->output = $this->minify_html( $this->output );
			$this->set_cache( $this->output );
		}

		echo $this->output;
	}

	/**
	 * Is caching disabled?
	 *
	 * @return bool
	 */
	private function is_disabled() {
		return ! $this->logged_in_users && is_user_logged_in();
	}

	/**
	 * Minify HTML before store in cache
	 *
	 * @param string $html
	 * @return string
	 */
	private function minify_html( $html ) {
		if ( function_exists( 'minify_html_obcb' ) ) {
			// Minify HTML plugin enabled, use its output buffer callback function
			return minify_html_obcb( $html );
		}

		// Compress_HTML options
		$options = [
				'useNewLines'     => false,
				'isXhtml'         => false,
				'jsCleanComments' => false
		];

		if ( class_exists( 'Compress_HTML' ) ) {
			// Minify HTML plugin disabled, but Compress_HTML class already loaded
			$html = Compress_HTML::minify( $html, $options );
		} else {
			// Try to load Compress_HTML class manually
			$path = realpath( WP_PLUGIN_DIR . '/minify-html-ascii-art/Compress_HTML.php' );
			if ( false !== $path ) {
				include_once $path;
				$html = Compress_HTML::minify( $html, $options );
			}
		}

		return $html;
	}

	/**
	 * Get cache
	 *
	 * @return bool|string Returns false if cache expired or not exists.
	 */
	private function get_cache() {
		if ( self::IGNORE_EXPIRE )
			return false;

		if ( ! is_file( $this->file ) )
			return false;

		if ( 0 !== $this->expire ) {
			if ( filemtime( $this->file ) < time() - $this->expire ) {
				return false;
			}
		}

		return file_get_contents( $this->file );
	}

	/**
	 * Set cache
	 *
	 * @param string $content
	 */
	private function set_cache( $content ) {
		file_put_contents( $this->file, $content );
	}

//	/**
//	 * Del cache
//	 */
//	private function del_cache() {
//		if ( is_file( $this->file ) ) {
//			unlink( $this->file );
//		}
//	}

}
