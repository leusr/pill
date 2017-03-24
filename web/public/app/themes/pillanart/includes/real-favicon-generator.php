<?php

/*
    Notes:

     - Internet Explorer looks for favicon.ico at the root of the web site.
       Granted: this is because we ask you to not declare favicon.ico.

     - iOS devices look for files such as apple-touch-icon-144x144.png at the root
       of the web site, as described by Apple. This issue can be mitigated by declaring
       the icons in the HTML code (this is necessary for Android anyway), but following
       Apple conventions is probably the best move.

     - By default, Internet Explorer 11 looks for browserconfig.xml at the root of the web site.

     - Several services, such as Yandex, look for favicon.ico in the root directory.
*/

class Real_Favicon_Generator {
	/**
	 * Icons dir relative to THEME_ASSETS_BASEURL
	 * @var string
	 */
	private $dir = 'icon';

	/**
	 * Url base.
	 * @var string
	 */
	private $url_base;

	/**
	 * Holds the current theme version.
	 * @var string
	 */
	private $theme_version;

	/**
	 * Icons to display in html head section.
	 * @var array
	 */
	private $link_icons = [
		'apple-touch-icon-57x57.png',
		'apple-touch-icon-60x60.png',
		'apple-touch-icon-72x72.png',
		'apple-touch-icon-76x76.png',
		'apple-touch-icon-114x114.png',
		'apple-touch-icon-120x120.png',
		'apple-touch-icon-144x144.png',
		'apple-touch-icon-152x152.png',
		'apple-touch-icon-180x180.png',
		'favicon-16x16.png',
		'favicon-32x32.png',
		'favicon-96x96.png',
		'favicon-194x194.png',
		'android-chrome-192x192.png',
	    'mstile-144x144.png'
	];

	/**
	 * Icons for Windows OS tiles, placed in browserconfig.xml.
	 * @var array
	 */
	private $tile_icons = [
		'mstile-70x70.png',
		'mstile-150x150.png',
		'mstile-310x310.png',
		'mstile-310x150.png'
	];

	/**
	 * Windows 8/10 tile background color.
	 * @var string
	 */
	private $tile_color = '#000000';

	/**
	 * Icons for Android OS, placed in manifest.json.
	 * @var array
	 */
	private $android_icons = [
		'0.75' => 'android-chrome-36x36.png',
	    '1.0'  => 'android-chrome-48x48.png',
		'1.5'  => 'android-chrome-72x72.png',
		'2.0'  => 'android-chrome-96x96.png',
		'3.0'  => 'android-chrome-144x144.png',
		'4.0'  => 'android-chrome-192x192.png'
	];

	/**
	 * Starting with Android Lollipop, you can customize
	 * the color of the task bar in the switcher.
	 * @var string
	 */
	private $droid_theme_color = '#ffffff';

	/**
	 * The current icons which is under process.
	 * @var string
	 */
	private $icon;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$theme = wp_get_theme();
		$this->theme_version = $theme->get( 'Version' );
		$this->url_base = parse_url( THEME_ASSETS_BASEURL, PHP_URL_PATH ) . $this->dir;

		$sfc = new SFC( 'favicons', DAY_IN_SECONDS, true, $this->is_theme_changed() ); if ( $sfc->off ) {
			$this->create_html();
			$this->create_manifest_json();
			$this->create_browserconfig_xml();
			update_option( 'theme_prev_version', $this->theme_version, true );
		}
		$sfc->end();
	}

	/**
	 * Check theme version changes.
	 *
	 * @return bool
	 */
	private function is_theme_changed() {
		return $this->theme_version !== get_option( 'theme_prev_version' );
	}

	/**
	 * Generate html into head section, and save it to cache.
	 */
	public function create_html() {
		foreach ( $this->link_icons as $icon ) {
			$this->icon = $icon;

			if ( $this->is_ms() ) {
				echo '<meta name="msapplication-TileImage" content="' . $this->get_url() . '">' . "\n";
			} else {
				printf( "<link%s%s%s href=\"%s\">\n",
					$this->get_rel_param(),
					$this->get_type_param(),
					$this->get_sizes_param(),
					$this->get_url()
				);
			}
		}

		?>
<meta name="msapplication-TileColor" content="<?php echo $this->tile_color ?>">
<meta name="theme-color" content="<?php echo $this->droid_theme_color ?>">
<link rel="mask-icon" href="<?php echo $this->url_base ?>/safari-pinned-tab.svg" color="<?php echo $this->tile_color ?>">
<link rel="manifest" href="/manifest.json">
<?php
	}

	/**
	 * Get link rel parameter.
	 *
	 * @return string
	 */
	private function get_rel_param() {
		$rel = $this->get_rel();

		return empty( $rel ) ? "" : " rel=\"$rel\"";
	}

	/**
	 * Get value of rel.
	 *
	 * @return string
	 */
	private function get_rel() {
		if ( $this->is_apple() ) {
			return 'apple-touch-icon';
		}

		return 'icon';
	}

	/**
	 * Get link type parameter.
	 *
	 * @return string
	 */
	private function get_type_param() {
		$type = $this->get_type();

		return empty( $type ) ? "" : " type=\"$type\"";
	}

	/**
	 * Get value of icon type.
	 *
	 * @return string
	 */
	private function get_type() {
		if ( $this->is_apple() ) {
			return "";
		}

		switch ( pathinfo( $this->icon, PATHINFO_EXTENSION ) ) {
			case 'png':
				return 'image/png';
			case 'jpg':
			case 'jpeg':
				return 'image/jpeg';
			case 'gif':
				return 'image/gif';
			case 'ico':
				return 'image/x-icon';
			default:
				return "";
		}
	}

	/**
	 * Get link sizes parameter.
	 *
	 * @return string
	 */
	private function get_sizes_param() {
		$size = $this->get_sizes();

		return " sizes=\"$size\"";
	}

	/**
	 * Get sizes value(s).
	 *
	 * @param string $format Default format is `string`, returns with sizes in WWWxHHH format.
	 *                       Alternate format is `array` (actually can be anything other than string),
	 *                       returns with array filled by the width and height values as integer.
	 * @return string|array
	 */
	private function get_sizes( $format = 'string' ) {
		if ( 'string' === $format ) {
			preg_match( '/^\D+(\d+x\d+)\.(png|gif|ico|jpe?g)$/', $this->icon, $matches );
			return $matches[1];
		}

		preg_match( '/^\D+(\d+)x(\d+)\.(png|gif|ico|jpe?g)$/', $this->icon, $matches );
		return [ (int) $matches[1], (int) $matches[2] ];
	}

	/**
	 * Get icon url.
	 * Apple's 144x144 sized icon stands in the root, others in their own directory.
	 *
	 * @return string
	 */
	private function get_url() {
		return '144x144' == $this->get_sizes() && $this->is_apple() ?
			"/$this->icon" : "$this->url_base/$this->icon";
	}

	/**
	 * Check if the icon related to Apple.
	 *
	 * @return string|false
	 */
	private function is_apple() {
		return strstr( $this->icon, 'apple' );
	}

	/**
	 * Check if the icon belongs to Windows.
	 *
	 * @return string|false
	 */
	private function is_ms() {
		return strstr( $this->icon, 'mstile' );
	}

	/**
	 * Generate manifest.json for Android devices.
	 */
	private function create_manifest_json() {
		$icons = [];

		foreach ( $this->android_icons as $density => $icon ) {
			$this->icon = $icon;

			$icons[] = [
				'src'     => $this->get_url(),
				'sizes'   => $this->get_sizes(),
				'type'    => $this->get_type(),
				'density' => $density
			];
		}

		$manifest = [
			'name'  => get_bloginfo( 'name' ),
		    'icons' => $icons
		];

		file_put_contents( dirname( ABSPATH ) . '/manifest.json', json_encode( $manifest, JSON_PRETTY_PRINT ) );
	}

	/**
	 * Generate browserconfig.xml for Windows devices.
	 */
	private function create_browserconfig_xml() {
		$tiles = "";

		foreach ( $this->tile_icons as $icon ) {
			$this->icon = $icon;

			$sizes = $this->get_sizes( 'array' );

			$name  = $sizes[0] === $sizes[1] ? 'square' : 'wide';
			$name .= $this->get_sizes() . 'logo';

			$tiles .= "      <$name src=\"" . $this->get_url() . "\"/>\n";
		}

		$tiles .= "      <TileColor>$this->tile_color</TileColor>";

		$xml = <<<HERE
<?xml version="1.0" encoding="utf-8"?>
<browserconfig>
  <msapplication>
    <tile>
%s
    </tile>
  </msapplication>
</browserconfig>
HERE;

		file_put_contents( dirname( ABSPATH ) . '/browserconfig.xml', sprintf( $xml, $tiles ) );
	}
}

/**
 * Attach class to wp_head.
 */
function real_favicon_generator() {
	new Real_Favicon_Generator;
}
add_action( 'wp_head', 'real_favicon_generator', 25 );
