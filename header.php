<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">

	<header id="header" class="header" role="banner">
		<div class="header__info">

		</div>
		<div class="header__main">
			<div class="logo">
				<?php $logo = carbon_get_theme_option('logo')?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo__link"><img src="<?php echo $logo ?>" class="logo__img" alt="Plantis" width="150" height="26"></a>
			</div><!-- .logo -->
			<div class="description">
				<?php $site_title = carbon_get_theme_option('site_title')?>
				<?php if ( is_front_page()) : ?>
					<h1 class="site-title"><?php echo $site_title ?></h1>
				<?php else : ?>
					<p class="site-title"><?php echo $site_title ?></p>
				<?php endif; ?>
			</div><!-- .description -->
			<div class="search">
				<?get_search_form();?>
				<div class="search-result">

				</div>
			</div><!-- .search -->
			<div class="header-cart">
				<?php plnt_woocommerce_cart_header(); ?>
			</div><!-- .header-cart -->
		</div>
		
		<div class="header__menu">
			<nav id="site-navigation" class="main-navigation" role="navigation">
				<?php ast_primary_menu(); ?>
			</nav><!-- #site-navigation -->
		</div>
	</header><!-- #header -->
	
