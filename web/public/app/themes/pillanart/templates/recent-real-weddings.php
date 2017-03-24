<?php $sfc = new SFC( "recent_real_weddings", 15 * MINUTE_IN_SECONDS ); if ( $sfc->off ) :

	$rrw = new WP_Query( [
		'posts_per_page'      => 4,
		'no_found_rows'       => true,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'cat'                 => get_cat_ID( 'Valódi esküvők' )
	] );

	while ( $rrw->have_posts() ) : $rrw->the_post() ?>

		<div class="post-exc-sm clear">
			<a class="img-link" href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>"><?php the_post_thumbnail( 'small-3x2' ) ?></a>
			<div class="exc-cont">
				<h2><a href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>" rel="bookmark"><?php the_title() ?></a></h2>
				<div class="post-meta">
					<span class="author"><?php the_pillanart_author() ?></span>
					<time datetime="<?php the_time( 'c' ) ?>"><?php the_time( 'M j, Y' ) ?></time>
				</div>
			</div>
		</div>

	<?php endwhile;	wp_reset_postdata() ?>

<?php endif; $sfc->end();
