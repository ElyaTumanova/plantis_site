<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
  <link rel='stylesheet' href='assets/css/all.php' />
	
	<!-- Yandex.Metrika counter -->
	<script async type="text/javascript" >
	(function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
	m[i].l=1*new Date();
	for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
	k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
	(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

	ym(87781741, "init", {
			clickmap:true,
			trackLinks:true,
			accurateTrackBounce:true,
			webvisor:true,
			ecommerce:"dataLayer"
	});
	window.dataLayer = window.dataLayer || [];
	</script>
	<noscript><div><img src="https://mc.yandex.ru/watch/87781741" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
	<!-- /Yandex.Metrika counter -->
	 
	<!-- Top.Mail.Ru counter -->
	<script type="text/javascript">
	var _tmr = window._tmr || (window._tmr = []);
	_tmr.push({id: "3527126", type: "pageView", start: (new Date()).getTime()});
	(function (d, w, id) {
	if (d.getElementById(id)) return;
	var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true; ts.id = id;
	ts.src = "https://top-fwz1.mail.ru/js/code.js";
	var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
	if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }
	})(document, window, "tmr-code");
	</script>
	<noscript><div><img src="https://top-fwz1.mail.ru/counter?id=3527126;js=na" style="position:absolute;left:-9999px;" alt="Top.Mail.Ru" /></div></noscript>
	<!-- /Top.Mail.Ru counter -->
	
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<noscript><div><img src="https://mc.yandex.ru/watch/87781741" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<div id="page" class="site">

<header id="header" class="header" role="banner">
		<div class="header__desktop">
			<div class="header__info">
				<div class="header__info-wrap container">
					<nav class="header__info-navigation" role="navigation">
						<?php plnt_secondary_menu(); ?>
					</nav>
					<div class="header__phones"><a href="tel:+78002015790">8 800 201 57 90</a> | <a href="tel:+79647687944">8 964 768 79 44</a></div>
					<div class="header__working-hours">Eжедневно с 10 до 20</div>
				</div>
			</div>
			<div class="header__notice-wrap">
				<?php get_template_part( 'template-parts/header-notice' );?>
			</div>

			<div class="header__main">
				<div class="container">
					<div class="header__wrap">
						<div class="logo">
							<?php $logo = carbon_get_theme_option('logo');?>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo__link"><img src="<?php echo $logo ?>" class="logo__img" alt="Plantis" width="150" height="26"></a>
						</div><!-- .logo -->
						<div class="header__catalog button"> 
							<div class="header__catalog_icon">
							<svg class="header__catalog_icon-svg" width="18" height="2" viewBox="0 0 18 2" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18 1H0" stroke="white" stroke-width="1.5" stroke-miterlimit="10"></path></svg>
							<svg class="header__catalog_icon-svg" width="18" height="2" viewBox="0 0 18 2" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18 1H0" stroke="white" stroke-width="1.5" stroke-miterlimit="10"></path></svg>
							<svg class="header__catalog_icon-svg" width="18" height="2" viewBox="0 0 18 2" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18 1H0" stroke="white" stroke-width="1.5" stroke-miterlimit="10"></path></svg>
							</div>
							Каталог
						</div>
					</div>
					<div class="header__description">
						<?php $site_title = carbon_get_theme_option('site_title')?>
						<?php if ( is_front_page()) : ?>
							<h1 class="site-title"><?php echo $site_title ?></h1>
						<?php else : ?>
							<p class="site-title"><?php echo $site_title ?></p>
						<?php endif; ?>
					</div><!-- .description -->
					<div class="header__wrap">
						<div class="search-btn">
							<?php $search_icon = carbon_get_theme_option('search_icon')?>
							<button class="header-btn__wrap">
								<?php echo $search_icon ?>
								<span class="header-btn__label">Поиск</span>		
							</button>
						</div>
						<div class="header__account">
							<?php $account_icon = carbon_get_theme_option('account_icon');
							$account_logged_icon = carbon_get_theme_option('account_logged_icon')?>
							<?php if (!is_user_logged_in()) : 
								if (is_account_page()) :?>
									<a href ="<?php echo esc_url( home_url( '/my-account' ) ); ?>" class="header-btn__wrap">
										<?php echo $account_icon ?>	
										<span class="header-btn__label">Войти</span>		
									</a>
								<?php else :?> 
									<div class="header-btn__wrap login-btn">
										<?php echo $account_icon ?>	
										<span class="header-btn__label">Войти</span>		
									</div>
								<?php endif; ?>
							<?php else :?>
								<a href ="<?php echo esc_url( home_url( '/my-account/orders' ) ); ?>" class="header-btn__wrap header-btn__wrap_active">
									<?php echo $account_icon ?>		
									<span class="header-btn__label">Войти</span>		
								</a>
							<?php endif; ?>
						</div>
						<div class="header__wishlist">
							<?php echo do_shortcode('[yith_wcwl_items_count]')?>
						</div>
						
						<div class="header-cart">
							<?php 
								plnt_woocommerce_cart_header(); 
								?><div class="mini-cart__wrap"> <?php
									plnt_woocommerce_mini_cart(); 
								?></div>
						</div><!-- .header-cart -->
					</div>
				</div>
			</div>
			
			<div class="header__menu">
				<div class="container">
					<nav id="site-navigation" class="main-navigation" role="navigation">
						<?php plnt_primary_menu(); ?>
					</nav><!-- #site-navigation -->
				</div>
			</div>
		</div>

		<div class="header__mob container">
			<div class="search-btn">
				<?php $search_icon_mob = carbon_get_theme_option('search_icon_mob')?>
				<button class="header-btn__wrap header-btn__wrap_mob">
					<?php echo $search_icon ?>
					<!-- <img class="header-btn__icon" src="<?php //echo $search_icon_mob ?>" alt="search" width="21" height="21">		 -->
				</button>
			</div>
			<div class="logo">
				<?php $logo = carbon_get_theme_option('logo');?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo__link"><img src="<?php echo $logo ?>" class="logo__img" alt="Plantis" width="150" height="26"></a>
			</div><!-- .logo -->
			<div class="header__mob-menu">
				<!-- <?php $menu_icon_mob = carbon_get_theme_option('menu_icon_mob')?> -->
				<button class="header-btn__wrap header-btn__wrap_mob">
					<img class="header-btn__icon" src="<?php echo $menu_icon_mob ?>" alt="menu" width="21" height="21">		
				</button>
			</div>
		</div>
		<div class="header__notice-wrap_mob">
			<?php get_template_part( 'template-parts/header-notice' );?>
		</div>

		<!-- <div class="header__breadcrumb container"><?php //woocommerce_breadcrumb() ?></div> -->
		
		<div class="header__nav-wrap container">
			<div class="header__phone">
				<?php $phone_icon = carbon_get_theme_option('phone_icon')?>
					<a href="tel:+79647687944" class="header-btn__wrap">	
						<?php echo $phone_icon ?>
						<span class="header-btn__label">Позвонить</span>	
					</a>
				</a>
			</div>

			<div class="header__catalog_mob">
				<?php $catalog_icon = carbon_get_theme_option('catalog_icon')?>
					<div class="header-btn__wrap">	
						<?php echo $catalog_icon ?>	
						<span class="header-btn__label">Каталог</span>		
					</div>
				</a>
			</div>

			<div class="header__wishlist">
				<?php echo do_shortcode('[yith_wcwl_items_count]')?>
			</div>
			
			<div class="header-cart">
				<?php 
					plnt_woocommerce_cart_header_mob(); 
				?>

			</div><!-- .header-cart -->
		</div>
	</header><!-- #header -->
	
