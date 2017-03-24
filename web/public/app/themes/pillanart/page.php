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
                                <h1><?php the_title() ?></h1>
                            </header>

                            <div class="post-content clear">
                                <?php the_content() ?>
                            </div>
                        </article>

                    <?php endif ?>

                </div>
                <!-- /Main Content -->

<?php
get_sidebar();
get_footer();