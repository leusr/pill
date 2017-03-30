<?php

/**
 * All in One File SEO Class
 *
 * @package    Pillanart Site Plugin
 * @author     Gyorgy Papp <gyorgy.pap@gmail.com>
 * @link       http://clearpixel.hu
 * @copyright  I am serious. I'll cut your arms. And legs.
 *             You are not allowed read this! I'll push your eyes into your brain!
 * @license    WTFPL
 */

class AIOF_Seo {

	/**
	 * Instance of this class.
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * The computed site title.
	 * @var string
	 */
	private $title;

	/**
	 *  Constructor
	 */
	public function __construct() {
		$this->attach_hooks();
	}

	private function attach_hooks() {
		add_filter( 'wp_title', [ $this, 'pillanart_title' ], 10, 2 );
		add_action( 'wp_head', [ $this, 'pillanart_meta_tags' ], 5 );
	}

	/**
	 * Return an instance of this class
	 *
	 * @return object A single instance of this class
	 */
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Get head_title custom field or normal wp_title
	 * Also fill up $title var
	 *
	 * @param string $title Default title text for current view.
	 * @param string $sep   Optional separator.
	 * @return string Filtered title
	 */
	public function pillanart_title( $title, $sep = '&lsaquo;' ) {
		$this->title = $this->get_pillanart_title( $title, $sep );

		return $this->title;
	}

	private function get_pillanart_title( $title, $sep = '&lsaquo;' ) {
		global $post, $paged, $page;

		$title = trim( str_replace( $sep, '', $title ) );

		if ( is_feed() ) {
			return $title;
		}

		if ( is_singular() ) {
			$mtitle = esc_attr( get_post_meta( $post->ID, '_meta_tags_title', true ) );
			if ( ! empty( $mtitle ) ) {
				return $mtitle;
			}
		}

		// Add the site description for the front page.
		$sitename = get_bloginfo( 'name', 'display' );
		$desc     = get_bloginfo( 'description', 'display' );
		if ( is_front_page() && ! empty( $desc ) ) {
			return "$sitename $sep $desc";
		}

		// Add sitename only if title shorter than 40 chars
		if ( 30 >= mb_strlen( $title ) ) {
			$title .= " $sep $sitename";
		}

		// Add a page number if necessary.
		if ( max( $paged, $page ) > 1 ) {
			$title = "$title $sep " . sprintf( __( 'Page %s' ), max( $paged, $page ) );
		}
		return $title;
	}

	/**
	 * Get an image in every imaginable scenarios
	 *
	 * @param  int $post_id
	 * @return string  An image url.
	 */
	private function get_image( $post_id ) {
		if ( is_single() || is_page() ) {

			// Get post thumbnail url
			$thumb_id = get_post_thumbnail_id( $post_id );

			// WordPress use get_post_meta to retrieving thumbnail id,
			// which returns with an empty string, if value not exist.
			// Yes, that's true. v4.1 Stable. 2015-01-22 00:20
			// So... let's check $thumb_id is a numberish thing.

			if ( is_numeric( $thumb_id ) ) {
				$img = wp_get_attachment_image_src( $thumb_id, 'medium' );

				return $this->normalize_url( $img[0] );
			}

			// No post thumbnail. Look for first image in the post.
			$imgs = $this->get_children_images( $post_id, 1 );
			if ( isset( $imgs[0] ) ) {
				$img = wp_get_attachment_image_src( $imgs[0]->ID, 'medium' );

				return $this->normalize_url( $img[0] );
			}
		}

		// Still nothing? Let's use one of hungarian Front page's images.
		$front_id = get_option( 'page_on_front' );
		$imgs = $this->get_children_images( $front_id, -1 );
		$i = mt_rand( 0, count( $imgs ) - 1 );
		$img = wp_get_attachment_image_src( $imgs[ $i ]->ID, 'medium' );

		return $this->normalize_url( $img[0] );
	}

	/**
	 * Get images attached to post/page/whatever at $post_id thingy
	 *
	 * @param int $post_id
	 * @param int $num      Number of images (-1 = all)
	 * @return array
	 */
	private function get_children_images( $post_id, $num ) {

		// PHPStorm strikes get_children call, because somebody attached
		// a short message to the PHPDOC, saying @interal: ... and an
		// "internal" message here, for developers. In an open source
		// software, at the PHPDOC. Totally ingoring the convention
		// of @internal usage. Brilliant.

		$imgs = get_children( [
			'post_parent'    => $post_id,
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'numberposts'    => $num
		]);

		return array_values( $imgs );
	}

	/**
	 * Normalize URL
	 *
	 * @param  string $url Some kind of urlish stringy.
	 * @return string The correct, stable, robust, final, universal URL resource location.
	 */
	private function normalize_url( $url ) {
		// Is this a full url?
		if ( false !== strpos( $url, home_url() ) ) {
			return esc_url( $url );
		}

		// Is leading slash on its place?
		if ( '/' !== substr( $url, 0, 1 ) ) {
			$url = '/' . $url;
		}

		return esc_url( home_url( $url ) );
	}

	/**
	 * Get description
	 *
	 * @param object $post
	 * @return string
	 */
	private function get_description( $post ) {
		if ( is_singular() ) {
			$desc = esc_attr( get_post_meta( $post->ID, '_meta_tags_description', true ) );
			if ( empty( $desc) ) {
				$excerpt = $post->post_excerpt;
				if ( empty( $excerpt ) ) {
					$excerpt = $post->post_content;
				}
				$desc = esc_attr( wp_trim_words( $excerpt, 25, '&#8230;' ) );
			}
			if ( empty( $desc ) ) {
				$desc = esc_attr( get_option( 'meta_description' ) );
			}
		} else {
			$desc = esc_attr( get_option( 'meta_description' ) );
		}
		return $desc;
	}

	/**
	 * Get author meta tags for posts and pages.
	 *
	 * @param object $post
	 * @return array or null
	 */
	private function get_pillanart_author( $post ) {
		if ( is_singular() ) {
			$author = [];
			if (  is_single() ) {
				$rel_members = get_post_meta( $post->ID, '_related_members', true );
				if ( ! empty( $rel_members ) ) {
					$rel_members = explode( ',', $rel_members );
					// Filter out unpublished members
					$pub_members = [];
					foreach ( $rel_members as $mid ) {
						$member = get_post( $mid );
						if ( $member instanceof WP_Post && 'publish' == $member->post_status ) {
							$pub_members[] = $mid;
						}
					}
					if ( ! empty( $pub_members ) ) {
						// Get related members data
						foreach ( $pub_members as $mid ) {
							$gplus_author = get_post_meta( $mid, '_member_gplus', true );
							if ( ! empty( $gplus_author ) ) {
								$author['gplus'][] = esc_attr( $gplus_author );
							}
							$fb_author = get_post_meta( $mid, '_member_facebook', true );
							if ( ! empty( $fb_author) ) {
								$author['fb'][] = esc_attr( $fb_author );
							}
							$tw_user = get_post_meta( $mid, '_member_twitter', true );
							if ( ! empty( $twitter_user ) ) {
								$author['tw'][] = esc_attr( $tw_user );
							}
						}
					}
				}
			} elseif ( is_singular( 'member' ) ) {
				$gplus_author = get_post_meta( $post->ID, '_member_gplus', true );
				if ( ! empty( $gplus_author ) ) {
					$author['gplus'][] = esc_attr( $gplus_author );
				}
				$fb_author = get_post_meta( $post->ID, '_member_facebook', true );
				if ( ! empty( $fb_author) ) {
					$author['fb'][] = esc_attr( $fb_author );
				}
				$tw_user = get_post_meta( $post->ID, '_member_twitter', true );
				if ( ! empty( $twitter_user ) ) {
					$author['tw'][] = esc_attr( $tw_user );
				}
			}

			// Get post author or site default data (if we are not already done)
			if ( ! isset( $author['gplus'] ) ) {
				$gplus_author = get_user_meta( $post->post_author, 'gplus', true );
				$gplus_author = ( ! empty( $gplus_author ) ) ? $gplus_author : get_option( 'gplus_author' );
				$author['gplus'][] = esc_attr( $gplus_author );
			}
			if ( ! isset( $author['fb'] ) ) {
				$fb_author = get_user_meta( $post->post_author, 'facebook', true );
				$fb_author = ( ! empty( $fb_author ) ) ? $fb_author : get_option( 'fb_author' );
				$author['fb'][] = esc_attr( $fb_author );
			}
			if ( ! isset( $author['tw'] ) ) {
				$tw_user = get_user_meta( $post->post_author, 'twitter', true );
				$tw_user = ( ! empty( $tw_user ) ) ? $tw_user : get_option( 'tw_user' );
				$author['tw'][] = esc_attr( $tw_user );
			}
			return $author;
		}

		return null;
	}

	/**
	 * Render all meta tags
	 */
	public function pillanart_meta_tags() {
		global $post;

		// Common site data
		$gplus_publisher = esc_attr( get_option( 'gplus_publisher' ) );
		$fb_publisher    = esc_attr( get_option( 'fb_publisher' ) );
		$tw_site         = esc_attr( get_option( 'tw_site' ) );
		$tw_domain       = esc_attr( get_option( 'tw_domain' ) );

		if ( is_single() || is_singular( 'member' ) || ( is_page() && ! is_front_page() ) ) {
			if ( is_page() ) {
				// Why? And why just on page? Dunno.
				wp_reset_postdata();
			}

			$image       = $this->get_image( $post->ID );
			$pub_time    = get_post_time( 'c', true, $post );
			$mod_time    = get_post_modified_time( 'c', true, $post );
			$site_name   = esc_attr( get_bloginfo( 'name', 'display' ) );
			$title       = esc_attr( $this->title );
			$description = $this->get_description( $post );
			$url         = $this->normalize_url( get_permalink( $post->ID ) );

			$author = $this->get_pillanart_author( $post );
			$link_author = '';
			if ( isset( $author['gplus'] ) ) {
				foreach ( $author['gplus'] as $gplus_author ) {
					$link_author .= '<link rel="author" href="' . $gplus_author . '">';
				}
			}
			$article_author = '';
			if ( isset( $author['fb'] ) ) {
				foreach ( $author['fb'] as $fb_author ) {
					$article_author .= '<meta property="article:author" content="' . $fb_author . '">';
				}
			}
			$tw_creator = '';
			if ( isset( $author['tw'] ) ) {
				foreach ( $author['tw'] as $tw_user ) {
					$tw_creator .= '<meta name="twitter:creator" content="' . $tw_user . '">';
				}
			}

			$out = <<<HERE

	<meta name="description" property="og:description" content="{$description}">
	<link rel="publisher" href="{$gplus_publisher}">
	{$link_author}

	<meta property="article:publisher" content="{$fb_publisher}">
	{$article_author}
	<meta property="article:published_time" content="{$pub_time}">
	<meta property="article:modified_time" content="{$mod_time}">
	<meta property="og:updated_time" content="{$mod_time}">
	<meta property="og:type" content="article">
	<meta property="og:title" content="{$title}">
	<meta property="og:url" content="{$url}">
	<meta property="og:site_name" content="{$site_name}">

	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:site" content="{$tw_site}">
	<meta name="twitter:domain" content="{$tw_domain}">
	{$tw_creator}
	<meta property="og:image" name="twitter:image:src" content="{$image}">

HERE;
		} else {
			wp_reset_postdata();
			$description = $this->get_description( $post );
			$gplus_author = esc_attr( get_option( 'gplus_author' ) );
			$out = <<<HERE

	<meta name="description" content="{$description}">
	<link rel="publisher" href="{$gplus_publisher}">
	<link rel="author" href="{$gplus_author}">

HERE;
		}

		echo $out;
	}

}
