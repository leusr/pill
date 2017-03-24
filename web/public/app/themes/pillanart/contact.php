<?php

/**
 * Template name: Contact
 */

get_header() ?>

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
                                <?php the_swift_contact_form( 'contact' ) ?>
                            </div>
                        </article>

                    <?php endif ?>

                </div>
                <!-- /Main Content -->

                <!-- Sidebar -->
                <div class="sidebar clear">

                    <?php the_billboard_post() ?>

                    <?php the_latest_tweets() ?>

                </div>
                <!-- /Sidebar -->

<?php get_footer();