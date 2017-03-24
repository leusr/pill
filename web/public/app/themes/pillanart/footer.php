
	</div>
</div>
<!-- End: Site Content -->

<!-- Site Footer -->
<footer class="site-footer">
	<div class="wrap">
		<!-- Column 1 -->
		<div class="col-1">
			<div class="block about">
				<div class="block-title"><h3><?php _e( 'About Pillana(r)t', 'pillanart-theme' ) ?></h3></div>
				<p><?php _e( 'Pillana(r)t - Hungarian Wedding Photographers was established in December 2007 along professional and ethical principles ten of the most sought after wedding photographers from Hungary. All former members of the association assume responsibility for each other and want to show the direction of this domestic chaos.', 'pillanart-theme' ) ?></p>
			</div>
			<div class="block contacts">
				<div class="block-title"><h3><?php _e( 'Contact', 'pillanart-theme' ) ?></h3></div>
				<ul>
					<li><span class="email"><i class="icon-email"></i> info kukac pillanart pont hu</span></li>
					<li><a href="https://facebook.com/pillanart" rel="external"><i class="icon-facebook"></i>
							<?php _e( 'Pillana(r)t on Facebook', 'pillanart-theme' ) ?></a></li>
					<li><a href="https://twitter.com/pillanart" rel="external"><i class="icon-twitter"></i>
							<?php _e( 'Pillana(r)t on Twitter', 'pillanart-theme' ) ?></a></li>
				</ul>
			</div>
		</div>
		<!-- End: Column 1 -->

		<!-- Column 2 -->
		<div class="col-2">
			<div class="block latest-posts">
				<div class="block-title"><h3><?php _e( 'Real Weddings', 'pillanart-theme' ) ?></h3></div>

				<?php get_template_part( 'templates/recent-real-weddings' ) ?>
			</div>
		</div>
		<!-- End: Column 2 -->

		<!-- Column 3 -->
		<div class="col-3">
			<div class="block links">
				<div class="block-title"><h3><?php _e( 'Recommended links', 'pillanart-theme' ) ?></h3></div>
				<ul>
					<li><a href="http://www.wpja.com/" rel="external nofollow">WPJA<br>
							<span class="desc">Wedding Photojournalist Association</span></a></li>
					<li><a href="http://www.agwpja.com/" rel="external nofollow">AG | WPJA<br>
							<span class="desc">Artistic Guild of the Wedding Photojournalist Association</span></a></li>
					<li><a href="http://www.ispwp.com/" rel="external nofollow">ISPWP<br>
							<span class="desc">International Society of Professional Wedding Photographers</span></a></li>
					<li><a href="http://www.swpp.co.uk/" rel="external nofollow">SWPP<br>
							<span class="desc">Society of Wedding and Portrait Photographers</span></a></li>
				</ul>
			</div>
		</div>
		<!-- End: Column 3 -->
	</div>
</footer>
<!-- End: Site Footer -->

<!-- Bottom Line -->
<footer class="bottom-line">
	<div class="wrap">
		<div class="copyright">
			<p><?php printf( __( '&#169; Copyright %d. <strong>Pillana(r)t</strong> – Hungarian Wedding Photographers', 'pillanart-theme' ), date( 'Y' ) ) ?></p>
		</div>
		<nav class="bottom-nav">
			<ul class="clear"><?php wp_nav_menu( [ 'theme_location' => 'bottom', 'indent' => 6 ] ) ?></ul>
		</nav>
	</div>
</footer>
<!-- End: Bottom Line -->

<nav class="scroll-top">
	<a href="#"><i class="icon-up"><span><?php _e( 'Scroll top', 'pillanart-theme' ) ?></span></i></a>
</nav>

</div>
<!-- End: div.site-container -->

<?php wp_footer() ?>

</body>
</html>