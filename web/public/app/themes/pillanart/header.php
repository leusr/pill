<!doctype html>
<html <?php language_attributes() ?>>
<head>
	<meta charset="utf-8">
	<meta name="ascii-art" content="yes">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Oswald:300,400,700%7CMerriweather+Sans:400,700,400italic&#38;subset=latin,latin-ext">

	<?php wp_head() ?>
</head>

<body>
<div class="site-container">

<!-- Site Header -->
<div class="top-line">
	<div class="wrap">
		<nav class="top-nav">
			<ul class="clear"><?php wp_nav_menu( [ 'theme_location' => 'top', 'indent' => 6 ] ) ?></ul>
		</nav>
	</div>
</div>

<header class="site-header">
	<div class="wrap">
		<?php the_site_logo() ?>

		<nav class="header-social">
			<a class="icon-facebook" href="https://www.facebook.com/pillanart" title="<?php _e( 'Pillana(r)t on Facebook', 'pillanart-theme' ) ?>" rel="external"><span>Facebook</span></a>
			<a class="icon-twitter" href="https://twitter.com/Pillanart" title="<?php _e( 'Follow Pillana(r)t on Twitter', 'pillanart-theme' ) ?>" rel="external"><span>Twitter</span></a>
			<a class="icon-gplus" href="http://google.com/+PillanartHu_Eskuvofotosok" title="<?php _e( 'Pillana(r)t is on Google+', 'pillanart-theme' ) ?>" rel="external"><span>Google+</span></a>
			<a class="icon-pinterest" href="http://www.pinterest.com/pillanart/" title="<?php _e( 'Follow Pillana(r)t on Pinterest', 'pillanart-theme' )?>" rel="external"><span>Pinterest</span></a>
		</nav>

		<div class="mobile-nav-show"><i class="icon-menu" title="<?php _e( 'Open Menu', 'pillanart-theme' ) ?>"></i></div>
	</div>
</header>

<nav class="mobile-nav">
	<ul class="mobile-nav-list"><?php wp_nav_menu( [ 'theme_location' => 'mobile', 'indent' => 4 ] ) ?></ul>
</nav>

<nav class="main-nav">
	<div class="wrap">
		<ul><?php wp_nav_menu( [ 'theme_location' => 'main', 'indent' => 5 ] ) ?></ul>
	</div>
</nav>
<!-- End: Site Header -->
