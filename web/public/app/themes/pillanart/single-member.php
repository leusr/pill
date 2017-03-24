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
                                <div class="post-share"><?php _e( 'Share', 'pillanart-theme' ) ?>: <?php print_share_links() ?></div>
                            </header>

                            <div class="post-content clear">
                                <div class="member-img"><?php the_post_thumbnail( 'medium-3x2' ) ?></div>
                                <div class="member-data">
                                    <?php the_member_data() ?>
                                    <button class="send-request"><?php _e( 'Send Request', 'pillanart-theme' ) ?></button>
                                </div>
                                <div class="wedding-request"><?php the_swift_contact_form( 'wedding_request' ) ?></div>
                                <div class="member-content"><?php the_content() ?></div>
                                <?php the_member_related_posts() ?>
                            </div>

                        </article>

                    <?php endif ?>

            </div>
            <!-- /Main Content -->

<?php
get_sidebar();
get_footer();