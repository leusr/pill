<?php get_header() ?>

        <!-- Site Content -->
        <div class="site-content">
            <div class="wrap">

                <!-- Main Content -->
                <div class="main-content clear">

                    <?php the_breadcrumbs() ?>

                    <h3 class="main-title"><?php _e( 'Wedding Photographers', 'pillanart-theme' ) ?></h3>

                    <?php global $wp_query;
                        query_posts( array_merge( $wp_query->query, array( 'orderby' => 'rand', 'posts_per_page' => -1 ) ) );
                        if ( have_posts() ) : ?>

                        <div class="archive-member">

                            <?php while ( have_posts() ) : the_post() ?>

                                <div class="member-exc clear">
                                    <a class="img-link" href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>"><?php the_post_thumbnail( 'medium-3x2' ) ?></a>
                                    <div class="exc-cont">
                                        <h2><a href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>" rel="bookmark"><?php the_title() ?></a></h2>
                                        <div class="exc-meta"><?php the_first_website() ?></div>
                                    </div>
                                </div>

                            <?php endwhile ?>

                        </div>

                    <?php endif ?>

                </div>
                <!-- /Main Content -->

<?php
get_sidebar();
get_footer();