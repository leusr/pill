<?php

/* ----------------------------------------------------------------------------
 * Shortcodes
 * ----------------------------------------------------------------------------
 */

/**
 * Spam protected emails
 * [email]info@example.com[/email] shortcode
 *
 * @param  array $atts (empty, unused)
 * @param  string|null $content Text inside the shortcode.
 *
 * @return string  Spam-safe email (restored with javascript)
 */
add_shortcode( 'email', 'spam_protected_email' );

function spam_protected_email( $atts, $content = null ) {
	if ( false === $email = filter_var( $content, FILTER_VALIDATE_EMAIL ) ) {
		return;
	}
	$email = str_replace( array( '@', '.' ), array( ' kukac ', ' pont ' ), $email );

	return sprintf( '<span class="email">%s</span>', $email );
}

/**
 * jQuery Gallery
 * [gallery] shortcode
 *
 * @param  array $atts
 *
 * @return string html
 */
add_shortcode( 'gallery', 'print_jq_gallery' );

function print_jq_gallery( $atts ) {
	global $post;
	extract( shortcode_atts( array(
		'post_id'   => $post->ID,
		'from_lang' => null,
		'ids'       => null,
		'exclude'   => null
	), $atts, 'gallery'
	) );

	// Postdata for Pin It button
	$post_url   = get_permalink( $post_id );
	$post_url   = ( false === strpos( $post_url,
			home_url() ) ) ? rawurlencode( home_url( $post_url ) ) : rawurlencode( $post_url );
	$post_title = rawurlencode( get_the_title( $post_id ) );

	if ( isset( $ids ) ) {
		$ids = explode( ',', $ids );
		$max = count( $ids );
		for ( $i = 0; $i < $max; $i ++ ) {
			$ids[ $i ] = trim( $ids[ $i ] );
		}
	} else { // No ids.
		$ids = array();

		if ( isset( $from_lang ) ) {
			$images = get_children_images( get_translated_pair_id( $post_id, $from_lang ) );
		} else {
			$images = get_children_images( $post_id );
			if ( empty( $images ) ) {
				// No ids. No (/wrong) post_id. No from_lang.
				// But THIS function definitely WILL get some fckng images!
				$images = get_children_images( get_translated_pair_id( $post_id ) );

				_prc( $post_id );
				_prc( get_translated_pair_id( $post_id ) );
			}
		}

		if ( isset( $exclude ) ) {
			$i       = 0;
			$exclude = explode( ',', $exclude );
			foreach ( $exclude as $ex ) {
				$ex            = trim( $ex );
				$exclude[ $i ] = $ex; // Write back trimmed value
				if ( ! is_numeric( $ex ) ) {
					if ( 'post_image' === $ex || 'post_thumbnail' === $ex ) {
						// Exlude post image
						$exclude[ $i ] = get_post_thumbnail_id( $post_id );
					} else {
						// Exlude by filename
						foreach ( $images as $img ) {
							$src  = wp_get_attachment_image_src( $img->ID, 'full' );
							$name = basename( $src[0] );
							if ( false !== strpos( $name, $ex ) ) {
								$exclude[ $i ] = $img->ID;
							}
						}
					}
				}
				$i ++;
			}
			// Group non-excluded image ids to new array
			foreach ( $images as $image ) {
				if ( ! in_array( $image->ID, $exclude ) ) {
					$ids[] = $image->ID;
				}
			}
		} else {
			// No excludes. Just get the ids from images
			foreach ( $images as $img ) {
				$ids[] = $img->ID;
			}
		}
	}

	// Now there must be a well filtered ids array. Build html out.
	$out = is_singular( 'member' ) ?
		'<h4 class="jq-gallery-header">' . __( 'Portfolio', 'pillanart-theme' ) . "</h4>\n" :
		'';
	$out .= '<div class="jq-gallery clear">' . "\n";

	$itemformat = "\t" . '<div data-src="%s" data-responsive-src="%s" data-pinit-link="%s">%s</div>' . "\n";
	foreach ( $ids as $img_id ) {
		// wp_get_attachment_image returns html
		$thumb = wp_get_attachment_image( $img_id, 'thumbnail' );
		// wp_get_attachment_image_src returns array 0: src, 1: width, 2: height
		$large = wp_get_attachment_image_src( $img_id, 'large' );
		$full  = wp_get_attachment_image_src( $img_id, 'qsxga' );
		$media = rawurlencode( home_url( $full[0] ) );
		$pinit = 'https://pinterest.com/pin/create/button/?url=' . $post_url
		         . '&amp;media=' . $media
		         . '&amp;description=' . $post_title;
		$out .= sprintf( $itemformat, $full[0], $large[0], $pinit, $thumb );
	}
	$out .= "</div>\n";

	return $out;
}
