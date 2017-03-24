<?php get_header(); ?>

<!-- Members Slider -->
<?php $sfc = new SFC( 'members_slider', 10 * MINUTE_IN_SECONDS ); if ( $sfc->off ) :
	$m = new WP_Query( [
		'post_type' => 'member',
		'nopaging'  => true,
		'orderby'   => 'rand'
	] ); ?>

	<div class="members-promo">
		<div class="wrap">
			<ul class="members-slider">
				<?php while ( $m->have_posts() ) : $m->the_post() ?>
					<li>
						<div class="member">
							<a class="img-link" href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>"><?php the_front_slider_image() ?></a>
							<div class="mem-cont clear">
								<a href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>"><?php the_post_thumbnail( 'thumbnail' ) ?></a>
								<h2><a href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>" rel="bookmark"><?php the_title() ?></a></h2>
								<div class="mem-meta"><?php the_first_website() ?></div>
							</div>
						</div>
					</li>
				<?php endwhile; wp_reset_postdata() ?>
			</ul>
		</div>
	</div>
<?php endif; $sfc->end() ?>
<!-- End: Members Slider -->

<!-- Site Content -->
<div class="site-content">
	<div class="wrap">

		<!-- Main Content -->
		<div class="main-content clear">
			<h3 class="main-title"><?= get_the_title( get_option( 'page_for_posts' ) ) ?></h3>

			<!-- Latest 4 post -->
			<?php $blog = new WP_Query( [ 'posts_per_page' => 4 ] ); while ( $blog->have_posts() ) : $blog->the_post() ?>
				<div class="post-exc clear">
					<a class="img-link" href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>"><?php the_post_thumbnail( 'medium-3x2' ) ?></a>
					<div class="exc-cont">
						<div class="post-meta">
							<span class="author"><?php the_pillanart_author() ?></span>
							<time datetime="<?php the_time( 'c' ) ?>"><?php the_time( 'M j, Y' ) ?></time>
						</div>
						<h2><a href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>" rel="bookmark"><?php the_title() ?></a></h2>
						<?php the_excerpt() ?>
						<div class="post-cat"><?php the_formatted_category_links() ?></div>
					</div>
				</div>
			<?php endwhile; wp_reset_postdata(); ?>
			<!-- End: Latest 4 post -->

			<!-- Plus 6 smaller posts -->
			<div class="post-exc-sm-list clear">
				<?php $plus = new WP_Query( [ 'posts_per_page' => 6, 'offset' => 4 ] ); while ( $plus->have_posts() ) : $plus->the_post() ?>
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
			<!-- End: Plus 6 smaller posts -->
		</div>
		<!-- End: Main Content -->

		<!-- Sidebar -->
		<div class="sidebar clear">

			<?php the_latest_tweets() ?>

			<?php the_billboard_post() ?>

			<?php get_template_part( 'templates/recent-posts' ) ?>

		</div>
		<!-- End: Sidebar -->

<?php get_footer();
