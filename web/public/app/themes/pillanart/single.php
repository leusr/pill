<?php get_header() ?>

        <!-- Site Content -->
        <div class="site-content">
            <div class="wrap">

                <!-- Main Content -->
                <div class="main-content clear">

                    <?php the_breadcrumbs() ?>

                    <?php if ( have_posts() ) : the_post() ?>

                        <article class="post">

                            <header class="post-header clear">
                                <div class="post-cat"><?php the_formatted_category_links() ?></div>
                                <h1><?php the_title() ?></h1>
                                <div class="post-meta">
                                    <span class="author"><?php the_pillanart_author() ?></span>
                                    <time datetime="<?php the_time( 'c' ) ?>"><?php the_time( 'M j, Y' ) ?></time>
                                </div>
                                <div class="post-share"><?php _e( 'Share', 'pillanart-theme' ) ?>: <?php print_share_links() ?></div>
                            </header>

                            <div class="post-content clear">
                                <?php the_content() ?>

                            </div>

                            <footer class="post-footer">
                                <?php the_tags( '<div class="post-tags"><strong>' . __( 'Tags', 'pillanart-theme' ) . '</strong>: ', ', ', '</div>' ) ?>

                                <div class="post-share"><?php print_share_links() ?></div>

                                <div class="pager-post clear">

                                    <div class="prev-post"><?php previous_post_link( '%link', '<i class="icon-left"></i> <span class="label">' . __( 'Previous post', 'pillanart-theme' ) . '</span> <span class="title">%title</span>' ) ?></div>

                                    <div class="next-post"><?php next_post_link( '%link', '<i class="icon-right"></i> <span class="label">' . __( 'Next post', 'pillanart-theme' ) . '</span> <span class="title">%title</span>' ) ?></div>

                                </div>
                            </footer>

                        </article>

                    <?php endif ?>

                </div>
                <!-- /Main Content -->

<?php
get_sidebar();
get_footer();