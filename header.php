<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	
	<?php wp_head(); ?>
</head>

<?php
global $plants_cat_id;
$plants_cat_id = carbon_get_theme_option('plants_cat_id');
// echo '<script>console.log('.$plants_cat_id.')</script>'
?>

<body <?php body_class(); ?>>
<div id="page" class="site">

	<header id="header" class="header" role="banner">
		<div class="header__info">
			<div class="container"></div>
		</div>
		<div class="header__main container">
			<div class="header__right-wrap">
				<div class="logo">
					<?php $logo = carbon_get_theme_option('logo');?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo__link"><img src="<?php echo $logo ?>" class="logo__img" alt="Plantis" width="150" height="26"></a>
				</div><!-- .logo -->
				<div class="search-btn">
					<?php $search_icon = carbon_get_theme_option('search_icon')?>
					<button class="header-btn__wrap">
						<img class="header-btn__icon" src="<?php echo $search_icon ?>" alt="search" width="21" height="21">
						<span class="header-btn__label">Поиск</span>		
					</button>
				</div>
			</div>
			<div class="description">
				<?php $site_title = carbon_get_theme_option('site_title')?>
				<?php if ( is_front_page()) : ?>
					<h1 class="site-title"><?php echo $site_title ?></h1>
				<?php else : ?>
					<p class="site-title"><?php echo $site_title ?></p>
				<?php endif; ?>
			</div><!-- .description -->
			<div class="header-cart">
				<?php plnt_woocommerce_cart_header(); ?>
			</div><!-- .header-cart -->
		</div>
		
		<div class="header__menu">
			<div class="container">
				<nav id="site-navigation" class="main-navigation" role="navigation">
					<?php ast_primary_menu(); ?>
				</nav><!-- #site-navigation -->
			</div>
		</div>
	</header><!-- #header -->
	
