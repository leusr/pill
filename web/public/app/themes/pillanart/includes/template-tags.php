<?php

/**
 * Display the site logo
 *
 * `h1` tag on front page `div` on others,
 * and add language slug to the class.
 */
function the_site_logo() {
	$tag = is_front_page() ? 'h1' : 'div';
	$format = "<$tag " . 'class="site-logo"><a href="/" title="%s">%s</a></' . "$tag>\n";
	printf( $format,
		__( 'Pillana(r)t Start Page', 'pillanart-theme' ),
		__( 'Pillana(r)t – Hungarian Wedding Photographers', 'pillanart-theme' ) );
}

/**
 * Display pagination with first, previous, next, last
 * icons, and +/- one page with numbers
 */
function the_fancy_pagination() {
	global $wp_query;

	// Return early if there's only 1 page
	if ( 1 >= $wp_query->max_num_pages ) {
		return;
	}

	$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
	$max   = intval( $wp_query->max_num_pages );
	$items = [];

	if ( 1 === $paged ) {
		$items[] = '<span class="' . 'first"><i class="icon-first"></i> ' . __( 'First Page', 'pillanart-theme' ) . '</span>';
		$items[] = '<span class="' . 'prev"><i class="icon-prev"></i> ' . __( 'Previous', 'pillanart-theme' ) . '</span>';
	} elseif ( 1 < $paged ) {
		$items[] = '<a class="first" href="' . get_pagenum_link( 1 ) . '"><i class="icon-first"></i> ' . __( 'First Page', 'pillanart-theme' ) . '</a>';
		$items[] = '<a class="prev" href="' . get_pagenum_link( $paged - 1 ) . '"><i class="icon-prev"></i> ' . __( 'Previous', 'pillanart-theme' ) . '</a>';
	}

	$start = $paged - 1;
	if ( 1 > $start ) $start = 1;
	$end = $start + 2;
	if ( $max < $end ) $end = $max;

	for ( $i = $start; $i <= $end; $i ++ ) {
		if ( $paged === $i ) {
			$items[] = '<span class="' . 'num current-item">' . $i . '</span>';
		} else {
			$items[] = '<a class="num" href="' . get_pagenum_link( $i ) . '">' . $i . '</a>';
		}
	}
	if ( $max === $paged ) {
		$items[] = '<span class="' . 'next"><i class="icon-next"></i> ' . __( 'Next Page', 'pillanart-theme' ) . '</span>';
		$items[] = '<span class="' . 'last"><i class="icon-last"></i> ' . __( 'Last', 'pillanart-theme' ) . '</span>';
	} elseif ( $max > $paged ) {
		$items[] = '<a class="next" href="' . get_pagenum_link( $paged + 1 ) . '"><i class="icon-next"></i> ' . __( 'Next Page', 'pillanart-theme' ) . '</a>';
		$items[] = '<a class="last" href="' . get_pagenum_link( $max ) . '"><i class="icon-last"></i> ' . __( 'Last', 'pillanart-theme' ) . '</a>';
	}

	// Print out ?>

	<nav class="pager-list clear">
		<div class="page-per-total"><?php printf( __( 'Page %s / %s', 'pillanart-theme' ), $paged, $max ) ?></div>
		<?php foreach ( $items as $item ) : ?>

			<?= $item ?>

		<?php endforeach ?>
	</nav>

<?php
}

/**
 * Wrap category links to spans
 *
 * @param null|int|WP_Post $post
 */
function the_formatted_category_links( $post = null ) {
	$post = get_post( $post );
	foreach ( ( get_the_category( $post ) ) as $cat ) { ?>
		<span class="cat-<?= $cat->cat_ID ?>"><a href="<?= get_category_link( $cat ) ?>"><?= $cat->cat_name ?></a></span>
<?php }
}

/**
 * Display member data with icons
 *
 * @param null|int|WP_Post $post
 */
function the_member_data( $post = null ) {
	$pid = get_post( $post )->ID;

	/**
	 * Extract variables
	 *
	 * @var $phones
	 * @var $email
	 * @var $websites
	 * @var $facebook
	 * @var $gplus
	 * @var $twitter
	 */
	$keys = array( 'phones', 'email', 'websites', 'facebook', 'gplus', 'twitter' );
	foreach ( $keys as $key ) {
		$$key = get_post_meta( $pid, '_member_' . $key, true );
	}

	$email_is_public = ( 1 === (int) get_post_meta( $pid, '_email_is_public', true ) ) ? true : false;
	// Phone(s)
	if ( ! empty( $phones ) ) {
		$phones = strpos( $phones, '<br>' ) ? explode( '<br>', $phones ) : array( $phones );
		foreach ( $phones as $phone ) {
			$phone = trim( $phone );
			if ( ! empty( $phone ) ) { // Filter probable empty lines ?>
				<i class="icon-phone"></i> <?= $phone ?><br>
			<?php }
		}
	}
	// Email
	if ( $email_is_public ) {
		$email  = str_replace( array( '@', '.' ), array( ' kukac ', ' pont ' ), $email );
		echo '<i class="' . 'icon-email-ol"></i> <span class="email">' . $email . '</span><br>';
	}
	// Website(s)
	if ( ! empty( $websites ) ) {
		$websites = strpos( $websites, '<br>' ) ? explode( '<br>', $websites ) : array( $websites );
		foreach ( $websites as $url ) {
			$url = trim( $url );
			if ( ! empty( $url ) ) {
				echo '<i class="' . 'icon-globe"></i> <a href="' . $url .'" rel="external">' . url2label( $url )
				     . '</a><br>';
			}
		}
	}
	// Facebook
	if ( ! empty( $facebook ) ) {
		echo '<i class="' . 'icon-facebook"></i> <a href="' . $facebook . '" rel="external">' . url2label( $facebook )
		     . '</a><br>';
	}
	// Google+
	if ( ! empty( $gplus ) ) {
		echo '<i class="' . 'icon-gplus"></i> <a href="' . $gplus . '" rel="external">' . url2label( $gplus ) . '</a><br>';
	}
	// Twitter
	if ( ! empty( $twitter ) ) {
		echo '<i class="' . 'icon-twitter"></i> <a href="https://twitter.com/' . str_replace( '@', '', $twitter )
		     . '" rel="external">' . $twitter . '</a><br>';
	}
}

/**
 * The member related posts
 *
 * @param null|int|WP_Post $post
 */
function the_member_related_posts( $post = null ) {
	global $wpdb;
	$pid = get_post( $post )->ID;

	$rel_posts = $wpdb->get_results(
		"
        SELECT m.post_id, p.ID, p.post_date
        FROM $wpdb->postmeta m
        JOIN $wpdb->posts p
        ON m.post_id = p.ID
        WHERE m.meta_key = '_related_members'
        AND m.meta_value = '$pid'
        ORDER BY p.post_date DESC;
        "
	);

	if ( empty( $rel_posts ) )
		return;

	?>

	<div class="related-posts">
		<h4><?php printf( __( 'Articles from %s', 'pillanart-theme' ), get_the_title( $pid ) ) ?></h4>
		<ul class="related-posts-slider">
			<?php foreach ( $rel_posts as $rel ) :

				$permalink = get_permalink( $rel->ID );
				$thumbnail  = str_replace( ' />', '>', wp_get_attachment_image( get_post_thumbnail_id( $rel->ID ), 'medium-3x2' ) );
				$title = get_the_title( $rel->ID );
				$title_attr = esc_attr( $title ); ?>

				<li>
					<a class="img-link" href="<?= $permalink ?>" title="<?= $title_attr ?>"><?= $thumbnail ?></a>
					<div class="post-cat">
						<?php the_formatted_category_links( $rel->ID ) ?>
					</div>
					<h5><a href="<?= $permalink ?>" title="<?= $title_attr ?>" rel="bookmark"><?= $title ?></a></h5>
				</li>

			<?php endforeach ?>
		</ul>
	</div>

	<?php

//	echo '<div class="related-posts">' . "\n" . '<h4>';
//	printf( __( 'Articles from %s', 'pillanart-theme' ), get_the_title( $pid ) );
//	echo "</h4>\n";
//	echo '<ul class="related-posts-slider">' . "\n";
//	foreach ( $rel_posts as $post ) {
//		$id         = $post->ID;
//		$thumbnail  = wp_get_attachment_image( get_post_thumbnail_id( $id ), 'medium-3x2' );
//		$url        = get_permalink( $id );
//		$title      = get_the_title( $id );
//		$title_attr = the_title_attribute( array( 'echo' => 0, 'post' => $id ) );
//
//		echo '<li><a class="img-link" href="' . $url . '" title="' . $title_attr . '">' . $thumbnail . "</a>\n";
//		echo '<div class="post-cat">';
//		the_formatted_category_links( $id );
//		echo "</div>\n";
//		echo '<h5><a href="' . $url . '" title="' . $title_attr . '" rel="bookmark">' . $title . "</a></h5>\n";
//		echo "</li>\n";
//	}
//	echo "</ul>\n</div>\n";

}

/**
 * The Pillanart Author
 * Search author in related members first
 *
 * @param null|int|WP_Post $post
 */
function the_pillanart_author( $post = null ) {
	$post = get_post( $post );

	$mid = get_related_member_id( $post->ID );
	echo false !== $mid ?
		'<a href="' . get_permalink( $mid ) . '">' . get_the_title( $mid ) . '</a>,' :
		get_the_author_meta( 'display_name', $post->post_author ) . ',';
}

/**
 * Get related member ID if member is active
 *
 * @param int $pid post ID
 * @return int|false Member ID or false
 */
function get_related_member_id( $pid ) {
	$mid = get_post_meta( $pid, '_related_members', true );
	if ( empty( $mid ) ) {
		return false;
	}

	$member = get_post( $mid );
	if ( $member instanceof WP_Post && 'publish' == $member->post_status ) {
		return intval( $mid );
	}

	return false;
}

/**
 * First website of a member
 *
 * @param null|int|WP_Post $post
 */
function the_first_website( $post = null ) {
	$post = get_post( $post );
	$websites = explode( '<br>', get_post_meta( $post->ID, '_member_websites', true ) );
	$url = esc_url( trim( $websites[0] ) );

	echo '<a href="' . $url . '">' . url2label( $url ) . '</a>';
}

/**
 * Display Contact form
 *
 * @param string           $form_slug The slug as the form was registered.
 * @param null|int|WP_Post $post      (optional) Current post, a post ID or WP_Post object
 */
function the_swift_contact_form( $form_slug, $post = null ) {
	$post = get_post( $post );

	if ( class_exists( 'Swift_contact_form' ) ) {
		$scf = new Swift_contact_form;
		$scf->set_form( $form_slug, $post->ID );
		$scf->render_form();
		$scf->save_transient();
	}
}

/**
 * The Billboard Post
 */
function the_billboard_post() {
	$pid = is_single() ? get_the_ID() : 0;
	$bbid = intval( get_transient( 'billboard_id' ) );

	$sfc = new SFC( 'billboard', 5 * MINUTE_IN_SECONDS, true, $bbid === $pid );
	if ( $sfc->off ) :
		$args = [
			'post_type'      => 'post',
			'category_name'  => 'menyasszonyoknak,paroknak,valodi-eskuvok',
			'posts_per_page' => 1,
			'offset'         => 10,
			'orderby'        => 'rand',
			'post__not_in'   => [ $bbid ]
		];
		$bbquery = new WP_Query( $args );
		$post = $bbquery->posts[0];

		// Don't recommend post from inactive members
		while ( false === get_related_member_id( $post->ID ) ) {
			$bbquery = new WP_Query( $args );
			$post = $bbquery->posts[0];
		}

		set_transient( 'billboard_id', $post->ID );

		$permalink  = get_permalink( $post->ID );
		$thumbnail  = str_replace( ' />', '>', wp_get_attachment_image( get_post_thumbnail_id( $post->ID ), 'billboard-4x3' ) );
		$label      = __( 'We recommend this also:', 'pillanart-theme' );
		$title      = get_the_title( $post->ID );
		$title_attr = esc_attr( $title ); ?>

		<div class="billboard">
			<a class="img-link" href="<?= $permalink ?>" title="<?= $title_attr ?>"><?= $thumbnail ?></a>
			<div class="billboard-cont">
				<h2><a href="<?= $permalink ?>" title="<?= $title_attr ?>"><?= $label ?><br>
					<strong><?= $title ?></strong></a></h2>
			</div>
		</div>

		<?php
	endif;
	$sfc->end();
}

function get_billboard_post() {

}

/**
 * Latest tweets
 *
 * @param string $username The twitter user name (after the `@` sign) (optional)
 * @param int $num How many tweets are we need? (optional)
 */
function the_latest_tweets( $username = 'pillanart', $num = 5 ) {
	$sfc = new SFC( "tweets_{$username}_{$num}", 30 * MINUTE_IN_SECONDS, true );
	if ( $sfc->off ) : ?>

		<div class="twitter-updates">
			<h3 class="block-title"><?= strong_first_word( __( 'Twitter updates', 'pillanart-theme' ) ) ?></h3>

			<?= get_tweets( $username, $num ) ?>

		</div>

	<?php endif;
	$sfc->end();
}

/**
 * Get image to front page slider
 *
 * Select random, find first image which
 *   - not contains 'portre' string eighter in filename or post_name
 *   - not the post_thumbnail image
 *   - not a portrait sized image
 *   - and not contains 'skipslider' in description
 *
 * @param int|null $post_id (optional)
 * @param string $size The size of image (as we registered it under init)
 */
function the_front_slider_image( $post_id = null, $size = 'billboard-4x3' ) {
	$post_id = get_post( $post_id )->ID;
	$images  = get_children_images( $post_id, array( 'orderby' => 'rand' ) );

	$postimg_id = get_post_thumbnail_id( $post_id ); // Post thumbnails synchronized by Polyang
	foreach ( $images as $img ) {
		$idata = wp_get_attachment_image_src( $img->ID, 'full' );
		$name  = basename( $idata[0] );
		// Check name, post_thumbnail and skipslider
		if ( $img->ID !== $postimg_id
		     && false === strpos( $img->post_name, 'portre' )
		     && false === strpos( $name, 'portre' )
		     && false === strpos( $img->post_content, 'skipslider' ) ) {

			// Check size
			if ( $idata[1] / $idata[2] > 4 / 3 ) {
				break;
			}
		}
	}
	echo wp_get_attachment_image( $img->ID, $size );
}

/**
 * Google friendy breadcrumbs
 */
function the_breadcrumbs() {
	global $post;

	if ( is_front_page() ) {
		return;
	}

	$front_id = get_option( 'page_on_front' );
	$home_id  = get_option( 'page_for_posts' );
	$paged    = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;

	$items[] = array( __( 'Home', 'pillanart-theme' ), get_the_permalink( $front_id ) );

	if ( is_page() ) {

		$ancestors = get_post_ancestors( $post->ID );
		if ( ! empty( $ancestors ) ) {
			array_reverse( $ancestors );
			foreach ( $ancestors as $anc_id ) {
				if ( $front_id !== $anc_id ) {
					$items[] = array( get_the_title( $anc_id ), get_the_permalink( $anc_id ) );
				}
			}
		}
		$items[] = get_the_title();

	} elseif ( is_home() ) {

		if ( 1 < $paged ) {
			$items[] = array( get_the_title( $home_id ), get_the_permalink( $home_id ) );
			$items[] = sprintf( __( 'Page %s', 'pillanart-theme' ), $paged );
		} else {
			$items[] = get_the_title( $home_id );
		}

	} elseif ( is_singular( 'member' ) ) {

		$items[] = array( __( 'Wedding Photographers', 'pillanart-theme' ), get_post_type_archive_link( 'member' ) );
		$items[] = get_the_title();

	} elseif ( is_single() ) {

		$items[] = array( get_the_title( $home_id ), get_the_permalink( $home_id ) );
		$items[] = get_the_title();

	} elseif ( is_post_type_archive( 'member' ) ) {

		$items[] = __( 'Wedding Photographers', 'pillanart-theme' );

	} elseif ( is_category() ) {

		$items[] = array( get_the_title( $home_id ), get_the_permalink( $home_id ) );
		$items[] = single_cat_title( '', false );

	} elseif ( is_tag() ) {

		$items[] = array( get_the_title( $home_id ), get_the_permalink( $home_id ) );
		$items[] = sprintf( __( 'Posts tagged with <em>%s</em>', 'pillanart-theme' ), single_cat_title( '', false ) );

	}

	$item_format = "\n\t" . '<span typeof="v:Breadcrumb">
        <a href="%s" rel="v:url" property="v:title">%s</a>
    </span>';
	$last_item   = "\n\t" . '<span typeof="v:Breadcrumb">
        <span property="v:title">%s</span>
    </span>';

	echo '<div class="breadcrumbs" xmlns:v="http://rdf.data-vocabulary.org/#">';
	foreach ( $items as $item ) {
		if ( is_array( $item ) ) {
			// Restore full url
			if ( false === strpos( $item[1], home_url() ) ) {
				$item[1] = home_url( $item[1] );
			}
			printf( $item_format, $item[1], $item[0] );
		} elseif ( is_string( $item ) ) {
			printf( $last_item, $item );
		}
	}
	echo "</div>\n";
}

/**
 * Share links
 *
 * @param int|null $post_id
 */
function print_share_links( $post_id = null ) {
	$post_id = get_post( $post_id )->ID;
	$post_url = get_permalink( $post_id );
	$post_url = false === strpos( $post_url, home_url() ) ?
		rawurlencode( home_url( $post_url ) ) : rawurlencode( $post_url );
	$post_title = rawurlencode( get_the_title( $post_id ) );

	// Post thumbnail for Pinterest
	$thumb_id = get_post_thumbnail_id( $post_id );
	if ( "" !== $thumb_id ) {
		$img     = wp_get_attachment_image_src( $thumb_id, 'medium-3x2' );
		$img_url = rawurlencode( home_url( $img[0] ) );

		// Image description
		$alt_text = get_post_meta( $thumb_id, '_wp_attachment_image_alt', true );
		if ( ! empty( $alt_text ) ) {
			$img_desc = rawurlencode( $alt_text );
		} else {
			$img_obj = get_posts( array(
				'p'              => $thumb_id,
				'post_type'      => 'attachment',
				'post_mime_type' => 'image'
			) );
			if ( ! empty( $img_obj->post_excerpt ) ) {
				$img_desc = rawurlencode( $img_obj->post_excerpt );
			} else {
				$img_desc = $post_title;
			}
		}
	} else {
		$img_url = null;
	}

	// Facebook
	$form = "\n" . '<a class="icon-facebook" href="%s" title="' . __( 'Share on Facebook',
			'pillanart-theme' ) . '" rel="external"><span>Facebook</span></a>';
	$link = 'https://www.facebook.com/sharer/sharer.php?u=' . $post_url;
	printf( $form, $link );

	// Twitter
	$form = "\n" . '<a class="icon-twitter" href="%s" title="' . __( 'Share on Twitter',
			'pillanart-theme' ) . '" rel="external"><span>Twitter</span></a>';
	$link = 'https://twitter.com/home?status=' . $post_title . '+' . $post_url;
	printf( $form, $link );

	// Google+
	$form = "\n" . '<a class="icon-gplus" href="%s" title="' . __( 'Share on Google+',
			'pillanart-theme' ) . '" rel="external"><span>Google+</span></a>';
	$link = 'https://plus.google.com/share?url=' . $post_url;
	printf( $form, $link );

	// Pinterest
	if ( ! is_null( $img_url ) ) {
		$form = "\n" . '<a class="icon-pinterest" href="%s" title="' . __( 'Pin it on Pinterest',
				'pillanart-theme' ) . '" rel="external"><span>Pinterest</span></a>';
		$link = 'https://pinterest.com/pin/create/button/?url=' . $post_url . '&amp;media=' . $img_url . '&amp;description=' . $img_desc;
		printf( $form, $link );
	}
}

/**
 * Get all images attached to a post
 *
 * @param  int        $post_id
 * @param  array|null $args (optional)
 * @return array
 */
function get_children_images( $post_id, $args = null ) {
	$params = array(
			'post_parent'    => $post_id,
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'orderby'        => 'title',
			'order'          => 'ASC'
	);
	if ( isset( $args ) ) {
		$params = array_merge( $params, $args );
	}

	return get_children( $params );
}
