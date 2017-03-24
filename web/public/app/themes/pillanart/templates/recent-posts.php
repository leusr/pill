<?php $sfc = new SFC( 'recent_posts', 15 * MINUTE_IN_SECONDS ); if ( $sfc->off ) : ?>

	<div class="recent-posts">
		<h3 class="block-title"><?= strong_first_word( __( 'Recent posts', 'pillanart-theme' ) ) ?></h3>

		<?php $rp = new WP_Query( [
				'posts_per_page'      => 4,
				'no_found_rows'       => true,
				'post_status'         => 'publish',
				'ignore_sticky_posts' => true
			] );

			while ( $rp->have_posts() ) : $rp->the_post() ?>

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

		<?php endwhile; wp_reset_postdata() ?>

	</div>

<?php endif; $sfc->end();
