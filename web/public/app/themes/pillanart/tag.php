<?php get_header() ?>

		<!-- Site Content -->
		<div class="site-content">
			<div class="wrap">

				<!-- Main Content -->
				<div class="main-content clear">

					<?php the_breadcrumbs() ?>

					<h3 class="main-title"><?php single_cat_title() ?></h3>

						<?php query_posts( $query_string . '&posts_per_page=-1' );
						if ( have_posts() ) : ?>

							<div class="post-exc-sm-list clear">

								<?php while ( have_posts() ) : the_post() ?>

									<div class="post-exc-sm clear">
										<a class="img-link" href="<?php the_permalink() ?>"><?php the_post_thumbnail( 'small-3x2' ) ?></a>
										<div class="exc-cont">
											<h2><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2>
											<div class="post-meta">
												<span class="author"><?php the_pillanart_author() ?></span>
												<time datetime="<?php the_time( 'c' ) ?>"><?php the_time( 'M j, Y' ) ?></time>
											</div>
										</div>
									</div>

								<?php endwhile ?>

							</div>

						<?php endif ?>

					<div class="tagcloud">
						<h2><strong><?php _e( 'Tagcloud', 'pillanart-theme' ) ?></strong></h2>

						<p><?php wp_tag_cloud( array( 'smallest' => 13, 'largest' => 20, 'unit' => 'px', 'number' => 0, 'separator' => ', ' ) ) ?></p>
					</div>

				</div>
				<!-- /Main Content -->

<?php
get_sidebar();
get_footer();
