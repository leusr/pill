<?php get_header() ?>

		<!-- Site Content -->
		<div class="site-content">
			<div class="wrap">

				<!-- Main Content -->
				<div class="main-content clear">

					<div class="page404">
						<h1><?php _e( '404 – Page not found', 'pillanart-theme') ?></h1>
						<p><?php _e( 'It looks like nothing was found at this location.', 'pillanart-theme' ) ?></p>
					</div>

				</div>
				<!-- /Main Content -->

				<!-- Sidebar -->
				<div class="sidebar clear">

					<?php the_billboard_post() ?>

				</div>
				<!-- /Sidebar -->

<?php get_footer();