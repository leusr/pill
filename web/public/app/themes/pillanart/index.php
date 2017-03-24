<?php get_header() ?>

        <!-- Site Content -->
        <div class="site-content">
            <div class="wrap">

                <!-- Main Content -->
                <div class="main-content clear">

                    <?php the_breadcrumbs() ?>

                    <h3 class="main-title"><?= get_the_title( get_option( 'page_for_posts' ) ) ?></h3>

                    <?php if ( have_posts() ) : while ( have_posts() ) : the_post() ?>

                        <div class="post-exc clear">
                            <a class="img-link" href="<?php the_permalink() ?>"><?php the_post_thumbnail( 'medium-3x2' ) ?></a>
                            <div class="exc-cont">
                                <div class="post-meta">
                                    <span class="author"><?php the_pillanart_author() ?></span>
                                    <time datetime="<?php the_time( 'c' ) ?>"><?php the_time( 'M j, Y' ) ?></time>
                                </div>
                                <h2><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2>
                                <?php the_excerpt() ?>
                                <div class="post-cat"><?php the_formatted_category_links() ?></div>
                            </div>
                        </div>

                    <?php endwhile; endif ?>

                    <?php the_fancy_pagination() ?>

            </div>
            <!-- /Main Content -->

<?php
get_sidebar();
get_footer();